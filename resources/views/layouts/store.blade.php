<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Synapse')</title>
  <link rel="icon" type="image/png" href="{{ asset('assets/favicon.png') }}">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <!-- Scripts & Styles -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 flex flex-col min-h-screen">
  
  <x-store-header />

  <main class="flex-grow pt-20 md:pt-24">
    @yield('content')
  </main>

  <x-store-footer />

  <x-toast />

  @stack('scripts')
</body>

</html>
