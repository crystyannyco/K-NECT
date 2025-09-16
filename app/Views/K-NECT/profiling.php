<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>K-NECT Youth Profiling</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            // Suppress CDN warning
        }
    </script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-in': 'slideIn 0.6s ease-out',
                        'bounce-in': 'bounceIn 0.8s ease-out',
                        'pulse-soft': 'pulseSoft 2s infinite',
                        'shake': 'shake 0.5s ease-in-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' }
                        },
                        slideIn: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' }
                        },
                        bounceIn: {
                            '0%': { transform: 'scale(0.3)', opacity: '0' },
                            '50%': { transform: 'scale(1.05)' },
                            '70%': { transform: 'scale(0.9)' },
                            '100%': { transform: 'scale(1)', opacity: '1' }
                        },
                        pulseSoft: {
                            '0%, 100%': { opacity: '1' },
                            '50%': { opacity: '0.7' }
                        },
                        shake: {
                            '0%, 100%': { transform: 'translateX(0)' },
                            '10%, 30%, 50%, 70%, 90%': { transform: 'translateX(-2px)' },
                            '20%, 40%, 60%, 80%': { transform: 'translateX(2px)' }
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .step-transition {
            transition: all 0.3s ease-in-out;
        }
        .form-field {
            transition: all 0.2s ease-in-out;
        }
        .form-field:focus {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
        }
        .btn-primary {
            background-color: #3b82f6;
            transition: all 0.2s ease-in-out;
        }
        .btn-primary:hover {
            background-color: #2563eb;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
        }
        .btn-secondary {
            transition: all 0.2s ease-in-out;
        }
        .btn-secondary:hover {
            transform: translateY(-1px);
        }
        .progress-line {
            transition: all 0.5s ease-in-out;
        }
        .step-circle {
            transition: all 0.3s ease-in-out;
        }
        .error-message {
            animation: slideIn 0.3s ease-out;
        }
        .form-section {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease-in-out;
        }
        .form-section:hover {
            border-color: #cbd5e1;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        .invalid-field {
            animation: shake 0.5s ease-in-out;
        }
        
        /* Enhanced qualification step styles (blur removed for cleaner UI) */
        .qualification-card {
            backdrop-filter: none;
        }
        .glass-effect {
            background: #ffffff;
            backdrop-filter: none;
            border: 1px solid #e5e7eb;
        }
        
        /* Responsive improvements */
        @media (max-width: 640px) {
            /* Improve text legibility on mobile */
            body {
                font-size: 14px;
                line-height: 1.5;
            }
            
            /* Better touch targets */
            button, select, input {
                min-height: 44px;
            }
            
            /* Prevent horizontal scroll */
            * {
                box-sizing: border-box;
            }
        }
        
        @media (max-width: 480px) {
            /* Even smaller screens */
            .qualification-card {
                margin-bottom: 1rem;
                padding: 1rem;
            }
            
            /* Optimize spacing for very small screens */
            .space-y-3 > * + * {
                margin-top: 0.5rem;
            }
        }
        
        /* Ensure better performance on low-end devices */
        @media (prefers-reduced-motion: reduce) {
            .animate-pulse, .animate-bounce, .animate-slide-in {
                animation: none;
            }
        }
        
        /* Custom file upload styles */
        .file-upload-container {
            position: relative;
            display: inline-block;
            width: 100%;
        }
        
        .file-upload-input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        
        .file-upload-button {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 12px 16px;
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            background-color: #f9fafb;
            transition: all 0.2s ease-in-out;
            cursor: pointer;
            min-height: 60px;
            overflow: hidden; /* hide overflow from long filenames */
        }
        
        .file-upload-button:hover {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }
        
        .file-upload-button.dragover {
            border-color: #3b82f6;
            background-color: #dbeafe;
            transform: scale(1.02);
        }
        
        .file-upload-button.has-file {
            border-color: #10b981;
            background-color: #ecfdf5;
        }
        
        .file-upload-button.error {
            border-color: #ef4444;
            background-color: #fef2f2;
        }
        
        .file-upload-text {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            width: 100%;
            min-width: 0; /* allow flex child to shrink so ellipsis can take effect */
        }

        /* Truncate long filenames gracefully */
        .file-upload-filename {
            display: block;
            max-width: 100%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .file-preview {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px;
            background-color: #f3f4f6;
            border-radius: 6px;
            margin-top: 8px;
        }
        
        .validation-message {
            display: none;
            margin-top: 4px;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            animation: slideIn 0.3s ease-out;
        }
        
        .validation-message.error {
            background-color: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        
        .validation-message.show {
            display: block;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes shake {
            0%, 20%, 50%, 80%, 100% {
                transform: translateX(0);
            }
            10%, 30%, 70%, 90% {
                transform: translateX(-5px);
            }
            40%, 60% {
                transform: translateX(5px);
            }
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
        }
        
        .shake {
            animation: shake 0.5s ease-in-out;
        }
        
        .modal-backdrop {
            /* blur removed for simple, clean look */
            backdrop-filter: none;
            -webkit-backdrop-filter: none;
            animation: fadeIn 0.3s ease-out;
        }
        
        .modal-content {
            animation: modalSlideIn 0.3s ease-out;
        }
        
        .progress-smooth {
            transition: width 0.1s linear;
        }
        
        .countdown-smooth {
            transition: all 0.15s ease-out;
        }
        
        .countdown-smooth-scale {
            animation: countdownScale 0.2s ease-out;
        }
        
        @keyframes countdownScale {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .progress-bar-seamless {
            background-color: #3b82f6;
            transition: width 0.1s linear;
        }
        
        .progress-container {
            overflow: hidden;
            position: relative;
            background-color: #e5e7eb;
        }
        
        .progress-smooth {
            transition: width 0.1s ease-out;
        }
        
        .countdown-smooth-scale {
            animation: countdownScale 0.2s ease-out;
        }
        
        @keyframes countdownScale {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        .button-hover {
            transition: all 0.2s ease-in-out;
        }
        
        .button-hover:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .button-hover:active {
            transform: translateY(0);
        }
        
        .modal-container {
            backdrop-filter: none;
            -webkit-backdrop-filter: none;
        }

        /* Disabled container state (keeps section visible but clearly disabled) */
        .container-disabled {
            opacity: 0.6;
            filter: grayscale(0.1);
            pointer-events: none; /* block interactions inside while keeping visible */
        }
        .container-disabled * {
            cursor: not-allowed !important;
        }
    </style>
</head>
<body class="min-h-screen font-sans">
    <!-- Main Container -->
    <div class="w-full max-w-7xl mx-auto px-2 sm:px-4 md:px-6 lg:px-8 xl:px-12">
        <div class=" rounded-lg sm:rounded-2xl lg:rounded-3xl mt-2 sm:mt-4 md:mt-6 lg:mt-8 overflow-hidden animate-fade-in">
            <div class="p-4 sm:p-6 md:p-8 lg:p-10 xl:p-12">
        <!-- Logos Header (matches login layout) -->
        <div class="flex flex-col items-center mb-4">
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
            <!-- Inline row: Pederasyon | K-NECT | Iriga -->
            <div class="flex items-center justify-center gap-3 sm:gap-4 mb-2">
                <?php if ($hasPederasyonLogo): ?>
                    <div class="w-16 h-16 sm:w-16 sm:h-16 flex-shrink-0">
                        <img src="<?= base_url($logos['pederasyon']['file_path']) ?>" alt="SK Pederasyon Logo" class="w-full h-full object-contain" onerror="this.style.display='none'">
                    </div>
                <?php endif; ?>

                <div class="text-center flex flex-col items-center">
                    <img src="<?= base_url('/assets/images/K-Nect-Logo.png') ?>" alt="K-NECT Logo" class="w-48 sm:w-56 mx-auto" />
                </div>

                <?php if ($hasIrigaLogo): ?>
                    <div class="w-16 h-16 sm:w-16 sm:h-16 flex-shrink-0">
                        <img src="<?= base_url($logos['iriga_city']['file_path']) ?>" alt="Iriga City Logo" class="w-full h-full object-contain" onerror="this.style.display='none'">
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Header -->
        <div class="text-center mb-6 sm:mb-8 md:mb-10 lg:mb-12">
            <h1 class="text-lg sm:text-xl md:text-2xl lg:text-3xl xl:text-4xl font-bold bg-gradient-to-r from-blue-800 to-blue-900 bg-clip-text text-transparent px-2">
                Youth Profiling
            </h1>
            <p class="text-xs sm:text-sm md:text-base lg:text-lg text-slate-600 font-medium px-4">Join the future of youth empowerment</p>
        </div>

        <!-- Success Message -->
        <?php if (session('success')): ?>
            <div id="success-message" class="fixed top-2 right-2 sm:top-4 sm:right-4 z-50 animate-slide-in">
                <div class="bg-white border border-green-200 rounded-lg shadow-md p-2 sm:p-3 max-w-xs sm:max-w-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-5 h-5 sm:w-6 sm:h-6 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-2 flex-1">
                            <p class="text-xs font-medium text-green-800">Success!</p>
                            <p class="text-xs text-green-700"><?= session('success') ?></p>
                        </div>
                        <button onclick="document.getElementById('success-message').remove()" class="ml-2 flex-shrink-0 text-green-400 hover:text-green-600 transition-colors">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <script>
                setTimeout(function() {
                    const successMessage = document.getElementById('success-message');
                    if (successMessage) {
                        successMessage.style.transform = 'translateX(100%)';
                        setTimeout(() => successMessage.remove(), 300);
                    }
                }, 5000);
            </script>
        <?php endif; ?>
        <!-- Progress Stepper -->
        <ol class="flex items-center justify-between w-full mb-6 sm:mb-8 md:mb-10 lg:mb-12 xl:mb-16 px-2 sm:px-4 md:px-6 lg:px-8">
            <!-- Step 1 Circle: Qualification -->
            <li class="flex flex-col items-center relative">
                <?php if ($step > 1): ?>
                    <span class="flex items-center justify-center w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 lg:w-7 lg:h-7 xl:w-8 xl:h-8 bg-blue-600 border-2 border-blue-600 rounded-full text-white text-xs sm:text-sm font-bold transition-all duration-300 hover:scale-110">
                        <svg class="w-2 h-2 sm:w-3 sm:h-3 md:w-4 md:h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                    </span>
                <?php elseif ($step == 1): ?>
                    <span class="flex items-center justify-center w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 lg:w-7 lg:h-7 xl:w-8 xl:h-8 bg-blue-600 border-2 border-blue-600 rounded-full text-white text-xs sm:text-sm font-bold transition-all duration-300 animate-pulse">
                        1
                    </span>
                <?php else: ?>
                    <span class="flex items-center justify-center w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 lg:w-7 lg:h-7 xl:w-8 xl:h-8 bg-white border-2 border-blue-600 rounded-full text-blue-600 text-xs sm:text-sm font-bold transition-all duration-300">
                        1
                    </span>
                <?php endif; ?>
                <span class="absolute left-1/2 -translate-x-1/2 top-full mt-1 sm:mt-2 text-xs sm:text-sm text-gray-500 font-medium">Qualification</span>
            </li>
            <!-- Line between Step 1 and Step 2 -->
            <div class="flex-1 h-0.5 sm:h-1 mx-1 sm:mx-2 transition-all duration-500 ease-in-out <?php echo ($step >= 2) ? 'bg-blue-600' : 'bg-blue-100'; ?>"></div>
            <!-- Step 2 Circle: Profile -->
            <li class="flex flex-col items-center relative">
                <?php if ($step > 2): ?>
                    <span class="flex items-center justify-center w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 lg:w-7 lg:h-7 xl:w-8 xl:h-8 bg-blue-600 border-2 border-blue-600 rounded-full text-white text-xs sm:text-sm font-bold transition-all duration-300 hover:scale-110">
                        <svg class="w-2 h-2 sm:w-3 sm:h-3 md:w-4 md:h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                    </span>
                <?php elseif ($step == 2): ?>
                    <span class="flex items-center justify-center w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 lg:w-7 lg:h-7 xl:w-8 xl:h-8 bg-blue-600 border-2 border-blue-600 rounded-full text-white text-xs sm:text-sm font-bold transition-all duration-300 animate-pulse">
                        2
                    </span>
                <?php else: ?>
                    <span class="flex items-center justify-center w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 lg:w-7 lg:h-7 xl:w-8 xl:h-8 bg-white border-2 border-blue-600 rounded-full text-blue-600 text-xs sm:text-sm font-bold transition-all duration-300">
                        2
                    </span>
                <?php endif; ?>
                <span class="absolute left-1/2 -translate-x-1/2 top-full mt-1 sm:mt-2 text-xs sm:text-sm text-gray-500 font-medium">Profile</span>
            </li>
            <!-- Line between Step 2 and Step 3 -->
            <div class="flex-1 h-0.5 sm:h-1 mx-1 sm:mx-2 transition-all duration-500 ease-in-out <?php echo ($step >= 3) ? 'bg-blue-600' : 'bg-blue-100'; ?>"></div>
            <!-- Step 3 Circle: Demographic -->
            <li class="flex flex-col items-center relative">
                <?php if ($step > 3): ?>
                    <span class="flex items-center justify-center w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 lg:w-7 lg:h-7 xl:w-8 xl:h-8 bg-blue-600 border-2 border-blue-600 rounded-full text-white text-xs sm:text-sm font-bold transition-all duration-300 hover:scale-110">
                        <svg class="w-2 h-2 sm:w-3 sm:h-3 md:w-4 md:h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                    </span>
                <?php elseif ($step == 3): ?>
                    <span class="flex items-center justify-center w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 lg:w-7 lg:h-7 xl:w-8 xl:h-8 bg-blue-600 border-2 border-blue-600 rounded-full text-white text-xs sm:text-sm font-bold transition-all duration-300 animate-pulse">
                        3
                    </span>
                <?php else: ?>
                    <span class="flex items-center justify-center w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 lg:w-7 lg:h-7 xl:w-8 xl:h-8 bg-white border-2 border-blue-600 rounded-full text-blue-600 text-xs sm:text-sm font-bold transition-all duration-300">
                        3
                    </span>
                <?php endif; ?>
                <span class="absolute left-1/2 -translate-x-1/2 top-full mt-1 sm:mt-2 text-xs sm:text-sm text-gray-500 font-medium">Demographic</span>
            </li>
            <!-- Line between Step 3 and Step 4 -->
            <div class="flex-1 h-0.5 sm:h-1 mx-1 sm:mx-2 transition-all duration-500 ease-in-out <?php echo ($step >= 4) ? 'bg-blue-600' : 'bg-blue-100'; ?>"></div>
            <!-- Step 4 Circle: Account -->
            <li class="flex flex-col items-center relative">
                <?php if ($step > 4): ?>
                    <span class="flex items-center justify-center w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 lg:w-7 lg:h-7 xl:w-8 xl:h-8 bg-blue-600 border-2 border-blue-600 rounded-full text-white text-xs sm:text-sm font-bold transition-all duration-300 hover:scale-110">
                        <svg class="w-2 h-2 sm:w-3 sm:h-3 md:w-4 md:h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                    </span>
                <?php elseif ($step == 4): ?>
                    <span class="flex items-center justify-center w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 lg:w-7 lg:h-7 xl:w-8 xl:h-8 bg-blue-600 border-2 border-blue-600 rounded-full text-white text-xs sm:text-sm font-bold transition-all duration-300 animate-pulse">
                        4
                    </span>
                <?php else: ?>
                    <span class="flex items-center justify-center w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 lg:w-7 lg:h-7 xl:w-8 xl:h-8 bg-white border-2 border-blue-600 rounded-full text-blue-600 text-xs sm:text-sm font-bold transition-all duration-300">
                        4
                    </span>
                <?php endif; ?>
                <span class="absolute left-1/2 -translate-x-1/2 top-full mt-1 sm:mt-2 text-xs sm:text-sm text-gray-500 font-medium">Account</span>
            </li>
            <!-- Line between Step 4 and Step 5 -->
            <div class="flex-1 h-0.5 sm:h-1 mx-1 sm:mx-2 transition-all duration-500 ease-in-out <?php echo ($step >= 5) ? 'bg-blue-600' : 'bg-blue-100'; ?>"></div>
            <!-- Step 5 Circle: Finish -->
            <li class="flex flex-col items-center relative">
                <?php if ($step == 5): ?>
                    <span class="flex items-center justify-center w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 lg:w-7 lg:h-7 xl:w-8 xl:h-8 bg-blue-600 border-2 border-blue-600 rounded-full text-white text-xs sm:text-sm font-bold transition-all duration-300 animate-bounce">
                        5
                    </span>
                <?php else: ?>
                    <span class="flex items-center justify-center w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6 lg:w-7 lg:h-7 xl:w-8 xl:h-8 bg-white border-2 border-blue-600 rounded-full text-blue-600 text-xs sm:text-sm font-bold transition-all duration-300">
                        5
                    </span>
                <?php endif; ?>
                <span class="absolute left-1/2 -translate-x-1/2 top-full mt-1 sm:mt-2 text-xs sm:text-sm text-gray-500 font-medium">Finish</span>
            </li>
        </ol>
        <!-- Step 1: Qualification -->
        <div class="qualification_container step-transition" id="part0" style="<?= $step == 1 ? '' : 'display:none;' ?>">
            <div class="relative bg-blue-50 rounded-xl sm:rounded-2xl md:rounded-3xl p-4 sm:p-6 md:p-8 lg:p-10 xl:p-12 border border-blue-100 shadow-lg animate-slide-in overflow-hidden">

                <!-- Introduction Section -->
                <div class="qualification-card bg-white/80 backdrop-blur-sm rounded-lg sm:rounded-xl md:rounded-2xl p-4 sm:p-6 md:p-8 mb-4 sm:mb-6 border border-white/50 shadow-md relative z-10">
                    <div class="flex flex-col sm:flex-row items-start space-y-3 sm:space-y-0 sm:space-x-4 mb-4">
                        <div class="flex-shrink-0 mx-auto sm:mx-0">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-500 rounded-lg flex items-center justify-center shadow-md">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="text-center sm:text-left">
                            <h3 class="text-base sm:text-lg md:text-xl font-bold text-slate-800 mb-2">Welcome to the Official Portal</h3>
                            <p class="text-sm sm:text-base text-slate-700 leading-relaxed">
                                This is a local implementation of the national initiative under <strong class="text-blue-700">DILG Memorandum Circular No. 2022-324</strong>. 
                                We're profiling all youth aged 15 to 30 across Iriga City's 36 barangays for youth development planning and inclusive governance.
                            </p>
                        </div>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-3 sm:p-4 border-l-4 border-blue-400">
                        <p class="text-sm sm:text-base text-slate-700 leading-relaxed">
                            Whether you're a student, out-of-school youth, working individual, SK official, or member of a youth sector — 
                            your participation helps shape programs and services for the <strong class="text-blue-700">Kabataang Irigueño</strong>.
                        </p>
                    </div>
                </div>

                <!-- Quick Eligibility Check -->
                <div class="qualification-card bg-green-50 rounded-lg sm:rounded-xl md:rounded-2xl p-4 sm:p-6 md:p-8 mb-4 sm:mb-6 border border-green-200 shadow-md relative z-10">
                    <div class="flex flex-col sm:flex-row items-start space-y-3 sm:space-y-0 sm:space-x-4">
                        <div class="flex-shrink-0 mx-auto sm:mx-0">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-500 rounded-lg flex items-center justify-center shadow-md">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 text-center sm:text-left">
                            <h3 class="text-base sm:text-lg md:text-xl font-bold text-slate-800 mb-3">Am I Eligible?</h3>
                            <p class="text-sm sm:text-base text-slate-700 mb-4">
                                You can participate if you're a youth residing in any of Iriga City's 36 barangays and meet these criteria:
                            </p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3">
                                <div class="flex items-center space-x-2 sm:space-x-3 bg-white/60 rounded-lg p-2 sm:p-3 border border-green-100">
                                    <div class="w-6 h-6 sm:w-8 sm:h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-3 h-3 sm:w-5 sm:h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs sm:text-sm font-medium text-slate-700"><strong>15 to 30 years old</strong></span>
                                </div>
                                <div class="flex items-center space-x-2 sm:space-x-3 bg-white/60 rounded-lg p-2 sm:p-3 border border-green-100">
                                    <div class="w-6 h-6 sm:w-8 sm:h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <svg class="w-3 h-3 sm:w-5 sm:h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs sm:text-sm font-medium text-slate-700">Iriga City <strong>resident</strong></span>
                                </div>
                            </div>
                            <div class="mt-3 text-xs text-slate-600 bg-white/40 rounded-lg p-2 border border-green-100">
                                <em>Includes SK officials, regular KK members, students, out-of-school youth, and working individuals</em>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Requirements Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-4 sm:mb-6 relative z-10">
                    <!-- Required Documents -->
                    <div class="qualification-card bg-white/80 backdrop-blur-sm rounded-lg sm:rounded-xl md:rounded-2xl p-4 sm:p-6 md:p-8 border border-white/50 shadow-md">
                        <div class="flex flex-col sm:flex-row items-center sm:items-start space-y-2 sm:space-y-0 sm:space-x-3 mb-4">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-orange-500 rounded-lg flex items-center justify-center shadow-md flex-shrink-0">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-base sm:text-lg font-bold text-slate-800 text-center sm:text-left">Required Documents</h3>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-600 mb-3 sm:mb-4 text-center sm:text-left">Please prepare these documents for upload:</p>
                        <div class="space-y-2 sm:space-y-3">
                            <div class="flex items-start space-x-2 sm:space-x-3 p-2 sm:p-3 bg-orange-50 rounded-lg border border-orange-100">
                                <div class="w-5 h-5 sm:w-6 sm:h-6 bg-orange-200 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-3 h-3 text-orange-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm sm:text-base font-medium text-slate-800">Birth Certificate</p>
                                    <p class="text-xs text-slate-600">For information verification</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-2 sm:space-x-3 p-2 sm:p-3 bg-blue-50 rounded-lg border border-blue-100">
                                <div class="w-5 h-5 sm:w-6 sm:h-6 bg-blue-200 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-3 h-3 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm sm:text-base font-medium text-slate-800">Valid ID</p>
                                    <p class="text-xs text-slate-600">School ID, government ID, or barangay clearance</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-2 sm:space-x-3 p-2 sm:p-3 bg-purple-50 rounded-lg border border-purple-100">
                                <div class="w-5 h-5 sm:w-6 sm:h-6 bg-purple-200 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-3 h-3 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm sm:text-base font-medium text-slate-800">1x1 ID Picture</p>
                                    <p class="text-xs text-slate-600">Clear, recent photo with white background</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Collection & Privacy -->
                    <div class="space-y-4 sm:space-y-6">
                        <!-- Information Collected -->
                        <div class="qualification-card bg-white/80 backdrop-blur-sm rounded-lg sm:rounded-xl md:rounded-2xl p-4 sm:p-6 border border-white/50 shadow-md">
                            <div class="flex flex-col sm:flex-row items-center sm:items-start space-y-2 sm:space-y-0 sm:space-x-3 mb-4">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-indigo-500 rounded-lg flex items-center justify-center shadow-md flex-shrink-0">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <h3 class="text-base sm:text-lg font-bold text-slate-800 text-center sm:text-left">Information We'll Collect</h3>
                            </div>
                            <div class="space-y-2 text-xs sm:text-sm text-slate-700">
                                <div class="flex items-center space-x-2">
                                    <div class="w-1.5 h-1.5 bg-indigo-400 rounded-full flex-shrink-0"></div>
                                    <span>Basic details (name, age, gender, contact)</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-1.5 h-1.5 bg-indigo-400 rounded-full flex-shrink-0"></div>
                                    <span>Education & employment status</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-1.5 h-1.5 bg-indigo-400 rounded-full flex-shrink-0"></div>
                                    <span>Voter registration & SK participation</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="w-1.5 h-1.5 bg-indigo-400 rounded-full flex-shrink-0"></div>
                                    <span>Youth sector classification</span>
                                </div>
                            </div>
                        </div>

                        <!-- Data Safety -->
                        <div class="qualification-card bg-green-50 rounded-lg sm:rounded-xl md:rounded-2xl p-4 sm:p-6 border border-green-200 shadow-md">
                            <div class="flex flex-col sm:flex-row items-center sm:items-start space-y-2 sm:space-y-0 sm:space-x-3 mb-3">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-green-500 rounded-lg flex items-center justify-center shadow-md flex-shrink-0">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-base sm:text-lg font-bold text-slate-800 text-center sm:text-left">Your Data Is Protected</h3>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-700 leading-relaxed text-center sm:text-left">
                                All information is kept <strong class="text-green-700">confidential</strong> and used only for official SK planning, 
                                NYC reporting, and youth development in Iriga City.
                            </p>
                            <div class="mt-3 flex items-center space-x-2 text-xs text-green-700 bg-green-100/50 rounded-lg p-2">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.99 1.73l-2.35 1.05a1.99 1.99 0 01-1.85 0L12 16a2 2 0 01-1.85 0L8.37 14.78"></path>
                                </svg>
                                <span>Compliant with data privacy regulations</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Call to Action -->
                <div class="text-center pt-4">
                    <div class="bg-blue-600 rounded-lg p-6 shadow-md">
                        <div class="text-center space-y-4">
                            <h3 class="text-xl font-bold text-white">Ready to Get Started?</h3>
                            <p class="text-blue-100 text-sm max-w-md mx-auto">
                                Join thousands of Iriga City youth who have already registered. Your voice matters in shaping our community's future.
                            </p>
                            <form action="<?= base_url('profiling/step1') ?>" method="post" class="max-w-sm mx-auto">
                                <button type="submit" class="w-full bg-white text-blue-600 font-semibold py-3 px-6 rounded-lg shadow-md hover:bg-blue-50 hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                    <span class="flex items-center justify-center space-x-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>I Qualify, Let's Continue!</span>
                                    </span>
                                </button>
                                <p class="text-blue-100 text-xs mt-3 opacity-75 flex items-center justify-center space-x-1">
                                    <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Registration takes about 5-10 minutes to complete</span>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Additional Info Footer -->
                <div class="mt-4 sm:mt-6 md:mt-8 text-center relative z-10">
                    <div class="inline-flex items-center space-x-2 text-xs sm:text-sm text-slate-600 glass-effect rounded-full px-3 sm:px-4 py-1 sm:py-2 shadow-sm hover:shadow-md transition-all duration-200">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-center">Need help? Contact your local Sangguniang Kabataan office</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Step 2: Profile Form -->
        <div class="profile_container step-transition" id="part1" style="<?= $step == 2 ? '' : 'display:none;' ?>">
            <div class="animate-slide-in px-2 sm:px-0">
                <div class="text-center mb-6 sm:mb-8 md:mb-10">
                    <h2 class="text-lg sm:text-xl md:text-2xl lg:text-3xl font-bold text-slate-800 mb-2 px-2">Personal Information</h2>
                    <p class="text-xs sm:text-sm md:text-base text-slate-600 px-4">Tell us about yourself to get started</p>
                </div>
                
                <form action="<?= base_url('profiling/step1') ?>" method="post" class="space-y-4 sm:space-y-6 md:space-y-8" id="step1Form">
                    <!-- Name Section -->
                    <div class="form-section rounded-lg sm:rounded-xl md:rounded-2xl p-3 sm:p-4 md:p-6 mx-2 sm:mx-0">
                        <h3 class="text-base sm:text-lg md:text-xl font-semibold text-slate-800 mb-3 sm:mb-4 md:mb-6 flex items-center">
                            <div class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <span class="text-sm sm:text-base md:text-lg lg:text-xl">Full Name</span>
                        </h3>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3 sm:gap-4">
                            <div class="space-y-1 sm:space-y-2">
                                <label class="block text-xs sm:text-sm font-medium text-slate-700">
                                    Last Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="last_name" placeholder="Dela Cruz"
                                    value="<?= old('last_name') !== null ? old('last_name') : (isset($profile_data['last_name']) ? esc($profile_data['last_name']) : '') ?>"
                                    class="form-field w-full p-3 sm:p-3 border-2 rounded-lg sm:rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent <?= session('validation_user') && session('validation_user')->hasError('last_name') ? 'border-red-400 bg-red-50' : 'border-slate-200' ?> transition-all duration-200 text-sm sm:text-base"
                                    data-required="true">
                                <?php if (session('validation_user') && session('validation_user')->hasError('last_name')): ?>
                                    <p class="error-message text-red-500 text-xs sm:text-sm"><?= session('validation_user')->getError('last_name') ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="space-y-1 sm:space-y-2">
                                <label class="block text-xs sm:text-sm font-medium text-slate-700">
                                    First Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="first_name" placeholder="Juan"
                                    value="<?= old('first_name') !== null ? old('first_name') : (isset($profile_data['first_name']) ? esc($profile_data['first_name']) : '') ?>"
                                    class="form-field w-full p-3 sm:p-3 border-2 rounded-lg sm:rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent <?= session('validation_user') && session('validation_user')->hasError('first_name') ? 'border-red-400 bg-red-50' : 'border-slate-200' ?> transition-all duration-200 text-sm sm:text-base"
                                    data-required="true">
                                <?php if (session('validation_user') && session('validation_user')->hasError('first_name')): ?>
                                    <p class="error-message text-red-500 text-xs sm:text-sm"><?= session('validation_user')->getError('first_name') ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="space-y-1 sm:space-y-2">
                                <label class="block text-xs sm:text-sm font-medium text-slate-700">
                                    Middle Name <span class="text-slate-400 text-xs">(Optional)</span>
                                </label>
                                <input type="text" name="middle_name" placeholder="Santos"
                                    value="<?= old('middle_name') !== null ? old('middle_name') : (isset($profile_data['middle_name']) ? esc($profile_data['middle_name']) : '') ?>"
                                    class="form-field w-full p-3 sm:p-3 border-2 rounded-lg sm:rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent border-slate-200 transition-all duration-200 text-sm sm:text-base">
                            </div>
                            
                            <div class="space-y-1 sm:space-y-2">
                                <label class="block text-xs sm:text-sm font-medium text-slate-700">
                                    Suffix <span class="text-slate-400 text-xs">(Optional)</span>
                                </label>
                                <input type="text" name="suffix" placeholder="Jr."
                                    value="<?= old('suffix') !== null ? old('suffix') : (isset($profile_data['suffix']) ? esc($profile_data['suffix']) : '') ?>"
                                    class="form-field w-full p-2 sm:p-3 border-2 rounded-lg sm:rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent border-slate-200 transition-all duration-200 text-sm sm:text-base">
                            </div>
                        </div>
                    </div>

                    <!-- Location Section -->
                    <div class="form-section rounded-lg sm:rounded-xl md:rounded-2xl p-3 sm:p-4 md:p-6 mx-2 sm:mx-0">
                        <h3 class="text-base sm:text-lg md:text-xl font-semibold text-slate-800 mb-3 sm:mb-4 md:mb-6 flex items-center">
                            <div class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <span class="text-sm sm:text-base md:text-lg lg:text-xl">Address Information</span>
                        </h3>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-3 sm:gap-4">
                            <div class="space-y-1 sm:space-y-2">
                                <label class="block text-xs sm:text-sm font-medium text-slate-700">
                                    Region <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="region" placeholder="Region"
                                    value="<?= old('region') !== null ? old('region') : (isset($profile_data['region']) ? esc($profile_data['region']) : 'Region V') ?>"
                                    readonly
                                    class="form-field w-full p-3 sm:p-3 border-2 rounded-lg sm:rounded-xl bg-slate-50 text-slate-600 cursor-not-allowed border-slate-200 text-sm sm:text-base">
                            </div>
                            
                            <div class="space-y-1 sm:space-y-2">
                                <label class="block text-xs sm:text-sm font-medium text-slate-700">
                                    Province <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="province" placeholder="Province"
                                    value="<?= old('province') !== null ? old('province') : (isset($profile_data['province']) ? esc($profile_data['province']) : 'Camarines Sur') ?>"
                                    readonly
                                    class="form-field w-full p-3 sm:p-3 border-2 rounded-lg sm:rounded-xl bg-slate-50 text-slate-600 cursor-not-allowed border-slate-200 text-sm sm:text-base">
                            </div>
                            
                            <div class="space-y-1 sm:space-y-2">
                                <label class="block text-xs sm:text-sm font-medium text-slate-700">
                                    Municipality <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="municipality" placeholder="Municipality"
                                    value="<?= old('municipality') !== null ? old('municipality') : (isset($profile_data['municipality']) ? esc($profile_data['municipality']) : 'Iriga City') ?>"
                                    readonly
                                    class="form-field w-full p-3 sm:p-3 border-2 rounded-lg sm:rounded-xl bg-slate-50 text-slate-600 cursor-not-allowed border-slate-200 text-sm sm:text-base">
                            </div>
                            
                            <div class="space-y-1 sm:space-y-2">
                                <label class="block text-xs sm:text-sm font-medium text-slate-700">
                                    Barangay <span class="text-red-500">*</span>
                                </label>
                                <select name="barangay" data-required="true"
                                    class="form-field w-full p-2 sm:p-3 border-2 rounded-lg sm:rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent <?= session('validation_address') && session('validation_address')->hasError('barangay') ? 'border-red-400 bg-red-50' : 'border-slate-200' ?> transition-all duration-200 text-sm sm:text-base">
                                    <option value="">Select Barangay</option>
                                    <?php $barangay_val = old('barangay') !== null ? old('barangay') : (isset($profile_data['barangay']) ? $profile_data['barangay'] : ''); ?>
                                    <option value="1" <?= $barangay_val == '1' ? 'selected' : '' ?>>Antipolo</option>
                                    <option value="2" <?= $barangay_val == '2' ? 'selected' : '' ?>>Cristo Rey</option>
                                    <option value="3" <?= $barangay_val == '3' ? 'selected' : '' ?>>Del Rosario (Banao)</option>
                                    <option value="4" <?= $barangay_val == '4' ? 'selected' : '' ?>>Francia</option>
                                    <option value="5" <?= $barangay_val == '5' ? 'selected' : '' ?>>La Anunciacion</option>
                                    <option value="6" <?= $barangay_val == '6' ? 'selected' : '' ?>>La Medalla</option>
                                    <option value="7" <?= $barangay_val == '7' ? 'selected' : '' ?>>La Purisima</option>
                                    <option value="8" <?= $barangay_val == '8' ? 'selected' : '' ?>>La Trinidad</option>
                                    <option value="9" <?= $barangay_val == '9' ? 'selected' : '' ?>>Niño Jesus</option>
                                    <option value="10" <?= $barangay_val == '10' ? 'selected' : '' ?>>Perpetual Help</option>
                                    <option value="11" <?= $barangay_val == '11' ? 'selected' : '' ?>>Sagrada</option>
                                    <option value="12" <?= $barangay_val == '12' ? 'selected' : '' ?>>Salvacion</option>
                                    <option value="13" <?= $barangay_val == '13' ? 'selected' : '' ?>>San Agustin</option>
                                    <option value="14" <?= $barangay_val == '14' ? 'selected' : '' ?>>San Andres</option>
                                    <option value="15" <?= $barangay_val == '15' ? 'selected' : '' ?>>San Antonio</option>
                                    <option value="16" <?= $barangay_val == '16' ? 'selected' : '' ?>>San Francisco</option>
                                    <option value="17" <?= $barangay_val == '17' ? 'selected' : '' ?>>San Isidro</option>
                                    <option value="18" <?= $barangay_val == '18' ? 'selected' : '' ?>>San Jose</option>
                                    <option value="19" <?= $barangay_val == '19' ? 'selected' : '' ?>>San Juan</option>
                                    <option value="20" <?= $barangay_val == '20' ? 'selected' : '' ?>>San Miguel</option>
                                    <option value="21" <?= $barangay_val == '21' ? 'selected' : '' ?>>San Nicolas</option>
                                    <option value="22" <?= $barangay_val == '22' ? 'selected' : '' ?>>San Pedro</option>
                                    <option value="23" <?= $barangay_val == '23' ? 'selected' : '' ?>>San Rafael</option>
                                    <option value="24" <?= $barangay_val == '24' ? 'selected' : '' ?>>San Ramon</option>
                                    <option value="25" <?= $barangay_val == '25' ? 'selected' : '' ?>>San Roque</option>
                                    <option value="26" <?= $barangay_val == '26' ? 'selected' : '' ?>>Santiago</option>
                                    <option value="27" <?= $barangay_val == '27' ? 'selected' : '' ?>>San Vicente Norte</option>
                                    <option value="28" <?= $barangay_val == '28' ? 'selected' : '' ?>>San Vicente Sur</option>
                                    <option value="29" <?= $barangay_val == '29' ? 'selected' : '' ?>>Santa Cruz Norte</option>
                                    <option value="30" <?= $barangay_val == '30' ? 'selected' : '' ?>>Santa Cruz Sur</option>
                                    <option value="31" <?= $barangay_val == '31' ? 'selected' : '' ?>>Santa Elena</option>
                                    <option value="32" <?= $barangay_val == '32' ? 'selected' : '' ?>>Santa Isabel</option>
                                    <option value="33" <?= $barangay_val == '33' ? 'selected' : '' ?>>Santa Maria</option>
                                    <option value="34" <?= $barangay_val == '34' ? 'selected' : '' ?>>Santa Teresita</option>
                                    <option value="35" <?= $barangay_val == '35' ? 'selected' : '' ?>>Santo Domingo</option>
                                    <option value="36" <?= $barangay_val == '36' ? 'selected' : '' ?>>Santo Niño</option>
                                </select>
                                <?php if (session('validation_address') && session('validation_address')->hasError('barangay')): ?>
                                    <p class="error-message text-red-500 text-xs sm:text-sm"><?= session('validation_address')->getError('barangay') ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="space-y-1 sm:space-y-2">
                                <label class="block text-xs sm:text-sm font-medium text-slate-700">
                                    Zone/Purok <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="zone_purok" placeholder="Zone/Purok" data-required="true"
                                    value="<?= old('zone_purok') !== null ? old('zone_purok') : (isset($profile_data['zone_purok']) ? esc($profile_data['zone_purok']) : '') ?>"
                                    class="form-field w-full p-2 sm:p-3 border-2 rounded-lg sm:rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent <?= session('validation_address') && session('validation_address')->hasError('zone_purok') ? 'border-red-400 bg-red-50' : 'border-slate-200' ?> transition-all duration-200 text-sm sm:text-base">
                                <?php if (session('validation_address') && session('validation_address')->hasError('zone_purok')): ?>
                                    <p class="error-message text-red-500 text-xs sm:text-sm"><?= session('validation_address')->getError('zone_purok') ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Details Section -->
                    <div class="form-section rounded-lg sm:rounded-xl md:rounded-2xl p-3 sm:p-4 md:p-6">
                        <h3 class="text-base sm:text-lg md:text-xl font-semibold text-slate-800 mb-3 sm:mb-4 md:mb-6 flex items-center">
                            <div class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-2 sm:mr-3">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            Personal Details
                        </h3>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                            <div class="space-y-1 sm:space-y-2">
                                <label class="block text-xs sm:text-sm font-medium text-slate-700">
                                    Sex <span class="text-red-500">*</span>
                                </label>
                                <select name="sex" data-required="true"
                                    class="form-field w-full p-2 sm:p-3 border-2 rounded-lg sm:rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent <?= session('validation_user') && session('validation_user')->hasError('sex') ? 'border-red-400 bg-red-50' : 'border-slate-200' ?> transition-all duration-200 text-sm sm:text-base">
                                    <?php $sex_val = old('sex') !== null ? old('sex') : (isset($profile_data['sex']) ? $profile_data['sex'] : ''); ?>
                                    <option value="">Select Sex</option>
                                    <option value="1" <?= $sex_val == '1' ? 'selected' : '' ?>>Male</option>
                                    <option value="2" <?= $sex_val == '2' ? 'selected' : '' ?>>Female</option>
                                </select>
                                <?php if (session('validation_user') && session('validation_user')->hasError('sex')): ?>
                                    <p class="error-message text-red-500 text-xs sm:text-sm"><?= session('validation_user')->getError('sex') ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="space-y-1 sm:space-y-2">
                                <label class="block text-xs sm:text-sm font-medium text-slate-700">
                                    Date of Birth <span class="text-red-500">*</span>
                                </label>
                                <div class="grid grid-cols-3 gap-2 sm:gap-3">
                                    <!-- Month -->
                                    <div>
                                        <label class="block text-xs text-slate-500 mb-1">Month</label>
                                        <select name="birth_month" data-required="true" 
                                                class="form-field w-full p-2 sm:p-3 border-2 rounded-lg sm:rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php if ((session('validation_user') && session('validation_user')->hasError('birthdate')) || session('age_error')) { echo 'border-red-400 bg-red-50'; } else { echo 'border-slate-200'; } ?> transition-all duration-200 text-xs sm:text-sm">
                                            <option value="">Month</option>
                                            <?php 
                                            $months = [
                                                '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
                                                '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
                                                '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
                                            ];
                                            $current_month = '';
                                            if (old('birth_month')) {
                                                $current_month = old('birth_month');
                                            } elseif (isset($profile_data['birthdate']) && $profile_data['birthdate']) {
                                                $current_month = date('m', strtotime($profile_data['birthdate']));
                                            }
                                            foreach ($months as $value => $label): ?>
                                                <option value="<?= $value ?>" <?= $current_month == $value ? 'selected' : '' ?>><?= $label ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <!-- Day -->
                                    <div>
                                        <label class="block text-xs text-slate-500 mb-1">Day</label>
                                        <select name="birth_day" data-required="true" 
                                                class="form-field w-full p-2 sm:p-3 border-2 rounded-lg sm:rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php if ((session('validation_user') && session('validation_user')->hasError('birthdate')) || session('age_error')) { echo 'border-red-400 bg-red-50'; } else { echo 'border-slate-200'; } ?> transition-all duration-200 text-xs sm:text-sm">
                                            <option value="">Day</option>
                                            <?php 
                                            $current_day = '';
                                            if (old('birth_day')) {
                                                $current_day = old('birth_day');
                                            } elseif (isset($profile_data['birthdate']) && $profile_data['birthdate']) {
                                                $current_day = date('d', strtotime($profile_data['birthdate']));
                                            }
                                            for ($i = 1; $i <= 31; $i++): 
                                                $day_value = str_pad($i, 2, '0', STR_PAD_LEFT);
                                            ?>
                                                <option value="<?= $day_value ?>" <?= $current_day == $day_value ? 'selected' : '' ?>><?= $i ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                    <!-- Year -->
                                    <div>
                                        <label class="block text-xs text-slate-500 mb-1">Year</label>
                                        <select name="birth_year" data-required="true" 
                                                class="form-field w-full p-2 sm:p-3 border-2 rounded-lg sm:rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php if ((session('validation_user') && session('validation_user')->hasError('birthdate')) || session('age_error')) { echo 'border-red-400 bg-red-50'; } else { echo 'border-slate-200'; } ?> transition-all duration-200 text-xs sm:text-sm">
                                            <option value="">Year</option>
                                            <?php 
                                            $current_year = '';
                                            if (old('birth_year')) {
                                                $current_year = old('birth_year');
                                            } elseif (isset($profile_data['birthdate']) && $profile_data['birthdate']) {
                                                $current_year = date('Y', strtotime($profile_data['birthdate']));
                                            }
                                            $start_year = date('Y') - 50; // 50 years ago
                                            $end_year = date('Y') - 15; // 15 years ago (minimum age)
                                            for ($year = $end_year; $year >= $start_year; $year--): ?>
                                                <option value="<?= $year ?>" <?= $current_year == $year ? 'selected' : '' ?>><?= $year ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                </div>
                                <!-- Hidden input for combined birthdate -->
                                <input type="hidden" name="birthdate" id="birthdate_hidden" 
                                       value="<?= old('birthdate') !== null ? old('birthdate') : (isset($profile_data['birthdate']) ? esc($profile_data['birthdate']) : '') ?>">
                                <?php if (session('validation_user') && session('validation_user')->hasError('birthdate')): ?>
                                    <p class="error-message text-red-500 text-xs sm:text-sm"><?= session('validation_user')->getError('birthdate') ?></p>
                                <?php endif; ?>
                                <?php if (session('age_error')): ?>
                                    <p class="error-message text-red-500 text-xs sm:text-sm"><?= session('age_error') ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="space-y-1 sm:space-y-2">
                                <label class="block text-xs sm:text-sm font-medium text-slate-700">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" id="email" placeholder="juan.delacruz@example.com"
                                    value="<?= old('email') !== null ? old('email') : (isset($profile_data['email']) ? esc($profile_data['email']) : '') ?>"
                                    class="form-field w-full p-2 sm:p-3 border-2 rounded-lg sm:rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent <?= session('validation_user') && session('validation_user')->hasError('email') ? 'border-red-400 bg-red-50' : 'border-slate-200' ?> transition-all duration-200 text-sm sm:text-base">
                                <?php if (session('validation_user') && session('validation_user')->hasError('email')): ?>
                                    <p class="error-message text-red-500 text-xs sm:text-sm"><?= session('validation_user')->getError('email') ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="space-y-1 sm:space-y-2">
                                <label class="block text-xs sm:text-sm font-medium text-slate-700">
                                    Phone Number <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <?php
                                    $phoneValue = old('phone_number') !== null ? old('phone_number') : (isset($profile_data['phone_number']) ? $profile_data['phone_number'] : '');
                                    // Strip +63 prefix for display since we show it separately
                                    if (str_starts_with($phoneValue, '+63')) {
                                        $phoneValue = ltrim(substr($phoneValue, 3), '0');
                                    }
                                    ?>
                                    <input type="tel" id="phone_number" name="phone_number" placeholder="912 345 6789" data-required="true"
                                        value="<?= esc($phoneValue) ?>"
                                        class="form-field w-full p-2 sm:p-3 pl-12 sm:pl-14 border-2 rounded-lg sm:rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent <?= session('validation_user') && session('validation_user')->hasError('phone_number') ? 'border-red-400 bg-red-50' : 'border-slate-200' ?> transition-all duration-200 text-sm sm:text-base">
                                    <span class="absolute left-3 sm:left-4 top-2 sm:top-3 text-slate-600 text-sm sm:text-base font-medium pointer-events-none">+63</span>
                                </div>
                                <?php if (session('validation_user') && session('validation_user')->hasError('phone_number')): ?>
                                    <p class="error-message text-red-500 text-xs sm:text-sm"><?= session('validation_user')->getError('phone_number') ?></p>
                                <?php endif; ?>
                                <p id="phone_error" class="error-message text-red-500 text-xs sm:text-sm hidden">Phone number must be 11 digits (enter 10 digits after +63).</p>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex flex-col sm:flex-row justify-between items-center pt-4 sm:pt-6 space-y-3 sm:space-y-0">
                        <button type="submit" formaction="<?= base_url('profiling/backToStep1') ?>" formmethod="post" 
                            class="btn-secondary bg-slate-300 text-slate-700 text-sm sm:text-base font-semibold py-2 sm:py-3 px-4 sm:px-6 rounded-lg sm:rounded-xl hover:bg-slate-400 transition-all duration-200 flex items-center space-x-2 w-full sm:w-auto justify-center">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                            </svg>
                            <span>Back</span>
                        </button>
                        
                        <button type="submit" 
                            class="btn-primary text-white text-sm sm:text-base font-semibold py-2 sm:py-3 px-6 sm:px-8 rounded-lg sm:rounded-xl transition-all duration-200 flex items-center space-x-2 hover:shadow-lg transform hover:scale-105 w-full sm:w-auto justify-center">
                            <span>Continue</span>
                            <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Step 3: Demographic Form -->
        <div class="demographic_container" id="part2" style="<?= $step == 3 ? '' : 'display:none;' ?>">
            <h2 class="text-lg sm:text-xl font-semibold mb-2">II. DEMOGRAPHIC CHARACTERISTICS</h2>
            <p class="text-xs sm:text-sm text-gray-500 mb-3 sm:mb-4">Please put a check mark next to the word or phrase that matches your response</p>
            <form action="<?= base_url('profiling/step2') ?>" method="post" class="space-y-4 sm:space-y-6" id="step2Form" enctype="multipart/form-data">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <!-- Civil Status -->
                    <div class="bg-gray-50 border <?= session('validation') && session('validation')->hasError('civil_status') ? 'border-red-500' : 'border-gray-200' ?> rounded-lg p-3 sm:p-4 shadow-sm">
                        <h4 class="text-sm sm:text-base font-medium mb-2">1. Civil Status: <span class="text-red-500">*</span></h4>
                        <select name="civil_status" data-required="true" class="w-full p-2 sm:p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400 <?= session('validation') && session('validation')->hasError('civil_status') ? 'border-red-500' : 'border-gray-300' ?> text-sm sm:text-base">
                            <?php $civil_status_val = old('civil_status') !== null ? old('civil_status') : (isset($demographic_data['civil_status']) ? $demographic_data['civil_status'] : ''); ?>
                            <option value="">Select Civil Status...</option>
                            <option value="1" <?= $civil_status_val == '1' ? 'selected' : '' ?>>Single</option>
                            <option value="2" <?= $civil_status_val == '2' ? 'selected' : '' ?>>Married</option>
                            <option value="3" <?= $civil_status_val == '3' ? 'selected' : '' ?>>Widowed</option>
                            <option value="4" <?= $civil_status_val == '4' ? 'selected' : '' ?>>Divorced</option>
                            <option value="5" <?= $civil_status_val == '5' ? 'selected' : '' ?>>Separated</option>
                            <option value="6" <?= $civil_status_val == '6' ? 'selected' : '' ?>>Annulled</option>
                            <option value="7" <?= $civil_status_val == '7' ? 'selected' : '' ?>>Live-in</option>
                            <option value="8" <?= $civil_status_val == '8' ? 'selected' : '' ?>>Unknown</option>
                        </select>
                        <?php if (session('validation') && session('validation')->hasError('civil_status')): ?>
                            <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('civil_status') ?></p>
                        <?php endif; ?>
                    </div>
                    <!-- Youth Classification -->
                    <div class="bg-gray-50 border <?= session('validation') && session('validation')->hasError('youth_classification') ? 'border-red-500' : 'border-gray-200' ?> rounded-lg p-3 sm:p-4 shadow-sm">
                        <h4 class="text-sm sm:text-base font-medium mb-2">2. Youth Classification: <span class="text-red-500">*</span></h4>
                        <?php $youth_classification_val = old('youth_classification') !== null ? old('youth_classification') : (isset($demographic_data['youth_classification']) ? $demographic_data['youth_classification'] : ''); ?>
                        <select name="youth_classification" data-required="true" class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400 <?= session('validation') && session('validation')->hasError('youth_classification') ? 'border-red-500' : 'border-gray-300' ?> text-sm sm:text-base">
                            <option value="">Select Youth Classification...</option>
                            <option value="1" <?= $youth_classification_val == '1' ? 'selected' : '' ?>>In School Youth</option>
                            <option value="2" <?= $youth_classification_val == '2' ? 'selected' : '' ?>>Out-of-School Youth</option>
                            <option value="3" <?= $youth_classification_val == '3' ? 'selected' : '' ?>>Working Youth</option>
                            <option value="4" <?= $youth_classification_val == '4' ? 'selected' : '' ?>>Youth with Specific Needs</option>
                            <option value="5" <?= $youth_classification_val == '5' ? 'selected' : '' ?>>Person with Disability</option>
                            <option value="6" <?= $youth_classification_val == '6' ? 'selected' : '' ?>>Children in Conflict with the Law</option>
                            <option value="7" <?= $youth_classification_val == '7' ? 'selected' : '' ?>>Indigenous People</option>
                        </select>
                        <?php if (session('validation') && session('validation')->hasError('youth_classification')): ?>
                            <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('youth_classification') ?></p>
                        <?php endif; ?>
                    </div>
                    <!-- Age Group -->
                    <div class="bg-gray-50 border <?= session('validation') && session('validation')->hasError('age_group') ? 'border-red-500' : 'border-gray-200' ?> rounded-lg p-3 sm:p-4 shadow-sm">
                        <h4 class="text-sm sm:text-base font-medium mb-2">3. Youth Age Group: <span class="text-red-500">*</span></h4>
                        <?php $age_group_val = old('age_group') !== null ? old('age_group') : (isset($profile_data['age_group']) ? $profile_data['age_group'] : (isset($demographic_data['age_group']) ? $demographic_data['age_group'] : '')); ?>
                        <select name="age_group" id="age_group_select" class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400 <?= session('validation') && session('validation')->hasError('age_group') ? 'border-red-500' : 'border-gray-300' ?> text-sm sm:text-base" disabled>
                            <option value="">Select Age Group...</option>
                            <option value="1" <?= $age_group_val == '1' ? 'selected' : '' ?>>Child Youth (15-17 years old)</option>
                            <option value="2" <?= $age_group_val == '2' ? 'selected' : '' ?>>Young Adult (18-24 years old)</option>
                            <option value="3" <?= $age_group_val == '3' ? 'selected' : '' ?>>Adult (25-30 years old)</option>
                        </select>
                        <input type="hidden" name="age_group" value="<?= esc($age_group_val) ?>">
                        <?php if (session('validation') && session('validation')->hasError('age_group')): ?>
                            <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('age_group') ?></p>
                        <?php endif; ?>
                    </div>
                    <!-- Work Status -->
                    <div class="bg-gray-50 border <?= session('validation') && session('validation')->hasError('work_status') ? 'border-red-500' : 'border-gray-200' ?> rounded-lg p-3 sm:p-4 shadow-sm">
                        <h4 class="text-sm sm:text-base font-medium mb-2">4. Work Status: <span class="text-red-500">*</span></h4>
                        <?php $work_status_val = old('work_status') !== null ? old('work_status') : (isset($demographic_data['work_status']) ? $demographic_data['work_status'] : ''); ?>
                        <select name="work_status" data-required="true" class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400 <?= session('validation') && session('validation')->hasError('work_status') ? 'border-red-500' : 'border-gray-300' ?> text-sm sm:text-base">
                            <option value="">Select Work Status...</option>
                            <option value="1" <?= $work_status_val == '1' ? 'selected' : '' ?>>Employed</option>
                            <option value="2" <?= $work_status_val == '2' ? 'selected' : '' ?>>Unemployed</option>
                            <option value="3" <?= $work_status_val == '3' ? 'selected' : '' ?>>Currently looking for a Job</option>
                            <option value="4" <?= $work_status_val == '4' ? 'selected' : '' ?>>Not Interested in finding a job</option>
                        </select>
                        <?php if (session('validation') && session('validation')->hasError('work_status')): ?>
                            <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('work_status') ?></p>
                        <?php endif; ?>
                    </div>
                    <!-- Educational Background -->
                    <div class="bg-gray-50 border <?= session('validation') && session('validation')->hasError('educational_background') ? 'border-red-500' : 'border-gray-200' ?> rounded-lg p-4 shadow-sm md:col-span-2">
                        <h4 class="font-medium mb-2">5. Educational Background: <span class="text-red-500">*</span></h4>
                        <?php $educational_background_val = old('educational_background') !== null ? old('educational_background') : (isset($demographic_data['educational_background']) ? $demographic_data['educational_background'] : ''); ?>
                        <select name="educational_background" data-required="true" class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400 <?= session('validation') && session('validation')->hasError('educational_background') ? 'border-red-500' : 'border-gray-300' ?>">
                            <option value="">Select Educational Background...</option>
                            <option value="1" <?= $educational_background_val == '1' ? 'selected' : '' ?>>Elementary Level</option>
                            <option value="2" <?= $educational_background_val == '2' ? 'selected' : '' ?>>Elementary Graduate</option>
                            <option value="3" <?= $educational_background_val == '3' ? 'selected' : '' ?>>High School Level</option>
                            <option value="4" <?= $educational_background_val == '4' ? 'selected' : '' ?>>High School Graduate</option>
                            <option value="5" <?= $educational_background_val == '5' ? 'selected' : '' ?>>Vocational Level</option>
                            <option value="6" <?= $educational_background_val == '6' ? 'selected' : '' ?>>College Level</option>
                            <option value="7" <?= $educational_background_val == '7' ? 'selected' : '' ?>>College Graduate</option>
                            <option value="8" <?= $educational_background_val == '8' ? 'selected' : '' ?>>Master Level</option>
                            <option value="9" <?= $educational_background_val == '9' ? 'selected' : '' ?>>Master Graduate</option>
                            <option value="10" <?= $educational_background_val == '10' ? 'selected' : '' ?>>Doctorate Level</option>
                            <option value="11" <?= $educational_background_val == '11' ? 'selected' : '' ?>>Doctorate Graduate</option>
                        </select>
                        <?php if (session('validation') && session('validation')->hasError('educational_background')): ?>
                            <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('educational_background') ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Voters Info and Assembly -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div class="flex flex-col gap-6">
                        <!-- 6. Registered SK Voter? -->
                        <div id="sk_voter_container" class="bg-gray-50 border <?= session('validation') && session('validation')->hasError('sk_voter') ? 'border-red-500' : 'border-gray-200' ?> rounded-lg p-4 shadow-sm flex flex-col gap-2">
                            <h4 class="font-medium mb-2">6. Registered SK Voter? <span class="text-red-500">*</span></h4>
                            <div class="flex gap-4">
                                <?php $sk_voter_val = old('sk_voter') !== null ? old('sk_voter') : (isset($demographic_data['sk_voter']) ? $demographic_data['sk_voter'] : ''); ?>
                                <label class="inline-flex items-center cursor-pointer hover:bg-blue-50 rounded px-2 py-1">
                                    <input type="radio" name="sk_voter" value="1" <?= $sk_voter_val === '1' ? 'checked' : '' ?> class="form-radio text-blue-600" data-required="true">
                                    <span class="ml-2">Yes</span>
                                </label>
                                <label class="inline-flex items-center cursor-pointer hover:bg-blue-50 rounded px-2 py-1">
                                    <input type="radio" name="sk_voter" value="0" <?= $sk_voter_val === '0' ? 'checked' : '' ?> class="form-radio text-blue-600" data-required="true">
                                    <span class="ml-2">No</span>
                                </label>
                            </div>
                            <?php if (session('validation') && session('validation')->hasError('sk_voter')): ?>
                                <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('sk_voter') ?></p>
                            <?php endif; ?>
                        </div>
                        <!-- 7. Did you vote last SK election? -->
                        <div id="sk_election_container" class="bg-gray-50 border <?= session('validation') && session('validation')->hasError('sk_election') ? 'border-red-500' : 'border-gray-200' ?> rounded-lg p-4 shadow-sm flex flex-col gap-2">
                            <h4 class="font-medium mb-2">7. Did you vote last SK election? <span class="text-red-500">*</span></h4>
                            <div class="flex gap-4">
                                <?php $sk_election_val = old('sk_election') !== null ? old('sk_election') : (isset($demographic_data['sk_election']) ? $demographic_data['sk_election'] : ''); ?>
                                <label class="inline-flex items-center cursor-pointer hover:bg-blue-50 rounded px-2 py-1">
                                    <input type="radio" name="sk_election" value="1" <?= $sk_election_val === '1' ? 'checked' : '' ?> class="form-radio text-blue-600" data-required="true">
                                    <span class="ml-2">Yes</span>
                                </label>
                                <label class="inline-flex items-center cursor-pointer hover:bg-blue-50 rounded px-2 py-1">
                                    <input type="radio" name="sk_election" value="0" <?= $sk_election_val === '0' ? 'checked' : '' ?> class="form-radio text-blue-600" data-required="true">
                                    <span class="ml-2">No</span>
                                </label>
                            </div>
                            <?php if (session('validation') && session('validation')->hasError('sk_election')): ?>
                                <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('sk_election') ?></p>
                            <?php endif; ?>
                        </div>
                        <!-- 8. Registered National Voter? -->
                        <div id="national_voter_container" class="bg-gray-50 border <?= session('validation') && session('validation')->hasError('national_voter') ? 'border-red-500' : 'border-gray-200' ?> rounded-lg p-4 shadow-sm flex flex-col gap-2">
                            <h4 class="font-medium mb-2">8. Registered National Voter? <span class="text-red-500">*</span></h4>
                            <div class="flex gap-4">
                                <?php $national_voter_val = old('national_voter') !== null ? old('national_voter') : (isset($demographic_data['national_voter']) ? $demographic_data['national_voter'] : ''); ?>
                                <label class="inline-flex items-center cursor-pointer hover:bg-blue-50 rounded px-2 py-1">
                                    <input type="radio" name="national_voter" value="1" <?= $national_voter_val === '1' ? 'checked' : '' ?> class="form-radio text-blue-600" data-required="true">
                                    <span class="ml-2">Yes</span>
                                </label>
                                <label class="inline-flex items-center cursor-pointer hover:bg-blue-50 rounded px-2 py-1">
                                    <input type="radio" name="national_voter" value="0" <?= $national_voter_val === '0' ? 'checked' : '' ?> class="form-radio text-blue-600" data-required="true">
                                    <span class="ml-2">No</span>
                                </label>
                            </div>
                            <?php if (session('validation') && session('validation')->hasError('national_voter')): ?>
                                <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('national_voter') ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="flex flex-col gap-6">
                        <!-- 9. Have you already attended a KK Assembly? -->
                        <div id="kk_assembly_container" class="bg-gray-50 border <?= session('validation') && session('validation')->hasError('kk_assembly') ? 'border-red-500' : 'border-gray-200' ?> rounded-lg p-4 shadow-sm flex flex-col gap-2">
                            <h4 class="font-medium mb-2">9. Have you already attended a KK Assembly? <span class="text-red-500">*</span></h4>
                            <div class="flex gap-4">
                                <?php $kk_assembly_val = old('kk_assembly') !== null ? old('kk_assembly') : (isset($demographic_data['kk_assembly']) ? $demographic_data['kk_assembly'] : ''); ?>
                                <label class="inline-flex items-center cursor-pointer hover:bg-blue-50 rounded px-2 py-1">
                                    <input type="radio" name="kk_assembly" value="1" <?= $kk_assembly_val === '1' ? 'checked' : '' ?> class="form-radio text-blue-600" data-required="true">
                                    <span class="ml-2">Yes</span>
                                </label>
                                <label class="inline-flex items-center cursor-pointer hover:bg-blue-50 rounded px-2 py-1">
                                    <input type="radio" name="kk_assembly" value="0" <?= $kk_assembly_val === '0' ? 'checked' : '' ?> class="form-radio text-blue-600" data-required="true">
                                    <span class="ml-2">No</span>
                                </label>
                            </div>
                            <?php if (session('validation') && session('validation')->hasError('kk_assembly')): ?>
                                <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('kk_assembly') ?></p>
                            <?php endif; ?>
                        </div>
                        <!-- 10. If Yes, How many times? -->
                        <div id="how_many_times_container" class="bg-gray-50 border <?= session('validation') && session('validation')->hasError('how_many_times') ? 'border-red-500' : 'border-gray-200' ?> rounded-lg p-4 shadow-sm flex flex-col gap-2">
                            <h4 class="font-medium mb-2">10. If Yes, How many times?</h4>
                            <div class="flex gap-4">
                                <?php $how_many_times_val = old('how_many_times') !== null ? old('how_many_times') : (isset($demographic_data['how_many_times']) ? $demographic_data['how_many_times'] : ''); ?>
                                <label class="inline-flex items-center cursor-pointer hover:bg-blue-50 rounded px-2 py-1">
                                    <input type="radio" name="how_many_times" value="1" <?= $how_many_times_val === '1' ? 'checked' : '' ?> class="form-radio text-blue-600">
                                    <span class="ml-2">1-2 times</span>
                                </label>
                                <label class="inline-flex items-center cursor-pointer hover:bg-blue-50 rounded px-2 py-1">
                                    <input type="radio" name="how_many_times" value="2" <?= $how_many_times_val === '2' ? 'checked' : '' ?> class="form-radio text-blue-600">
                                    <span class="ml-2">3-4 times</span>
                                </label>
                                <label class="inline-flex items-center cursor-pointer hover:bg-blue-50 rounded px-2 py-1">
                                    <input type="radio" name="how_many_times" value="3" <?= $how_many_times_val === '3' ? 'checked' : '' ?> class="form-radio text-blue-600">
                                    <span class="ml-2">5 or more times</span>
                                </label>
                            </div>
                            <?php if (session('validation') && session('validation')->hasError('how_many_times')): ?>
                                <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('how_many_times') ?></p>
                            <?php endif; ?>
                        </div>
                        <!-- 11. If No, Why? -->
                        <div id="no_why_container" class="bg-gray-50 border <?= session('validation') && session('validation')->hasError('no_why') ? 'border-red-500' : 'border-gray-200' ?> rounded-lg p-4 shadow-sm flex flex-col gap-2">
                            <h4 class="font-medium mb-2">11. If No, Why?</h4>
                            <div class="flex gap-4 flex-wrap">
                                <?php $no_why_val = old('no_why') !== null ? old('no_why') : (isset($demographic_data['no_why']) ? $demographic_data['no_why'] : ''); ?>
                                <label class="inline-flex items-center cursor-pointer hover:bg-blue-50 rounded px-2 py-1">
                                    <input type="radio" name="no_why" value="1" <?= $no_why_val === '1' ? 'checked' : '' ?> class="form-radio text-blue-600">
                                    <span class="ml-2">There was no KK Assembly Meeting</span>
                                </label>
                                <label class="inline-flex items-center cursor-pointer hover:bg-blue-50 rounded px-2 py-1">
                                    <input type="radio" name="no_why" value="0" <?= $no_why_val === '0' ? 'checked' : '' ?> class="form-radio text-blue-600">
                                    <span class="ml-2">Not Interested to Attend</span>
                                </label>
                            </div>
                            <?php if (session('validation') && session('validation')->hasError('no_why')): ?>
                                <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('no_why') ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <!-- Document Uploads -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <?php 
                    $birth_cert_file = old('birth_certificate') ?? ($demographic_data['birth_certificate'] ?? ''); 
                    $has_birth_cert_error = session('file_errors') && isset(session('file_errors')['birth_certificate']);
                    $upload_id_file = old('upload_id') ?? ($demographic_data['upload_id'] ?? ''); 
                    $upload_id_back_file = old('upload_id_back') ?? ($demographic_data['upload_id-back'] ?? '');
                    $has_upload_id_error = session('file_errors') && isset(session('file_errors')['upload_id']);
                    $has_upload_id_back_error = session('file_errors') && isset(session('file_errors')['upload_id_back']);
                    ?>
                    <div class="bg-gray-50 border <?= $has_birth_cert_error ? 'border-red-500' : 'border-gray-200' ?> rounded-lg p-4 shadow-sm flex flex-col gap-2">
                        <h4 class="font-medium mb-2 flex items-center gap-2">Upload Birth Certificate <span class="text-red-500">*</span>
                            <button type="button" id="showSampleBirthCertBtn" class="ml-2 text-green-600 underline text-xs hover:text-green-800">Sample</button>
                        </h4>
                        
                        <div class="file-upload-container" data-has-existing-file="<?= ($birth_cert_file && !$has_birth_cert_error) ? 'true' : 'false' ?>">
                            <input type="file" name="birth_certificate" accept=".jpg,.jpeg,.png,.gif,.webp,.pdf" 
                                   class="file-upload-input" id="birth_certificate_input" 
                                   <?= (!$birth_cert_file || $has_birth_cert_error) ? 'data-required="true"' : '' ?>>
                            <div class="file-upload-button <?= $birth_cert_file && !$has_birth_cert_error ? 'has-file' : '' ?> <?= $has_birth_cert_error ? 'error' : '' ?>" 
                                 id="birth_certificate_button">
                                <div class="file-upload-text">
                                    <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <span class="text-xs font-medium text-gray-700" id="birth_certificate_text">
                                        <?php if ($birth_cert_file && !$has_birth_cert_error): ?>
                                            <strong>File uploaded:</strong><br>
                                            <span class="text-green-600 file-upload-filename" title="<?= esc($birth_cert_file) ?>"><?= esc($birth_cert_file) ?></span><br>
                                            <span class="text-xs text-blue-500">Click to replace</span>
                                        <?php elseif ($has_birth_cert_error): ?>
                                            <strong>File needs to be re-uploaded</strong><br>
                                            <span class="text-red-500">Previous file had errors</span>
                                        <?php else: ?>
                                            Click to upload or drag and drop
                                        <?php endif; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <?php if ($has_birth_cert_error): ?>
                            <div class="validation-message error show" style="color: #ef4444; font-size: 0.75rem; margin-top: 0.25rem; padding: 0.5rem; background-color: #fef2f2; border: 1px solid #fecaca; border-radius: 0.375rem;">
                                <?= session('file_errors')['birth_certificate'] ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 shadow-sm flex flex-col gap-2">
                        <h4 class="font-medium mb-2 flex items-center gap-2">Upload Valid ID (Front and Back)
                            <button type="button" id="showSampleValidIdBtn" class="ml-2 text-blue-600 underline text-xs hover:text-blue-800">Sample</button>
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Front -->
                            <div>
                                <label class="block text-xs text-slate-700 mb-1">Front Side <span class="text-red-500">*</span></label>
                                <div class="file-upload-container" data-has-existing-file="<?= ($upload_id_file && !$has_upload_id_error) ? 'true' : 'false' ?>">
                                    <input type="file" name="upload_id" accept=".jpg,.jpeg,.png,.gif,.webp,.pdf" 
                                           class="file-upload-input" id="upload_id_input" 
                                           <?= (!$upload_id_file || $has_upload_id_error) ? 'data-required="true"' : '' ?>>
                                    <div class="file-upload-button <?= $upload_id_file && !$has_upload_id_error ? 'has-file' : '' ?> <?= $has_upload_id_error ? 'error' : '' ?>" 
                                         id="upload_id_button">
                                        <div class="file-upload-text">
                                            <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                            <span class="text-xs font-medium text-gray-700" id="upload_id_text">
                                                <?php if ($upload_id_file && !$has_upload_id_error): ?>
                                                    <strong>File uploaded:</strong><br>
                                                    <span class="text-green-600 file-upload-filename" title="<?= esc($upload_id_file) ?>"><?= esc($upload_id_file) ?></span><br>
                                                    <span class="text-xs text-blue-500">Click to replace</span>
                                                <?php elseif ($has_upload_id_error): ?>
                                                    <strong>File needs to be re-uploaded</strong><br>
                                                    <span class="text-red-500">Previous file had errors</span>
                                                <?php else: ?>
                                                    Click to upload or drag and drop
                                                <?php endif; ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($has_upload_id_error): ?>
                                    <div class="validation-message error show" style="color: #ef4444; font-size: 0.75rem; margin-top: 0.25rem; padding: 0.5rem; background-color: #fef2f2; border: 1px solid #fecaca; border-radius: 0.375rem;">
                                        <?= session('file_errors')['upload_id'] ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <!-- Back (Optional) -->
                            <div>
                                <label class="block text-xs text-slate-700 mb-1">Back Side <span class="text-slate-400">(Optional)</span></label>
                                <div class="file-upload-container" data-has-existing-file="<?= ($upload_id_back_file && !$has_upload_id_back_error) ? 'true' : 'false' ?>">
                                    <input type="file" name="upload_id_back" accept=".jpg,.jpeg,.png,.gif,.webp,.pdf" 
                                           class="file-upload-input" id="upload_id_back_input">
                                    <div class="file-upload-button <?= $upload_id_back_file && !$has_upload_id_back_error ? 'has-file' : '' ?> <?= $has_upload_id_back_error ? 'error' : '' ?>" 
                                         id="upload_id_back_button">
                                        <div class="file-upload-text">
                                            <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                            </svg>
                                            <span class="text-xs font-medium text-gray-700" id="upload_id_back_text">
                                                <?php if ($upload_id_back_file && !$has_upload_id_back_error): ?>
                                                    <strong>File uploaded:</strong><br>
                                                    <span class="text-green-600 file-upload-filename" title="<?= esc($upload_id_back_file) ?>"><?= esc($upload_id_back_file) ?></span><br>
                                                    <span class="text-xs text-blue-500">Click to replace</span>
                                                <?php elseif ($has_upload_id_back_error): ?>
                                                    <strong>File needs to be re-uploaded</strong><br>
                                                    <span class="text-red-500">Previous file had errors</span>
                                                <?php else: ?>
                                                    Click to upload or drag and drop
                                                <?php endif; ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($has_upload_id_back_error): ?>
                                    <div class="validation-message error show" style="color: #ef4444; font-size: 0.75rem; margin-top: 0.25rem; padding: 0.5rem; background-color: #fef2f2; border: 1px solid #fecaca; border-radius: 0.375rem;">
                                        <?= session('file_errors')['upload_id_back'] ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row justify-between mt-6 sm:mt-8 space-y-3 sm:space-y-0">
                    <button type="submit" formaction="<?= base_url('profiling/backToStep2') ?>" formmethod="post" class="btn-secondary bg-slate-300 text-slate-700 text-sm sm:text-base font-semibold py-2 sm:py-3 px-4 sm:px-6 rounded-lg sm:rounded-xl hover:bg-slate-400 transition-all duration-200 flex items-center space-x-2 w-full sm:w-auto justify-center">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                        </svg>
                        <span>Back</span>
                    </button>
                    <button type="submit" class="btn-primary text-white text-sm sm:text-base font-semibold py-2 sm:py-3 px-6 sm:px-8 rounded-lg sm:rounded-xl transition-all duration-200 flex items-center space-x-2 hover:shadow-lg transform hover:scale-105 w-full sm:w-auto justify-center">
                        <span>Continue</span>
                        <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Step 4: Account Form -->
        <div class="account_container" id="part3" style="<?= $step == 4 ? '' : 'display:none;' ?>">
            <h2 class="text-lg sm:text-xl font-semibold mb-2">III. ACCOUNT INFORMATION</h2>
            <form action="<?= base_url('profiling/step3') ?>" method="post" class="space-y-4 sm:space-y-6" id="step3Form" enctype="multipart/form-data">
                <div class="grid grid-cols-1 gap-6">
                    <div class="info-container mb-2 flex flex-col">
                        <h4 class="font-medium mb-1">Username <span class="text-red-500">*</span></h4>
                        <?php $validationAccountErrors = session('validation_account_errors') ?? []; ?>
                        <input type="text" name="username" placeholder="Username" data-required="true" value="<?= old('username') !== null ? old('username') : (isset($account_data['username']) ? esc($account_data['username']) : '') ?>" class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400 <?= isset($validationAccountErrors['username']) ? 'border-red-500' : 'border-gray-300' ?>">
                        <?php if (isset($validationAccountErrors['username'])): ?>
                            <p class="text-red-500 text-xs mt-1"><?= $validationAccountErrors['username'] ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="info-container mb-2 flex flex-col">
                        <h4 class="font-medium mb-1">Password <span class="text-red-500">*</span></h4>
                        <?php $validationAccountErrors = session('validation_account_errors') ?? []; ?>
                        <input type="password" id="password" name="password" placeholder="Password" data-required="true" value="<?= old('password') !== null ? old('password') : (isset($account_data['password']) ? esc($account_data['password']) : '') ?>" class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400 <?= isset($validationAccountErrors['password']) ? 'border-red-500' : 'border-gray-300' ?>">
                        <?php if (isset($validationAccountErrors['password'])): ?>
                            <p class="text-red-500 text-xs mt-1"><?= $validationAccountErrors['password'] ?></p>
                        <?php endif; ?>
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
                    <div class="info-container mb-2 flex flex-col">
                        <h4 class="font-medium mb-1">Confirm Password <span class="text-red-500">*</span></h4>
                        <?php $validationAccountErrors = session('validation_account_errors') ?? []; ?>
                        <input type="password" name="confirm_password" placeholder="Confirm Password" data-required="true" value="<?= old('confirm_password') !== null ? old('confirm_password') : (isset($account_data['confirm_password']) ? esc($account_data['confirm_password']) : '') ?>" class="w-full p-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400 <?= isset($validationAccountErrors['confirm_password']) ? 'border-red-500' : 'border-gray-300' ?>">
                        <?php if (isset($validationAccountErrors['confirm_password'])): ?>
                            <p class="text-red-500 text-xs mt-1"><?= $validationAccountErrors['confirm_password'] ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="info-container mb-2 flex flex-col">
                        <h4 class="font-medium mb-1 flex items-center gap-2">1x1 Profile Picture (White Background) <span class="text-red-500">*</span>
                            <button type="button" id="showSamplePicBtn" class="ml-2 text-blue-600 underline text-xs hover:text-blue-800">Sample</button>
                        </h4>
                        <?php 
                        $profile_picture_file = old('profile_picture') ?? ($account_data['profile_picture'] ?? ''); 
                        $has_profile_pic_error = isset(session('file_errors')['profile_picture']) && session('file_errors')['profile_picture'];
                        ?>
                        
                        <div class="file-upload-container" data-has-existing-file="<?= ($profile_picture_file && !$has_profile_pic_error) ? 'true' : 'false' ?>">
                            <input type="file" name="profile_picture" accept=".jpg,.jpeg,.png,.webp" 
                                   class="file-upload-input" id="profile_picture_input" 
                                   <?= (!$profile_picture_file || $has_profile_pic_error) ? 'data-required="true"' : '' ?>>
                            <div class="file-upload-button <?= $profile_picture_file && !$has_profile_pic_error ? 'has-file' : '' ?> <?= $has_profile_pic_error ? 'error' : '' ?>" 
                                 id="profile_picture_button">
                                <div class="file-upload-text">
                                    <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700" id="profile_picture_text">
                                        <?php if ($profile_picture_file && !$has_profile_pic_error): ?>
                                            <strong>Photo uploaded:</strong><br>
                                            <span class="text-green-600 file-upload-filename" title="<?= esc($profile_picture_file) ?>"><?= esc($profile_picture_file) ?></span><br>
                                            <span class="text-xs text-blue-500">Click to replace</span>
                                        <?php elseif ($has_profile_pic_error): ?>
                                            <strong>Photo needs to be re-uploaded</strong><br>
                                            <span class="text-red-500">Previous photo had errors</span>
                                        <?php else: ?>
                                            Click to upload your 1x1 photo<br>
                                            <span class="text-xs text-gray-500">JPG, PNG, WEBP up to 5MB</span>
                                        <?php endif; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <?php if ($has_profile_pic_error): ?>
                            <div class="validation-message error show" style="color: #ef4444; font-size: 0.75rem; margin-top: 0.25rem; padding: 0.5rem; background-color: #fef2f2; border: 1px solid #fecaca; border-radius: 0.375rem;">
                                <?= session('file_errors')['profile_picture'] ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="flex justify-between mt-8">
                    <button type="submit" formaction="<?= base_url('profiling/backToStep3') ?>" formmethod="post" class="btn-secondary bg-slate-300 text-slate-700 font-semibold py-3 px-6 rounded-xl hover:bg-slate-400 transition-all duration-200 flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                        </svg>
                        <span>Back</span>
                    </button>
                    <button type="submit" class="btn-primary text-white font-semibold py-3 px-8 rounded-xl transition-all duration-200 flex items-center space-x-2 hover:shadow-lg transform hover:scale-105">
                        <span>Continue</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
        <!-- Sample Profile Picture Modal -->
        <div id="samplePicModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
            <div class="bg-white border border-blue-400 text-blue-700 px-8 pt-6 pb-4 rounded-lg shadow-lg relative animate-fade-in max-w-sm w-full flex flex-col items-center z-20">
                <span class="block text-lg font-semibold mb-2">Sample 1x1 Picture</span>
                <div style="width: 15rem; height: 15rem; overflow: hidden; border: 1px solid #d1d5db; border-radius: 0.375rem; background: white; margin-bottom: 0.5rem; margin-top: 0;">
                    <img src="https://i.pinimg.com/736x/d4/5e/77/d45e7768a551280b6597d3cb5caa589b.jpg" alt="Sample 1x1" style="width: 100%; height: 120%; object-fit: cover; object-position: center top; position: relative; top: -20px;">
                </div>
                <span class="text-xs text-gray-500 mb-2">White background, clear face, 1x1 ratio</span>
                <button id="closeSamplePicModal" class="absolute top-2 right-2 text-blue-700 hover:text-blue-900 text-xl font-bold">&times;</button>
            </div>
            <div id="samplePicModalBg" class="fixed inset-0 bg-black opacity-30 z-10"></div>
        </div>

        <!-- Sample Birth Certificate Modal -->
        <div id="sampleBirthCertModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
            <div class="bg-white border border-green-400 text-green-700 px-8 pt-6 pb-4 rounded-lg shadow-lg relative animate-fade-in max-w-3xl w-full mx-4 flex flex-col items-center z-20">
                <span class="block text-lg font-semibold mb-3">Sample Birth Certificate</span>
                
                <!-- Content Container -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 w-full">
                    <!-- Left Side - Accepted Types (2 columns) -->
                    <div class="md:col-span-2 flex flex-col">
                        <div class="text-sm text-gray-600 text-left">
                            <h4 class="font-medium mb-4 text-base">Accepted Birth Certificate Types:</h4>
                            <ul class="text-sm space-y-2">
                                <li class="flex items-start">
                                    <span class="text-green-600 mr-2">•</span>
                                    <span>PSA (Philippine Statistics Authority) Birth Certificate</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-green-600 mr-2">•</span>
                                    <span>NSO (National Statistics Office) Birth Certificate</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-green-600 mr-2">•</span>
                                    <span>Local Civil Registry Birth Certificate</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-green-600 mr-2">•</span>
                                    <span>Certified True Copy from PSA/NSO</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Right Side - Sample Image (3 columns) -->
                    <div class="md:col-span-2 flex flex-col">
                        <h5 class="text-sm font-medium text-gray-700 mb-2 text-center">Sample Document</h5>
                        <div style="width: 100%; height: 400px; overflow: hidden; border: 1px solid #d1d5db; border-radius: 0.375rem; background: #f3f4f6; position: relative;">
                            <!-- Loading placeholder -->
                            <div id="birthCertLoading" class="absolute inset-0 flex items-center justify-center">
                                <div class="text-center p-4">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-2 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <div class="text-sm font-medium text-gray-700">Loading Sample...</div>
                                </div>
                            </div>
                            <!-- Actual image -->
                            <img id="birthCertImage" 
                                 src="https://i.pinimg.com/736x/54/39/16/543916392604de744f9cad775ac5a9b1.jpg" 
                                 alt="Sample Birth Certificate" 
                                 style="width: 100%; height: 100%; object-fit: contain; display: none;"
                                 onload="document.getElementById('birthCertLoading').style.display='none'; this.style.display='block';"
                                 onerror="document.getElementById('birthCertLoading').querySelector('.text-sm').textContent='PSA Birth Certificate'; document.getElementById('birthCertLoading').querySelector('.text-xs').textContent='Official government-issued birth certificate';">
                            </div>
                        </div>
                    </div>
                
                <button id="closeSampleBirthCertModal" class="absolute top-2 right-2 text-green-700 hover:text-green-900 text-xl font-bold">&times;</button>
            </div>
            <div id="sampleBirthCertModalBg" class="fixed inset-0 bg-black opacity-30 z-10"></div>
        </div>

        <!-- Sample Valid ID Modal -->
        <div id="sampleValidIdModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
            <div class="bg-white border border-blue-400 text-blue-700 px-8 pt-6 pb-4 rounded-lg shadow-lg relative animate-fade-in max-w-3xl w-full mx-4 flex flex-col items-center z-20">
                <span class="block text-lg font-semibold mb-3">Sample Valid ID</span>
                
                <!-- Content wrapper with responsive flex direction -->
                <div class="flex flex-col-reverse md:flex-col w-full">
                    <!-- ID Images Container - Shows second on mobile (due to flex-col-reverse), first on desktop -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full mb-4">
                        <!-- Front ID -->
                        <div class="text-center">
                            <h5 class="text-sm font-medium text-gray-700 mb-2">Front Side</h5>
                            <div style="width: 100%; height: 200px; overflow: hidden; border: 1px solid #d1d5db; border-radius: 0.375rem; background: #f3f4f6; position: relative;">
                                <!-- Loading placeholder for front -->
                                <div id="idFrontLoading" class="absolute inset-0 flex items-center justify-center">
                                    <div class="text-center p-4">
                                        <svg class="mx-auto h-8 w-8 text-gray-400 mb-2 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                        </svg>
                                        <div class="text-xs font-medium text-gray-700">Loading...</div>
                                    </div>
                                </div>
                                <!-- Actual front image -->
                                <img id="idFrontImage" 
                                     src="https://philsys.gov.ph/wp-content/uploads/2022/11/PhilID-specimen-Front_highres1-768x432.png" 
                                     alt="Sample Valid ID Front" 
                                     style="width: 100%; height: 100%; object-fit: contain; display: none;"
                                     onload="document.getElementById('idFrontLoading').style.display='none'; this.style.display='block';"
                                     onerror="document.getElementById('idFrontLoading').querySelector('.text-xs').textContent='ID Front';">
                            </div>
                        </div>
                        
                        <!-- Back ID -->
                        <div class="text-center">
                            <h5 class="text-sm font-medium text-gray-700 mb-2">Back Side</h5>
                            <div style="width: 100%; height: 200px; overflow: hidden; border: 1px solid #d1d5db; border-radius: 0.375rem; background: #f3f4f6; position: relative;">
                                <!-- Loading placeholder for back -->
                                <div id="idBackLoading" class="absolute inset-0 flex items-center justify-center">
                                    <div class="text-center p-4">
                                        <svg class="mx-auto h-8 w-8 text-gray-400 mb-2 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                        </svg>
                                        <div class="text-xs font-medium text-gray-700">Loading...</div>
                                    </div>
                                </div>
                                <!-- Actual back image -->
                                <img id="idBackImage" 
                                     src="https://philsys.gov.ph/wp-content/uploads/2022/11/PhilID-specimen-Back_highres2-768x432.png" 
                                     alt="Sample Valid ID Back" 
                                     style="width: 100%; height: 100%; object-fit: contain; display: none;"
                                     onload="document.getElementById('idBackLoading').style.display='none'; this.style.display='block';"
                                     onerror="document.getElementById('idBackLoading').querySelector('.text-xs').textContent='ID Back';">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Accepted Types Section - Shows first on mobile (due to flex-col-reverse), second on desktop -->
                    <div class="text-sm text-gray-600 mb-4 text-left w-full">
                        <h4 class="font-medium mb-2">Accepted Valid ID Types:</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs">
                            <ul class="space-y-1">
                                <li>• Driver's License</li>
                                <li>• SSS ID</li>
                                <li>• UMID</li>
                                <li>• PhilHealth ID</li>
                                <li>• Passport</li>
                                <li>• Voter's ID</li>
                            </ul>
                            <ul class="space-y-1">
                                <li>• TIN ID</li>
                                <li>• Postal ID</li>
                                <li>• Barangay ID</li>
                                <li>• School ID</li>
                                <li>• Senior Citizen ID</li>
                                <li>• PWD ID</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <button id="closeSampleValidIdModal" class="absolute top-2 right-2 text-blue-700 hover:text-blue-900 text-xl font-bold">&times;</button>
            </div>
            <div id="sampleValidIdModalBg" class="fixed inset-0 bg-black opacity-30 z-10"></div>
        </div>
        
        <!-- Step 5: Preview Information -->
        <div class="preview_container step-transition" id="part4" style="<?= $step == 5 ? '' : 'display:none;' ?>">
            <div class="animate-slide-in">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-slate-800 mb-2">Review Your Information</h2>
                    <p class="text-slate-600">Please review all the information below before submitting your registration</p>
                </div>

                <!-- Profile Picture Preview -->
                <?php if (isset($account_data['profile_picture']) && $account_data['profile_picture']): ?>
                <div class="flex justify-center mb-8">
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4 text-center flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Profile Picture
                        </h3>
                        <div class="flex flex-col items-center space-y-4">
                            <div class="w-48 h-48 rounded-lg overflow-hidden border-2 border-slate-200 shadow-sm">
                                <img src="<?= base_url('uploads/profile_pictures/' . $account_data['profile_picture']) ?>" 
                                     alt="Profile Picture" 
                                     class="w-full h-full object-cover">
                            </div>
                            <button type="button" 
                                    onclick="previewDocument('<?= base_url('uploads/profile_pictures/' . $account_data['profile_picture']) ?>', '<?= esc($account_data['profile_picture']) ?>', 'Profile Picture')"
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 hover:text-blue-700 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                View Full Size
                            </button>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Information Cards -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Personal Information Card -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                        <h3 class="text-xl font-semibold text-slate-800 mb-6 flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            Personal Information
                        </h3>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">Full Name:</span>
                                <span class="text-slate-800 font-semibold text-right">
                                    <?= esc($profile_data['first_name'] ?? '') ?> 
                                    <?= esc($profile_data['middle_name'] ?? '') ?> 
                                    <?= esc($profile_data['last_name'] ?? '') ?> 
                                    <?= esc($profile_data['suffix'] ?? '') ?>
                                </span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">Sex:</span>
                                <span class="text-slate-800"><?= isset($profile_data['sex']) ? ($profile_data['sex'] == '1' ? 'Male' : ($profile_data['sex'] == '2' ? 'Female' : '')) : '' ?></span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">Date of Birth:</span>
                                <span class="text-slate-800"><?= esc($profile_data['birthdate'] ?? '') ?></span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">Email:</span>
                                <span class="text-slate-800 text-right break-all"><?= esc($profile_data['email'] ?? '') ?></span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="text-slate-600 font-medium">Phone:</span>
                                <span class="text-slate-800"><?= esc($profile_data['phone_number'] ?? '') ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Address Information Card -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                        <h3 class="text-xl font-semibold text-slate-800 mb-6 flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            Address Information
                        </h3>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">Region:</span>
                                <span class="text-slate-800"><?= esc($profile_data['region'] ?? '') ?></span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">Province:</span>
                                <span class="text-slate-800"><?= esc($profile_data['province'] ?? '') ?></span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">Municipality:</span>
                                <span class="text-slate-800"><?= esc($profile_data['municipality'] ?? '') ?></span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">Barangay:</span>
                                <span class="text-slate-800">
                                    <?php
                                    $barangay_names = [
                                        '1' => 'Antipolo', '2' => 'Cristo Rey', '3' => 'Del Rosario (Banao)', '4' => 'Francia',
                                        '5' => 'La Anunciacion', '6' => 'La Medalla', '7' => 'La Purisima', '8' => 'La Trinidad',
                                        '9' => 'Niño Jesus', '10' => 'Perpetual Help', '11' => 'Sagrada', '12' => 'Salvacion',
                                        '13' => 'San Agustin', '14' => 'San Andres', '15' => 'San Antonio', '16' => 'San Francisco',
                                        '17' => 'San Isidro', '18' => 'San Jose', '19' => 'San Juan', '20' => 'San Miguel',
                                        '21' => 'San Nicolas', '22' => 'San Pedro', '23' => 'San Rafael', '24' => 'San Ramon',
                                        '25' => 'San Roque', '26' => 'Santiago', '27' => 'San Vicente Norte', '28' => 'San Vicente Sur',
                                        '29' => 'Santa Cruz Norte', '30' => 'Santa Cruz Sur', '31' => 'Santa Elena', '32' => 'Santa Isabel',
                                        '33' => 'Santa Maria', '34' => 'Santa Teresita', '35' => 'Santo Domingo', '36' => 'Santo Niño'
                                    ];
                                    echo esc($barangay_names[$profile_data['barangay'] ?? ''] ?? '');
                                    ?>
                                </span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="text-slate-600 font-medium">Zone/Purok:</span>
                                <span class="text-slate-800"><?= esc($profile_data['zone_purok'] ?? '') ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Demographic Information Card -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                        <h3 class="text-xl font-semibold text-slate-800 mb-6 flex items-center">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            Demographic Information
                        </h3>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">Civil Status:</span>
                                <span class="text-slate-800">
                                    <?php
                                    $civil_status_names = [
                                        '1' => 'Single', '2' => 'Married', '3' => 'Widowed', '4' => 'Divorced',
                                        '5' => 'Separated', '6' => 'Annulled', '7' => 'Live-in', '8' => 'Unknown'
                                    ];
                                    echo esc($civil_status_names[$demographic_data['civil_status'] ?? ''] ?? '');
                                    ?>
                                </span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">Youth Classification:</span>
                                <span class="text-slate-800 text-right">
                                    <?php
                                    $youth_classification_names = [
                                        '1' => 'In School Youth', '2' => 'Out-of-School Youth', '3' => 'Working Youth',
                                        '4' => 'Youth with Specific Needs', '5' => 'Person with Disability',
                                        '6' => 'Children in Conflict with the Law', '7' => 'Indigenous People'
                                    ];
                                    echo esc($youth_classification_names[$demographic_data['youth_classification'] ?? ''] ?? '');
                                    ?>
                                </span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">Age Group:</span>
                                <span class="text-slate-800">
                                    <?php
                                    $age_group_names = [
                                        '1' => 'Child Youth (15-17)', '2' => 'Young Adult (18-24)', '3' => 'Adult (25-30)'
                                    ];
                                    echo esc($age_group_names[$demographic_data['age_group'] ?? ''] ?? '');
                                    ?>
                                </span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">Work Status:</span>
                                <span class="text-slate-800 text-right">
                                    <?php
                                    $work_status_names = [
                                        '1' => 'Employed', '2' => 'Unemployed', '3' => 'Currently looking for a Job',
                                        '4' => 'Not Interested in finding a job'
                                    ];
                                    echo esc($work_status_names[$demographic_data['work_status'] ?? ''] ?? '');
                                    ?>
                                </span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="text-slate-600 font-medium">Education:</span>
                                <span class="text-slate-800 text-right">
                                    <?php
                                    $education_names = [
                                        '1' => 'Elementary Level', '2' => 'Elementary Graduate', '3' => 'High School Level',
                                        '4' => 'High School Graduate', '5' => 'Vocational Level', '6' => 'College Level',
                                        '7' => 'College Graduate', '8' => 'Master Level', '9' => 'Master Graduate',
                                        '10' => 'Doctorate Level', '11' => 'Doctorate Graduate'
                                    ];
                                    echo esc($education_names[$demographic_data['educational_background'] ?? ''] ?? '');
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Account & Registration Card -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200">
                        <h3 class="text-xl font-semibold text-slate-800 mb-6 flex items-center">
                            <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            Account & Registration
                        </h3>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">Username:</span>
                                <span class="text-slate-800 font-mono"><?= esc($account_data['username'] ?? '') ?></span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">SK Voter:</span>
                                <span class="text-slate-800"><?= isset($demographic_data['sk_voter']) ? ($demographic_data['sk_voter'] == '1' ? 'Yes' : 'No') : '' ?></span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-slate-100">
                                <span class="text-slate-600 font-medium">National Voter:</span>
                                <span class="text-slate-800"><?= isset($demographic_data['national_voter']) ? ($demographic_data['national_voter'] == '1' ? 'Yes' : 'No') : '' ?></span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="text-slate-600 font-medium">KK Assembly:</span>
                                <span class="text-slate-800"><?= isset($demographic_data['kk_assembly']) ? ($demographic_data['kk_assembly'] == '1' ? 'Yes' : 'No') : '' ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Documents Section -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100 mb-8">
                    <h3 class="text-xl font-semibold text-slate-800 mb-6 flex items-center">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        Uploaded Documents
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-lg p-4 border border-blue-200">
                            <h4 class="font-medium text-slate-700 mb-2">Birth Certificate</h4>
                            <?php if (isset($demographic_data['birth_certificate']) && $demographic_data['birth_certificate'] && (!session('file_errors') || !isset(session('file_errors')['birth_certificate']))): ?>
                                <div class="flex items-center space-x-2 mb-2">
                                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm text-slate-600">Uploaded successfully</span>
                                </div>
                                <div class="mt-2">
                                    <button type="button" 
                                            onclick="previewDocument('<?= base_url('uploads/certificate/' . $demographic_data['birth_certificate']) ?>', '<?= esc($demographic_data['birth_certificate']) ?>', 'Birth Certificate')"
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 hover:text-blue-700 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Preview Document
                                    </button>
                                    <p class="text-xs text-slate-500 mt-1"><?= esc($demographic_data['birth_certificate']) ?></p>
                                </div>
                            <?php else: ?>
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    <span class="text-sm text-red-500">Not uploaded or has errors</span>
                                </div>
                                <?php if (session('file_errors') && isset(session('file_errors')['birth_certificate'])): ?>
                                    <div class="mt-1 text-xs text-red-600">
                                        Error: <?= session('file_errors')['birth_certificate'] ?>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        
                        <div class="bg-white rounded-lg p-4 border border-blue-200">
                            <h4 class="font-medium text-slate-700 mb-2">Valid ID</h4>
                            <?php if (isset($demographic_data['upload_id']) && $demographic_data['upload_id'] && (!session('file_errors') || !isset(session('file_errors')['upload_id']))): ?>
                                <div class="flex items-center space-x-2 mb-2">
                                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm text-slate-600">Uploaded successfully</span>
                                </div>
                                <div class="mt-2">
                                    <button type="button" 
                                            onclick="previewDocument('<?= base_url('uploads/id/' . $demographic_data['upload_id']) ?>', '<?= esc($demographic_data['upload_id']) ?>', 'Valid ID')"
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 hover:text-blue-700 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Preview Front Side
                                    </button>
                                    <p class="text-xs text-slate-500 mt-1"><?= esc($demographic_data['upload_id']) ?></p>
                                    <?php if (isset($demographic_data['upload_id-back']) && $demographic_data['upload_id-back']): ?>
                                        <div class="mt-3">
                                            <button type="button" 
                                                    onclick="previewDocument('<?= base_url('uploads/id/' . $demographic_data['upload_id-back']) ?>', '<?= esc($demographic_data['upload_id-back']) ?>', 'Valid ID (Back)')"
                                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 hover:text-blue-700 transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Preview Back Side
                                            </button>
                                            <p class="text-xs text-slate-500 mt-1"><?= esc($demographic_data['upload_id-back']) ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    <span class="text-sm text-red-500">Not uploaded or has errors</span>
                                </div>
                                <?php if (session('file_errors') && isset(session('file_errors')['upload_id'])): ?>
                                    <div class="mt-1 text-xs text-red-600">
                                        Error: <?= session('file_errors')['upload_id'] ?>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Important Notice -->
                <div class="bg-yellow-50 border-l-4 border-yellow-400 rounded-lg p-4 mb-8">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-yellow-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Important Notice</h3>
                            <p class="mt-1 text-sm text-yellow-700">
                                Please review all information carefully. Once submitted, you will not be able to edit your registration details. 
                                Make sure all information is accurate and complete.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-between items-center pt-6">
                    <form action="<?= base_url('profiling/backToStep4') ?>" method="post" style="display:inline;">
                        <button type="submit" formaction="<?= base_url('profiling/backToStep4') ?>" formmethod="post"
                            class="btn-secondary bg-slate-300 text-slate-700 font-semibold py-3 px-6 rounded-xl hover:bg-slate-400 transition-all duration-200 flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                            </svg>
                            <span>Back</span>
                        </button>
                    </form>
                    
                    <form action="<?= base_url('profiling/submit') ?>" method="post" style="display:inline;">
                        <button type="submit" 
                            class="btn-primary text-white font-semibold py-3 px-8 rounded-xl transition-all duration-200 flex items-center space-x-2 hover:shadow-lg transform hover:scale-105">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Confirm & Submit Registration</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Step 6: Success State -->
    <div class="success_container step-transition" id="part5" style="<?= $step == 6 ? '' : 'display:none;' ?>">
        <div class="max-w-lg mx-auto p-6">
            <div class="bg-white border border-gray-200 rounded-xl p-6 text-center shadow-sm">
                <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-green-100 mb-3">
                    <svg class="h-7 w-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <?php if (isset($success_type) && $success_type === 'reupload'): ?>
                    <h1 class="text-xl font-semibold text-gray-900 mb-1">Profile updated</h1>
                    <p class="text-sm text-gray-600 mb-4">Your profile was resubmitted for review.</p>
                <?php else: ?>
                    <h1 class="text-xl font-semibold text-gray-900 mb-1">Registration successful</h1>
                    <p class="text-sm text-gray-600 mb-4">Your account was created and is pending approval.</p>
                <?php endif; ?>
                <div class="flex items-center justify-center gap-3">
                    <button onclick="redirectToLogin()" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                        Go to login
                    </button>
                    <button onclick="startNewRegistration()" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-200">
                        Register another
                    </button>
                </div>
                <div class="mt-4 text-xs text-gray-500">
                    Redirecting in <span id="countdown">5</span>s
                    <div class="w-full bg-gray-200 rounded-full h-1 mt-1">
                        <div id="progressBar" class="bg-blue-600 h-1 rounded-full" style="width:0%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal Popup -->
    <div id="successModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 modal-backdrop modal-container z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl p-6 max-w-xs w-full mx-4 modal-content">
            <div class="text-center">
                <!-- Success Icon -->
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-3">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                
                <!-- Success Title -->
                <h3 id="successTitle" class="text-lg font-bold text-green-600 mb-2">Success!</h3>
                
                <!-- Success Message -->
                <p id="successMessage" class="text-gray-700 mb-4 text-xs"></p>
                
                <!-- Countdown Section -->
                <div class="bg-white border border-blue-200 rounded-lg p-3 mb-4">
                    <p class="text-gray-600 text-xs mb-1">Redirecting in:</p>
                    <div class="text-lg font-bold text-blue-600 mb-2 countdown-smooth">
                        <span id="modalCountdown">5</span>s
                    </div>
                    <div class="bg-gray-200 rounded-full h-2 mb-1 progress-container">
                        <div id="modalProgressBar" class="progress-bar-seamless h-2 rounded-full progress-smooth" style="width: 0%"></div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex space-x-2">
                    <button onclick="redirectToLogin()" class="flex-1 bg-blue-600 text-white py-2 px-3 rounded-lg font-medium button-hover shadow text-xs hover:bg-blue-700 transition-colors">
                        Login Now
                    </button>
                    <button onclick="startNewRegistration()" class="flex-1 bg-gray-200 text-gray-700 py-2 px-3 rounded-lg font-medium button-hover shadow text-xs hover:bg-gray-300 transition-colors">
                        Register New
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="file-size-modal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="bg-white border border-red-400 text-red-700 px-8 py-6 rounded-lg shadow-lg relative animate-fade-in max-w-sm w-full z-10">
            <span class="block text-lg font-semibold mb-2">File Size Error</span>
            <span id="file-size-modal-message"></span>
            <button id="file-size-modal-close" class="absolute top-2 right-2 text-red-700 hover:text-red-900 text-xl font-bold">&times;</button>
        </div>
        <div id="file-size-modal-bg" class="fixed inset-0 bg-black opacity-30 z-0"></div>
    </div>

    <!-- Document Preview Modal -->
    <div id="document-preview-modal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl relative animate-fade-in max-w-4xl max-h-[90vh] w-full mx-4 z-20">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900" id="document-preview-title">Document Preview</h3>
                <div class="flex items-center space-x-2">
                    <button id="document-download-btn" class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 hover:text-blue-700 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Download
                    </button>
                    <button id="document-preview-close" onclick="if(window.closeDocumentPreview) window.closeDocumentPreview(); return false;" class="text-gray-400 hover:text-gray-600 text-xl font-bold w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 transition-colors duration-200">
                        &times;
                    </button>
                </div>
            </div>
            
            <!-- Modal Body -->
            <div class="p-4 max-h-[calc(90vh-120px)] overflow-auto">
                <div id="document-preview-content" class="flex items-center justify-center">
                    <!-- Loading state -->
                    <div id="document-loading" class="flex flex-col items-center justify-center py-12">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mb-4"></div>
                        <p class="text-gray-600">Loading document...</p>
                    </div>
                    
                    <!-- Preview content will be inserted here -->
                    <div id="document-viewer" class="hidden w-full">
                        <!-- For images -->
                        <img id="document-image" class="max-w-full h-auto rounded-lg shadow-sm hidden" alt="Document preview">
                        
                        <!-- For PDFs -->
                        <div id="document-pdf" class="hidden w-full">
                            <iframe id="pdf-viewer" class="w-full h-96 border rounded-lg" frameborder="0"></iframe>
                            <p class="text-sm text-gray-600 mt-2 text-center">
                                If the PDF doesn't display properly, 
                                <a id="pdf-fallback-link" href="#" target="_blank" class="text-blue-600 hover:text-blue-800 underline">click here to open in a new tab</a>
                            </p>
                        </div>
                        
                        <!-- Error state -->
                        <div id="document-error" class="hidden flex flex-col items-center justify-center py-12">
                            <svg class="w-16 h-16 text-red-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">Cannot Preview Document</h4>
                            <p class="text-gray-600 text-center mb-4">This document type cannot be previewed directly.</p>
                            <button id="document-error-download" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Download to View
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="document-preview-modal-bg" onclick="if(window.closeDocumentPreview) window.closeDocumentPreview(); return false;" class="fixed inset-0 bg-black opacity-75 z-10"></div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Expose current server-allowed step (1..6) to JS
            window.serverProfilingStep = parseInt('<?= (int)($step ?? 1) ?>', 10) || 1;
            // Add validation styles if not already present
            if (!document.querySelector('style[data-validation]')) {
                const style = document.createElement('style');
                style.setAttribute('data-validation', 'true');
                style.textContent = `
                    .shake {
                        animation: shake 0.5s ease-in-out;
                    }
                    @keyframes shake {
                        0%, 100% { transform: translateX(0); }
                        10%, 30%, 50%, 70%, 90% { transform: translateX(-3px); }
                        20%, 40%, 60%, 80% { transform: translateX(3px); }
                    }
                    .field-error {
                        border-color: #ef4444 !important;
                        background-color: #fef2f2 !important;
                    }
                `;
                document.head.appendChild(style);
            }

            // Initialize all functionality
            initializeFormPersistence();
            initializeInstantValidation();
            initializePhoneValidation(); // Enhanced email and phone validation
            initializeBackButtonHandling();
            initializeFileUploads();
            initializeAgeGroupCalculation();
            initializeDependentLogic();
            initializeModalHandlers();
            initializeProfilingTimeouts();

            // Form persistence functionality
            function initializeFormPersistence() {
                // Save form data on input/change
                const allInputs = document.querySelectorAll('input, select, textarea');
                allInputs.forEach(input => {
                    // Skip certain inputs that shouldn't be persisted
                    if (input.type === 'submit' || input.type === 'button' || input.type === 'file') {
                        return;
                    }
                    
                    // Add event listeners for all input types
                    input.addEventListener('input', () => saveFormData());
                    input.addEventListener('change', () => saveFormData());
                    
                    // For radio buttons, add change event to all radios in the group
                    if (input.type === 'radio') {
                        input.addEventListener('change', () => saveFormData());
                    }
                });
                
                // Load saved form data on page load
                loadFormData();
                
        // Save form data before submitting forms or navigating
                const forms = document.querySelectorAll('form');
                forms.forEach(form => {
                    form.addEventListener('submit', function(e) {
            // Mark that this is an intentional in-app submission to avoid beforeunload cleanup
            window.isProfilingFormSubmit = true;
                        // Check if this is a back button submission
                        const clickedButton = document.activeElement;
                        const isBackButton = clickedButton && (
                            clickedButton.getAttribute('formaction') && 
                            clickedButton.getAttribute('formaction').includes('backTo')
                        );
                        
                        // Save current form data before submission
                        saveFormData();
                        
                        // For forward submissions, try to restore cached files before validation
                        if (!isBackButton) {
                            restoreCachedFiles();
                        }
                    });
                });
            }
            
            function saveFormData() {
                const formData = {};
                const fileData = {};
                // Do not extend session due to background input changes when pre-warning is visible
                const allowActivityUpdate = !window.isPrewarnVisible;
                
            // Save form data and file metadata to sessionStorage (clears on browser close)
                const allInputs = document.querySelectorAll('input, select, textarea');
                allInputs.forEach(input => {
                    if (input.type === 'submit' || input.type === 'button') {
                        return;
                    }
                    
            // Also persist the currently visible step/tab
            try { saveCurrentPart(); } catch (e) { /* noop */ }
                    if (input.type === 'file') {
                        // Save file information
                        if (input.files && input.files.length > 0) {
                            const file = input.files[0];
                            fileData[input.name] = {
                                name: file.name,
                                size: file.size,
                                type: file.type,
                                lastModified: file.lastModified
                            };
                        }
                        return;
                    }
                    
                    if (input.type === 'radio' || input.type === 'checkbox') {
                        if (input.checked) {
                            formData[input.name] = input.value;
                        }
                    } else {
                        if (input.value.trim() !== '') {
                            formData[input.name] = input.value;
                        }
                    }
                });
                
                // Save form data and file metadata to sessionStorage (clears on browser close)
                sessionStorage.setItem('profiling_form_data', JSON.stringify(formData));
                sessionStorage.setItem('profiling_file_info', JSON.stringify(fileData));
                // Update last activity timestamp only if not showing pre-warning
                if (allowActivityUpdate) {
                    sessionStorage.setItem('profiling_last_activity', Date.now().toString());
                }
            }
            
            function loadFormData() {
                const savedData = sessionStorage.getItem('profiling_form_data');
                const savedFileInfo = sessionStorage.getItem('profiling_file_info');
                
                if (savedData) {
                    try {
                        const formData = JSON.parse(savedData);
                        
                        // Restore form values
                        Object.keys(formData).forEach(name => {
                            const value = formData[name];
                            const inputs = document.querySelectorAll(`[name="${name}"]`);
                            
                            inputs.forEach(input => {
                                if (input.type === 'radio' || input.type === 'checkbox') {
                                    if (input.value === value) {
                                        input.checked = true;
                                        // Trigger change event to update dependent fields
                                        input.dispatchEvent(new Event('change', { bubbles: true }));
                                    }
                                } else {
                                    input.value = value;
                                    // Trigger change event to update dependent fields
                                    input.dispatchEvent(new Event('change', { bubbles: true }));
                                }
                            });
                        });
                        
                        // Update age group calculation after loading birthdate
                        if (window.updateAgeGroupCalculation) {
                            window.updateAgeGroupCalculation();
                        }
                        
                    } catch (e) {
                        console.error('Error loading form data:', e);
                    }
                }
                
                // Restore file upload states
                if (savedFileInfo) {
                    try {
                        const fileInfo = JSON.parse(savedFileInfo);
                        
                        Object.keys(fileInfo).forEach(inputName => {
                            const info = fileInfo[inputName];
                            const input = document.querySelector(`input[name="${inputName}"]`);
                            
                            if (input && input.type === 'file') {
                                const container = input.closest('.file-upload-container');
                                const button = container?.querySelector('.file-upload-button');
                                const textElement = container?.querySelector(`#${input.id.replace('_input', '_text')}`);
                                
                                if (button && textElement) {
                                    // Mark file as uploaded from cache
                                    button.classList.add('has-file');
                                    button.classList.remove('error');
                                    
                                    const fileName = info.name.length > 30 ? 
                                        info.name.substring(0, 30) + '...' : info.name;
                                    
                                    textElement.innerHTML = `
                                        <strong>File from cache:</strong><br>
                                        <span class="text-blue-600">${fileName}</span><br>
                                        <span class="text-xs text-orange-500">File selected previously - click to change</span>
                                    `;
                                    
                                    // Mark as not required since file exists in cache
                                    input.removeAttribute('data-required');
                                    clearFieldError(input);
                                }
                            }
                        });
                        
                    } catch (e) {
                        console.error('Error loading file data:', e);
                    }
                }
            }
            
            function restoreCachedFiles() {
                const savedFileInfo = sessionStorage.getItem('profiling_file_info');
                if (!savedFileInfo) return;
                
                try {
                    const fileInfo = JSON.parse(savedFileInfo);
                    
                    Object.keys(fileInfo).forEach(inputName => {
                        const input = document.querySelector(`input[name="${inputName}"]`);
                        
                        // Only restore if input exists and has no current file
                        if (input && input.type === 'file' && (!input.files || input.files.length === 0)) {
                            const container = input.closest('.file-upload-container');
                            const button = container?.querySelector('.file-upload-button');
                            
                            // Check if file has the 'cached' state
                            if (button && button.classList.contains('has-file')) {
                                const textElement = container.querySelector(`#${input.id.replace('_input', '_text')}`);
                                if (textElement && textElement.innerHTML.includes('File from cache:')) {
                                    // This indicates we have a cached file that needs to be preserved
                                    // We'll rely on the server-side session data for actual file handling
                                    // but mark this input as having a valid cached file for validation
                                    input.removeAttribute('data-required');
                                }
                            }
                        }
                    });
                    
                } catch (e) {
                    console.error('Error restoring cached files:', e);
                }
            }
            
            function clearFormData() {
                sessionStorage.removeItem('profiling_form_data');
                sessionStorage.removeItem('profiling_file_info');
                sessionStorage.removeItem('profiling_last_activity');
            sessionStorage.removeItem('profiling_current_part');
            }
            
            // Clear form data on successful submission
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('success') === '1' || urlParams.get('fresh') === '1') {
                clearFormData();

            // --- Step/tab persistence helpers ---
            function getVisiblePartId() {
                // Prefer explicit style display check, fallback to computed style
                const parts = Array.from(document.querySelectorAll('[id^="part"]'));
                for (const el of parts) {
                    const styleDisp = el.getAttribute('style') || '';
                    if (!styleDisp.includes('display:none')) return el.id;
                }
                // Fallback: find first visible by computed style
                return parts.find(p => window.getComputedStyle(p).display !== 'none')?.id || null;
            }
            function partIdToStep(id) {
                // part0..part5 => steps 1..6
                const m = /part(\d+)/.exec(id || '');
                if (!m) return null;
                const idx = parseInt(m[1], 10);
                return isNaN(idx) ? null : (idx + 1);
            }
            function setVisiblePart(id) {
                if (!id) return;
                const parts = document.querySelectorAll('[id^="part"]');
                parts.forEach(p => { p.style.display = (p.id === id) ? 'block' : 'none'; });
                const step = partIdToStep(id);
                if (step) updateProgressStepper(step);
            }
            function saveCurrentPart() {
                const id = getVisiblePartId();
                if (id) sessionStorage.setItem('profiling_current_part', id);
            }
            function restoreCurrentPart() {
                // Skip restoring if success modal flow is active
                const params = new URLSearchParams(window.location.search);
                if (params.get('success') === '1') return;
                const saved = sessionStorage.getItem('profiling_current_part');
                if (saved && document.getElementById(saved)) {
                    const savedStep = partIdToStep(saved) || 1;
                    // Don't restore beyond server-allowed step
                    if (savedStep <= (window.serverProfilingStep || 1)) {
                        setVisiblePart(saved);
                    } else {
                        saveCurrentPart();
                    }
                } else {
                    // Ensure the initially visible part is persisted for future refreshes
                    saveCurrentPart();
                }
            }
                // After loading any saved inputs, restore the last visible step/tab
                restoreCurrentPart();
            }

            // Handle back buttons separately to bypass validation
            function initializeBackButtonHandling() {
                const backButtons = document.querySelectorAll('button[formaction*="backTo"]');
                
                backButtons.forEach(button => {
                    button.addEventListener('click', function(e) {
                        // Clear any existing validation errors when going back
                        const form = button.closest('form');
                        if (form) {
                            const errorFields = form.querySelectorAll('.field-error');
                            const errorMessages = form.querySelectorAll('.validation-error');
                            
                            errorFields.forEach(field => {
                                field.classList.remove('field-error');
                            });
                            
                            errorMessages.forEach(message => {
                                message.remove();
                            });
                        }
                        
                        // Allow the form to submit normally for back navigation
                        // The form action will handle the back navigation
                    });
                });
            }

            // Instant validation for required fields
            function initializeInstantValidation() {
                const requiredFields = document.querySelectorAll('input[data-required="true"], select[data-required="true"]');
                
                // Add specific handling for email field (not using data-required)
                const emailField = document.getElementById('email');
                if (emailField) {
                    emailField.addEventListener('blur', function() {
                        if (window.validateEmailField) {
                            window.validateEmailField();
                        }
                    });
                    emailField.addEventListener('input', function() {
                        // Clear error on input if field has value
                        if (this.value.trim() !== '' && window.validateEmailField) {
                            window.validateEmailField();
                        }
                    });
                }
                
                requiredFields.forEach(field => {
                    if (field.type === 'radio') {
                        // For radio buttons, add change event to all buttons in the group
                        const radioGroup = document.querySelectorAll(`input[name="${field.name}"]`);
                        radioGroup.forEach(radio => {
                            radio.addEventListener('change', function() {
                                clearFieldError(field); // Clear error when any radio in group is selected
                            });
                        });
                    } else {
                        // For other field types
                        field.addEventListener('blur', function() {
                            validateSingleField(field, true);
                        });
                        
                        field.addEventListener('input', function() {
                            if (field.value.trim() !== '') {
                                clearFieldError(field);
                            }
                        });
                        
                        field.addEventListener('change', function() {
                            if (field.value.trim() !== '') {
                                clearFieldError(field);
                            }
                        });

                        field.addEventListener('keydown', function(e) {
                            if (e.key === 'Tab' && field.value.trim() === '') {
                                setTimeout(() => validateSingleField(field, true), 10);
                            }
                        });
                    }
                });

                // Form submission validation - exclude back buttons
                const forms = document.querySelectorAll('form');
                forms.forEach(form => {
                    form.addEventListener('submit', function(e) {
                        // Check if the clicked button is a back button
                        const clickedButton = document.activeElement;
                        const isBackButton = clickedButton && (
                            clickedButton.getAttribute('formaction') && 
                            clickedButton.getAttribute('formaction').includes('backTo')
                        );
                        
                        // Only validate if it's not a back button
                        if (!isBackButton && !validateStepForm(form)) {
                            e.preventDefault();
                            const firstInvalidField = form.querySelector('.field-error') || form.querySelector('.validation-error');
                            if (firstInvalidField) {
                                firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                firstInvalidField.focus();
                            }
                        }
                    });
                });
            }

            // Step-aware validation runner that builds on validateForm()
            function validateStepForm(form) {
                let isValid = validateForm(form);

                // Helper to show custom message without breaking instant required
                function ensureValid(field, predicate, message) {
                    if (!field) return true;
                    if (!predicate(field.value)) {
                        showFieldError(field, message);
                        return false;
                    }
                    return true;
                }

                // Compute current form/step by id
                const formId = form.getAttribute('id') || '';

                // Step 1 specific validations (Personal Information)
                if (formId === 'step1Form') {
                    // Names: allow letters, spaces, hyphens, apostrophes; min 2 letters
                    const nameRegex = /^[A-Za-zÀ-ÖØ-öø-ÿ'\-\s]{2,}$/;
                    const lastName = form.querySelector('input[name="last_name"]');
                    const firstName = form.querySelector('input[name="first_name"]');
                    if (lastName && lastName.value.trim() !== '') {
                        isValid = ensureValid(lastName, v => nameRegex.test(v.trim()), 'Please enter a valid last name.') && isValid;
                    }
                    if (firstName && firstName.value.trim() !== '') {
                        isValid = ensureValid(firstName, v => nameRegex.test(v.trim()), 'Please enter a valid first name.') && isValid;
                    }

                    // Zone/Purok: positive integer
                    const zone = form.querySelector('input[name="zone_purok"]');
                    if (zone && zone.value.trim() !== '') {
                        isValid = ensureValid(zone, v => { const n = Number(v); return Number.isInteger(n) && n > 0; }, 'Please enter a valid zone/purok number.') && isValid;
                    }

                    // Birthdate: require complete date and age 15-30 inclusive
                    const monthSel = form.querySelector('select[name="birth_month"]');
                    const daySel = form.querySelector('select[name="birth_day"]');
                    const yearSel = form.querySelector('select[name="birth_year"]');
                    if (monthSel && daySel && yearSel) {
                        const m = monthSel.value, d = daySel.value, y = yearSel.value;
                        if (m && d && y) {
                            const birthdate = new Date(parseInt(y, 10), parseInt(m, 10) - 1, parseInt(d, 10));
                            const today = new Date();
                            let age = today.getFullYear() - birthdate.getFullYear();
                            const mdiff = today.getMonth() - birthdate.getMonth();
                            if (mdiff < 0 || (mdiff === 0 && today.getDate() < birthdate.getDate())) age--;
                            if (!(age >= 15 && age <= 30)) {
                                // Anchor error to year select for proper placement
                                showFieldError(yearSel, 'You must be between 15 and 30 years old.');
                                isValid = false;
                            }
                        }
                    }
                }

                // Step 3 specific validations (Account Information)
                if (formId === 'step3Form') {
                    // Username: 4-30 chars, letters, numbers, underscores, dots
                    const username = form.querySelector('input[name="username"]');
                    if (username && username.value.trim() !== '') {
                        const unameOk = /^[A-Za-z0-9_\.]{4,30}$/.test(username.value.trim());
                        if (!unameOk) {
                            showFieldError(username, 'Username must be 4-30 characters (letters, numbers, _ or .)');
                            isValid = false;
                        }
                    }

                    // Password: min 8 and complexity; Confirm matches
                    const password = form.querySelector('input[name="password"]');
                    const confirm = form.querySelector('input[name="confirm_password"]');
                    if (password && password.value.trim() !== '') {
                        const pass = password.value;
                        const complex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/.test(pass);
                        if (!complex) {
                            showFieldError(password, 'Password must be 8+ chars with upper, lower, number, and special character.');
                            isValid = false;
                        }
                    }
                    if (password && confirm && confirm.value.trim() !== '') {
                        if (password.value !== confirm.value) {
                            showFieldError(confirm, 'Passwords do not match.');
                            isValid = false;
                        }
                    }
                }

                return isValid;
            }

            function validateSingleField(field, showMessage = false) {
                const value = field.value.trim();
                const isRequired = field.getAttribute('data-required') === 'true';
                
                // Use enhanced validation for email and phone fields
                if (field.type === 'email' || field.name === 'email' || field.id === 'email') {
                    if (window.validateEmailField) {
                        return window.validateEmailField();
                    }
                }
                
                if (field.name === 'phone_number' || field.id === 'phone_number' || field.name === 'phone') {
                    if (window.validatePhoneField) {
                        return window.validatePhoneField();
                    }
                }
                
                if (isRequired) {
                    // Handle radio buttons differently
                    if (field.type === 'radio') {
                        const radioGroup = document.querySelectorAll(`input[name="${field.name}"]`);
                        const isChecked = Array.from(radioGroup).some(radio => radio.checked);
                        
                        if (!isChecked) {
                            if (showMessage) {
                                showFieldError(field, 'Please select an option');
                            }
                            return false;
                        } else {
                            clearFieldError(field);
                            return true;
                        }
                    } else {
                        // Handle other field types
                        if (value === '') {
                            if (showMessage) {
                                showFieldError(field, 'This field is required');
                            }
                            return false;
                        } else {
                            clearFieldError(field);
                            return true;
                        }
                    }
                } else {
                    clearFieldError(field);
                    return true;
                }
            }

            function validateForm(form) {
                const requiredFields = form.querySelectorAll('input[data-required="true"], select[data-required="true"]');
                let isValid = true;
                const processedRadioGroups = new Set();
                
                // First, validate email and phone fields with enhanced validation
                const emailField = form.querySelector('input[type="email"], input[name="email"], #email');
                const phoneField = form.querySelector('input[name="phone_number"], #phone_number, input[name="phone"]');
                
                if (emailField && window.validateEmailField) {
                    if (!window.validateEmailField()) {
                        isValid = false;
                    }
                }
                
                if (phoneField && window.validatePhoneField) {
                    if (!window.validatePhoneField()) {
                        isValid = false;
                    }
                }
                
                requiredFields.forEach(field => {
                    // Skip email and phone fields as they're handled above with enhanced validation
                    if ((field.type === 'email' || field.name === 'email' || field.id === 'email') ||
                        (field.name === 'phone_number' || field.id === 'phone_number' || field.name === 'phone')) {
                        return; // Skip, already validated above
                    }
                    
                    // For radio buttons, only validate once per group
                    if (field.type === 'radio') {
                        if (!processedRadioGroups.has(field.name)) {
                            processedRadioGroups.add(field.name);
                            if (!validateSingleField(field, true)) {
                                isValid = false;
                            }
                        }
                    } else {
                        // For other field types, validate normally
                        if (!validateSingleField(field, true)) {
                            isValid = false;
                        }
                    }
                });
                
                // Additional file validation for forms with file uploads
                const fileInputs = form.querySelectorAll('.file-upload-input');
                fileInputs.forEach(input => {
                    const container = input.closest('.file-upload-container');
                    const hasExistingFile = container.getAttribute('data-has-existing-file') === 'true';
                    const hasNewFile = input.files && input.files.length > 0;
                    const hasError = container.querySelector('.file-upload-button').classList.contains('error');
                    
                    // Check if there's a cached file
                    let hasCachedFile = false;
                    const savedFileInfo = sessionStorage.getItem('profiling_file_info');
                    if (savedFileInfo) {
                        try {
                            const fileInfo = JSON.parse(savedFileInfo);
                            hasCachedFile = fileInfo[input.name] ? true : false;
                        } catch (e) {
                            console.error('Error checking cached file:', e);
                        }
                    }
                    
                    // Check if file is required
                    const isFileRequired = input.getAttribute('data-required') === 'true';
                    
                    if (isFileRequired && !hasExistingFile && !hasNewFile && !hasCachedFile) {
                        showFieldError(input, 'Please upload a file');
                        isValid = false;
                    } else if (hasNewFile) {
                        // Validate new file before submission
                        const file = input.files[0];
                        const validation = validateFile(file, input.name);
                        
                        if (!validation.valid) {
                            const container = input.closest('.file-upload-container');
                            const button = container.querySelector('.file-upload-button');
                            showFileError(input, button, validation.message);
                            isValid = false;
                        }
                    } else if (hasError) {
                        // Existing file has error - need to re-upload
                        showFieldError(input, 'Please upload a valid file');
                        isValid = false;
                    }
                });
                
                return isValid;
            }

            function validateFile(file, inputName) {
                const MAX_SIZE = 5 * 1024 * 1024; // 5MB
                let allowedTypes = [];
                
                if (inputName === 'profile_picture') {
                    allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
                } else {
                    allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'application/pdf'];
                }

                if (!allowedTypes.includes(file.type)) {
                    const allowedExtensions = allowedTypes.map(type => 
                        type.split('/')[1].toUpperCase()).join(', ');
                    return {
                        valid: false,
                        message: `Invalid file type. Allowed formats: ${allowedExtensions}`
                    };
                }

                if (file.size > MAX_SIZE) {
                    const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                    return {
                        valid: false,
                        message: `File is too large (${fileSizeMB} MB). Maximum allowed size is 5MB.`
                    };
                }

                return { valid: true };
            }

            function showFieldError(field, message) {
                field.classList.add('field-error', 'shake');
                
                // For radio buttons, find the container div and add red border
                let errorContainer;
                if (field.type === 'radio') {
                    errorContainer = field.closest('.bg-gray-50');
                    if (errorContainer) {
                        errorContainer.classList.remove('border-gray-200');
                        errorContainer.classList.add('border-red-500');
                    }
                } else if (field.type === 'file') {
                    // For file inputs, add error to the file control (not the outer card)
                    const uploadWrapper = field.closest('.file-upload-container');
                    const fileButton = uploadWrapper ? uploadWrapper.querySelector('.file-upload-button') : null;
                    if (fileButton) {
                        fileButton.classList.add('error');
                        fileButton.classList.remove('has-file');
                    }
                    // Use the immediate section container to place the error message
                    errorContainer = uploadWrapper ? uploadWrapper.parentNode : field.parentNode;
                    // If a server-rendered error block already exists, don't add another client message
                    const existingServerError = errorContainer.querySelector('.validation-message.error');
                    if (existingServerError) {
                        return; // Avoid duplicating the message
                    }
                } else {
                    errorContainer = field.parentNode;
                }
                
                const existingError = errorContainer.querySelector('.validation-error');
                if (existingError) {
                    existingError.remove();
                }
                
                const errorElement = document.createElement('p');
                errorElement.className = 'validation-error text-red-500 text-xs mt-1';
                errorElement.textContent = message;
                errorContainer.appendChild(errorElement);
                
                setTimeout(() => {
                    field.classList.remove('shake');
                }, 500);
            }

            function clearFieldError(field) {
                field.classList.remove('field-error');
                
                // For radio buttons and file inputs, find the container div and remove red border
                let errorContainer;
                if (field.type === 'radio') {
                    errorContainer = field.closest('.bg-gray-50');
                    if (errorContainer) {
                        errorContainer.classList.remove('border-red-500');
                        errorContainer.classList.add('border-gray-200');
                    }
                } else if (field.type === 'file') {
                    // For file inputs, remove error from the file control only
                    const uploadWrapper = field.closest('.file-upload-container');
                    const fileButton = uploadWrapper ? uploadWrapper.querySelector('.file-upload-button') : null;
                    if (fileButton) {
                        fileButton.classList.remove('error');
                    }
                    errorContainer = uploadWrapper ? uploadWrapper.parentNode : field.parentNode;
                } else {
                    errorContainer = field.parentNode;
                }
                
                const errorElement = errorContainer.querySelector('.validation-error');
                if (errorElement) {
                    errorElement.remove();
                }
            }

            // Enhanced file upload functionality with persistence
        function initializeFileUploads() {
                const fileInputs = document.querySelectorAll('.file-upload-input');
                
                fileInputs.forEach(input => {
                    // Remember whether this field was originally marked as required in markup
                    input.dataset.requiredOriginally = input.hasAttribute('data-required') ? 'true' : 'false';
                    const container = input.closest('.file-upload-container');
                    const button = container.querySelector('.file-upload-button');
                    const textElement = container.querySelector(`#${input.id.replace('_input', '_text')}`);
            const hasExistingFile = container.getAttribute('data-has-existing-file') === 'true';
            // Detect if a cached file was restored by loadFormData()
            const hasCachedFile = button.classList.contains('has-file') && textElement && textElement.innerHTML.includes('File from cache:');
                    
                    // Initialize file upload state based on existing files
            initializeFileState(input, button, textElement, (hasExistingFile || hasCachedFile));
                    
                    // File input change event
                    input.addEventListener('change', function(e) {
                        handleFileSelection(e.target, button, textElement);
                    });

                    // Drag and drop functionality
                    button.addEventListener('dragover', function(e) {
                        e.preventDefault();
                        button.classList.add('dragover');
                    });

                    button.addEventListener('dragleave', function(e) {
                        e.preventDefault();
                        button.classList.remove('dragover');
                    });

                    button.addEventListener('drop', function(e) {
                        e.preventDefault();
                        button.classList.remove('dragover');
                        
                        const files = e.dataTransfer.files;
                        if (files.length > 0) {
                            input.files = files;
                            handleFileSelection(input, button, textElement);
                        }
                    });

                    // Click to upload
                    button.addEventListener('click', function() {
                        input.click();
                    });
                });

                function initializeFileState(input, button, textElement, hasExistingOrCachedFile) {
                    // Check if there's an existing file
                    const hasError = button.classList.contains('error');
                    
                    if (hasExistingOrCachedFile && !hasError) {
                        // File exists and is valid - show as uploaded
                        button.classList.add('has-file');
                        button.classList.remove('error');
                        
                        // Mark as not required since file exists
                        input.removeAttribute('data-required');
                        clearFieldError(input);
                    } else if (hasError) {
                        // File has validation error - require new upload
                        button.classList.add('error');
                        button.classList.remove('has-file');
                        if (input.dataset.requiredOriginally === 'true') {
                            input.setAttribute('data-required', 'true');
                        } else {
                            input.removeAttribute('data-required');
                        }
                        // Do not duplicate messages: server-rendered error, if present, is enough
                    } else {
                        // No file exists - require upload
                        if (input.dataset.requiredOriginally === 'true') {
                            input.setAttribute('data-required', 'true');
                        } else {
                            input.removeAttribute('data-required');
                        }
                        resetFileUpload(input, button, textElement);
                    }
                }

                function handleFileSelection(input, button, textElement) {
                    const file = input.files[0];
                    
                    if (!file) {
                        // If no file selected, check if there's cached file info
                        const savedFileInfo = sessionStorage.getItem('profiling_file_info');
                        if (savedFileInfo) {
                            try {
                                const fileInfo = JSON.parse(savedFileInfo);
                                if (fileInfo[input.name]) {
                                    // Keep the cached file state
                                    return;
                                }
                            } catch (e) {
                                console.error('Error parsing cached file info:', e);
                            }
                        }
                        
                        // If no file selected and no cache, reset to required state
                        const container = input.closest('.file-upload-container');
                        const hasExistingFile = container.getAttribute('data-has-existing-file') === 'true';
                        
                        if (!hasExistingFile) {
                            resetFileUpload(input, button, textElement);
                            input.setAttribute('data-required', 'true');
                        }
                        return;
                    }

                    // Validate file
                    const validation = validateFile(file, input.name);
                    
                    if (!validation.valid) {
                        showFileError(input, button, validation.message);
                        input.value = '';
                        if (input.dataset.requiredOriginally === 'true') {
                            input.setAttribute('data-required', 'true');
                        } else {
                            input.removeAttribute('data-required');
                        }
                        // Remove from cache if validation fails
                        removeFileFromCache(input.name);
                        return;
                    }

                    // File is valid
                    button.classList.remove('error');
                    button.classList.add('has-file');
                    
                    const fileName = file.name.length > 30 ? 
                        file.name.substring(0, 30) + '...' : file.name;
                    
                    textElement.innerHTML = `
                        <strong>New file selected:</strong><br>
                        <span class="text-green-600">${fileName}</span><br>
                        <span class="text-xs text-blue-500">Ready to upload</span>
                    `;

                    // Clear any existing validation errors and mark as not required
                    clearFieldError(input);
                    input.removeAttribute('data-required');
                    
                    // Clear server-side validation errors
                    const errorDiv = input.closest('.file-upload-container').parentNode.querySelector('.validation-message.error');
                    if (errorDiv) {
                        errorDiv.remove();
                    }
                    
                    // Save file info to cache
                    saveFormData();
                }

                function validateFile(file, inputName) {
                    const MAX_SIZE = 5 * 1024 * 1024; // 5MB
                    let allowedTypes = [];
                    
                    if (inputName === 'profile_picture') {
                        allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
                    } else {
                        allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'application/pdf'];
                    }

                    if (!allowedTypes.includes(file.type)) {
                        const allowedExtensions = allowedTypes.map(type => 
                            type.split('/')[1].toUpperCase()).join(', ');
                        return {
                            valid: false,
                            message: `Invalid file type. Allowed formats: ${allowedExtensions}`
                        };
                    }

                    if (file.size > MAX_SIZE) {
                        const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                        return {
                            valid: false,
                            message: `File size (${fileSizeMB} MB) exceeds 5MB limit`
                        };
                    }

                    return { valid: true };
                }

                function showFileError(input, button, message) {
                    button.classList.add('error');
                    button.classList.remove('has-file');
                    
                    const container = input.closest('.file-upload-container').parentNode;
                    let errorDiv = container.querySelector('.validation-message.error');
                    
                    if (!errorDiv) {
                        errorDiv = document.createElement('div');
                        errorDiv.className = 'validation-message error show';
                        errorDiv.style.cssText = 'color: #ef4444; font-size: 0.75rem; margin-top: 0.25rem; padding: 0.5rem; background-color: #fef2f2; border: 1px solid #fecaca; border-radius: 0.375rem;';
                        container.appendChild(errorDiv);
                    }
                    
                    errorDiv.textContent = message;
                    errorDiv.classList.add('show');
                    // Avoid adding a second, client-generated message
                }

                function resetFileUpload(input, button, textElement) {
                    button.classList.remove('has-file', 'error');
                    
                    if (input.name === 'profile_picture') {
                        textElement.innerHTML = `
                            Click to upload your 1x1 photo<br>
                            <span class="text-xs text-gray-500">JPG, PNG, WEBP up to 5MB</span>
                        `;
                    } else {
                        textElement.innerHTML = `
                            Click to upload or drag and drop<br>
                            <span class="text-xs text-gray-500">JPG, PNG, GIF, WEBP, PDF up to 5MB</span>
                        `;
                    }
                }

                function removeFileFromCache(inputName) {
                    const savedFileInfo = sessionStorage.getItem('profiling_file_info');
                    if (savedFileInfo) {
                        try {
                            const fileInfo = JSON.parse(savedFileInfo);
                            delete fileInfo[inputName];
                            sessionStorage.setItem('profiling_file_info', JSON.stringify(fileInfo));
                        } catch (e) {
                            console.error('Error removing file from cache:', e);
                        }
                    }
                }
            }

            // Age group calculation
            function initializeAgeGroupCalculation() {
                const birthdateInput = document.querySelector('input[name="birthdate"]');
                const ageGroupSelect = document.querySelector('#age_group_select');
                
                // Birthday dropdown elements
                const monthSelect = document.querySelector('select[name="birth_month"]');
                const daySelect = document.querySelector('select[name="birth_day"]');
                const yearSelect = document.querySelector('select[name="birth_year"]');
                const hiddenBirthdateInput = document.querySelector('#birthdate_hidden');
                
                if (!ageGroupSelect) return;

                function calculateAge(birthdate) {
                    const today = new Date();
                    const birthDate = new Date(birthdate);
                    let age = today.getFullYear() - birthDate.getFullYear();
                    const monthDiff = today.getMonth() - birthDate.getMonth();
                    
                    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                        age--;
                    }
                    return age;
                }
                
                function updateBirthdateFromDropdowns() {
                    if (monthSelect && daySelect && yearSelect && hiddenBirthdateInput) {
                        const month = monthSelect.value;
                        const day = daySelect.value;
                        const year = yearSelect.value;
                        
                        if (month && day && year) {
                            const birthdate = `${year}-${month}-${day}`;
                            hiddenBirthdateInput.value = birthdate;
                            updateAgeGroup(birthdate);
                        } else {
                            hiddenBirthdateInput.value = '';
                            ageGroupSelect.value = '';
                        }
                    }
                }
                
                function updateAgeGroup(birthdate = null) {
                    let birthdateValue = birthdate;
                    
                    // Try to get birthdate from different sources
                    if (!birthdateValue) {
                        if (birthdateInput) {
                            birthdateValue = birthdateInput.value;
                        } else if (hiddenBirthdateInput) {
                            birthdateValue = hiddenBirthdateInput.value;
                        }
                    }
                    
                    if (!birthdateValue) {
                        ageGroupSelect.value = '';
                        return;
                    }
                    
                    const age = calculateAge(birthdateValue);
                    if (age >= 15 && age <= 17) {
                        ageGroupSelect.value = '1';
                    } else if (age >= 18 && age <= 24) {
                        ageGroupSelect.value = '2';
                    } else if (age >= 25 && age <= 30) {
                        ageGroupSelect.value = '3';
                    } else {
                        ageGroupSelect.value = '';
                    }
                    
                    // Update hidden input for age_group
                    const hiddenAgeGroup = ageGroupSelect.parentNode.querySelector('input[type="hidden"][name="age_group"]');
                    if (hiddenAgeGroup) {
                        hiddenAgeGroup.value = ageGroupSelect.value;
                    }
                }
                
                // Add event listeners for dropdown changes
                if (monthSelect && daySelect && yearSelect) {
                    monthSelect.addEventListener('change', updateBirthdateFromDropdowns);
                    daySelect.addEventListener('change', updateBirthdateFromDropdowns);
                    yearSelect.addEventListener('change', updateBirthdateFromDropdowns);
                    
                    // Initialize on page load
                    updateBirthdateFromDropdowns();
                }
                
                // Fallback for original date input (if exists)
                if (birthdateInput) {
                    birthdateInput.addEventListener('change', () => updateAgeGroup());
                    if (birthdateInput.value) {
                        updateAgeGroup();
                    }
                }
                
                // Make updateAgeGroup available globally for form persistence
                window.updateAgeGroupCalculation = updateAgeGroup;
            }

            // Dependent field logic
            function initializeDependentLogic() {
                // SK Voter and National Voter logic
                const skVoterRadios = document.querySelectorAll('input[name="sk_voter"]');
                const nationalVoterRadios = document.querySelectorAll('input[name="national_voter"]');
                const nationalVoterContainer = document.getElementById('national_voter_container');
                const skElectionRadios = document.querySelectorAll('input[name="sk_election"]');
                const skElectionContainer = document.getElementById('sk_election_container');
                
                function updateNationalVoter() {
                    const skVoter = document.querySelector('input[name="sk_voter"]:checked');
                    const form = document.querySelector('form#step2Form');
                    let hiddenInput = form?.querySelector('input[name="national_voter"][type="hidden"]');
                    let hiddenSkElectionInput = form?.querySelector('input[name="sk_election"][type="hidden"]');
                    
                    if (skVoter && skVoter.value === '0') {
                        nationalVoterRadios.forEach(radio => {
                            if (radio.value === '0') {
                                radio.checked = true;
                            }
                            radio.disabled = true;
                        });
                        if (nationalVoterContainer) nationalVoterContainer.classList.add('container-disabled');
                        // Also disable SK election question when not an SK voter
                        skElectionRadios.forEach(radio => {
                            if (radio.value === '0') {
                                radio.checked = true;
                            }
                            radio.disabled = true;
                        });
                        if (skElectionContainer) skElectionContainer.classList.add('container-disabled');
                        if (!hiddenSkElectionInput && form) {
                            hiddenSkElectionInput = document.createElement('input');
                            hiddenSkElectionInput.type = 'hidden';
                            hiddenSkElectionInput.name = 'sk_election';
                            hiddenSkElectionInput.value = '0';
                            form.appendChild(hiddenSkElectionInput);
                        }
                        
                        if (!hiddenInput && form) {
                            hiddenInput = document.createElement('input');
                            hiddenInput.type = 'hidden';
                            hiddenInput.name = 'national_voter';
                            hiddenInput.value = '0';
                            form.appendChild(hiddenInput);
                        }
                    } else {
                        nationalVoterRadios.forEach(radio => {
                            radio.disabled = false;
                        });
                        if (nationalVoterContainer) nationalVoterContainer.classList.remove('container-disabled');
                        // Re-enable SK election when SK voter is yes/unknown
                        skElectionRadios.forEach(radio => {
                            radio.disabled = false;
                        });
                        if (skElectionContainer) skElectionContainer.classList.remove('container-disabled');
                        if (hiddenSkElectionInput) {
                            hiddenSkElectionInput.remove();
                        }
                        if (hiddenInput) {
                            hiddenInput.remove();
                        }
                    }
                }
                
                skVoterRadios.forEach(radio => {
                    radio.addEventListener('change', updateNationalVoter);
                });
                updateNationalVoter();

                // KK Assembly logic
        const kkAssemblyRadios = document.querySelectorAll('input[name="kk_assembly"]');
        const howManyTimesRadios = document.querySelectorAll('input[name="how_many_times"]');
        const noWhyRadios = document.querySelectorAll('input[name="no_why"]');
        const howManyTimesContainer = document.getElementById('how_many_times_container');
        const noWhyContainer = document.getElementById('no_why_container');
                
                function updateKKAssemblyRelated() {
                    const kkAssembly = document.querySelector('input[name="kk_assembly"]:checked');
                    
                    if (kkAssembly && kkAssembly.value === '0') {
                        howManyTimesRadios.forEach(radio => {
                            radio.checked = false;
                            radio.disabled = true;
                        });
            if (howManyTimesContainer) howManyTimesContainer.classList.add('container-disabled');
                        noWhyRadios.forEach(radio => {
                            radio.disabled = false;
                        });
            if (noWhyContainer) noWhyContainer.classList.remove('container-disabled');
                    } else if (kkAssembly && kkAssembly.value === '1') {
                        howManyTimesRadios.forEach(radio => {
                            radio.disabled = false;
                        });
            if (howManyTimesContainer) howManyTimesContainer.classList.remove('container-disabled');
                        noWhyRadios.forEach(radio => {
                            radio.checked = false;
                            radio.disabled = true;
                        });
            if (noWhyContainer) noWhyContainer.classList.add('container-disabled');
                    } else {
                        howManyTimesRadios.forEach(radio => {
                            radio.disabled = false;
                        });
            if (howManyTimesContainer) howManyTimesContainer.classList.remove('container-disabled');
                        noWhyRadios.forEach(radio => {
                            radio.disabled = false;
                        });
            if (noWhyContainer) noWhyContainer.classList.remove('container-disabled');
                    }
                }
                
                kkAssemblyRadios.forEach(radio => {
                    radio.addEventListener('change', updateKKAssemblyRelated);
                });
                updateKKAssemblyRelated();
            }

            // Modal handlers
            function initializeModalHandlers() {
                
                // Sample profile picture modal
                const showSamplePicBtn = document.getElementById('showSamplePicBtn');
                const samplePicModal = document.getElementById('samplePicModal');
                const closeSamplePicModal = document.getElementById('closeSamplePicModal');
                const samplePicModalBg = document.getElementById('samplePicModalBg');
                
                if (showSamplePicBtn && samplePicModal) {
                    showSamplePicBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        samplePicModal.classList.remove('hidden');
                    });
                    
                    // Close button event
                    if (closeSamplePicModal) {
                        closeSamplePicModal.addEventListener('click', (e) => {
                            e.preventDefault();
                            e.stopPropagation();
                            samplePicModal.classList.add('hidden');
                        });
                    }
                    
                    // Background click event
                    if (samplePicModalBg) {
                        samplePicModalBg.addEventListener('click', (e) => {
                            e.preventDefault();
                            e.stopPropagation();
                            samplePicModal.classList.add('hidden');
                            samplePicModal.classList.add('hidden');
                        });
                    }
                    
                    // ESC key support for sample modal
                    document.addEventListener('keydown', (e) => {
                        if (e.key === 'Escape' && !samplePicModal.classList.contains('hidden')) {
                            samplePicModal.classList.add('hidden');
                        }
                    });
                }

                // Sample birth certificate modal
                const showSampleBirthCertBtn = document.getElementById('showSampleBirthCertBtn');
                const sampleBirthCertModal = document.getElementById('sampleBirthCertModal');
                const closeSampleBirthCertModal = document.getElementById('closeSampleBirthCertModal');
                const sampleBirthCertModalBg = document.getElementById('sampleBirthCertModalBg');
                
                if (showSampleBirthCertBtn && sampleBirthCertModal) {
                    showSampleBirthCertBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        sampleBirthCertModal.classList.remove('hidden');
                    });
                    
                    if (closeSampleBirthCertModal) {
                        closeSampleBirthCertModal.addEventListener('click', (e) => {
                            e.preventDefault();
                            e.stopPropagation();
                            sampleBirthCertModal.classList.add('hidden');
                        });
                    }
                    
                    if (sampleBirthCertModalBg) {
                        sampleBirthCertModalBg.addEventListener('click', (e) => {
                            e.preventDefault();
                            e.stopPropagation();
                            sampleBirthCertModal.classList.add('hidden');
                        });
                    }
                    
                    document.addEventListener('keydown', (e) => {
                        if (e.key === 'Escape' && !sampleBirthCertModal.classList.contains('hidden')) {
                            sampleBirthCertModal.classList.add('hidden');
                        }
                    });
                }

                // Sample valid ID modal
                const showSampleValidIdBtn = document.getElementById('showSampleValidIdBtn');
                const sampleValidIdModal = document.getElementById('sampleValidIdModal');
                const closeSampleValidIdModal = document.getElementById('closeSampleValidIdModal');
                const sampleValidIdModalBg = document.getElementById('sampleValidIdModalBg');
                
                if (showSampleValidIdBtn && sampleValidIdModal) {
                    showSampleValidIdBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        sampleValidIdModal.classList.remove('hidden');
                    });
                    
                    if (closeSampleValidIdModal) {
                        closeSampleValidIdModal.addEventListener('click', (e) => {
                            e.preventDefault();
                            e.stopPropagation();
                            sampleValidIdModal.classList.add('hidden');
                        });
                    }
                    
                    if (sampleValidIdModalBg) {
                        sampleValidIdModalBg.addEventListener('click', (e) => {
                            e.preventDefault();
                            e.stopPropagation();
                            sampleValidIdModal.classList.add('hidden');
                        });
                    }
                    
                    document.addEventListener('keydown', (e) => {
                        if (e.key === 'Escape' && !sampleValidIdModal.classList.contains('hidden')) {
                            sampleValidIdModal.classList.add('hidden');
                        }
                    });
                }

                // File error modal
                const modal = document.getElementById('file-size-modal');
                const modalClose = document.getElementById('file-size-modal-close');
                const modalBg = document.getElementById('file-size-modal-bg');
                
                // Close button event
                if (modalClose) {
                    modalClose.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        modal?.classList.add('hidden');
                    });
                }
                
                // Background click event
                if (modalBg) {
                    modalBg.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        modal?.classList.add('hidden');
                    });
                }
                
                // ESC key support for file modal
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
                        modal.classList.add('hidden');
                    }
                });

                // Document preview modal
                const documentModal = document.getElementById('document-preview-modal');
                const documentModalClose = document.getElementById('document-preview-close');
                const documentModalBg = document.getElementById('document-preview-modal-bg');
                
                // Close button event
                if (documentModalClose) {
                    documentModalClose.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        window.closeDocumentPreview();
                    });
                }
                
                // Background click event
                if (documentModalBg) {
                    documentModalBg.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        window.closeDocumentPreview();
                    });
                } else {
                    console.error('Document modal background not found');
                }
                
                // ESC key support for document modal
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && documentModal && !documentModal.classList.contains('hidden')) {
                        window.closeDocumentPreview();
                    }
                });
            }
        });

        // Show document error state - Define at global scope
        function showDocumentError() {
            const imageElement = document.getElementById('document-image');
            const pdfElement = document.getElementById('document-pdf');
            const errorElement = document.getElementById('document-error');
            
            if (imageElement) imageElement.classList.add('hidden');
            if (pdfElement) pdfElement.classList.add('hidden');
            if (errorElement) errorElement.classList.remove('hidden');
        }

        // Document preview functionality - Define at global scope
        window.previewDocument = function(url, filename, title) {
            const modal = document.getElementById('document-preview-modal');
            const titleElement = document.getElementById('document-preview-title');
            const downloadBtn = document.getElementById('document-download-btn');
            const loadingElement = document.getElementById('document-loading');
            const viewerElement = document.getElementById('document-viewer');
            const imageElement = document.getElementById('document-image');
            const pdfElement = document.getElementById('document-pdf');
            const pdfViewer = document.getElementById('pdf-viewer');
            const pdfFallbackLink = document.getElementById('pdf-fallback-link');
            const errorElement = document.getElementById('document-error');
            const errorDownloadBtn = document.getElementById('document-error-download');

            // Check if modal exists
            if (!modal) {
                alert('Preview modal not found. Please contact support.');
                return;
            }

            // Set title and download link
            if (titleElement) titleElement.textContent = title;
            if (downloadBtn) downloadBtn.onclick = () => downloadDocument(url, filename);
            if (errorDownloadBtn) errorDownloadBtn.onclick = () => downloadDocument(url, filename);

            // Show modal and loading state
            modal.classList.remove('hidden');
            
            if (loadingElement) loadingElement.classList.remove('hidden');
            if (viewerElement) viewerElement.classList.add('hidden');
            
            // Hide all preview elements
            if (imageElement) imageElement.classList.add('hidden');
            if (pdfElement) pdfElement.classList.add('hidden');
            if (errorElement) errorElement.classList.add('hidden');

            // Determine file type
            const fileExtension = filename.toLowerCase().split('.').pop();
            const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExtension);
            const isPdf = fileExtension === 'pdf';

            console.log('File type detection:', { fileExtension, isImage, isPdf });

            setTimeout(() => {
                console.log('Starting content load...');
                
                if (loadingElement) loadingElement.classList.add('hidden');
                if (viewerElement) viewerElement.classList.remove('hidden');

                if (isImage) {
                    console.log('Loading image:', url);
                    // Preview image
                    if (imageElement) {
                        imageElement.src = url;
                        imageElement.onload = () => {
                            console.log('Image loaded successfully');
                            imageElement.classList.remove('hidden');
                        };
                        imageElement.onerror = () => {
                            console.error('Failed to load image:', url);
                            showDocumentError();
                        };
                    }
                } else if (isPdf) {
                    console.log('Loading PDF:', url);
                    // Preview PDF
                    if (pdfViewer && pdfFallbackLink && pdfElement) {
                        pdfViewer.src = url + '#toolbar=1&navpanes=1&scrollbar=1';
                        pdfFallbackLink.href = url;
                        pdfElement.classList.remove('hidden');
                    }
                } else {
                    console.log('Unsupported file type, showing error');
                    // Unsupported file type
                    showDocumentError();
                }
            }, 500);
        };

        // Document preview functionality (alias for compatibility)
        function previewDocument(url, filename, title) {
            return window.previewDocument(url, filename, title);
        }

        // Make closeDocumentPreview globally accessible
        window.closeDocumentPreview = function() {
            console.log('Closing document preview');
            const modal = document.getElementById('document-preview-modal');
            const imageElement = document.getElementById('document-image');
            const pdfViewer = document.getElementById('pdf-viewer');
            const loadingElement = document.getElementById('document-loading');
            const viewerElement = document.getElementById('document-viewer');
            const errorElement = document.getElementById('document-error');
            
            if (modal) {
                modal.classList.add('hidden');
                console.log('Modal hidden');
            } else {
                console.error('Modal not found when trying to close');
            }
            
            // Clear content to stop loading and reset modal state
            if (imageElement) {
                imageElement.src = '';
                imageElement.classList.add('hidden');
            }
            
            if (pdfViewer) {
                pdfViewer.src = '';
            }
            
            // Reset all states
            if (loadingElement) loadingElement.classList.add('hidden');
            if (viewerElement) viewerElement.classList.add('hidden');
            if (errorElement) errorElement.classList.add('hidden');
            
            // Reset document elements
            const pdfElement = document.getElementById('document-pdf');
            if (pdfElement) pdfElement.classList.add('hidden');
        };

        // Document preview functionality (alias for compatibility)
        function previewDocument(url, filename, title) {
            return window.previewDocument(url, filename, title);
        }

        // Close document preview functionality (alias for compatibility)
        function closeDocumentPreview() {
            return window.closeDocumentPreview();
        }

        function downloadDocument(url, filename) {
            console.log('Downloading document:', { url, filename });
            // Create a temporary link element and trigger download
            const link = document.createElement('a');
            link.href = url;
            link.download = filename;
            link.target = '_blank';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Handle form submission success
        function showSuccessState() {
            // Clear all form data and session storage completely
            clearAllFormData();
            
            // Reset the form to step 1
            resetToStep1();
            
            // Show success modal popup instead of step 6
            showSuccessModal();
        }

        // Clear all form data completely
        function clearAllFormData() {
            // Clear sessionStorage (we use this for profiling persistence)
            sessionStorage.removeItem('profiling_form_data');
            sessionStorage.removeItem('profiling_file_info');
            sessionStorage.removeItem('profiling_last_activity');
            
            // As a safety, also clear any legacy localStorage keys if present
            try {
                localStorage.removeItem('profiling_form_data');
                localStorage.removeItem('profiling_file_info');
            } catch (e) { /* ignore */ }
            
            // Reset all form fields
            const allInputs = document.querySelectorAll('input, select, textarea');
            allInputs.forEach(input => {
                if (input.type === 'checkbox' || input.type === 'radio') {
                    input.checked = false;
                } else if (input.type === 'file') {
                    input.value = '';
                    // Reset file upload displays
                    const container = input.closest('.file-upload-container');
                    if (container) {
                        const button = container.querySelector('.file-upload-button');
                        const text = container.querySelector('.file-upload-text');
                        if (button) {
                            button.classList.remove('has-file', 'error');
                        }
                        if (text) {
                            text.innerHTML = `
                                <svg class="h-6 w-6 text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-700">Upload File</span>
                                <span class="text-xs text-gray-500">Click or drag file here</span>
                            `;
                        }
                    }
                } else {
                    input.value = '';
                }
                // Clear any error states
                input.classList.remove('field-error', 'shake');
            });
            
            // Clear all error messages
            document.querySelectorAll('.validation-error').forEach(error => error.remove());
            
            // Reset any dependent fields
            const dependentFields = document.querySelectorAll('[data-dependent]');
            dependentFields.forEach(field => {
                field.style.display = 'none';
            });
        }

        // Initialize 30-minute timeout and on-close cleanup with pre-timeout warning
        function initializeProfilingTimeouts() {
            const TIMEOUT_MS = 30 * 60 * 1000; // 30 minutes
            const PREWARN_MS = 60 * 1000; // Show warning when <= 1 minute remains
            const resetEndpoint = '<?= base_url('profiling/reset') ?>';

            // Mark initial activity
            if (!sessionStorage.getItem('profiling_last_activity')) {
                sessionStorage.setItem('profiling_last_activity', Date.now().toString());
            }

            // Activity events to refresh timer
            ['click','input','change','keydown','mousemove','touchstart'].forEach(evt => {
                document.addEventListener(evt, (e) => {
                    // When pre-warning is visible, block all activity from extending session
                    // except clicking the Continue button inside the pre-warning modal.
                    if (window.isPrewarnVisible) {
                        const target = e.target;
                        const isContinue = target && target.closest && target.closest('#prewarn-continue');
                        if (!isContinue) return;
                    }
                    // Otherwise, record activity normally
                    sessionStorage.setItem('profiling_last_activity', Date.now().toString());
                }, { passive: true });
            });

            // Utility to format remaining ms to mm:ss
            function fmt(ms) {
                const total = Math.max(0, Math.floor(ms / 1000));
                const m = Math.floor(total / 60).toString().padStart(2,'0');
                const s = (total % 60).toString().padStart(2,'0');
                return `${m}:${s}`;
            }

            // Create/top-left warning panel (modal-like)
            // Create overlay to block background interactions
            let overlayEl = document.getElementById('profiling-prewarn-overlay');
            if (!overlayEl) {
                overlayEl = document.createElement('div');
                overlayEl.id = 'profiling-prewarn-overlay';
                overlayEl.className = 'fixed inset-0 bg-black/60 z-40 hidden';
                document.body.appendChild(overlayEl);
            }

            let warnEl = document.getElementById('profiling-prewarn');
            if (!warnEl) {
                warnEl = document.createElement('div');
                warnEl.id = 'profiling-prewarn';
                warnEl.className = 'fixed top-4 left-1/2 -translate-x-1/2 z-50 bg-white/95 border border-orange-300 shadow-lg rounded-lg px-4 py-3 w-[28rem] max-w-[92vw] hidden';
                warnEl.innerHTML = `
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-semibold text-orange-800">Session will expire soon</div>
                            <div class="text-xs text-orange-700 mt-0.5">Time remaining: <span id="prewarn-remaining" class="font-bold">--:--</span></div>
                        </div>
                        <div class="flex gap-2">
                            <button id="prewarn-continue" class="px-3 py-1.5 text-xs font-semibold bg-orange-600 text-white rounded-md hover:bg-orange-700">Continue</button>
                            <button id="prewarn-dismiss" class="px-3 py-1.5 text-xs font-medium border border-orange-300 text-orange-800 rounded-md hover:bg-orange-50">Dismiss</button>
                        </div>
                    </div>`;
                document.body.appendChild(warnEl);
            }

            const remainSpan = () => document.getElementById('prewarn-remaining');
            const btnContinue = () => document.getElementById('prewarn-continue');
            const btnDismiss = () => document.getElementById('prewarn-dismiss');

            function showPrewarn() {
                if (overlayEl) overlayEl.classList.remove('hidden');
                if (warnEl) { warnEl.classList.remove('hidden'); window.isPrewarnVisible = true; }
            }
            function hidePrewarn() {
                if (overlayEl) overlayEl.classList.add('hidden');
                if (warnEl) { warnEl.classList.add('hidden'); window.isPrewarnVisible = false; }
            }

            // Wire buttons
            if (btnContinue()) {
                btnContinue().onclick = () => {
                    sessionStorage.setItem('profiling_last_activity', Date.now().toString());
                    hidePrewarn();
                };
            }
            if (btnDismiss()) {
                btnDismiss().onclick = () => {
                    // Destroy profiling session and redirect to fresh profiling start
                    if (window.profilingTimeoutTimer) clearInterval(window.profilingTimeoutTimer);
                    hidePrewarn();
                    clearAllFormData();
                    try { fetch('<?= base_url('profiling/reset') ?>', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, keepalive: true }); } catch (e) {}
                    window.location.href = '<?= base_url('profiling') ?>?fresh=1';
                };
            }

            // 1-second ticker to update remaining, UI, and enforce timeout
            const TICK_MS = 1000;
            if (window.profilingTimeoutTimer) clearInterval(window.profilingTimeoutTimer);
            window.profilingTimeoutTimer = setInterval(() => {
                const last = parseInt(sessionStorage.getItem('profiling_last_activity') || '0', 10);
                const now = Date.now();
                const elapsed = last ? (now - last) : 0;
                const remaining = TIMEOUT_MS - elapsed;

                // Show/hide pre-warning panel
                if (remaining <= PREWARN_MS && remaining > 0) {
                    if (remainSpan()) remainSpan().textContent = fmt(remaining);
                    showPrewarn();
                } else {
                    hidePrewarn();
                }

                // Timeout reached
                if (remaining <= 0) {
                    clearInterval(window.profilingTimeoutTimer);
                    hidePrewarn();
                    clearAllFormData();
                    try { fetch(resetEndpoint, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, keepalive: true }); } catch (e) {}
                    window.location.href = '<?= base_url('profiling') ?>?fresh=1';
                }
            }, TICK_MS);

            // Block random taps/clicks while warning is visible: only modal buttons work
            function blockBackgroundClicks(e) {
                if (!window.isPrewarnVisible) return;
                const target = e.target;
                const inModal = target && target.closest && target.closest('#profiling-prewarn');
                const isBtn = target && target.closest && (target.closest('#prewarn-continue') || target.closest('#prewarn-dismiss'));
                if (!inModal || !isBtn) {
                    e.stopPropagation();
                    e.preventDefault();
                }
            }
            ['click','mousedown','touchstart'].forEach(evt => {
                document.addEventListener(evt, blockBackgroundClicks, true);
            });

            // On browser/tab close or navigation away, clear data and inform server
            // On page unload: do NOT clear sessionStorage so refresh keeps data.
            // Rely on: (a) sessionStorage auto-clears when tab closes, (b) TIMEOUT/explicit reset buttons to clear server.
            window.addEventListener('beforeunload', function() {
                if (window.isProfilingFormSubmit) return;
                try { saveCurrentPart(); } catch (e) {}
            });
        }

        // Reset form to step 1
        function resetToStep1() {
            // Hide all step containers
            document.querySelectorAll('[id^="part"]').forEach(part => {
                part.style.display = 'none';
            });
            
            // Show step 1 (qualification)
            const step1Container = document.getElementById('part0');
            if (step1Container) {
                step1Container.style.display = 'block';
            }
            
            // Update progress stepper to step 1
            updateProgressStepper(1);
            
            // Scroll to top
            window.scrollTo(0, 0);
        }

        // Update progress stepper visual state
        function updateProgressStepper(currentStep) {
            // Reset all steps to inactive state
            const stepCircles = document.querySelectorAll('.step-circle');
            const progressLines = document.querySelectorAll('.progress-line');
            
            // Update step circles and lines based on current step
            for (let i = 1; i <= 5; i++) {
                const circle = document.querySelector(`[data-step="${i}"]`);
                const line = document.querySelector(`[data-line="${i}"]`);
                
                if (circle) {
                    if (i < currentStep) {
                        circle.className = 'w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-semibold';
                    } else if (i === currentStep) {
                        circle.className = 'w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-semibold animate-pulse';
                    } else {
                        circle.className = 'w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold';
                    }
                }
                
                if (line) {
                    if (i < currentStep) {
                        line.className = 'flex-1 h-1 bg-blue-600 transition-all duration-500 ease-in-out';
                    } else {
                        line.className = 'flex-1 h-1 bg-blue-100 transition-all duration-500 ease-in-out';
                    }
                }
            }
        }

        // Show success modal popup
        function showSuccessModal() {
            const modal = document.getElementById('successModal');
            const titleElement = document.getElementById('successTitle');
            const messageElement = document.getElementById('successMessage');
            
            if (!modal || !titleElement || !messageElement) {
                console.error('Success modal elements not found');
                return;
            }

            // Set content based on success type
            const urlParams = new URLSearchParams(window.location.search);
            const isReupload = window.successType === 'reupload';
            
            if (isReupload) {
                titleElement.textContent = 'Profile Updated Successfully!';
                messageElement.textContent = 'Your profile has been successfully updated and resubmitted for review.';
            } else {
                titleElement.textContent = 'Registration Successful!';
                messageElement.textContent = 'Your account has been successfully created and is pending approval.';
            }

            // Add blur effect to background content
            const mainContainer = document.querySelector('.max-w-6xl');
            if (mainContainer) {
                mainContainer.style.filter = 'blur(5px)';
                mainContainer.style.transition = 'filter 0.3s ease-out';
            }

            // Show modal with animation
            modal.classList.remove('hidden');
            
            // Start smooth countdown
            startModalAutoRedirect();
            
            // Clear URL parameters to prevent issues with back button
            window.history.replaceState({}, document.title, window.location.pathname);
            
            // Prevent body scroll when modal is open
            document.body.style.overflow = 'hidden';
        }

        // Auto-redirect functionality for modal with smooth animations
        function startModalAutoRedirect() {
            let totalTime = 5; // 5 seconds total
            let intervalTime = 50; // Update every 50ms for ultra-smooth animation
            let totalIntervals = (totalTime * 1000) / intervalTime; // 100 intervals total
            let currentInterval = 0;
            let lastDisplayedTime = totalTime;
            
            const countdownElement = document.getElementById('modalCountdown');
            const progressBar = document.getElementById('modalProgressBar');
            
            if (!countdownElement || !progressBar) {
                // Fallback - redirect immediately if elements not found
                setTimeout(() => redirectToLogin(), 1000);
                return;
            }

            // Initial state
            countdownElement.textContent = totalTime;
            progressBar.style.width = '0%';

            const timer = setInterval(() => {
                currentInterval++;
                
                // Calculate smooth progress (0% to 100%)
                const progress = (currentInterval / totalIntervals) * 100;
                progressBar.style.width = `${Math.min(progress, 100)}%`;
                
                // Calculate remaining time more accurately
                const timeRemaining = totalTime - (currentInterval * intervalTime / 1000);
                const displayTime = Math.max(0, Math.ceil(timeRemaining));
                
                // Update countdown display only when the displayed second changes
                if (displayTime !== lastDisplayedTime) {
                    lastDisplayedTime = displayTime;
                    countdownElement.textContent = displayTime;
                    
                    // Add a subtle scale animation when number changes
                    countdownElement.classList.add('countdown-smooth-scale');
                    setTimeout(() => {
                        countdownElement.classList.remove('countdown-smooth-scale');
                    }, 200);
                }
                
                // Check if time is completely up
                if (currentInterval >= totalIntervals || timeRemaining <= 0) {
                    clearInterval(timer);
                    progressBar.style.width = '100%';
                    countdownElement.textContent = '0';
                    
                    // Small delay before redirect for visual feedback
                    setTimeout(() => {
                        redirectToLogin();
                    }, 200);
                }
            }, intervalTime);
            
            // Store timer reference to clear it if user manually redirects
            window.modalTimer = timer;
        }

        // Start new registration (reset everything)
        function startNewRegistration() {
            // Clear any running timers
            if (window.modalTimer) {
                clearInterval(window.modalTimer);
            }
            if (window.mainPageTimer) {
                clearInterval(window.mainPageTimer);
            }
            
            // Remove blur effect and restore body scroll
            removeModalEffects();
            
            // Hide modal
            const modal = document.getElementById('successModal');
            if (modal) {
                modal.classList.add('hidden');
            }
            
            // Clear all data and reset to step 1
            clearAllFormData();
            resetToStep1();
            
            // Clear URL to ensure fresh start
            window.history.replaceState({}, document.title, window.location.pathname);
        }

        // Redirect to login function
        function redirectToLogin() {
            // Clear any running timers
            if (window.modalTimer) {
                clearInterval(window.modalTimer);
            }
            if (window.mainPageTimer) {
                clearInterval(window.mainPageTimer);
            }
            
            // Remove blur effect and restore body scroll
            removeModalEffects();
            
            // Clear all data before redirecting
            clearAllFormData();
            
            // Redirect to login
            window.location.href = '<?= base_url('login') ?>';
        }

        // Helper function to remove modal effects
        function removeModalEffects() {
            // Remove blur effect from background
            const mainContainer = document.querySelector('.max-w-6xl');
            if (mainContainer) {
                mainContainer.style.filter = 'none';
            }
            
            // Restore body scroll
            document.body.style.overflow = 'auto';
        }

        // Main page countdown functionality
        function startMainPageAutoRedirect() {
            let totalTime = 5; // 5 seconds total
            let intervalTime = 50; // Update every 50ms for ultra-smooth animation
            let totalIntervals = (totalTime * 1000) / intervalTime; // 100 intervals total
            let currentInterval = 0;
            let lastDisplayedTime = totalTime;
            
            const countdownElement = document.getElementById('countdown');
            const progressBar = document.getElementById('progressBar');
            
            if (!countdownElement || !progressBar) {
                // Fallback - redirect immediately if elements not found
                setTimeout(() => redirectToLogin(), 1000);
                return;
            }

            // Initial state
            countdownElement.textContent = totalTime;
            progressBar.style.width = '0%';

            const timer = setInterval(() => {
                currentInterval++;
                
                // Calculate smooth progress (0% to 100%)
                const progress = (currentInterval / totalIntervals) * 100;
                progressBar.style.width = `${Math.min(progress, 100)}%`;
                
                // Calculate remaining time more accurately
                const timeRemaining = totalTime - (currentInterval * intervalTime / 1000);
                const displayTime = Math.max(0, Math.ceil(timeRemaining));
                
                // Update countdown display only when the displayed second changes
                if (displayTime !== lastDisplayedTime) {
                    lastDisplayedTime = displayTime;
                    countdownElement.textContent = displayTime;
                    
                    // Add a subtle scale animation when number changes
                    countdownElement.classList.add('countdown-smooth-scale');
                    setTimeout(() => {
                        countdownElement.classList.remove('countdown-smooth-scale');
                    }, 200);
                }
                
                // Check if time is completely up
                if (currentInterval >= totalIntervals || timeRemaining <= 0) {
                    clearInterval(timer);
                    progressBar.style.width = '100%';
                    countdownElement.textContent = '0';
                    
                    // Small delay before redirect for visual feedback
                    setTimeout(() => {
                        redirectToLogin();
                    }, 200);
                }
            }, intervalTime);
            
            // Store timer reference to clear it if user manually redirects
            window.mainPageTimer = timer;
        }

        // Initialize countdown when page loads and step 6 is visible
        function initializeCountdowns() {
            // Check if we're on step 6 (success page)
            const successContainer = document.getElementById('part5');
            if (successContainer && successContainer.style.display !== 'none') {
                startMainPageAutoRedirect();
            }
        }

        // Enhanced Email and Phone Number Validation (matching account-settings pattern)
        function initializePhoneValidation() {
            const phoneInput = document.getElementById('phone_number');
            const emailInput = document.getElementById('email');
            
            // Helper functions for uniform validation
            function getOrMakeErrorEl(input, id) {
                // Check for existing error element first
                const existing = document.getElementById(id);
                if (existing) return existing;
                
                // For phone field, check if there's the original phone_error element
                if (id === 'phone-error-inline' && input.name === 'phone_number') {
                    const phoneError = document.getElementById('phone_error');
                    if (phoneError) {
                        phoneError.id = 'phone-error-inline'; // Update ID for consistency
                        // Match small inline error styling
                        phoneError.className = 'validation-error text-red-500 text-xs mt-1 hidden';
                        return phoneError;
                    }
                }
                
                // For email field, place error after any existing server validation errors
                if (id === 'email-error-inline' && input.name === 'email') {
                    // Look for existing server validation error
                    const parentDiv = input.closest('.space-y-1, .space-y-2');
                    if (parentDiv) {
                        const existingError = parentDiv.querySelector('.error-message');
                        if (existingError) {
                            // Place after existing error
                            const el = document.createElement('p');
                            el.id = id;
                            el.className = 'validation-error text-red-500 text-xs mt-1 hidden';
                            existingError.insertAdjacentElement('afterend', el);
                            return el;
                        }
                    }
                }
                
                // Create new error element with proper styling
                const el = document.createElement('p');
                el.id = id;
                el.className = 'validation-error text-red-500 text-xs mt-1 hidden';
                input.insertAdjacentElement('afterend', el);
                return el;
            }
            
            function showError(input, el, msg) {
                input.classList.add('border-red-500');
                input.classList.remove('border-green-500', 'border-gray-300', 'border-slate-200');
                input.setAttribute('aria-invalid', 'true');
                if (el) { 
                    el.textContent = msg || ''; 
                    el.classList.remove('hidden');
                    el.classList.add('show'); // Add show class for consistency
                }
            }
            
            function clearError(input, el) {
                input.classList.remove('border-red-500');
                input.classList.add('border-slate-200');
                input.removeAttribute('aria-invalid');
                if (el) { 
                    el.textContent = ''; 
                    el.classList.add('hidden');
                    el.classList.remove('show'); // Remove show class
                }
            }
            
            // Email validation
            if (emailInput) {
                const emailErr = getOrMakeErrorEl(emailInput, 'email-error-inline');
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/i;
                
                function validateEmail() {
                    const value = (emailInput.value || '').trim();
                    
                    // Clear any existing server validation errors for this field
                    const parentDiv = emailInput.closest('.space-y-1, .space-y-2');
                    if (parentDiv) {
                        const serverError = parentDiv.querySelector('.error-message');
                        if (serverError && serverError !== emailErr && serverError.textContent.includes('email')) {
                            serverError.style.display = 'none';
                        }
                    }
                    
                    if (!value) {
                        showError(emailInput, emailErr, 'Email is required.');
                        return false;
                    }
                    if (!emailRegex.test(value)) {
                        showError(emailInput, emailErr, 'Please enter a valid email address.');
                        return false;
                    }
                    clearError(emailInput, emailErr);
                    return true;
                }
                
                emailInput.addEventListener('input', validateEmail);
                emailInput.addEventListener('blur', validateEmail);
                
                // Make validation accessible globally for form submission
                window.validateEmailField = validateEmail;
            }
            
            // Phone number validation: Input holds only the 10 digits (UI shows +63 via prefix span)
            if (phoneInput) {
                const phoneErr = getOrMakeErrorEl(phoneInput, 'phone-error-inline');
                const step1Form = document.getElementById('step1Form');
                
                function toDigits(raw) {
                    let digits = String(raw || '').replace(/\D/g, '');
                    // If pasted with country code, strip it
                    if (digits.startsWith('63')) digits = digits.slice(2);
                    // If local 11-digit starting with 0, drop the 0
                    if (digits.startsWith('0')) digits = digits.slice(1);
                    return digits.slice(0, 10); // keep at most 10
                }

                function formatGroups(d) {
                    // Format as 3-3-4 groups: 912 345 6789 (progressively as typed)
                    if (!d) return '';
                    const a = d.slice(0, 3);
                    const b = d.slice(3, 6);
                    const c = d.slice(6, 10);
                    return [a, b, c].filter(Boolean).join(' ');
                }

                function normalizePhone(raw) {
                    const digits = toDigits(raw);
                    return formatGroups(digits);
                }

                function validatePhone() {
                    const formatted = normalizePhone(phoneInput.value || '');
                    if (formatted !== phoneInput.value) phoneInput.value = formatted;
                    const digits = toDigits(formatted);
                    if (!digits) {
                        showError(phoneInput, phoneErr, 'Phone number is required.');
                        return false;
                    }
                    if (digits.length !== 10) {
                        showError(phoneInput, phoneErr, 'Phone must be +63 followed by 10 digits.');
                        return false;
                    }
                    clearError(phoneInput, phoneErr);
                    return true;
                }

                // Normalize any prefilled value (e.g., from server or cache)
                if (phoneInput.value) {
                    phoneInput.value = normalizePhone(phoneInput.value);
                }

                phoneInput.addEventListener('input', function(e) {
                    const start = e.target.selectionStart || 0;
                    const before = e.target.value;
                    const formatted = normalizePhone(before);
                    if (formatted !== before) {
                        e.target.value = formatted;
                        // best-effort cursor reposition near end
                        const pos = Math.min(formatted.length, start + (formatted.length - before.length));
                        setTimeout(() => e.target.setSelectionRange(pos, pos), 0);
                    }
                    // Light validate while typing (no error until blur unless over length)
                    const digits = toDigits(formatted);
                    if (digits.length > 10) {
                        showError(phoneInput, phoneErr, 'Phone must be +63 followed by 10 digits.');
                    } else {
                        // Don't show error during typing unless cleared
                        if (digits.length) clearError(phoneInput, phoneErr);
                    }
                });

                phoneInput.addEventListener('blur', validatePhone);
                
                // Prevent Step 1 submit if invalid phone
                if (step1Form) {
                    step1Form.addEventListener('submit', function(ev) {
                        const phoneValid = validatePhone();
                        const emailValid = window.validateEmailField ? window.validateEmailField() : true;
                        
                        if (!phoneValid || !emailValid) {
                            ev.preventDefault();
                            ev.stopPropagation();
                        }
                    });
                }
                
                // Make validation accessible globally for form submission
                window.validatePhoneField = validatePhone;
            }
        }
        
        // Password Strength Validation
        function initializePasswordValidation() {
            const passwordInput = document.getElementById('password');
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
                });
            }
        }

        // Check if there's a success parameter in URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('success') === '1') {
            // Store success type globally for modal
            window.successType = '<?= isset($success_type) ? $success_type : "registration" ?>';
            showSuccessState();
        } else {
            // Initialize countdowns for main page if we're on step 6
            document.addEventListener('DOMContentLoaded', function() {
                initializeCountdowns();
                initializePasswordValidation();
            });
        }
    </script>
</body>
</html>
