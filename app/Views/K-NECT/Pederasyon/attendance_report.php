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
    // Use custom page size: 13 x 8.5 inches -> expressed in mm: 13*25.4 = 330.2, 8.5*25.4 = 215.9
    const doc = new jsPDF('l', 'mm', [330.2, 215.9]); // landscape, custom size in mm
    
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
        const pageWidth = doc.internal.pageSize.getWidth(); // ~330.2mm for 13x8.5 landscape
        const centerX = pageWidth / 2; // Center of page
        // Safe printable margin: 0.5 inch = 12.7 mm
        const safeMargin = 12.7;
        const tableWidth = pageWidth - (safeMargin * 2);
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
        doc.text('PANLUNGSOD NA PEDERASYON NG MGA', centerX, yPosition + 20, { align: 'center' });
        doc.text('SANGGUNIANG KABATAAN', centerX, yPosition + 25, { align: 'center' });
        
        // No barangay line for Pederasyon documents - header intentionally shows city + Panlungsod Pederasyon only
        yPosition += 40;
        
        // Title
        doc.setFontSize(14);
        doc.setFont('helvetica', 'bold');
        doc.text('ATTENDANCE REPORT', centerX, yPosition, { align: 'center' });
        
        yPosition += 15;
        
        // Event details - align to table's left edge
        doc.setFontSize(10);
        doc.setFont('helvetica', 'normal');
        const effectiveLeft = Math.max(safeMargin, (pageWidth - tableWidth) / 2);
        doc.text('Event: ' + eventData.title, effectiveLeft, yPosition);
        doc.text('Date: ' + eventData.date, effectiveLeft, yPosition + 5);
        doc.text('Time: ' + eventData.time, effectiveLeft, yPosition + 10);
        if (eventData.location) {
            doc.text('Location: ' + eventData.location, effectiveLeft, yPosition + 15);
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
        // Compute proportional column widths so the table fills the printable width
        const colWeights = [6, 10, 36, 10, 8, 8, 8, 8, 8, 8]; // relative weights for columns
        const totalWeight = colWeights.reduce((a, b) => a + b, 0);
        const columnStyles = {};
        colWeights.forEach((w, i) => {
            columnStyles[i] = {
                cellWidth: (tableWidth * (w / totalWeight)),
                halign: (i === 2 ? 'left' : 'center')
            };
        });

        doc.autoTable({
            head: [['No.', 'KK Number', 'Name', 'Zone', 'AM Time-In', 'AM Time-Out', 'AM Status', 'PM Time-In', 'PM Time-Out', 'PM Status']],
            body: tableData,
            startY: yPosition,
            margin: { left: safeMargin, right: safeMargin }, // Safe area margins
            tableWidth: tableWidth, // Use computed printable width
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
        columnStyles: columnStyles,
        theme: 'grid',  // Clean grid theme with borders only
        didDrawPage: function (data) {
            // Center the table on each page
            const tableW = data.table.width;
            const pageW = doc.internal.pageSize.getWidth();
            const marginLeft = (pageW - tableW) / 2;
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
