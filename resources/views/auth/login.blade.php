<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white rounded-xl shadow p-8 w-full max-w-sm">
        <h1 class="text-2xl font-bold text-gray-800 mb-1">👋 Selamat Datang</h1>
        <p class="text-gray-400 text-sm mb-6">Login untuk lanjut ke To-Do List kamu</p>

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" name="username" value="{{ old('username') }}"
                    class="w-full border rounded-lg px-3 py-2 @error('username') border-red-400 @enderror"
                    placeholder="Masukkan username">
                @error('username')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" class="w-full border rounded-lg px-3 py-2"
                    placeholder="Masukkan password">
            </div>

            <button type="submit"
                class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition font-medium">
                Login
            </button>
        </form>
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <p class="text-center text-sm text-gray-400 mt-4">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-indigo-600 hover:underline">Daftar</a>
        </p>
    </div>

</body>

</html>
