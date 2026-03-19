<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="<?= base_url('/images/logo.png') ?>">
  <title>Admin Login</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <style>
    :root {
      --login-olive: #7d8f43;
      --login-olive-deep: #445324;
      --login-cream: #f7f1e3;
      --login-sand: #e7d2ab;
      --login-ink: #1f2a16;
      --login-muted: #677358;
      --login-card: rgba(255, 252, 246, 0.88);
      --login-border: rgba(68, 83, 36, 0.12);
      --login-shadow: 0 24px 60px rgba(40, 48, 24, 0.18);
    }

    body {
      min-height: 100vh;
      margin: 0;
      font-family: 'DM Sans', sans-serif;
      color: var(--login-ink);
      background:
        radial-gradient(circle at top left, rgba(247, 214, 137, 0.62), transparent 28%),
        radial-gradient(circle at bottom right, rgba(125, 143, 67, 0.35), transparent 24%),
        linear-gradient(135deg, #f4eedf 0%, #e6dcc2 50%, #d1c593 100%);
      overflow-x: hidden;
    }

    .login-shell {
      position: relative;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: clamp(1rem, 2vw, 1.75rem);
    }

    .login-stage {
      width: min(1080px, 100%);
      margin-inline: auto;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-orb {
      position: absolute;
      border-radius: 999px;
      filter: blur(8px);
      opacity: 0.65;
      animation: drift 9s ease-in-out infinite;
      pointer-events: none;
    }

    .login-orb.one {
      width: 13rem;
      height: 13rem;
      top: 4rem;
      left: -2rem;
      background: rgba(125, 143, 67, 0.28);
    }

    .login-orb.two {
      width: 10rem;
      height: 10rem;
      right: 3rem;
      bottom: 4rem;
      background: rgba(232, 183, 90, 0.3);
      animation-delay: -2s;
    }

    .login-frame {
      position: relative;
      z-index: 1;
      width: 100%;
      margin-inline: auto;
      border-radius: 2rem;
      overflow: hidden;
      border: 1px solid rgba(255, 255, 255, 0.45);
      box-shadow: var(--login-shadow);
      backdrop-filter: blur(12px);
      background: rgba(255, 255, 255, 0.18);
      animation: rise-in 0.7s ease-out both;
    }

    .login-hero {
      min-height: 100%;
      padding: 2.75rem;
      background:
        linear-gradient(160deg, rgba(68, 83, 36, 0.95), rgba(125, 143, 67, 0.90)),
        url("<?= esc(base_url('/images/logo.png')) ?>") no-repeat bottom right / 160px;
      color: #fff8ef;
      position: relative;
      overflow: hidden;
    }

    .login-hero::after {
      content: "";
      position: absolute;
      inset: auto -3rem -3rem auto;
      width: 12rem;
      height: 12rem;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.10);
    }

    .login-eyebrow {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.45rem 0.85rem;
      border-radius: 999px;
      background: rgba(255, 255, 255, 0.12);
      font-size: 0.84rem;
      letter-spacing: 0.04em;
      text-transform: uppercase;
    }

    .login-hero h1 {
      margin-top: 1.5rem;
      font-family: 'Space Grotesk', sans-serif;
      font-size: clamp(2rem, 4vw, 3.4rem);
      line-height: 1.02;
      letter-spacing: -0.04em;
    }

    .login-hero p {
      max-width: 32rem;
      margin-top: 1rem;
      color: rgba(255, 248, 239, 0.82);
      font-size: 1rem;
    }

    .login-highlights {
      display: grid;
      gap: 0.9rem;
      margin-top: 2rem;
    }

    .login-highlight {
      display: flex;
      gap: 0.9rem;
      align-items: flex-start;
      padding: 0.95rem 1rem;
      border-radius: 1rem;
      background: rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(6px);
    }

    .login-highlight i {
      font-size: 1.2rem;
      color: var(--login-sand);
    }

    .login-panel {
      padding: 2.5rem 2rem;
      background: var(--login-card);
      justify-content: center;
    }

    .login-card {
      max-width: 29rem;
      margin: 0 auto;
      animation: rise-in 0.8s ease-out 0.1s both;
    }

    .brand-row {
      display: flex;
      align-items: center;
      gap: 0.9rem;
      margin-bottom: 1.5rem;
    }

    .brand-logo {
      width: 3.25rem;
      height: 3.25rem;
      border-radius: 1rem;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #fff7e5, #e7d2ab);
      box-shadow: inset 0 0 0 1px rgba(68, 83, 36, 0.08);
    }

    .brand-logo img {
      width: 2rem;
      height: 2rem;
      object-fit: contain;
    }

    .brand-copy small {
      color: var(--login-muted);
      text-transform: uppercase;
      letter-spacing: 0.08em;
      font-size: 0.72rem;
    }

    .brand-copy h2 {
      margin: 0.25rem 0 0;
      font-family: 'Space Grotesk', sans-serif;
      font-size: 1.8rem;
      letter-spacing: -0.03em;
    }

    .login-subtext {
      color: var(--login-muted);
      margin-bottom: 1.75rem;
    }

    .login-form-wrap {
      border-radius: 1.5rem;
      padding: 1.4rem;
      background: rgba(255, 255, 255, 0.72);
      border: 1px solid var(--login-border);
    }

    .form-label {
      font-weight: 600;
      color: var(--login-ink);
    }

    .form-control {
      min-height: 3.2rem;
      border-radius: 1rem;
      border: 1px solid rgba(68, 83, 36, 0.14);
      background: rgba(255, 255, 255, 0.92);
      padding-inline: 1rem;
      transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
    }

    .form-control:focus {
      border-color: rgba(125, 143, 67, 0.7);
      box-shadow: 0 0 0 0.25rem rgba(125, 143, 67, 0.16);
      transform: translateY(-1px);
    }

    .password-wrap {
      position: relative;
    }

    .password-toggle {
      position: absolute;
      top: 50%;
      right: 0.8rem;
      transform: translateY(-50%);
      border: 0;
      background: transparent;
      color: var(--login-muted);
      width: 2.5rem;
      height: 2.5rem;
      border-radius: 999px;
    }

    .password-toggle:hover,
    .password-toggle:focus {
      background: rgba(125, 143, 67, 0.10);
      color: var(--login-olive-deep);
    }

    .login-meta {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 1rem;
      margin-top: 1rem;
      color: var(--login-muted);
      font-size: 0.92rem;
    }

    .login-submit {
      min-height: 3.35rem;
      border: 0;
      border-radius: 1rem;
      font-weight: 700;
      letter-spacing: 0.02em;
      background: linear-gradient(135deg, var(--login-olive), #96ab51);
      box-shadow: 0 18px 28px rgba(125, 143, 67, 0.24);
      transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
    }

    .login-submit:hover,
    .login-submit:focus {
      transform: translateY(-2px);
      box-shadow: 0 20px 32px rgba(125, 143, 67, 0.28);
      filter: saturate(1.05);
    }

    .login-note {
      margin-top: 1.25rem;
      padding-top: 1rem;
      border-top: 1px dashed rgba(68, 83, 36, 0.16);
      color: var(--login-muted);
      font-size: 0.92rem;
    }

    @keyframes rise-in {
      from {
        opacity: 0;
        transform: translateY(24px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes drift {
      0%,
      100% {
        transform: translate3d(0, 0, 0);
      }

      50% {
        transform: translate3d(10px, -14px, 0);
      }
    }

    @media (max-width: 991.98px) {
      .login-stage {
        width: min(760px, 100%);
      }

      .login-hero {
        padding: 2rem;
      }

      .login-panel {
        padding: 1.5rem;
      }
    }

    @media (max-width: 767.98px) {
      .login-shell {
        padding: 1rem;
      }

      .login-stage {
        width: min(100%, 32rem);
      }

      .login-frame {
        border-radius: 1.5rem;
      }

      .login-hero {
        background-size: 120px;
      }

      .login-panel {
        padding: 1.2rem;
      }

      .login-form-wrap {
        padding: 1rem;
      }

      .login-meta {
        flex-direction: column;
        align-items: flex-start;
      }
    }
  </style>
</head>

<body>
  <?= view('/partials/site_loader') ?>
  <?= view('/partials/flash_toasts') ?>

  <main class="login-shell">
    <div class="login-orb one"></div>
    <div class="login-orb two"></div>

    <div class="login-stage">
      <div class="login-frame row g-0">
        <div class="col-lg-6">
          <section class="login-hero h-100 d-flex flex-column justify-content-between">
            <div>
              <span class="login-eyebrow">
                <i class="bi bi-shield-lock"></i>
                Secure Access
              </span>
              <h1>Run the kitchen, billing, and operations from one admin door.</h1>
              <p>Sign in to manage company settings, profiles, roles, categories, and the daily flow of Kuberan Foods.</p>
            </div>

            <div class="login-highlights">
              <div class="login-highlight">
                <i class="bi bi-people"></i>
                <div>
                  <strong>Role-based access</strong>
                  <div class="small text-white-50">Admin, sub-admin, and manager access can grow from one login system.</div>
                </div>
              </div>
              <div class="login-highlight">
                <i class="bi bi-phone"></i>
                <div>
                  <strong>Responsive workflow</strong>
                  <div class="small text-white-50">Built to stay usable across laptop, tablet, and mobile screens.</div>
                </div>
              </div>
            </div>
          </section>
        </div>

        <div class="col-lg-6">
          <section class="login-panel h-100 d-flex align-items-center">
            <div class="login-card w-100">
              <div class="brand-row">
                <span class="brand-logo">
                  <img src="<?= esc($company_logo ?? base_url('/images/logo.png')) ?>" alt="Logo">
                </span>
                <div class="brand-copy">
                  <small><?= esc($company_name ?? 'Kuberan Foods Admin') ?></small>
                  <h2>Welcome Back</h2>
                </div>
              </div>

              <p class="login-subtext">Use your account credentials to continue into the admin workspace.</p>

              <div class="login-form-wrap">
                <form action="/admin/login" method="post">
                  <?= csrf_field() ?>

                  <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?= esc(old('username')) ?>" placeholder="Enter your username" required>
                  </div>

                  <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="password-wrap">
                      <input type="password" class="form-control pe-5" id="password" name="password" placeholder="Enter your password" required>
                      <button type="button" class="password-toggle" id="passwordToggle" aria-label="Show password">
                        <i class="bi bi-eye"></i>
                      </button>
                    </div>
                  </div>

                  <div class="login-meta">
                    <span><i class="bi bi-dot"></i> Protected login session</span>
                    <span><i class="bi bi-dot"></i> Password verified securely</span>
                  </div>

                  <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-success login-submit">Login to Dashboard</button>
                  </div>
                </form>

                <div class="login-note">
                  Need access updates? Contact the administrator to create or adjust your role and company assignment.
                </div>
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
  </main>

  <script>
    (function() {
      var toggle = document.getElementById('passwordToggle');
      var passwordInput = document.getElementById('password');

      if (!toggle || !passwordInput) return;

      toggle.addEventListener('click', function() {
        var isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        toggle.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
        toggle.innerHTML = isPassword ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
      });
    })();
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>
