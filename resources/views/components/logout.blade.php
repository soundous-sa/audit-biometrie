<!-- User info & logout (stuck to bottom) -->
   
  <div class="mt-auto p-4">
      <div class="flex items-center ">
        @php
          $user = auth()->user();
          $photo = $user && isset($user->profile_photo_path) && $user->profile_photo_path
            ? asset('storage/' . $user->profile_photo_path)
            : asset('images/');
        @endphp

        <!-- Avatar -->
        <img src="{{ asset('images/log.jpg') }}" alt="avatar" class="w-10 h-10 rounded-full object-cover border-2 border-white">

        <!-- Vertical separator (thin white line) on sm+ 
        <div class="hidden sm:block h-10 w-px bg-white/40 mx-2"></div>-->

        <!-- User name & email (hidden when collapsed) -->
        <div class="flex-1 text-white min-w-0" x-show="open">
          <div class="font-semibold truncate">{{ $user ? $user->name : 'مستخدم' }}</div>
          <div class="text-sm opacity-80 truncate">{{ $user ? $user->email : '' }}</div>
        </div>

        
        <!-- Logout button -->
    <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0  rounded-full bg-blue-800 hover:bg-blue-900">
      @csrf
      <button
        type="submit"
        aria-label="تسجيل الخروج"
        title="تسجيل الخروج"
        class="flex items-center gap-2 p-2 rounded-full hover:bg-blue-900 transition">
        <!-- Icon (always visible) -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white"
             viewBox="0 0 24 24" fill="none" stroke="currentColor"
             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
          <path d="M16 17l5-5-5-5"></path>
          <path d="M21 12H9"></path>
        </svg>
      </button>
        </form>
      </div>
    </div>
  </div>