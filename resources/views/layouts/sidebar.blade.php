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
        <span x-show="open" class="text-2xl font-bold tracking-wide">تدقيق_الهوية</span>
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

      <a href="{{ route('fonctionnaires.index') }}" class="flex items-center px-4 py-3 rounded-lg hover:bg-blue-800 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1m-6 6H6v-2a4 4 0 014-4h0a4 4 0 014 4v2zM9 11a4 4 0 100-8 4 4 0 000 8zM17 11a4 4 0 100-8 4 4 0 000 8z" />
        </svg>
        <span x-show="open" class="mr-3">الموظفين</span>
      </a>
    </nav>

  <!-- User info & logout (stuck to bottom) -->
   
  <div class="mt-auto p-4">
      <div class="flex items-center space-x-3 space-x-reverse">
        @php
          $user = auth()->user();
          $photo = $user && isset($user->profile_photo_path) && $user->profile_photo_path
            ? asset('storage/' . $user->profile_photo_path)
            : asset('images/Logo_de_la_DGAPR.svg');
        @endphp

        <!-- Avatar -->
        <img src="{{ $photo }}" alt="avatar" class="w-10 h-10 rounded-full object-cover border-2 border-white">

        <!-- Vertical separator (thin white line) on sm+ -->
        <div class="hidden sm:block h-10 w-px bg-white/40 mx-2"></div>

        <!-- User name & email (hidden when collapsed) -->
        <div class="flex-1 text-white min-w-0" x-show="open">
          <div class="font-semibold truncate">{{ $user ? $user->name : 'مستخدم' }}</div>
          <div class="text-sm opacity-80 truncate">{{ $user ? $user->email : '' }}</div>
        </div>

        <!-- Logout button/icon (styled & responsive) -->
        <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0">
          @csrf
          <button
            type="submit"
            aria-label="تسجيل الخروج"
            title="تسجيل الخروج"
            :class="open ? 'flex items-center gap-2 p-1.5 rounded-full bg-blue-800 hover:bg-blue-900' : 'p-2 mx-auto rounded-full bg-white/5 '"
            class="transition-colors focus:outline-none focus:ring-2 focus:ring-white/30">
            <span class="flex items-center justify-center w-8 h-8 rounded-full ">
              <!-- stylized logout icon (arrow out of door) -->
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <path d="M16 17l5-5-5-5"></path>
                <path d="M21 12H9"></path>
              </svg>
            </span>
            
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
