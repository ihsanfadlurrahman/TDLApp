<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — MyTugas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500&family=Syne:wght@700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
</head>

<body class="register">

    <div class="left-panel register">
        <div class="dot-grid"></div>
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
        <div class="left-content">
            <div class="form-brand">
                <div class="form-brand-icon">✓</div>
                <span class="form-brand-name">MyTugas</span>
            </div>
            <h1 class="left-heading">Mulai perjalanan<br><span>produktif</span><br>kamu</h1>
            <p class="left-sub">Daftar sekarang dan kelola semua task kamu dalam satu tempat yang rapi.</p>
            <div class="steps">
                <div class="step-item">
                    <div class="step-num">1</div>
                    <div class="step-text">
                        <strong>Buat akun gratis</strong>
                        <span>Cukup username & password</span>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-num">2</div>
                    <div class="step-text">
                        <strong>Tambah kategori & task</strong>
                        <span>Organisir sesuai kebutuhan</span>
                    </div>
                </div>
                <div class="step-item">
                    <div class="step-num">3</div>
                    <div class="step-text">
                        <strong>Pantau progress kamu</strong>
                        <span>Sub-task & dashboard lengkap</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="right-panel">
        <div class="form-card">

            {{-- Brand di atas form --}}
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:28px;">
                <div
                    style="width:36px;height:36px;background:linear-gradient(135deg,#10b981,#06b6d4);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;">
                    ✓</div>
                <span
                    style="font-family:'Syne',sans-serif;font-weight:800;font-size:20px;color:#fff;letter-spacing:-0.5px;">MyTugas</span>
            </div>

            <h2 class="form-title">Buat akun baru</h2>
            <p class="form-sub">Gratis selamanya, tanpa perlu email</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="field-group">
                    <label>Username</label>
                    <div class="input-wrap">
                        <span class="input-icon">👤</span>
                        <input type="text" name="username" value="{{ old('username') }}"
                            placeholder="Buat username kamu"
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
                        <input type="password" name="password" id="passwordInput"
                            placeholder="Buat password (min. 6 karakter)"
                            class="{{ $errors->has('password') ? 'input-error' : '' }}"
                            oninput="checkStrength(this.value)">
                    </div>
                    <div class="strength-bar">
                        <div class="strength-seg" id="s1"></div>
                        <div class="strength-seg" id="s2"></div>
                        <div class="strength-seg" id="s3"></div>
                        <div class="strength-seg" id="s4"></div>
                    </div>
                    <div class="hint" id="strengthHint">Minimal 6 karakter</div>
                    @error('password')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field-group">
                    <label>Konfirmasi Password</label>
                    <div class="input-wrap">
                        <span class="input-icon">🔒</span>
                        <input type="password" name="password_confirmation" placeholder="Ulangi password kamu">
                    </div>
                </div>

                <button type="submit" class="btn-register">Buat akun sekarang →</button>
            </form>

            <div class="divider">
                <div class="divider-line"></div>
                <span class="divider-text">atau</span>
                <div class="divider-line"></div>
            </div>

            <div class="login-link">
                Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
            </div>

            <p class="terms">Dengan mendaftar, kamu menyetujui penggunaan aplikasi ini.</p>
        </div>
    </div>

    <script>
        function checkStrength(val) {
            const segs = [document.getElementById('s1'), document.getElementById('s2'), document.getElementById('s3'),
                document.getElementById('s4')
            ];
            const hint = document.getElementById('strengthHint');
            const colors = ['#ef4444', '#f59e0b', '#10b981', '#10b981'];
            const hints = ['Terlalu pendek', 'Lemah', 'Cukup kuat', 'Kuat!'];
            let score = 0;
            if (val.length >= 6) score++;
            if (val.length >= 10) score++;
            if (/[A-Z]/.test(val) || /[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            segs.forEach((s, i) => {
                s.style.background = i < score ? colors[score - 1] : 'rgba(255,255,255,0.08)';
            });
            hint.textContent = score > 0 ? hints[score - 1] : 'Minimal 6 karakter';
            hint.style.color = score > 0 ? colors[score - 1] : 'rgba(255,255,255,0.2)';
        }
    </script>

</body>

</html>
