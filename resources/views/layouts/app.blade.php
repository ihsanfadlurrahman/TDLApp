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
        <div class="max-w-5xl mx-auto px-4 py-3 flex justify-between items-center">
            <a href="{{ route('tasks.index') }}" class="text-xl font-bold">✅ To-Do List</a>
            <div class="flex gap-4">
                <a href="{{ route('tasks.index') }}"
                   class="hover:underline">Tasks</a>
                <a href="{{ route('categories.index') }}"
                   class="hover:underline">Kategori</a>
            </div>
        </div>
    </nav>

    <!-- Flash Message -->
    <div class="max-w-5xl mx-auto px-4 mt-4">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif
    </div>

    <!-- Content -->
    <main class="max-w-5xl mx-auto px-4 py-6">
        @yield('content')
    </main>

</body>
</html>
