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
            --primary: #10b981;
            --primary-dark: #059669;
            --secondary: #34d399;
            --accent: #6ee7b7;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --bg-light: #f9fafb;
            --bg-white: #ffffff;
            --border: #d1d5db;
            --shadow: 0 20px 50px rgba(16, 185, 129, 0.1);
            --shadow-hover: 0 25px 60px rgba(16, 185, 129, 0.15);
            --gradient: linear-gradient(135deg, #10b981 0%, #34d399 100%);
            --gradient-dark: linear-gradient(135deg, #059669 0%, #10b981 100%);
            --success: #10b981;
            --warning: #f59e0b;
            --error: #ef4444;
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
                radial-gradient(circle at 80% 20%, #10b981 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, #34d399 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        /* Floating Elements */
        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        .floating-element {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: floatElement 15s ease-in-out infinite;
        }

        .floating-element:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-element:nth-child(2) {
            width: 60px;
            height: 60px;
            top: 70%;
            left: 80%;
            animation-delay: -5s;
        }

        .floating-element:nth-child(3) {
            width: 100px;
            height: 100px;
            top: 50%;
            left: 5%;
            animation-delay: -10s;
        }

        @keyframes floatElement {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-30px) rotate(120deg); }
            66% { transform: translateY(20px) rotate(240deg); }
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
            border: 1px solid rgba(255, 255, 255, 0.3);
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

        /* Logo Container */
        .logo-container {
            width: 180px;
            height: 80px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.2);
            border: 3px solid var(--bg-white);
            overflow: hidden;
            background: var(--bg-white);
            position: relative;
            transition: all 0.3s ease;
        }

        .logo-container:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 30px rgba(16, 185, 129, 0.3);
        }

        .logo-img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 12px;
            padding: 8px;
        }

        .logo-placeholder {
            width: 100%;
            height: 100%;
            background: var(--gradient);
            border-radius: 12px;
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
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
            transform: translateY(-2px);
        }

        .input-field::placeholder {
            color: #9ca3af;
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
            border-radius: 6px;
        }

        .toggle-password:hover {
            color: var(--primary);
            background: rgba(16, 185, 129, 0.1);
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
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
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
            box-shadow: 0 12px 30px rgba(16, 185, 129, 0.5);
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

        .login-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        /* Message Styles */
        .message {
            text-align: center;
            padding: 14px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: 1px solid;
        }

        .message.error {
            color: var(--error);
            background: #fef2f2;
            border-color: #fecaca;
        }

        .message.success {
            color: var(--success);
            background: #f0fdf4;
            border-color: #bbf7d0;
        }

        .message.warning {
            color: var(--warning);
            background: #fffbeb;
            border-color: #fed7aa;
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
            background: rgba(16, 185, 129, 0.1);
        }

        .back-link a:hover {
            background: rgba(16, 185, 129, 0.2);
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

        /* Progress Bar */
        .progress-bar {
            width: 100%;
            height: 4px;
            background: #e5e7eb;
            border-radius: 2px;
            overflow: hidden;
            margin-top: 10px;
        }

        .progress-fill {
            height: 100%;
            background: var(--gradient);
            width: 0%;
            transition: width 0.3s ease;
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

        .toggle-password:focus-visible {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }

        /* Reduced motion for accessibility */
        @media (prefers-reduced-motion: reduce) {
            .bg-animation,
            .floating-element,
            .login-container,
            .input-field,
            .login-btn,
            .logo-container {
                animation: none;
                transition: none;
            }
        }

        /* Password strength indicator */
        .password-strength {
            margin-top: 8px;
            font-size: 12px;
            display: none;
        }

        .strength-bar {
            height: 4px;
            border-radius: 2px;
            margin-top: 4px;
            transition: all 0.3s ease;
        }

        .strength-weak { background: #ef4444; width: 33%; }
        .strength-medium { background: #f59e0b; width: 66%; }
        .strength-strong { background: #10b981; width: 100%; }
    </style>
</head>
<body>
    <div class="bg-animation"></div>
    <div class="floating-elements">
        <div class="floating-element"></div>
        <div class="floating-element"></div>
        <div class="floating-element"></div>
    </div>
    
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
                         class="logo-img"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="logo-placeholder" style="display: none;">
                        🏛️ E-Mahkamah
                    </div>
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
            <div class="message error">
                <i class="fas fa-exclamation-circle"></i> 
                <?= session()->getFlashdata('msg') ?>
            </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="message success">
                <i class="fas fa-check-circle"></i> 
                <?= session()->getFlashdata('success') ?>
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
                       autocomplete="username"
                       minlength="3"
                       maxlength="50">
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
                       autocomplete="current-password"
                       minlength="6"
                       maxlength="100">
                <button type="button" class="toggle-password" onclick="togglePassword()" aria-label="Toggle password visibility">
                    <i class="fas fa-eye" id="eyeIcon"></i>
                </button>
                
                <!-- Password Strength Indicator -->
                <div class="password-strength" id="passwordStrength">
                    <div>Kekuatan password: <span id="strengthText">-</span></div>
                    <div class="strength-bar" id="strengthBar"></div>
                </div>
            </div>

            <button type="submit" class="login-btn" id="loginBtn">
                <i class="fas fa-sign-in-alt"></i>
                <span>Masuk ke Sistem</span>
            </button>
            
            <!-- Progress Bar -->
            <div class="progress-bar">
                <div class="progress-fill" id="progressFill"></div>
            </div>
            
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
        // Password visibility toggle
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

        // Password strength checker
        function checkPasswordStrength(password) {
            let strength = 0;
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');
            const passwordStrength = document.getElementById('passwordStrength');
            
            if (password.length === 0) {
                passwordStrength.style.display = 'none';
                return;
            }
            
            passwordStrength.style.display = 'block';

            // Length check
            if (password.length >= 6) strength++;
            if (password.length >= 8) strength++;
            
            // Character variety checks
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            // Update UI
            strengthBar.className = 'strength-bar';
            if (strength <= 2) {
                strengthBar.classList.add('strength-weak');
                strengthText.textContent = 'Lemah';
                strengthText.style.color = '#ef4444';
            } else if (strength <= 4) {
                strengthBar.classList.add('strength-medium');
                strengthText.textContent = 'Sedang';
                strengthText.style.color = '#f59e0b';
            } else {
                strengthBar.classList.add('strength-strong');
                strengthText.textContent = 'Kuat';
                strengthText.style.color = '#10b981';
            }
        }

        // Form submission dengan loading state
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const loginBtn = document.getElementById('loginBtn');
            const loading = document.getElementById('loading');
            const progressFill = document.getElementById('progressFill');
            
            // Validasi form
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();
            
            if (!username || !password) {
                e.preventDefault();
                showMessage('Harap isi semua field!', 'error');
                return;
            }

            if (username.length < 3) {
                e.preventDefault();
                showMessage('Username minimal 3 karakter!', 'error');
                return;
            }

            if (password.length < 6) {
                e.preventDefault();
                showMessage('Password minimal 6 karakter!', 'error');
                return;
            }
            
            // Tampilkan loading
            loginBtn.disabled = true;
            loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Memproses...</span>';
            loading.style.display = 'flex';
            
            // Animate progress bar
            let progress = 0;
            const progressInterval = setInterval(() => {
                progress += 5;
                progressFill.style.width = progress + '%';
                if (progress >= 90) clearInterval(progressInterval);
            }, 100);
            
            // Safety timeout
            setTimeout(() => {
                if (loading.style.display === 'flex') {
                    resetFormState();
                    showMessage('Timeout: Silakan coba lagi', 'error');
                }
            }, 10000);
        });

        function resetFormState() {
            const loginBtn = document.getElementById('loginBtn');
            const loading = document.getElementById('loading');
            const progressFill = document.getElementById('progressFill');
            
            loginBtn.disabled = false;
            loginBtn.innerHTML = '<i class="fas fa-sign-in-alt"></i><span>Masuk ke Sistem</span>';
            loading.style.display = 'none';
            progressFill.style.width = '0%';
        }

        function showMessage(message, type) {
            // Remove existing messages
            const existingMessages = document.querySelectorAll('.message');
            existingMessages.forEach(msg => msg.remove());
            
            // Create new message
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${type}`;
            messageDiv.innerHTML = `
                <i class="fas fa-${type === 'error' ? 'exclamation-circle' : 'check-circle'}"></i>
                ${message}
            `;
            
            // Insert after header
            const header = document.querySelector('.login-header');
            header.parentNode.insertBefore(messageDiv, header.nextSibling);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (messageDiv.parentNode) {
                    messageDiv.style.opacity = '0';
                    messageDiv.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => messageDiv.remove(), 500);
                }
            }, 5000);
        }

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
                        this.style.borderColor = 'var(--primary)';
                    } else {
                        this.style.background = 'var(--bg-light)';
                        this.style.borderColor = 'var(--border)';
                    }
                    
                    // Password strength check
                    if (this.id === 'password') {
                        checkPasswordStrength(this.value);
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
            
            // Enter to submit form when both fields are filled
            if (e.key === 'Enter' && e.target.type !== 'textarea') {
                const username = document.getElementById('username').value.trim();
                const password = document.getElementById('password').value.trim();
                if (username && password) {
                    document.getElementById('loginForm').requestSubmit();
                }
            }
        });

        // Handle page visibility changes
        document.addEventListener('visibilitychange', function() {
            if (document.visibilityState === 'visible') {
                // Reset form state when user returns to tab
                resetFormState();
            }
        });

        console.log('✅ Login page loaded successfully - Hijau Muda Theme');
    </script>
</body>
</html>