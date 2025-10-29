<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\Cliente;
use App\Models\Manutencao; // Model de Manutenção
use App\Models\Orcamento;  // <-- ADICIONADO: Model de Orçamento
use Carbon\Carbon;         // Para datas
use Illuminate\Support\Facades\Log;

// --- Helper Function para Enviar Mensagens (sem alterações) ---
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
// --- Fim da Helper Function ---


Route::post('/webhook', function (Request $request) {
    // Sua Chave API Global da Evolution
    $apiKey = '8nQHBRTKUvTxQAj1t1XhFqW7JbGjJ4aH'; 

    // Validações iniciais (sem alterações)
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

    // ***** LÓGICA DE CANCELAMENTO (sem alterações) *****
    if (strtolower($message) === 'cancelar') {
        Cache::forget('conversation_' . $sender);
        sendWhatsappMessage($instanceName, $sender, "Atendimento cancelado. Para começar de novo, envie qualquer mensagem.", $apiKey);
        return response()->json(['status' => 'ok']);
    }
    
    // ***** MÁQUINA DE ESTADOS *****
    $conversation = Cache::get('conversation_' . $sender);
    $state = $conversation['state'] ?? null;

    switch ($state) {
        // --- FLUXO DE ORÇAMENTO (MODIFICADO) ---
        case 'awaiting_main_menu_choice':
            handleMainMenuChoice($instanceName, $sender, $message, $apiKey); // Reutiliza a função
            break;

        case 'orcamento_awaiting_name':
            // Tenta encontrar um cliente existente pelo nome
            $clienteExistente = Cliente::where('nome', 'like', $message)->first();
            
            $conversation['data']['nome_solicitante'] = $message; // Guarda o nome informado
            $conversation['data']['cliente_id'] = $clienteExistente ? $clienteExistente->id : null; // Guarda ID se achou

            $conversation['state'] = 'orcamento_awaiting_email';
            Cache::put('conversation_' . $sender, $conversation, now()->addMinutes(10));
            sendWhatsappMessage($instanceName, $sender, "Ótimo. Agora, por favor, insira um e-mail para contato:", $apiKey);
            break;

        case 'orcamento_awaiting_email':
            // Validação básica de email
            if (!filter_var($message, FILTER_VALIDATE_EMAIL)) {
                 sendWhatsappMessage($instanceName, $sender, "E-mail inválido. Por favor, insira um e-mail válido:", $apiKey);
                 Cache::put('conversation_' . $sender, $conversation, now()->addMinutes(10)); // Mantém o estado
                 break; 
            }
            
            $conversation['data']['email_solicitante'] = $message;
            $conversation['state'] = 'orcamento_awaiting_description';
            Cache::put('conversation_' . $sender, $conversation, now()->addMinutes(10));
            sendWhatsappMessage($instanceName, $sender, "Perfeito. Descreva resumidamente a sua solicitação:", $apiKey);
            break;

        case 'orcamento_awaiting_description':
            $conversation['data']['escopo'] = $message; // Campo 'escopo'

            // *** Tenta salvar o orçamento no banco de dados ***
            try {
                $orcamento = Orcamento::create([
                    'cliente_id' => $conversation['data']['cliente_id'] ?? null,
                    'escopo' => $conversation['data']['escopo'],
                    'status' => 'Pendente', // Status inicial padrão
                ]);

                Log::info('Nova solicitação de orçamento criada via WhatsApp:', $orcamento->toArray());

                // Mensagem de sucesso detalhada
                $successMessage = "✅ Solicitação de orçamento registrada com sucesso!\n\n";
                $successMessage .= "*Solicitante:* {$conversation['data']['nome_solicitante']}\n";
                $successMessage .= "*E-mail:* {$conversation['data']['email_solicitante']}\n";
                $successMessage .= "*Descrição:* {$conversation['data']['escopo']}\n\n";
                $successMessage .= "Entraremos em contato em breve. FIM.";

                sendWhatsappMessage($instanceName, $sender, $successMessage, $apiKey);
                Cache::forget('conversation_' . $sender); // Limpa o estado

            } catch (\Exception $e) {
                Log::error("Erro ao criar orçamento via WhatsApp: " . $e->getMessage(), $conversation['data']);
                sendWhatsappMessage($instanceName, $sender, "❌ Ocorreu um erro ao registrar sua solicitação. Por favor, tente novamente mais tarde ou entre em contato por outro canal.", $apiKey);
                Cache::forget('conversation_' . $sender); // Limpa o estado mesmo em erro
            }
            break;

        // --- FLUXO DE MANUTENÇÃO (Lógica completa) ---
        case 'manutencao_awaiting_cnpj':
            $cleanedDocumento = preg_replace('/[^0-9]/', '', $message);
            $cliente = Cliente::whereRaw('REGEXP_REPLACE(documento, "[^0-9]", "") = ?', [$cleanedDocumento])->first();
            
            if ($cliente) {
                $conversation['data']['cliente_id'] = $cliente->id;
                $conversation['data']['cliente_nome'] = $cliente->nome; // Guarda o nome para a msg final
                $conversation['state'] = 'manutencao_awaiting_description'; 
                Cache::put('conversation_' . $sender, $conversation, now()->addMinutes(10));
                sendWhatsappMessage($instanceName, $sender, "Bem vindo, {$cliente->nome}!\n\nPor favor, *descreva o problema* que você está enfrentando:", $apiKey);
            } else {
                sendWhatsappMessage($instanceName, $sender, "CNPJ não encontrado. Por favor, tente novamente ou digite 'cancelar' para voltar ao menu.", $apiKey);
            }
            break;

        case 'manutencao_awaiting_description':
            $conversation['data']['descricao'] = $message;
            $conversation['state'] = 'manutencao_awaiting_requester'; // Próximo estado: solicitante
            Cache::put('conversation_' . $sender, $conversation, now()->addMinutes(10));
            sendWhatsappMessage($instanceName, $sender, "Entendido. Agora, por favor, informe o *nome do solicitante*:", $apiKey);
            break;

        case 'manutencao_awaiting_requester':
            $conversation['data']['solicitante'] = $message;
            $conversation['state'] = 'manutencao_awaiting_area'; // Próximo estado: área
            Cache::put('conversation_' . $sender, $conversation, now()->addMinutes(10));
            sendWhatsappMessage($instanceName, $sender, "Obrigado, {$message}.\n\nPara finalizar, *selecione a área de atuação* do problema:\n1- Civil\n2- Hidráulica\n3- Elétrica", $apiKey);
            break;
            
        case 'manutencao_awaiting_area':
            $areas = ['1' => 'Civil', '2' => 'Hidráulica', '3' => 'Elétrica'];
            $choice = preg_replace('/[^1-3]/', '', $message);

            if (array_key_exists($choice, $areas)) {
                $conversation['data']['area'] = $areas[$choice];
                
                // *** Salva a manutenção no banco de dados ***
                try {
                    $manutencao = Manutencao::create([
                        'cliente_id' => $conversation['data']['cliente_id'],
                        'descricao' => $conversation['data']['descricao'],
                        'solicitante' => $conversation['data']['solicitante'],
                        'area' => $conversation['data']['area'],
                        'tipo' => 'Corretiva', // Define o tipo como Corretiva
                        'status' => 'Agendada', // Status inicial
                        'data_inicio_atendimento' => Carbon::now(), // Data da solicitação
                    ]);

                    Log::info('Novo chamado de manutenção corretiva criado via WhatsApp:', $manutencao->toArray());

                    // Mensagem de sucesso detalhada
                    $successMessage = "✅ Chamado de manutenção corretiva registrado com sucesso para *{$conversation['data']['cliente_nome']}*!\n\n";
                    $successMessage .= "*Solicitante:* {$conversation['data']['solicitante']}\n";
                    $successMessage .= "*Área:* {$conversation['data']['area']}\n";
                    $successMessage .= "*Problema:* {$conversation['data']['descricao']}\n\n";
                    $successMessage .= "Entraremos em contato em breve. FIM.";

                    sendWhatsappMessage($instanceName, $sender, $successMessage, $apiKey);
                    Cache::forget('conversation_' . $sender); // Limpa o estado

                } catch (\Exception $e) {
                    Log::error("Erro ao criar manutenção via WhatsApp: " . $e->getMessage(), $conversation['data']);
                    sendWhatsappMessage($instanceName, $sender, "❌ Ocorreu um erro ao registrar seu chamado. Por favor, tente novamente mais tarde ou entre em contato por outro canal.", $apiKey);
                    Cache::forget('conversation_' . $sender); // Limpa o estado mesmo em erro
                }

            } else {
                sendWhatsappMessage($instanceName, $sender, "Opção inválida. Por favor, digite 1, 2 ou 3 para a área.", $apiKey);
            }
            break;
            
        // --- CASO PADRÃO: INÍCIO DA CONVERSA ---
        default:
            $menu = "Bem vindo ao autoatendimento da MAGSERV!\n\n*Selecione uma opção de atendimento:*\n\n*1)* Solicitação de orçamento\n*2)* Abertura de chamado de manutenção corretiva\n*3)* Financeiro\n\nDigite 'cancelar' a qualquer momento para reiniciar.";
            sendWhatsappMessage($instanceName, $sender, $menu, $apiKey);
            Cache::put('conversation_' . $sender, ['state' => 'awaiting_main_menu_choice', 'data' => []], now()->addMinutes(10));
            break;
    }

    return response()->json(['status' => 'ok']);
});


// --- Função para tratar a escolha do menu principal (CORRIGIDA) ---
// (Corrigido $instanceName para $instance, que é o nome do parâmetro da função)
function handleMainMenuChoice(string $instance, string $sender, string $message, string $apiKey) {
    $choice = preg_replace('/[^1-3]/', '', $message);
    $conversation = Cache::get('conversation_' . $sender);

    switch ($choice) {
        case '1': // Orçamento
            $conversation['state'] = 'orcamento_awaiting_name';
            Cache::put('conversation_' . $sender, $conversation, now()->addMinutes(10));
            sendWhatsappMessage($instance, $sender, "Você selecionou *Solicitação de Orçamento*.\n\nPara começar, qual o seu nome ou razão social?", $apiKey);
            break;
        case '2': // Manutenção
            $conversation['state'] = 'manutencao_awaiting_cnpj';
            Cache::put('conversation_' . $sender, $conversation, now()->addMinutes(10));
            sendWhatsappMessage($instance, $sender, "Você selecionou *Abertura de Chamado Corretivo*.\n\nPor favor, insira o *CNPJ* da empresa:", $apiKey);
            break;
        case '3': // Financeiro
            sendWhatsappMessage($instance, $sender, "O módulo financeiro ainda está em desenvolvimento. Por favor, escolha outra opção.", $apiKey);
            Cache::put('conversation_' . $sender, $conversation, now()->addMinutes(10)); // Mantém o estado
            break;
        default:
            sendWhatsappMessage($instance, $sender, "Opção inválida. Por favor, digite 1, 2 ou 3.", $apiKey);
            Cache::put('conversation_' . $sender, $conversation, now()->addMinutes(10)); // Mantém o estado
            break;
    }
}