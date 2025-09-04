<!-- User info & logout (stuck to bottom) -->
   
  <div class="mt-auto p-4">
      <div class="flex items-center space-x-3 space-x-reverse">
        @php
          $user = auth()->user();
          $photo = $user && isset($user->profile_photo_path) && $user->profile_photo_path
            ? asset('storage/' . $user->profile_photo_path)
            : asset('images/');
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
            :class="open 
            ? 'flex items-center gap-2 p-1.5 rounded-full bg-blue-800 hover:bg-blue-900' : 'p-2 mx-auto rounded-full bg-white/5 '"
            class="transition-colors focus:outline-none focus:ring-2 focus:ring-white/30">
                <span class="flex items-center justify-center w-8 h-8 rounded-full">
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