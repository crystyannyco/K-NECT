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
        .otp-input {
            width: 50px;
            height: 60px;
            font-size: 24px;
            font-weight: 700;
            text-align: center;
            border: 2px solid #d1d5db;
            border-radius: 8px;
            transition: all 0.2s;
        }
        .otp-input:focus {
            border-color: #2563eb;
            outline: none;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        .otp-input.filled {
            border-color: #10b981;
            background-color: #f0fdf4;
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

            <!-- OTP Verification Card -->
            <div class="bg-white rounded-3xl shadow-2xl p-8">
                <!-- Header -->
                <div class="text-center mb-6">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Enter OTP Code</h3>
                    <p class="text-gray-600 text-sm">
                        We've sent a verification code to<br>
                        <span class="font-semibold text-gray-800"><?= esc($masked_contact) ?></span>
                        <?php if ($method === 'sms'): ?>
                            <span class="text-xs text-gray-500">(via SMS)</span>
                        <?php else: ?>
                            <span class="text-xs text-gray-500">(via Email)</span>
                        <?php endif; ?>
                    </p>
                </div>

                <!-- Success/Error Messages -->
                <div id="messageContainer" class="hidden mb-4"></div>

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

                <!-- OTP Form -->
                <form id="otpForm" action="<?= base_url('verify-otp') ?>" method="post" class="space-y-6">
                    <?= csrf_field() ?>
                    <!-- OTP Input Boxes -->
                    <div class="space-y-2">
                        <label class="block text-gray-700 text-sm font-medium text-center">6-Digit OTP Code</label>
                        <div class="flex justify-center space-x-2">
                            <input type="text" id="otp1" maxlength="1" class="otp-input" pattern="[0-9]" required>
                            <input type="text" id="otp2" maxlength="1" class="otp-input" pattern="[0-9]" required>
                            <input type="text" id="otp3" maxlength="1" class="otp-input" pattern="[0-9]" required>
                            <input type="text" id="otp4" maxlength="1" class="otp-input" pattern="[0-9]" required>
                            <input type="text" id="otp5" maxlength="1" class="otp-input" pattern="[0-9]" required>
                            <input type="text" id="otp6" maxlength="1" class="otp-input" pattern="[0-9]" required>
                        </div>
                        <input type="hidden" name="otp" id="otpHidden">
                        <div id="otpError" class="mt-1 text-red-600 text-xs text-center hidden"></div>
                    </div>

                    <!-- Expiry Timer -->
                    <div class="text-center">
                        <p class="text-sm text-gray-600">
                            Code expires in <span id="timer" class="font-semibold text-blue-600">5:00</span>
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        id="submitBtn"
                        class="btn-hover w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <span class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Verify OTP</span>
                        </span>
                    </button>
                </form>

                <!-- Resend OTP -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600 mb-2">Didn't receive the code?</p>
                    <button 
                        id="resendBtn"
                        onclick="resendOtp()"
                        <?= !$can_resend ? 'disabled' : '' ?>
                        class="text-blue-600 hover:text-blue-700 font-medium transition-colors text-sm <?= !$can_resend ? 'opacity-50 cursor-not-allowed' : '' ?>">
                        <span class="flex items-center justify-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <span id="resendText">Resend OTP</span>
                        </span>
                    </button>
                </div>

                <!-- Back to Login Link -->
                <div class="mt-6 text-center">
                    <a href="<?= base_url('forgot-password') ?>" class="text-gray-600 hover:text-gray-700 font-medium transition-colors text-sm inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Start Over
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
        // OTP Input handling
        const otpInputs = document.querySelectorAll('.otp-input');
        const otpHidden = document.getElementById('otpHidden');
        const otpError = document.getElementById('otpError');
        const otpForm = document.getElementById('otpForm');
        const submitBtn = document.getElementById('submitBtn');

        // Auto-focus first input
        otpInputs[0].focus();

        // Handle OTP input
        otpInputs.forEach((input, index) => {
            input.addEventListener('input', function(e) {
                const value = this.value;
                
                // Only allow numbers
                if (!/^\d*$/.test(value)) {
                    this.value = '';
                    return;
                }

                if (value) {
                    this.classList.add('filled');
                    // Move to next input
                    if (index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }
                } else {
                    this.classList.remove('filled');
                }

                // Update hidden field
                updateOtpValue();
            });

            input.addEventListener('keydown', function(e) {
                // Backspace handling
                if (e.key === 'Backspace' && !this.value && index > 0) {
                    otpInputs[index - 1].focus();
                    otpInputs[index - 1].value = '';
                    otpInputs[index - 1].classList.remove('filled');
                    updateOtpValue();
                }

                // Arrow key navigation
                if (e.key === 'ArrowLeft' && index > 0) {
                    otpInputs[index - 1].focus();
                }
                if (e.key === 'ArrowRight' && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            });

            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const pastedData = e.clipboardData.getData('text').replace(/\D/g, '');
                
                if (pastedData.length === 6) {
                    otpInputs.forEach((input, i) => {
                        input.value = pastedData[i] || '';
                        if (input.value) {
                            input.classList.add('filled');
                        }
                    });
                    updateOtpValue();
                    otpInputs[5].focus();
                }
            });
        });

        function updateOtpValue() {
            const otpValue = Array.from(otpInputs).map(input => input.value).join('');
            otpHidden.value = otpValue;
            
            // Clear error when user types
            if (otpValue.length > 0) {
                clearFieldError();
            }
        }

        function clearFieldError() {
            otpInputs.forEach(input => input.classList.remove('border-red-500'));
            otpError.textContent = '';
            otpError.classList.add('hidden');
        }

        function setFieldError(message) {
            otpInputs.forEach(input => input.classList.add('border-red-500'));
            otpError.textContent = message;
            otpError.classList.remove('hidden');
        }

        function showMessage(message, type) {
            const messageContainer = document.getElementById('messageContainer');
            const iconSvg = type === 'success' 
                ? '<svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>'
                : '<svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>';
            
            const bgColor = type === 'success' ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200';
            const textColor = type === 'success' ? 'text-green-700' : 'text-red-700';
            
            messageContainer.className = `mb-4 p-4 ${bgColor} border rounded-lg`;
            messageContainer.innerHTML = `
                <div class="flex items-center">
                    ${iconSvg}
                    <p class="${textColor} text-sm">${message}</p>
                </div>
            `;
            messageContainer.classList.remove('hidden');
        }

        // Timer countdown - Get actual remaining time from server
        let timeLeft = <?= $remaining_seconds ?? 300 ?>; // Remaining seconds from server
        const timerElement = document.getElementById('timer');
        let timerInterval = null;

        function updateTimer() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            
            // Change color when less than 1 minute
            if (timeLeft <= 60 && timeLeft > 0) {
                timerElement.classList.add('text-orange-600');
                timerElement.classList.remove('text-gray-700');
            }
            
            if (timeLeft === 0) {
                clearInterval(timerInterval);
                timerElement.classList.remove('text-orange-600');
                timerElement.classList.add('text-red-600');
                showMessage('OTP has expired. Please request a new one.', 'error');
                submitBtn.disabled = true;
                submitBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
                submitBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            } else {
                timeLeft--;
            }
        }

        // Update timer immediately and then every second
        updateTimer();
        timerInterval = setInterval(updateTimer, 1000);

        // Form submission
        otpForm.addEventListener('submit', function(e) {
            e.preventDefault();
            clearFieldError();

            const otpValue = otpHidden.value;

            if (otpValue.length !== 6) {
                setFieldError('Please enter all 6 digits.');
                return;
            }

            setLoadingState(true);

            fetch(otpForm.action, {
                method: 'POST',
                body: new FormData(otpForm),
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                setLoadingState(false);

                if (data.success) {
                    showMessage(data.message, 'success');
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1000);
                } else {
                    if (data.expired || data.locked) {
                        showMessage(data.message, 'error');
                        submitBtn.disabled = true;
                    } else {
                        setFieldError(data.message);
                        // Clear inputs for retry
                        otpInputs.forEach(input => {
                            input.value = '';
                            input.classList.remove('filled');
                        });
                        otpInputs[0].focus();
                    }
                }
            })
            .catch(error => {
                setLoadingState(false);
                showMessage('Unable to connect to server. Please try again.', 'error');
            });
        });

        function setLoadingState(loading) {
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
                submitBtn.innerHTML = `
                    <span class="flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Verify OTP</span>
                    </span>
                `;
            }
        }

        // Resend OTP
    let resendCooldown = <?= $can_resend ? '0' : '60' ?>;
        const resendBtn = document.getElementById('resendBtn');
        const resendText = document.getElementById('resendText');

        function resendOtp() {
            if (resendCooldown > 0) return;

            resendBtn.disabled = true;
            resendText.textContent = 'Sending...';

            fetch('<?= base_url('send-otp') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage('New OTP has been sent!', 'success');
                    // Reset timer
                    timeLeft = 300;
                    clearInterval(timerInterval);
                    timerElement.classList.remove('text-red-600', 'text-orange-600');
                    timerElement.classList.add('text-gray-700');
                    updateTimer();
                    timerInterval = setInterval(updateTimer, 1000);
                    
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
                    submitBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');
                    
                    // Start cooldown
                    startResendCooldown(60);
                } else {
                    showMessage(data.message, 'error');
                    if (data.remainingSeconds) {
                        startResendCooldown(data.remainingSeconds);
                    }
                }
            })
            .catch(error => {
                showMessage('Unable to resend OTP. Please try again.', 'error');
                resendBtn.disabled = false;
                resendText.textContent = 'Resend OTP';
            });
        }

        function startResendCooldown(seconds) {
            resendCooldown = seconds;
            updateResendButton();
        }

        function updateResendButton() {
            if (resendCooldown > 0) {
                resendBtn.disabled = true;
                resendBtn.classList.add('opacity-50', 'cursor-not-allowed');
                resendText.textContent = `Resend in ${resendCooldown}s`;
                resendCooldown--;
                setTimeout(updateResendButton, 1000);
            } else {
                resendBtn.disabled = false;
                resendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                resendText.textContent = 'Resend OTP';
            }
        }

        // Start cooldown if needed
        if (resendCooldown > 0) {
            updateResendButton();
        }
    </script>
</body>
</html>
