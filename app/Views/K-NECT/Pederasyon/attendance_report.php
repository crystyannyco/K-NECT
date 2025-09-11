<!-- Required libraries for PDF generation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>

<!-- ===== MAIN CONTENT AREA ===== -->
<div class="flex-1 flex flex-col min-h-0 ml-64 pt-16">
    <main class="flex-1 overflow-auto p-6 bg-gray-50">
        <!-- Breadcrumbs -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2">
                <li class="inline-flex items-center">
                    <a href="<?= base_url('pederasyon/dashboard') ?>" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-purple-600">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-2" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <a href="<?= base_url('pederasyon/attendance') ?>" class="text-sm font-medium text-gray-600 hover:text-purple-600">Events</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-2" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="text-sm font-medium text-gray-600">Attendance Report</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header Section -->
        <div class="mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h3 class="text-2xl font-bold text-gray-900">Attendance Report</h3>
                <p class="text-sm text-gray-600 mt-1">Manage Attendance Report</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="<?= base_url('pederasyon/attendance') ?>" 
                    class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Events  
                </a>
            </div>
        </div>

        <!-- Event Information Card -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex items-start space-x-6">
                <div class="flex-1">
                    <div class="flex items-start justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 mb-2"><?= esc($event['title']) ?></h1>
                            <div class="flex items-center text-gray-500 mb-4">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="text-sm">Event Report</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php if (!empty($event['description'])): ?>
                        <div class="flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-0.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Description</p>
                                <div class="text-sm text-gray-900 mt-1 overflow-hidden">
                                    <div class="line-clamp-2"><?= esc($event['description']) ?></div>
                                    <button onclick="toggleDescription(this)" class="text-xs text-purple-600 hover:text-purple-800 mt-1 focus:outline-none">
                                        Show more
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-0.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Date</p>
                                <p class="text-sm font-medium text-gray-900 mt-1"><?= date('F j, Y', strtotime($event['start_datetime'])) ?></p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-0.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Time</p>
                                <p class="text-sm font-medium text-gray-900 mt-1"><?= date('g:i A', strtotime($event['start_datetime'])) ?> - <?= date('g:i A', strtotime($event['end_datetime'])) ?></p>
                            </div>
                        </div>
                        
                        <?php if ($event['location']): ?>
                        <div class="flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-0.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Location</p>
                                <p class="text-sm font-medium text-gray-900 mt-1"><?= esc($event['location']) ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($event['category']): ?>
                        <div class="flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-0.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Category</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 mt-1">
                                    <?= esc(ucfirst($event['category'])) ?>
                                </span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Attendance Statistics -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h5 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Attendance Summary
                    </h5>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Total Attendees</span>
                            <span class="text-lg font-bold text-purple-600"><?= count($attendance_records) ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Morning Session</span>
                            <span class="text-sm font-semibold text-green-600"><?= count(array_filter($attendance_records, function($r) { return !empty($r['time-in_am']); })) ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Afternoon Session</span>
                            <span class="text-sm font-semibold text-blue-600"><?= count(array_filter($attendance_records, function($r) { return !empty($r['time-in_pm']); })) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Records Table -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <!-- Table Header -->
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <h4 class="text-lg font-semibold text-gray-900">Attendance Log</h4>
                        <span class="text-sm text-gray-500">Total: <span id="totalCount"><?= count($attendance_records) ?></span> attendees</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button onclick="downloadAttendanceExcel()" class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Excel
                        </button>
                        <button onclick="downloadAttendancePDF()" class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            PDF
                        </button>
                        <button onclick="downloadAttendanceWord()" class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Word
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Table Content -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">No.</th>
                            <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">KK Number</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Zone</th>
                            <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">AM Time-In</th>
                            <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">AM Time-Out</th>
                            <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">AM Status</th>
                            <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">PM Time-In</th>
                            <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">PM Time-Out</th>
                            <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">PM Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (!empty($attendance_records)): ?>
                            <?php foreach ($attendance_records as $index => $record): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-center text-gray-900"><?= $index + 1 ?></td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-center text-gray-900"><?= esc($record['permanent_user_id'] ?? 'N/A') ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <?php
                                        // Format: Lastname, Firstname Middlename
                                        $nameParts = explode(' ', trim($record['user_name']));
                                        if (count($nameParts) >= 2) {
                                            $firstName = $nameParts[0];
                                            $lastName = end($nameParts);
                                            $middleName = count($nameParts) > 2 ? implode(' ', array_slice($nameParts, 1, -1)) : '';
                                            $formattedName = $lastName . ', ' . $firstName . ($middleName ? ' ' . $middleName : '');
                                        } else {
                                            $formattedName = $record['user_name'];
                                        }
                                        echo esc($formattedName);
                                        ?>
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-center text-gray-900"><?= esc($record['zone_purok'] ?? 'N/A') ?></td>
                                    
                                    <!-- AM Time-In -->
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-center text-gray-900">
                                        <?= !empty($record['time-in_am']) ? date('h:i A', strtotime($record['time-in_am'])) : '-' ?>
                                    </td>
                                    
                                    <!-- AM Time-Out -->
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-center text-gray-900">
                                        <?= !empty($record['time-out_am']) ? date('h:i A', strtotime($record['time-out_am'])) : '-' ?>
                                    </td>
                                    
                                    <!-- AM Status -->
                                    <td class="px-3 py-4 whitespace-nowrap text-center">
                                        <?php
                                        $amStatus = 'Absent';
                                        $amStatusClass = 'bg-red-100 text-red-800';
                                        
                                        if (!empty($record['time-in_am'])) {
                                            if (!empty($record['status_am']) && strtolower($record['status_am']) === 'late') {
                                                $amStatus = 'Late';
                                                $amStatusClass = 'bg-orange-100 text-orange-800';
                                            } elseif (!empty($record['time-out_am'])) {
                                                $amStatus = 'Complete';
                                                $amStatusClass = 'bg-green-100 text-green-800';
                                            } else {
                                                $amStatus = 'Present';
                                                $amStatusClass = 'bg-yellow-100 text-yellow-800';
                                            }
                                        }
                                        ?>
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?= $amStatusClass ?>">
                                            <?= $amStatus ?>
                                        </span>
                                    </td>
                                    
                                    <!-- PM Time-In -->
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-center text-gray-900">
                                        <?= !empty($record['time-in_pm']) ? date('h:i A', strtotime($record['time-in_pm'])) : '-' ?>
                                    </td>
                                    
                                    <!-- PM Time-Out -->
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-center text-gray-900">
                                        <?= !empty($record['time-out_pm']) ? date('h:i A', strtotime($record['time-out_pm'])) : '-' ?>
                                    </td>
                                    
                                    <!-- PM Status -->
                                    <td class="px-3 py-4 whitespace-nowrap text-center">
                                        <?php
                                        $pmStatus = 'Absent';
                                        $pmStatusClass = 'bg-red-100 text-red-800';
                                        
                                        if (!empty($record['time-in_pm'])) {
                                            if (!empty($record['status_pm']) && strtolower($record['status_pm']) === 'late') {
                                                $pmStatus = 'Late';
                                                $pmStatusClass = 'bg-orange-100 text-orange-800';
                                            } elseif (!empty($record['time-out_pm'])) {
                                                $pmStatus = 'Complete';
                                                $pmStatusClass = 'bg-green-100 text-green-800';
                                            } else {
                                                $pmStatus = 'Present';
                                                $pmStatusClass = 'bg-yellow-100 text-yellow-800';
                                            }
                                        }
                                        ?>
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?= $pmStatusClass ?>">
                                            <?= $pmStatus ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No attendance records found for this event.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script>
// Event data for JavaScript functions
const eventData = {
    id: <?= $event['event_id'] ?>,
    title: '<?= addslashes($event['title']) ?>',
    description: '<?= addslashes($event['description'] ?? '') ?>',
    date: '<?= date('F j, Y', strtotime($event['start_datetime'])) ?>',
    time: '<?= date('g:i A', strtotime($event['start_datetime'])) ?> - <?= date('g:i A', strtotime($event['end_datetime'])) ?>',
    location: '<?= addslashes($event['location'] ?? '') ?>',
    category: '<?= addslashes($event['category'] ?? '') ?>'
};

// Attendance records data
const attendanceRecords = <?= json_encode($attendance_records) ?>;

// Download attendance report as Excel
function downloadAttendanceExcel() {
    const button = event.target.closest('button');
    const originalHTML = button.innerHTML;
    button.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Generating Excel...';
    button.disabled = true;
    
    // Check if there are attendance records
    if (!attendanceRecords || attendanceRecords.length === 0) {
        // Reset button state
        button.innerHTML = originalHTML;
        button.disabled = false;
        // Show notification
        showNotification('No attendance data available for Excel generation.', 'warning');
        return;
    }
    
    // Make AJAX request to generate Excel document
    fetch('<?= base_url('pederasyon/attendance-report-excel') ?>/' + eventData.id, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({})
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Create a temporary link to download the Excel document
            const link = document.createElement('a');
            link.href = data.download_url;
            link.download = data.download_url.split('/').pop();
            link.style.display = 'none';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            showNotification('Attendance report Excel document generated and downloaded successfully!', 'success');
        } else {
            console.error('Server error:', data);
            showNotification((data.message || 'Unknown error occurred'), 'error');
        }
    })
    .catch(error => {
        console.error('Network error:', error);
        showNotification(error.message + '. Please check your connection and try again.', 'error');
    })
    .finally(() => {
        // Reset button state
        button.innerHTML = originalHTML;
        button.disabled = false;
    });
}

// Download attendance report as Word
function downloadAttendanceWord() {
    const button = event.target.closest('button');
    const originalHTML = button.innerHTML;
    button.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Generating Word...';
    button.disabled = true;
    
    // Check if there are attendance records
    if (!attendanceRecords || attendanceRecords.length === 0) {
        // Reset button state
        button.innerHTML = originalHTML;
        button.disabled = false;
        // Show notification
        showNotification('No attendance data available for Word generation.', 'warning');
        return;
    }
    
    // Make AJAX request to generate Word document
    fetch('<?= base_url('pederasyon/attendance-report-word') ?>/' + eventData.id, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({})
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Create a temporary link to download the Word document
            const link = document.createElement('a');
            link.href = data.download_url;
            link.download = data.download_url.split('/').pop();
            link.style.display = 'none';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            showNotification('Attendance report Word document generated and downloaded successfully!', 'success');
        } else {
            console.error('Server error:', data);
            showNotification((data.message || 'Unknown error occurred'), 'error');
        }
    })
    .catch(error => {
        console.error('Network error:', error);
        showNotification(error.message + '. Please check your connection and try again.', 'error');
    })
    .finally(() => {
        // Reset button state
        button.innerHTML = originalHTML;
        button.disabled = false;
    });
}

// Download attendance report as PDF
function downloadAttendancePDF() {
    const button = event.target.closest('button');
    const originalHTML = button.innerHTML;
    button.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Generating PDF...';
    button.disabled = true;
    
    // Check if there are attendance records
    if (!attendanceRecords || attendanceRecords.length === 0) {
        // Reset button state
        button.innerHTML = originalHTML;
        button.disabled = false;
        // Show notification
        showNotification('No attendance data available for PDF generation.', 'warning');
        return;
    }
    
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('l', 'mm', 'a4'); // landscape orientation
    
    // First fetch logos to include in PDF if available
    fetch('<?= base_url('documents/logos') ?>')
        .then(response => response.json())
        .then(data => {
            const promises = [];
            let pederasyonLogo = null;
            let irigaLogo = null;
            
            if (data.success && data.data) {
                const logos = data.data;
                
                // Load Pederasyon logo
                if (logos.pederasyon) {
                    const pederasyonPromise = new Promise((resolve) => {
                        const img = new Image();
                        img.crossOrigin = 'anonymous';
                        img.onload = function() {
                            pederasyonLogo = this;
                            resolve();
                        };
                        img.onerror = function() {
                            resolve(); // Continue even if logo fails to load
                        };
                        img.src = '<?= base_url() ?>' + logos.pederasyon.file_path;
                    });
                    promises.push(pederasyonPromise);
                }
                
                // Load Iriga City logo
                if (logos.iriga_city) {
                    const irigaPromise = new Promise((resolve) => {
                        const img = new Image();
                        img.crossOrigin = 'anonymous';
                        img.onload = function() {
                            irigaLogo = this;
                            resolve();
                        };
                        img.onerror = function() {
                            resolve(); // Continue even if logo fails to load
                        };
                        img.src = '<?= base_url() ?>' + logos.iriga_city.file_path;
                    });
                    promises.push(irigaPromise);
                }
            }
            
            // Wait for all logos to load, then generate PDF
            Promise.all(promises).then(() => {
                generateAttendancePDFWithLogos(doc, pederasyonLogo, irigaLogo, button, originalHTML);
            });
        })
        .catch(error => {
            console.error('Error fetching logos for PDF:', error);
            // Continue with PDF generation without logos
            generateAttendancePDFWithLogos(doc, null, null, button, originalHTML);
        });
}

function generateAttendancePDFWithLogos(doc, pederasyonLogo, irigaLogo, button, originalHTML) {
    try {
        let yPosition = 15;
        
        // Layout: Logo - 20% space - Header text - 20% space - Logo
        const pageWidth = doc.internal.pageSize.getWidth(); // ~297mm for A4 landscape
        const centerX = pageWidth / 2; // Center of page
        const logoSize = 20; // 20mm logos
        const spaceWidth = pageWidth * 0.2; // 20% of page width for spacing
        
        // Calculate positions: Logo - Space - Text - Space - Logo
        const leftLogoX = centerX - spaceWidth - logoSize; // Left logo position
        const rightLogoX = centerX + spaceWidth; // Right logo position
        
        if (pederasyonLogo) {
            doc.addImage(pederasyonLogo, 'PNG', leftLogoX, yPosition, logoSize, logoSize);
        }
        if (irigaLogo) {
            doc.addImage(irigaLogo, 'PNG', rightLogoX, yPosition, logoSize, logoSize);
        }
        
        // Header text - centered between logos
        doc.setFontSize(12);
        doc.setFont('helvetica', 'bold');
        doc.text('REPUBLIC OF THE PHILIPPINES', centerX, yPosition + 5, { align: 'center' });
        doc.text('PROVINCE OF CAMARINES SUR', centerX, yPosition + 10, { align: 'center' });
        doc.text('CITY OF IRIGA', centerX, yPosition + 15, { align: 'center' });
        doc.setFontSize(10);
        doc.setFont('helvetica', 'normal');
        doc.text('SANGGUNIANG KABATAAN PEDERASYON', centerX, yPosition + 20, { align: 'center' });
        
        // Add barangay name if available - FIXED: Use barangay_name from controller or fallback to records
        <?php
        // Try to get barangay name from controller data first, then from records
        $barangayName = $barangay_name ?? '';
        if (!$barangayName && !empty($attendance_records)) {
            foreach ($attendance_records as $record) {
                if (!empty($record['barangay_name'])) {
                    $barangayName = $record['barangay_name'];
                    break;
                }
            }
        }
        ?>
        <?php if ($barangayName): ?>
        doc.text('NG BARANGAY <?= strtoupper(addslashes($barangayName)) ?>', centerX, yPosition + 25, { align: 'center' });
        yPosition += 40;
        <?php else: ?>
        yPosition += 35;
        <?php endif; ?>
        
        // Title
        doc.setFontSize(14);
        doc.setFont('helvetica', 'bold');
        doc.text('ATTENDANCE REPORT', centerX, yPosition, { align: 'center' });
        
        yPosition += 15;
        
        // Event details
        doc.setFontSize(10);
        doc.setFont('helvetica', 'normal');
        doc.text('Event: ' + eventData.title, 20, yPosition);
        doc.text('Date: ' + eventData.date, 20, yPosition + 5);
        doc.text('Time: ' + eventData.time, 20, yPosition + 10);
        if (eventData.location) {
            doc.text('Location: ' + eventData.location, 20, yPosition + 15);
            yPosition += 5;
        }
        
        yPosition += 25;
        
        // Prepare table data
        const tableData = attendanceRecords.map((record, index) => {
            // AM Time-In
            let amTimeIn = '-';
            if (record['time-in_am']) {
                amTimeIn = new Date(record['time-in_am']).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
            }
            
            // AM Time-Out
            let amTimeOut = '-';
            if (record['time-out_am']) {
                amTimeOut = new Date(record['time-out_am']).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
            }
            
            // PM Time-In
            let pmTimeIn = '-';
            if (record['time-in_pm']) {
                pmTimeIn = new Date(record['time-in_pm']).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
            }
            
            // PM Time-Out
            let pmTimeOut = '-';
            if (record['time-out_pm']) {
                pmTimeOut = new Date(record['time-out_pm']).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
            }
            
            // AM Status
            let amStatus = 'Absent';
            if (record['time-in_am']) {
                if (record['status_am'] === 'Late') {
                    amStatus = 'Late';
                } else if (record['time-out_am']) {
                    amStatus = 'Complete';
                } else {
                    amStatus = 'Present';
                }
            }
            
            // PM Status
            let pmStatus = 'Absent';
            if (record['time-in_pm']) {
                if (record['status_pm'] === 'Late') {
                    pmStatus = 'Late';
                } else if (record['time-out_pm']) {
                    pmStatus = 'Complete';
                } else {
                    pmStatus = 'Present';
                }
            }
            
            // Format name as Lastname, Firstname Middlename
            let formattedName = record.user_name || 'N/A';
            if (record.user_name) {
                const nameParts = record.user_name.trim().split(' ');
                if (nameParts.length >= 2) {
                    const firstName = nameParts[0];
                    const lastName = nameParts[nameParts.length - 1];
                    const middleName = nameParts.length > 2 ? nameParts.slice(1, -1).join(' ') : '';
                    formattedName = lastName + ', ' + firstName + (middleName ? ' ' + middleName : '');
                }
            }
            
            return [
                index + 1,
                record.permanent_user_id || 'N/A',
                formattedName,
                record.zone_purok || 'N/A',
                amTimeIn,
                amTimeOut,
                amStatus,
                pmTimeIn,
                pmTimeOut,
                pmStatus
            ];
        });
        
        // Add table with proper safe area margins and perfect centering
        const safeMargin = 15; // 15mm safe margins from edges
        const tableWidth = pageWidth - (safeMargin * 2); // Full width minus safe margins
        
        doc.autoTable({
            head: [['No.', 'KK Number', 'Name', 'Zone', 'AM Time-In', 'AM Time-Out', 'AM Status', 'PM Time-In', 'PM Time-Out', 'PM Status']],
            body: tableData,
            startY: yPosition,
            margin: { left: safeMargin, right: safeMargin }, // Safe area margins
            tableWidth: 'wrap', // Let autoTable calculate optimal width
            horizontalPageBreak: true,
            styles: {
                fontSize: 7,
                cellPadding: 1.5,
                overflow: 'linebreak',
                lineColor: [0, 0, 0],
                lineWidth: 0.1,  // Thin borders
                halign: 'center',
                valign: 'middle'
            },
            headStyles: {
                fillColor: [255, 255, 255],  // White background
                textColor: [0, 0, 0],        // Black text
                fontStyle: 'bold',
                fontSize: 8,
                lineColor: [0, 0, 0],
                lineWidth: 0.1,  // Thin borders
                halign: 'center',
                valign: 'middle'
            },
            bodyStyles: {
                fillColor: [255, 255, 255],  // White background
                textColor: [0, 0, 0],        // Black text
                lineColor: [0, 0, 0],
                lineWidth: 0.1,  // Thin borders
                halign: 'center',
                valign: 'middle'
            },
            columnStyles: {
                0: { cellWidth: 20, halign: 'center' },  // No.
                1: { cellWidth: 25, halign: 'center' },  // KK Number
                2: { cellWidth: 55, halign: 'left' },    // Name (left aligned for readability)
                3: { cellWidth: 20, halign: 'center' },  // Zone
                4: { cellWidth: 25, halign: 'center' },  // AM Time-In
                5: { cellWidth: 25, halign: 'center' },  // AM Time-Out
                6: { cellWidth: 22, halign: 'center' },  // AM Status
                7: { cellWidth: 25, halign: 'center' },  // PM Time-In
                8: { cellWidth: 25, halign: 'center' },  // PM Time-Out
                9: { cellWidth: 22, halign: 'center' }   // PM Status
            },
            theme: 'grid',  // Clean grid theme with borders only
            didDrawPage: function (data) {
                // Center the table on each page
                const tableWidth = data.table.width;
                const pageWidth = doc.internal.pageSize.getWidth();
                const marginLeft = (pageWidth - tableWidth) / 2;
                data.settings.margin.left = Math.max(marginLeft, safeMargin);
            }
        });
        
        // Save and download
        const fileName = `Pederasyon_Attendance_Report_${eventData.title.replace(/[^a-zA-Z0-9]/g, '_')}_${new Date().toISOString().split('T')[0]}.pdf`;
        doc.save(fileName);
        
        showNotification('Attendance report PDF generated and downloaded successfully!', 'success');
        
    } catch (error) {
        console.error('Error generating PDF:', error);
        showNotification('Error generating PDF: ' + error.message, 'error');
    } finally {
        // Reset button state - FIXED: Use the passed button reference for proper reset
        if (button) {
            button.innerHTML = originalHTML;
            button.disabled = false;
        }
    }
}

// Enhanced notification function
// Notification function with improved styling
function showNotification(message, type = 'info') {
    // Ensure toast container exists and is styled for stacking
    let toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toastContainer';
        toastContainer.className = 'fixed top-4 right-4 z-[100000] flex flex-col gap-2 items-end pointer-events-none';
        document.body.appendChild(toastContainer);
    } else {
        toastContainer.className = 'fixed top-4 right-4 z-[100000] flex flex-col gap-2 items-end pointer-events-none';
    }

    const notification = document.createElement('div');
    notification.className = 'pointer-events-auto p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full min-w-[280px] max-w-md break-words';
    
    switch(type) {
        case 'success':
            notification.className += ' bg-green-500 text-white';
            break;
        case 'error':
            notification.className += ' bg-red-500 text-white';
            break;
        case 'warning':
            notification.className += ' bg-orange-500 text-white';
            break;
        default:
            notification.className += ' bg-blue-500 text-white';
    }
    
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
            icon = '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>';
            break;
        case 'info':
        default:
            icon = '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01" /></svg>';
            break;
    }
    
    notification.innerHTML = `
        <div class="flex items-center">
            ${icon}
            <span class="mr-2">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-white hover:text-gray-200 focus:outline-none">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    `;
    
    toastContainer.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 300);
    }, 5000);
}

// Add this to your script section if not already present
function toggleDescription(button) {
    const descElement = button.previousElementSibling;
    if (descElement.classList.contains('line-clamp-2')) {
        descElement.classList.remove('line-clamp-2');
        button.textContent = 'Show less';
    } else {
        descElement.classList.add('line-clamp-2');
        button.textContent = 'Show more';
    }
}
</script>

<!-- ===== MAIN CONTENT AREA ===== -->
<div class="flex-1 flex flex-col min-h-0 ml-64 pt-16">
    <main class="flex-1 overflow-auto p-6 bg-gray-50">
        <!-- Header Section -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-900">Attendance Report</h3>
                <p class="text-sm text-gray-600 mt-1">View attendance details for completed events</p>
            </div>
            <a href="<?= base_url('pederasyon/attendance') ?>" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Events
            </a>
        </div>

        <!-- Event Details Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <?= esc($event['title']) ?>
                </h4>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Event Information -->
                    <div class="lg:col-span-2">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php if ($event['description']): ?>
                            <div class="md:col-span-2">
                                <div class="flex items-start">
                                    <svg class="w-4 h-4 mr-2 mt-0.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Description</p>
                                        <p class="text-sm text-gray-900 mt-1"><?= esc($event['description']) ?></p>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <div class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Date</p>
                                    <p class="text-sm font-medium text-gray-900 mt-1"><?= date('F j, Y', strtotime($event['start_datetime'])) ?></p>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Time</p>
                                    <p class="text-sm font-medium text-gray-900 mt-1"><?= date('g:i A', strtotime($event['start_datetime'])) ?> - <?= date('g:i A', strtotime($event['end_datetime'])) ?></p>
                                </div>
                            </div>
                            
                            <?php if ($event['location']): ?>
                            <div class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Location</p>
                                    <p class="text-sm font-medium text-gray-900 mt-1"><?= esc($event['location']) ?></p>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($event['category']): ?>
                            <div class="flex items-start">
                                <svg class="w-4 h-4 mr-2 mt-0.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Category</p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                                        <?= esc(ucfirst($event['category'])) ?>
                                    </span>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Attendance Statistics -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h5 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Attendance Summary
                        </h5>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Total Attendees</span>
                                <span class="text-lg font-bold text-blue-600"><?= count($attendance_records) ?></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Morning Session</span>
                                <span class="text-sm font-semibold text-green-600"><?= count(array_filter($attendance_records, function($r) { return !empty($r['time-in_am']); })) ?></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Afternoon Session</span>
                                <span class="text-sm font-semibold text-blue-600"><?= count(array_filter($attendance_records, function($r) { return !empty($r['time-in_pm']); })) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Records Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h4 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        Attendance Records
                    </h4>
                        <div class="flex items-center text-sm text-gray-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <?= count($attendance_records) ?> total records
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table id="attendanceTable" class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                    </svg>
                                    No.
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Name
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Barangay
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Age
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                    </svg>
                                    Sex
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                    Time In (AM)
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                                    </svg>
                                    Time Out (AM)
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                    Time In (PM)
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                                    </svg>
                                    Time Out (PM)
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    AM Status
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    PM Status
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <?php if (!empty($attendance_records)): ?>
                            <?php foreach ($attendance_records as $index => $record): ?>
                                <tr class="border-b border-gray-100 hover:bg-blue-50 transition-colors duration-150">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-800 text-xs font-semibold">
                                            <?= $index + 1 ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-blue-800">
                                                        <?= strtoupper(substr($record['user_name'], 0, 2)) ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?= esc($record['user_name']) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <?= esc($record['barangay']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 text-center">
                                        <?= esc($record['age']) ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-center">
                                        <?php if (strtolower($record['sex']) === 'male'): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6z"/>
                                                </svg>
                                                Male
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6z"/>
                                                </svg>
                                                Female
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <?php if (!empty($record['time_in_am'])): ?>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                </svg>
                                                <?= date('h:i A', strtotime($record['time_in_am'])) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-50 text-gray-500 border border-gray-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                                No Record
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <?php if (!empty($record['time_out_am'])): ?>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-yellow-50 text-yellow-700 border border-yellow-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                </svg>
                                                <?= date('h:i A', strtotime($record['time_out_am'])) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-50 text-gray-500 border border-gray-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                                No Record
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <?php if (!empty($record['time_in_pm'])): ?>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-orange-50 text-orange-700 border border-orange-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                </svg>
                                                <?= date('h:i A', strtotime($record['time_in_pm'])) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-50 text-gray-500 border border-gray-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                                No Record
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <?php if (!empty($record['time_out_pm'])): ?>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-orange-50 text-orange-700 border border-orange-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                </svg>
                                                <?= date('h:i A', strtotime($record['time_out_pm'])) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-50 text-gray-500 border border-gray-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                                No Record
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <?php
                                        $am_status = $record['status_am'] ?? 'No Record';
                                        $am_icon = '';
                                        $am_color = '';
                                        switch(strtolower($am_status)) {
                                            case 'present':
                                                $am_color = 'bg-green-50 text-green-700 border-green-200';
                                                $am_icon = '<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>';
                                                break;
                                            case 'absent':
                                                $am_color = 'bg-red-50 text-red-700 border-red-200';
                                                $am_icon = '<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>';
                                                break;
                                            case 'late':
                                                $am_color = 'bg-yellow-50 text-yellow-700 border-yellow-200';
                                                $am_icon = '<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>';
                                                break;
                                            default:
                                                $am_color = 'bg-gray-50 text-gray-600 border-gray-200';
                                                $am_icon = '<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>';
                                        }
                                        ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium border <?= $am_color ?>">
                                            <?= $am_icon ?>
                                            <?= esc($am_status) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <?php
                                        $pm_status = $record['status_pm'] ?? 'No Record';
                                        $pm_icon = '';
                                        $pm_color = '';
                                        switch(strtolower($pm_status)) {
                                            case 'present':
                                                $pm_color = 'bg-green-50 text-green-700 border-green-200';
                                                $pm_icon = '<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>';
                                                break;
                                            case 'absent':
                                                $pm_color = 'bg-red-50 text-red-700 border-red-200';
                                                $pm_icon = '<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>';
                                                break;
                                            case 'late':
                                                $pm_color = 'bg-yellow-50 text-yellow-700 border-yellow-200';
                                                $pm_icon = '<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>';
                                                break;
                                            default:
                                                $pm_color = 'bg-gray-50 text-gray-600 border-gray-200';
                                                $pm_icon = '<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>';
                                        }
                                        ?>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium border <?= $pm_color ?>">
                                            <?= $pm_icon ?>
                                            <?= esc($pm_status) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="11" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                        </svg>
                                        <p class="text-sm text-gray-500">No attendance records found for this event.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTable with enhanced styling
    $('#attendanceTable').DataTable({
        responsive: true,
        order: [[0, 'asc']],
        pageLength: 25,
        dom: '<"flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4"<"mb-2 sm:mb-0"l><"flex items-center space-x-2"Bf>>rtip',
        buttons: [
            {
                extend: 'excel',
                text: '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>Excel',
                className: 'inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200',
                title: 'Attendance Report - <?= esc($event['title']) ?>',
                filename: 'attendance_report_<?= date('Y-m-d', strtotime($event['start_datetime'])) ?>'
            },
            {
                extend: 'pdf',
                text: '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>PDF',
                className: 'inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200',
                title: 'Attendance Report',
                filename: 'attendance_report_<?= date('Y-m-d', strtotime($event['start_datetime'])) ?>',
                customize: function(doc) {
                    doc.content[1].table.widths = ['7%', '16%', '11%', '7%', '7%', '10%', '10%', '8%', '10%', '10%', '8%'];
                    doc.styles.tableHeader.fontSize = 9;
                    doc.defaultStyle.fontSize = 8;
                }
            }
        ],
        language: {
            search: "",
            searchPlaceholder: "Search records...",
            lengthMenu: "Show _MENU_",
            info: "Showing _START_ to _END_ of _TOTAL_ records",
            infoEmpty: "No records available",
            infoFiltered: "(filtered from _MAX_ total records)",
            paginate: {
                first: "«",
                last: "»",
                next: "›",
                previous: "‹"
            }
        },
        columnDefs: [
            { targets: [0, 3, 4, 5, 6, 7, 8, 9, 10], className: 'text-center' },
            { targets: [0], orderable: false }
        ],
        initComplete: function() {
            // Style the search input
            $('.dataTables_filter input').addClass('block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm');
            
            // Add search icon
            $('.dataTables_filter').addClass('relative').prepend('<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></div>');
            
            // Style the length select
            $('.dataTables_length select').addClass('block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md');
        }
    });
});
</script>
