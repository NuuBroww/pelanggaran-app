<?php
date_default_timezone_set('Asia/Jakarta');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E - Mahkamah | Login Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary: #667eea;
            --primary-dark: #5a6fd8;
            --secondary: #764ba2;
            --accent: #f093fb;
            --text-dark: #2c3e50;
            --text-light: #7f8c8d;
            --bg-light: #f8f9fa;
            --bg-white: #ffffff;
            --border: #e9ecef;
            --shadow: 0 20px 50px rgba(0,0,0,0.1);
            --shadow-hover: 0 25px 60px rgba(0,0,0,0.15);
            --gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-dark: linear-gradient(135deg, #5a6fd8 0%, #6a4b8c 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: var(--gradient);
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Background Animation */
        .bg-animation {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.1;
            background: 
                radial-gradient(circle at 20% 80%, var(--accent) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, #667eea 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, #764ba2 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        /* Login Container */
        .login-container {
            position: relative;
            background: var(--bg-white);
            width: 100%;
            max-width: 440px;
            padding: 40px 35px;
            border-radius: 24px;
            box-shadow: var(--shadow);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            z-index: 2;
            border: 1px solid rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
        }

        .login-container:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-hover);
        }

        /* Header */
        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        /* Logo Container - PERSEGI PANJANG */
        .logo-container {
            width: 180px;  /* Lebar diperbesar */
            height: 80px;  /* Tinggi tetap */
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.2);
            border: 3px solid var(--bg-white);
            overflow: hidden;
            background: var(--bg-white);
            position: relative;
        }

        .logo-img {
            width: 100%;
            height: 100%;
            object-fit: contain; /* Biar logo proporsional */
            border-radius: 8px;
            padding: 8px;
        }

        .logo-placeholder {
            width: 100%;
            height: 100%;
            background: var(--gradient);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--bg-white);
            font-size: 14px;
            font-weight: 700;
            text-align: center;
            padding: 10px;
            line-height: 1.3;
        }

        .login-title {
            color: var(--text-dark);
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 8px;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .login-subtitle {
            color: var(--text-light);
            font-size: 15px;
            font-weight: 500;
            line-height: 1.4;
        }

        /* Form Elements */
        .input-group {
            margin-bottom: 24px;
            position: relative;
        }

        .input-label {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-dark);
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .input-label i {
            color: var(--primary);
            width: 16px;
        }

        .input-field {
            width: 100%;
            padding: 16px 50px 16px 16px;
            border: 2px solid var(--border);
            border-radius: 14px;
            outline: none;
            font-size: 16px;
            transition: all 0.3s ease;
            background: var(--bg-light);
            color: var(--text-dark);
            font-weight: 500;
        }

        .input-field:focus {
            border-color: var(--primary);
            background: var(--bg-white);
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        .input-field::placeholder {
            color: #adb5bd;
            font-weight: 400;
        }

        /* Password Toggle */
        .toggle-password {
            position: absolute;
            right: 16px;
            top: 50px;
            cursor: pointer;
            font-size: 18px;
            color: var(--text-light);
            transition: all 0.3s ease;
            background: none;
            border: none;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toggle-password:hover {
            color: var(--primary);
            transform: scale(1.1);
        }

        /* Button */
        .login-btn {
            width: 100%;
            background: var(--gradient);
            border: none;
            color: white;
            padding: 18px;
            border-radius: 14px;
            font-size: 17px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .login-btn:hover {
            background: var(--gradient-dark);
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(102, 126, 234, 0.5);
        }

        .login-btn:active {
            transform: translateY(-1px);
        }

        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.6s;
        }

        .login-btn:hover::before {
            left: 100%;
        }

        /* Message */
        .message {
            text-align: center;
            color: #e74c3c;
            font-size: 15px;
            margin-bottom: 20px;
            padding: 14px;
            background: #fee;
            border-radius: 12px;
            border: 1px solid #fadbd8;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        /* Back Link */
        .back-link {
            text-align: center;
            margin: 25px 0;
        }

        .back-link a {
            color: var(--primary);
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 10px;
            transition: all 0.3s ease;
            background: rgba(102, 126, 234, 0.1);
        }

        .back-link a:hover {
            background: rgba(102, 126, 234, 0.2);
            transform: translateY(-2px);
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
            color: var(--text-light);
            border-top: 1px solid var(--border);
            padding-top: 25px;
            line-height: 1.6;
        }

        .footer i {
            color: var(--primary);
            margin-bottom: 8px;
        }

        /* Loading Animation */
        .loading {
            display: none;
            text-align: center;
            margin-top: 15px;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid var(--primary);
            border-radius: 50%;
            width: 24px;
            height: 24px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            body {
                padding: 15px;
                align-items: flex-start;
                min-height: 100vh;
            }

            .login-container {
                padding: 30px 25px;
                margin-top: 20px;
                border-radius: 20px;
            }

            /* Logo mobile */
            .logo-container {
                width: 160px;
                height: 70px;
                margin-bottom: 15px;
            }

            .login-title {
                font-size: 24px;
            }

            .login-subtitle {
                font-size: 14px;
            }

            .input-field {
                padding: 14px 45px 14px 14px;
                font-size: 16px;
            }

            .toggle-password {
                top: 46px;
                right: 14px;
            }

            .login-btn {
                padding: 16px;
                font-size: 16px;
            }

            .footer {
                font-size: 13px;
                margin-top: 25px;
                padding-top: 20px;
            }
        }

        @media (max-width: 360px) {
            .login-container {
                padding: 25px 20px;
            }

            .logo-container {
                width: 140px;
                height: 60px;
            }

            .login-title {
                font-size: 22px;
            }

            .input-field {
                padding: 12px 40px 12px 12px;
            }
        }

        /* Enhanced focus states for accessibility */
        .input-field:focus-visible {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }

        .login-btn:focus-visible {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }

        /* Reduced motion for accessibility */
        @media (prefers-reduced-motion: reduce) {
            .bg-animation,
            .login-container,
            .input-field,
            .login-btn {
                animation: none;
                transition: none;
            }
        }
    </style>
</head>
<body>
    <div class="bg-animation"></div>
    
    <div class="login-container">
        <!-- Header dengan Logo -->
        <div class="login-header">
            <div class="logo-container">
                <?php
                $logoPath = FCPATH . 'assets/img/logo.png';
                $logoFound = file_exists($logoPath);
                ?>
                
                <?php if ($logoFound): ?>
                    <img src="<?= base_url('assets/img/logo.png') ?>" 
                         alt="Logo E-Mahkamah" 
                         class="logo-img">
                <?php else: ?>
                    <div class="logo-placeholder">
                        🏛️ E-Mahkamah
                    </div>
                <?php endif; ?>
            </div>
            <h1 class="login-title">E - Mahkamah</h1>
            <p class="login-subtitle">Login Admin - PTD Ar-Rahman</p>
        </div>

        <?php if(session()->getFlashdata('msg')): ?>
            <div class="message">
                <i class="fas fa-exclamation-circle"></i> 
                <?= session()->getFlashdata('msg') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('/login') ?>" method="post" id="loginForm">
            <div class="input-group">
                <label class="input-label">
                    <i class="fas fa-user"></i>
                    <span>Username</span>
                </label>
                <input type="text" 
                       name="username" 
                       id="username" 
                       class="input-field" 
                       placeholder="Masukkan username..." 
                       required 
                       autocomplete="username">
            </div>

            <div class="input-group">
                <label class="input-label">
                    <i class="fas fa-lock"></i>
                    <span>Password</span>
                </label>
                <input type="password" 
                       name="password" 
                       id="password" 
                       class="input-field" 
                       placeholder="Masukkan password..." 
                       required 
                       autocomplete="current-password">
                <button type="button" class="toggle-password" onclick="togglePassword()" aria-label="Toggle password visibility">
                    <i class="fas fa-eye" id="eyeIcon"></i>
                </button>
            </div>

            <button type="submit" class="login-btn" id="loginBtn">
                <i class="fas fa-sign-in-alt"></i>
                <span>Masuk ke Sistem</span>
            </button>
            
            <div class="loading" id="loading">
                <div class="spinner"></div>
                <span>Memproses login...</span>
            </div>
        </form>

        <div class="back-link">
            <a href="<?= base_url('/user_view') ?>">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Tampilan User</span>
            </a>
        </div>

        <div class="footer">
            <i class="fas fa-shield-alt"></i><br>
            Sistem Manajemen Pelanggaran Santri<br>
            <small>PTD Ar-Rahman © <?= date('Y') ?></small>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if(passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.className = 'fas fa-eye-slash';
                eyeIcon.setAttribute('aria-label', 'Sembunyikan password');
            } else {
                passwordInput.type = 'password';
                eyeIcon.className = 'fas fa-eye';
                eyeIcon.setAttribute('aria-label', 'Tampilkan password');
            }
        }

        // Form submission dengan loading state
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const loginBtn = document.getElementById('loginBtn');
            const loading = document.getElementById('loading');
            
            // Validasi cepat
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();
            
            if (!username || !password) {
                e.preventDefault();
                return;
            }
            
            // Tampilkan loading
            loginBtn.style.display = 'none';
            loading.style.display = 'flex';
            
            // Safety timeout
            setTimeout(() => {
                if (loading.style.display === 'flex') {
                    loginBtn.style.display = 'flex';
                    loading.style.display = 'none';
                }
            }, 8000);
        });

        // Enhanced focus management
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.input-field');
            
            inputs.forEach(input => {
                // Add focus effects
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'translateY(-2px)';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'translateY(0)';
                });
                
                // Add input validation styling
                input.addEventListener('input', function() {
                    if (this.value.trim() !== '') {
                        this.style.background = 'var(--bg-white)';
                    } else {
                        this.style.background = 'var(--bg-light)';
                    }
                });
            });

            // Auto-focus username field
            const usernameField = document.getElementById('username');
            if (usernameField) {
                setTimeout(() => {
                    usernameField.focus();
                }, 400);
            }
        });

        // Add keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl + / to focus password field
            if (e.ctrlKey && e.key === '/') {
                e.preventDefault();
                document.getElementById('password')?.focus();
            }
            
            // Escape to blur current field
            if (e.key === 'Escape') {
                document.activeElement?.blur();
            }
        });

        console.log('🔄 Login page loaded successfully');
    </script>
</body>
</html>