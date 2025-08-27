<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>واجهة تدقيق الهوية</title>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Tajawal', sans-serif; }
  </style>
</head>
<body class="bg-gray-100">
  <div class="flex h-screen" x-data="{ open: true }">

    <!-- Sidebar (inclus séparément) -->
    @include('layouts.sidebar')

    <!-- Main Content -->
    <div class="flex-1 bg-gray-100 p-6 overflow-auto">
      <div class="w-full bg-white p-6 rounded-xl shadow-lg">
        @yield('content')
      </div>
    </div>
  </div>
</body>
</html>
