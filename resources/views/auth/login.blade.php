<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — TaskFlow</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500&family=Syne:wght@700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
</head>

<body class="login">

    <div class="left-panel login">
        <div class="dot-grid"></div>
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
        <div class="left-content">
            <div class="form-brand">
                <div class="form-brand-icon">✓</div>
                <span class="form-brand-name">TaskFlow</span>
            </div>
            <h1 class="left-heading">Kelola tugas<br>dengan <span>lebih<br>cerdas</span></h1>
            <p class="left-sub">Satu tempat untuk semua to-do list kamu. Terorganisir, efisien, dan menyenangkan.</p>
            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-dot"></div> Buat dan kelola task dengan mudah
                </div>
                <div class="feature-item">
                    <div class="feature-dot"></div> Prioritaskan pekerjaan penting
                </div>
                <div class="feature-item">
                    <div class="feature-dot"></div> Pantau progres setiap saat
                </div>
            </div>
        </div>
    </div>

    <div class="right-panel">
        <div class="form-card">

            {{-- Brand di atas form --}}
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:28px;">
                <div class="brand-icon"
                    style="width:36px;height:36px;background:linear-gradient(135deg,#1d4ed8,#2563eb);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;">
                    ✓</div>
                <span
                    style="font-family:'Syne',sans-serif;font-weight:800;font-size:20px;color:#fff;letter-spacing:-0.5px;">TaskFlow</span>
            </div>

            <h2 class="form-title">Selamat datang 👋</h2>
            <p class="form-sub">Masuk ke akun kamu untuk melanjutkan</p>

            @if (session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="field-group">
                    <label>Username</label>
                    <div class="input-wrap">
                        <span class="input-icon">👤</span>
                        <input type="text" name="username" value="{{ old('username') }}"
                            placeholder="Masukkan username kamu"
                            class="{{ $errors->has('username') ? 'input-error' : '' }}">
                    </div>
                    @error('username')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field-group">
                    <label>Password</label>
                    <div class="input-wrap">
                        <span class="input-icon">🔒</span>
                        <input type="password" name="password" placeholder="Masukkan password kamu">
                    </div>
                </div>

                <button type="submit" class="btn-login">Masuk sekarang →</button>
            </form>

            <div class="divider">
                <div class="divider-line"></div>
                <span class="divider-text">atau</span>
                <div class="divider-line"></div>
            </div>

            <div class="register-link">
                Belum punya akun? <a href="{{ route('register') }}">Daftar gratis</a>
            </div>
        </div>
    </div>

</body>

</html>
