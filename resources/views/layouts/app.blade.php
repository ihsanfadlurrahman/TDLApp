<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'To-Do List')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

    <!-- Navbar -->
    <nav class="bg-indigo-600 text-white shadow-md">
        <div class="max-w-5xl mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <a href="{{ route('tasks.index') }}" class="text-xl font-bold">✅ To-Do List</a>

                {{-- Hamburger button (mobile only) --}}
                <button id="menu-toggle" class="md:hidden focus:outline-none">
                    <svg id="icon-open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg id="icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                {{-- Desktop menu --}}
                <div class="hidden md:flex items-center gap-4">
                    <a href="{{ route('dashboard') }}" class="hover:underline text-sm">Dashboard</a>
                    <a href="{{ route('tasks.index') }}" class="hover:underline text-sm">Tasks</a>
                    <a href="{{ route('categories.index') }}" class="hover:underline text-sm">Kategori</a>
                    <div class="flex items-center gap-3 border-l border-indigo-400 pl-4">
                        <span class="text-sm text-indigo-200">👤 {{ auth()->user()->username }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="text-sm bg-indigo-700 hover:bg-indigo-800 px-3 py-1 rounded-lg transition">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Mobile menu --}}
            <div id="mobile-menu" class="hidden md:hidden mt-3 pb-2 border-t border-indigo-500 pt-3 space-y-2">
                <a href="{{ route('dashboard') }}" class="block text-sm hover:text-indigo-200">Dashboard</a>
                <a href="{{ route('tasks.index') }}" class="block text-sm hover:text-indigo-200">Tasks</a>
                <a href="{{ route('categories.index') }}" class="block text-sm hover:text-indigo-200">Kategori</a>
                <div class="flex items-center justify-between pt-2 border-t border-indigo-500">
                    <span class="text-sm text-indigo-200">👤 {{ auth()->user()->username }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-sm bg-indigo-700 hover:bg-indigo-800 px-3 py-1 rounded-lg transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <script>
        const toggle = document.getElementById('menu-toggle');
        const menu = document.getElementById('mobile-menu');
        const iconOpen = document.getElementById('icon-open');
        const iconClose = document.getElementById('icon-close');

        toggle.addEventListener('click', () => {
            menu.classList.toggle('hidden');
            iconOpen.classList.toggle('hidden');
            iconClose.classList.toggle('hidden');
        });
    </script>

    {{-- Flash Message --}}
    <div class="max-w-5xl mx-auto px-4 mt-4 space-y-2">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded flex items-center gap-2">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded flex items-center gap-2">
                ❌ {{ session('error') }}
            </div>
        @endif
    </div>

    <!-- Content -->
    <main class="max-w-5xl mx-auto px-4 py-6">
        @yield('content')
    </main>

</body>

</html>
