<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
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
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1;
        }
        .login-content { position: relative; z-index: 2; }
        .input-container { transition: all 0.3s ease; }
        .input-container:focus-within { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15); }
        .input-focus { transition: all 0.3s ease; }
        .btn-hover { transition: all 0.3s ease; }
        .btn-hover:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25); }
    </style>
    <!-- Security headers to prevent caching -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    
</head>
<body class="login-bg min-h-screen relative">
    <div class="min-h-screen flex items-center justify-center login-content p-4">
        <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl p-6 lg:p-8">
        <div class="text-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Change Password</h2>
            <p class="text-gray-600 mt-2">Welcome, <span class="font-semibold text-blue-600"><?= esc($username) ?></span>!</p>
            <p class="text-sm text-gray-500 mt-1">
                <?php if ($user_type === 'sk'): ?>
                    As an SK Official, you must change your password before accessing the dashboard.
                <?php else: ?>
                    As a Pederasyon Officer, you must change your password before accessing the dashboard.
                <?php endif; ?>
            </p>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <form id="changePasswordForm" action="<?= base_url('change-password-process') ?>" method="post" class="space-y-4" novalidate>
            <div>
                <label for="new_password" class="block text-gray-700 font-medium">New Password</label>
                <div class="relative input-container">
                    <input type="password" id="new_password" name="new_password" 
                           class="input-focus mt-1 block w-full px-3 py-3 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           required minlength="8" placeholder="Enter your new password">
                    <button type="button" id="toggle_new_password" aria-label="Show password" title="Show password"
                            class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none z-10 cursor-pointer">
                        <!-- Eye (show) -->
                        <svg class="icon-eye-open h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <!-- Eye off (hide) -->
                        <svg class="icon-eye-closed h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.584 10.587a2 2 0 002.828 2.83"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.363 5.365A9.466 9.466 0 0112 5c4.478 0 8.268 2.943 9.542 7a18.057 18.057 0 01-1.065 3"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.83 9.17a3 3 0 00-4.243 4.243"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.35 8.596A17.954 17.954 0 002.458 12C3.732 16.057 7.523 19 12 19c1.898 0 3.683-.508 5.241-1.392"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Password Strength Indicator -->
                <div id="password-strength" class="mt-2 hidden">
                    <div class="flex items-center space-x-2">
                        <div class="flex space-x-1">
                            <div id="strength-bar-1" class="w-6 h-1 bg-gray-300 rounded"></div>
                            <div id="strength-bar-2" class="w-6 h-1 bg-gray-300 rounded"></div>
                            <div id="strength-bar-3" class="w-6 h-1 bg-gray-300 rounded"></div>
                            <div id="strength-bar-4" class="w-6 h-1 bg-gray-300 rounded"></div>
                        </div>
                        <span id="strength-text" class="text-xs font-medium">Weak</span>
                    </div>
                    <div id="password-requirements" class="mt-2 space-y-1 text-xs">
                        <div id="req-length" class="flex items-center space-x-2">
                            <span class="requirement-icon">✗</span>
                            <span class="text-gray-600">At least 8 characters</span>
                        </div>
                        <div id="req-uppercase" class="flex items-center space-x-2">
                            <span class="requirement-icon">✗</span>
                            <span class="text-gray-600">One uppercase letter</span>
                        </div>
                        <div id="req-lowercase" class="flex items-center space-x-2">
                            <span class="requirement-icon">✗</span>
                            <span class="text-gray-600">One lowercase letter</span>
                        </div>
                        <div id="req-number" class="flex items-center space-x-2">
                            <span class="requirement-icon">✗</span>
                            <span class="text-gray-600">One number</span>
                        </div>
                        <div id="req-special" class="flex items-center space-x-2">
                            <span class="requirement-icon">✗</span>
                            <span class="text-gray-600">One special character</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div>
                <label for="confirm_password" class="block text-gray-700 font-medium">Confirm New Password</label>
                <div class="relative input-container">
                    <input type="password" id="confirm_password" name="confirm_password" 
                           class="input-focus mt-1 block w-full px-3 py-3 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           required minlength="8" placeholder="Confirm your new password">
                    <button type="button" id="toggle_confirm_password" aria-label="Show password" title="Show password"
                            class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none z-10 cursor-pointer">
                        <!-- Eye (show) -->
                        <svg class="icon-eye-open h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <!-- Eye off (hide) -->
                        <svg class="icon-eye-closed h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.584 10.587a2 2 0 002.828 2.83"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.363 5.365A9.466 9.466 0 0112 5c4.478 0 8.268 2.943 9.542 7a18.057 18.057 0 01-1.065 3"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.83 9.17a3 3 0 00-4.243 4.243"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.35 8.596A17.954 17.954 0 002.458 12C3.732 16.057 7.523 19 12 19c1.898 0 3.683-.508 5.241-1.392"></path>
                        </svg>
                    </button>
                </div>
                <div id="confirm-password-error" class="mt-1 text-xs text-red-500 hidden">
                    Passwords do not match
                </div>
                <div id="confirm-password-success" class="mt-1 text-xs text-green-500 hidden">
                    Passwords match
                </div>
            </div>
            
            <button type="submit" id="submitButton" class="btn-hover w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500">
                Change Password & Continue
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="<?= base_url('login') ?>" class="text-sm text-gray-500 hover:text-gray-700">
                Back to Login
            </a>
        </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <div id="loadingModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full text-center">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mx-auto mb-4"></div>
            <p>Changing password...</p>
        </div>
    </div>

    <!-- Message Modal -->
    <div id="messageModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full text-center">
            <h3 id="messageTitle" class="text-xl font-bold mb-2"></h3>
            <p id="messageText" class="mb-4"></p>
            <button id="closeMessageBtn" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">OK</button>
        </div>
    </div>

    <script>
    // Password Strength Validation
    function initializePasswordValidation() {
        const passwordInput = document.getElementById('new_password');
        const strengthContainer = document.getElementById('password-strength');
        const strengthText = document.getElementById('strength-text');
        const strengthBars = [
            document.getElementById('strength-bar-1'),
            document.getElementById('strength-bar-2'),
            document.getElementById('strength-bar-3'),
            document.getElementById('strength-bar-4')
        ];
        
        const requirements = {
            length: document.getElementById('req-length'),
            uppercase: document.getElementById('req-uppercase'),
            lowercase: document.getElementById('req-lowercase'),
            number: document.getElementById('req-number'),
            special: document.getElementById('req-special')
        };
        
        if (passwordInput) {
            passwordInput.addEventListener('input', function(e) {
                const password = e.target.value;
                
                if (password.length > 0) {
                    strengthContainer.classList.remove('hidden');
                } else {
                    strengthContainer.classList.add('hidden');
                    return;
                }
                
                // Check requirements
                const checks = {
                    length: password.length >= 8,
                    uppercase: /[A-Z]/.test(password),
                    lowercase: /[a-z]/.test(password),
                    number: /\d/.test(password),
                    special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>?]/.test(password)
                };
                
                // Update requirement indicators
                Object.keys(checks).forEach(req => {
                    const element = requirements[req];
                    const icon = element.querySelector('.requirement-icon');
                    const text = element.querySelector('span:last-child');
                    
                    if (checks[req]) {
                        icon.textContent = '✓';
                        icon.style.color = '#10b981';
                        text.style.color = '#10b981';
                    } else {
                        icon.textContent = '✗';
                        icon.style.color = '#ef4444';
                        text.style.color = '#6b7280';
                    }
                });
                
                // Calculate strength
                const score = Object.values(checks).filter(Boolean).length;
                let strength = 'Weak';
                let color = '#ef4444'; // red
                
                if (score >= 5) {
                    strength = 'Very Strong';
                    color = '#10b981'; // green
                } else if (score >= 4) {
                    strength = 'Strong';
                    color = '#059669'; // dark green
                } else if (score >= 3) {
                    strength = 'Medium';
                    color = '#f59e0b'; // yellow
                } else if (score >= 2) {
                    strength = 'Fair';
                    color = '#f97316'; // orange
                }
                
                // Update strength bars
                strengthBars.forEach((bar, index) => {
                    if (index < score) {
                        bar.style.backgroundColor = color;
                    } else {
                        bar.style.backgroundColor = '#d1d5db';
                    }
                });
                
                // Update strength text
                strengthText.textContent = strength;
                strengthText.style.color = color;
                
                // Check submit button state after password validation
                checkSubmitButtonState();
            });
        }
    }

    document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = e.target;
        const formData = new FormData(form);
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        
        // Client-side validation
        if (newPassword !== confirmPassword) {
            showMessage('Error', 'Passwords do not match.');
            return;
        }
        
        // Enhanced password validation
        if (newPassword.length < 8) {
            showMessage('Error', 'Password must be at least 8 characters long.');
            return;
        }
        
        // Check password strength requirements
        const checks = {
            uppercase: /[A-Z]/.test(newPassword),
            lowercase: /[a-z]/.test(newPassword),
            number: /\d/.test(newPassword),
            special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>?]/.test(newPassword)
        };
        
        const failedChecks = Object.keys(checks).filter(key => !checks[key]);
        if (failedChecks.length > 0) {
            let message = 'Password must contain: ';
            const requirements = {
                uppercase: 'at least one uppercase letter',
                lowercase: 'at least one lowercase letter',
                number: 'at least one number',
                special: 'at least one special character'
            };
            
            message += failedChecks.map(key => requirements[key]).join(', ');
            showMessage('Error', message);
            return;
        }
        
        // Show loading
        document.getElementById('loadingModal').classList.remove('hidden');
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('loadingModal').classList.add('hidden');
            
            if (data.success) {
                showMessage('Success', data.message, () => {
                    window.location.href = data.redirect;
                });
            } else {
                showMessage('Error', data.message);
            }
        })
        .catch(error => {
            document.getElementById('loadingModal').classList.add('hidden');
            showMessage('Error', 'An error occurred. Please try again.');
            console.error('Error:', error);
        });
    });
    
    function showMessage(title, message, callback) {
        document.getElementById('messageTitle').textContent = title;
        document.getElementById('messageText').textContent = message;
        document.getElementById('messageModal').classList.remove('hidden');
        
        document.getElementById('closeMessageBtn').onclick = function() {
            document.getElementById('messageModal').classList.add('hidden');
            if (callback) callback();
        };
    }
    
    // Real-time password confirmation validation
    function validatePasswordMatch() {
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        const confirmPasswordField = document.getElementById('confirm_password');
        const errorMessage = document.getElementById('confirm-password-error');
        const successMessage = document.getElementById('confirm-password-success');
        const submitButton = document.getElementById('submitButton');
        
        if (confirmPassword.length > 0) {
            if (newPassword !== confirmPassword) {
                // Show error
                confirmPasswordField.classList.remove('border-gray-300', 'border-green-500');
                confirmPasswordField.classList.add('border-red-500');
                errorMessage.classList.remove('hidden');
                successMessage.classList.add('hidden');
                confirmPasswordField.setCustomValidity('Passwords do not match');
                
                // Disable submit button
                submitButton.disabled = true;
                submitButton.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                submitButton.classList.add('bg-gray-400', 'cursor-not-allowed');
            } else {
                // Show success
                confirmPasswordField.classList.remove('border-gray-300', 'border-red-500');
                confirmPasswordField.classList.add('border-green-500');
                errorMessage.classList.add('hidden');
                successMessage.classList.remove('hidden');
                confirmPasswordField.setCustomValidity('');
                
                // Enable submit button if password requirements are met
                checkSubmitButtonState();
            }
        } else {
            // Reset to default state
            confirmPasswordField.classList.remove('border-red-500', 'border-green-500');
            confirmPasswordField.classList.add('border-gray-300');
            errorMessage.classList.add('hidden');
            successMessage.classList.add('hidden');
            confirmPasswordField.setCustomValidity('');
            
            // Disable submit button when confirm password is empty
            submitButton.disabled = true;
            submitButton.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            submitButton.classList.add('bg-gray-400', 'cursor-not-allowed');
        }
    }
    
    // Check if submit button should be enabled
    function checkSubmitButtonState() {
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        const submitButton = document.getElementById('submitButton');
        
        // Check password requirements
        const checks = {
            length: newPassword.length >= 8,
            uppercase: /[A-Z]/.test(newPassword),
            lowercase: /[a-z]/.test(newPassword),
            number: /\d/.test(newPassword),
            special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>?]/.test(newPassword)
        };
        
        const allRequirementsMet = Object.values(checks).every(Boolean);
        const passwordsMatch = newPassword === confirmPassword && confirmPassword.length > 0;
        
        if (allRequirementsMet && passwordsMatch) {
            submitButton.disabled = false;
            submitButton.classList.remove('bg-gray-400', 'cursor-not-allowed');
            submitButton.classList.add('bg-blue-600', 'hover:bg-blue-700');
        } else {
            submitButton.disabled = true;
            submitButton.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            submitButton.classList.add('bg-gray-400', 'cursor-not-allowed');
        }
    }
    
    // Password confirmation validation - real-time
    document.getElementById('confirm_password').addEventListener('input', validatePasswordMatch);
    document.getElementById('new_password').addEventListener('input', validatePasswordMatch);
    
    // Initialize password validation when page loads
    document.addEventListener('DOMContentLoaded', function() {
        initializePasswordValidation();
        checkSubmitButtonState(); // Initially disable submit button

        // Password show/hide toggles
        function setupPasswordToggle(inputId, buttonId) {
            const input = document.getElementById(inputId);
            const button = document.getElementById(buttonId);
            if (!input || !button) return;
            const eyeOpen = button.querySelector('.icon-eye-open');
            const eyeClosed = button.querySelector('.icon-eye-closed');
            eyeClosed.style.display = 'none';
            button.addEventListener('click', () => {
                const isHidden = input.type === 'password';
                input.type = isHidden ? 'text' : 'password';
                if (isHidden) {
                    button.setAttribute('aria-label', 'Hide password');
                    button.setAttribute('title', 'Hide password');
                    eyeOpen.style.display = 'none';
                    eyeClosed.style.display = 'inline';
                } else {
                    button.setAttribute('aria-label', 'Show password');
                    button.setAttribute('title', 'Show password');
                    eyeClosed.style.display = 'none';
                    eyeOpen.style.display = 'inline';
                }
            });
        }
        setupPasswordToggle('new_password', 'toggle_new_password');
        setupPasswordToggle('confirm_password', 'toggle_confirm_password');
    });
    </script>
</body>
</html>
