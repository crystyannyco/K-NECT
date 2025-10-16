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
    
    <!-- Tailwind CSS - Production Build -->
    <link rel="stylesheet" href="<?= base_url('assets/css/tailwind.css') ?>" />
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
                    <?= csrf_field() ?>
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

                <!-- Step 2: Verification Method Selection (Hidden Initially) -->
                <form id="methodSelectionForm" class="space-y-4 hidden">
                    <!-- Account Info Display -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p class="text-blue-800 font-medium text-sm">Account Found</p>
                                <p class="text-blue-700 text-xs">Full name: <span id="displayFullName" class="font-semibold"></span></p>
                                <p class="text-blue-700 text-xs">Username: <span id="displayUsername" class="font-semibold"></span></p>
                                <p class="text-blue-700 text-xs">Type: <span id="displayAccountType" class="font-semibold"></span></p>
                            </div>
                        </div>
                    </div>

                    <p class="text-gray-600 text-sm text-center mb-4">Choose how you'd like to receive your verification code:</p>

                    <!-- Verification Method Buttons -->
                    <div class="space-y-3">
                        <button 
                            type="button"
                            onclick="selectMethod('email')"
                            class="btn-hover w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <span class="flex items-center justify-center space-x-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span>Verify via Email</span>
                            </span>
                        </button>

                        <button 
                            type="button"
                            onclick="selectMethod('sms')"
                            class="btn-hover w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-4 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            <span class="flex items-center justify-center space-x-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                <span>Verify via SMS</span>
                            </span>
                        </button>
                    </div>

                    <!-- Back Button -->
                    <button 
                        type="button" 
                        onclick="backFromMethodSelection()"
                        class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-4 rounded-lg focus:outline-none transition-colors">
                        <span class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span>Back to Username</span>
                        </span>
                    </button>
                </form>

                <!-- Step 3: Contact Info Form (Hidden Initially) -->
                <form id="contactInfoForm" class="space-y-4 hidden">
                    <!-- Account Info Display -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p class="text-blue-800 font-medium text-sm">Verification Method: <span id="selectedMethodLabel" class="font-semibold"></span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Masked Contact Display (Hidden Initially) -->
                    <div id="maskedContactDisplay" class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4 hidden">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p class="text-green-800 font-medium text-sm">Contact Verified</p>
                                <p class="text-green-700 text-xs">OTP will be sent to: <span id="maskedContact" class="font-semibold font-mono"></span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Info Field -->
                    <div class="space-y-2" id="contactInputSection">
                        <label for="contactInfo" class="block text-gray-700 text-sm font-medium" id="contactLabel">Contact Information</label>
                        <p class="text-gray-500 text-xs mb-2" id="contactHint">Enter your registered contact information</p>
                        <div class="relative input-container">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10" id="contactIcon">
                                <!-- Icon will be set by JS -->
                            </div>
                            <div class="absolute inset-y-0 left-10 pl-3 flex items-center pointer-events-none z-10 hidden" id="phonePrefix">
                                <span class="text-gray-700 font-medium mr-2">+63</span>
                            </div>
                            <input 
                                type="text" 
                                id="contactInfo" 
                                name="contact_info" 
                                placeholder="Enter your contact information" 
                                required
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div id="contactError" class="mt-1 text-red-600 text-xs hidden"></div>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="btn-hover w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <span class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Verify and Continue</span>
                        </span>
                    </button>

                    <!-- Back Button -->
                    <button 
                        type="button" 
                        onclick="backFromContactInfo()"
                        class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-4 rounded-lg focus:outline-none transition-colors">
                        <span class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span>Back to Method Selection</span>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Form elements
            const usernameForm = document.getElementById('usernameForm');
            const usernameInput = document.getElementById('username');
            const usernameError = document.getElementById('usernameError');
            const headerDescription = document.getElementById('headerDescription');

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
                    window.verifiedUsername = username;
                    window.verifiedAccountType = data.account_type;
                    window.verifiedAccountTypeLabel = data.account_type_label;
                        // store full name for display
                        window.verifiedFullName = data.full_name || '';

                    // Update display elements in method selection form
                    document.getElementById('displayFullName').textContent = window.verifiedFullName;
                    document.getElementById('displayUsername').textContent = username;
                    document.getElementById('displayAccountType').textContent = data.account_type_label;

                    // Switch to verification method selection
                    usernameForm.classList.add('hidden');
                    document.getElementById('methodSelectionForm').classList.remove('hidden');
                    headerDescription.textContent = 'Choose your verification method';
                } else {
                    setFieldError(usernameInput, usernameError, data.message || 'Username not found.');
                }
            })
            .catch(error => {
                setLoadingState(usernameForm, false, originalButtonText);
                setFieldError(usernameInput, usernameError, 'Unable to connect to server. Please try again.');
            });
        });

        // Step 2: Method Selection
        let selectedMethod = '';

        window.selectMethod = function(method) {
            selectedMethod = method;
            
            const methodLabel = method === 'sms' ? 'SMS' : 'Email';
            document.getElementById('selectedMethodLabel').textContent = methodLabel;
            
            // Update contact info form based on method
            const contactLabel = document.getElementById('contactLabel');
            const contactHint = document.getElementById('contactHint');
            const contactIcon = document.getElementById('contactIcon');
            const contactInput = document.getElementById('contactInfo');
            const phonePrefix = document.getElementById('phonePrefix');
            
            if (method === 'sms') {
                contactLabel.textContent = 'Phone Number';
                contactHint.textContent = 'Enter your registered phone number (e.g., 91234567890)';
                contactIcon.innerHTML = `
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                `;
                contactInput.type = 'tel';
                contactInput.placeholder = '91234567890';
                contactInput.maxLength = 10;
                
                // Show +63 prefix and adjust padding
                phonePrefix.classList.remove('hidden');
                contactInput.classList.remove('pl-10');
                contactInput.classList.add('pl-24');
                
                // Add phone number formatting and validation
                contactInput.addEventListener('input', function(e) {
                    // Remove any non-digit characters
                    let value = e.target.value.replace(/\D/g, '');
                    
                    // If there's input and it doesn't start with 9, clear it or remove invalid starting digits
                    if (value.length > 0 && !value.startsWith('9')) {
                        // Remove all leading digits that are not 9
                        value = value.replace(/^[^9]+/, '');
                    }
                    
                    // Only keep digits and limit to 10
                    value = value.substring(0, 10);
                    
                    e.target.value = value;
                });
            } else {
                contactLabel.textContent = 'Email Address';
                contactHint.textContent = 'Enter your registered email address';
                contactIcon.innerHTML = `
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                `;
                contactInput.type = 'email';
                contactInput.placeholder = 'Enter your email address';
                
                // Hide +63 prefix and reset padding
                phonePrefix.classList.add('hidden');
                contactInput.classList.remove('pl-24');
                contactInput.classList.add('pl-10');
                contactInput.removeAttribute('maxLength');
            }
            
            // Update display elements
            document.getElementById('displayUsername').textContent = window.verifiedUsername;
            document.getElementById('displayAccountType').textContent = window.verifiedAccountTypeLabel;
            
            // Show contact info form
            document.getElementById('methodSelectionForm').classList.add('hidden');
            document.getElementById('contactInfoForm').classList.remove('hidden');
            headerDescription.textContent = 'Enter your ' + (method === 'sms' ? 'phone number' : 'email address');
            contactInput.focus();
        };

        window.backFromMethodSelection = function() {
            document.getElementById('methodSelectionForm').classList.add('hidden');
            usernameForm.classList.remove('hidden');
            headerDescription.textContent = 'Enter your username to verify your account.';
        };

        window.backFromContactInfo = function() {
            document.getElementById('contactInfoForm').classList.add('hidden');
            document.getElementById('methodSelectionForm').classList.remove('hidden');
            headerDescription.textContent = 'Choose your verification method';
            document.getElementById('contactInfo').value = '';
            clearContactError();
        };

        // Step 3: Contact Info Form
        const contactInfoForm = document.getElementById('contactInfoForm');
        const contactInput = document.getElementById('contactInfo');
        const contactError = document.getElementById('contactError');

        function clearContactError() {
            contactInput.classList.remove('border-red-500');
            contactError.textContent = '';
            contactError.classList.add('hidden');
        }

        function setContactError(message) {
            contactInput.classList.add('border-red-500');
            contactError.textContent = message;
            contactError.classList.remove('hidden');
        }

        contactInput.addEventListener('input', () => clearContactError());

        contactInfoForm.addEventListener('submit', function(e) {
            e.preventDefault();
            clearContactError();

            const contactValue = contactInput.value.trim();

            if (!contactValue) {
                setContactError('This field is required.');
                return;
            }

            // Validate based on method
            if (selectedMethod === 'email') {
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(contactValue)) {
                    setContactError('Please enter a valid email address.');
                    return;
                }
            } else if (selectedMethod === 'sms') {
                const cleanPhone = contactValue.replace(/\D/g, '');
                
                // Must be exactly 10 digits
                if (cleanPhone.length !== 10) {
                    setContactError('Please enter exactly 10 digits for your phone number.');
                    return;
                }
                
                // Must start with 9 (reject 0)
                if (!cleanPhone.startsWith('9')) {
                    setContactError('Phone number must start with 9 (e.g., 91234567890).');
                    return;
                }
                
                // Only numbers allowed
                if (!/^[0-9]+$/.test(cleanPhone)) {
                    setContactError('Phone number must contain only numbers.');
                    return;
                }
            }

            const submitBtn = contactInfoForm.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            
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

            // First verify contact info
            const verifyData = new FormData();
            verifyData.append('username', window.verifiedUsername);
            verifyData.append('account_type', window.verifiedAccountType);
            verifyData.append('method', selectedMethod);
            verifyData.append('contact_info', contactValue);

            fetch('<?= base_url('verify-contact-info') ?>', {
                method: 'POST',
                body: verifyData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show masked contact
                    document.getElementById('maskedContact').textContent = data.masked_contact;
                    document.getElementById('maskedContactDisplay').classList.remove('hidden');
                    document.getElementById('contactInputSection').classList.add('hidden');
                    
                    // Update button to show sending status
                    submitBtn.innerHTML = `
                        <span class="flex items-center justify-center space-x-2">
                            <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Sending OTP...</span>
                        </span>
                    `;
                    
                    // Contact verified, now send OTP
                    return fetch('<?= base_url('send-otp') ?>', {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                } else {
                    throw new Error(data.message);
                }
            })
            .then(response => response.json())
            .then(data => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;

                if (data.success) {
                    // Redirect to OTP verification page
                    window.location.href = '<?= base_url('verify-otp') ?>';
                } else {
                    // Hide masked contact and show input again on error
                    document.getElementById('maskedContactDisplay').classList.add('hidden');
                    document.getElementById('contactInputSection').classList.remove('hidden');
                    setContactError(data.message || 'Failed to send OTP. Please try again.');
                }
            })
            .catch(error => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
                // Hide masked contact and show input again on error
                document.getElementById('maskedContactDisplay').classList.add('hidden');
                document.getElementById('contactInputSection').classList.remove('hidden');
                setContactError(error.message || 'An error occurred. Please try again.');
            });
        });

        }); // End DOMContentLoaded
    </script>
</body>
</html>
