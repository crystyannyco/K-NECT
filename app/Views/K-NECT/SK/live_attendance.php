<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Attendance - <?= esc($event['title']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        /* Refresh animation */
        .refresh-animation {
            animation: spin 0.5s linear;
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        /* Pulse animation for new entries */
        .new-entry {
            animation: pulse 2s;
        }
        
        @keyframes pulse {
            0% { background-color: rgba(34, 197, 94, 0.1); }
            50% { background-color: rgba(34, 197, 94, 0.3); }
            100% { background-color: transparent; }
        }
        
        /* Session indicator pulse */
        .session-indicator {
            animation: pulse 2s infinite;
        }
        
        /* Card styles matching existing system */
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    
    <!-- Enhanced Header Section matching attendance_display.php -->
    <header class="bg-white shadow border-b border-gray-200">
        <div class="max-w-full mx-auto px-6 py-6">
            <div class="flex flex-wrap items-center justify-between gap-6">
                <!-- Left: Barangay Logo and Event Info -->
                <div class="flex items-center gap-2 flex-1 min-w-0">
                    <!-- Logos Section -->
                    <div class="flex items-center gap-2">
                        <!-- SK Logo -->
                        <div class="flex-shrink-0">
                            <?php if (!empty($sk_logo['file_path'])): ?>
                                <img src="<?= base_url($sk_logo['file_path']) ?>" alt="<?= esc($sk_logo['logo_name']) ?>" class="w-32 h-32 rounded-full object-cover">
                            <?php else: ?>
                                <div class="w-32 h-32 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-10 h-10 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- Event and Barangay Info -->
                    <div class="min-w-0">
                        <h2 class="text-sm font-bold uppercase">
                            Barangay <?= esc($barangay_name) ?>
                        </h2>
                        <h2 class="text-base font-bold uppercase mb-1">
                            Sanguniang Kabataan ng Barangay <?= esc($barangay_name) ?>
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
                <!-- Right: Session Status & Controls -->
                <div class="flex flex-col items-end justify-center bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 min-w-[250px] shadow-sm">
                    <!-- Live Monitoring Status -->
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 bg-green-400 rounded-full session-indicator"></div>
                        <span class="text-sm font-semibold text-green-700">Live Monitoring</span>
                    </div>
                    
                    <!-- Session Status -->
                    <div class="flex items-center space-x-2 mb-1">
                        <span class="text-xs font-medium" id="sessionStatus">Checking...</span>
                    </div>
                    
                    <!-- Current Active Session Time -->
                    <div class="text-xs text-gray-500 mb-2" id="currentSessionTime">
                        Checking session...
                    </div>
                    
                    <!-- Controls -->
                    <div class="flex space-x-2">
                        <button id="refreshBtn" onclick="refreshData()" class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-xs leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Refresh
                        </button>
                        <button onclick="window.close()" class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-xs leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-full mx-auto px-6 py-4">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="card p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <dt class="text-sm font-medium text-gray-500">Total Attendees</dt>
                        <dd id="totalAttendees" class="text-lg font-semibold text-gray-900">0</dd>
                    </div>
                </div>
            </div>

            <div class="card p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <dt class="text-sm font-medium text-gray-500">Present</dt>
                        <dd id="presentCount" class="text-lg font-semibold text-gray-900">0</dd>
                    </div>
                </div>
            </div>

            <div class="card p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <dt class="text-sm font-medium text-gray-500">Late</dt>
                        <dd id="lateCount" class="text-lg font-semibold text-gray-900">0</dd>
                    </div>
                </div>
            </div>

            <div class="card p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-gray-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                        <dd id="lastUpdated" class="text-sm font-semibold text-gray-900">--:--</dd>
                    </div>
                </div>
            </div>
        </div>

        <!-- Live Attendance Table -->
        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Recent Attendance Activity</h3>
                <div class="text-xs text-gray-500">Updates every 3 seconds</div>
            </div>
            
            <div class="overflow-hidden rounded-lg border border-gray-200">
                <div style="max-height: 500px; overflow-y: auto;">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No.</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">KK Number</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Session</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time IN</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time OUT</th>
                            </tr>
                        </thead>
                        <tbody id="attendanceTableBody" class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-lg">Waiting for attendance activity...</span>
                                        <span class="text-sm text-gray-400 mt-1">Data refreshes every 3 seconds</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <script>
        const eventId = <?= $event['event_id'] ?>;
        const attendanceSettings = <?= json_encode($attendance_settings ?? []) ?>;
        let liveAttendanceInterval = null;
        let lastUpdateTime = null;
        let previousDataHash = '';
        let currentActiveSession = null;

        // Start live monitoring when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Update current time
            updateCurrentTime();
            setInterval(updateCurrentTime, 1000);
            
            // Start session status monitoring
            updateSessionStatus();
            setInterval(updateSessionStatus, 30000); // Check every 30 seconds
            
            // Start live attendance monitoring
            startLiveAttendanceMonitoring();
        });

        function updateCurrentTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', { 
                hour12: true, 
                hour: '2-digit', 
                minute: '2-digit',
                second: '2-digit'
            });
            document.getElementById('currentTime').textContent = timeString;
        }

        function startLiveAttendanceMonitoring() {
            // Initial load
            loadLiveAttendanceData();
            
            // Set up periodic refresh every 3 seconds
            if (liveAttendanceInterval) {
                clearInterval(liveAttendanceInterval);
            }
            
            liveAttendanceInterval = setInterval(() => {
                loadLiveAttendanceData();
            }, 3000);
        }

        function loadLiveAttendanceData() {
            fetch(`<?= base_url('sk/getAttendanceData') ?>`, {
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
                    updateLiveAttendanceTable(data.data || []);
                    updateAttendanceCounts(data.counts || {});
                    updateLastUpdatedTime();
                } else {
                    console.error('Failed to load live attendance data:', data.message);
                }
            })
            .catch(error => {
                console.error('Error loading live attendance data:', error);
            });
        }

        function updateLiveAttendanceTable(attendanceData) {
            const tableBody = document.getElementById('attendanceTableBody');
            
            if (!attendanceData || attendanceData.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-lg">Waiting for attendance activity...</span>
                                <span class="text-sm text-gray-400 mt-1">Data refreshes every 3 seconds</span>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }
            
            // Create data hash to detect changes
            const dataHash = JSON.stringify(attendanceData);
            const hasNewData = dataHash !== previousDataHash;
            previousDataHash = dataHash;
            
            // Sort by most recent first
            const sortedData = [...attendanceData].sort((a, b) => {
                const timeA = new Date(a.time || a['time-in_am'] || a['time-in_pm']).getTime();
                const timeB = new Date(b.time || b['time-in_am'] || b['time-in_pm']).getTime();
                return timeB - timeA;
            });
            
            // Only show last 100 entries for performance
            const recentData = sortedData.slice(0, 100);
            
            tableBody.innerHTML = recentData.map((record, index) => {
                const time = record.time ? 
                    new Date(record.time).toLocaleTimeString('en-US', { 
                        hour12: true, 
                        hour: '2-digit', 
                        minute: '2-digit',
                        second: '2-digit'
                    }) : 
                    (record['time-in_am'] ? 
                        new Date(record['time-in_am']).toLocaleTimeString('en-US', { 
                            hour12: true, 
                            hour: '2-digit', 
                            minute: '2-digit',
                            second: '2-digit' 
                        }) : 
                        (record['time-in_pm'] ? 
                            new Date(record['time-in_pm']).toLocaleTimeString('en-US', { 
                                hour12: true, 
                                hour: '2-digit', 
                                minute: '2-digit',
                                second: '2-digit' 
                            }) : 'N/A')
                    );
                    
                const session = record.session || 
                    (record['time-in_am'] ? 'Morning' : 
                    (record['time-in_pm'] ? 'Afternoon' : 'Unknown'));
                    
                const status = record.status || record.status_am || record.status_pm || 'Present';
                const kkNumber = record.permanent_user_id || record.user_id || 'N/A';
                
                // Get time-in and time-out values
                const timeIn = record['time-in_am'] || record['time-in_pm'] || record.time_in || '';
                const timeOut = record['time-out_am'] || record['time-out_pm'] || record.time_out || '';
                
                const timeInFormatted = timeIn ? new Date(timeIn).toLocaleTimeString('en-US', { 
                    hour12: true, 
                    hour: '2-digit', 
                    minute: '2-digit'
                }) : '--:--';
                
                const timeOutFormatted = timeOut ? new Date(timeOut).toLocaleTimeString('en-US', { 
                    hour12: true, 
                    hour: '2-digit', 
                    minute: '2-digit'
                }) : '--:--';
                
                const statusClass = status === 'Present' ? 'bg-green-100 text-green-800' : 
                                   status === 'Late' ? 'bg-yellow-100 text-yellow-800' : 
                                   'bg-gray-100 text-gray-800';
                
                // Add new-entry class to first 3 entries if there's new data
                const rowClass = hasNewData && index < 3 ? 'new-entry' : '';
                
                return `
                    <tr class="hover:bg-gray-50 ${rowClass}">
                        <td class="px-3 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${index + 1}</td>
                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 font-mono">${kkNumber}</td>
                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">${record.user_name || record.name || 'Unknown'}</td>
                        <td class="px-3 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full ${statusClass}">
                                ${status}
                            </span>
                        </td>
                        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">${session}</td>
                        <td class="px-3 py-4 whitespace-nowrap text-sm font-mono text-gray-900">${timeInFormatted}</td>
                        <td class="px-3 py-4 whitespace-nowrap text-sm font-mono text-gray-900">${timeOutFormatted}</td>
                    </tr>
                `;
            }).join('');
        }

        function updateAttendanceCounts(counts) {
            document.getElementById('totalAttendees').textContent = counts.total || 0;
            document.getElementById('presentCount').textContent = counts.present || 0;
            document.getElementById('lateCount').textContent = counts.late || 0;
        }

        function updateLastUpdatedTime() {
            const now = new Date();
            document.getElementById('lastUpdated').textContent = now.toLocaleTimeString('en-US', { 
                hour12: true, 
                hour: '2-digit', 
                minute: '2-digit'
            });
        }

        function refreshData() {
            // Refresh the entire page
            window.location.reload();
        }

        // Session Status Functions
        function updateSessionStatus() {
            const now = new Date();
            const currentTime = getCurrentTime24HourForComparison();
            currentActiveSession = null;
            
            const sessionStatus = document.getElementById('sessionStatus');
            const currentSessionTime = document.getElementById('currentSessionTime');
            
            let statusText = 'No Active Session';
            let sessionTimeText = 'No active attendance session';
            
            // Check morning session
            if (attendanceSettings.start_attendance_am && attendanceSettings.end_attendance_am) {
                const startTime = attendanceSettings.start_attendance_am;
                const endTime = attendanceSettings.end_attendance_am;
                
                if (isTimeInRange(currentTime, startTime, endTime)) {
                    currentActiveSession = 'morning';
                    statusText = 'Morning Session Active';
                    const startFormatted = new Date(`2000-01-01 ${startTime}`).toLocaleTimeString('en-US', { 
                        hour12: true, 
                        hour: '2-digit', 
                        minute: '2-digit'
                    });
                    const endFormatted = new Date(`2000-01-01 ${endTime}`).toLocaleTimeString('en-US', { 
                        hour12: true, 
                        hour: '2-digit', 
                        minute: '2-digit'
                    });
                    sessionTimeText = `${startFormatted} - ${endFormatted}`;
                }
            }
            
            // Check afternoon session (only if morning is not active)
            if (!currentActiveSession && attendanceSettings.start_attendance_pm && attendanceSettings.end_attendance_pm) {
                const startTime = attendanceSettings.start_attendance_pm;
                const endTime = attendanceSettings.end_attendance_pm;
                
                if (isTimeInRange(currentTime, startTime, endTime)) {
                    currentActiveSession = 'afternoon';
                    statusText = 'Afternoon Session Active';
                    const startFormatted = new Date(`2000-01-01 ${startTime}`).toLocaleTimeString('en-US', { 
                        hour12: true, 
                        hour: '2-digit', 
                        minute: '2-digit'
                    });
                    const endFormatted = new Date(`2000-01-01 ${endTime}`).toLocaleTimeString('en-US', { 
                        hour12: true, 
                        hour: '2-digit', 
                        minute: '2-digit'
                    });
                    sessionTimeText = `${startFormatted} - ${endFormatted}`;
                }
            }
            
            // Update UI elements
            if (currentActiveSession) {
                sessionStatus.className = 'text-xs font-medium text-green-700';
                currentSessionTime.className = 'text-xs text-green-600 mb-2';
            } else {
                sessionStatus.className = 'text-xs font-medium text-gray-500';
                currentSessionTime.className = 'text-xs text-gray-500 mb-2';
            }
            
            sessionStatus.textContent = statusText;
            currentSessionTime.textContent = sessionTimeText;
        }

        function getCurrentTime24HourForComparison() {
            const now = new Date();
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = now.getMinutes().toString().padStart(2, '0');
            return `${hours}:${minutes}`;
        }

        function isTimeInRange(currentTime, startTime, endTime) {
            // Convert times to comparable format
            const current = timeToMinutes(currentTime);
            const start = timeToMinutes(startTime);
            const end = timeToMinutes(endTime);
            
            // Handle case where end time is on the next day
            if (end < start) {
                return current >= start || current < end;
            } else {
                return current >= start && current < end;
            }
        }

        function timeToMinutes(timeStr) {
            const [hours, minutes] = timeStr.split(':').map(Number);
            return hours * 60 + minutes;
        }

        // Clean up interval when page is closed
        window.addEventListener('beforeunload', function() {
            if (liveAttendanceInterval) {
                clearInterval(liveAttendanceInterval);
            }
        });
    </script>
</body>
</html>
