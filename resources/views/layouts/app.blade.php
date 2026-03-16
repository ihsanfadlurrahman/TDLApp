<!DOCTYPE html>
<html lang="id" id="html-root">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MyTugas')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Syne:wght@700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
</head>

<body class="min-h-screen">

    {{-- Navbar --}}
    <nav class="navy-card sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6">
            <div class="flex justify-between items-center h-14">

                {{-- Brand --}}
                <a href="{{ route('tasks.index') }}" class="flex items-center gap-2.5">
                    <div
                        class="w-7 h-7 bg-blue-600 rounded-lg flex items-center justify-center text-sm font-bold text-white">
                        ✓</div>
                    <span class="font-bold tracking-tight text-white"
                        style="font-family:'Syne',sans-serif;">MyTugas</span>
                </a>

                {{-- Hamburger --}}
                <button id="menu-toggle" class="md:hidden text-white/50 hover:text-white">
                    <svg id="icon-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg id="icon-close" class="w-5 h-5 hidden" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                {{-- Desktop Nav --}}
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('dashboard') }}"
                        class="px-3 py-1.5 rounded-lg text-sm transition
                          {{ request()->routeIs('dashboard') ? 'bg-blue-600/20 text-blue-300 border border-blue-600/25' : 'text-white/50 hover:text-white hover:bg-white/5' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('tasks.index') }}"
                        class="px-3 py-1.5 rounded-lg text-sm transition
                          {{ request()->routeIs('tasks.*') ? 'bg-blue-600/20 text-blue-300 border border-blue-600/25' : 'text-white/50 hover:text-white hover:bg-white/5' }}">
                        Tasks
                    </a>
                    <a href="{{ route('categories.index') }}"
                        class="px-3 py-1.5 rounded-lg text-sm transition
                          {{ request()->routeIs('categories.*') ? 'bg-blue-600/20 text-blue-300 border border-blue-600/25' : 'text-white/50 hover:text-white hover:bg-white/5' }}">
                        Kategori
                    </a>
                </div>

                {{-- User + Toggle --}}
                <div class="hidden md:flex items-center gap-3">

                    {{-- Theme Toggle --}}
                    <button class="theme-toggle" id="theme-toggle" title="Toggle dark/light mode">
                        <span id="theme-icon">🌙</span>
                    </button>

                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-white/5 border border-white/8">
                        <div
                            class="w-6 h-6 rounded-full bg-blue-900 border border-blue-400 flex items-center justify-center text-blue-300 text-xs font-bold">
                            {{ strtoupper(substr(auth()->user()->username, 0, 2)) }}
                        </div>
                        <span class="text-sm text-white/60">{{ auth()->user()->username }}</span>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            class="px-3 py-1.5 text-sm text-white/40 hover:text-white border border-white/8 rounded-lg hover:bg-white/5 transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>

            {{-- Mobile menu --}}
            <div id="mobile-menu" class="hidden md:hidden pb-4 border-t border-white/5 pt-3 space-y-1">
                <a href="{{ route('dashboard') }}"
                    class="block px-3 py-2 rounded-lg text-sm text-white/60 hover:text-white hover:bg-white/5">Dashboard</a>
                <a href="{{ route('tasks.index') }}"
                    class="block px-3 py-2 rounded-lg text-sm text-white/60 hover:text-white hover:bg-white/5">Tasks</a>
                <a href="{{ route('categories.index') }}"
                    class="block px-3 py-2 rounded-lg text-sm text-white/60 hover:text-white hover:bg-white/5">Kategori</a>
                <div class="flex items-center justify-between pt-3 border-t border-white/5 mt-2">
                    <div class="flex items-center gap-2">
                        <button class="theme-toggle" id="theme-toggle-mobile">
                            <span id="theme-icon-mobile">🌙</span>
                        </button>
                        <span class="text-sm text-white/50">{{ auth()->user()->username }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            class="text-sm text-white/40 border border-white/8 px-3 py-1.5 rounded-lg hover:bg-white/5">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- Flash Message --}}
    <div class="max-w-6xl mx-auto px-6 mt-4 space-y-2">
        @if (session('success'))
            <div
                class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                ✅ {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div
                class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                ❌ {{ session('error') }}
            </div>
        @endif
    </div>

    <main class="max-w-6xl mx-auto px-6 py-8">
        @yield('content')
    </main>

    <script src="{{ asset('assets/js/app.js') }}"></script>
</body>

</html>
