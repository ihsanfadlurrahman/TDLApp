<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="bg-white rounded-xl shadow p-8 w-full max-w-sm">
    <h1 class="text-2xl font-bold text-gray-800 mb-1">📝 Buat Akun</h1>
    <p class="text-gray-400 text-sm mb-6">Daftar untuk mulai kelola task kamu</p>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
            <input type="text" name="username" value="{{ old('username') }}"
                   class="w-full border rounded-lg px-3 py-2 @error('username') border-red-400 @enderror"
                   placeholder="Buat username">
            @error('username')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" name="password"
                   class="w-full border rounded-lg px-3 py-2 @error('password') border-red-400 @enderror"
                   placeholder="Buat password (min. 6 karakter)">
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
            <input type="password" name="password_confirmation"
                   class="w-full border rounded-lg px-3 py-2"
                   placeholder="Ulangi password">
        </div>

        <button type="submit"
                class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition font-medium">
            Daftar
        </button>
    </form>

    <p class="text-center text-sm text-gray-400 mt-4">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Login</a>
    </p>
</div>

</body>
</html>
