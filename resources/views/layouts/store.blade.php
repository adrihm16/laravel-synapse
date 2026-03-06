<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Synapse')</title>
  <link rel="icon" type="image/png" href="{{ asset('assets/favicon.png') }}">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      font-family: "Poppins", sans-serif;
    }
    @yield('styles')
  </style>
</head>

<body class="bg-gray-50 flex flex-col min-h-screen">
  
  <x-store-header />

  <main class="flex-grow">
    @yield('content')
  </main>

  <x-store-footer />

  <x-toast />

  @stack('scripts')
</body>

</html>
