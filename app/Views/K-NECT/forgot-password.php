<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - K-NECT</title>
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

            <!-- Forgot Password Card -->
            <div class="bg-white rounded-3xl shadow-2xl p-8">
                <!-- Header -->
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Forgot Password?</h3>
                    <p class="text-gray-600 text-sm" id="headerDescription">Enter your username to verify your account.</p>
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

                <!-- Step 1: Username Form -->
                <form id="usernameForm" action="<?= base_url('verify-username') ?>" method="post" class="space-y-4">
                    <!-- Username Field -->
                    <div class="space-y-2">
                        <label for="username" class="block text-gray-700 text-sm font-medium">Username</label>
                        <div class="relative input-container">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <input 
                                type="text" 
                                id="username" 
                                name="username" 
                                placeholder="Enter your username" 
                                required
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div id="usernameError" class="mt-1 text-red-600 text-xs hidden"></div>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="btn-hover w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <span class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Verify Username</span>
                        </span>
                    </button>
                </form>

                <!-- Step 2: Email Form (Hidden Initially) -->
                <form id="emailForm" action="<?= base_url('send-reset-email') ?>" method="post" class="space-y-4 hidden">
                    <input type="hidden" id="verified_username" name="username" value="">
                    <input type="hidden" id="account_type" name="account_type" value="">
                    
                    <!-- Account Type Display -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p class="text-blue-800 font-medium text-sm">Account Found</p>
                                <p class="text-blue-700 text-xs">Username: <span id="displayUsername" class="font-semibold"></span> | Type: <span id="displayAccountType" class="font-semibold"></span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Email Field -->
                    <div class="space-y-2">
                        <label for="email" class="block text-gray-700 text-sm font-medium">Email Address</label>
                        <p class="text-gray-500 text-xs mb-2">Enter the registered email for this <span id="accountTypeLabel"></span> account.</p>
                        <div class="relative input-container">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                placeholder="Enter your registered email address" 
                                required
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div id="emailError" class="mt-1 text-red-600 text-xs hidden"></div>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="btn-hover w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <span class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span>Send Reset Link</span>
                        </span>
                    </button>

                    <!-- Back Button -->
                    <button 
                        type="button" 
                        id="backToUsername"
                        class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-4 rounded-lg focus:outline-none transition-colors">
                        <span class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span>Back to Username</span>
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
        // Form elements
        const usernameForm = document.getElementById('usernameForm');
        const emailForm = document.getElementById('emailForm');
        const usernameInput = document.getElementById('username');
        const emailInput = document.getElementById('email');
        const usernameError = document.getElementById('usernameError');
        const emailError = document.getElementById('emailError');
        const headerDescription = document.getElementById('headerDescription');
        const backToUsernameBtn = document.getElementById('backToUsername');

        // Helper functions
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

        function setLoadingState(form, loading, buttonText) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (loading) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <span class="flex items-center justify-center space-x-2">
                        <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Verifying...</span>
                    </span>
                `;
            } else {
                submitBtn.disabled = false;
                submitBtn.innerHTML = buttonText;
            }
        }

        // Clear errors on input
        usernameInput.addEventListener('input', () => clearFieldError(usernameInput, usernameError));
        emailInput.addEventListener('input', () => clearFieldError(emailInput, emailError));

        // Step 1: Username verification
        usernameForm.addEventListener('submit', function(e) {
            e.preventDefault();
            clearFieldError(usernameInput, usernameError);

            const username = usernameInput.value.trim();

            if (!username) {
                setFieldError(usernameInput, usernameError, 'Username is required.');
                return;
            }

            const originalButtonText = `
                <span class="flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Verify Username</span>
                </span>
            `;

            setLoadingState(usernameForm, true, originalButtonText);

            fetch(usernameForm.action, {
                method: 'POST',
                body: new FormData(usernameForm),
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                setLoadingState(usernameForm, false, originalButtonText);

                if (data.success) {
                    // Store username and account type
                    document.getElementById('verified_username').value = username;
                    document.getElementById('account_type').value = data.account_type;
                    document.getElementById('displayUsername').textContent = username;
                    document.getElementById('displayAccountType').textContent = data.account_type_label;
                    document.getElementById('accountTypeLabel').textContent = data.account_type_label;

                    // Switch to email form
                    usernameForm.classList.add('hidden');
                    emailForm.classList.remove('hidden');
                    headerDescription.textContent = 'Enter the registered email for your ' + data.account_type_label + ' account.';
                    emailInput.focus();
                } else {
                    setFieldError(usernameInput, usernameError, data.message || 'Username not found.');
                }
            })
            .catch(error => {
                setLoadingState(usernameForm, false, originalButtonText);
                setFieldError(usernameInput, usernameError, 'Unable to connect to server. Please try again.');
            });
        });

        // Step 2: Email verification and send reset link
        emailForm.addEventListener('submit', function(e) {
            e.preventDefault();
            clearFieldError(emailInput, emailError);

            const email = emailInput.value.trim();

            if (!email) {
                setFieldError(emailInput, emailError, 'Email address is required.');
                return;
            }

            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                setFieldError(emailInput, emailError, 'Please enter a valid email address.');
                return;
            }

            const originalButtonText = `
                <span class="flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span>Send Reset Link</span>
                </span>
            `;

            setLoadingState(emailForm, true, originalButtonText);

            fetch(emailForm.action, {
                method: 'POST',
                body: new FormData(emailForm),
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                setLoadingState(emailForm, false, originalButtonText);

                if (data.success) {
                    const submitBtn = emailForm.querySelector('button[type="submit"]');
                    submitBtn.innerHTML = `
                        <span class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Sent!</span>
                        </span>
                    `;
                    
                    // Show success message
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'mb-4 p-4 bg-green-50 border border-green-200 rounded-lg';
                    alertDiv.innerHTML = `
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <p class="text-green-700 text-sm">${data.message}</p>
                        </div>
                    `;
                    emailForm.insertBefore(alertDiv, emailForm.firstChild);
                    
                    // Clear email input
                    emailInput.value = '';
                } else {
                    setFieldError(emailInput, emailError, data.message || 'An error occurred. Please try again.');
                }
            })
            .catch(error => {
                setLoadingState(emailForm, false, originalButtonText);
                setFieldError(emailInput, emailError, 'Unable to connect to server. Please try again.');
            });
        });

        // Back to username button
        backToUsernameBtn.addEventListener('click', function() {
            emailForm.classList.add('hidden');
            usernameForm.classList.remove('hidden');
            headerDescription.textContent = 'Enter your username to verify your account.';
            emailInput.value = '';
            clearFieldError(emailInput, emailError);
            usernameInput.focus();
        });
    </script>
</body>
</html>
