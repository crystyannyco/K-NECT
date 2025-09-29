<style>
    .event-card {
        transition: all 0.2s ease;
        border: 1px solid #e5e7eb;
        height: 100%;
    }
    
    .event-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border-color: #2563eb;
    }
    
    .filter-tab {
        cursor: pointer;
        border: 1px solid #e5e7eb;
        background: white;
        color: #6b7280;
        transition: all 0.2s ease;
    }
    
    .filter-tab:hover {
        border-color: #2563eb;
        color: #2563eb;
    }
    
    .filter-tab.active {
        background: #2563eb;
        color: white;
        border-color: #2563eb;
    }
    
    .category-badge {
        @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
    }
    .capsule-container {
        display: inline-flex;
        align-items: center;
        border-radius: 9999px;
        background: #fff;
        border: 1px solid #e5e7eb;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        padding-left: 0.75rem;
        padding-right: 0.75rem;
        padding-top: 0.25rem;
        padding-bottom: 0.25rem;
        margin-top: 0.25rem;
    }
    
    .badge-education { @apply bg-blue-100 text-blue-800; }
    .badge-health { @apply bg-green-100 text-green-800; }
    .badge-sports { @apply bg-red-100 text-red-800; }
    .badge-community { @apply bg-purple-100 text-purple-800; }
    .badge-environment { @apply bg-yellow-100 text-yellow-800; }
    .badge-default { @apply bg-gray-100 text-gray-800; }
    
    /* Toggle Switch Styles */
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 48px;
        height: 24px;
    }
    
    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 24px;
    }
    
    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    
    input:checked + .toggle-slider {
        background-color: #2563eb;
    }
    
    input:checked + .toggle-slider:before {
        transform: translateX(24px);
    }
    
    .session-disabled {
        opacity: 0.5;
        pointer-events: none;
    }
</style>

<!-- ===== MAIN CONTENT AREA ===== -->
<div class="flex-1 flex flex-col min-h-0 ml-64 pt-16">
    <main class="flex-1 overflow-auto p-6 bg-gray-50">
    <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2">
                <li class="inline-flex items-center">
                    <a href="<?= base_url('pederasyon/dashboard') ?>" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-blue-600">
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
                        <a href="<?= base_url('pederasyon/attendance') ?>" class="text-sm font-medium text-gray-600 hover:text-blue-600">Attendance</a>
                    </div>
                </li>
            </ol>
        </nav>    
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-900">City-Wide Event Attendance</h3>
                <p class="text-sm text-gray-600 mt-1">Manage attendance for <span class="font-semibold text-blue-600">City-Wide Events</span></p>
            </div>
        </div>
        
        <!-- Filters Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="p-4 border-b border-gray-200">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <!-- Status Filter Tabs -->
                    <div class="flex flex-wrap gap-2">
                        <button class="filter-tab active px-4 py-2 rounded-lg text-sm font-medium transition-all" data-filter="all">
                            All Events
                        </button>
                        <button class="filter-tab px-4 py-2 rounded-lg text-sm font-medium transition-all" data-filter="upcoming">
                            Upcoming
                        </button>
                        <button class="filter-tab px-4 py-2 rounded-lg text-sm font-medium transition-all" data-filter="ongoing">
                            Ongoing
                        </button>
                        <button class="filter-tab px-4 py-2 rounded-lg text-sm font-medium transition-all" data-filter="completed">
                            Completed
                        </button>
                    </div>
                    
                    <!-- Category Filter and Clear Button -->
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-medium text-gray-600">Category:</span>
                        <select id="categoryFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Categories</option>
                            <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= esc($category) ?>"><?= esc(ucfirst($category)) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <button id="clearFilters" class="px-3 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            Clear Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Events Grid -->
        <div id="eventsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (!empty($events)): ?>
                <?php foreach ($events as $event): ?>
                    <?php
                    $startDateTime = new DateTime($event['start_datetime']);
                    $endDateTime = new DateTime($event['end_datetime']);
                    $currentDateTime = new DateTime();
                    
                    // Determine event status
                    $status = 'upcoming';
                    if ($currentDateTime >= $startDateTime && $currentDateTime <= $endDateTime) {
                        $status = 'ongoing';
                    } elseif ($currentDateTime > $endDateTime) {
                        $status = 'completed';
                    }
                    
                    // Get category badge class
                    $categoryClass = 'badge-default';
                    if ($event['category']) {
                        $categoryClass = 'badge-' . strtolower($event['category']);
                        if (!in_array($categoryClass, ['badge-education', 'badge-health', 'badge-sports', 'badge-community', 'badge-environment'])) {
                            $categoryClass = 'badge-default';
                        }
                    }
                    ?>
                    <div class="event-card bg-white rounded-lg overflow-hidden flex flex-col h-full" 
                         data-status="<?= $status ?>" 
                         data-category="<?= esc($event['category']) ?>">
                        
                        <!-- Event Image -->
                        <div class="h-48 bg-blue-500 relative overflow-hidden flex-shrink-0">
            <?php if (!empty($event['event_banner'])): ?>
                <img src="<?= base_url('uploads/event/' . $event['event_banner']) ?>" 
                     alt="<?= esc($event['title']) ?>" 
                     class="w-full h-full object-cover">
            <?php else: ?>
                <img src="<?= base_url('assets/images/default-event-banner.svg') ?>" 
                     alt="No banner" 
                     class="w-full h-full object-cover">
            <?php endif; ?>                            <!-- Status Badge -->
                            <div class="absolute top-3 right-3">
                                <?php if ($status === 'upcoming'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Upcoming
                                    </span>
                                <?php elseif ($status === 'ongoing'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Ongoing
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Completed
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Event Details -->
                        <div class="p-4 flex flex-col flex-grow">
                            <div class="flex items-start justify-between mb-2">
                                <h4 class="text-lg font-semibold text-gray-900 line-clamp-2"><?= esc($event['title']) ?></h4>
                            </div>
                            <div class="space-y-2 text-sm text-gray-600 mb-4">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <?php
                                    // Format date range
                                    $startDate = $startDateTime->format('M j');
                                    $endDate = $endDateTime->format('M j');
                                    $year = $startDateTime->format('Y');
                                    $startTime = $startDateTime->format('g:i A');
                                    $endTime = $endDateTime->format('g:i A');
                                    
                                    if ($startDateTime->format('Y-m-d') === $endDateTime->format('Y-m-d')) {
                                        // Same day event
                                        echo $startDateTime->format('M j, Y') . ' • ' . $startTime . ' - ' . $endTime;
                                    } else {
                                        // Multi-day event
                                        if ($startDateTime->format('M') === $endDateTime->format('M')) {
                                            // Same month
                                            echo $startDate . '-' . $endDateTime->format('j, Y') . ' • ' . $startTime . ' - ' . $endTime;
                                        } else {
                                            // Different months
                                            echo $startDate . ' - ' . $endDate . ', ' . $year . ' • ' . $startTime . ' - ' . $endTime;
                                        }
                                    }
                                    ?>
                                </div>
                                <?php if ($event['location']): ?>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <?= esc($event['location']) ?>
                                </div>
                                <?php endif; ?>
                                <?php if ($event['category']): ?>
                                <div class="mt-1 flex justify-start">
                                    <div class="capsule-container">
                                        <span class="category-badge <?= $categoryClass ?>">
                                            <?= esc(ucfirst($event['category'])) ?>
                                        </span>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="flex-grow">
                                <?php if ($event['description']): ?>
                                <p class="text-sm text-gray-600 mb-4 line-clamp-1"><?= esc($event['description']) ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Action Button - Always at bottom -->
                            <div class="mt-auto">
                                <?php if ($status === 'completed'): ?>
                                    <button onclick="viewAttendanceReport(<?= $event['event_id'] ?>)" 
                                            class="w-full bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                                        View Attendance
                                    </button>
                                <?php else: ?>
                                    <div class="flex gap-2">
                                        <button onclick="openAttendanceModal(<?= $event['event_id'] ?>)" 
                                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200"
                                                style="flex-basis: 70%;">
                                            Manage Attendance
                                        </button>
                                        <button onclick="openLiveAttendanceModal(<?= $event['event_id'] ?>)" 
                                                class="flex-1 bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200"
                                                style="flex-basis: 30%;">
                                            Live
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No city-wide events found</h3>
                    <p class="mt-1 text-sm text-gray-500">No published city-wide events are available for attendance management.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<!-- Manage Attendance Modal -->
<div id="attendanceModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-white border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Manage Attendance</h3>
                    <p class="text-sm text-gray-600">Configure attendance settings for this event</p>
                </div>
                <button onclick="closeAttendanceModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

    <!-- Modal Content -->
    <div class="p-6 overflow-y-auto flex-1" style="max-height: calc(90vh - 64px - 80px);">
            <!-- Event Info -->
            <div id="eventInfo" class="mb-6 p-4 bg-gray-50 rounded-lg">
                <!-- Event details will be populated here -->
            </div>

            <!-- Attendance Time Settings -->
            <div class="space-y-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Attendance Time Settings</h4>
                
                <!-- Morning Session -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h5 class="font-medium text-gray-900">Morning Session</h5>
                        <label class="toggle-switch">
                            <input type="checkbox" id="enableMorningSession" checked onchange="toggleSession('morning')">
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <div id="morningSessionContent" class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
                            <input type="time" id="startAttendanceAM" 
                                   min="01:00" max="12:00"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   onchange="validateAttendanceForm()">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                            <input type="time" id="endAttendanceAM" 
                                   min="01:00" max="12:00"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   onchange="validateAttendanceForm()">
                        </div>
                    </div>
                </div>

                <!-- Afternoon Session -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h5 class="font-medium text-gray-900">Afternoon Session</h5>
                        <label class="toggle-switch">
                            <input type="checkbox" id="enableAfternoonSession" checked onchange="toggleSession('afternoon')">
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <div id="afternoonSessionContent" class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
                            <input type="time" id="startAttendancePM" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   onchange="validateAttendanceForm()">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                            <input type="time" id="endAttendancePM" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   onchange="validateAttendanceForm()">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Modal Footer -->
    <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 sticky bottom-0 z-10">
            <button onclick="closeAttendanceModal()" 
                class="px-4 py-2 w-full sm:w-auto text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-2 focus:ring-blue-400 focus:outline-none transition-colors font-semibold">
                Cancel
            </button>
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 w-full sm:w-auto">
                <button id="saveAttendanceBtn" onclick="saveAttendanceSettings()" 
                    class="px-4 py-2 w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-semibold focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    Save Settings
                </button>
                <button id="startAttendanceBtn" onclick="startAttendanceEnhanced()" 
                    class="px-4 py-2 w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors flex items-center justify-center space-x-2 font-semibold focus:ring-2 focus:ring-green-400 focus:outline-none disabled:bg-gray-400 disabled:cursor-not-allowed disabled:hover:bg-gray-400" 
                    disabled title="Please configure at least one session before starting attendance">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    <span>Save & Start Attendance</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="toastContainer" class="fixed top-4 right-4 z-[100] space-y-4"></div>

<script>
let currentEventId = null;
const eventsData = <?= json_encode($events) ?>;

// Log events data to check if it's loaded correctly
console.log('Events data loaded:', eventsData);
console.log('Total events:', eventsData ? eventsData.length : 0);

document.addEventListener('DOMContentLoaded', function() {
    
    // Test if buttons exist
    const buttons = document.querySelectorAll('button[onclick]');
    console.log('Found buttons with onclick:', buttons.length);
    
    // Add event listeners for debugging
    buttons.forEach((button, index) => {
        button.addEventListener('click', function(e) {
            console.log(`Button ${index} clicked:`, button.getAttribute('onclick'));
        });
    });
});

// Add global error handler
window.addEventListener('error', function(e) {
    console.error('JavaScript error:', e.error);
    console.error('Error message:', e.message);
    console.error('Error source:', e.filename, e.lineno, e.colno);
});

// Test if functions are available
window.testJavaScript = function() {
    console.log('JavaScript is working!');
    console.log('eventsData:', eventsData);
    alert('JavaScript test successful!');
};

function showNotification(message, type = 'info') {
    let toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toastContainer';
        toastContainer.className = 'fixed top-4 right-4 z-[100000] space-y-4';
        document.body.appendChild(toastContainer);
    }

    const notification = document.createElement('div');
    notification.className = 'pointer-events-auto p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full min-w-[280px] max-w-md break-words';
    
    // Choose background color based on type
    switch(type) {
        case 'success':
            notification.className += ' bg-green-500 text-white';
            break;
        case 'error':
            notification.className += ' bg-red-500 text-white';
            break;
        case 'warning':
            notification.className += ' bg-yellow-500 text-white';
            break;
        default:
            notification.className += ' bg-blue-500 text-white';
    }

    // Icon SVGs for notification types
    let icon = '';
    switch(type) {
        case 'success':
            icon = '<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>';
            break;
        case 'error':
            icon = '<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';
            break;
        case 'warning':
            icon = '<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h17.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>';
            break;
        default:
            icon = '<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01" /></svg>';
            break;
    }

    notification.innerHTML = `
        <div class="flex items-center">
            <div class="flex-none mr-3">${icon}</div>
            <div class="flex-1">${message}</div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white opacity-70 hover:opacity-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
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
            if (notification.parentNode) {
                notification.remove();
            }
        }, 300);
    }, 5000);
}

// Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    // Status filter tabs
    document.querySelectorAll('.filter-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs
            document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
            // Add active class to clicked tab
            this.classList.add('active');
            
            const filter = this.dataset.filter;
            filterEvents();
        });
    });
    
    // Category filter
    document.getElementById('categoryFilter').addEventListener('change', filterEvents);
    
    // Clear filters
    document.getElementById('clearFilters').addEventListener('click', function() {
        // Reset status filter
        document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
        document.querySelector('.filter-tab[data-filter="all"]').classList.add('active');
        
        // Reset category filter
        document.getElementById('categoryFilter').value = '';
        
        // Apply filters
        filterEvents();
        
        showNotification('Filters cleared successfully', 'success');
    });
});

function filterEvents() {
    const statusFilter = document.querySelector('.filter-tab.active').dataset.filter;
    const categoryFilter = document.getElementById('categoryFilter').value;
    
    document.querySelectorAll('.event-card').forEach(card => {
        const cardStatus = card.dataset.status;
        const cardCategory = card.dataset.category;
        
        let showCard = true;
        
        // Filter by status
        if (statusFilter !== 'all' && cardStatus !== statusFilter) {
            showCard = false;
        }
        
        // Filter by category
        if (categoryFilter && cardCategory !== categoryFilter) {
            showCard = false;
        }
        
        card.style.display = showCard ? 'block' : 'none';
    });
}

function openAttendanceModal(eventId) {
    console.log('openAttendanceModal called with eventId:', eventId);
    currentEventId = eventId;
    const event = eventsData.find(e => e.event_id == eventId);
    
    console.log('Found event:', event);
    
    if (!event) {
        console.error('Event not found for ID:', eventId);
        showNotification('Event not found', 'error');
        return;
    }
    
    // Populate event info
    const eventInfo = document.getElementById('eventInfo');
    const startDate = new Date(event.start_datetime);
    
    eventInfo.innerHTML = `
        <div class="flex items-start space-x-4">
            <div class="flex-shrink-0">
                ${event.event_banner ? 
                    `<img src="<?= base_url('uploads/event/') ?>${event.event_banner}" alt="${event.title}" class="w-16 h-16 rounded-lg object-cover">` :
                    `<img src="<?= base_url('assets/images/default-event-banner.svg') ?>" alt="No banner" class="w-16 h-16 rounded-lg object-cover">`
                }
            </div>
            <div class="flex-1">
                <h4 class="font-semibold text-gray-900">${event.title}</h4>
                <p class="text-sm text-gray-600 mt-1">${startDate.toLocaleDateString('en-US', { 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric', 
                    hour: '2-digit', 
                    minute: '2-digit' 
                })}</p>
                ${event.location ? `<p class="text-sm text-gray-500 mt-1">${event.location}</p>` : ''}
            </div>
        </div>
    `;
    
    // Load existing attendance settings
    loadAttendanceSettings(eventId);
    
    // Show modal
    document.getElementById('attendanceModal').classList.remove('hidden');
    
    // Validate form state when modal opens
    validateAttendanceForm();
}

function closeAttendanceModal() {
    document.getElementById('attendanceModal').classList.add('hidden');
    currentEventId = null;
}

function loadAttendanceSettings(eventId) {
    fetch('<?= base_url('pederasyon/getEventAttendanceSettings') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `event_id=${eventId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.settings) {
            const settings = data.settings;
            
            // Load morning session settings
            if (settings.start_attendance_am && settings.end_attendance_am) {
                document.getElementById('enableMorningSession').checked = true;
                document.getElementById('startAttendanceAM').value = settings.start_attendance_am || '';
                document.getElementById('endAttendanceAM').value = settings.end_attendance_am || '';
                document.getElementById('morningSessionContent').classList.remove('session-disabled');
            } else {
                document.getElementById('enableMorningSession').checked = false;
                document.getElementById('startAttendanceAM').value = '';
                document.getElementById('endAttendanceAM').value = '';
                document.getElementById('morningSessionContent').classList.add('session-disabled');
            }
            
            // Load afternoon session settings
            if (settings.start_attendance_pm && settings.end_attendance_pm) {
                document.getElementById('enableAfternoonSession').checked = true;
                document.getElementById('startAttendancePM').value = settings.start_attendance_pm || '';
                document.getElementById('endAttendancePM').value = settings.end_attendance_pm || '';
                document.getElementById('afternoonSessionContent').classList.remove('session-disabled');
            } else {
                document.getElementById('enableAfternoonSession').checked = false;
                document.getElementById('startAttendancePM').value = '';
                document.getElementById('endAttendancePM').value = '';
                document.getElementById('afternoonSessionContent').classList.add('session-disabled');
            }
        }
        
        // Validate form after loading settings
        validateAttendanceForm();
    })
    .catch(error => {
        console.error('Error loading attendance settings:', error);
        // Validate form even if loading fails
        validateAttendanceForm();
    });
}

function toggleSession(session) {
    const isEnabled = document.getElementById(`enable${session.charAt(0).toUpperCase() + session.slice(1)}Session`).checked;
    const content = document.getElementById(`${session}SessionContent`);
    
    if (isEnabled) {
        content.classList.remove('session-disabled');
    } else {
        content.classList.add('session-disabled');
        // Clear the time inputs when disabled
        if (session === 'morning') {
            document.getElementById('startAttendanceAM').value = '';
            document.getElementById('endAttendanceAM').value = '';
        } else {
            document.getElementById('startAttendancePM').value = '';
            document.getElementById('endAttendancePM').value = '';
        }
    }
    
    // Validate form after toggling session
    validateAttendanceForm();
}

// Function to validate the attendance form and enable/disable start button
function validateAttendanceForm() {
    const startBtn = document.getElementById('startAttendanceBtn');
    const saveBtn = document.getElementById('saveAttendanceBtn');
    const morningEnabled = document.getElementById('enableMorningSession').checked;
    const afternoonEnabled = document.getElementById('enableAfternoonSession').checked;
    
    let isValid = false;
    let validationErrors = [];
    
    // Check if at least one session is enabled
    if (morningEnabled || afternoonEnabled) {
        let morningValid = true;
        let afternoonValid = true;
        
        // Validate morning session if enabled
        if (morningEnabled) {
            const startAM = document.getElementById('startAttendanceAM').value;
            const endAM = document.getElementById('endAttendanceAM').value;
            
            if (!startAM || !endAM) {
                morningValid = false;
                validationErrors.push('Morning session requires both start and end times');
            } else {
                // Validate 30 minutes minimum duration for morning session
                const startTime = new Date(`2000-01-01 ${startAM}`);
                const endTime = new Date(`2000-01-01 ${endAM}`);
                const diffMinutes = (endTime - startTime) / (1000 * 60);
                
                if (diffMinutes < 30) {
                    morningValid = false;
                    validationErrors.push('Morning session must be at least 30 minutes long');
                } else if (diffMinutes < 0) {
                    morningValid = false;
                    validationErrors.push('Morning session end time must be after start time');
                }
            }
        }
        
        // Validate afternoon session if enabled
        if (afternoonEnabled) {
            const startPM = document.getElementById('startAttendancePM').value;
            const endPM = document.getElementById('endAttendancePM').value;
            
            if (!startPM || !endPM) {
                afternoonValid = false;
                validationErrors.push('Afternoon session requires both start and end times');
            } else {
                // Validate 30 minutes minimum duration for afternoon session
                const startTime = new Date(`2000-01-01 ${startPM}`);
                const endTime = new Date(`2000-01-01 ${endPM}`);
                const diffMinutes = (endTime - startTime) / (1000 * 60);
                
                if (diffMinutes < 30) {
                    afternoonValid = false;
                    validationErrors.push('Afternoon session must be at least 30 minutes long');
                } else if (diffMinutes < 0) {
                    afternoonValid = false;
                    validationErrors.push('Afternoon session end time must be after start time');
                }
            }
        }
        
        isValid = morningValid && afternoonValid;
    }
    
    // Enable/disable both save and start attendance buttons
    const buttonTitle = isValid 
        ? 'Ready to save or start attendance' 
        : (validationErrors.length > 0 
            ? validationErrors.join('. ') 
            : 'Please configure at least one session with valid times');
    
    if (startBtn) {
        startBtn.disabled = !isValid;
        startBtn.title = buttonTitle;
    }
    
    if (saveBtn) {
        saveBtn.disabled = !isValid;
        saveBtn.title = buttonTitle;
    }
    
    return isValid;
}

function saveAttendanceSettings() {
    if (!currentEventId) {
        showNotification('No event selected', 'error');
        return;
    }
    
    // Check if at least one session is enabled
    const morningEnabled = document.getElementById('enableMorningSession').checked;
    const afternoonEnabled = document.getElementById('enableAfternoonSession').checked;
    
    if (!morningEnabled && !afternoonEnabled) {
        showNotification('Please enable at least one session', 'error');
        return;
    }

    // Show loading state for save button
    const saveBtn = document.getElementById('saveAttendanceBtn');
    const originalSaveHTML = saveBtn.innerHTML;
    saveBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Saving...';
    saveBtn.disabled = true;
    
    const formData = new FormData();
    formData.append('event_id', currentEventId);
    
    // Check if morning session is enabled
    if (morningEnabled) {
        const startAM = document.getElementById('startAttendanceAM').value;
        const endAM = document.getElementById('endAttendanceAM').value;
        
        if (!startAM || !endAM) {
            showNotification('Please fill in both start and end times for morning session', 'error');
            // Reset button state
            saveBtn.innerHTML = originalSaveHTML;
            saveBtn.disabled = false;
            return;
        }
        
        // Validate 30 minutes minimum duration for morning session
        const startTime = new Date(`2000-01-01 ${startAM}`);
        const endTime = new Date(`2000-01-01 ${endAM}`);
        const diffMinutes = (endTime - startTime) / (1000 * 60);
        
        if (diffMinutes < 30) {
            showNotification('Morning session must be at least 30 minutes long', 'error');
            // Reset button state
            saveBtn.innerHTML = originalSaveHTML;
            saveBtn.disabled = false;
            return;
        } else if (diffMinutes < 0) {
            showNotification('Morning session end time must be after start time', 'error');
            // Reset button state
            saveBtn.innerHTML = originalSaveHTML;
            saveBtn.disabled = false;
            return;
        }
        
        formData.append('start_attendance_am', startAM);
        formData.append('end_attendance_am', endAM);
    } else {
        formData.append('start_attendance_am', '');
        formData.append('end_attendance_am', '');
    }
    
    // Check if afternoon session is enabled
    if (afternoonEnabled) {
        const startPM = document.getElementById('startAttendancePM').value;
        const endPM = document.getElementById('endAttendancePM').value;
        
        if (!startPM || !endPM) {
            showNotification('Please fill in both start and end times for afternoon session', 'error');
            // Reset button state
            saveBtn.innerHTML = originalSaveHTML;
            saveBtn.disabled = false;
            return;
        }
        
        // Validate 30 minutes minimum duration for afternoon session
        const startTime = new Date(`2000-01-01 ${startPM}`);
        const endTime = new Date(`2000-01-01 ${endPM}`);
        const diffMinutes = (endTime - startTime) / (1000 * 60);
        
        if (diffMinutes < 30) {
            showNotification('Afternoon session must be at least 30 minutes long', 'error');
            // Reset button state
            saveBtn.innerHTML = originalSaveHTML;
            saveBtn.disabled = false;
            return;
        } else if (diffMinutes < 0) {
            showNotification('Afternoon session end time must be after start time', 'error');
            // Reset button state
            saveBtn.innerHTML = originalSaveHTML;
            saveBtn.disabled = false;
            return;
        }
        
        formData.append('start_attendance_pm', startPM);
        formData.append('end_attendance_pm', endPM);
    } else {
        formData.append('start_attendance_pm', '');
        formData.append('end_attendance_pm', '');
    }
    
    fetch('<?= base_url('pederasyon/saveEventAttendanceSettings') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Attendance settings saved successfully!', 'success');
            
            // Broadcast session update to attendance display windows
            broadcastSessionUpdate(currentEventId);
        } else {
            showNotification(data.message || 'Failed to save settings', 'error');
        }
    })
    .catch(error => {
        console.error('Error saving attendance settings:', error);
        showNotification('Error saving attendance settings', 'error');
    })
    .finally(() => {
        // Reset button state
        saveBtn.innerHTML = originalSaveHTML;
        saveBtn.disabled = false;
        validateAttendanceForm(); // Re-validate to set proper disabled state
    });
}

function startAttendance() {
    if (!currentEventId) {
        showNotification('No event selected', 'error');
        return;
    }
    
    // Open attendance display in a new tab
    const attendanceDisplayUrl = `<?= base_url('pederasyon/attendanceDisplay') ?>/${currentEventId}`;
    openNewAttendanceWindow(attendanceDisplayUrl, currentEventId);
    
    // Close the modal
    closeAttendanceModal();
    
    showNotification('Attendance display opened in new tab', 'success');
}

// Function to broadcast session updates to attendance display windows
function broadcastSessionUpdate(eventId) {
    try {
        // Store the update signal in localStorage with timestamp
        const updateSignal = {
            event_id: eventId,
            timestamp: Date.now(),
            action: 'session_updated'
        };
        
        localStorage.setItem('attendance_session_update', JSON.stringify(updateSignal));
        
        console.log('Session update broadcasted for event:', eventId);
        
        // Also try to send direct message to attendance display windows if possible
        // This uses postMessage to communicate with child windows
        if (window.attendanceDisplayWindows && window.attendanceDisplayWindows[eventId]) {
            try {
                window.attendanceDisplayWindows[eventId].postMessage({
                    type: 'session_updated',
                    event_id: eventId
                }, window.location.origin);
            } catch (e) {
                console.log('Could not send direct message to attendance window:', e);
            }
        }
        
    } catch (error) {
        console.error('Error broadcasting session update:', error);
    }
}

// Track opened attendance display windows
window.attendanceDisplayWindows = window.attendanceDisplayWindows || {};

// Helper function to open new attendance display tab/window for a specific event
function openNewAttendanceWindow(url, eventId) {
    // Open as a normal tab by using the named target without window features
    try {
        const targetName = `attendance_${eventId}`;
        const attendanceWindow = window.open(url, targetName);

        if (attendanceWindow) {
            // Track the opened tab/window reference for this event
            window.attendanceDisplayWindows = window.attendanceDisplayWindows || {};
            window.attendanceDisplayWindows[eventId] = attendanceWindow;

            // Clean up when tab/window is closed
            const checkClosed = setInterval(() => {
                try {
                    if (attendanceWindow.closed) {
                        delete window.attendanceDisplayWindows[eventId];
                        clearInterval(checkClosed);
                    }
                } catch (e) {
                    // Accessing properties can fail if cross-origin; just stop the checker
                    clearInterval(checkClosed);
                }
            }, 1000);

            showNotification('Attendance display opened in new tab', 'success');
        } else {
            showNotification('Failed to open attendance display. Please check popup blocker.', 'error');
        }
    } catch (err) {
        console.error('Error opening attendance tab:', err);
        showNotification('Unable to open attendance display', 'error');
    }
}

// Enhanced startAttendance function to save settings first, then track windows
function startAttendanceEnhanced() {
    if (!currentEventId) {
        showNotification('No event selected', 'error');
        return;
    }
    
    // Validate that attendance settings are configured
    const morningEnabled = document.getElementById('enableMorningSession').checked;
    const afternoonEnabled = document.getElementById('enableAfternoonSession').checked;
    
    // Check if at least one session is enabled
    if (!morningEnabled && !afternoonEnabled) {
        showNotification('Please enable at least one session before starting attendance', 'error');
        return;
    }
    
    // Validate morning session times if enabled
    if (morningEnabled) {
        const startAM = document.getElementById('startAttendanceAM').value;
        const endAM = document.getElementById('endAttendanceAM').value;
        
        if (!startAM || !endAM) {
            showNotification('Please fill in both start and end times for morning session', 'error');
            return;
        }
        
        // Validate 30 minutes minimum duration for morning session
        const startTime = new Date(`2000-01-01 ${startAM}`);
        const endTime = new Date(`2000-01-01 ${endAM}`);
        const diffMinutes = (endTime - startTime) / (1000 * 60);
        
        if (diffMinutes < 30) {
            showNotification('Morning session must be at least 30 minutes long', 'error');
            return;
        } else if (diffMinutes < 0) {
            showNotification('Morning session end time must be after start time', 'error');
            return;
        }
    }
    
    // Validate afternoon session times if enabled
    if (afternoonEnabled) {
        const startPM = document.getElementById('startAttendancePM').value;
        const endPM = document.getElementById('endAttendancePM').value;
        
        if (!startPM || !endPM) {
            showNotification('Please fill in both start and end times for afternoon session', 'error');
            return;
        }
        
        // Validate 30 minutes minimum duration for afternoon session
        const startTime = new Date(`2000-01-01 ${startPM}`);
        const endTime = new Date(`2000-01-01 ${endPM}`);
        const diffMinutes = (endTime - startTime) / (1000 * 60);
        
        if (diffMinutes < 30) {
            showNotification('Afternoon session must be at least 30 minutes long', 'error');
            return;
        } else if (diffMinutes < 0) {
            showNotification('Afternoon session end time must be after start time', 'error');
            return;
        }
    }

    // Show loading state
    const startBtn = document.getElementById('startAttendanceBtn');
    const originalHTML = startBtn.innerHTML;
    startBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Saving & Starting...';
    startBtn.disabled = true;

    // First save the attendance settings
    const formData = new FormData();
    formData.append('event_id', currentEventId);
    
    // Add morning session data if enabled
    if (morningEnabled) {
        formData.append('start_attendance_am', document.getElementById('startAttendanceAM').value);
        formData.append('end_attendance_am', document.getElementById('endAttendanceAM').value);
    } else {
        formData.append('start_attendance_am', '');
        formData.append('end_attendance_am', '');
    }
    
    // Add afternoon session data if enabled
    if (afternoonEnabled) {
        formData.append('start_attendance_pm', document.getElementById('startAttendancePM').value);
        formData.append('end_attendance_pm', document.getElementById('endAttendancePM').value);
    } else {
        formData.append('start_attendance_pm', '');
        formData.append('end_attendance_pm', '');
    }
    
    // Save settings first, then open attendance display
    fetch('<?= base_url('pederasyon/saveEventAttendanceSettings') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Settings saved successfully!', 'success');
            
            // Broadcast session update to attendance display windows
            broadcastSessionUpdate(currentEventId);
            
            // Wait a moment, then check for existing tab or open new one
            setTimeout(() => {
                const attendanceDisplayUrl = `<?= base_url('pederasyon/attendanceDisplay') ?>/${currentEventId}`;
                const targetName = `attendance_${currentEventId}`;
                
                // Try to focus/update an existing tracked tab for this event, otherwise open a new named tab
                const existingWindow = window.attendanceDisplayWindows && window.attendanceDisplayWindows[currentEventId];

                if (existingWindow && !existingWindow.closed) {
                    try {
                        // Try to focus the tab and refresh it
                        existingWindow.focus();
                        existingWindow.location.reload();
                        showNotification('Focused existing tab and refreshed page', 'success');
                    } catch (focusError) {
                        // If focus/reload fails, try to open a new window with same name (will replace if exists)
                        console.warn('Cannot focus existing tab, opening replacement:', focusError);
                        const newWindow = window.open(attendanceDisplayUrl, targetName);
                        if (newWindow) {
                            window.attendanceDisplayWindows[currentEventId] = newWindow;
                            showNotification('Opened fresh attendance display', 'success');
                        }
                    }
                } else {
                    // No valid window reference; open a new named tab
                    const newWindow = window.open(attendanceDisplayUrl, targetName);
                    if (newWindow) {
                        window.attendanceDisplayWindows = window.attendanceDisplayWindows || {};
                        window.attendanceDisplayWindows[currentEventId] = newWindow;
                        showNotification('Opened new attendance display tab', 'success');
                        
                        // Clean up when tab/window is closed
                        const checkClosed = setInterval(() => {
                            try {
                                if (newWindow.closed) {
                                    delete window.attendanceDisplayWindows[currentEventId];
                                    clearInterval(checkClosed);
                                }
                            } catch (e) {
                                clearInterval(checkClosed);
                            }
                        }, 1000);
                    } else {
                        showNotification('Failed to open attendance display. Please check popup blocker.', 'error');
                    }
                }
                
                // Close the modal
                closeAttendanceModal();
            }, 500);
            
        } else {
            showNotification(data.message || 'Failed to save settings', 'error');
        }
    })
    .catch(error => {
        console.error('Error saving attendance settings:', error);
        showNotification('Error saving attendance settings', 'error');
    })
    .finally(() => {
        // Reset button state
        startBtn.innerHTML = originalHTML;
        startBtn.disabled = false;
        validateAttendanceForm(); // Re-validate to set proper disabled state
    });
}

// Close modal when clicking outside
document.getElementById('attendanceModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAttendanceModal();
    }
});

// Test function to manually trigger session update broadcast
function testSessionUpdate() {
    if (currentEventId) {
        broadcastSessionUpdate(currentEventId);
        showNotification('Session update test broadcasted!', 'info');
    } else {
        showNotification('No event selected for test', 'error');
    }
}

// Live Attendance Monitoring Functions
function openLiveAttendanceModal(eventId) {
    // Open live attendance in a new tab
    const liveAttendanceUrl = `<?= base_url('pederasyon/liveAttendance') ?>/${eventId}`;
    window.open(liveAttendanceUrl, '_blank');
}

// Function to view attendance report for completed events
function viewAttendanceReport(eventId) {
    console.log('viewAttendanceReport called with eventId:', eventId);
    
    const event = eventsData.find(e => e.event_id == eventId);
    
    console.log('Found event for report:', event);
    
    if (!event) {
        console.error('Event not found for ID:', eventId);
        showNotification('Event not found', 'error');
        return;
    }
    
    // Navigate to attendance report page in the same tab
    const reportUrl = `<?= base_url('pederasyon/attendanceReport') ?>/${eventId}`;
    window.location.href = reportUrl;
}
</script>
