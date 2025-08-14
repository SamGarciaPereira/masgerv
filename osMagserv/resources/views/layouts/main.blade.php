<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white font-inter">
      <span id="open-sidebar-btn" class="absolute text-white text-4xl top-5 left-4 cursor-pointer">
    <i class="bi bi-filter-left px-2 bg-gray-900 rounded-md"></i>
  </span>
  <div class="sidebar fixed top-0 bottom-0 lg:left-0 p-2 w-[300px] h-screen bg-gray-900 shadow 
        -translate-x-full transition-transform duration-300 ease-in-out">
    <div class="text-gray-100 text-xl">
      <div class="p-2.5 mt-1 flex items-center rounded-md ">
        <i class="bi bi-app-indicator px-2 py-1 bg-blue-600 rounded-md"></i>
        <h1 class="text-[15px]  ml-3 text-xl text-gray-200 font-bold">Magserv</h1>
        <i id="close-sidebar-btn" class="bi bi-x ml-20 cursor-pointer lg:hidden"></i>
      </div>
      <hr class="my-2 text-gray-600">

      <div>
        <div class="p-2.5 mt-3 flex items-center rounded-md 
        px-4 duration-300 cursor-pointer  bg-gray-700">
          <i class="bi bi-search text-sm"></i>
          <input class="text-[15px] ml-4 w-full bg-transparent focus:outline-none" placeholder="Pesquisar"/>
        </div>

        <a href="{{ route('home') }}" class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-blue-600">
          <i class="bi bi-house-door-fill"></i>
          <span class="text-[15px] ml-4 text-gray-200">Menu</span>
        </a>
        <a href="{{ route('processo') }}"  class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-blue-600">
          <i class="bi bi-house-door-fill"></i>
          <span class="text-[15px] ml-4 text-gray-200">Processos</span>
        </a>
        <a href="{{ route('orcamentos') }}" class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-blue-600">
          <i class="bi bi-clipboard2-fill"></i>
          <span class="text-[15px] ml-4 text-gray-200">Orçamentos</span>
        </a>
        <a href="{{ route('manutencao') }}" class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-blue-600">
          <i class="bi bi-envelope-fill"></i>
          <span class="text-[15px] ml-4 text-gray-200">Manutenção</span>
        </a>
        <div class="p-2.5 mt-2 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-blue-600">
          <i class="bi bi-piggy-bank-fill"></i>
          <div id="dropdown-btn" class="flex justify-between w-full items-center">
            <span class="text-[15px] ml-4 text-gray-200">Financeiro</span>
            <span class="text-sm rotate-180" id="arrow">
              <i class="bi bi-chevron-down"></i>
            </span>
          </div>
        </div>
        <div class=" leading-7 text-left text-sm font-thin mt-2 w-4/5 mx-auto" id="submenu">
          <a href="{{ route('financeiro.contas-pagar') }}" class="cursor-pointer p-2 hover:bg-gray-700 rounded-md mt-1">Contas a pagar</a>
          <a href="{{ route('financeiro.contas-receber') }}" class="cursor-pointer p-2 hover:bg-gray-700 rounded-md mt-1">Contas a receber</a>
        </div>
        <div class="p-2.5 mt-3 flex items-center rounded-md px-4 duration-300 cursor-pointer  hover:bg-blue-600">
          <i class="bi bi-box-arrow-in-right"></i>
          <span class="text-[15px] ml-4 text-gray-200">Logout</span>
        </div>

      </div>
    </div>
  </div>
    @yield('content')
<footer>
    <p>Magserv Manutenção e Serviços ltda. &copy; 2025 | Samuel Software Developer </p>
</footer>
</body>
</html> 