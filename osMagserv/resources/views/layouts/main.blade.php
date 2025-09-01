<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Magserv')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body class="bg-gray-100 font-inter">

    <div id="sidebar" class="fixed top-0 left-0 h-screen bg-gray-900 text-white p-4 flex flex-col z-50 w-20">
        <div class="flex items-center justify-between pb-4 border-b border-gray-700">
            <div class="flex items-center min-w-0">
                <i class="bi bi-app-indicator text-2xl text-blue-500"></i>
                <h1 class="sidebar-text text-xl font-bold ml-3 w-0 opacity-0">Magserv</h1>
            </div>
            <button id="sidebar-toggle" class="text-gray-400 hover:text-white focus:outline-none">
              <i class="bi bi-list text-2xl"></i>
            </button>
        </div>

        <nav class="flex-grow mt-4 space-y-2">
            <div class="relative">
                <div class="p-2.5 flex items-center rounded-md bg-gray-700">
                    <i class="bi bi-search text-sm text-gray-400"></i>
                    <input type="text" class="sidebar-text text-sm ml-4 w-0 opacity-0 bg-transparent focus:outline-none" placeholder="Pesquisar"/>
                </div>
            </div>

            <a href="{{ route('home') }}" class="p-2.5 flex items-center rounded-md hover:bg-blue-600 group">
                <i class="bi bi-house-door-fill text-lg"></i>
                <span class="sidebar-text text-sm font-medium ml-4 w-0 opacity-0">Menu</span>
            </a>
            <a href="{{ route('processo') }}" class="p-2.5 flex items-center rounded-md hover:bg-blue-600 group">
                <i class="bi bi-inboxes-fill text-lg"></i>
                <span class="sidebar-text text-sm font-medium ml-4 w-0 opacity-0">Processos</span>
            </a>
            <a href="{{ route('orcamentos') }}" class="p-2.5 flex items-center rounded-md hover:bg-blue-600 group">
                <i class="bi bi-file-earmark-ruled-fill text-lg"></i>
                <span class="sidebar-text text-sm font-medium ml-4 w-0 opacity-0">Orçamentos</span>
            </a>
            <a href="manutencao" class="p-2.5 flex items-center rounded-md hover:bg-blue-600 group">
                <i class="bi bi-hammer text-lg"></i>
                <span class="sidebar-text text-sm font-medium ml-4 w-0 opacity-0">Manutenção</span>
            </a> 
            <a href="{{ route('clientes.index') }}" class="p-2.5 flex items-center rounded-md hover:bg-blue-600 group">
                <i class="bi bi-people-fill text-lg"></i>
                <span class="sidebar-text text-sm font-medium ml-4 w-0 opacity-0">Clientes</span>
            </a>
            
            <div>
                <button id="dropdown-btn" class="w-full p-2.5 flex items-center justify-between rounded-md hover:bg-blue-600 group">
                    <div class="flex items-center">
                        <i class="bi bi-piggy-bank-fill text-lg"></i>
                        <span class="sidebar-text text-sm font-medium ml-4 w-0 opacity-0">Financeiro</span>
                    </div>
                    <i class="sidebar-text bi bi-chevron-down text-xs w-0 opacity-0" id="arrow"></i>
                </button>
                <div id="submenu" class="hidden flex-col mt-1 pl-10">
                    <a href="{{ route('financeiro.contas-pagar') }}" class="sidebar-text text-sm text-gray-300 p-2 rounded-md hover:bg-gray-700 w-full opacity-0">Contas a pagar</a>
                    <a href="{{ route('financeiro.contas-receber') }}" class="sidebar-text text-sm text-gray-300 p-2 rounded-md hover:bg-gray-700 w-full opacity-0">Contas a receber</a>
                </div>
            </div>
        </nav>

        <div class="pt-4 border-t border-gray-700">
            <a href="{{ route('login') }}" class="p-2.5 flex items-center rounded-md hover:bg-blue-600 group">
                <i class="bi bi-box-arrow-in-right text-lg"></i>
                <span class="sidebar-text text-sm font-medium ml-4 w-0 opacity-0">Logout</span>
            </a>
        </div>
    </div>

    <main id="main-content" class="w-full pl-20">
        <div class="p-8">
            @yield('content')
        </div>
        
        <footer class="text-center p-4 text-gray-500 text-sm">
            <p>Magserv Manutenção e Serviços LTDA &copy; 2025 | Samuel Software Developer</p>
        </footer>
    </main>

    <script src="{{ asset('js/script.js') }}"></script>
    @vite('resources/js/app.js')
</body>
</html>
