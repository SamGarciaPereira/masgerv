<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\Cliente;
use App\Models\Manutencao;
use App\Models\Orcamento;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

// Helper: Envia mensagem de volta para o usuário via Evolution API
function sendWhatsappMessage(string $instance, string $number, string $text, string $apiKey) {
    $evolutionApiUrl = 'http://localhost:8081';
    Http::withHeaders(['apiKey' => $apiKey])->post(
        "{$evolutionApiUrl}/message/sendText/{$instance}",
        [
            "number" => $number,
            "options" => ["delay" => 1200, "presence" => "composing"],
            "text" => $text
        ]
    );
}

// Helper: Envia o menu principal de serviços (após o login)
function sendMainMenu(string $instance, string $sender, string $apiKey, string $clientName) {
    $menu = "Olá, *{$clientName}*!\n\nComo podemos te ajudar?\n\n*1)* Solicitação de orçamento\n*2)* Abertura de chamado de manutenção corretiva\n\nDigite 'cancelar' a qualquer momento para reiniciar.";
    sendWhatsappMessage($instance, $sender, $menu, $apiKey);
}

// --- ROTA PRINCIPAL DO WEBHOOK ---
Route::post('/webhook', function (Request $request) {
    $apiKey = '8nQHBRTKUvTxQAj1t1XhFqW7JbGjJ4aH'; // Sua chave da Evolution API

    // Ignora webhooks que não são mensagens de texto ou são do próprio bot
    if (!$request->has('data.message.conversation')) {
        return response()->json(['status' => 'ok', 'message' => 'Not a text message, ignored.']);
    }
    $data = $request->input('data');
    if ($data['key']['fromMe'] === true) {
        return response()->json(['status' => 'ok', 'message' => 'Message from self, ignored.']);
    }

    $instanceName = $request->input('instance');
    $sender = $data['key']['remoteJid'];
    $message = trim($request->input('data.message.conversation'));

    // Lógica global de cancelamento
    if (strtolower($message) === 'cancelar') {
        Cache::forget('conversation_' . $sender); // Limpa o estado da conversa
        sendWhatsappMessage($instanceName, $sender, "Atendimento cancelado. Para começar de novo, envie qualquer mensagem.", $apiKey);
        return response()->json(['status' => 'ok']);
    }
    
    // --- Controle da Máquina de Estados ---
    $conversation = Cache::get('conversation_' . $sender);
    $state = $conversation['state'] ?? null; // Pega o estado atual ou null (início)

    // Roteador de estados da conversa
    switch ($state) {
        
        // Estado: Aguardando escolha inicial (1-Cliente, 2-Não Cliente)
        case 'awaiting_client_type_choice':
            $choice = preg_replace('/[^1-2]/', '', $message);
            if ($choice == '1') { // Já é cliente
                $conversation['state'] = 'awaiting_existing_client_lookup';
                Cache::put('conversation_' . $sender, $conversation, now()->addMinutes(10));
                sendWhatsappMessage($instanceName, $sender, "Entendido. Por favor, informe seu *CNPJ ou CPF* para localizarmos seu cadastro:", $apiKey);
            } elseif ($choice == '2') { // Não é cliente
                $conversation['state'] = 'register_awaiting_name';
                Cache::put('conversation_' . $sender, $conversation, now()->addMinutes(10));
                sendWhatsappMessage($instanceName, $sender, "Vamos iniciar seu cadastro. Por favor, informe o *nome da empresa* (Razão Social):", $apiKey);
            } else {
                sendWhatsappMessage($instanceName, $sender, "Opção inválida. Digite *1* (Já sou cliente) ou *2* (Ainda não sou cliente).", $apiKey);
                Cache::put('conversation_' . $sender, $conversation, now()->addMinutes(10));
            }
            break;

        // Estado: Busca cliente por CNPJ/CPF
        case 'awaiting_existing_client_lookup':
            $cleanedDocumento = preg_replace('/[^0-9]/', '', $message);
            $cliente = Cliente::whereRaw('REGEXP_REPLACE(documento, "[^0-9]", "") = ?', [$cleanedDocumento])->first();
            
            if ($cliente) {
                // Cliente encontrado, "loga" e vai para o menu de serviços
                $conversation['data']['cliente_id'] = $cliente->id;
                $conversation['data']['cliente_nome'] = $cliente->nome;
                $conversation['state'] = 'awaiting_main_menu_choice';
                Cache::put('conversation_' . $sender, $conversation, now()->addMinutes(10));
                sendMainMenu($instanceName, $sender, $apiKey, $cliente->nome);
            } else {
                // Cliente não encontrado, inicia fluxo de cadastro
                $conversation['state'] = 'register_awaiting_name'; 
                Cache::put('conversation_' . $sender, $conversation, now()->addMinutes(10));
                sendWhatsappMessage($instanceName, $sender, "Não localizamos seu cadastro. Vamos iniciar um novo.\n\nPor favor, informe o *nome da empresa* (Razão Social):", $apiKey);
            }
            break;

        // --- Início do Fluxo de Cadastro ---
        case 'register_awaiting_name':
            $conversation['data']['nome'] = $message;
            $conversation['state'] = 'register_awaiting_responsavel_name';
            Cache::put('conversation_' . $sender, $conversation, now()->addMinutes(10));
            sendWhatsappMessage($instanceName, $sender, "Obrigado. Agora, informe o *nome do responsável* pela empresa:", $apiKey);
            break;

        case 'register_awaiting_responsavel_name':
            $conversation['data']['responsavel'] = $message;
            $conversation['state'] = 'register_awaiting_responsavel_telefone';
            Cache::put('conversation_' . $sender, $conversation, now()->addMinutes(10));
            sendWhatsappMessage($instanceName, $sender, "Qual o *telefone do responsável* (com DDD)?", $apiKey);
            break;
        
        case 'register_awaiting_responsavel_telefone':
             $conversation['data']['telefone_responsavel'] = $message;
             $conversation['state'] = 'register_awaiting_email';
             Cache::put('conversation_' . $sender, $conversation, now()->addMinutes(10));
             sendWhatsappMessage($instanceName, $sender, "Qual o *e-mail* principal para contato?", $apiKey);
             break;

        case 'register_awaiting_email':
            if (!filter_var($message, FILTER_VALIDATE_EMAIL)) {
                 sendWhatsappMessage($instanceName, $sender, "E-mail inválido. Por favor, insira um e-mail válido:", $apiKey);
                 Cache::put('conversation_' . $sender, $conversation, now()->addMinutes(10));
                 break; 
            }
            $conversation['data']['email'] = $message;
            $conversation['state'] = 'register_awaiting_document';
            Cache::put('conversation_' . $sender, $conversation, now()->addMinutes(10));
            sendWhatsappMessage($instanceName, $sender, "Para finalizar, qual o *CNPJ ou CPF* da empresa?", $apiKey);
            break;

        // Estado: Salva o novo cliente no DB
        case 'register_awaiting_document':
            $conversation['data']['documento'] = preg_replace('/[^0-9]/', '', $message);
            
            try {
                $cliente = Cliente::create([
                    'nome' => $conversation['data']['nome'],
                    'responsavel' => $conversation['data']['responsavel'],
                    'telefone_responsavel' => $conversation['data']['telefone_responsavel'],
                    'email' => $conversation['data']['email'],
                    'documento' => $conversation['data']['documento'],
                    'telefone' => preg_replace('/@s\.whatsapp\.net$/', '', $sender),
                ]);

                // "Loga" o cliente e vai para o menu de serviços
                $conversation['data']['cliente_id'] = $cliente->id;
                $conversation['data']['cliente_nome'] = $cliente->nome;
                $conversation['state'] = 'awaiting_main_menu_choice';
                Cache::put('conversation_' . $sender, $conversation, now()->addMinutes(10));
                
                Log::info('Novo cliente cadastrado via WhatsApp:', $cliente->toArray());
                sendWhatsappMessage($instanceName, $sender, "Cadastro realizado com sucesso!", $apiKey);
                sendMainMenu($instanceName, $sender, $apiKey, $cliente->nome);

            } catch (\Illuminate\Database\QueryException $e) {
                if ($e->errorInfo[1] == 1062) { // Erro de duplicidade (CNPJ/Email)
                    sendWhatsappMessage($instanceName, $sender, "Este CNPJ/CPF ou E-mail já está cadastrado. Por favor, digite 'cancelar' e reinicie a conversa selecionando 'Já sou cliente'.", $apiKey);
                } else {
                    Log::error("Erro ao cadastrar cliente via WhatsApp: " . $e->getMessage(), $conversation['data']);
                    sendWhatsappMessage($instanceName, $sender, "❌ Ocorreu um erro ao registrar seu cadastro. Por favor, tente novamente.", $apiKey);
                }
                Cache::forget('conversation_' . $sender);
            }
            break;

        // --- NÍVEL 2: Menu de Serviços (Pós-Login) ---
        case 'awaiting_main_menu_choice':
            $choice = preg_replace('/[^1-2]/', '', $message);
            if ($choice == '1') { // 1. Orçamento
                $conversation['state'] = 'orcamento_awaiting_description';
                Cache::put('conversation_' . $sender, $conversation, now()->addMinutes(10));
                sendWhatsappMessage($instanceName, $sender, "Você selecionou *Solicitação de Orçamento*.\n\nPor favor, *descreva sua solicitação*:", $apiKey);
            
            } elseif ($choice == '2') { // 2. Manutenção
                $conversation['state'] = 'manutencao_awaiting_area'; 
                Cache::put('conversation_' . $sender, $conversation, now()->addMinutes(10));
                sendWhatsappMessage($instanceName, $sender, "Você selecionou *Abertura de Chamado Corretivo*.\n\nPor favor, *selecione a área de atuação* do problema:\n1- Civil\n2- Hidráulica\n3- Elétrica", $apiKey);
            
            } else {
                sendWhatsappMessage($instanceName, $sender, "Opção inválida. Digite *1* (Orçamento) ou *2* (Manutenção).", $apiKey);
                Cache::put('conversation_' . $sender, $conversation, now()->addMinutes(10));
            }
            break;
        
        // Estado: Salva Orçamento
        case 'orcamento_awaiting_description':
            $conversation['data']['escopo'] = $message;
            try {
                $orcamento = Orcamento::create([
                    'cliente_id' => $conversation['data']['cliente_id'],
                    'escopo' => $conversation['data']['escopo'],
                    'status' => 'Pendente',
                ]);
                Log::info('Nova solicitação de orçamento criada via WhatsApp:', $orcamento->toArray());

                $successMessage = "✅ Solicitação de orçamento registrada com sucesso para *{$conversation['data']['cliente_nome']}*!\n\n";
                $successMessage .= "*Descrição:* {$conversation['data']['escopo']}\n\n";
                $successMessage .= "Entraremos em contato em breve. FIM.";

                sendWhatsappMessage($instanceName, $sender, $successMessage, $apiKey);
                Cache::forget('conversation_' . $sender);

            } catch (\Exception $e) {
                Log::error("Erro ao criar orçamento via WhatsApp: " . $e->getMessage(), $conversation['data']);
                sendWhatsappMessage($instanceName, $sender, "❌ Ocorreu um erro ao registrar sua solicitação.", $apiKey);
                Cache::forget('conversation_' . $sender);
            }
            break;

        // --- Início do Fluxo de Manutenção ---
        case 'manutencao_awaiting_area':
            $areas = ['1' => 'Civil', '2' => 'Hidráulica', '3' => 'Elétrica'];
            $choice = preg_replace('/[^1-3]/', '', $message);

            if (array_key_exists($choice, $areas)) {
                $conversation['data']['area'] = $areas[$choice];
                $conversation['state'] = 'manutencao_awaiting_requester';
                Cache::put('conversation_' . $sender, $conversation, now()->addMinutes(10));
                sendWhatsappMessage($instanceName, $sender, "Entendido. Agora, por favor, informe o *nome do solicitante*:", $apiKey);
            } else {
                sendWhatsappMessage($instanceName, $sender, "Opção inválida. Por favor, digite 1, 2 ou 3 para a área.", $apiKey);
                Cache::put('conversation_' . $sender, $conversation, now()->addMinutes(10));
            }
            break;

        case 'manutencao_awaiting_requester':
            $conversation['data']['solicitante'] = $message;
            $conversation['state'] = 'manutencao_awaiting_description';
            Cache::put('conversation_' . $sender, $conversation, now()->addMinutes(10)); 
            sendWhatsappMessage($instanceName, $sender, "Obrigado, {$message}.\n\nPara finalizar, *descreva o problema* que você está enfrentando:", $apiKey);
            break;
            
        // Estado: Salva Manutenção
        case 'manutencao_awaiting_description':
            $conversation['data']['descricao'] = $message;
            
            try {
                $manutencao = Manutencao::create([
                    'cliente_id' => $conversation['data']['cliente_id'],
                    'descricao' => $conversation['data']['descricao'],
                    'solicitante' => $conversation['data']['solicitante'],
                    'area' => $conversation['data']['area'],
                    'tipo' => 'Corretiva',
                    'status' => 'Agendada',
                    'data_inicio_atendimento' => Carbon::now(),
                ]);

                Log::info('Novo chamado de manutenção corretiva criado via WhatsApp:', $manutencao->toArray());

                $successMessage = "✅ Chamado de manutenção corretiva registrado com sucesso para *{$conversation['data']['cliente_nome']}*!\n\n";
                $successMessage .= "*Área:* {$conversation['data']['area']}\n";
                $successMessage .= "*Solicitante:* {$conversation['data']['solicitante']}\n";
                $successMessage .= "*Problema:* {$conversation['data']['descricao']}\n\n";
                $successMessage .= "Entraremos em contato em breve. FIM.";

                sendWhatsappMessage($instanceName, $sender, $successMessage, $apiKey);
                Cache::forget('conversation_' . $sender);

            } catch (\Exception $e) {
                Log::error("Erro ao criar manutenção via WhatsApp: " . $e->getMessage(), $conversation['data']);
                sendWhatsappMessage($instanceName, $sender, "❌ Ocorreu um erro ao registrar seu chamado.", $apiKey);
                Cache::forget('conversation_' . $sender);
            }
            break;
            
        // Estado Padrão: Início da conversa
        default:
            $menu = "Bem vindo ao autoatendimento da MAGSERV!\n\nVocê já é nosso cliente?\n\n*1)* Sim, já sou cliente\n*2)* Não, ainda não sou cliente\n\nDigite 'cancelar' a qualquer momento para reiniciar.";
            sendWhatsappMessage($instanceName, $sender, $menu, $apiKey);
            Cache::put('conversation_' . $sender, ['state' => 'awaiting_client_type_choice', 'data' => []], now()->addMinutes(10));
            break;
    }

    // Responde 200 OK para a API da Evolution
    return response()->json(['status' => 'ok']);
});