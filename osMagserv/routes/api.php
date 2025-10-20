<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\Cliente;
use Illuminate\Support\Facades\Log; // Usaremos para salvar os dados no final

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
    $apiKey = '8nQHBRTKUvTxQAj1t1XhFqW7JbGjJ4aH';

    // Validações iniciais
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

    // ***** LÓGICA DE CANCELAMENTO *****
    if (strtolower($message) === 'cancelar') {
        Cache::forget('conversation_' . $sender);
        sendWhatsappMessage($instanceName, $sender, "Atendimento cancelado. Para começar de novo, envie qualquer mensagem.", $apiKey);
        return response()->json(['status' => 'ok']);
    }
    
    // ***** NOVA MÁQUINA DE ESTADOS *****
    $conversation = Cache::get('conversation_' . $sender);
    $state = $conversation['state'] ?? null;

    switch ($state) {
        // --- FLUXO DE ORÇAMENTO ---
        case 'awaiting_main_menu_choice':
            handleMainMenuChoice($instanceName, $sender, $message, $apiKey);
            break;

        case 'orcamento_awaiting_name':
            $conversation['data']['name'] = $message;
            $conversation['state'] = 'orcamento_awaiting_email';
            Cache::put('conversation_' . $sender, $conversation, now()->addMinutes(10));
            sendWhatsappMessage($instanceName, $sender, "Ótimo. Agora, por favor, insira um e-mail para contato:", $apiKey);
            break;

        case 'orcamento_awaiting_email':
            $conversation['data']['email'] = $message;
            $conversation['state'] = 'orcamento_awaiting_description';
            Cache::put('conversation_' . $sender, $conversation, now()->addMinutes(10));
            sendWhatsappMessage($instanceName, $sender, "Perfeito. Descreva resumidamente a sua solicitação:", $apiKey);
            break;

        case 'orcamento_awaiting_description':
            $conversation['data']['description'] = $message;
            
            // TODO: Aqui você salvaria os dados no banco de dados.
            // Ex: Orcamento::create($conversation['data']);
            Log::info('Nova solicitação de orçamento recebida:', $conversation['data']);

            sendWhatsappMessage($instanceName, $sender, "✅ Solicitação de orçamento registrada com sucesso! Entraremos em contato em breve. FIM.", $apiKey);
            Cache::forget('conversation_' . $sender); // Limpa o estado
            break;

        // --- FLUXO DE MANUTENÇÃO ---
        case 'manutencao_awaiting_cnpj':
            $cleanedDocumento = preg_replace('/[^0-9]/', '', $message);
            $cliente = Cliente::whereRaw('REGEXP_REPLACE(documento, "[^0-9]", "") = ?', [$cleanedDocumento])->first();
            
            if ($cliente) {
                $conversation['data']['cliente_id'] = $cliente->id;
                $conversation['state'] = 'manutencao_awaiting_area';
                Cache::put('conversation_' . $sender, $conversation, now()->addMinutes(10));
                sendWhatsappMessage($instanceName, $sender, "Bem vindo, {$cliente->nome}!\n\n*Selecione a área de atuação:*\n1- Civil\n2- Hidráulica\n3- Elétrica", $apiKey);
            } else {
                sendWhatsappMessage($instanceName, $sender, "CNPJ não encontrado. Por favor, tente novamente ou digite 'cancelar' para voltar ao menu.", $apiKey);
            }
            break;
            
        case 'manutencao_awaiting_area':
            $areas = ['1' => 'Civil', '2' => 'Hidráulica', '3' => 'Elétrica'];
            $choice = preg_replace('/[^1-3]/', '', $message);

            if (array_key_exists($choice, $areas)) {
                $conversation['data']['area'] = $areas[$choice];
                
                // TODO: Salvar o chamado de manutenção no banco de dados.
                Log::info('Novo chamado de manutenção:', $conversation['data']);

                sendWhatsappMessage($instanceName, $sender, "✅ Chamado de manutenção para a área *{$areas[$choice]}* registrado com sucesso! FIM.", $apiKey);
                Cache::forget('conversation_' . $sender); // Limpa o estado
            } else {
                sendWhatsappMessage($instanceName, $sender, "Opção inválida. Por favor, digite 1, 2 ou 3.", $apiKey);
            }
            break;
            
        // --- CASO PADRÃO: INÍCIO DA CONVERSA ---
        default:
            $menu = "Bem vindo ao autoatendimento da MAGSERV!\n\n*Selecione uma opção de atendimento:*\n\n*1)* Solicitação de orçamento\n*2)* Abertura de chamado de manutenção\n*3)* Financeiro\n\nDigite 'cancelar' a qualquer momento para reiniciar.";
            sendWhatsappMessage($instanceName, $sender, $menu, $apiKey);
            Cache::put('conversation_' . $sender, ['state' => 'awaiting_main_menu_choice', 'data' => []], now()->addMinutes(10));
            break;
    }

    return response()->json(['status' => 'ok']);
});

// --- Nova Função para tratar a escolha do menu principal ---
function handleMainMenuChoice(string $instance, string $sender, string $message, string $apiKey) {
    $choice = preg_replace('/[^1-3]/', '', $message);
    $conversation = Cache::get('conversation_' . $sender);

    switch ($choice) {
        case '1': // Orçamento
            $conversation['state'] = 'orcamento_awaiting_name';
            Cache::put('conversation_' . $sender, $conversation, now()->addMinutes(10));
            sendWhatsappMessage($instanceName, $sender, "Você selecionou *Solicitação de Orçamento*.\n\nPara começar, qual o seu nome ou razão social?", $apiKey);
            break;
        case '2': // Manutenção
            $conversation['state'] = 'manutencao_awaiting_cnpj';
            Cache::put('conversation_' . $sender, $conversation, now()->addMinutes(10));
            sendWhatsappMessage($instanceName, $sender, "Você selecionou *Abertura de Chamado*.\n\nPor favor, insira o seu CNPJ:", $apiKey);
            break;
        case '3': // Financeiro
            // TODO: Implementar o fluxo do financeiro
            sendWhatsappMessage($instanceName, $sender, "O módulo financeiro ainda está em desenvolvimento. Por favor, escolha outra opção.", $apiKey);
            // Mantém o estado para que o usuário possa escolher novamente.
            Cache::put('conversation_' . $sender, $conversation, now()->addMinutes(10));
            break;
        default:
            sendWhatsappMessage($instanceName, $sender, "Opção inválida. Por favor, digite 1, 2 ou 3.", $apiKey);
            break;
    }
}