<div :class="open ? 'w-96' : 'w-24'"
  class="relative text-white flex flex-col transition-all duration-500 ease-in-out shadow-2xl overflow-hidden">

  <!-- Background -->
  <div class="absolute inset-0">
    <div class="absolute inset-0 bg-blue-700 bg-opacity-70"></div>
  </div>

  <!-- Sidebar Content -->
  <div class="relative flex flex-col h-full">
    <!-- Header -->
    <div class="flex items-center justify-between p-4 border-b border-blue-500">
      <div class="flex items-center space-x-2 space-x-reverse">
        <img src="{{ asset('images/Logo_de_la_DGAPR.svg') }}" alt="Logo" class="w-14 h-14 object-contain rounded-full">
        <span x-show="open" class="text-2xl font-bold tracking-wide">تحيين البصمات البيومترية </span>
      </div>
      <!-- Toggle Button -->
      <button @click="open = !open"
        class="p-2 rounded-full bg-blue-800 hover:bg-blue-900 transition">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
          class="w-6 h-6 transform transition-transform duration-300"
          :class="open ? 'rotate-180' : 'rotate-0'">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
    </div>

    <!-- Nav Links -->
    <nav class="flex-1 space-y-2 p-4">
      <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-800 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h7v7H3zM14 3h7v7h-7zM14 14h7v7h-7zM3 14h7v7H3z" />
        </svg>
        <span x-show="open" class="mr-3">الرئيسية</span>
      </a>


      @if(Auth::user()->role === 'admin')
      <a href="{{ route('admin.fonctionnaires.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-800 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1m-6 6H6v-2a4 4 0 014-4h0a4 4 0 014 4v2zM9 11a4 4 0 100-8 4 4 0 000 8zM17 11a4 4 0 100-8 4 4 0 000 8z" />
        </svg>
        <span x-show="open" class="mr-3">الموظفين</span>
      </a>
      @endif
      @if(Auth::user()->role === 'user')
      <a href="{{ route('audits.create') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-800 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1m-6 6H6v-2a4 4 0 014-4h0a4 4 0 014 4v2zM9 11a4 4 0 100-8 4 4 0 000 8zM17 11a4 4 0 100-8 4 4 0 000 8z" />
        </svg>
        <span x-show="open" class="mr-3">اضافة تحيين</span>
      </a>

      <a href="{{ route('audits.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-800 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
        <span x-show="open" class="mr-3">لائحة عمليات التحيين</span>
      </a>
     <a href="{{ route('audits.exportForm') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-800 transition">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-4 0H10m0 0v4h4v-4m-4 0h4" />
    </svg>
    <span x-show="open" class="mr-3">طباعة</span>
</a>


      @endif

    </nav>
    <x-logout />

  </div>