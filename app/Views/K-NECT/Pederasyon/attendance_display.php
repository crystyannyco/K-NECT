<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Display - <?= esc($event['title']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
        }
        
        /* Enhanced Professional Card Styles */
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(226, 232, 240, 0.8);
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .card:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 35px rgba(0, 0, 0, 0.12);
        }
        
        /* Enhanced Header Styling */
        .header-gradient {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #60a5fa 100%);
            box-shadow: 0 4px 20px rgba(59, 130, 246, 0.22);
            padding-top: 10px; /* reduced vertical padding for compact header */
            padding-bottom: 10px;
        }
        
        .time-display {
            font-family: 'Inter', sans-serif;
            font-size: 1.6rem; /* slightly smaller to save vertical space */
            font-weight: 700;
            min-width: 10ch;
            white-space: nowrap;
            display: inline-block;
            text-align: center;
            letter-spacing: -0.02em;
        }
        
        .time-card {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border: 1px solid rgba(59, 130, 246, 0.12);
            box-shadow: 0 2px 10px rgba(59, 130, 246, 0.06);
            padding: 8px 12px; /* compact padding */
        }
        
        /* Enhanced Scanner Card */
        .scan-card {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            position: relative;
            /* Remove overflow: hidden to prevent animation clipping */
            border-radius: inherit;
        }
        
        /* shimmer element placed inside the .scan-clip wrapper so it's clipped cleanly */
        .scan-shimmer {
            position: absolute;
            /* extend a bit so the long highlight covers the card fully; wrapper will clip */
            top: -6px;
            left: -20%;
            right: -20%;
            bottom: -6px;
            background: linear-gradient(70deg,
                rgba(255,255,255,0) 0%,
                rgba(255,255,255,0.06) 35%,
                rgba(255,255,255,0.20) 50%,
                rgba(255,255,255,0.06) 65%,
                rgba(255,255,255,0) 100%
            );
            transform: translateX(-150%);
            animation: shimmer 5.5s linear infinite;
            z-index: 2; /* sits below card content (card children are z-index:3) */
            pointer-events: none;
            /* Smoothly fade left/right edges so no hard edge is visible */
            -webkit-mask-image: linear-gradient(to right, transparent 0%, black 12%, black 88%, transparent 100%);
            mask-image: linear-gradient(to right, transparent 0%, black 12%, black 88%, transparent 100%);
        }
        
        @keyframes shimmer {
            0% { transform: translateX(-160%); opacity: 0; }
            20% { opacity: 1; }
            80% { opacity: 1; }
            100% { transform: translateX(160%); opacity: 0; }
        }
        
        .scan-pulse {
            animation: scanPulse 2s infinite ease-in-out;
            transform-origin: center;
        }
        
        @keyframes scanPulse {
            0%, 100% { 
                opacity: 1; 
                transform: scale(1);
                box-shadow: 0 4px 25px rgba(0, 0, 0, 0.08);
            }
            50% { 
                opacity: 0.85; 
                transform: scale(1.02);
                box-shadow: 0 8px 40px rgba(59, 130, 246, 0.25);
            }
        }
        
        /* Ensure scanner card content stays above animation layers */
        .scan-card > * {
            position: relative;
            z-index: 3;
        }

        /* Wrapper that clips the shimmer to the card's rounded corners */
        .scan-clip {
            border-radius: 16px;
            overflow: hidden;
            display: block;
            position: relative; /* ensure absolute children are positioned inside this wrapper */
        }
        
        /* Scanner card hover effect that works with animations */
        .scan-card:hover {
            transform: translateY(-2px);
            transition: transform 0.2s ease;
        }
        
        /* Enhanced Profile Card */
        .profile-card {
            background: linear-gradient(135deg, #fefefe 0%, #f8fafc 100%);
            border: 2px solid rgba(148, 163, 184, 0.2);
        }
        
        .profile-avatar {
            background: linear-gradient(135deg, #64748b 0%, #475569 100%);
            box-shadow: 0 4px 15px rgba(100, 116, 139, 0.3);
            transition: all 0.3s ease;
        }
        
        .profile-avatar:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(100, 116, 139, 0.4);
        }
        
        /* Enhanced Table Styles */
        .attendance-table {
            border-radius: 12px;
            overflow: hidden;
            background: white;
        }
        
        .attendance-table thead {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        
        .attendance-table th {
            padding: 12px 16px;
            font-weight: 600;
            color: #374151;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }
        
        .attendance-table td {
            padding: 12px 16px;
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.2s ease;
        }
        
        .attendance-table tbody tr:hover {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            transform: translateX(2px);
        }
        
        /* Status Badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 8px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }
        
        .status-present {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            color: #166534;
            border: 1px solid #22c55e;
        }
        
        .status-late {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
            border: 1px solid #f59e0b;
        }
        
        /* Enhanced Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        /* Enhanced Form Inputs */
        .form-input {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            background: #fefefe;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            background: white;
        }
        
        /* Session Status Indicators */
        .session-indicator {
            animation: sessionPulse 2s infinite;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }
        
        @keyframes sessionPulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }
        
        .session-active {
            border-color: #10b981;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2);
        }
        
        .session-inactive {
            border-color: #e5e7eb;
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
            opacity: 0.7;
        }
        
        .session-past {
            border-color: #dc2626;
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            opacity: 0.8;
        }
        
        /* Loading and Animation States */
        .refresh-animation {
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .attendance-disabled {
            pointer-events: none;
            opacity: 0.5;
            filter: grayscale(50%);
        }
        
        /* Enhanced Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #cbd5e1 0%, #94a3b8 100%);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #94a3b8 0%, #64748b 100%);
        }
        
        /* Responsive Design Enhancements */
        @media (max-width: 1024px) {
            .header-gradient {
                padding: 1.5rem 1rem;
            }
            
            .time-card {
                padding: 1rem 1.5rem;
            }
            
            .card {
                padding: 1rem;
            }
            
            .scan-card {
                padding: 1.5rem;
            }
            
            /* Reduce padding wrapper on smaller screens to save space */
            .scan-card {
                margin: 2px;
            }
        }
        
        @media (max-width: 768px) {
            .toast-container {
                bottom: 16px;
                right: 16px;
                left: 16px;
                max-width: none;
            }
            
            .notification-toast {
                min-width: 280px;
                padding: 16px 20px;
            }
            
            .header-gradient {
                text-align: center;
            }
            
            main {
                padding: 1rem;
            }
            
            .grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
        }
        

        /* Enhanced Loading Screen - transparent with 3-dot animated logo */
        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            /* Dark solid overlay outside the container (no transparency/blur) */
            background: rgba(0, 0, 0, 0.78);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 1;
            visibility: visible;
            transition: opacity 0.4s ease, visibility 0.4s ease;
            pointer-events: auto; /* block interaction while visible */
        }

        .loading-screen.hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        /* SVG network loader (3 connected nodes) */
        .loader-panel {
            display: flex;
            align-items: center;
            gap: 18px;
            background: #ffffff; /* white container */
            border-radius: 14px;
            padding: 14px 18px;
            box-shadow: 0 10px 30px rgba(2,6,20,0.12);
            border: 1px solid rgba(16,24,40,0.06);
        }

        .loader-svg { width: 220px; height: 84px; display: block; }

        .loader-line {
            stroke: url(#lineGrad);
            stroke-width: 4.5;
            stroke-linecap: round;
            transition: stroke-width 120ms ease, stroke 120ms ease, opacity 120ms ease;
            opacity: 0.94;
        }

        .loader-circle {
            filter: drop-shadow(0 6px 12px rgba(2,6,23,0.06));
            transition: r 180ms ease, transform 300ms cubic-bezier(.2,.8,.2,1);
        }

        .loader-circle.red { fill: #ef4444; }
        .loader-circle.blue { fill: #2563eb; }
        .loader-circle.yellow { fill: #f59e0b; }

        /* subtle pulsing to keep the nodes feeling alive */
        @keyframes nodePulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.06); }
            100% { transform: scale(1); }
        }

    .loader-circle { animation: nodePulse 1600ms ease-in-out infinite; }

    .loader-text-wrap { color: #0b1220; }
    .loader-title { color: #0b1220; font-weight: 700; font-size: 15px; margin-bottom: 4px; }
    .loader-subtext { color: rgba(11,18,32,0.72); font-size: 13px; }

    .loader-progress { width: 180px; height: 6px; background: rgba(255,255,255,0.04); border-radius: 999px; margin-top: 8px; overflow: hidden; }
    .loader-progress-bar { height:100%; background: linear-gradient(90deg,#60a5fa,#93c5fd); width: 0%; border-radius: 999px; transition: width 320ms cubic-bezier(.2,.9,.2,1); }

    /* pulse lines when nodes approach each other */
    .loader-line.pulse { stroke-width: 6.5; opacity: 1; }

        .loading-text {
            color: white;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 12px;
            text-align: center;
        }

        .loading-subtext {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            text-align: center;
        }
        
        /* Simplified, professional toast styles */
        .toast-container {
            position: fixed;
            bottom: 18px;
            right: 18px;
            z-index: 1000;
            max-width: 360px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
        }

        .notification-toast {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            transition: transform 200ms ease, opacity 200ms ease;
            transform: translateY(8px) scale(0.995);
            opacity: 0;
            pointer-events: auto;
            background: #ffffff;
            color: #0f172a;
            border: 1px solid rgba(15,23,42,0.06);
            overflow: hidden;
            font-size: 0.95rem;
        }

        .notification-toast.show {
            transform: translateY(0) scale(1);
            opacity: 1;
        }

        .notification-toast.success { background: #ecfdf5; border-color: rgba(16,185,129,0.12); }
        .notification-toast.error   { background: #fff1f2; border-color: rgba(239,68,68,0.12); }
        .notification-toast.warning { background: #fffbeb; border-color: rgba(245,158,11,0.12); }
        .notification-toast.info    { background: #eff6ff; border-color: rgba(59,130,246,0.12); }

        .toast-icon { width: 18px; height: 18px; flex-shrink: 0; opacity: 0.95 }
        .toast-message { flex: 1; line-height: 1.2; }
        .toast-close { background: transparent; border: none; color: inherit; cursor: pointer; padding: 4px; opacity: 0.6 }
        .toast-close:hover { opacity: 1 }
        
        /* Permanent visibility for all attendance records */
        .permanent-visible {
            display: table-row !important;
        }
        
        /* Highlight active session records with a subtle border */
        .highlight-active-session {
            background-color: #f0f9ff !important;
            border-left: 3px solid #3b82f6;
            animation: subtle-pulse 3s ease-in-out infinite;
        }
        
        /* Slightly dim inactive session records but keep them visible */
        .dim-inactive-session {
            opacity: 0.7;
            background-color: #f9fafb;
        }
        
        
        /* Enhanced session filter styling */
        .session-filter-enhanced {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);
        }
        
        /* Override any display: none for attendance rows */
        tr[data-session] {
            display: table-row !important;
        }
        
        /* Smooth transitions for row highlighting */
        .permanent-visible {
            transition: all 0.3s ease;
        }
        
        /* Enhanced attendance status badges (no animation) */
        .highlight-active-session .status-badge {
            box-shadow: 0 1px 3px rgba(59, 130, 246, 0.3);
        }
        
        /* Session indicator in time column for better visibility */
        .session-indicator {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .session-indicator.morning {
            color: #0891b2;
        }
        
        .session-indicator.afternoon {
            color: #c2410c;
        }
        /* Responsive header title sizing: keep readable but fit container */
        .header-title {
            font-size: clamp(0.95rem, 1.8vw, 1.25rem); /* reduced max size for compact header */
            line-height: 1.05;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-weight: 700;
        }
        /* Responsive logos to prevent overlap with title */
        .header-logo {
            width: clamp(36px, 5.5vw, 64px); /* smaller logos */
            height: clamp(36px, 5.5vw, 64px);
            flex: 0 0 auto;
            object-fit: contain;
        }

        .pederasyon-logo {
            width: clamp(44px, 6.5vw, 80px);
            height: clamp(44px, 6.5vw, 80px);
            flex: 0 0 auto;
            object-fit: contain;
        }
        /* Allow title to wrap on very small screens to avoid overlap with logos */
        @media (max-width: 420px) {
            .header-title {
                white-space: normal;
                overflow: visible;
                text-overflow: clip;
            }
            /* Slightly reduce center card padding on tiny screens */
            .flex-shrink-0.flex {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
        }
        /* Highlight for recent taps (transient) */
        .recent-tap {
            background-color: #fff7ed !important; /* light warm highlight */
            border-left: 3px solid #f59e0b !important;
            transition: background-color 0.6s ease, border-left-color 0.6s ease, opacity 0.6s ease;
        }



        /* RFID Input Focus Lock */
        .rfid-locked {
            outline: 3px solid #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.3);
        }

        /* User Profile Auto-clear Animation */
        .profile-auto-clear {
            animation: profileClearCountdown 30s linear forwards;
            border-left: 4px solid transparent;
        }

        @keyframes profileClearCountdown {
            0% { border-left-color: #10b981; }
            80% { border-left-color: #f59e0b; }
            100% { border-left-color: #ef4444; }
        }
        
        /* Additional Professional Touches */
        .card-header {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 16px 16px 0 0;
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }

        /* Make the live attendance log container rounded and clip inner content */
        #attendanceLogCard {
            border-radius: 16px;
            overflow: hidden;
        }
        
        .icon-badge {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
        }
        
        /* Micro-animations for better UX */
        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-scale-in {
            animation: scaleIn 0.2s ease-out;
        }
        
        @keyframes scaleIn {
            from { transform: scale(0.95); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
    </style>
</head>
<body class="min-h-screen">
    
    <!-- Loading Screen -->
    <div id="loadingScreen" class="loading-screen" role="status" aria-live="polite" aria-label="Loading">
        <div class="loader-panel" aria-hidden="false">
            <svg class="loader-svg" viewBox="0 0 200 84" preserveAspectRatio="xMidYMid meet" aria-hidden="true">
                <defs>
                    <linearGradient id="lineGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#60a5fa" stop-opacity="0.9" />
                        <stop offset="100%" stop-color="#93c5fd" stop-opacity="0.6" />
                    </linearGradient>
                    <filter id="softGlow" x="-50%" y="-50%" width="200%" height="200%">
                        <feGaussianBlur stdDeviation="3" result="coloredBlur"/>
                        <feMerge>
                            <feMergeNode in="coloredBlur"/>
                            <feMergeNode in="SourceGraphic"/>
                        </feMerge>
                    </filter>
                </defs>
                <!-- connecting lines -->
                <line id="line12" class="loader-line" x1="38" y1="44" x2="100" y2="26" stroke="url(#lineGrad)"></line>
                <line id="line23" class="loader-line" x1="100" y1="26" x2="162" y2="44" stroke="url(#lineGrad)"></line>

                <!-- nodes -->
                <circle id="node1" class="loader-circle red" cx="38" cy="44" r="9" filter="url(#softGlow)"></circle>
                <circle id="node2" class="loader-circle blue" cx="100" cy="26" r="11" filter="url(#softGlow)"></circle>
                <circle id="node3" class="loader-circle yellow" cx="162" cy="44" r="9" filter="url(#softGlow)"></circle>
            </svg>
            <div class="loader-text-wrap">
                <div class="loader-title">Initializing Attendance System</div>
                <div class="loader-subtext"><span id="loadingStatus">Checking event date and time...</span></div>
                <div class="loader-progress">
                    <div id="loaderProgressBar" class="loader-progress-bar" style="width:0%"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Enhanced Professional Header Section -->
    <header class="header-gradient text-white shadow-lg">
    <div class="max-w-full mx-auto px-4 py-3">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-8">
                <!-- Left: City Logo and Event Info -->
                <div class="flex items-center gap-4 flex-1 min-w-0 w-full lg:w-auto">
                    <!-- Logo Section -->
                    <div class="flex-shrink-0 flex items-center gap-3">
                        <div class="relative">
                            <?php if (!empty($iriga_logo['file_path'])): ?>
                                <img src="<?= base_url($iriga_logo['file_path']) ?>" alt="<?= esc($iriga_logo['logo_name']) ?>" class="w-24 h-24 rounded-full ring-4 ring-white/20 shadow-lg">
                            <?php else: ?>
                                <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center ring-4 ring-white/20 shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="relative">
                            <?php if (!empty($pederasyon_logo['file_path'])): ?>
                                <img src="<?= base_url($pederasyon_logo['file_path']) ?>" alt="<?= esc($pederasyon_logo['logo_name']) ?>" class="w-24 h-24 rounded-full ring-4 ring-white/20 shadow-lg">
                            <?php else: ?>
                                <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center ring-4 ring-white/20 shadow-lg">
                                    <svg class="w-24 h-24 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Event Information -->
                    <div class="flex-1 min-w-0">
                        <div class="text-center lg:text-left">
                            <h2 class="text-sm font-bold uppercase text-white/90 tracking-wide">
                                City of Iriga
                            </h2>
                            <h2 class="text-lg font-bold uppercase mb-2 text-white tracking-wide">
                                Pederasyon ng mga Sangguniang Kabataan
                            </h2>
                            <h1 class="text-2xl lg:text-3xl font-bold text-white mb-3 leading-tight" title="<?= esc($event['title']) ?>"><?= esc($event['title']) ?></h1>
                            <div class="flex flex-wrap justify-center lg:justify-start items-center gap-x-6 gap-y-2 text-sm text-white/80">
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="font-semibold">Date:</span> 
                                    <?php 
                                        $startDate = date('Y-m-d', strtotime($event['start_datetime']));
                                        $endDate = date('Y-m-d', strtotime($event['end_datetime']));
                                        
                                        if ($startDate === $endDate) {
                                            echo date('F d, Y', strtotime($event['start_datetime']));
                                        } else {
                                            $startFormatted = date('F d', strtotime($event['start_datetime']));
                                            $endFormatted = date('F d, Y', strtotime($event['end_datetime']));
                                            
                                            if (date('Y-m', strtotime($event['start_datetime'])) === date('Y-m', strtotime($event['end_datetime']))) {
                                                echo $startFormatted . ' - ' . $endFormatted;
                                            } else {
                                                echo date('F d, Y', strtotime($event['start_datetime'])) . ' - ' . $endFormatted;
                                            }
                                        }
                                    ?>
                                </div>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="font-semibold">Time:</span> <?= date('g:i A', strtotime($event['start_datetime'])) ?> - <?= date('g:i A', strtotime($event['end_datetime'])) ?>
                                </div>
                                <?php if (!empty($event['location'])): ?>
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="font-semibold">Location:</span> <?= esc($event['location']) ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Center: Current Time Card -->
                <div class="flex-shrink-0">
                    <div class="time-card rounded-2xl px-4 py-4 text-center shadow-lg">
                        <div class="text-xs text-blue-700 font-semibold mb-2 uppercase tracking-wider">Current Time</div>
                        <div class="text-3xl font-bold text-blue-800 mb-2 time-display" id="currentTime"></div>
                        <div class="text-xs text-blue-600 flex items-center justify-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Manila Time (PHT)
                        </div>
                    </div>
                </div>
                
                <!-- Right: Session Status Card -->
                <div class="flex-shrink-0">
                    <div class="bg-white/20 backdrop-blur-sm rounded-2xl px-4 py-2 border border-white/30 shadow-lg">
                        <div class="text-center px-4">
                            <div class="flex items-center justify-center space-x-2 mb-2">
                                <div class="w-3 h-3 rounded-full session-indicator" id="sessionIndicator"></div>
                                <span class="text-sm font-semibold text-white" id="sessionStatus">Waiting</span>
                            </div>
                            <div class="text-lg font-bold text-white mb-3" id="currentSessionDisplay">No Active Session</div>
                            
                            <!-- Session stalk: persistent indicator -->
                            <div id="sessionStalk" class="text-xs px-3 py-1 rounded-full bg-white/20 text-white/80 hidden mb-2" title="Session status">
                                <!-- content updated by JS -->
                            </div>
                            
                            <div class="space-y-1">
                                <div class="text-xs text-white/80 flex items-center justify-between" id="amSessionTimes">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                        </svg>
                                        AM:
                                    </span>
                                    <span><?= $attendance_settings['start_attendance_am'] ? date('g:i A', strtotime($attendance_settings['start_attendance_am'])) : 'Not Set' ?> - <?= $attendance_settings['end_attendance_am'] ? date('g:i A', strtotime($attendance_settings['end_attendance_am'])) : 'Not Set' ?></span>
                                </div>
                                <div class="text-xs text-white/80 flex items-center justify-between" id="pmSessionTimes">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                                        </svg>
                                        PM:
                                    </span>
                                    <span><?= $attendance_settings['start_attendance_pm'] ? date('g:i A', strtotime($attendance_settings['start_attendance_pm'])) : 'Not Set' ?> - <?= $attendance_settings['end_attendance_pm'] ? date('g:i A', strtotime($attendance_settings['end_attendance_pm'])) : 'Not Set' ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content - Enhanced Professional Layout -->
    <main class="max-w-full mx-auto px-4 py-4">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- RFID Scanner Card - Enhanced -->
            <div class="lg:col-span-4">
                <!-- Add padding wrapper to accommodate animation overflow -->
                    <div class="scan-clip">
                        <div class="scan-shimmer" aria-hidden="true"></div>
                        <div class="card scan-card p-8 h-full text-center relative">
                    <!-- Background Pattern -->
                    <div class="absolute inset-0 opacity-10">
                        <svg class="w-full h-full" viewBox="0 0 100 100" fill="none">
                            <defs>
                                <pattern id="scanPattern" x="0" y="0" width="10" height="10" patternUnits="userSpaceOnUse">
                                    <circle cx="5" cy="5" r="1" fill="white"/>
                                </pattern>
                            </defs>
                            <rect width="100" height="100" fill="url(#scanPattern)"/>
                        </svg>
                    </div>
                    
                    <h3 class="text-2xl font-bold mb-4 text-white">RFID Scanner</h3>
                    <p class="text-sm mb-6 text-white/90 leading-relaxed" id="scanStatus">Ready to scan your RFID card...</p>
                    
                    <!-- Session Status -->
                    <div class="mb-8 bg-white/10 backdrop-blur-sm rounded-xl p-4">
                        <div class="text-sm font-semibold mb-2 text-white/90" id="currentSessionDisplay">No Active Session</div>
                        <div id="sessionStatus" class="inline-flex items-center px-3 py-2 rounded-full text-sm bg-white/20 text-white font-medium">
                            <div class="w-2 h-2 rounded-full bg-current mr-2 session-indicator"></div>
                            Waiting
                        </div>
                    </div>
                    
                    <!-- Hidden RFID Input -->
                    <input type="text" id="rfidInput" 
                           class="opacity-0 absolute -top-10 left-0 w-1 h-1" 
                           autofocus>
                    
                    <!-- Manual Input Section -->
                    <div class="bg-white rounded-2xl p-6 shadow-lg relative z-10">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            <label class="text-sm font-bold text-gray-800">Manual Entry</label>
                        </div>
                        <div class="space-y-4">
                            <input type="text" id="userIdInput" 
                                placeholder="Enter User ID (e.g., 123456 or 25-123456)" 
                                class="form-input w-full text-gray-900 placeholder-gray-500">
                            <button onclick="processManualAttendance()" 
                                    class="btn-primary w-full py-4">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Submit Attendance
                            </button>
                            <div id="manualEntryStatus" class="text-xs text-gray-500 text-center">
                                Ready for manual input
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            
            <!-- User Profile Card - Enhanced -->
            <div class="lg:col-span-3">
                <div class="card profile-card p-6 h-full">
                    <div class="flex items-center gap-3 mb-6">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <h3 class="text-lg font-bold text-gray-900">User Profile</h3>
                    </div>
                    
                    <div id="userInfoContent" class="text-center">
                        <!-- Default State -->
                        <div class="profile-avatar w-20 h-20 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">No User Selected</h4>
                        <p class="text-sm text-gray-500 mb-6">Scan RFID card or enter User ID to view profile</p>
                        
                        <!-- User Info Fields -->
                        <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                            <div class="flex items-center justify-between py-2 border-b border-gray-200 last:border-b-0">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">Type:</span>
                                </div>
                                <span class="text-sm text-gray-500 font-mono">---</span>
                            </div>
                            <div class="flex items-center justify-between py-2 border-b border-gray-200 last:border-b-0">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">Age:</span>
                                </div>
                                <span class="text-sm text-gray-500 font-mono">---</span>
                            </div>
                            <div class="flex items-center justify-between py-2 border-b border-gray-200 last:border-b-0">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">Gender:</span>
                                </div>
                                <span class="text-sm text-gray-500 font-mono">---</span>
                            </div>
                            <div class="flex items-center justify-between py-2 border-b border-gray-200 last:border-b-0">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">Zone:</span>
                                </div>
                                <span class="text-sm text-gray-500 font-mono">---</span>
                            </div>
                            <div class="flex items-center justify-between py-2">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">Status:</span>
                                </div>
                                <span class="text-sm text-gray-500 font-mono">---</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Log Card - Enhanced -->
            <div class="lg:col-span-5">
                <div id="attendanceLogCard" class="card h-full flex flex-col">
                    <!-- Enhanced Header -->
                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-blue-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-blue-100 rounded-lg">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Live Attendance Log</h3>
                                    <span id="sessionFilter" class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-700 hidden font-medium">
                                        Filtered by Active Session
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="text-right">
                                    <div class="text-xs text-gray-500 uppercase tracking-wide">Total Attendees</div>
                                    <div class="text-2xl font-bold text-blue-600" id="totalAttendees">0</div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button onclick="refreshData()" 
                                            class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-all" 
                                            title="Refresh attendance data">
                                        <svg id="refreshIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Enhanced Table -->
                    <div class="flex-1 relative">
                        <div class="absolute inset-0 overflow-hidden">
                            <div class="h-full overflow-y-auto overflow-x-hidden">
                                <table class="attendance-table min-w-full table-fixed">
                                    <thead class="sticky top-0 z-10">
                                        <tr>
                                            <th class="attendance-table th w-2/5">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                    Name
                                                </div>
                                            </th>
                                            <th class="attendance-table th w-1/5">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Status
                                                </div>
                                            </th>
                                            <th class="attendance-table th w-1/5">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Time
                                                </div>
                                            </th>
                                            <th class="attendance-table th w-1/5">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                    </svg>
                                                    Action
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="attendanceLogsList" class="bg-white">
                                        <!-- Dynamic content will be inserted here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Configuration and Global Variables
        const eventId = <?= $event['event_id'] ?>;
        const eventData = <?= json_encode($event ?? []) ?>;
        const attendanceSettings = <?= json_encode($attendance_settings ?? []) ?>;
        const existingAttendanceRecords = <?= json_encode($attendance_records ?? []) ?>;
        
        // Interval and timer management
        let refreshInterval;
        let currentActiveSession = null;
        let sessionStartWaitInterval = null;
        let realTimeUpdateInterval = null;
        let lastSettingsUpdate = null;
        let autoTimeoutTimers = {};
        let profileClearTimer = null;
        let loadingScreen = null;
        let sessionStatusInterval = null;
        let pendingNotificationInterval = null;

        // Configuration Constants
        const CONFIG = {
            REAL_TIME_UPDATE_INTERVAL: 5000,    // 5 seconds polling for server status
            SESSION_CHECK_INTERVAL: 30000,       // 30 seconds for session pending checks (less noisy)
            PROFILE_DISPLAY_DURATION: 30000,    // 30 seconds for profile visibility
            LOADING_MIN_DURATION: 2000,         // Minimum loading screen time
            TOAST_DURATION: 5000,               // Toast notification duration
            RFID_FOCUS_CHECK_INTERVAL: 500      // RFID input focus check interval
        };

        // Application State
        const AppState = {
            isInitialized: false,
            eventDateValid: false,
            lastUserProfileUpdate: null,
            rfidInputLocked: false,
            loadingStartTime: null
        };

        // Toast registry to prevent repeated toasts
        const ToastRegistry = new Map(); // key -> timestamp
        const TOAST_COOLDOWNS = {
            'session': 60000,   // 60s cooldown for session messages
            'user': 3000,       // 3s cooldown for user tap messages
            'default': 10000    // 10s default cooldown
        };
        const MAX_TOAST_STACK = 3;

        // ==================== UTILITY FUNCTIONS ====================
        
        // Time formatting helpers
        function formatTimeTo12Hour(timeString) {
            if (!timeString) return '';
            
            // Handle both HH:MM and HH:MM:SS formats
            const timeParts = timeString.split(':');
            let hours = parseInt(timeParts[0], 10);
            const minutes = timeParts[1];
            
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // 0 should be 12
            
            return `${hours}:${minutes} ${ampm}`;
        }

        function getCurrentTime12Hour() {
            const now = new Date();
            return now.toLocaleTimeString('en-US', { 
                hour: 'numeric', 
                minute: '2-digit',
                hour12: true 
            });
        }

        function getCurrentTime24HourForComparison() {
            const now = new Date();
            return now.toTimeString().slice(0, 5); // HH:MM format for backend comparison
        }

        function getCurrentDate() {
            const now = new Date();
            return now.toISOString().split('T')[0]; // YYYY-MM-DD format
        }

        function getCurrentDateTime() {
            return new Date();
        }

        // ==================== EVENT DATE/TIME VALIDATION ====================
        
        function validateEventDateTime() {
            updateLoadingStatus('Validating event date and time...');

            if (!eventData || !eventData.start_datetime) {
                console.error('Event data or start_datetime not available');
                AppState.eventDateValid = false;
                AppState.eventState = 'past';
                return AppState.eventState;
            }

            const currentDate = getCurrentDate();
            const eventStartDate = eventData.start_datetime.split(' ')[0]; // Extract date part
            const eventEndDate = eventData.end_datetime ? eventData.end_datetime.split(' ')[0] : eventStartDate;

            const currentDateObj = new Date(currentDate);
            const eventStartDateObj = new Date(eventStartDate);
            const eventEndDateObj = new Date(eventEndDate);

            // Determine state: active (today within range), upcoming (today before start), past (today after end)
            if (currentDateObj >= eventStartDateObj && currentDateObj <= eventEndDateObj) {
                AppState.eventState = 'active';
                AppState.eventDateValid = true;
            } else if (currentDateObj < eventStartDateObj) {
                AppState.eventState = 'upcoming';
                AppState.eventDateValid = false;
            } else {
                AppState.eventState = 'past';
                AppState.eventDateValid = false;
            }

            const dateRangeText = eventStartDate === eventEndDate ? 
                formatEventDate(eventStartDate) : 
                `${formatEventDate(eventStartDate)} - ${formatEventDate(eventEndDate)}`;

            if (AppState.eventState === 'upcoming') {
                showToast(`Event scheduled for ${dateRangeText}. Display is read-only until the event starts.`, 'info');
            } else if (AppState.eventState === 'past') {
                showToast(`This event occurred ${dateRangeText}. Attendance display is read-only for review.`, 'info');
            }

            // Return the current event state so callers can decide how to proceed
            return AppState.eventState;
        }

        function formatEventDate(dateString) {
            const options = { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                weekday: 'long'
            };
            return new Date(dateString).toLocaleDateString('en-US', options);
        }

        // ==================== LOADING SCREEN MANAGEMENT ====================
        
        function updateLoadingStatus(message) {
            const loadingStatus = document.getElementById('loadingStatus');
            if (loadingStatus) {
                loadingStatus.textContent = message;
            }
        }

        // Progress bar control (functional, smooth eased animation)
        const _progressState = { current: 0, target: 0, rafId: null };

        function _renderProgress() {
            const el = document.getElementById('loaderProgressBar');
            if (!el) return;
            el.style.width = _progressState.current.toFixed(2) + '%';
        }

        function _progressStep() {
            // ease towards target using exponential smoothing
            const cur = _progressState.current;
            const tgt = _progressState.target;
            const diff = tgt - cur;
            // if very close, snap and stop
            if (Math.abs(diff) < 0.04) {
                _progressState.current = tgt;
                _renderProgress();
                // stop if reached stable 100 or target
                if (_progressState.rafId) { cancelAnimationFrame(_progressState.rafId); _progressState.rafId = null; }
                return;
            }
            // smoothing factor controls speed (0.08..0.3)
            const alpha = 0.16;
            _progressState.current = cur + diff * alpha;
            _renderProgress();
            _progressState.rafId = requestAnimationFrame(_progressStep);
        }

        function setLoaderProgress(percent) {
            try {
                const p = Math.max(0, Math.min(100, Number(percent)));
                _progressState.target = p;
                // start RAF loop if not running
                if (!_progressState.rafId) {
                    _progressState.rafId = requestAnimationFrame(_progressStep);
                }
            } catch (e) {
                console.warn('setLoaderProgress error', e);
            }
        }

        // Loader animation control variables for SVG nodes
        let _loaderInterval = null;
        const _nodes = { n1: null, n2: null, n3: null, l12: null, l23: null };

        function _getNodePositions() {
            return {
                n1x: parseFloat(_nodes.n1.getAttribute('cx')),
                n1y: parseFloat(_nodes.n1.getAttribute('cy')),
                n2x: parseFloat(_nodes.n2.getAttribute('cx')),
                n2y: parseFloat(_nodes.n2.getAttribute('cy')),
                n3x: parseFloat(_nodes.n3.getAttribute('cx')),
                n3y: parseFloat(_nodes.n3.getAttribute('cy'))
            };
        }

        function startLoaderAnimation() {
            _nodes.n1 = document.getElementById('node1');
            _nodes.n2 = document.getElementById('node2');
            _nodes.n3 = document.getElementById('node3');
            _nodes.l12 = document.getElementById('line12');
            _nodes.l23 = document.getElementById('line23');

            if (!_nodes.n1 || !_nodes.n2 || !_nodes.n3) return;
            if (_loaderInterval) return; // already running (we'll store RAF id here)

            // aggressive RAF-driven motion
            // slower, smooth-moving state with collision avoidance
            const state = {
                nodes: {
                    n1: { x: 28, y: 32, tx: 28, ty: 32, speed: 0.04 },
                    n2: { x: 80, y: 20, tx: 80, ty: 20, speed: 0.05 },
                    n3: { x: 132, y: 32, tx: 132, ty: 32, speed: 0.04 }
                },
                lastTargetAt: Date.now(),
                nextSwapAt: Date.now() + 2200
            };

            function chooseNewTargets() {
                const now = Date.now();
                const bounds = { minX: 18, maxX: 142, minY: 12, maxY: 48 };
                const minSeparation = 36; // minimum separation between node targets

                // helper to ensure a target is far enough from other targets
                function ensureSeparation(tx, ty, others) {
                    for (const o of others) {
                        const dx = tx - o.tx;
                        const dy = ty - o.ty;
                        const d = Math.hypot(dx, dy);
                        if (d < minSeparation) {
                            // push away along vector
                            const angle = Math.atan2(dy || 0.0001, dx || 0.0001);
                            tx = o.tx + Math.cos(angle) * minSeparation;
                            ty = o.ty + Math.sin(angle) * minSeparation;
                        }
                    }
                    // clamp to bounds
                    tx = Math.max(bounds.minX, Math.min(bounds.maxX, tx));
                    ty = Math.max(bounds.minY, Math.min(bounds.maxY, ty));
                    return { tx, ty };
                }

                // occasionally perform a sides-swap so nodes cross side-to-side
                const doSideSwap = Math.random() < 0.18;

                // first generate tentative targets
                const keys = Object.keys(state.nodes);
                const tentative = {};
                keys.forEach((k, i) => {
                    if (doSideSwap) {
                        // swap n1 and n3 positions: n1 -> near n3, n3 -> near n1, n2 stays central
                        if (k === 'n1') {
                            tentative[k] = { tx: state.nodes['n3'].x + (Math.random()-0.5)*8, ty: state.nodes['n3'].y + (Math.random()-0.5)*8 };
                        } else if (k === 'n3') {
                            tentative[k] = { tx: state.nodes['n1'].x + (Math.random()-0.5)*8, ty: state.nodes['n1'].y + (Math.random()-0.5)*8 };
                        } else {
                            tentative[k] = { tx: state.nodes[k].x + (Math.random()-0.5)*6, ty: state.nodes[k].y + (Math.random()-0.5)*6 };
                        }
                    } else {
                        if (Math.random() < 0.12 && k === 'n2') {
                            const targetKey = Math.random() < 0.5 ? 'n1' : 'n3';
                            tentative[k] = {
                                tx: state.nodes[targetKey].x + (Math.random() - 0.5) * 12,
                                ty: state.nodes[targetKey].y + (Math.random() - 0.5) * 12
                            };
                        } else {
                            tentative[k] = {
                                tx: Math.random() * (bounds.maxX - bounds.minX) + bounds.minX,
                                ty: Math.random() * (bounds.maxY - bounds.minY) + bounds.minY
                            };
                        }
                    }
                });

                // enforce separation between tentative targets
                keys.forEach((k, i) => {
                    const others = keys.filter(x => x !== k).map(x => tentative[x]);
                    const fixed = ensureSeparation(tentative[k].tx, tentative[k].ty, others);
                    state.nodes[k].tx = fixed.tx;
                    state.nodes[k].ty = fixed.ty;
                });

                // update timing
                state.lastTargetAt = now;
                state.nextSwapAt = now + (1800 + Math.random()*2200); // slower changes
            }

            chooseNewTargets();

            function step() {
                try {
                    // occasionally swap targets to create crossing behavior
                    if (Date.now() > state.nextSwapAt) chooseNewTargets();

                    // move each node towards its target with interpolation and simple collision avoidance
                    const keys = Object.keys(state.nodes);
                    // first apply smooth lerp towards targets
                    keys.forEach(k => {
                        const n = state.nodes[k];
                        const t = Math.max(0.04, Math.min(0.2, n.speed + 0.02));
                        n.x += (n.tx - n.x) * t;
                        n.y += (n.ty - n.y) * t;
                    });

                    // then apply separation if nodes are too close
                    const minSeparation = 28; // px
                    for (let i = 0; i < keys.length; i++) {
                        for (let j = i+1; j < keys.length; j++) {
                            const a = state.nodes[keys[i]];
                            const b = state.nodes[keys[j]];
                            const dx = b.x - a.x;
                            const dy = b.y - a.y;
                            const d = Math.hypot(dx, dy) || 0.0001;
                            if (d < minSeparation) {
                                // push each away proportionally
                                const overlap = (minSeparation - d) / 2;
                                const ux = dx / d;
                                const uy = dy / d;
                                a.x -= ux * overlap;
                                a.y -= uy * overlap;
                                b.x += ux * overlap;
                                b.y += uy * overlap;
                            }
                        }
                    }

                    // apply to DOM and clamp to bounds
                    keys.forEach(k => {
                        const n = state.nodes[k];
                        const el = _nodes[k];
                        // clamp to display bounds roughly matching SVG viewbox
                        n.x = Math.max(12, Math.min(188, n.x));
                        n.y = Math.max(10, Math.min(70, n.y));
                        if (el) {
                            el.setAttribute('cx', n.x.toFixed(2));
                            el.setAttribute('cy', n.y.toFixed(2));
                        }
                    });

                    // update connecting lines fast
                    _nodes.l12.setAttribute('x1', _nodes.n1.getAttribute('cx'));
                    _nodes.l12.setAttribute('y1', _nodes.n1.getAttribute('cy'));
                    _nodes.l12.setAttribute('x2', _nodes.n2.getAttribute('cx'));
                    _nodes.l12.setAttribute('y2', _nodes.n2.getAttribute('cy'));

                    _nodes.l23.setAttribute('x1', _nodes.n2.getAttribute('cx'));
                    _nodes.l23.setAttribute('y1', _nodes.n2.getAttribute('cy'));
                    _nodes.l23.setAttribute('x2', _nodes.n3.getAttribute('cx'));
                    _nodes.l23.setAttribute('y2', _nodes.n3.getAttribute('cy'));
                } catch (e) {
                    console.warn('Loader RAF step error', e);
                }
                _loaderInterval = requestAnimationFrame(step);
            }

            // initialize progress to a small value; controlled updates happen during init
            startLoaderAnimation._progress = 6;
            setLoaderProgress(startLoaderAnimation._progress);

            _loaderInterval = requestAnimationFrame(function loop() {
                // line pulse when nodes are near
                try {
                    const ax = parseFloat(_nodes.n1.getAttribute('cx'));
                    const ay = parseFloat(_nodes.n1.getAttribute('cy'));
                    const bx = parseFloat(_nodes.n2.getAttribute('cx'));
                    const by = parseFloat(_nodes.n2.getAttribute('cy'));
                    const cx = parseFloat(_nodes.n3.getAttribute('cx'));
                    const cy = parseFloat(_nodes.n3.getAttribute('cy'));
                    const dist12 = Math.hypot(ax-bx, ay-by);
                    const dist23 = Math.hypot(bx-cx, by-cy);
                    // pulse if close
                    if (dist12 < 36) _nodes.l12.classList.add('pulse'); else _nodes.l12.classList.remove('pulse');
                    if (dist23 < 36) _nodes.l23.classList.add('pulse'); else _nodes.l23.classList.remove('pulse');
                } catch (e) {}
                step();
            });
        }

        function stopLoaderAnimation() {
            try {
                if (_loaderInterval) {
                    cancelAnimationFrame(_loaderInterval);
                    _loaderInterval = null;
                }
            } catch (e) {}
            try { clearInterval(startLoaderAnimation._progressTimer); } catch (e) {}
            try { if (_progressState.rafId) { cancelAnimationFrame(_progressState.rafId); _progressState.rafId = null; } } catch (e) {}
            // reset nodes to canonical positions
            try {
                if (_nodes.n1) { _nodes.n1.setAttribute('cx', 28); _nodes.n1.setAttribute('cy', 32); }
                if (_nodes.n2) { _nodes.n2.setAttribute('cx', 80); _nodes.n2.setAttribute('cy', 20); }
                if (_nodes.n3) { _nodes.n3.setAttribute('cx', 132); _nodes.n3.setAttribute('cy', 32); }
                if (_nodes.l12) {
                    _nodes.l12.setAttribute('x1', 28); _nodes.l12.setAttribute('y1', 32);
                    _nodes.l12.setAttribute('x2', 80); _nodes.l12.setAttribute('y2', 20);
                }
                if (_nodes.l23) {
                    _nodes.l23.setAttribute('x1', 80); _nodes.l23.setAttribute('y1', 20);
                    _nodes.l23.setAttribute('x2', 132); _nodes.l23.setAttribute('y2', 32);
                }
                // ensure progress bar finishes
                setLoaderProgress(100);
                try { setTimeout(() => { const el = document.getElementById('loaderProgressBar'); if (el) el.style.opacity = '0.98'; }, 120); } catch (e) {}
            } catch (e) {}
        }

        function hideLoadingScreen() {
            const loadingScreen = document.getElementById('loadingScreen');
            if (loadingScreen) {
                // Ensure minimum loading duration for better UX
                const loadingDuration = Date.now() - AppState.loadingStartTime;
                const remainingTime = Math.max(0, CONFIG.LOADING_MIN_DURATION - loadingDuration);
                
                setTimeout(() => {
                    loadingScreen.classList.add('hidden');
                    stopLoaderAnimation();
                    setTimeout(() => {
                        loadingScreen.style.display = 'none';
                    }, 500); // Wait for fade out animation
                }, remainingTime);
            }
        }

        // ==================== INITIALIZATION ====================
        
        async function initializeAttendanceSystem() {
            console.log('Initializing attendance system...');
            AppState.loadingStartTime = Date.now();
            // start the loader animation immediately
            try { startLoaderAnimation(); } catch (e) { console.warn('Loader animation failed to start', e); }
            
            try {
                // Step 1: Validate event date
                updateLoadingStatus('Checking event date and time...');
                await new Promise(resolve => setTimeout(resolve, 500)); // Simulate processing
                setLoaderProgress(12);
                
                const eventState = validateEventDateTime();
                if (eventState !== 'active') {
                    // Keep the UI informative but continue initializing so current time and logs work
                    updateLoadingStatus(eventState === 'upcoming' ? 'Upcoming event - display is read-only' : 'Past event - display in read-only review mode');
                    await new Promise(resolve => setTimeout(resolve, 800));
                    // Disable inputs but keep the page interactive for review
                    disableAttendanceSystem(true);
                    // Do not return; continue initialization so clocks and log rendering work
                }
                
                // Step 2: Load existing records
                updateLoadingStatus('Loading attendance records...');
                await new Promise(resolve => setTimeout(resolve, 300));
                setLoaderProgress(36);
                loadExistingAttendanceRecords();
                
                // Step 3: Initialize real-time updates
                updateLoadingStatus('Starting real-time monitoring...');
                await new Promise(resolve => setTimeout(resolve, 300));
                setLoaderProgress(58);
                startRealTimeUpdates();
                
                // Step 4: Setup UI components
                updateLoadingStatus('Setting up interface...');
                await new Promise(resolve => setTimeout(resolve, 300));
                setLoaderProgress(72);
                
                // Initialize time display
                updateCurrentTime();
                setInterval(updateCurrentTime, 1000);
                
                // Initialize session status
                updateSessionStatus();
                updateSessionCard();
                
                // Setup RFID input management
                initializeRFIDCapture();
                
                // Setup profile management
                initializeProfileDisplay();
                
                updateLoadingStatus('System ready!');
                await new Promise(resolve => setTimeout(resolve, 500));
                setLoaderProgress(100);
                
                AppState.isInitialized = true;
                hideLoadingScreen();
                
                console.log('Attendance system initialization completed');
                // Start periodic client-side session checks (ensures session transitions happen even without server push)
                if (sessionStatusInterval) clearInterval(sessionStatusInterval);
                sessionStatusInterval = setInterval(() => {
                    try {
                        updateSessionStatus();
                    } catch (err) {
                        console.error('Error during client-side session status update:', err);
                    }
                }, CONFIG.REAL_TIME_UPDATE_INTERVAL);
                
            } catch (error) {
                console.error('Failed to initialize attendance system:', error);
                updateLoadingStatus('Initialization failed');
                await new Promise(resolve => setTimeout(resolve, 1500));
                hideLoadingScreen();
                showToast('Failed to initialize attendance system. Please refresh the page.', 'error');
            }
        }

        function disableAttendanceSystem(readOnly = false) {
            // Disable inputs and update UI when attendance is not available.
            // If readOnly is true, preserve the time display and attendance log for review.
            const rfidInput = document.getElementById('rfidInput');
            if (rfidInput) {
                rfidInput.disabled = true;
            }

            const userIdInput = document.getElementById('userIdInput');
            if (userIdInput) {
                userIdInput.disabled = true;
            }

            // Update scan status to a professional message
            const scanStatus = document.getElementById('scanStatus');
            if (scanStatus) {
                scanStatus.textContent = readOnly ? 'Display is read-only. Attendance input is disabled.' : 'Attendance is not available at this time.';
            }

            // Update session status and card with clear professional labels
            const sessionStatus = document.getElementById('sessionStatus');
            const currentSessionDisplay = document.getElementById('currentSessionDisplay');
                if (sessionStatus && currentSessionDisplay) {
                currentSessionDisplay.textContent = readOnly ? 'No Active Event (Read-Only)' : 'No Active Event';
                sessionStatus.textContent = readOnly ? 'Read-Only' : 'Inactive';
                sessionStatus.className = readOnly ? 'inline-flex items-center px-2 py-1 rounded-full text-xs bg-gray-400 text-white' : 'inline-flex items-center px-2 py-1 rounded-full text-xs bg-red-500 text-white';
            }

            // If readOnly, ensure attendance log remains visible and interactive in read-only mode
            if (readOnly) {
                // Remove any input affordances visually but keep records visible
                const manualSubmitBtn = document.querySelector('button[onclick="processManualAttendance()"]');
                if (manualSubmitBtn) {
                    manualSubmitBtn.disabled = true;
                    manualSubmitBtn.classList.add('attendance-disabled');
                    manualSubmitBtn.textContent = 'Display Read-Only';
                }
            }
        }

        // ==================== RFID CAPTURE MANAGEMENT ====================
        
        function initializeRFIDCapture() {
            const rfidInput = document.getElementById('rfidInput');
            const userIdInput = document.getElementById('userIdInput');
            
            if (!rfidInput) return;
            
            // Ensure RFID input gets exclusive focus for scanning
            function maintainRFIDFocus() {
                if (!AppState.rfidInputLocked && document.activeElement !== rfidInput && document.activeElement !== userIdInput) {
                    rfidInput.focus();
                }
            }
            
            // Focus management
            rfidInput.addEventListener('focus', function() {
                AppState.rfidInputLocked = true;
                this.classList.add('rfid-locked');
            });
            
            rfidInput.addEventListener('blur', function() {
                AppState.rfidInputLocked = false;
                this.classList.remove('rfid-locked');
                // Re-focus after a short delay if not manually focused elsewhere
                setTimeout(() => {
                    if (document.activeElement !== userIdInput) {
                        this.focus();
                    }
                }, 100);
            });
            
            // Prevent other inputs from stealing focus during RFID scan
            setInterval(maintainRFIDFocus, CONFIG.RFID_FOCUS_CHECK_INTERVAL);
            
            // Handle RFID input
            rfidInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    const rfidCode = this.value.trim();
                    
                    if (!AppState.eventDateValid) {
                        showToast('Attendance not available - Event date does not match current date', 'warning');
                        this.value = '';
                        return;
                    }
                    
                    if (!currentActiveSession) {
                        showToast('No active session - Please wait for session to start', 'warning');
                        this.value = '';
                        return;
                    }
                    
                    // Remove length validation - let backend handle RFID validation
                    
                    processAttendance(rfidCode, null);
                    this.value = '';
                }
            });
            
            console.log('RFID capture system initialized');
        }

        // ==================== PROFILE DISPLAY MANAGEMENT ====================
        
        function initializeProfileDisplay() {
            console.log('Profile display system initialized');
        }

        function displayUserProfile(user, duration = CONFIG.PROFILE_DISPLAY_DURATION) {
            clearProfileClearTimer();
            
            AppState.lastUserProfileUpdate = Date.now();
            
            const userInfoContent = document.getElementById('userInfoContent');
            if (!userInfoContent) return;
            
            // Remove any existing auto-clear animation
            userInfoContent.classList.remove('profile-auto-clear');
            
            // Set profile content
            showUserInfo(user);
            
            // Add auto-clear animation and set timer
            setTimeout(() => {
                userInfoContent.classList.add('profile-auto-clear');
            }, 100);
            
            profileClearTimer = setTimeout(() => {
                clearUserProfile();
            }, duration);
        }

        function clearUserProfile() {
            clearProfileClearTimer();
            
            const userInfoContent = document.getElementById('userInfoContent');
            if (userInfoContent) {
                userInfoContent.classList.remove('profile-auto-clear');
            }
            
            clearUserInfo();
        }

        function clearProfileClearTimer() {
            if (profileClearTimer) {
                clearTimeout(profileClearTimer);
                profileClearTimer = null;
            }
        }

        // ==================== REAL-TIME UPDATES ====================
        
        function startRealTimeUpdates() {
            if (realTimeUpdateInterval) {
                clearInterval(realTimeUpdateInterval);
            }
            // Run an immediate check, then start interval polling
            (async () => {
                try {
                    await checkAttendanceStatus();
                } catch (err) {
                    console.error('Immediate attendance status check failed:', err);
                }
            })();

            realTimeUpdateInterval = setInterval(async () => {
                try {
                    await checkAttendanceStatus();
                } catch (error) {
                    console.error('Error in real-time update:', error);
                }
            }, CONFIG.REAL_TIME_UPDATE_INTERVAL);

            console.log('Real-time updates started with interval', CONFIG.REAL_TIME_UPDATE_INTERVAL);
        }

        // Check attendance status and update display
        async function checkAttendanceStatus() {
            // Preserve manual entry state during update
            const userIdInput = document.getElementById('userIdInput');
            const preservedValue = userIdInput ? userIdInput.value : '';
            const preservedFocus = document.activeElement === userIdInput;
            
            try {
                const response = await fetch(`<?= base_url('pederasyon/getAttendanceStatus/') ?>${eventId}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                
                if (data.success) {
                    // Check if settings have been updated
                    const newSettingsUpdate = data.settings_last_updated;
                    if (lastSettingsUpdate && lastSettingsUpdate !== newSettingsUpdate) {
                        console.log('Attendance settings updated, refreshing...');
                        showToast('Attendance settings updated! Refreshing display...', 'info');
                        
                        // Update local settings
                        Object.assign(attendanceSettings, data.attendance_settings);
                        
                        // Update session card display immediately
                        updateSessionCard();
                        
                        // Update session status immediately
                        updateSessionStatusFromData(data);
                        
                        // Cancel any existing auto-timeout timers and set new ones
                        setupAutoTimeoutTimers(data);
                    }
                    lastSettingsUpdate = newSettingsUpdate;
                    
                    // Update whether event date is valid on the client
                    if (typeof data.event_date_valid !== 'undefined') {
                        AppState.eventDateValid = !!data.event_date_valid;
                    }

                    // Update session status from server and also run client-side session check
                    updateSessionStatusFromData(data);
                    // Run client-side check to ensure UI reflects exact client time
                    updateSessionStatus();

                    // Update attendance records if there are new ones
                    updateAttendanceRecords(data.attendance_records);
                    
                } else {
                    console.error('Failed to get attendance status:', data.message);
                }
            } catch (error) {
                console.error('Error checking attendance status:', error);
            } finally {
                // Restore manual entry state after update
                if (userIdInput) {
                    userIdInput.value = preservedValue;
                    if (preservedFocus && preservedValue.length > 0) {
                        // Only restore focus if user was typing
                        setTimeout(() => {
                            userIdInput.focus();
                            // Restore cursor position to end
                            userIdInput.setSelectionRange(preservedValue.length, preservedValue.length);
                        }, 50);
                    }
                }
            }
        }

        // Update session status from server data
        function updateSessionStatusFromData(data) {
            const morningStatus = data.morning_status;
            const afternoonStatus = data.afternoon_status;
            
            // Determine which session should be active
            let newActiveSession = null;
            let sessionInfo = null;
            
            if (morningStatus.active) {
                newActiveSession = 'morning';
                sessionInfo = morningStatus;
            } else if (afternoonStatus.active) {
                newActiveSession = 'afternoon';
                sessionInfo = afternoonStatus;
            }
            
            // Check for session state changes
            if (currentActiveSession !== newActiveSession) {
                handleSessionStateChange(currentActiveSession, newActiveSession, sessionInfo, morningStatus, afternoonStatus);
            }
            
            currentActiveSession = newActiveSession;
            updateSessionDisplay(sessionInfo, morningStatus, afternoonStatus);
        }

        // ==================== SESSION STATE MANAGEMENT ====================
        
        function handleSessionStateChange(oldSession, newSession, sessionInfo, morningStatus, afternoonStatus) {
            const sessionName = newSession ? newSession.charAt(0).toUpperCase() + newSession.slice(1) : '';
            const oldSessionName = oldSession ? oldSession.charAt(0).toUpperCase() + oldSession.slice(1) : '';
            
            // Clear pending notifications when session state changes
            if (pendingNotificationInterval) {
                clearInterval(pendingNotificationInterval);
                pendingNotificationInterval = null;
            }
            
            if (!oldSession && newSession) {
                // Session started
                showSessionToast(`${sessionName} session is now active!`, 'starting');
                enableAttendanceInput();
                setupSessionAutoTimeout(newSession, sessionInfo);
                
            } else if (oldSession && !newSession) {
                // Session ended  
                showSessionToast(`${oldSessionName} session has ended`, 'ended');
                disableAttendanceInput();
                triggerAutoTimeout(oldSession);
                
            } else if (oldSession && newSession && oldSession !== newSession) {
                // Session switched
                showSessionToast(`${oldSessionName} session ended, ${sessionName} session started`, 'active');
                triggerAutoTimeout(oldSession);
                setupSessionAutoTimeout(newSession, sessionInfo);
            }
            
            // Handle pending sessions - now using periodic toast notifications
            if (morningStatus && morningStatus.status === 'waiting') {
                waitForSessionStart('morning', morningStatus.start_time);
            }
            if (afternoonStatus && afternoonStatus.status === 'waiting') {
                waitForSessionStart('afternoon', afternoonStatus.start_time);
            }
        }

        function enableAttendanceInput() {
            const rfidInput = document.getElementById('rfidInput');
            const userIdInput = document.getElementById('userIdInput');
            
            if (rfidInput) {
                rfidInput.disabled = false;
                rfidInput.focus();
            }
            if (userIdInput) {
                userIdInput.disabled = false;
            }
        }

        function disableAttendanceInput() {
            const rfidInput = document.getElementById('rfidInput');
            const userIdInput = document.getElementById('userIdInput');
            
            if (rfidInput) {
                rfidInput.disabled = true;
            }
            if (userIdInput) {
                userIdInput.disabled = true;
            }
        }

        // Setup automatic session start timer
        function setupSessionWaitTimer(session, status) {
            if (sessionStartWaitInterval) {
                clearInterval(sessionStartWaitInterval);
            }
            
            if (status.countdown_target) {
                sessionStartWaitInterval = setInterval(() => {
                    const currentTime = getCurrentTime24HourForComparison();
                    
                    if (currentTime >= status.countdown_target) {
                        clearInterval(sessionStartWaitInterval);
                        sessionStartWaitInterval = null;
                        
                        // Force immediate status check
                        checkAttendanceStatus();
                    }
                }, SESSION_CHECK_INTERVAL);
            }
        }

        // Setup automatic timeout when session ends
        function setupSessionAutoTimeout(session, sessionInfo) {
            if (autoTimeoutTimers[session]) {
                clearTimeout(autoTimeoutTimers[session]);
            }
            
            if (sessionInfo && sessionInfo.end_time) {
                const now = new Date();
                const endTime = sessionInfo.end_time;
                const [hours, minutes] = endTime.split(':').map(Number);
                
                const sessionEndDate = new Date();
                sessionEndDate.setHours(hours, minutes, 0, 0);
                
                const timeUntilEnd = sessionEndDate.getTime() - now.getTime();
                
                if (timeUntilEnd > 0) {
                    autoTimeoutTimers[session] = setTimeout(() => {
                        triggerAutoTimeout(session);
                    }, timeUntilEnd);
                    
                    console.log(`Auto-timeout scheduled for ${session} session in ${Math.round(timeUntilEnd/1000)} seconds`);
                }
            }
        }

        // Setup auto-timeout timers from server data
        function setupAutoTimeoutTimers(data) {
            // Clear existing timers
            Object.values(autoTimeoutTimers).forEach(timer => clearTimeout(timer));
            autoTimeoutTimers = {};
            
            // Setup new timers based on current session states
            if (data.morning_status.active) {
                setupSessionAutoTimeout('morning', data.morning_status);
            }
            if (data.afternoon_status.active) {
                setupSessionAutoTimeout('afternoon', data.afternoon_status);
            }
        }

        // Trigger automatic timeout for users who didn't check out
        async function triggerAutoTimeout(session) {
            try {
                const response = await fetch('<?= base_url('pederasyon/autoMarkTimeouts') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        event_id: eventId,
                        session: session
                    })
                });
                
                const data = await response.json();
                
                if (data.success && data.updated_count > 0) {
                    showToast(`Auto-marked ${data.updated_count} users as timed out for ${session} session`, 'info');
                    // Force refresh of attendance records
                    checkAttendanceStatus();
                }
                
            } catch (error) {
                console.error('Error triggering auto-timeout:', error);
            }
        }

        // ==================== ATTENDANCE LOG MANAGEMENT ====================
        
        function updateAttendanceRecords(newRecords) {
            const logsList = document.getElementById('attendanceLogsList');
            
            // Remove loading states and temporary messages
            const placeholderRows = logsList.querySelectorAll('tr.loading-records, tr.text-center, tr.no-records-row, tr.session-ended-message');
            placeholderRows.forEach(row => row.remove());

            if (newRecords && newRecords.length > 0) {
                // Clear any existing "no records" message
                clearNoRecordsMessage();
                
                // Add or update records
                newRecords.forEach(record => {
                    addAttendanceLogEntry(record);
                });
                updateAttendanceCounts();
                // Ensure records are sorted newest-first after batch update
                sortAttendanceByDatetimeDesc();
            } else {
                // Check if there are any existing records
                const existingRows = logsList.querySelectorAll('tr[data-existing-record], tr.permanent-visible');
                if (existingRows.length === 0) {
                    showAttendanceMessage('No attendance records for this event yet.');
                }
            }
        }

        function showAttendanceMessage(message) {
            const logsList = document.getElementById('attendanceLogsList');
            const tbody = logsList.querySelector('tbody') || logsList;
            
            // Remove any existing message
            clearNoRecordsMessage();
            
            const messageRow = document.createElement('tr');
            messageRow.className = 'text-center no-records-row';
            messageRow.innerHTML = `
                <td colspan="4" class="px-6 py-8 text-gray-500">
                    <div class="flex flex-col items-center justify-center space-y-2">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-sm font-medium">${message}</p>
                        <p class="text-xs text-gray-400">Records will appear here when users time in</p>
                    </div>
                </td>
            `;
            
            tbody.appendChild(messageRow);
        }

        function clearNoRecordsMessage() {
            const logsList = document.getElementById('attendanceLogsList');
            const messageRows = logsList.querySelectorAll('tr.no-records-row, tr.no-records, tr.text-center:not([data-existing-record])');
            messageRows.forEach(row => row.remove());
        }



        // Load existing attendance records on page load
        function loadExistingAttendanceRecords() {
            const logsList = document.getElementById('attendanceLogsList');
            
            if (existingAttendanceRecords && existingAttendanceRecords.length > 0) {
                console.log('Loading existing attendance records:', existingAttendanceRecords.length);
                
                // Remove loading state and any placeholder rows
                const placeholderRows = logsList.querySelectorAll('tr.loading-records, tr.no-records-row, tr.text-center, tr.no-records');
                placeholderRows.forEach(row => row.remove());
                
                // Add each existing record using the unified addAttendanceLogEntry function
                existingAttendanceRecords.forEach(record => {
                    // Process AM session - create separate entries for time-in and time-out
                    if (record['time-in_am']) {
                        // Add time-in entry for AM session
                        addAttendanceLogEntry({
                            user_id: record.user_id,
                            name: record.user_name || getFullName(record),
                            session: 'morning',
                            time: formatTimeDisplay(record['time-in_am']),
                            status: record.status_am || 'Present',
                            action: 'time_in',
                            rfid_code: record.rfid_code,
                            zone_purok: record.zone_purok || '',
                            barangay: record.barangay || '',
                            profile_picture: record.profile_picture || '',
                            attendanceStatus: record.status_am || 'Present',
                            isExisting: true  // Mark as existing record for always visible display
                        });
                        
                        // Add time-out entry for AM session if it exists
                        if (record['time-out_am']) {
                            addAttendanceLogEntry({
                                user_id: record.user_id,
                                name: record.user_name || getFullName(record),
                                session: 'morning',
                                time: formatTimeDisplay(record['time-out_am']),
                                status: record.status_am || 'Present',
                                action: 'time_out',
                                rfid_code: record.rfid_code,
                                zone_purok: record.zone_purok || '',
                                barangay: record.barangay || '',
                                profile_picture: record.profile_picture || '',
                                attendanceStatus: record.status_am || 'Present',
                                isExisting: true  // Mark as existing record for always visible display
                            });
                        }
                    }
                    
                    // Process PM session - create separate entries for time-in and time-out
                    if (record['time-in_pm']) {
                        // Add time-in entry for PM session
                        addAttendanceLogEntry({
                            user_id: record.user_id,
                            name: record.user_name || getFullName(record),
                            session: 'afternoon',
                            time: formatTimeDisplay(record['time-in_pm']),
                            status: record.status_pm || 'Present',
                            action: 'time_in',
                            rfid_code: record.rfid_code,
                            zone_purok: record.zone_purok || '',
                            barangay: record.barangay || '',
                            profile_picture: record.profile_picture || '',
                            attendanceStatus: record.status_pm || 'Present',
                            isExisting: true  // Mark as existing record for always visible display
                        });
                        
                        // Add time-out entry for PM session if it exists
                        if (record['time-out_pm']) {
                            addAttendanceLogEntry({
                                user_id: record.user_id,
                                name: record.user_name || getFullName(record),
                                session: 'afternoon',
                                time: formatTimeDisplay(record['time-out_pm']),
                                status: record.status_pm || 'Present',
                                action: 'time_out',
                                rfid_code: record.rfid_code,
                                zone_purok: record.zone_purok || '',
                                barangay: record.barangay || '',
                                profile_picture: record.profile_picture || '',
                                attendanceStatus: record.status_pm || 'Present',
                                isExisting: true  // Mark as existing record for always visible display
                            });
                        }
                    }
                });
                
                // Update counts
                updateAttendanceCounts();
                // Ensure existing records are sorted newest-first after load
                sortAttendanceByDatetimeDesc();
            } else {
                // No existing records, remove loading state and show appropriate message
                const placeholderRows = logsList.querySelectorAll('tr.loading-records');
                placeholderRows.forEach(row => row.remove());
                
                // Only show "no records" if no session is active
                if (!isAnySessionActive() && !hasSessionsEnded()) {
                    logsList.innerHTML = `
                        <tr class="text-center no-records">
                            <td colspan="4" class="px-2 py-4 text-gray-500">
                                <svg class="mx-auto h-5 w-5 text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-xs">No attendance records for this event yet</p>
                            </td>
                        </tr>
                    `;
                }
            }
        }

        // Check if any session is currently active
        function isAnySessionActive() {
            return currentActiveSession !== null;
        }

        // Check if sessions have ended
        function hasSessionsEnded() {
            // Check if there's a session-ended message or if session filter shows "Session Ended"
            const sessionFilter = document.getElementById('sessionFilter');
            return sessionFilter && sessionFilter.textContent.includes('Session Ended');
        }

        // Add existing attendance record to the log


        // Helper function to get full name from record
        function getFullName(record) {
            const firstName = record.first_name || '';
            const middleName = record.middle_name || '';
            const lastName = record.last_name || '';
            return [firstName, middleName, lastName].filter(Boolean).join(' ') || 'Unknown User';
        }

        // Format time for display
        function formatTimeDisplay(timeString) {
            if (!timeString) return '';
            const date = new Date(timeString);
            return date.toLocaleTimeString('en-US', { 
                hour: 'numeric', 
                minute: '2-digit',
                hour12: true 
            });
        }

        // Wait for session to start (automatic session start behavior)
        function waitForSessionStart(session, startTime) {
            // Clear any existing intervals
            if (sessionStartWaitInterval) {
                clearInterval(sessionStartWaitInterval);
            }
            if (pendingNotificationInterval) {
                clearInterval(pendingNotificationInterval);
            }

            const formattedStartTime = formatTimeTo12Hour(startTime);
            
            // Show initial notification immediately
            showToast(`${session.charAt(0).toUpperCase() + session.slice(1)} session will start at ${formattedStartTime}`, 'info', 'session');
            
            // Set up periodic notifications every 1 minute (60000ms) to notify admin
            pendingNotificationInterval = setInterval(() => {
                const currentTime = getCurrentTime24HourForComparison();
                
                if (currentTime >= startTime) {
                    // Session has started, clear the notification interval
                    clearInterval(pendingNotificationInterval);
                    pendingNotificationInterval = null;
                } else {
                    // Show periodic reminder toast
                    showToast(`Reminder: ${session.charAt(0).toUpperCase() + session.slice(1)} session will start at ${formattedStartTime}`, 'info', 'session');
                }
            }, 60000); // 1 minute intervals

            // Continue with session start monitoring at less frequent intervals
            sessionStartWaitInterval = setInterval(() => {
                const currentTime = getCurrentTime24HourForComparison();

                if (currentTime >= startTime) {
                    clearInterval(sessionStartWaitInterval);
                    sessionStartWaitInterval = null;
                    
                    // Clear pending notifications when session starts
                    if (pendingNotificationInterval) {
                        clearInterval(pendingNotificationInterval);
                        pendingNotificationInterval = null;
                    }
                    
                    // Important event - show session started toast
                    showToast(`${session.charAt(0).toUpperCase() + session.slice(1)} session has started!`, 'success', 'session');
                    updateSessionStatus();
                }
            }, CONFIG.SESSION_CHECK_INTERVAL);
        }

        // Update current time display
        function updateCurrentTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', { 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit',
                hour12: true 
            });
            document.getElementById('currentTime').textContent = timeString;
        }

        // Update session display based on server data
        function updateSessionDisplay(activeSessionInfo, morningStatus, afternoonStatus) {
            // Preserve manual entry state
            const userIdInput = document.getElementById('userIdInput');
            const preservedValue = userIdInput ? userIdInput.value : '';
            const preservedFocus = document.activeElement === userIdInput;
            
            const sessionIndicator = document.getElementById('sessionIndicator');
            const sessionStatus = document.getElementById('sessionStatus');
            const currentSessionDisplay = document.getElementById('currentSessionDisplay');
            const scanStatus = document.getElementById('scanStatus');
            
            // If the event itself is marked upcoming or past, show clear labels
            if (AppState.eventState === 'upcoming') {
                currentSessionDisplay.textContent = 'Event Not Active (Upcoming)';
                sessionStatus.textContent = 'Not Active';
                sessionStatus.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-600 text-white';
                sessionIndicator.className = 'w-3 h-3 rounded-full bg-blue-500 session-indicator';
                const startText = (morningStatus && morningStatus.start_time) || (afternoonStatus && afternoonStatus.start_time) || '';
                scanStatus.textContent = startText ? `Attendance opens at ${formatTimeTo12Hour(startText)} on the event date.` : 'Event scheduled - display is read-only.';

                document.getElementById('rfidInput').disabled = true;
                if (userIdInput) userIdInput.disabled = true;
                showAllAttendanceLogs();
                return;
            }

            if (AppState.eventState === 'past') {
                currentSessionDisplay.textContent = 'Event Not Active (Completed)';
                sessionStatus.textContent = 'Not Active';
                sessionStatus.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs bg-gray-500 text-white';
                sessionIndicator.className = 'w-3 h-3 rounded-full bg-gray-400 session-indicator';
                scanStatus.textContent = 'Event completed. Attendance display is read-only for review.';

                document.getElementById('rfidInput').disabled = true;
                if (userIdInput) userIdInput.disabled = true;
                showAllAttendanceLogs();
                return;
            }

            if (activeSessionInfo && activeSessionInfo.active) {
                // Active session
                const sessionName = activeSessionInfo.message || `${currentActiveSession.charAt(0).toUpperCase() + currentActiveSession.slice(1)} Session`;
                currentSessionDisplay.textContent = sessionName;
                sessionStatus.textContent = 'Active';
                sessionStatus.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-500 bg-opacity-100 text-white';
                sessionIndicator.className = 'w-3 h-3 rounded-full bg-green-500 session-indicator';
                scanStatus.textContent = 'Ready to scan - Tap your RFID card';
                
                // Enable attendance input but preserve manual entry
                document.getElementById('rfidInput').disabled = false;
                if (userIdInput) {
                    userIdInput.disabled = false;
                    userIdInput.value = preservedValue; // Restore value
                }
                
                // Filter attendance log by active session
                filterAttendanceLogBySession();
                
            } else {
                // No active session - check for pending sessions
                let pendingSession = null;
                
                if (morningStatus.status === 'waiting') {
                    pendingSession = morningStatus;
                } else if (afternoonStatus.status === 'waiting') {
                    pendingSession = afternoonStatus;
                }
                
                if (pendingSession) {
                    // Pending session
                    currentSessionDisplay.textContent = pendingSession.message || 'Waiting for session';
                    sessionStatus.textContent = 'Pending';
                    sessionStatus.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs bg-yellow-500 bg-opacity-100 text-white';
                    sessionIndicator.className = 'w-3 h-3 rounded-full bg-yellow-400';
                    const displayTime = pendingSession.display_start_time || formatTimeTo12Hour(pendingSession.start_time);
                    scanStatus.textContent = `Session starts at ${displayTime}`;
                    
                    // Disable RFID input but keep manual entry enabled and preserve its state
                    document.getElementById('rfidInput').disabled = true;
                    if (userIdInput) {
                        userIdInput.disabled = false;
                        userIdInput.value = preservedValue; // Restore value
                    }
                    
                } else {
                    // No sessions
                    currentSessionDisplay.textContent = 'No Active Session';
                    sessionStatus.textContent = 'Waiting';
                    sessionStatus.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs bg-white bg-opacity-20';
                    sessionIndicator.className = 'w-3 h-3 rounded-full bg-gray-400';
                    scanStatus.textContent = 'No session scheduled';
                    
                    // Keep RFID disabled, but allow manual entry and preserve its state
                    document.getElementById('rfidInput').disabled = true;
                    if (userIdInput) {
                        userIdInput.disabled = false;
                        userIdInput.value = preservedValue; // Restore value
                    }
                    
                    // Show all attendance logs when no session is active
                    showAllAttendanceLogs();
                }
            }
            
            // ...existing code... (manual entry UI updated later in the file)
            
            // Restore focus if user was typing
            if (preservedFocus && preservedValue.length > 0 && userIdInput) {
                setTimeout(() => {
                    userIdInput.focus();
                    userIdInput.setSelectionRange(preservedValue.length, preservedValue.length);
                }, 50);
            }
        }

        // Enhanced time comparison function with proper accuracy
        function compareTimeStrings(currentTime, targetTime) {
            // Convert HH:MM strings to comparable format
            const current = currentTime.split(':').map(n => parseInt(n, 10));
            const target = targetTime.split(':').map(n => parseInt(n, 10));
            
            const currentMinutes = current[0] * 60 + current[1];
            const targetMinutes = target[0] * 60 + target[1];
            
            return {
                isBefore: currentMinutes < targetMinutes,
                isAfter: currentMinutes > targetMinutes,
                isEqual: currentMinutes === targetMinutes,
                isAtOrAfter: currentMinutes >= targetMinutes,
                isAtOrBefore: currentMinutes <= targetMinutes
            };
        }

        // Update session status based on current time with improved accuracy
        function updateSessionStatus() {
            const now = new Date();
            const currentTime = getCurrentTime24HourForComparison(); // HH:MM format for backend comparison
            const previousActiveSession = currentActiveSession;
            currentActiveSession = null;
            
            const sessionIndicator = document.getElementById('sessionIndicator');
            const sessionStatus = document.getElementById('sessionStatus');
            const currentSessionDisplay = document.getElementById('currentSessionDisplay');
            const scanStatus = document.getElementById('scanStatus');

            if (AppState.eventState && AppState.eventState !== 'active') {
                // When the current date does not match the event date, treat the session card as not active
                const isUpcoming = AppState.eventState === 'upcoming';
                currentSessionDisplay.textContent = isUpcoming ? 'Event Not Active (Upcoming)' : 'Event Not Active (Completed)';
                sessionStatus.textContent = 'Not Active';
                sessionStatus.className = isUpcoming
                    ? 'inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-600 text-white'
                    : 'inline-flex items-center px-2 py-1 rounded-full text-xs bg-gray-500 text-white';
                sessionIndicator.className = isUpcoming
                    ? 'w-3 h-3 rounded-full bg-blue-500 session-indicator'
                    : 'w-3 h-3 rounded-full bg-gray-400 session-indicator';
                scanStatus.textContent = isUpcoming
                    ? 'Attendance opens on the event date. Display is read-only.'
                    : 'This event has concluded. Display is read-only for review.';

                // Disable inputs during read-only mode
                const rfidInput = document.getElementById('rfidInput');
                if (rfidInput) rfidInput.disabled = true;
                const userIdInput = document.getElementById('userIdInput');
                if (userIdInput) userIdInput.disabled = true;

                showAllAttendanceLogs();
                return;
            }
            
            let sessionState = 'inactive';
            let nextSessionInfo = null;
            let sessionEnded = false;
            
            // Check morning session with precise time comparison
            if (attendanceSettings.start_attendance_am && attendanceSettings.end_attendance_am) {
                const startTime = attendanceSettings.start_attendance_am;
                const endTime = attendanceSettings.end_attendance_am;
                
                const startComparison = compareTimeStrings(currentTime, startTime);
                const endComparison = compareTimeStrings(currentTime, endTime);
                
                const isActive = startComparison.isAtOrAfter && endComparison.isAtOrBefore;
                const isPending = startComparison.isBefore;
                const isPast = endComparison.isAfter;
                
                if (isActive) {
                    currentActiveSession = 'morning';
                    sessionState = 'active';
                    // Show notification when morning session starts (only if no previous session was active)
                    if (previousActiveSession !== 'morning') {
                        showToast('Morning session is now active!', 'success');
                    }
                } else if (isPending) {
                    sessionState = 'pending';
                    nextSessionInfo = { session: 'morning', startTime: startTime };
                } else if (isPast && previousActiveSession === 'morning') {
                    // AUTOMATIC TIMEOUT - Session just ended
                    sessionEnded = true;
                    showToast('Morning session has ended - Auto-timeout initiated', 'info');
                    autoTimeoutSession('morning');
                    hideSessionAttendanceLogs(); // Hide live display when session ends
                }
            }
            
            // Check afternoon session with precise time comparison
            if (attendanceSettings.start_attendance_pm && attendanceSettings.end_attendance_pm) {
                const startTime = attendanceSettings.start_attendance_pm;
                const endTime = attendanceSettings.end_attendance_pm;
                
                const startComparison = compareTimeStrings(currentTime, startTime);
                const endComparison = compareTimeStrings(currentTime, endTime);
                
                const isActive = startComparison.isAtOrAfter && endComparison.isAtOrBefore;
                const isPending = startComparison.isBefore;
                const isPast = endComparison.isAfter;
                
                if (isActive) {
                    currentActiveSession = 'afternoon';
                    sessionState = 'active';
                    // Show notification when afternoon session starts (only if no previous session was active)
                    if (previousActiveSession !== 'afternoon') {
                        showToast('Afternoon session is now active!', 'success');
                    }
                } else if (isPending && (!nextSessionInfo || startComparison.isBefore)) {
                    // Use afternoon if it's the next upcoming session
                    sessionState = 'pending';
                    nextSessionInfo = { session: 'afternoon', startTime: startTime };
                } else if (isPast && previousActiveSession === 'afternoon') {
                    // AUTOMATIC TIMEOUT - Session just ended
                    sessionEnded = true;
                    showToast('Afternoon session has ended - Auto-timeout initiated', 'info');
                    autoTimeoutSession('afternoon');
                    hideSessionAttendanceLogs(); // Hide live display when session ends
                }
            }
            
            // Update UI based on session status
            if (currentActiveSession) {
                const sessionName = currentActiveSession === 'morning' ? 'Morning Session' : 'Afternoon Session';
                currentSessionDisplay.textContent = sessionName;
                sessionStatus.textContent = 'Active';
                sessionStatus.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-500 bg-opacity-100 text-white';
                sessionIndicator.className = 'w-3 h-3 rounded-full bg-green-500 session-indicator';
                scanStatus.textContent = 'Ready to scan - Tap your RFID card';
                
                // Enable attendance input
                document.getElementById('rfidInput').disabled = false;
                document.getElementById('userIdInput').disabled = false;
                
                // Clear any waiting interval
                if (sessionStartWaitInterval) {
                    clearInterval(sessionStartWaitInterval);
                    sessionStartWaitInterval = null;
                }
                
                // Filter attendance log by active session
                filterAttendanceLogBySession();
            } else if (sessionState === 'pending' && nextSessionInfo) {
                currentSessionDisplay.textContent = `Waiting for ${nextSessionInfo.session} session`;
                sessionStatus.textContent = 'Pending';
                sessionStatus.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs bg-yellow-500 bg-opacity-100 text-white';
                sessionIndicator.className = 'w-3 h-3 rounded-full bg-yellow-400';
                const displayTime = nextSessionInfo.display_start_time || formatTimeTo12Hour(nextSessionInfo.startTime);
                scanStatus.textContent = `Session starts at ${displayTime}`;
                
                // Disable attendance input during pending state
                document.getElementById('rfidInput').disabled = true;
                document.getElementById('userIdInput').disabled = false; // Keep manual entry enabled
                
                // Start waiting for session to begin
                waitForSessionStart(nextSessionInfo.session, nextSessionInfo.startTime);
            } else {
                currentSessionDisplay.textContent = 'No Active Session';
                sessionStatus.textContent = 'Waiting';
                sessionStatus.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs bg-white bg-opacity-20';
                sessionIndicator.className = 'w-3 h-3 rounded-full bg-gray-400';
                scanStatus.textContent = 'No session scheduled';
                
                // Keep RFID input disabled, but allow manual entry
                document.getElementById('rfidInput').disabled = true;
                document.getElementById('userIdInput').disabled = false;
                
                // Show all attendance logs when no session is active
                showAllAttendanceLogs();
            }
            
            // Update manual entry button and status
            const manualSubmitBtn = document.querySelector('button[onclick="processManualAttendance()"]');
            const manualEntryStatus = document.getElementById('manualEntryStatus');
            if (manualSubmitBtn) {
                manualSubmitBtn.textContent = 'Submit Attendance';
                manualSubmitBtn.className = 'w-full px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded transition-all text-sm font-semibold';
            }
            if (manualEntryStatus) {
                manualEntryStatus.textContent = 'Ready for manual input';
                manualEntryStatus.className = 'text-xs text-gray-500 text-center';
            }
        }



        // Show all attendance logs (when no session is active)
        function showAllAttendanceLogs() {
            const sessionFilter = document.getElementById('sessionFilter');
            if (sessionFilter) {
                sessionFilter.classList.add('hidden');
            }
            
            const logsList = document.getElementById('attendanceLogsList');
            const rows = logsList.querySelectorAll('tr[data-session]');
            
            // Remove all session-based highlighting - show all records equally
            rows.forEach(row => {
                row.classList.add('permanent-visible');
                row.classList.remove('highlight-active-session', 'dim-inactive-session');
                
                // Also update time indicators to show neutral state
                const timeCell = row.querySelector('.time-indicator');
                if (timeCell) {
                    timeCell.classList.remove('active-session-indicator', 'inactive-session-indicator');
                }
            });
        }

        // Dim session attendance logs when session ends
        function hideSessionAttendanceLogs() {
            const sessionFilter = document.getElementById('sessionFilter');
            if (sessionFilter) {
                sessionFilter.textContent = 'Session Ended - Records Remain Visible';
                sessionFilter.className = 'text-xs px-2 py-1 rounded-full bg-orange-100 text-orange-800';
                sessionFilter.classList.remove('hidden');
            }
            
            const logsList = document.getElementById('attendanceLogsList');
            const rows = logsList.querySelectorAll('tr[data-session]');
            
            // Apply permanent visibility with inactive dimming to all records
            rows.forEach(row => {
                row.classList.add('permanent-visible', 'dim-inactive-session');
                row.classList.remove('highlight-active-session');
                
                // Update time indicators to show inactive state
                const timeCell = row.querySelector('.time-indicator');
                if (timeCell) {
                    timeCell.classList.add('inactive-session-indicator');
                    timeCell.classList.remove('active-session-indicator');
                }
            });
        }

        // Global RFID keypress listener removed (handled within initialization scope to prevent duplicates)
        
        // Update scan status helper function
        function updateScanStatus(message, type = 'normal') {
            const scanStatus = document.getElementById('scanStatus');
            const originalMessage = currentActiveSession ? 'Ready to scan - Tap your RFID card' : 'Waiting for active session to start';
            
            scanStatus.textContent = message;
            
            // Revert to original message after 3 seconds
            setTimeout(() => {
                scanStatus.textContent = originalMessage;
            }, 3000);
        }

        // Process manual attendance
        function processManualAttendance() {
            const rfidCode = document.getElementById('rfidInput').value.trim();
            const userId = document.getElementById('userIdInput').value.trim();
            
            if (!currentActiveSession) {
                // Enhanced notification for manual entry without active session
                showToast('Manual entry attempted but no active session - Entry will not be recorded', 'warning');
                updateScanStatus('No active session - Manual entry blocked');
                
                // Show visual feedback in user info card
                showUserInfo({
                    name: userId ? `User ID: ${userId}` : 'Manual Entry',
                    status: 'No active session available - Entry blocked',
                    error: true
                });
                
                // Clear the visual feedback after 4 seconds
                setTimeout(() => {
                    clearUserInfo();
                }, 4000);
                
                return;
            }
            
            if (!rfidCode && !userId) {
                showToast('Please enter RFID code or User ID', 'warning');
                return;
            }
            
            // Remove RFID length validation - let backend handle it
            
            if (userId && !isNaN(userId)) {
                // Regular numeric User ID - valid
            } else if (userId && /^\d{2}-\d{6}$/.test(userId)) {
                // YY-XXXXXX format - valid  
            } else if (userId) {
                showToast('User ID must be a number or YY-XXXXXX format (e.g., 25-123456)', 'warning');
                return;
            }
            
            processAttendance(rfidCode || null, userId || null);
            document.getElementById('rfidInput').value = '';
            document.getElementById('userIdInput').value = '';
        }

        // Ensure AppState exists
        if (typeof window.AppState === 'undefined') window.AppState = {};
        window.AppState.processing = window.AppState.processing || false;

        // Process attendance (RFID or manual) with duplicate-guard
        function processAttendance(rfidCode, userId) {
            const scanKey = `${rfidCode || ''}:${userId || ''}`;
            if (window.AppState.processing) return;
            window.AppState.processing = true;
            const _releaseProcessing = () => { setTimeout(() => { window.AppState.processing = false; }, 1200); };
            if (!currentActiveSession) {
                // More specific message for RFID vs manual entry
                const inputType = rfidCode ? 'RFID card scanned' : 'User ID entered';
                showToast(`${inputType} but no active session available`, 'error');
                updateScanStatus('No active session available', 'error');
                return;
            }

            // Show processing state
            updateScanStatus('Processing attendance...', 'processing');
            showUserInfo({
                name: 'Processing...',
                status: 'Checking attendance...',
                loading: true
            });

            // Make API call to process attendance
            fetch('<?= base_url('pederasyon/processAttendance') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    event_id: eventId,
                    rfid_code: rfidCode || '',
                    user_id: userId || '',
                    session: currentActiveSession
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    try {
                        window.AppState.lastSuccessScan = scanKey;
                        window.AppState.lastSuccessAt = Date.now();
                    } catch (e) {}
                    const user = data.data.user;
                    showUserInfo({
                        name: user.name,
                        first_name: user.first_name,
                        last_name: user.last_name,
                        middle_name: user.middle_name,
                        id: user.id,
                        user_id: user.user_id,
                        rfid_code: user.rfid_code,
                        status: data.message,
                        session: user.session,
                        time: user.time,
                        action: user.action,
                        attendanceStatus: user.attendanceStatus || user.status,
                        age: user.age,
                        sex: user.sex,
                        position: user.position,
                        user_type: user.user_type,
                        barangay: user.barangay,
                        zone_purok: user.zone_purok,
                        email: user.email,
                        phone_number: user.phone_number,
                        profile_picture: user.profile_picture
                    });
                    
                    addAttendanceLog(user);
                    refreshData(); // Refresh counts and logs
                    
                    // Enhanced success notifications with status and timeout info
                    const statusText = user.attendanceStatus === 'Late' ? ' (Late)' : ' (Present)';
                    const actionText = user.action === 'time_out' ? 'Checked Out' : 'Checked In';
                    let toastType = 'success';
                    
                    // Special handling for late entries
                    if (user.attendanceStatus === 'Late') {
                        toastType = 'warning';
                    }
                    
                    // Special handling for timeout check-outs
                    if (user.action === 'time_out' && data.message.includes('timeout')) {
                        toastType = 'info';
                        showToast(`${actionText} after 30+ minutes${statusText}`, toastType);
                    } else {
                        showToast(`${actionText} successfully${statusText}`, toastType);
                    }
                    
                    updateScanStatus('Attendance recorded successfully', 'success');
                } else {
                    // Handle specific error cases with detailed messages
                    let errorType = 'error';
                    
                    // DUPLICATE ENTRY HANDLING
                    if (data.duplicate) {
                        errorType = 'warning';
                        
                        // Show remaining time for timeout if available
                        if (data.remaining_minutes) {
                            showToast(`Duplicate entry - Wait ${data.remaining_minutes} minutes to check out`, 'warning');
                            updateScanStatus(`Wait ${data.remaining_minutes} more minutes`, 'warning');
                        } else {
                            showToast('Duplicate entry - Already scanned', 'warning');
                            updateScanStatus('Already scanned', 'warning');
                        }
                        
                        // Show duplicate entry info in user card
                        showUserInfo({
                            name: rfidCode ? `RFID: ${rfidCode}` : `User ID: ${userId}`,
                            status: data.message,
                            duplicate: true
                        });
                    } else if (data.session_status === 'pending') {
                        // Session hasn't started yet
                        errorType = 'info';
                        showToast(`${data.message} - Current time: ${data.current_time}`, 'info');
                        updateScanStatus(`⏳ Session starts at ${data.start_time}`, 'info');
                        showUserInfo({
                            name: rfidCode ? `RFID: ${rfidCode}` : `User ID: ${userId}`,
                            status: `Session starts at ${data.start_time}`,
                            pending: true
                        });
                    } else if (data.session_status === 'ended') {
                        // Session has ended
                        errorType = 'warning';
                        showToast(`${data.message} - Current time: ${data.current_time}`, 'warning');
                        updateScanStatus(`⛔ Session ended at ${data.end_time}`, 'warning');
                        showUserInfo({
                            name: rfidCode ? `RFID: ${rfidCode}` : `User ID: ${userId}`,
                            status: `Session ended at ${data.end_time}`,
                            ended: true
                        });
                    } else if (data.message.toLowerCase().includes('not found') || data.message.toLowerCase().includes('invalid')) {
                        errorType = 'warning';
                        showUserInfo({
                            name: rfidCode ? `RFID: ${rfidCode}` : `User ID: ${userId}`,
                            status: data.message,
                            error: true
                        });
                        showToast(`${data.message}`, errorType);
                        updateScanStatus(data.message, 'error');
                    } else {
                        showUserInfo({
                            name: rfidCode ? `RFID: ${rfidCode}` : `User ID: ${userId}`,
                            status: data.message,
                            error: true
                        });
                        showToast(`${data.message}`, errorType);
                        updateScanStatus(data.message, 'error');
                    }
                }
                
                // Clear user info after 30 seconds
                setTimeout(() => {
                    clearUserInfo();
                }, 30000);
            })
            .catch(error => {
                console.error('Error processing attendance:', error);
                let errorMessage = 'Network error occurred';
                
                // Handle specific network errors
                if (error.message.includes('HTTP 500')) {
                    errorMessage = 'Server error - Please try again';
                } else if (error.message.includes('HTTP 404')) {
                    errorMessage = 'Service not found - Contact administrator';
                } else if (error.message.includes('Failed to fetch')) {
                    errorMessage = 'Connection failed - Check network';
                }
                
                const lastScan = window.AppState.lastSuccessScan || null;
                const lastAt = window.AppState.lastSuccessAt || 0;
                const now = Date.now();
                if (lastScan === scanKey && (now - lastAt) < 2000) {
                    console.info('Suppressed server/network error toast due to immediate successful scan for same input');
                } else {
                    showUserInfo({
                        name: rfidCode ? `RFID: ${rfidCode}` : `User ID: ${userId}`,
                        status: errorMessage,
                        error: true
                    });
                    showToast(`${errorMessage}`, 'error');
                    updateScanStatus(`${errorMessage}`, 'error');
                }
                
                setTimeout(() => {
                    clearUserInfo();
                }, 30000);
            }).finally(() => {
                _releaseProcessing();
            });
        }

        // Show user information
        function showUserInfo(user) {
            const userInfoContent = document.getElementById('userInfoContent');
            
            if (user.loading) {
                userInfoContent.innerHTML = `
                    <div class="w-4 h-4 border-2 border-blue-500 rounded-full mx-auto mb-2"></div>
                    <h4 class="font-medium text-gray-900 mb-1 text-sm">Processing...</h4>
                    <p class="text-xs text-gray-600">${user.status || 'Checking attendance...'}</p>
                `;
                return;
            }
            
            if (user.duplicate) {
                userInfoContent.innerHTML = `
                    <div class="w-16 h-16 bg-yellow-100 rounded-full mx-auto mb-3 flex items-center justify-center">
                        <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h4 class="font-medium text-yellow-700 mb-1 text-sm">Duplicate Entry</h4>
                    <p class="text-xs text-yellow-600">${user.status}</p>
                `;
                return;
            }
            
            if (user.pending) {
                userInfoContent.innerHTML = `
                    <div class="w-16 h-16 bg-blue-100 rounded-full mx-auto mb-3 flex items-center justify-center">
                        <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h4 class="font-medium text-blue-700 mb-1 text-sm">Session Pending</h4>
                    <p class="text-xs text-blue-600">${user.status}</p>
                `;
                return;
            }
            
            if (user.ended) {
                userInfoContent.innerHTML = `
                    <div class="w-16 h-16 bg-gray-100 rounded-full mx-auto mb-3 flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.707-10.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L9.414 11H13a1 1 0 100-2H9.414l1.293-1.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h4 class="font-medium text-gray-700 mb-1 text-sm">Session Ended</h4>
                    <p class="text-xs text-gray-600">${user.status}</p>
                `;
                return;
            }
            
            if (user.error) {
                userInfoContent.innerHTML = `
                    <div class="w-16 h-16 bg-red-100 rounded-full mx-auto mb-3 flex items-center justify-center">
                        <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h4 class="font-medium text-red-600 mb-1 text-sm">Error</h4>
                    <p class="text-xs text-red-500">${user.status}</p>
                `;
                return;
            }
            
            // Success state with detailed profile information
            const statusColor = user.attendanceStatus === 'Late' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800';
            
            // Map user_type to label
            let userTypeLabel = '';
            if (user.user_type == 1) userTypeLabel = 'KK';
            else if (user.user_type == 2) userTypeLabel = 'SK Official';
            else if (user.user_type == 3) userTypeLabel = 'Pederasyon Officer | SK Chairperson';
            userInfoContent.innerHTML = `
                <!-- Profile Picture or Initial -->
                <div class="w-20 h-20 rounded-full mx-auto mb-2 flex items-center justify-center overflow-hidden shadow-md border-2 border-white">
                    ${user.profile_picture ? 
                        `<img src=\"<?= base_url('uploads/profile_pictures/') ?>${user.profile_picture}\" alt=\"${user.name}\" class=\"w-full h-full object-cover\">` :
                        `<div class=\"w-full h-full bg-blue-400 flex items-center justify-center\">
                            <svg class=\"w-6 h-6 text-white\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">
                                <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z\"></path>
                            </svg>
                         </div>`
                    }
                </div>
                
                <!-- User Name and Status -->
                <h4 class="font-bold text-gray-900 mb-1 text-xs">${user.name}</h4>
                <p class="text-xs text-green-600 font-medium mb-2">${user.action === 'time_out' ? 'Checked Out' : 'Checked In'}</p>
                
                <!-- Detailed User Information -->
                <div class="space-y-1 text-left">
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-600">Type:</span>
                        <span class="text-xs text-gray-900">${userTypeLabel}</span>
                    </div>
                    ${user.age ? `
                        <div class="flex justify-between">
                            <span class="text-xs text-gray-600">Age:</span>
                            <span class="text-xs text-gray-900">${user.age} years old</span>
                        </div>
                    ` : ''}
                    ${user.sex ? `
                        <div class="flex justify-between">
                            <span class="text-xs text-gray-600">Gender:</span>
                            <span class="text-xs text-gray-900">${user.sex}</span>
                        </div>
                    ` : ''}
                    ${user.zone_purok ? `
                        <div class="flex justify-between">
                            <span class="text-xs text-gray-600">Zone:</span>
                            <span class="text-xs text-gray-900">${user.zone_purok}</span>
                        </div>
                    ` : ''}
                    <!-- Attendance Status -->
                    <div class="flex justify-between items-center pt-1">
                        <span class="text-xs text-gray-600">Status:</span>
                        <span class="text-xs px-2 py-1 ${statusColor} rounded-full">${user.attendanceStatus || 'Present'}</span>
                    </div>
                    <!-- Session and Time Info -->
                    ${user.session && user.time ? `
                        <div class="pt-2 border-t border-gray-200">
                            <div class="text-center">
                                <span class="text-xs text-gray-500 capitalize">${user.session} Session - ${user.time}</span>
                            </div>
                        </div>
                    ` : ''}
                </div>
            `;
        }

        // Clear user info
        function clearUserInfo() {
            const userInfoContent = document.getElementById('userInfoContent');
            userInfoContent.innerHTML = `
                <div class="w-12 h-12 rounded-full mx-auto mb-2 flex items-center justify-center shadow-md bg-gray-400">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h4 class="font-medium text-gray-900 mb-1 text-xs">No User Selected</h4>
                <p class="text-xs text-gray-500 mb-2">Scan RFID to view profile</p>
                
                <div class="space-y-1 text-left">
                        <div class="flex justify-between">
                            <span class="text-xs text-gray-600">Type:</span>
                            <span class="text-xs text-gray-400">---</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-xs text-gray-600">Age:</span>
                            <span class="text-xs text-gray-400">---</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-xs text-gray-600">Gender:</span>
                            <span class="text-xs text-gray-400">---</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-xs text-gray-600">Zone:</span>
                            <span class="text-xs text-gray-400">---</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-xs text-gray-600">Status:</span>
                            <span class="text-xs text-gray-400">---</span>
                        </div>
                    </div>
                </div>
            `;
        }

        // Add attendance log entry (unified function for new and existing records)
        function addAttendanceLogEntry(user) {
            const logsList = document.getElementById('attendanceLogsList');

            // Remove "no records" and "session ended" messages when actual attendance is recorded
            // include both legacy 'no-records' and standardized 'no-records-row'
            const placeholderRows = logsList.querySelectorAll('tr.text-center, tr.no-records, tr.no-records-row, tr.session-ended-message');
            placeholderRows.forEach(row => row.remove());

            const status = user.attendanceStatus || user.status || 'Present';
            const statusColor = status === 'Late' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800';
            const actionLabel = user.action === 'time_out' ? 'Time-Out' : 'Time-In';

            // Parse time to get ISO format for data-time attribute
            let isoTime = '';
            try {
                if (user.time) {
                    // If it's already a formatted time like "2:30 PM", create a date object for today
                    if (typeof user.time === 'string' && (user.time.includes('AM') || user.time.includes('PM'))) {
                        const today = new Date();
                        const timeStr = user.time.replace(/\s?(AM|PM)/i, ' $1');
                        const tempDate = new Date(`${today.toDateString()} ${timeStr}`);
                        isoTime = tempDate.toISOString();
                    } else {
                        // Assume it's already in datetime format
                        const tempDate = new Date(user.time);
                        isoTime = tempDate.toISOString();
                    }
                }
            } catch (error) {
                console.warn('Error parsing time for filtering:', user.time, error);
                isoTime = new Date().toISOString(); // Fallback to current time
            }

            // Prefer server-provided attendance_id for stable deduplication if available
            const stableId = user.attendance_id || user.attendanceId || null;
            const fallbackId = `${user.user_id || user.id}-${user.session}-${user.action}-${isoTime}`;
            const entryId = stableId ? `aid-${stableId}` : fallbackId;

            // Check if this entry already exists to prevent duplicates
            const existingEntry = logsList.querySelector(`tr[data-entry-id="${entryId}"]`);
            if (existingEntry) {
                console.log('Duplicate entry detected, skipping:', entryId);
                return;
            }

            const logRow = document.createElement('tr');
            logRow.className = 'hover:bg-gray-50 permanent-visible';
            logRow.setAttribute('data-session', user.session);
            logRow.setAttribute('data-time', isoTime);
            logRow.setAttribute('data-entry-id', entryId);
            logRow.setAttribute('data-user-id', user.user_id || user.id);
            logRow.setAttribute('data-action', user.action);

            // Attach attendance id if present
            if (stableId) {
                logRow.setAttribute('data-attendance-id', stableId);
            }

            // Mark existing records so they are always visible
            if (user.isExisting) {
                logRow.setAttribute('data-existing-record', 'true');
            }

            // Apply session highlighting based on current active session
            if (currentActiveSession === user.session) {
                logRow.classList.add('highlight-active-session');
            } else if (currentActiveSession !== null) {
                logRow.classList.add('dim-inactive-session');
            }

            logRow.innerHTML = `
                <td class="px-2 py-1">
                    <div class="flex items-center">
                        <div class="w-6 h-6 rounded-full flex items-center justify-center mr-2 flex-shrink-0 overflow-hidden shadow-sm border border-gray-200">
                            ${user.profile_picture ? 
                                `<img src="<?= base_url('uploads/profile_pictures/') ?>${user.profile_picture}" alt="${user.name}" class="w-full h-full object-cover">` :
                                `<div class="w-full h-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                                    <span class="text-white text-[10px] font-medium">${user.name.charAt(0).toUpperCase()}</span>
                                </div>`
                            }
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="text-xs font-medium text-gray-900 truncate">${user.name}</div>
                            <div class="flex flex-row space-x-1">
                                ${user.zone_purok ? `<span class="text-xs text-gray-400">Zone ${user.zone_purok}</span>` : ''}
                                ${user.zone_purok && user.barangay ? `<span class="text-xs text-gray-300">|</span>` : ''}
                                ${user.barangay ? `<span class="text-xs text-gray-400">Brgy. ${user.barangay}</span>` : ''}
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-2 py-1">
                    <span class="inline-flex px-1 py-0.5 text-[10px] font-medium rounded-full ${statusColor}">
                        ${status}
                    </span>
                </td>
                <td class="px-2 py-1">
                    <div class="text-xs text-gray-900 time-indicator ${currentActiveSession === user.session ? 'active-session-indicator' : (currentActiveSession !== null ? 'inactive-session-indicator' : '')}">${user.time}</div>
                </td>
                <td class="px-2 py-1">
                    <span class="inline-flex px-1 py-0.5 text-[10px] font-medium rounded-full ${user.action === 'time_out' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'}">
                        ${actionLabel}
                    </span>
                </td>
            `;

            // Insert entry in chronological order based on time
            insertEntryByTime(logsList, logRow, isoTime);

            // Ensure recent taps are visible at the top: scroll container to top and add transient highlight
            try {
                // The wrapper div that scrolls is the parent of the table
                const wrapper = logsList.closest('div[style*="max-height"]');
                if (wrapper) {
                    // Scroll to top so newest entries (top) are visible
                    wrapper.scrollTop = 0;
                }
                // Add transient highlight class
                logRow.classList.add('recent-tap');
                setTimeout(() => {
                    logRow.classList.remove('recent-tap');
                }, 2500);
            } catch (err) {
                console.warn('Could not apply recent tap highlight or scroll:', err);
            }

            // Update total attendee count after adding a new log entry
            try {
                updateAttendanceCounts();
            } catch (err) {
                console.warn('Error updating attendance counts after adding entry:', err);
            }
        }

        // Insert entry so the newest tap is always at the top of the attendance log
        function insertEntryByTime(logsList, newRow, newTime) {
            try {
                const tbody = logsList.querySelector('tbody') || logsList;

                // Prepend the new row so recent taps appear first
                if (tbody.firstChild) {
                    tbody.insertBefore(newRow, tbody.firstChild);
                } else {
                    tbody.appendChild(newRow);
                }
                // After inserting, sort all rows by data-time descending (newest first)
                try {
                    const rows = Array.from(tbody.querySelectorAll('tr[data-time]'));
                    rows.sort((a, b) => {
                        const ta = new Date(a.getAttribute('data-time')).getTime() || 0;
                        const tb = new Date(b.getAttribute('data-time')).getTime() || 0;
                        return tb - ta; // descending
                    });

                    // Re-append rows in sorted order
                    rows.forEach(r => tbody.appendChild(r));
                } catch (sortErr) {
                    console.warn('Could not sort attendance rows:', sortErr);
                }
            } catch (err) {
                // Fallback to append when DOM operations fail for any reason
                try {
                    logsList.insertBefore(newRow, logsList.firstChild);
                } catch (e) {
                    logsList.appendChild(newRow);
                }
            }
        }

        // Sort entire attendance table body by data-time (datetime) descending
        function sortAttendanceByDatetimeDesc() {
            try {
                const logsList = document.getElementById('attendanceLogsList');
                const tbody = logsList.querySelector('tbody') || logsList;
                const rows = Array.from(tbody.querySelectorAll('tr[data-time]'));

                rows.sort((a, b) => {
                    const ta = new Date(a.getAttribute('data-time')).getTime() || 0;
                    const tb = new Date(b.getAttribute('data-time')).getTime() || 0;
                    return tb - ta; // newest first
                });

                rows.forEach(r => tbody.appendChild(r));
            } catch (err) {
                console.warn('Could not sort attendance table:', err);
            }
        }

        // Add attendance log (wrapper for new records)
        function addAttendanceLog(user) {
            const logsList = document.getElementById('attendanceLogsList');
            // Remove "no records" message if present
            const noRecordsRow = logsList.querySelector('tr.text-center');
            if (noRecordsRow) {
                noRecordsRow.remove();
            }
            
            // Mark as new entry for proper insertion order
            user.isNewEntry = true;
            addAttendanceLogEntry(user);
            
            // Filter entries based on active session if session is active
            filterAttendanceLogBySession();
            // Keep log sorted by datetime (newest-first)
            sortAttendanceByDatetimeDesc();
        }

        // Filter attendance log by active session
        function filterAttendanceLogBySession() {
            if (!currentActiveSession) return;

            const logsList = document.getElementById('attendanceLogsList');
            const rows = logsList.querySelectorAll('tr[data-session]');
            
            // Get session timeframes
            const sessionStart = currentActiveSession === 'morning' ? 
                attendanceSettings.start_attendance_am : 
                attendanceSettings.start_attendance_pm;
            const sessionEnd = currentActiveSession === 'morning' ? 
                attendanceSettings.end_attendance_am : 
                attendanceSettings.end_attendance_pm;
            
            rows.forEach(row => {
                const rowSession = row.getAttribute('data-session');
                const rowTimeStr = row.getAttribute('data-time');
                // Always keep rows visible; use classes to indicate state
                row.classList.add('permanent-visible');
                row.classList.remove('highlight-active-session', 'dim-inactive-session');

                // Time cell indicator (if present)
                const timeCell = row.querySelector('.time-indicator');

                // If there's no active session, keep all rows in neutral state
                if (!currentActiveSession) {
                    if (timeCell) {
                        timeCell.classList.remove('active-session-indicator', 'inactive-session-indicator');
                    }
                    return;
                }

                // Compute session start/end Date objects for comparison
                const [startHour, startMin] = sessionStart.split(':').map(n => parseInt(n, 10));
                const [endHour, endMin] = sessionEnd.split(':').map(n => parseInt(n, 10));
                const sessionStartTime = new Date();
                const sessionEndTime = new Date();
                sessionStartTime.setHours(startHour, startMin, 0, 0);
                sessionEndTime.setHours(endHour, endMin, 0, 0);

                // If row belongs to the active session and has a timestamp, highlight it when within timeframe
                if (rowSession === currentActiveSession && rowTimeStr) {
                    const rowTime = new Date(rowTimeStr);
                    if (rowTime >= sessionStartTime && rowTime <= sessionEndTime) {
                        row.classList.add('highlight-active-session');
                        if (timeCell) {
                            timeCell.classList.add('active-session-indicator');
                            timeCell.classList.remove('inactive-session-indicator');
                        }
                    } else {
                        // Same session but outside timeframe -> dim but keep visible
                        row.classList.add('dim-inactive-session');
                        if (timeCell) {
                            timeCell.classList.add('inactive-session-indicator');
                            timeCell.classList.remove('active-session-indicator');
                        }
                    }
                } else {
                    // Rows from other sessions are dimmed but still visible
                    row.classList.add('dim-inactive-session');
                    if (timeCell) {
                        timeCell.classList.add('inactive-session-indicator');
                        timeCell.classList.remove('active-session-indicator');
                    }
                }
            });
        }

        // Update attendance counts - only used for real data now
        function updateAttendanceCounts() {
            try {
                const logsList = document.getElementById('attendanceLogsList');
                if (!logsList) return;

                // Collect unique user IDs from rows (consider both data-user-id and entries inside cells)
                const rows = Array.from(logsList.querySelectorAll('tr[data-user-id]'));
                const uniqueIds = new Set();

                rows.forEach(r => {
                    const uid = r.getAttribute('data-user-id');
                    if (uid) uniqueIds.add(uid.toString());
                });

                const total = uniqueIds.size;

                const totalEl = document.getElementById('totalAttendees');
                if (totalEl) {
                    totalEl.textContent = total;
                }

                return total;
            } catch (err) {
                console.warn('updateAttendanceCounts error:', err);
            }
        }



        // Automatic timeout for session - calls the server to timeout all active users
        function autoTimeoutSession(session) {
            console.log(`Auto-timeout initiated for ${session} session`);
            
            fetch('<?= base_url('pederasyon/autoTimeoutSession') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    event_id: eventId,
                    session: session
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(`Auto-timeout complete: ${data.timeout_count} users timed out for ${session} session`, 'info');
                    refreshData(); // Refresh the attendance logs
                } else {
                    console.error('Auto-timeout failed:', data.message);
                    showToast(`Auto-timeout failed: ${data.message}`, 'warning');
                }
            })
            .catch(error => {
                console.error('Auto-timeout error:', error);
                showToast('Auto-timeout failed - Network error', 'error');
            });
        }

    // Toast notification area (we use showNotification to render toasts)



        // Use the same notification UI as `attendance.php` (top-right slide-in toasts)
        function showNotification(message, type = 'info') {
            let toastContainerEl = document.getElementById('toastContainer');
            if (!toastContainerEl) {
                toastContainerEl = document.createElement('div');
                toastContainerEl.id = 'toastContainer';
                toastContainerEl.className = 'fixed top-4 right-4 z-[100] space-y-4';
                document.body.appendChild(toastContainerEl);
            }

            const notification = document.createElement('div');
            notification.className = 'pointer-events-auto p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full min-w-[280px] max-w-md break-words';

            // Choose background color based on type (keeps simple palette)
            switch(type) {
                case 'success':
                    notification.style.background = '#ecfdf5';
                    notification.style.border = '1px solid rgba(16,185,129,0.12)';
                    break;
                case 'error':
                    notification.style.background = '#fff1f2';
                    notification.style.border = '1px solid rgba(239,68,68,0.12)';
                    break;
                case 'warning':
                    notification.style.background = '#fffbeb';
                    notification.style.border = '1px solid rgba(245,158,11,0.12)';
                    break;
                default:
                    notification.style.background = '#eff6ff';
                    notification.style.border = '1px solid rgba(59,130,246,0.12)';
            }

            // Small inline SVG icons
            let icon = '';
            switch(type) {
                case 'success':
                    icon = '<svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
                    break;
                case 'error':
                    icon = '<svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';
                    break;
                case 'warning':
                    icon = '<svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0zM12 9v4m0 4h.01"/></svg>';
                    break;
                default:
                    icon = '<svg class="w-5 h-5 text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="9" stroke-width="1.6"/><path d="M12 8h.01M11.75 11h.5v4h-.5z" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>';
            }

            notification.innerHTML = `
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0">${icon}</div>
                    <div class="flex-1 text-sm text-slate-900">${message}</div>
                    <button class="ml-3 text-slate-700 hover:opacity-90" aria-label="Close">&times;</button>
                </div>
            `;

            // Close button
            const closeBtn = notification.querySelector('button');
            closeBtn.addEventListener('click', () => {
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 260);
            });

            toastContainerEl.appendChild(notification);

            // Animate in
            setTimeout(() => notification.classList.remove('translate-x-full'), 100);

            // Auto remove after 5s (or CONFIG.TOAST_DURATION if present)
            const duration = (typeof CONFIG !== 'undefined' && CONFIG.TOAST_DURATION) ? CONFIG.TOAST_DURATION : 5000;
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 260);
            }, duration);
        }

        function showToast(message, type = 'info', category = 'default') {
            // Keep dedupe behavior from earlier implementation, then use showNotification UI
            const now = Date.now();
            const key = `${category}:${message}`;
            const cooldown = (typeof TOAST_COOLDOWNS !== 'undefined' && TOAST_COOLDOWNS[category]) ? TOAST_COOLDOWNS[category] : (typeof TOAST_COOLDOWNS !== 'undefined' ? TOAST_COOLDOWNS.default : 3000);
            const last = ToastRegistry.get(key) || 0;
            if (now - last < cooldown) return;
            ToastRegistry.set(key, now);

            // Use the attendance.php style notification
            showNotification(message, type);
        }

        // Route session-specific messages to stalk (non-intrusive) unless critical
        function showSessionToast(message, level = 'info') {
            // Levels: pending, starting, active, ended
            const importantLevels = ['starting', 'ended', 'active'];
            if (importantLevels.includes(level)) {
                // Show as toast but with session category to apply cooldown
                showToast(message, level === 'ended' ? 'info' : (level === 'active' ? 'success' : 'info'), 'session');
            } else {
                // Update persistent session stalk only
                updateSessionStalk(message, level);
            }
        }

        function updateSessionStalk(message, level = 'info') {
            const stalk = document.getElementById('sessionStalk');
            if (!stalk) return;
            stalk.textContent = message;
            stalk.classList.remove('hidden');
            // Apply subtle color hints based on level
            stalk.style.backgroundColor = level === 'info' ? '#f3f4f6' : (level === 'warning' ? '#fffbeb' : '#ecfdf5');
            stalk.style.color = level === 'warning' ? '#92400e' : '#065f46';

            // For pending messages, don't auto-hide. For others, hide after a while
            if (level !== 'pending') {
                setTimeout(() => {
                    // Only hide if the message hasn't been updated recently
                    if (stalk.textContent === message) {
                        stalk.classList.add('hidden');
                    }
                }, 8000);
            }
        }

        // Refresh attendance data - fetch latest attendance records and session status
        function refreshData() {
            const refreshBtnIcon = document.getElementById('refreshIcon');
            // Show simple loading state on icon
            if (refreshBtnIcon) {
                refreshBtnIcon.classList.add('animate-spin');
                refreshBtnIcon.style.opacity = '0.75';
            }

            // Try to fetch detailed attendance data (counts + records)
            fetch(`<?= base_url('pederasyon/getAttendanceData') ?>`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `event_id=${eventId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update table entries - to avoid duplicates, replace current rows with server data
                    if (Array.isArray(data.data)) {
                        const logsList = document.getElementById('attendanceLogsList');
                        const tbody = logsList.querySelector('tbody') || logsList;
                        // Clear existing rows before re-rendering authoritative server data
                        try { tbody.innerHTML = ''; } catch (e) { while (tbody.firstChild) tbody.removeChild(tbody.firstChild); }

                        data.data.forEach(record => {
                            // Mark as existing so entries are treated as persisted
                            record.isExisting = true;
                            addAttendanceLogEntry(record);
                        });
                    }

                    // Update counts if provided
                    if (data.counts) {
                        const totalEl = document.getElementById('totalAttendees');
                        if (totalEl && typeof data.counts.total !== 'undefined') {
                            totalEl.textContent = data.counts.total;
                        } else {
                            updateAttendanceCounts();
                        }
                    } else {
                        updateAttendanceCounts();
                    }

                    // Also update session/status via lightweight status check
                    checkAttendanceStatus();
                } else {
                    showToast(data.message || 'Failed to refresh attendance data', 'warning');
                }
            })
            .catch(error => {
                console.error('Error refreshing attendance data:', error);
                showToast('Network error while refreshing attendance data', 'error');
            })
            .finally(() => {
                if (refreshBtnIcon) {
                    refreshBtnIcon.classList.remove('animate-spin');
                    refreshBtnIcon.style.opacity = '';
                }
            });
        }

        // Update session card display with current settings
        function updateSessionCard() {
            const amSessionTimes = document.getElementById('amSessionTimes');
            const pmSessionTimes = document.getElementById('pmSessionTimes');
            
            if (amSessionTimes) {
                const amStart = attendanceSettings.start_attendance_am ? 
                    formatTimeTo12Hour(attendanceSettings.start_attendance_am) : 'Not Set';
                const amEnd = attendanceSettings.end_attendance_am ? 
                    formatTimeTo12Hour(attendanceSettings.end_attendance_am) : 'Not Set';
                amSessionTimes.textContent = `AM: ${amStart} - ${amEnd}`;
            }
            
            if (pmSessionTimes) {
                const pmStart = attendanceSettings.start_attendance_pm ? 
                    formatTimeTo12Hour(attendanceSettings.start_attendance_pm) : 'Not Set';
                const pmEnd = attendanceSettings.end_attendance_pm ? 
                    formatTimeTo12Hour(attendanceSettings.end_attendance_pm) : 'Not Set';
                pmSessionTimes.textContent = `PM: ${pmStart} - ${pmEnd}`;
            }
        }

        // Enhanced refresh attendance settings with real-time updates
        function refreshAttendanceSettings() {
            fetch('<?= base_url('pederasyon/getEventAttendanceSettings') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    event_id: eventId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.settings) {
                    const oldSettings = { ...attendanceSettings };
                    
                    // Update attendance settings
                    attendanceSettings = data.settings;
                    
                    // Check if session times were changed
                    let sessionChanged = false;
                    let changedSessionType = null;
                    
                    // Check for any changes in session times
                    if (oldSettings.start_attendance_am !== attendanceSettings.start_attendance_am ||
                        oldSettings.end_attendance_am !== attendanceSettings.end_attendance_am) {
                        sessionChanged = true;
                        changedSessionType = 'morning';
                    }
                    
                    if (oldSettings.start_attendance_pm !== attendanceSettings.start_attendance_pm ||
                        oldSettings.end_attendance_pm !== attendanceSettings.end_attendance_pm) {
                        sessionChanged = true;
                        changedSessionType = sessionChanged && changedSessionType === 'morning' ? 'both' : 'afternoon';
                    }
                    
                    if (sessionChanged) {
                        console.log(`Session time updated for ${changedSessionType} session`);
                        
                        // Show notification about session changes
                        if (changedSessionType === 'morning' && attendanceSettings.start_attendance_am && attendanceSettings.end_attendance_am) {
                            showToast(`Morning session updated: ${attendanceSettings.start_attendance_am} - ${attendanceSettings.end_attendance_am}`, 'info');
                        }
                        if (changedSessionType === 'afternoon' && attendanceSettings.start_attendance_pm && attendanceSettings.end_attendance_pm) {
                            showToast(`Afternoon session updated: ${attendanceSettings.start_attendance_pm} - ${attendanceSettings.end_attendance_pm}`, 'info');
                        }
                        if (changedSessionType === 'both') {
                            showToast('Both sessions have been updated!', 'info');
                        }
                        
                        // Update session status immediately to reflect changes
                        updateSessionStatus();
                        
                        // Update session card display
                        updateSessionCard();
                        
                        // Re-apply filtering to show/hide users based on new session times
                        filterAttendanceLogBySession();
                        
                        // Create visual update indicator
                        showSessionUpdateIndicator();
                        
                        // Force immediate server status check
                        setTimeout(() => {
                            checkAttendanceStatus();
                        }, 100);
                    }
                } else {
                    console.log('No attendance settings found or error occurred');
                }
            })
            .catch(error => {
                console.error('Error refreshing attendance settings:', error);
            });
        }

        // Function to show visual session update indicator
        function showSessionUpdateIndicator() {
            // Create a distinctive visual indicator
            const indicator = document.createElement('div');
            indicator.className = 'fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-blue-600 text-white px-8 py-4 rounded-lg shadow-lg z-50 flex items-center space-x-3';
            indicator.innerHTML = `
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <div>
                    <div class="font-medium">Session Times Updated</div>
                    <div class="text-sm text-blue-200">Refreshing attendance display...</div>
                </div>
            `;
            
            document.body.appendChild(indicator);
            
            // Remove after 2 seconds
            setTimeout(() => {
                if (indicator.parentNode) {
                    indicator.style.opacity = '0';
                    indicator.style.transform = 'translate(-50%, -50%) scale(0.9)';
                    setTimeout(() => indicator.remove(), 300);
                }
            }, 2000);
        }

        // Setup real-time session update listener
        function setupSessionUpdateListener() {
            // Listen for localStorage changes (cross-tab communication)
            window.addEventListener('storage', function(e) {
                if (e.key === 'attendance_session_update') {
                    try {
                        const updateSignal = JSON.parse(e.newValue);
                        if (updateSignal && updateSignal.event_id == eventId) {
                            console.log('Received session update signal from management interface');
                            // Immediate refresh when we get the signal
                            setTimeout(() => {
                                refreshAttendanceSettings();
                            }, 500); // Small delay to ensure backend has processed the update
                        }
                    } catch (error) {
                        console.error('Error parsing session update signal:', error);
                    }
                }
            });

            // Also listen for direct postMessage from parent window
            window.addEventListener('message', function(e) {
                if (e.origin !== window.location.origin) return;
                
                if (e.data && e.data.type === 'session_updated' && e.data.event_id == eventId) {
                    console.log('Received direct session update message');
                    setTimeout(() => {
                        refreshAttendanceSettings();
                    }, 500);
                }
            });

            // Check for pending updates on page load
            try {
                const storedUpdate = localStorage.getItem('attendance_session_update');
                if (storedUpdate) {
                    const updateSignal = JSON.parse(storedUpdate);
                    // Check if update is recent (within last 15 seconds) and for this event
                    if (updateSignal && 
                        updateSignal.event_id == eventId && 
                        (Date.now() - updateSignal.timestamp) < 15000) {
                        console.log('Found recent session update on page load');
                        setTimeout(() => {
                            refreshAttendanceSettings();
                        }, 1000);
                        // Clear the signal so it doesn't trigger again
                        localStorage.removeItem('attendance_session_update');
                    }
                }
            } catch (error) {
                console.error('Error checking for pending session updates:', error);
            }
        }

        // ==================== APPLICATION INITIALIZATION ====================
        
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize the attendance system with loading screen
            initializeAttendanceSystem();
            
            // Ensure loading state is cleared after initialization
            setTimeout(() => {
                const loadingRows = document.querySelectorAll('tr.loading-records');
                loadingRows.forEach(row => row.remove());
            }, 2000); // 2 second fallback to clear any stuck loading states
            
            // Setup session update listener for cross-tab communication
            setupSessionUpdateListener();
            
            // Add manual entry field listener for no-session feedback
            const userIdInput = document.getElementById('userIdInput');
            if (userIdInput) {
                // Add Enter key listener for manual input
                userIdInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        processManualAttendance();
                    }
                });
            }
            
            // Handle online/offline status
            window.addEventListener('online', function() {
                showToast('Connection restored', 'success');
                // Restart real-time updates if they were stopped
                startRealTimeUpdates();
            });
            
            window.addEventListener('offline', function() {
                showToast('Connection lost - Real-time updates paused', 'warning');
                // Stop real-time updates to prevent errors
                if (realTimeUpdateInterval) {
                    clearInterval(realTimeUpdateInterval);
                    realTimeUpdateInterval = null;
                }
            });
            
            // Add keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // F5 or Ctrl+R for manual refresh
                if (e.key === 'F5' || (e.ctrlKey && e.key === 'r')) {
                    e.preventDefault();
                    checkAttendanceStatus();
                }
                
                // Escape to clear current scan
                if (e.key === 'Escape') {
                    const rfidInput = document.getElementById('rfidInput');
                    const userIdInput = document.getElementById('userIdInput');
                    if (rfidInput) rfidInput.value = '';
                    if (userIdInput) userIdInput.value = '';
                    clearUserInfo();
                }
            });
            
            // Cleanup on page unload
            window.addEventListener('beforeunload', function() {
                if (realTimeUpdateInterval) {
                    clearInterval(realTimeUpdateInterval);
                }
                if (sessionStartWaitInterval) {
                    clearInterval(sessionStartWaitInterval);
                }
                if (sessionStatusInterval) {
                    clearInterval(sessionStatusInterval);
                }
                if (profileClearTimer) {
                    clearTimeout(profileClearTimer);
                }
                Object.values(autoTimeoutTimers).forEach(timer => clearTimeout(timer));
            });
            
            // Auto-refresh session status every 30 seconds (without reloading page)
            refreshInterval = setInterval(checkAttendanceStatus, 30000);
            
            // Focus on RFID input and keep it focused with smart manual entry detection
            const rfidInput = document.getElementById('rfidInput');
            let manualEntryInUse = false;
            let manualEntryTimeout = null;
            
            if (rfidInput) {
                rfidInput.focus();
                
                // Track when user starts using manual entry
                const userIdInput = document.getElementById('userIdInput');
                if (userIdInput) {
                    userIdInput.addEventListener('focus', function() {
                        manualEntryInUse = true;
                        clearTimeout(manualEntryTimeout);
                    });
                    
                    userIdInput.addEventListener('input', function() {
                        manualEntryInUse = true;
                        clearTimeout(manualEntryTimeout);
                        // Keep manual entry active for 10 seconds after last input
                        manualEntryTimeout = setTimeout(() => {
                            if (document.activeElement !== userIdInput) {
                                manualEntryInUse = false;
                            }
                        }, 10000);
                    });
                    
                    userIdInput.addEventListener('blur', function() {
                        // Delay clearing manual entry flag to avoid focus conflicts
                        clearTimeout(manualEntryTimeout);
                        manualEntryTimeout = setTimeout(() => {
                            manualEntryInUse = false;
                        }, 2000);
                    });
                }
                
                // Less aggressive RFID focus management - every 3 seconds instead of 1 second
                setInterval(() => {
                    const activeElement = document.activeElement;
                    
                    // Only auto-focus RFID if:
                    // 1. Manual entry is not in use
                    // 2. RFID input is enabled
                    // 3. No input field has focus
                    // 4. User is not actively typing anywhere
                    if (!manualEntryInUse && 
                        !rfidInput.disabled &&
                        activeElement !== userIdInput && 
                        activeElement.tagName !== 'INPUT' &&
                        activeElement.tagName !== 'TEXTAREA') {
                        rfidInput.focus();
                    }
                }, 3000);
                
                // Prevent RFID input from losing focus (unless user is using manual entry)
                rfidInput.addEventListener('blur', function() {
                    if (!this.disabled && 
                        !manualEntryInUse &&
                        document.activeElement.tagName !== 'INPUT' &&
                        document.activeElement.tagName !== 'TEXTAREA') {
                        setTimeout(() => {
                            if (!manualEntryInUse) {
                                this.focus();
                            }
                        }, 500);
                    }
                });
            }
        });

        // Handle page visibility change
        document.addEventListener('visibilitychange', function() {
            if (document.visibilityState === 'hidden') {
                clearInterval(refreshInterval);
            } else {
                // Page became visible again - reload all data
                console.log('Page became visible - reloading attendance data');
                updateCurrentTime();
                updateSessionStatus();
                
                // Reload fresh attendance data from server
                checkAttendanceStatus();
                
                // Restart refresh interval
                clearInterval(refreshInterval);
                refreshInterval = setInterval(checkAttendanceStatus, 30000);
                
                // Refocus RFID input if not disabled
                const rfidInput = document.getElementById('rfidInput');
                if (rfidInput && !rfidInput.disabled) {
                    setTimeout(() => {
                        rfidInput.focus();
                    }, 500);
                }
                
                showToast('Attendance data refreshed', 'info');
            }
        });
    </script>
</body>
</html>
