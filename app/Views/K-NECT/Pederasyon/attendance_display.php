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
        }
        .time-display {
            font-family: 'Courier New', monospace;
            font-size: 2.5rem;
            font-weight: bold;
        }
        
        .session-indicator {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .rfid-input {
            font-size: 1.2rem;
            padding: 1rem;
        }
        
        .user-info-card {
            transition: all 0.3s ease;
        }
        
        .session-active {
            border-color: #10b981;
            background-color: #f0fdf4;
        }
        
        .session-inactive {
            border-color: #e5e7eb;
            background-color: #f9fafb;
            opacity: 0.6;
        }
        
        .session-past {
            border-color: #dc2626;
            background-color: #fef2f2;
            opacity: 0.7;
        }
        
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
        }
        
        /* Simple Card Styles */
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
        }
        
        .scan-card {
            background: #3b82f6;
            color: white;
        }
        
        .scan-pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        .session-card-small {
            min-height: 100px;
        }
        
        /* Toast Notification Styles - Bottom Right with Overlap */
        .toast-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            max-width: 400px;
        }
        
        /* Modern Toast Style matching member.php */
        .notification-toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            padding: 16px;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            transform: translateX(100%);
            opacity: 0;
            max-width: 400px;
            min-width: 300px;
        }
        
        .notification-toast.show {
            transform: translateX(0);
            opacity: 1;
        }
        
        .notification-toast.success {
            background-color: #10b981;
            color: white;
        }
        
        .notification-toast.error {
            background-color: #ef4444;
            color: white;
        }
        
        .notification-toast.warning {
            background-color: #f59e0b;
            color: white;
        }
        
        .notification-toast.info {
            background-color: #3b82f6;
            color: white;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    
    <!-- Enhanced Header Section -->
    <header class="bg-white shadow border-b border-gray-200">
        <div class="max-w-full mx-auto px-6 py-6">
            <div class="flex flex-wrap items-center justify-between gap-6">
                <!-- Left: City Logo and Event Info -->
                <div class="flex items-center gap-2 flex-1 min-w-0">
                    <!-- Logos Section -->
                    <div class="flex items-center gap-2">
                        <!-- Iriga City Logo -->
                        <div class="flex-shrink-0">
                            <?php if (!empty($iriga_logo['file_path'])): ?>
                                <img src="<?= base_url($iriga_logo['file_path']) ?>" alt="<?= esc($iriga_logo['logo_name']) ?>" class="w-24 h-24 rounded-full object-cover">
                            <?php else: ?>
                                <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </div>
                        <!-- Pederasyon Logo -->
                        <div class="flex-shrink-0">
                            <?php if (!empty($pederasyon_logo['file_path'])): ?>
                                <img src="<?= base_url($pederasyon_logo['file_path']) ?>" alt="<?= esc($pederasyon_logo['logo_name']) ?>" class="w-32 h-32 rounded-full object-cover">
                            <?php else: ?>
                                <div class="w-32 h-32 bg-purple-100 rounded-full flex items-center justify-center">
                                    <svg class="w-10 h-10 text-purple-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- Event and City Info -->
                    <div class="min-w-0">
                        <h2 class="text-sm font-bold uppercase">
                            City of Iriga
                        </h2>
                        <h2 class="text-base font-bold uppercase mb-1">
                            Pederasyon ng mga Sangguniang Kabataan
                        </h2>
                        <h1 class="text-2xl font-bold text-blue-800 mb-1"><?= esc($event['title']) ?></h1>
                        <div class="flex flex-wrap gap-4 items-center">
                            <div class="text-xs text-gray-600">
                                <span class="font-semibold">Date:</span> <?= date('F d, Y', strtotime($event['start_datetime'])) ?>
                            </div>
                            <div class="text-xs text-gray-600">
                                <span class="font-semibold">Time:</span> <?= date('g:i A', strtotime($event['start_datetime'])) ?> - <?= date('g:i A', strtotime($event['end_datetime'])) ?>
                            </div>
                            <?php if (!empty($event['location'])): ?>
                            <div class="text-xs text-gray-600">
                                <span class="font-semibold">Location:</span> <?= esc($event['location']) ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <!-- Center: Current Time Card -->
                <div class="flex flex-col items-center justify-center bg-blue-50 border border-blue-200 rounded-lg px-8 py-4 shadow-sm mx-auto">
                    <div class="text-xs text-blue-700 font-semibold mb-1">Current Time</div>
                    <div class="text-3xl font-bold text-blue-700 mb-1" id="currentTime"></div>
                    <div class="text-xs text-gray-500">Manila Time (PHT)</div>
                </div>
                <!-- Right: Session Card -->
                <div class="flex flex-col items-end justify-center bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 min-w-[200px] shadow-sm">
                    <div class="flex items-center space-x-2 mb-1">
                        <div class="w-2 h-2 rounded-full" id="sessionIndicator"></div>
                        <span class="text-xs font-medium" id="sessionStatus">Waiting</span>
                    </div>
                    <div class="text-sm font-semibold text-gray-900 mb-1" id="currentSessionDisplay">No Active Session</div>
                    <div class="text-xs text-gray-500" id="amSessionTimes">
                        AM: <?= $attendance_settings['start_attendance_am'] ? date('g:i A', strtotime($attendance_settings['start_attendance_am'])) : 'Not Set' ?> - <?= $attendance_settings['end_attendance_am'] ? date('g:i A', strtotime($attendance_settings['end_attendance_am'])) : 'Not Set' ?>
                    </div>
                    <div class="text-xs text-gray-500" id="pmSessionTimes">
                        PM: <?= $attendance_settings['start_attendance_pm'] ? date('g:i A', strtotime($attendance_settings['start_attendance_pm'])) : 'Not Set' ?> - <?= $attendance_settings['end_attendance_pm'] ? date('g:i A', strtotime($attendance_settings['end_attendance_pm'])) : 'Not Set' ?>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content - Updated Layout: 20% - 40% - 40% -->
    <main class="max-w-full mx-auto px-6 py-6">
        <div class="grid grid-cols-10 gap-6">
            <!-- User Profile Card - 20% -->
            <div class="col-span-3">
                <div class="card p-3 h-full">
                    <h3 class="text-sm font-semibold text-gray-900 mb-2">User Profile</h3>
                    
                    <div id="userInfoContent" class="text-center">
                        <!-- Default State -->
                        <div class="w-12 h-12 rounded-full mx-auto mb-2 flex items-center justify-center shadow-md bg-gray-400">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h4 class="font-medium text-gray-900 mb-1 text-xs">No User Selected</h4>
                        <p class="text-xs text-gray-500 mb-2">Scan RFID to view profile</p>
                        
                        <!-- User Info Fields -->
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
                </div>
            </div>

            <!-- RFID Scan Card - 40% -->
            <div class="col-span-3">
                <div class="card scan-card p-4 h-full text-center">
                    <!-- RFID Icon -->
                    <div class="mb-2">
                        <div class="w-16 h-16 bg-white bg-opacity-20 rounded-lg mx-auto flex items-center justify-center scan-pulse">
                            <img src="https://cdn-icons-png.flaticon.com/512/12484/12484640.png" 
                                 alt="RFID Icon" 
                                 class="w-10 h-10 filter brightness-0 invert">
                        </div>
                    </div>
                    <h3 class="text-base font-bold mb-2">Scan RFID Card</h3>
                    <p class="text-xs mb-3 opacity-90" id="scanStatus">Ready to scan...</p>
                    
                    <!-- Session Status -->
                    <div class="mb-3">
                        <div id="currentSessionDisplay" class="text-xs font-semibold mb-1">No Active Session</div>
                        <div id="sessionStatus" class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-white bg-opacity-20">
                            Waiting
                        </div>
                    </div>
                    
                    <!-- Hidden RFID Input -->
                    <input type="text" id="rfidInput" 
                           class="opacity-0 absolute -top-10 left-0 w-1 h-1" 
                           autofocus>
                    
                    <!-- Manual Input -->
                                        <div class="border border-blue-200 rounded-lg p-3 bg-white">
                                            <label class="block text-xs font-semibold mb-2 text-blue-700">Manual Entry</label>
                                            <div class="space-y-2">
                                                <input type="text" id="userIdInput" 
                                                    placeholder="Enter User ID..." 
                                                    class="w-full px-3 py-2 border border-gray-300 rounded text-gray-900 placeholder-gray-400 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                                                <button onclick="processManualAttendance()" 
                                                        class="w-full px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded transition-all text-sm font-semibold">
                                                    Submit
                                                </button>
                                                <!-- Manual Entry Status Indicator -->
                                                <div id="manualEntryStatus" class="text-xs text-gray-500 text-center">
                                                    Ready for manual input
                                                </div>
                                            </div>
                                        </div>
                </div>
            </div>

            <!-- Attendance Log Card - 40% -->
            <div class="col-span-4">
                <div class="card h-full flex flex-col">
                    <div class="px-4 py-3 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <h3 class="text-base font-semibold text-gray-900">Attendance Log</h3>
                                <span id="sessionFilter" class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-800 hidden">
                                    Filtered by Active Session
                                </span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="text-xs text-gray-600">
                                    Total: <span class="font-semibold text-blue-600" id="totalAttendees">0</span>
                                </span>
                                <button onclick="refreshAttendanceSettings()" class="text-green-600 hover:text-green-800" title="Refresh session settings">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </button>
                                <button onclick="refreshData()" class="text-blue-600 hover:text-blue-800" title="Refresh attendance data">
                                    <svg id="refreshIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex-1">
                        <div style="max-height: 22rem; overflow-y: auto;">
                            <table class="min-w-full">
                                <thead class="bg-gray-50 sticky top-0">
                                    <tr>
                                        <th class="px-2 py-1 text-left text-[11px] font-medium text-gray-500 uppercase">Name</th>
                                        <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                                        <th class="px-2 py-1 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="attendanceLogsList" class="bg-white divide-y divide-gray-200">
                                    <!-- Records will be populated dynamically -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        const eventId = <?= $event['event_id'] ?>;
        const attendanceSettings = <?= json_encode($attendance_settings ?? []) ?>;
        const existingAttendanceRecords = <?= json_encode($attendance_records ?? []) ?>;
        let refreshInterval;
        let currentActiveSession = null;
        let sessionStartWaitInterval = null;
        let realTimeUpdateInterval = null;
        let lastSettingsUpdate = null;
        let autoTimeoutTimers = {};

        // Real-time update configuration
        const REAL_TIME_UPDATE_INTERVAL = 3000; // 3 seconds for precise timing
        const SESSION_CHECK_INTERVAL = 1000;    // 1 second for precise timing

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

        // Initialize real-time attendance system
        function initializeRealTimeAttendance() {
            console.log('Initializing real-time attendance system...');
            
            // Load existing records
            loadExistingAttendanceRecords();
            
            // Start real-time updates
            startRealTimeUpdates();
            
            // Update current time every second
            updateCurrentTime();
            setInterval(updateCurrentTime, 1000);
            
            // Initialize session status
            updateSessionStatus();
            
            // Initialize session card display
            updateSessionCard();
            
            console.log('Real-time attendance system initialized');
        }

        // Start real-time updates
        function startRealTimeUpdates() {
            if (realTimeUpdateInterval) {
                clearInterval(realTimeUpdateInterval);
            }
            
            realTimeUpdateInterval = setInterval(async () => {
                try {
                    await checkAttendanceStatus();
                } catch (error) {
                    console.error('Error in real-time update:', error);
                }
            }, REAL_TIME_UPDATE_INTERVAL);
            
            console.log('Real-time updates started');
        }

        // Check attendance status and update display
        async function checkAttendanceStatus() {
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
                    
                    // Update session status
                    updateSessionStatusFromData(data);
                    
                    // Update attendance records if there are new ones
                    updateAttendanceRecords(data.attendance_records);
                    
                } else {
                    console.error('Failed to get attendance status:', data.message);
                }
            } catch (error) {
                console.error('Error checking attendance status:', error);
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

        // Handle session state changes
        function handleSessionStateChange(oldSession, newSession, sessionInfo, morningStatus, afternoonStatus) {
            if (!oldSession && newSession) {
                // Session started
                showToast(`${newSession.charAt(0).toUpperCase() + newSession.slice(1)} session has started!`, 'success');
                document.getElementById('rfidInput').disabled = false;
                document.getElementById('rfidInput').focus();
                
                // Setup auto-timeout for this session
                setupSessionAutoTimeout(newSession, sessionInfo);
                
            } else if (oldSession && !newSession) {
                // Session ended
                showToast(`${oldSession.charAt(0).toUpperCase() + oldSession.slice(1)} session has ended!`, 'warning');
                document.getElementById('rfidInput').disabled = true;
                
                // Trigger auto-timeout for users who didn't check out
                triggerAutoTimeout(oldSession);
                
            } else if (oldSession && newSession && oldSession !== newSession) {
                // Session switched
                showToast(`${oldSession.charAt(0).toUpperCase() + oldSession.slice(1)} session ended, ${newSession} session started!`, 'info');
                
                // Trigger auto-timeout for old session
                triggerAutoTimeout(oldSession);
                
                // Setup auto-timeout for new session
                setupSessionAutoTimeout(newSession, sessionInfo);
            }
            
            // Check for waiting states
            if (morningStatus.status === 'waiting') {
                setupSessionWaitTimer('morning', morningStatus);
            }
            if (afternoonStatus.status === 'waiting') {
                setupSessionWaitTimer('afternoon', afternoonStatus);
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

        // Update attendance records display
        function updateAttendanceRecords(newRecords) {
            // Simple approach: clear and reload all records
            // In a production system, you might want to implement more sophisticated diffing
            const logsList = document.getElementById('attendanceLogsList');
            
            // Remove all existing attendance rows and placeholder messages
            const existingRows = logsList.querySelectorAll('tr[data-session], tr.text-center, tr.no-records, tr.session-ended-message');
            existingRows.forEach(row => row.remove());
            
            // Add updated records
            if (newRecords && newRecords.length > 0) {
                newRecords.forEach(record => {
                    addExistingAttendanceRecord(record);
                });
                updateAttendanceCounts();
            } else {
                // Only show "no records" message if no session has ended and no session is active
                if (!hasSessionsEnded() && !isAnySessionActive()) {
                    const noRecordsRow = document.createElement('tr');
                    noRecordsRow.className = 'text-center no-records';
                    noRecordsRow.innerHTML = '<td colspan="4" class="px-6 py-4 text-gray-500">No attendance records for this event yet</td>';
                    logsList.appendChild(noRecordsRow);
                }
            }
        }

        // Load existing attendance records on page load
        function loadExistingAttendanceRecords() {
            const logsList = document.getElementById('attendanceLogsList');
            
            if (existingAttendanceRecords && existingAttendanceRecords.length > 0) {
                console.log('Loading existing attendance records:', existingAttendanceRecords.length);
                
                // Clear any existing "no records" rows first
                const noRecordsRows = logsList.querySelectorAll('tr.text-center, tr.no-records');
                noRecordsRows.forEach(row => row.remove());
                
                // Add each existing record to the log
                existingAttendanceRecords.forEach(record => {
                    addExistingAttendanceRecord(record);
                });
                
                // Update counts
                updateAttendanceCounts();
            } else {
                // Only show "no records" message if this is for the current event and no session has started yet
                // Don't show if sessions have ended or if there are records from other events
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
        function addExistingAttendanceRecord(record) {
            const logsList = document.getElementById('attendanceLogsList');
            
            // Process AM session - create separate entries for time-in and time-out
            if (record['time-in_am']) {
                // Add time-in entry for AM session
                addAttendanceLogEntry({
                    user_id: record.user_id,
                    name: record.user_name || getFullName(record),
                    session: 'morning',
                    time: formatTimeDisplay(record['time-in_am']),
                    status: record.status_am || 'Present',
                    action: 'check_in',
                    rfid_code: record.rfid_code,
                    zone_purok: record.zone_purok || '',
                    attendanceStatus: record.status_am || 'Present'
                });
                
                // Add time-out entry for AM session if it exists
                if (record['time-out_am']) {
                    addAttendanceLogEntry({
                        user_id: record.user_id,
                        name: record.user_name || getFullName(record),
                        session: 'morning',
                        time: formatTimeDisplay(record['time-out_am']),
                        status: record.status_am || 'Present',
                        action: 'check_out',
                        rfid_code: record.rfid_code,
                        zone_purok: record.zone_purok || '',
                        attendanceStatus: record.status_am || 'Present'
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
                    action: 'check_in',
                    rfid_code: record.rfid_code,
                    zone_purok: record.zone_purok || '',
                    attendanceStatus: record.status_pm || 'Present'
                });
                
                // Add time-out entry for PM session if it exists
                if (record['time-out_pm']) {
                    addAttendanceLogEntry({
                        user_id: record.user_id,
                        name: record.user_name || getFullName(record),
                        session: 'afternoon',
                        time: formatTimeDisplay(record['time-out_pm']),
                        status: record.status_pm || 'Present',
                        action: 'check_out',
                        rfid_code: record.rfid_code,
                        zone_purok: record.zone_purok || '',
                        attendanceStatus: record.status_pm || 'Present'
                    });
                }
            }
        }

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
            if (sessionStartWaitInterval) {
                clearInterval(sessionStartWaitInterval);
            }
            
            const formattedStartTime = formatTimeTo12Hour(startTime);
            showToast(`Waiting for ${session} session to start at ${formattedStartTime}`, 'info');
            
            sessionStartWaitInterval = setInterval(() => {
                const currentTime = getCurrentTime24HourForComparison();
                
                if (currentTime >= startTime) {
                    clearInterval(sessionStartWaitInterval);
                    sessionStartWaitInterval = null;
                    showToast(`${session.charAt(0).toUpperCase() + session.slice(1)} session has started!`, 'success');
                    updateSessionStatus();
                }
            }, 1000);
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
            const sessionIndicator = document.getElementById('sessionIndicator');
            const sessionStatus = document.getElementById('sessionStatus');
            const currentSessionDisplay = document.getElementById('currentSessionDisplay');
            const scanStatus = document.getElementById('scanStatus');
            
            if (activeSessionInfo && activeSessionInfo.active) {
                // Active session
                const sessionName = activeSessionInfo.message || `${currentActiveSession.charAt(0).toUpperCase() + currentActiveSession.slice(1)} Session`;
                currentSessionDisplay.textContent = sessionName;
                sessionStatus.textContent = 'Active';
                sessionStatus.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-500 bg-opacity-100 text-white';
                sessionIndicator.className = 'w-3 h-3 rounded-full bg-green-500 session-indicator';
                scanStatus.textContent = 'Ready to scan - Tap your RFID card';
                
                // Enable attendance input
                document.getElementById('rfidInput').disabled = false;
                document.getElementById('userIdInput').disabled = false;
                
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
                    
                    // Disable RFID input but keep manual entry enabled
                    document.getElementById('rfidInput').disabled = true;
                    document.getElementById('userIdInput').disabled = false;
                    
                } else {
                    // No sessions
                    currentSessionDisplay.textContent = 'No Active Session';
                    sessionStatus.textContent = 'Waiting';
                    sessionStatus.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs bg-white bg-opacity-20';
                    sessionIndicator.className = 'w-3 h-3 rounded-full bg-gray-400';
                    scanStatus.textContent = 'No session scheduled';
                    
                    // Keep RFID disabled, but allow manual entry
                    document.getElementById('rfidInput').disabled = true;
                    document.getElementById('userIdInput').disabled = false;
                    
                    // Show all attendance logs when no session is active
                    showAllAttendanceLogs();
                }
            }
            
            // Update manual entry button status
            const manualSubmitBtn = document.querySelector('button[onclick="processManualAttendance()"]');
            const manualEntryStatus = document.getElementById('manualEntryStatus');
            if (manualSubmitBtn) {
                manualSubmitBtn.textContent = 'Submit';
                manualSubmitBtn.className = 'w-full px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded transition-all text-sm font-semibold';
            }
            if (manualEntryStatus) {
                manualEntryStatus.textContent = 'Ready for manual input';
                manualEntryStatus.className = 'text-xs text-gray-500 text-center';
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
                manualSubmitBtn.textContent = 'Submit';
                manualSubmitBtn.className = 'w-full px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded transition-all text-sm font-semibold';
            }
            if (manualEntryStatus) {
                manualEntryStatus.textContent = 'Ready for manual input';
                manualEntryStatus.className = 'text-xs text-gray-500 text-center';
            }
        }

        // Filter attendance log by active session - only show users who tapped within session timeframe
        function filterAttendanceLogBySession() {
            const sessionFilter = document.getElementById('sessionFilter');
            
            if (!currentActiveSession) {
                if (sessionFilter) {
                    sessionFilter.classList.add('hidden');
                }
                return;
            }

            // Show session filter indicator
            if (sessionFilter) {
                sessionFilter.textContent = `Showing ${currentActiveSession.charAt(0).toUpperCase() + currentActiveSession.slice(1)} Session Only`;
                sessionFilter.className = 'text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-800';
                sessionFilter.classList.remove('hidden');
            }

            const logsList = document.getElementById('attendanceLogsList');
            const rows = logsList.querySelectorAll('tr[data-session]');
            
            // Get session timeframes
            const sessionStart = currentActiveSession === 'morning' ? 
                attendanceSettings.start_attendance_am : 
                attendanceSettings.start_attendance_pm;
            const sessionEnd = currentActiveSession === 'morning' ? 
                attendanceSettings.end_attendance_am : 
                attendanceSettings.end_attendance_pm;
            
            let visibleCount = 0;
            
            rows.forEach(row => {
                const rowSession = row.getAttribute('data-session');
                const rowTimeStr = row.getAttribute('data-time');
                
                // Only show entries from current active session that are within the exact timeframe
                if (rowSession === currentActiveSession && rowTimeStr) {
                    try {
                        // Parse the attendance time
                        const attendanceTime = new Date(rowTimeStr);
                        const attendanceTimeStr = getCurrentTime24HourForComparison(); // HH:MM for comparison
                        
                        // Use our enhanced time comparison function
                        const startComparison = compareTimeStrings(attendanceTimeStr, sessionStart);
                        const endComparison = compareTimeStrings(attendanceTimeStr, sessionEnd);
                        
                        // Show only if attendance time is within session bounds (start_time <= attendance_time <= end_time)
                        if (startComparison.isAtOrAfter && endComparison.isAtOrBefore) {
                            row.style.display = '';
                            visibleCount++;
                        } else {
                            row.style.display = 'none';
                        }
                    } catch (error) {
                        console.error('Error parsing attendance time:', rowTimeStr, error);
                        row.style.display = 'none';
                    }
                } else {
                    // Hide entries from other sessions when a session is active
                    row.style.display = 'none';
                }
            });
            
            // If no records are visible in the active session, show a message
            if (visibleCount === 0) {
                const existingMessage = logsList.querySelector('.no-session-records');
                if (!existingMessage) {
                    const noRecordsMessage = document.createElement('tr');
                    noRecordsMessage.className = 'text-center no-session-records';
                    noRecordsMessage.innerHTML = `
                        <td colspan="4" class="px-3 py-6 text-gray-500">
                            <svg class="mx-auto h-6 w-6 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            No attendance records for this session timeframe yet<br>
                            <small class="text-xs text-gray-400">${sessionStart} - ${sessionEnd}</small>
                        </td>
                    `;
                    logsList.appendChild(noRecordsMessage);
                }
            } else {
                // Remove the no-records message if it exists
                const existingMessage = logsList.querySelector('.no-session-records');
                if (existingMessage) {
                    existingMessage.remove();
                }
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
            
            rows.forEach(row => {
                row.style.display = '';
            });
        }

        // Hide session attendance logs when session ends
        function hideSessionAttendanceLogs() {
            const sessionFilter = document.getElementById('sessionFilter');
            if (sessionFilter) {
                sessionFilter.textContent = 'Session Ended - Live View Hidden';
                sessionFilter.className = 'text-xs px-2 py-1 rounded-full bg-red-100 text-red-800';
                sessionFilter.classList.remove('hidden');
            }
            
            const logsList = document.getElementById('attendanceLogsList');
            
            // Remove any "no records" messages first since session has ended
            const noRecordsRows = logsList.querySelectorAll('tr.text-center, tr.no-records');
            noRecordsRows.forEach(row => row.remove());
            
            // Hide all live attendance entries but keep them in DOM for record keeping
            const rows = logsList.querySelectorAll('tr[data-session]');
            rows.forEach(row => {
                row.style.display = 'none';
            });
            
            // Don't show any session ended messages - just keep it clean
        }

        // Process RFID input
        document.getElementById('rfidInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const rfidCode = this.value.trim();
                const userId = document.getElementById('userIdInput').value.trim();
                
                if (!currentActiveSession) {
                    // Enhanced notification for RFID scan without active session
                    showToast('RFID card detected but no active session - Please wait for session to start', 'warning');
                    updateScanStatus('No active session - Card detected but cannot process');
                    
                    // Show visual feedback in user info card
                    showUserInfo({
                        name: `RFID: ${rfidCode}`,
                        status: 'No active session available',
                        error: true
                    });
                    
                    // Clear the visual feedback after 4 seconds
                    setTimeout(() => {
                        clearUserInfo();
                    }, 4000);
                    
                    this.value = ''; // Clear the input
                    return;
                }
                
                if (rfidCode) {
                    // Update scan status
                    const scanStatus = document.getElementById('scanStatus');
                    scanStatus.textContent = 'Processing RFID...';
                    
                    processAttendance(rfidCode, userId || null);
                    this.value = '';
                    document.getElementById('userIdInput').value = '';
                } else {
                    showToast('Please scan an RFID card or enter User ID', 'warning');
                    updateScanStatus('Invalid input - Please try again');
                }
            }
        });
        
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
                showToast('Please enter either RFID code or User ID', 'warning');
                return;
            }
            
            if (rfidCode && rfidCode.length < 3) {
                showToast('RFID code too short - Please scan again', 'warning');
                return;
            }
            
            if (userId && isNaN(userId)) {
                showToast('User ID must be a number', 'warning');
                return;
            }
            
            processAttendance(rfidCode || null, userId || null);
            document.getElementById('rfidInput').value = '';
            document.getElementById('userIdInput').value = '';
        }

        // Process attendance (RFID or manual)
        function processAttendance(rfidCode, userId) {
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
                    const actionText = user.action === 'check_out' ? 'Checked Out' : 'Checked In';
                    let toastType = 'success';
                    
                    // Special handling for late entries
                    if (user.attendanceStatus === 'Late') {
                        toastType = 'warning';
                    }
                    
                    // Special handling for timeout check-outs
                    if (user.action === 'check_out' && data.message.includes('timeout')) {
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
                            updateScanStatus(`⏱️ Wait ${data.remaining_minutes} more minutes`, 'warning');
                        } else {
                            showToast('Duplicate entry - Already scanned', 'warning');
                            updateScanStatus('⚠️ Already scanned', 'warning');
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
                
                // Clear user info after 5 seconds
                setTimeout(() => {
                    clearUserInfo();
                }, 5000);
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
                
                showUserInfo({
                    name: rfidCode ? `RFID: ${rfidCode}` : `User ID: ${userId}`,
                    status: errorMessage,
                    error: true
                });
                showToast(`${errorMessage}`, 'error');
                updateScanStatus(`${errorMessage}`, 'error');
                
                setTimeout(() => {
                    clearUserInfo();
                }, 5000);
            });
        }

        // Show user information
        function showUserInfo(user) {
            const userInfoContent = document.getElementById('userInfoContent');
            
            if (user.loading) {
                userInfoContent.innerHTML = `
                    <div class="animate-spin w-4 h-4 border-2 border-blue-500 border-t-transparent rounded-full mx-auto mb-2"></div>
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
            else if (user.user_type == 3) userTypeLabel = 'Pederasyon Officer';
            userInfoContent.innerHTML = `
                <!-- Profile Picture or Initial -->
                <div class="w-12 h-12 rounded-full mx-auto mb-2 flex items-center justify-center overflow-hidden shadow-md border-2 border-white">
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
                <p class="text-xs text-green-600 font-medium mb-2">${user.action === 'check_out' ? 'Checked Out' : 'Checked In'}</p>
                
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
            const placeholderRows = logsList.querySelectorAll('tr.text-center, tr.no-records, tr.session-ended-message');
            placeholderRows.forEach(row => row.remove());
            
            const status = user.attendanceStatus || user.status || 'Present';
            const statusColor = status === 'Late' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800';
            const actionLabel = user.action === 'check_out' ? 'Time-Out' : 'Time-In';
            
            // Parse time to get ISO format for data-time attribute
            let isoTime = '';
            try {
                if (user.time) {
                    // If it's already a formatted time like "2:30 PM", create a date object for today
                    if (user.time.includes('AM') || user.time.includes('PM')) {
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
            
            // Create unique identifier for this entry to prevent duplicates
            const entryId = `${user.user_id || user.id}-${user.session}-${user.action}-${isoTime}`;
            
            // Check if this entry already exists to prevent duplicates
            const existingEntry = logsList.querySelector(`tr[data-entry-id="${entryId}"]`);
            if (existingEntry) {
                console.log('Duplicate entry detected, skipping:', entryId);
                return;
            }
            
            const logRow = document.createElement('tr');
            logRow.className = 'hover:bg-gray-50';
            logRow.setAttribute('data-session', user.session);
            logRow.setAttribute('data-time', isoTime);
            logRow.setAttribute('data-entry-id', entryId);
            logRow.setAttribute('data-user-id', user.user_id || user.id);
            logRow.setAttribute('data-action', user.action);
            logRow.innerHTML = `
                <td class="px-2 py-1">
                    <div class="flex items-center">
                        <div class="w-5 h-5 bg-blue-500 rounded-full flex items-center justify-center mr-2 flex-shrink-0">
                            <span class="text-white text-xs font-medium">${user.name.charAt(0).toUpperCase()}</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="text-xs font-medium text-gray-900 truncate">${user.name}</div>
                            ${user.zone_purok ? `<div class="text-xs text-gray-400">Zone ${user.zone_purok}</div>` : ''}
                        </div>
                    </div>
                </td>
                <td class="px-2 py-1">
                    <span class="inline-flex px-1 py-0.5 text-[10px] font-medium rounded-full ${statusColor}">
                        ${status}
                    </span>
                </td>
                <td class="px-2 py-1">
                    <div class="text-xs text-gray-900">${user.time}</div>
                </td>
                <td class="px-2 py-1">
                    <span class="inline-flex px-1 py-0.5 text-[10px] font-medium rounded-full ${user.action === 'check_out' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'}">
                        ${actionLabel}
                    </span>
                </td>
            `;
            
            // Insert entry in chronological order based on time
            insertEntryByTime(logsList, logRow, isoTime);
        }

        // Insert entry in chronological order based on time
        function insertEntryByTime(logsList, newRow, newTime) {
            const newTimeMs = new Date(newTime).getTime();
            const newAction = newRow.getAttribute('data-action');
            const existingRows = Array.from(logsList.querySelectorAll('tr[data-time]'));
            
            // If no existing rows, just append
            if (existingRows.length === 0) {
                logsList.appendChild(newRow);
                return;
            }
            
            // Find the correct position to insert (newest first - descending order)
            let insertPosition = null;
            
            for (let i = 0; i < existingRows.length; i++) {
                const rowTime = existingRows[i].getAttribute('data-time');
                const rowTimeMs = new Date(rowTime).getTime();
                const rowAction = existingRows[i].getAttribute('data-action');
                
                // If new time is newer (greater), insert before this row
                if (newTimeMs > rowTimeMs) {
                    insertPosition = existingRows[i];
                    break;
                }
                
                // If same time, sort by action (check_in before check_out)
                if (newTimeMs === rowTimeMs) {
                    if (newAction === 'check_in' && rowAction === 'check_out') {
                        insertPosition = existingRows[i];
                        break;
                    }
                }
            }
            
            // If no position found, append at the end (oldest)
            if (insertPosition) {
                logsList.insertBefore(newRow, insertPosition);
            } else {
                logsList.appendChild(newRow);
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
                
                // Show only entries from current active session within timeframe
                if (rowSession === currentActiveSession && rowTimeStr) {
                    const rowTime = new Date(rowTimeStr);
                    const sessionStartTime = new Date();
                    const sessionEndTime = new Date();
                    
                    const [startHour, startMin] = sessionStart.split(':');
                    const [endHour, endMin] = sessionEnd.split(':');
                    
                    sessionStartTime.setHours(startHour, startMin, 0, 0);
                    sessionEndTime.setHours(endHour, endMin, 0, 0);
                    
                    if (rowTime >= sessionStartTime && rowTime <= sessionEndTime) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                } else if (rowSession !== currentActiveSession) {
                    // Hide entries from other sessions when a session is active
                    row.style.display = 'none';
                } else {
                    row.style.display = '';
                }
            });
        }

        // Update attendance counts - only used for real data now
        function updateAttendanceCounts() {
            // This function is now handled by refreshData()
            // keeping it for backward compatibility
        }

        // Show user feedback
        function showUserFeedback(message, type) {
            const inputStatus = document.getElementById('inputStatus');
            const color = type === 'error' ? 'text-red-600' : 'text-green-600';
            inputStatus.innerHTML = `<p class="text-sm ${color}">${message}</p>`;
            
            setTimeout(() => {
                if (currentActiveSession) {
                    inputStatus.innerHTML = '<p class="text-sm text-green-600 font-medium">Ready for attendance input</p>';
                } else {
                    inputStatus.innerHTML = '<p class="text-sm text-gray-600">Waiting for active session</p>';
                }
            }, 3000);
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

        // Enhanced Toast notification system matching member.php style
        function showToast(message, type = 'success') {
            // Remove any existing toast first (overlap behavior)
            const existingToast = document.querySelector('.notification-toast');
            if (existingToast) {
                existingToast.remove();
            }
            
            // Create new toast element
            const toast = document.createElement('div');
            toast.className = `notification-toast ${type}`;
            
            // Get appropriate icon based on type
            let icon = '';
            switch(type) {
                case 'success':
                    icon = '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>';
                    break;
                case 'error':
                    icon = '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z" /></svg>';
                    break;
                case 'warning':
                    icon = '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z" /></svg>';
                    break;
                case 'info':
                default:
                    icon = '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01" /></svg>';
                    break;
            }
            
            toast.innerHTML = `
                <div class="flex items-center">
                    ${icon}
                    <span class="mr-2 flex-1">${message}</span>
                    <button onclick="removeToast(this)" class="ml-2 text-white hover:text-gray-200 focus:outline-none">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            // Animate in
            setTimeout(() => {
                toast.classList.add('show');
            }, 100);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                removeToast(toast);
            }, 5000);
        }

        function removeToast(toastElement) {
            // Handle both button click (this = button) and direct element
            if (typeof toastElement === 'object' && toastElement.closest) {
                toastElement = toastElement.closest('.notification-toast');
            }
            
            if (toastElement && toastElement.classList.contains('notification-toast')) {
                toastElement.classList.remove('show');
                setTimeout(() => {
                    if (toastElement.parentElement) {
                        toastElement.remove();
                    }
                }, 300);
            }
        }

        // Refresh attendance data - reload the page for a full refresh
        function refreshData() {
            window.location.reload();
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
                <svg class="w-6 h-6 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize the real-time attendance system
            initializeRealTimeAttendance();
            
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
                Object.values(autoTimeoutTimers).forEach(timer => clearTimeout(timer));
            });
            
            // Auto-refresh data every 30 seconds
            refreshInterval = setInterval(refreshData, 30000);
            
            // Focus on RFID input and keep it focused
            const rfidInput = document.getElementById('rfidInput');
            if (rfidInput) {
                rfidInput.focus();
                
                // Keep RFID input focused at all times
                setInterval(() => {
                    if (document.activeElement !== rfidInput && !rfidInput.disabled) {
                        rfidInput.focus();
                    }
                }, 1000);
                
                // Prevent RFID input from losing focus
                rfidInput.addEventListener('blur', function() {
                    if (!this.disabled) {
                        setTimeout(() => {
                            this.focus();
                        }, 100);
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
                refreshData();
                
                // Restart refresh interval
                clearInterval(refreshInterval);
                refreshInterval = setInterval(refreshData, 30000);
                
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
