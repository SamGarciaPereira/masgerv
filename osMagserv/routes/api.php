<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache; // Cache foi reintroduzido
use App\Models\Cliente;

// --- Helper Function para Enviar Mensagens ---
function sendWhatsappMessage(string $instance, string $number, string $text, string $apiKey) {
    $evolutionApiUrl = 'http://localhost:8081';
    Http::withHeaders(['apiKey' => $apiKey])->post(
        "{$evolutionApiUrl}/message/sendText/{$instance}",
        [
            "number" => $number,
            "options" => [
                "delay" => 1200,
                "presence" => "composing"
            ],
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
    $message = trim($data['message']['conversation']);

    // --- Lógica Principal com Reinicialização ---
    $state = Cache::get('state_' . $sender);

    if ($state === 'awaiting_documento') {
        $cleanedDocumento = preg_replace('/[^0-9]/', '', $message);
        $cliente = Cliente::where('documento', $cleanedDocumento)->first();

        if ($cliente) {
            // Sucesso: envia boas-vindas e limpa o estado para futuras conversas
            $responseText = "Bem vindo, {$cliente->nome}!";
            sendWhatsappMessage($instanceName, $sender, $responseText, $apiKey);
            Cache::forget('state_' . $sender); // Finaliza o fluxo atual
        } else {
            // Falha: informa o erro e pede para tentar novamente, mantendo o estado
            sendWhatsappMessage($instanceName, $sender, "Cliente não cadastrado no sistema.", $apiKey);
            sendWhatsappMessage($instanceName, $sender, "Por favor, tente novamente com um CNPJ válido (SOMENTE NÚMEROS).", $apiKey);
            // O estado 'awaiting_documento' é mantido para a próxima mensagem
        }
    } else {
        // Início da conversa: Pede o documento pela primeira vez e define o estado
        sendWhatsappMessage($instanceName, $sender, "Olá! Bem-vindo ao nosso atendimento.", $apiKey);
        sendWhatsappMessage($instanceName, $sender, "Por favor, digite o seu CNPJ para continuar (SOMENTE NÚMEROS).", $apiKey);
        Cache::put('state_' . $sender, 'awaiting_documento', now()->addMinutes(5));
    }

    return response()->json(['status' => 'ok']);
});