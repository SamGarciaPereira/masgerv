    @extends('layouts.main')

@section('content')

<!-- Conteúdo do Formulário de Edição -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Editar Cliente</h1>
        <p class="text-gray-600 mt-1">Altere os dados do cliente "{{ $cliente->nome }}".</p>
    </div>
    <a href="{{ route('clientes.index') }}" class="bg-gray-200 text-gray-700 hover:bg-gray-300 font-medium py-2 px-4 rounded-lg">
        Voltar para a Lista
    </a>
</div>

<div class="bg-white p-8 rounded-lg shadow-md">
    <form action="{{ route('clientes.update', $cliente->id) }}" method="POST">
        @csrf
        @method('PUT') <!-- Importante para indicar que é uma atualização -->
        
        <h2 class="text-xl font-semibold text-gray-800 border-b pb-4 mb-6">Informações Pessoais</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">Nome Completo / Razão Social</label>
                <input type="text" id="nome" name="nome" value="{{ $cliente->nome }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label for="documento" class="block text-sm font-medium text-gray-700 mb-2">CPF / CNPJ</label>
                <input type="text" id="documento" name="documento" value="{{ $cliente->documento }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="responsavel" class="block text-sm font-medium text-gray-700 mb-2">Responsável</label>
                <input type="text" id="responsavel" name="responsavel" value="{{ $cliente->responsavel }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">E-mail</label>
                <input type="email" id="email" name="email" value="{{ $cliente->email }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label for="telefone" class="block text-sm font-medium text-gray-700 mb-2">Telefone</label>
                <input type="tel" id="telefone" name="telefone" value="{{ $cliente->telefone }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>

        <h2 class="text-xl font-semibold text-gray-800 border-b pb-4 mt-10 mb-6">Endereço</h2>
        <div class="grid grid-cols-1 md:grid-cols-6 gap-6">
            <div class="md:col-span-2">
                <label for="cep" class="block text-sm font-medium text-gray-700 mb-2">CEP</label>
                <input type="text" id="cep" name="cep" value="{{ $cliente->cep }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="md:col-span-4">
                <label for="logradouro" class="block text-sm font-medium text-gray-700 mb-2">Logradouro</label>
                <input type="text" id="logradouro" name="logradouro" value="{{ $cliente->logradouro }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="md:col-span-2">
                <label for="numero" class="block text-sm font-medium text-gray-700 mb-2">Número</label>
                <input type="text" id="numero" name="numero" value="{{ $cliente->numero }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="md:col-span-4">
                <label for="bairro" class="block text-sm font-medium text-gray-700 mb-2">Bairro</label>
                <input type="text" id="bairro" name="bairro" value="{{ $cliente->bairro }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="md:col-span-3">
                <label for="cidade" class="block text-sm font-medium text-gray-700 mb-2">Cidade</label>
                <input type="text" id="cidade" name="cidade" value="{{ $cliente->cidade }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="md:col-span-3">
                <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                <input type="text" id="estado" name="estado" value="{{ $cliente->estado }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>

        <div class="flex justify-end mt-10 pt-6 border-t">
            <a href="{{ route('clientes.index') }}" class="bg-gray-200 text-gray-700 hover:bg-gray-300 font-medium py-2 px-6 rounded-lg mr-4">
                Cancelar
            </a>
            <button type="submit" class="bg-blue-600 text-white hover:bg-blue-700 font-medium py-2 px-6 rounded-lg">
                Atualizar Cliente
            </button>
        </div>
    </form>
</div>

@endsection
