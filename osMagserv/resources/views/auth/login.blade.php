<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Painel MAGSERV</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-gray-50">
    <section class="bg-gray-50">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            <!-- Logo and Title -->
            <div class="w-full bg-white rounded-lg shadow border md:mt-0 sm:max-w-md xl:p-0 border-gray-200">
                <div class="p-6 space-y-6 sm:p-8">
                    <form class="space-y-6" action="{{ route('login') }}" method="POST">
                        @csrf

                        @error('email')
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
                                <p>{{ $message }}</p>
                            </div>
                        @enderror

                        <div class="mb-4 relative">
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900">E-mail</label>
                            <span class="absolute left-3 bottom-3 text-gray-400">
                                <i class="bi bi-envelope-fill"></i>
                            </span>
                            <input type="email" id="email" name="email"
                               class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5"
                                placeholder="seu@email.com" required value="{{ old('email') }}">
                        </div>

                        <div class="mb-6 relative">
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Senha</label>
                            <span class="absolute left-3 bottom-3 text-gray-400">
                                <i class="bi bi-lock-fill"></i>
                            </span>
                            <input type="password" id="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5" required>
                        </div>

                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <input id="remember" name="remember" type="checkbox"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600">
                                <label for="remember" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Lembrar-me</label>
                            </div>
                            <a href="#" class="text-sm text-blue-600 hover:underline dark:text-blue-500">Esqueceu a senha?</a>
                        </div>

                        <button type="submit"
                                class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Entrar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
   
</body>
</html>