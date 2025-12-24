<nav x-data="{ open: false }"
    class="bg-white/80 dark:bg-dark-800/80 backdrop-blur-md border-b border-primary-100 dark:border-white/5 sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo & Branding -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                        <x-application-logo
                            class="block h-12 w-auto fill-current text-primary-600 dark:text-primary-400 drop-shadow-lg transition-transform hover:scale-105 duration-300" />
                        <span
                            class="font-bold text-2xl tracking-tight text-gray-900 dark:text-white hidden sm:block">Paul
                            <span class="text-primary-600 dark:text-primary-400">Quiz</span></span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="url('/')" :active="request()->is('/')">
                        {{ __('Home') }}
                    </x-nav-link>
                    <x-nav-link :href="route('modules.index')" :active="request()->routeIs('modules.index')">
                        {{ __('Modules') }}
                    </x-nav-link>
                    <x-nav-link :href="route('leaderboard.index')" :active="request()->routeIs('leaderboard.index')">
                        {{ __('Leaderboard') }}
                    </x-nav-link>
                    @auth
                        @if(Auth::user()->hasRole('admin'))
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                                {{ __('Admin Panel') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <!-- Notification Dropdown -->
                    <div class="relative mr-4" x-data="{

                                                                                                                                count: 0,
                                                                                                                                lastId: null,
                                                                                                                                toastVisible: false,
                                                                                                                                toastTitle: '',
                                                                                                                                toastMessage: '',
                                                                                                                                initialized: false,
                                                                                                                                init() {
                                                                                                                                    console.log('Navbar Notification System Starting...');

                                                                                                                                    // 1. Initial Fetch (Baseline)
                                                                                                                                    fetch('{{ route('notifications.check') }}')
                                                                                                                                        .then(response => response.json())
                                                                                                                                        .then(data => {
                                                                                                                                            this.count = data.count;
                                                                                                                                            if (data.latest) {
                                                                                                                                                this.lastId = data.latest.id;
                                                                                                                                            } else {
                                                                                                                                                this.lastId = 0;
                                                                                                                                            }
                                                                                                                                            this.initialized = true;
                                                                                                                                        })
                                                                                                                                        .catch(err => console.error('Initial notification check failed', err));

                                                                                                                                    // 2. Polling
                                                                                                                                    setInterval(() => {
                                                                                                                                        if (!this.initialized) return;

                                                                                                                                        fetch('{{ route('notifications.check') }}')
                                                                                                                                            .then(response => response.json())
                                                                                                                                            .then(data => {
                                                                                                                            // Update badge count
                                                                                                                            this.count = data.count;

                                                                                                                            // Check for NEW notification (Loose equality for safety)
                                                                                                                            if (data.latest && data.latest.id != this.lastId) {
                                                                                                                                console.log('New notification found:', data.latest);
                                                                                                                                console.log('Previous ID:', this.lastId, 'New ID:', data.latest.id);

                                                                                                                                this.lastId = data.latest.id; // Update first to prevent loop

                                                                                                                                this.toastTitle = data.latest.title;
                                                                                                                                this.toastMessage = data.latest.message;
                                                                                                                                this.toastVisible = true;

                                                                                                                                    // Play Sound (Base64 Beep - Reliable & Fast)
                                                                                                                                const beep = 'data:audio/wav;base64,UklGRl9vT19XQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YU';

                                                                                                                                // Actual pleasant notification sound (Base64)
                                                                                                                                const notificationSound = new Audio('data:audio/mp3;base64,SUQzBAAAAAABAFRYWFgAAAASAAADbWFqb3JfYnJhbmQAbXA0MgBUWFhYAAAAEQAAA21pbm9yX3ZlcnNpb24AMABUWFhYAAAAHAAAA2NvbXBhdGlibGVfYnJhbmRzAGlzb21tcDQyAFRTU0UAAAAPAAADTGF2ZjU3LjU2LjEwMAAAAAAAAAAAAAAA//uQZAAAAAAAABAAAAAAAAAAAAkJbzz///////7kGQAAAAAAAACAAAAAAAAAAAAQU1FMy45OS41//uQZAAAAAAAABAAAAAAAAAAAAkJbzz///////7kGQAAAAAAAACAAAAAAAAAAAAQU1FMy45OS41//uQZAAAAAAAABAAAAAAAAAAAAkJbzz///////7kGQAAAAAAAACAAAAAAAAAAAAQU1FMy45OS41//uQZAAAAAAAABAAAAAAAAAAAAkJbzz///////7kGQAAAAAAAACAAAAAAAAAAAAQU1FMy45OS41//uQZAAAAAAAABAAAAAAAAAAAAkJbzz///////7kGQAAAAAAAACAAAAAAAAAAAAQU1FMy45OS41//uQZAAAAAAAABAAAAAAAAAAAAkJbzz///////7kGQAAAAAAAACAAAAAAAAAAAAQU1FMy45OS41//uQZAAAAAAAABAAAAAAAAAAAAkJbzz///////7kGQAAAAAAAACAAAAAAAAAAAAQU1FMy45OS41//uQZAAAAAAAABAAAAAAAAAAAAkJbzz///////7kGQAAAAAAAACAAAAAAAAAAAAQU1FMy45OS41//uQZAAAAAAAABAAAAAAAAAAAAkJbzz///////7kGQAAAAAAAACAAAAAAAAAAAAQU1FMy45OS41//uQZAAAAAAAABAAAAAAAAAAAAkJbzz///////7kGQAAAAAAAACAAAAAAAAAAAAQU1FMy45OS41//uQZAAAAAAAABAAAAAAAAAAAAkJbzz///////7kGQAAAAAAAACAAAAAAAAAAAAQU1FMy45OS41//uQZAAAAAAAABAAAAAAAAAAAAkJbzz///////7kGQAAAAAAAACAAAAAAAAAAAAQU1FMy45OS41//uQZAAAAAAAABAAAAAAAAAAAAkJbzz///////7kGQAAAAAAAACAAAAAAAAAAAAQU1FMy45OS41//uQZAAAAAAAABAAAAAAAAAAAAkJbzz///////7kGQAAAAAAAACAAAAAAAAAAAAQU1FMy45OS41//uQZAAAAAAAABAAAAAAAAAAAAkJbzz///////7kGQAAAAAAAACAAAAAAAAAAAAQU1FMy45OS41//uQZAAAAAAAABAAAAAAAAAAAAkJbzz///////7kGQAAAAAAAACAAAAAAAAAAAAQU1FMy45OS41//uQZAAAAAAAABAAAAAAAAAAAAkJbzz///////7kGQAAAAAAAACAAAAAAAAAAAAQU1FMy45OS41//uQZAAAAAAAABAAAAAAAAAAAAkJbzz///////7kGQAAAAAAAACAAAAAAAAAAAAQU1FMy45OS41//uQZAAAAAAAABAAAAAAAAAAAAkJbzz///////7kGQAAAAAAAACAAAAAAAAAAAAQU1FMy45OS41//uQZAAAAAAAABAAAAAAAAAAAAkJbzz///////7kGQAAAAAAAACAAAAAAAAAAAAQU1FMy45OS41//uQZAAAAAAAABAAAAAAAAAAAAkJbzz///////7kGQAAAAAAAACAAAAAAAAAAAAQU1FMy45OS41//uQZAAAAAAAABAAAAAAAAAAAAkJbzz///////7kGQAAAAAAAACAAAAAAAAAAAAQU1FMy45OS41//uQZAAAAAAAABAAAAAAAAAAAAkJbzz///////7kGQAAAAAAAACAAAAAAAAAAAAQU1FMy45OS41//uQZAAAAAAAABAAAAAAAAAAAAkJbzz///////7kGQAAAAAAAACAAAAAAAAAAAAQU1FMy45OS41//uQZAAAAAAAABAAAAAAAAAAAAkJbzz///////7kGQAAAAAAAACAAAAAAAAAAAAQU1FMy45OS41//uQZAAAAAAAABAAAAAAAAAAAAkJbzz///////7kGQAAAAAAAACAAAAAAAAAAAAQU1FMy45OS41//uQZAAAAAAAABAAAAAAAAAAAAkJbzz///////7kGQAAAAAAAACAAAAAAAAAAAAQU1FMy45OS41//uQZAAAAAAAABAAAAAAAAAAAAkJbzz///////7kGQAAAAAAAACAAAAAAAAAAAAQU1FMy45OS41//uQZAAAAAAAABAAAAAAAAAAAAkJbzz///////7kGQAAAAAAAACAAAAAAAAAAAAQU1FMy45OS41//uQZAAAAAAAABAAAAAAAAAAAAkJbzz///////7kGQAAAAAAAACAAAAAAAAAAAAQU1FMy45OS41//uQZAAAAAAAABAAAAAAAAAAAAkJbzz///////7kGQAAAAAAAACAAAAAAAAAAAAQU1FMy45OS41//uQZAAAAAAAABAAAAAAAAAAAAkJbzz///////7uQZAAAAAAABAAAAAAAAAAAAkJbzz////////');

                                                                                                                                notificationSound.volume = 1.0;
                                                                                                                                // Attempt to play
                                                                                                                                const playPromise = notificationSound.play();
                                                                                                                                if (playPromise !== undefined) {
                                                                                                                                    playPromise.catch(error => {
                                                                                                                                        console.log('Autoplay prevented. User interaction required.');
                                                                                                                                    });
                                                                                                                                }

                                                                                                                                // Auto-hide
                                                                                                                                setTimeout(() => {
                                                                                                                                    this.toastVisible = false;
                                                                                                                                }, 15000);
                                                                                                                            }
                                                                                                                            })
                                                                                                                            .catch(error => console.error('Polling error:', error));
                                                                                                                    }, 3000);

                                                                                                                // Unlock Audio on First Click
                                                                                                                document.addEventListener('click', () => {
                                                                                                                    const AudioContext = window.AudioContext || window.webkitAudioContext;
                                                                                                                    if (AudioContext) {
                                                                                                                        const ctx = new AudioContext();
                                                                                                                        ctx.resume();
                                                                                                                    }
                                                                                                                }, { once: true });
                                                                                                        }
                                                                                                    }" x-init="init()">

                        <button @click="open = !open"
                            class="relative p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <span class="sr-only">View notifications</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span x-show="count > 0" x-text="count"
                                class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full"></span>
                        </button>

                        <div x-show="open" @click.away="open = false" style="display: none;"
                            class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                            <div class="px-4 py-2 border-b border-gray-100 flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-900">Notifications</span>
                                <template x-if="count > 0">
                                    <form action="{{ route('notifications.readAll') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-xs text-primary-600 hover:text-primary-900">Mark
                                            all as read</button>
                                    </form>
                                </template>
                            </div>

                            <div class="max-h-64 overflow-y-auto">
                                @forelse(auth()->user()->unreadNotifications as $notification)
                                    <div class="px-4 py-3 hover:bg-gray-50 border-b border-gray-100">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $notification->data['title'] ?? 'Notification' }}
                                                </p>
                                                <p class="text-xs text-gray-500 mt-1">{{ $notification->data['message'] ?? '' }}
                                                </p>
                                                <p class="text-xs text-gray-400 mt-1">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                            <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-gray-400 hover:text-gray-600">
                                                    <span class="sr-only">Mark as read</span>
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <div class="px-4 py-3 text-center text-sm text-gray-500">
                                        No new notifications
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Toast Notification (Moved Inside Scope) -->
                        <div x-show="toastVisible" x-transition:enter="transition ease-out duration-500"
                            x-transition:enter-start="opacity-0 translate-y-[-2rem] scale-90"
                            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                            x-transition:leave="transition ease-in duration-300"
                            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                            x-transition:leave-end="opacity-0 translate-y-[-2rem] scale-90"
                            style="display: none; 
                                       background: rgba(255, 255, 255, 0.1); 
                                       backdrop-filter: blur(20px); 
                                       border: 1px solid rgba(255, 255, 255, 0.2);
                                       box-shadow: -8px 8px 24px rgba(16, 185, 129, 0.6), -4px 4px 12px rgba(16, 185, 129, 0.4);"
                            class="fixed top-24 right-5 z-[99999] max-w-sm w-full rounded-2xl pointer-events-auto font-sans transform hover:scale-[1.02] transition-transform duration-200">
                            <!-- Close Button (Absolute Top-Right) -->
                            <button @click="toastVisible = false"
                                class="absolute top-2 right-2 text-gray-400 hover:text-green-600 dark:hover:text-green-400 z-50 p-1.5 rounded-full hover:bg-green-50 dark:hover:bg-green-900/30 transition-colors">
                                <span class="sr-only">Close</span>
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>

                            <div class="p-4 relative overflow-hidden pr-8">
                                <div class="flex items-start relative z-10">
                                    <div class="flex-shrink-0">
                                        <!-- Animated Bell Icon -->
                                        <div
                                            class="h-10 w-10 bg-gradient-to-br from-primary-100 to-primary-50 dark:from-primary-900/50 dark:to-primary-800/30 rounded-full flex items-center justify-center shadow-inner ring-1 ring-primary-100 dark:ring-primary-700/30">
                                            <svg class="h-5 w-5 text-primary-600 dark:text-primary-400 animate-bounce-slight"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-3 w-0 flex-1 pt-0.5">
                                        <p class="text-sm font-bold text-gray-900 dark:text-gray-100 tracking-tight"
                                            x-text="toastTitle"></p>
                                        <p class="mt-1 text-xs text-gray-600 dark:text-gray-300 leading-relaxed font-medium"
                                            x-text="toastMessage"></p>
                                    </div>
                                </div>
                            </div>
                            <!-- Modern Progress bar -->
                            <div
                                class="absolute bottom-0 left-0 h-1 bg-gradient-to-r from-green-400 to-green-600 w-full origin-left animate-[shrink_15s_linear_forwards]">
                            </div>
                        </div>
                    </div>

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-primary-200 shadow-sm text-sm leading-4 font-medium rounded-md text-primary-700 dark:text-gray-400 bg-white dark:bg-gray-800 hover:bg-primary-50 dark:hover:bg-gray-700 hover:text-primary-800 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('users.stats', Auth::user())">
                                {{ __('Statistik') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                                                                                                                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="space-x-4">
                        <a href="{{ route('login') }}"
                            class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400">Log
                            in</a>
                        <a href="{{ route('register') }}"
                            class="px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-lg shadow-sm transition-all hover:shadow-md">Register</a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="url('/')" :active="request()->is('/')">
                {{ __('Home') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('modules.index')" :active="request()->routeIs('modules.index')">
                {{ __('Modules') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('leaderboard.index')" :active="request()->routeIs('leaderboard.index')">
                {{ __('Leaderboard') }}
            </x-responsive-nav-link>
            @auth
                @if(Auth::user()->hasRole('admin'))
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        {{ __('Admin Panel') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('users.stats', Auth::user())">
                        {{ __('Statistik') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                                                                                                                                                this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="mt-3 space-y-1 px-4">
                    <x-responsive-nav-link :href="route('login')">
                        {{ __('Log in') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')">
                        {{ __('Register') }}
                    </x-responsive-nav-link>
                </div>
            @endauth
        </div>
    </div>


</nav>