<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= htmlspecialchars(material_symbols_url(), ENT_QUOTES) ?>" />
    <style>
        :root {
            --primary-color: #FF8F56;
            --primary-dark: #e67a42;
            --bg-gradient: linear-gradient(135deg, #FF8F56 0%, #FFB38E 100%);
        }
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
        }

        .login-container {
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }

        .login-card {
            background: white;
            border-radius: 24px;
            border: none;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--bg-gradient);
        }

        .brand-logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .brand-logo img {
            max-width: 150px;
        }

        .form-label {
            font-weight: 600;
            color: #444;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 16px;
            border: 1px solid #e0e0e0;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(255, 143, 86, 0.1);
        }

        .btn-login {
            background: var(--bg-gradient);
            border: none;
            border-radius: 12px;
            padding: 12px;
            color: white;
            font-weight: 700;
            font-size: 1rem;
            width: 100%;
            margin-top: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(255, 143, 86, 0.2);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px rgba(255, 143, 86, 0.3);
            color: white;
        }

        .login-footer {
            text-align: center;
            margin-top: 30px;
            color: #888;
            font-size: 0.85rem;
        }

        .input-group-text {
            background: none;
            border-right: none;
            color: #adb5bd;
        }

        .input-group .form-control {
            border-left: none;
        }

        /* Float background elements */
        .shape {
            position: absolute;
            z-index: -1;
            opacity: 0.1;
        }
        .shape-1 { top: -50px; right: -50px; width: 200px; height: 200px; background: var(--primary-color); border-radius: 50%; }
        .shape-2 { bottom: -80px; left: -80px; width: 250px; height: 250px; background: #FFB38E; border-radius: 50%; }
    </style>
</head>
<body>
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>

    <div class="login-container">
        <div class="login-card">
            <div class="brand-logo">
                <img src="<?= ASSETS_URL; ?>img/Logo-GoSirk-01.png" alt="GoSirk Logo">
                <h4 class="fw-bold mt-4">Welcome Back</h4>
                <p class="text-muted small">Please enter your credentials to continue</p>
            </div>

            <div class="mb-3">
                <?php Flasher::flash(); ?>
            </div>

            <form action="<?= BASE_URL; ?>auth/login" method="POST">
                <?= Csrf::field() ?>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <span class="material-symbols-outlined fs-5">person</span>
                        </span>
                        <input type="text" class="form-control" id="username" name="username" placeholder="admin" required autofocus>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <span class="material-symbols-outlined fs-5">lock</span>
                        </span>
                        <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="mb-4 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label small text-muted" for="remember">Remember me</label>
                    </div>
                    <a href="<?= BASE_URL; ?>auth/forgot_password" class="small text-decoration-none" style="color: var(--primary-color);">Forgot Password?</a>
                </div>

                <button type="submit" class="btn btn-login">Sign In</button>
            </form>

            <div class="login-footer">
                &copy; <?= date('Y'); ?> GoSirk. All rights reserved.
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
