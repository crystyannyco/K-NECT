<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>K-NECT Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Optional fallback for serif display look if Domaine Display Narrow is unavailable -->
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    <!-- Security headers to prevent caching -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .login-bg {
            /* background-image: url('https://iriga.gov.ph/wp-content/uploads/2014/09/facts-banner.jpg'); */
            background-image: url('/assets/images/bg.png');
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
            /* backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px); */
        }
        .login-content {
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
        .input-focus {
            transition: all 0.3s ease;
        }
        .btn-hover {
            transition: all 0.3s ease;
        }
        .btn-hover:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
        }
        /* Title font: prefer Domaine Display Narrow if available; fall back to DM Serif Display and system serifs */
        .font-domaine {
            font-family: 'Domaine Display Narrow', 'DM Serif Display', 'Playfair Display', Georgia, 'Times New Roman', serif;
            letter-spacing: 0.1em;
        }
        /* Subtle blue glow for the K-NECT logo image */
        .glow-logo {
            filter: drop-shadow(0 0 12px rgba(255, 255, 255, 0.7)) drop-shadow(0 0 24px rgba(255, 255, 255, 0.35));
        }
    </style>
</head>
<body class="login-bg min-h-screen relative">
    <!-- Two-column layout -->
    <div class="min-h-screen flex flex-col lg:flex-row login-content">
        
        <!-- Mobile Title for smaller screens -->
        <div class="lg:hidden w-full text-center p-8">
            <img src="<?= base_url('/assets/images/K-Nect-Logo.png') ?>" alt="K-NECT Logo" class="w-48 mx-auto mb-1 drop-shadow-xl glow-logo" />
            <h2 class="text-xl font-semibold text-white mb-2 font-domaine">KABATAAN CONNECT</h2>
            <p class="text-base text-white leading-relaxed mb-4 text-justify px-2">
                A comprehensive governance system designed for Iriga City's youth development and community engagement. Connecting the youth with opportunities for growth, leadership, and civic participation.
            </p>
            <div class="flex flex-wrap items-center justify-center gap-x-4 gap-y-2 text-xs text-white mb-2">
                <div class="flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Youth Profiling
                </div>
                <div class="flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Event Management
                </div>
                <div class="flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Document Management
                </div>
            </div>
        </div>

        <!-- Left Side - System Title and Description (Desktop) -->
        <div class="hidden lg:flex lg:w-1/2 flex-col justify-center items-center p-12 text-center lg:text-left">
            <div class="max-w-md flex flex-col items-center lg:items-start">
                <img src="<?= base_url('/assets/images/K-Nect-Logo.png') ?>" alt="K-NECT Logo" class="w-64 mb-1 drop-shadow-xl glow-logo items-center" />
                <h2 class="text-2xl md:text-2xl font-black text-white mb-2 font-public-sans font-domaine">
                    KABATAAN CONNECT
                </h2>
                <p class="text-base text-white leading-relaxed mb-4 ">
                    A comprehensive youth governance system designed for Iriga City's youth development and community engagement. Connecting the youth with opportunities for growth, leadership, and civic participation.
                </p>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6">
            <div class="w-full max-w-md">
                <!-- Login Card -->
                <div class="bg-white rounded-3xl shadow-2xl p-6 lg:p-8">
                    
                    <!-- Small Logos at Top -->
                    <div class="flex flex-col items-center mb-6">
                        <!-- SK Pederasyon and Iriga City Logos Row -->
                        <?php
                            // Determine if logos are available and files actually exist to avoid broken images
                            $pederasyonPath = isset($logos['pederasyon']['file_path']) ? trim($logos['pederasyon']['file_path']) : '';
                            $irigaPath      = isset($logos['iriga_city']['file_path']) ? trim($logos['iriga_city']['file_path']) : '';

                            // Normalize relative paths and check existence under public/ (FCPATH)
                            $pederasyonRel = $pederasyonPath ? ltrim($pederasyonPath, '/') : '';
                            $irigaRel      = $irigaPath ? ltrim($irigaPath, '/') : '';

                            $hasPederasyonLogo = $pederasyonRel && file_exists(FCPATH . $pederasyonRel);
                            $hasIrigaLogo      = $irigaRel && file_exists(FCPATH . $irigaRel);
                        ?>
                        <?php if ($hasPederasyonLogo || $hasIrigaLogo): ?>
                            <div class="flex items-center justify-center gap-1 mb-1">
                                <!-- SK Pederasyon Logo -->
                                <?php if ($hasPederasyonLogo): ?>
                                    <div class="w-12 h-12 flex-shrink-0">
                                        <img src="<?= base_url('/previewDocument/logos/' . basename($logos['pederasyon']['file_path'])) ?>" 
                                             alt="SK Pederasyon Logo" 
                                             class="w-full h-full object-contain" onerror="this.style.display='none'">
                                    </div>
                                <?php endif; ?>

                                <!-- Iriga City Logo -->
                                <?php if ($hasIrigaLogo): ?>
                                    <div class="w-12 h-12 flex-shrink-0">
                                        <img src="<?= base_url('/previewDocument/logos/' . basename($logos['iriga_city']['file_path'])) ?>" 
                                             alt="Iriga City Logo" 
                                             class="w-full h-full object-contain" onerror="this.style.display='none'">
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- K-NECT Logo Below -->
                        <div class="text-center flex items-center justify-center">
                            <!-- <img src="<?= base_url('uploads/logos/K-Nect Logo.png') ?>" alt="K-NECT" class="w-36 h-10 object-contain" /> -->
                             <div class="flex flex-col items-center space-y-1">
                                <p class="font-semibold text-sm text-gray-700 leading-none">Lungsod ng Iriga</p>
                                <p class="text-gray-600 font-semibold text-xs leading-none">Panlungsod na Pederasyon</p>
                                <p class="text-gray-600 font-semibold text-xs leading-none">ng mga Sangguniang Kabataan</p>
                             </div>

                        </div>
                    </div>

                    <!-- Header Text -->
                    <div class="text-left mb-6">
                        <!-- <h2 class="text-2xl font-bold text-gray-900 mb-1">K-Nect</h2>
                        <p class="text-gray-500 text-xs mb-4">A Youth Governance System for Iriga City</p> -->
                        <h3 class="text-3xl font-bold text-gray-800 mb-1">Sign in</h3>
                        <p class="text-gray-600 text-xs">Please login to continue to your account.</p>
                    </div>

                    <!-- Login Form -->
                    <form id="loginForm" action="<?= base_url('loginProcess') ?>" method="post" class="space-y-4" novalidate>
                        <!-- Email/Username Field -->
                        <div class="space-y-2">
                            <label for="login" class="block text-gray-700 text-sm font-medium">Email or Username</label>
                            <div class="relative input-container">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <input 
                                    type="text" 
                                    id="login" 
                                    name="login" 
                                    placeholder="Enter your email or username" 
                    class="input-focus w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                <div id="loginError" class="mt-1 text-red-600 text-xs hidden"></div>
                        </div>

                        <!-- Password Field -->
                        <div class="space-y-2">
                            <label for="password" class="block text-gray-700 text-sm font-medium">Password</label>
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
                                    placeholder="Enter your password"
                                    class="input-focus w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center z-10">
                                    <svg id="eyeIconOpen" class="h-5 w-5 text-gray-400 hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <svg id="eyeIconClosed" class="h-5 w-5 text-gray-400 hover:text-gray-600 transition-colors hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.584 10.587a2 2 0 002.828 2.83"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.363 5.365A9.466 9.466 0 0112 5c4.478 0 8.268 2.943 9.542 7a18.057 18.057 0 01-1.065 3"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.83 9.17a3 3 0 00-4.243 4.243"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.35 8.596A17.954 17.954 0 002.458 12C3.732 16.057 7.523 19 12 19c1.898 0 3.683-.508 5.241-1.392"></path>
                                    </svg>
                                </button>
                            </div>
                            <div id="passwordError" class="mt-1 text-red-600 text-xs hidden"></div>
                        </div>

                        <!-- Login Button -->
                        <button 
                            type="submit" 
                            class="btn-hover w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <span class="flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                                <span>Sign In</span>
                            </span>
                        </button>
                    </form>

                    <!-- Additional Links -->
                    <div class="mt-4 text-center">
                        <p class="text-gray-600 text-xs">
                            Don't have an account? 
                            <a href="<?= base_url('profiling') ?>" class="text-blue-600 hover:text-blue-700 font-medium transition-colors">Register here</a>
                        </p>
                    </div>
                    
                    <!-- Footer -->
                    <div class="text-center mt-4 pt-3 border-t border-gray-100">
                        <p class="text-gray-400 text-xs">
                            © 2025 K-NECT Youth Profiling System. All rights reserved.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Modal -->
    <div id="popupModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">
        <div class="bg-white rounded-xl shadow-2xl p-6 max-w-sm w-full mx-4 transform transition-all duration-300 scale-95">
            <div class="text-center">
                <div id="modalIcon" class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center"></div>
                <h3 id="popupTitle" class="text-xl font-bold mb-2 text-gray-800"></h3>
                <p id="popupMessage" class="mb-2 text-gray-600"></p>
                <p id="popupReason" class="mb-6 text-red-500 text-sm"></p>
                
                <div class="space-y-3">
                    <div id="reuploadBtnContainer" class="hidden">
                        <button id="reuploadBtn" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-3 px-4 rounded-lg transition-colors">
                            Reupload Documents
                        </button>
                    </div>
                    <button id="closeModalBtn" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Prevent browser back button from accessing login page when user is authenticated
    window.addEventListener('load', function() {
        // Clear any cached form data
        if (window.history && window.history.pushState) {
            window.history.pushState(null, null, window.location.href);
            window.addEventListener('popstate', function() {
                // Force user to stay on login page by pushing current state again
                window.history.pushState(null, null, window.location.href);
            });
        }
        
        // Clear browser cache for this page
        if (performance.navigation.type === 2) {
            // User came from back button, force reload
            location.replace(location.href);
        }
    });
    
    // Disable browser cache for this page and clear sensitive data
    window.addEventListener('beforeunload', function() {
        // Clear any sensitive data
        document.getElementById('login').value = '';
        document.getElementById('password').value = '';
    });
    
    let rejectedUserId = null;
    
    // Add loading state to button
    function setLoadingState(loading) {
        const submitBtn = document.querySelector('button[type="submit"]');
        if (loading) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <span class="flex items-center justify-center space-x-2">
                    <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Signing In...</span>
                </span>
            `;
        } else {
            submitBtn.disabled = false;
            submitBtn.innerHTML = `
                <span class="flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Sign In</span>
                </span>
            `;
        }
    }
    
    // Show modal with appropriate styling
    function showModal(type, title, message, reason = '', userId = null) {
        const modal = document.getElementById('popupModal');
        const modalIcon = document.getElementById('modalIcon');
        const titleEl = document.getElementById('popupTitle');
        const messageEl = document.getElementById('popupMessage');
        const reasonEl = document.getElementById('popupReason');
        const reuploadContainer = document.getElementById('reuploadBtnContainer');
        
        // Set icon and colors based on type
        let iconClass = '';
        let iconContent = '';
        
        if (type === 'pending') {
            iconClass = 'bg-yellow-100 text-yellow-600';
            iconContent = `<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>`;
            reuploadContainer.classList.add('hidden');
        } else if (type === 'rejected') {
            iconClass = 'bg-red-100 text-red-600';
            iconContent = `<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>`;
            reuploadContainer.classList.remove('hidden');
            rejectedUserId = userId;
        } else {
            iconClass = 'bg-red-100 text-red-600';
            iconContent = `<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>`;
            reuploadContainer.classList.add('hidden');
        }
        
        modalIcon.className = `w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center ${iconClass}`;
        modalIcon.innerHTML = iconContent;
        titleEl.textContent = title;
        messageEl.textContent = message;
        reasonEl.textContent = reason;
        
        modal.classList.remove('hidden');
        // Add animation
        setTimeout(() => {
            modal.querySelector('.transform').classList.remove('scale-95');
            modal.querySelector('.transform').classList.add('scale-100');
        }, 10);
    }
    
    const loginInput = document.getElementById('login');
    const passwordInput = document.getElementById('password');
    const loginError = document.getElementById('loginError');
    const passwordError = document.getElementById('passwordError');

    function clearErrors() {
        [loginInput, passwordInput].forEach(el => el.classList.remove('border-red-500'));
        [loginError, passwordError].forEach(el => { el.textContent = ''; el.classList.add('hidden'); });
    }

    function setFieldError(inputEl, errorEl, message) {
        inputEl.classList.add('border-red-500');
        errorEl.textContent = message;
        errorEl.classList.remove('hidden');
    }

    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);

        // JS validation
        clearErrors();
        let hasError = false;
        if (!loginInput.value.trim()) {
            setFieldError(loginInput, loginError, 'Email or username is required.');
            hasError = true;
        }
        if (!passwordInput.value.trim()) {
            setFieldError(passwordInput, passwordError, 'Password is required.');
            hasError = true;
        }
        if (hasError) return;
        
        setLoadingState(true);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            setLoadingState(false);
            
            if (data.success) {
                // Success feedback before redirect
                const submitBtn = document.querySelector('button[type="submit"]');
                submitBtn.innerHTML = `
                    <span class="flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Success!</span>
                    </span>
                `;
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1000);
            } else {
                // Inline error for invalid credentials and generic errors
                if (data.type === 'invalid' || data.type === 'validation' || data.type === 'error' || data.type === 'unauthorized' || data.type === 'invalid_status') {
                    setFieldError(passwordInput, passwordError, data.message || 'Invalid username or password.');
                    // also highlight login field if invalid creds
                    if (data.type === 'invalid') {
                        loginInput.classList.add('border-red-500');
                    }
                    return;
                }

                // Show modal only for pending, rejected, inactive
                if (data.type === 'pending' || data.type === 'rejected' || data.type === 'inactive') {
                    let title = '';
                    let message = data.message || '';
                    let reason = '';
                    if (data.type === 'pending') title = 'Account Pending Approval';
                    if (data.type === 'rejected') { title = 'Account Rejected'; reason = data.reason || ''; }
                    if (data.type === 'inactive') title = 'Account Deactivated';
                    showModal(data.type, title, message, reason, data.user_id);
                    return;
                }

                // Fallback inline
                setFieldError(passwordInput, passwordError, data.message || 'Login failed.');
            }
        })
        .catch(async (err) => {
            setLoadingState(false);
            setFieldError(passwordInput, passwordError, 'Unable to connect to server. Please try again.');
        });
    });

    // Clear individual field error on input
    loginInput.addEventListener('input', () => {
        loginInput.classList.remove('border-red-500');
        loginError.textContent = '';
        loginError.classList.add('hidden');
    });
    passwordInput.addEventListener('input', () => {
        passwordInput.classList.remove('border-red-500');
        passwordError.textContent = '';
        passwordError.classList.add('hidden');
    });
    
    document.getElementById('closeModalBtn').addEventListener('click', function() {
        const modal = document.getElementById('popupModal');
        modal.querySelector('.transform').classList.remove('scale-100');
        modal.querySelector('.transform').classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    });
    
    document.getElementById('reuploadBtn').addEventListener('click', function() {
        if (rejectedUserId) {
            window.location.href = '/profiling/reupload/' + rejectedUserId;
        }
    });
    
    // Password toggle functionality
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const eyeIconOpen = document.getElementById('eyeIconOpen');
        const eyeIconClosed = document.getElementById('eyeIconClosed');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIconOpen.classList.add('hidden');
            eyeIconClosed.classList.remove('hidden');
        } else {
            passwordInput.type = 'password';
            eyeIconOpen.classList.remove('hidden');
            eyeIconClosed.classList.add('hidden');
        }
    });
    
    // Close modal when clicking outside
    document.getElementById('popupModal').addEventListener('click', function(e) {
        if (e.target === this) {
            document.getElementById('closeModalBtn').click();
        }
    });
    </script>
</body>
</html>
