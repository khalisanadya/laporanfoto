<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login - Sistem Laporan PGN</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root{
      --bg: #f1f5f9;
      --card: #ffffff;
      --border: #e2e8f0;
      --text: #1e293b;
      --muted: #64748b;
      --primary: #0369a1;
      --primary-light: #0ea5e9;
      --primary-dark: #075985;
      --primary-bg: #f0f9ff;
      --primary-border: #bae6fd;
      --danger: #ef4444;
      --danger-bg: #fef2f2;
      --shadow: 0 1px 3px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.06);
      --shadow-md: 0 4px 6px -1px rgba(0,0,0,.1), 0 2px 4px -1px rgba(0,0,0,.06);
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif;
      background: linear-gradient(135deg, #f1f5f9 0%, #e0f2fe 100%);
      color: var(--text);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .login-container {
      width: 100%;
      max-width: 420px;
    }

    .login-card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 16px;
      padding: 40px;
      box-shadow: var(--shadow-md);
    }

    .login-header {
      margin-bottom: 32px;
      text-align: center;
    }

    .login-icon {
      width: 56px;
      height: 56px;
      background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 16px;
      font-size: 28px;
      color: var(--primary-dark);
    }

    .login-title {
      font-size: 28px;
      font-weight: 700;
      color: var(--text);
      margin-bottom: 8px;
    }

    .login-subtitle {
      font-size: 14px;
      color: var(--muted);
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-label {
      display: block;
      font-size: 14px;
      font-weight: 600;
      color: var(--text);
      margin-bottom: 8px;
    }

    .form-input {
      width: 100%;
      padding: 12px 14px;
      border: 1.5px solid var(--border);
      border-radius: 10px;
      font-size: 14px;
      font-family: inherit;
      transition: all 0.2s ease;
      background: #fafbfc;
    }

    .form-input:focus {
      outline: none;
      border-color: var(--primary);
      background: var(--primary-bg);
      box-shadow: 0 0 0 3px rgba(3, 105, 161, 0.1);
    }

    .form-error {
      display: flex;
      align-items: center;
      gap: 8px;
      background: var(--danger-bg);
      border: 1px solid #fecaca;
      border-radius: 10px;
      padding: 12px 14px;
      margin-bottom: 20px;
      font-size: 13px;
      color: var(--danger);
    }

    .form-error::before {
      content: "⚠";
      font-size: 16px;
      flex-shrink: 0;
    }

    .field-error {
      display: block;
      font-size: 12px;
      color: var(--danger);
      margin-top: 6px;
      font-weight: 500;
    }

    .btn-login {
      width: 100%;
      padding: 12px 16px;
      background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
      color: white;
      border: none;
      border-radius: 10px;
      font-size: 15px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s ease;
      font-family: inherit;
      box-shadow: 0 2px 8px rgba(3, 105, 161, 0.2);
    }

    .btn-login:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(3, 105, 161, 0.3);
    }

    .btn-login:active {
      transform: translateY(0);
    }

    .demo-info {
      background: var(--primary-bg);
      border: 1px solid var(--primary-border);
      border-radius: 10px;
      padding: 16px;
      margin-top: 24px;
      font-size: 13px;
      color: var(--primary-dark);
    }

    .demo-info-title {
      font-weight: 600;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .demo-info-title::before {
      content: "ℹ";
      font-size: 15px;
    }

    .demo-credential {
      display: flex;
      justify-content: space-between;
      padding: 6px 0;
      font-family: 'Courier New', monospace;
      font-size: 12px;
    }

    .demo-label {
      font-weight: 600;
    }

    .password-field {
      position: relative;
    }

    .password-toggle {
      position: absolute;
      right: 14px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      cursor: pointer;
      color: var(--muted);
      padding: 4px 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: color 0.2s ease;
      width: 32px;
      height: 32px;
    }

    .password-toggle:hover {
      color: var(--primary);
    }

    .password-toggle svg {
      width: 20px;
      height: 20px;
      stroke: currentColor;
      fill: none;
      stroke-width: 2;
      stroke-linecap: round;
      stroke-linejoin: round;
    }

    .password-input-wrapper {
      position: relative;
    }

    .form-input.password-input {
      padding-right: 40px;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-card">
      <div class="login-header">
        <h1 class="login-title">Login</h1>
        <p class="login-subtitle">Sistem Laporan Foto PGN</p>
      </div>

      @if ($errors->any())
        <div class="form-error">
          {{ $errors->first('email') }}
        </div>
      @endif

      <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
          <label class="form-label" for="email">Email</label>
          <input
            class="form-input"
            type="email"
            id="email"
            name="email"
            value="{{ old('email') }}"
            placeholder="nama@example.com"
            required
            autofocus
          >
          @error('email')
            <span class="field-error">{{ $message }}</span>
          @enderror
        </div>

        <div class="form-group">
          <label class="form-label" for="password">Password</label>
          <div class="password-input-wrapper">
            <input
              class="form-input password-input"
              type="password"
              id="password"
              name="password"
              placeholder="Masukkan password Anda"
              required
            >
            <button type="button" class="password-toggle" id="passwordToggle">
              <svg id="eyeIcon" viewBox="0 0 24 24">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
              </svg>
            </button>
          </div>
          @error('password')
            <span class="field-error">{{ $message }}</span>
          @enderror
        </div>

        <button type="submit" class="btn-login">Login Sekarang</button>
      </form>
    </div>
  </div>

  <script>
    const passwordInput = document.getElementById('password');
    const passwordToggle = document.getElementById('passwordToggle');
    const eyeIcon = document.getElementById('eyeIcon');

    const eyeOpenSVG = `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>`;
    const eyeClosedSVG = `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>`;

    passwordToggle.addEventListener('click', function(e) {
      e.preventDefault();
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.innerHTML = eyeClosedSVG;
      } else {
        passwordInput.type = 'password';
        eyeIcon.innerHTML = eyeOpenSVG;
      }
    });
  </script>
</body>
</html>
