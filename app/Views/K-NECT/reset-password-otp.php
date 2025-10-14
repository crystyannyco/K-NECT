<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>K-Nect - Youth Governance System</title>
    
    <!-- Open Graph Meta Tags for Link Sharing -->
    <meta property="og:title" content="K-Nect - Youth Governance System">
    <meta property="og:description" content="Unified youth engagement platform for announcements, events, resources, and data-driven community impact.">
    <meta property="og:image" content="<?= base_url('assets/images/K-Nect-Logo.png') ?>">
    <meta property="og:url" content="<?= current_url() ?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="K-Nect">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="K-Nect - Youth Governance System">
    <meta name="twitter:description" content="Unified youth engagement platform for announcements, events, resources, and data-driven community impact.">
    <meta name="twitter:image" content="<?= base_url('assets/images/K-Nect-Logo.png') ?>">
    
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .login-bg {
            background-image: url('<?= base_url('assets/images/background.png') ?>');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .login-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1;
        }
        .content {
            position: relative;
            z-index: 2;
        }
        .input-container {
            transition: all 0.3s ease;
        }
        .input-container:focus-within {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
        }
        .btn-hover {
            transition: all 0.3s ease;
        }
        .btn-hover:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
        }
        .glow-logo {
            filter: drop-shadow(0 0 12px rgba(255, 255, 255, 0.7)) drop-shadow(0 0 24px rgba(255, 255, 255, 0.35));
        }
        .strength-indicator {
            height: 4px;
            transition: all 0.3s ease;
        }
    </style>
</head>
<body class="login-bg min-h-screen relative">
    <div class="min-h-screen flex items-center justify-center content p-6">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="text-center mb-6">
                <img src="<?= base_url('/assets/images/K-Nect-Logo.png') ?>" alt="K-NECT Logo" class="w-40 mx-auto mb-3 drop-shadow-xl glow-logo" />
                <h2 class="text-xl font-semibold text-white">KABATAAN CONNECT</h2>
            </div>

            <!-- Reset Password Card -->
            <div class="bg-white rounded-3xl shadow-2xl p-8">
                <!-- Header -->
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Reset Password</h3>
                    <p class="text-gray-600 text-sm">Enter your new password below.</p>
                </div>

                <!-- Success/Error Messages -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <p class="text-green-700 text-sm"><?= session()->getFlashdata('success') ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <p class="text-red-700 text-sm"><?= session()->getFlashdata('error') ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Success Message Display -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <p class="text-green-800 font-medium text-sm">OTP Verified Successfully</p>
                            <p class="text-green-700 text-xs">You can now set a new password for your account</p>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <form id="resetPasswordForm" action="<?= base_url('process-reset-password-otp') ?>" method="post" class="space-y-4">
                    <?= csrf_field() ?>
                    <input type="hidden" name="token" value="<?= esc($token ?? '') ?>">

                    <!-- New Password Field -->
                    <div class="space-y-2">
                        <label for="password" class="block text-gray-700 text-sm font-medium">New Password</label>
                        <div class="relative input-container">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                placeholder="Enter new password" 
                                required
                                class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center z-10">
                                <svg id="eyeIconOpen" class="h-5 w-5 text-gray-400 hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg id="eyeIconClosed" class="h-5 w-5 text-gray-400 hover:text-gray-600 transition-colors hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                </svg>
                            </button>
                        </div>
                        <!-- Password Strength Indicator -->
                        <div class="mt-2">
                            <div class="flex gap-1">
                                <div id="strength-bar-1" class="flex-1 strength-indicator bg-gray-200 rounded"></div>
                                <div id="strength-bar-2" class="flex-1 strength-indicator bg-gray-200 rounded"></div>
                                <div id="strength-bar-3" class="flex-1 strength-indicator bg-gray-200 rounded"></div>
                                <div id="strength-bar-4" class="flex-1 strength-indicator bg-gray-200 rounded"></div>
                            </div>
                            <p id="strength-text" class="text-xs mt-1 text-gray-500"></p>
                        </div>
                        <div id="passwordError" class="mt-1 text-red-600 text-xs hidden"></div>
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="space-y-2">
                        <label for="confirm_password" class="block text-gray-700 text-sm font-medium">Confirm Password</label>
                        <div class="relative input-container">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input 
                                type="password" 
                                id="confirm_password" 
                                name="confirm_password" 
                                placeholder="Confirm new password" 
                                required
                                class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <button type="button" id="toggleConfirmPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center z-10">
                                <svg id="eyeIconOpen2" class="h-5 w-5 text-gray-400 hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg id="eyeIconClosed2" class="h-5 w-5 text-gray-400 hover:text-gray-600 transition-colors hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                </svg>
                            </button>
                        </div>
                        <div id="confirmPasswordError" class="mt-1 text-red-600 text-xs hidden"></div>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="btn-hover w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <span class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Reset Password</span>
                        </span>
                    </button>
                </form>

                <!-- Back to Login Link -->
                <div class="mt-6 text-center">
                    <a href="<?= base_url('login') ?>" class="text-blue-600 hover:text-blue-700 font-medium transition-colors text-sm inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Login
                    </a>
                </div>

                <!-- Footer -->
                <div class="text-center mt-6 pt-4 border-t border-gray-100">
                    <p class="text-gray-400 text-xs">
                        © 2025 K-NECT: A Youth Governance System. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirm_password');
        const passwordError = document.getElementById('passwordError');
        const confirmPasswordError = document.getElementById('confirmPasswordError');
        const form = document.getElementById('resetPasswordForm');

        // Password strength checker
        function checkPasswordStrength(password) {
            let strength = 0;
            const bars = [
                document.getElementById('strength-bar-1'),
                document.getElementById('strength-bar-2'),
                document.getElementById('strength-bar-3'),
                document.getElementById('strength-bar-4')
            ];
            const strengthText = document.getElementById('strength-text');

            // Reset bars
            bars.forEach(bar => {
                bar.className = 'flex-1 strength-indicator bg-gray-200 rounded';
            });

            if (password.length === 0) {
                strengthText.textContent = '';
                return;
            }

            // Check criteria
            if (password.length >= 6) strength++;
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/\d/.test(password)) strength++;
            if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>?]/.test(password)) strength++;

            // Normalize to 0-4 scale
            strength = Math.min(4, strength);

            // Update bars and text
            if (strength === 1) {
                bars[0].className = 'flex-1 strength-indicator bg-red-500 rounded';
                strengthText.textContent = 'Weak password';
                strengthText.className = 'text-xs mt-1 text-red-500';
            } else if (strength === 2) {
                bars[0].className = 'flex-1 strength-indicator bg-orange-500 rounded';
                bars[1].className = 'flex-1 strength-indicator bg-orange-500 rounded';
                strengthText.textContent = 'Fair password';
                strengthText.className = 'text-xs mt-1 text-orange-500';
            } else if (strength === 3) {
                bars[0].className = 'flex-1 strength-indicator bg-yellow-500 rounded';
                bars[1].className = 'flex-1 strength-indicator bg-yellow-500 rounded';
                bars[2].className = 'flex-1 strength-indicator bg-yellow-500 rounded';
                strengthText.textContent = 'Good password';
                strengthText.className = 'text-xs mt-1 text-yellow-600';
            } else if (strength === 4) {
                bars[0].className = 'flex-1 strength-indicator bg-green-500 rounded';
                bars[1].className = 'flex-1 strength-indicator bg-green-500 rounded';
                bars[2].className = 'flex-1 strength-indicator bg-green-500 rounded';
                bars[3].className = 'flex-1 strength-indicator bg-green-500 rounded';
                strengthText.textContent = 'Strong password';
                strengthText.className = 'text-xs mt-1 text-green-600';
            }
        }

        passwordInput.addEventListener('input', function() {
            checkPasswordStrength(this.value);
            clearFieldError(passwordInput, passwordError);
        });

        confirmPasswordInput.addEventListener('input', function() {
            clearFieldError(confirmPasswordInput, confirmPasswordError);
        });

        function clearFieldError(input, errorEl) {
            input.classList.remove('border-red-500');
            errorEl.textContent = '';
            errorEl.classList.add('hidden');
        }

        function setFieldError(input, errorEl, message) {
            input.classList.add('border-red-500');
            errorEl.textContent = message;
            errorEl.classList.remove('hidden');
        }

        function setLoadingState(loading) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (loading) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <span class="flex items-center justify-center space-x-2">
                        <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Resetting...</span>
                    </span>
                `;
            } else {
                submitBtn.disabled = false;
                submitBtn.innerHTML = `
                    <span class="flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Reset Password</span>
                    </span>
                `;
            }
        }

        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            document.getElementById('eyeIconOpen').classList.toggle('hidden');
            document.getElementById('eyeIconClosed').classList.toggle('hidden');
        });

        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            const type = confirmPasswordInput.type === 'password' ? 'text' : 'password';
            confirmPasswordInput.type = type;
            document.getElementById('eyeIconOpen2').classList.toggle('hidden');
            document.getElementById('eyeIconClosed2').classList.toggle('hidden');
        });

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;

            // Clear previous errors
            clearFieldError(passwordInput, passwordError);
            clearFieldError(confirmPasswordInput, confirmPasswordError);

            let hasError = false;

            // Validate password
            if (password.length < 6) {
                setFieldError(passwordInput, passwordError, 'Password must be at least 6 characters long.');
                hasError = true;
            }

            // Check if passwords match
            if (password !== confirmPassword) {
                setFieldError(confirmPasswordInput, confirmPasswordError, 'Passwords do not match.');
                hasError = true;
            }

            if (hasError) return;

            setLoadingState(true);

            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                setLoadingState(false);

                if (data.success) {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    submitBtn.innerHTML = `
                        <span class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Success!</span>
                        </span>
                    `;
                    setTimeout(() => {
                        window.location.href = data.redirect || '<?= base_url('login') ?>';
                    }, 1000);
                } else {
                    setFieldError(passwordInput, passwordError, data.message || 'An error occurred. Please try again.');
                }
            })
            .catch(error => {
                setLoadingState(false);
                setFieldError(passwordInput, passwordError, 'Unable to connect to server. Please try again.');
            });
        });
    </script>
</body>
</html>
