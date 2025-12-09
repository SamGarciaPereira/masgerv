<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magserv | Login</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    
    @vite('resources/css/app.css')
    
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50">
    <section class="bg-gray-50">
        <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
            
            <a href="#" class="flex flex-col items-center mb-6 text-2xl font-semibold text-gray-900">
                <img class="h-20 mb-2" src="{{ asset('img/magservLogo.png') }}" alt="Logo Magserv">
            </a>

            <div class="w-full bg-white rounded-lg shadow border md:mt-0 sm:max-w-md xl:p-0 border-gray-200">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl text-center">
                        Entre na sua conta
                    </h1>

                    <form class="space-y-4 md:space-y-6" action="{{ route('login') }}" method="POST">
                        @csrf

                        @if ($errors->any())
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md" role="alert">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900">E-mail</label>
                            <input type="email" name="email" id="email" 
                                   value="{{ old('email') }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                                   placeholder="seu@email.com" required>
                        </div>

                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Senha</label>
                            <input type="password" name="password" id="password" placeholder="Insira sua senha" 
                                   class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                                   required>
                        </div>
                        <button type="submit" class="w-full text-white bg-[#024073] hover:bg-[#023055] focus:ring-4 focus:outline-none focus:ring-orange-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition duration-200">
                            Entrar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>