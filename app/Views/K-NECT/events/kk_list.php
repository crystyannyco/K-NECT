<?php
$session = session();
$user_id = $session->get('user_id');
$username = $session->get('username');
$barangay_id = $session->get('barangay_id');
$barangay_name = isset($barangay_name) ? $barangay_name : 'Unknown Barangay';
?>

<!-- ===== MAIN CONTENT AREA ===== -->
<div class="flex-1 flex flex-col min-h-0 ml-0 lg:ml-64 pt-16">
    <main class="flex-1 overflow-auto p-6 bg-gray-50">
        
        <div class="max-w-7xl mx-auto">
            <!-- Header with Title -->
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-blue-700"><?= esc($barangay_name) ?> Event List</h1>
                    <p class="text-sm text-gray-600 mt-0.5">View events happening in your barangay</p>
                </div>
            </div>
            
            <!-- Improved Side-by-side layout: Events on left (60%), Calendar on right (40%) -->
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6" style="align-items: stretch;">
                
                <!-- Events Cards - Takes 3/5 of the space (60%) -->
                <div class="lg:col-span-3 flex flex-col">
                    <!-- Status Filter Tabs at the top of events section -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-4 flex-shrink-0">
                        <div class="p-4">
                            <div class="flex flex-wrap gap-2">
                                <button class="status-tab px-4 py-2 rounded-lg text-sm font-semibold transition-all bg-blue-600 text-white border border-blue-600" data-status="all">
                                    <i class="fas fa-list mr-2"></i>All
                                </button>
                                <button class="status-tab px-4 py-2 rounded-lg text-sm font-semibold transition-all bg-white text-gray-700 border border-gray-300 hover:border-blue-600 hover:text-blue-600" data-status="ongoing">
                                    <i class="fas fa-circle mr-2"></i>Ongoing
                                </button>
                                <button class="status-tab px-4 py-2 rounded-lg text-sm font-semibold transition-all border border-gray-300 bg-white text-gray-700 hover:border-blue-600 hover:text-blue-600" data-status="upcoming">
                                    <i class="fas fa-calendar mr-2"></i>Upcoming
                                </button>
                                <button class="status-tab px-4 py-2 rounded-lg text-sm font-semibold transition-all border border-gray-300 bg-white text-gray-700 hover:border-blue-600 hover:text-blue-600" data-status="completed">
                                    <i class="fas fa-check-circle mr-2"></i>Completed
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Category Filter below status tabs -->
                    <div class="mb-4 flex items-center gap-4 flex-shrink-0">
                        <label for="categoryFilter" class="text-sm font-medium text-gray-700 whitespace-nowrap flex items-center">
                            <i class="fas fa-filter mr-1.5"></i>Category:
                        </label>
                        <select id="categoryFilter" class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                            <option value="">All Categories</option>
                            <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= esc($category) ?>"><?= esc(ucfirst($category)) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <button id="clearFilters" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 whitespace-nowrap">
                            <i class="fas fa-times mr-1.5"></i>Clear Filters
                        </button>
                    </div>
                    
                    <!-- Scrollable Events Container with Fixed Height -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden flex flex-col" style="height: calc(100vh - 320px);">
                        <div class="overflow-y-auto custom-scrollbar flex-1">
                            <div class="p-4 space-y-4">
                                    <?php if (!empty($events)): ?>
                                        <?php foreach ($events as $event): 
                                            $desc = esc($event['description']);
                                            $shortDesc = mb_strlen($desc) > 120 ? mb_substr($desc, 0, 120) . '...' : $desc;
                                            $modalId = 'eventModal_' . $event['event_id'];
                                            $banner = !empty($event['event_banner']) ? "/uploads/event/" . esc($event['event_banner']) : "/assets/images/default-event-banner.svg";
                                            $category = isset($event['category']) ? $event['category'] : '';
                                            
                                            // Determine event status based on dates
                                            $currentDateTime = new DateTime('now', new DateTimeZone('Asia/Manila'));
                                            $startDateTime = new DateTime($event['start_datetime'], new DateTimeZone('Asia/Manila'));
                                            $endDateTime = new DateTime($event['end_datetime'], new DateTimeZone('Asia/Manila'));
                                            
                                            if ($currentDateTime < $startDateTime) {
                                                $temporalStatus = 'upcoming';
                                            } elseif ($currentDateTime >= $startDateTime && $currentDateTime <= $endDateTime) {
                                                $temporalStatus = 'ongoing';
                                            } else {
                                                $temporalStatus = 'completed';
                                            }
                                        ?>
                                            <div class="flex items-center gap-2 w-full event-row" data-status="Published" data-category="<?= esc($category) ?>" data-temporal="<?= $temporalStatus ?>">
                                                <div class="group bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex flex-col sm:flex-row gap-4 w-full cursor-pointer transition-all duration-200 hover:shadow-md hover:border-blue-300" onclick="openEventModal('<?= $modalId ?>')">
                                                <!-- Event Banner -->
                                                <div class="flex-shrink-0 w-full sm:w-56 h-40 rounded-lg overflow-hidden bg-gray-100 relative">
                                                    <img class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" 
                                                         src="<?= $banner ?>" 
                                                         alt="Event Banner"
                                                         loading="lazy">
                                                </div>

                                                <!-- Event Details -->
                                                <div class="flex flex-col flex-1 min-w-0">
                                                    <!-- Category and Status Badges -->
                                                    <div class="flex flex-wrap gap-1.5 mb-2">
                                                        <?php if ($category): ?>
                                                            <span class="inline-flex items-center leading-none px-3 py-1.5 text-[11px] font-medium bg-blue-50 text-blue-700 border border-blue-300 rounded-full">
                                                                <i class="fas fa-tag mr-1 text-[10px]"></i>
                                                                <?= ucfirst($category) ?>
                                                            </span>
                                                        <?php endif; ?>
                                                        
                                                        <!-- Temporal Status Badge -->
                                                        <span class="inline-flex items-center leading-none px-3 py-1.5 text-[11px] font-medium rounded-full border
                                                            <?php
                                                            switch($temporalStatus) {
                                                                case 'upcoming':
                                                                    echo 'bg-blue-50 text-blue-700 border-blue-300';
                                                                    break;
                                                                case 'ongoing':
                                                                    echo 'bg-purple-50 text-purple-700 border-purple-300';
                                                                    break;
                                                                case 'completed':
                                                                    echo 'bg-gray-50 text-gray-700 border-gray-300';
                                                                    break;
                                                            }
                                                            ?>">
                                                            <?php
                                                            switch($temporalStatus) {
                                                                case 'upcoming':
                                                                    echo '<i class="far fa-calendar-alt mr-1 text-[10px]"></i>';
                                                                    break;
                                                                case 'ongoing':
                                                                    echo '<i class="fas fa-play-circle mr-1 text-[10px]"></i>';
                                                                    break;
                                                                case 'completed':
                                                                    echo '<i class="fas fa-check-circle mr-1 text-[10px]"></i>';
                                                                    break;
                                                            }
                                                            ?>
                                                            <?= ucfirst($temporalStatus) ?>
                                                        </span>
                                                    </div>

                                                    <!-- Event Title -->
                                                    <h3 class="text-lg font-bold text-gray-900 mb-1.5 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                                        <?= esc($event['title']) ?>
                                                    </h3>

                                                    <!-- Event Description -->
                                                    <p class="text-sm text-gray-600 line-clamp-2">
                                                        <?= $shortDesc ?>
                                                    </p>

                                                    <!-- Event Meta Information -->
                                                    <div class="space-y-1.5 text-xs text-gray-500 mt-2">
                                                        <div class="flex items-start gap-2">
                                                            <i class="fas fa-clock mt-0.5 text-gray-400 flex-shrink-0"></i>
                                                            <span class="flex-1">
                                                                <span class="font-medium text-gray-700">Start:</span> <?= date('M d, Y', strtotime($event['start_datetime'])) ?> at <?= date('g:i A', strtotime($event['start_datetime'])) ?> 
                                                                <span class="mx-1">•</span> 
                                                                <span class="font-medium text-gray-700">End:</span> <?= date('M d, Y', strtotime($event['end_datetime'])) ?> at <?= date('g:i A', strtotime($event['end_datetime'])) ?>
                                                            </span>
                                                        </div>
                                                        <div class="flex items-start gap-2">
                                                            <i class="fas fa-map-marker-alt mt-0.5 text-gray-400 flex-shrink-0"></i>
                                                            <span class="flex-1 line-clamp-1">
                                                                <span class="font-medium text-gray-700">Location:</span> <?= esc($event['location']) ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            </div>
                                            
                                            <!-- Modal for this event -->
                                            <div id="<?= $modalId ?>" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[9997] hidden" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; margin: 0; padding: 0;">
                                                <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 relative max-h-[90vh] overflow-y-auto">
                                                    <!-- Header with image background and close button -->
                                                    <div class="relative">
                                                        <?php if (!empty($event['event_banner'])): ?>
                                                            <div class="h-80 bg-cover bg-center relative" style="background-image: url('<?= $banner ?>');">
                                                                <div class="absolute inset-0 bg-black bg-opacity-40"></div>
                                                                <div class="absolute top-6 right-6">
                                                                    <button onclick="closeEventModal('<?= $modalId ?>')" class="text-white hover:text-gray-300 text-2xl font-bold w-8 h-8 flex items-center justify-center">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>
                                                                </div>
                                                                <div class="absolute bottom-6 left-6 right-6">
                                                                    <h2 class="text-3xl font-bold text-white mb-2"><?= esc($event['title']) ?></h2>
                                                                    <?php if ($category): ?>
                                                                        <span class="inline-flex items-center leading-none px-3 py-1.5 text-sm font-medium bg-white bg-opacity-90 text-blue-800 rounded-full">
                                                                            <svg class="mr-1.5 h-2 w-2 text-blue-500" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"></circle></svg>
                                                                            <?= ucfirst($category) ?>
                                                                        </span>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="h-80 bg-cover bg-center relative" style="background-image: url('<?= $banner ?>');">
                                                                <div class="absolute inset-0 bg-black bg-opacity-40"></div>
                                                                <div class="absolute top-6 right-6">
                                                                    <button onclick="closeEventModal('<?= $modalId ?>')" class="text-white hover:text-gray-300 text-2xl font-bold w-8 h-8 flex items-center justify-center">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>
                                                                </div>
                                                                <div class="absolute bottom-4 left-6 right-6">
                                                                    <h2 class="text-2xl font-bold text-white mb-2"><?= esc($event['title']) ?></h2>
                                                                    <?php if ($category): ?>
                                                                        <span class="inline-flex items-center leading-none px-3 py-1.5 text-sm font-medium bg-white bg-opacity-90 text-blue-800 rounded-full">
                                                                            <svg class="mr-1.5 h-2 w-2 text-blue-500" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"></circle></svg>
                                                                            <?= ucfirst($category) ?>
                                                                        </span>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>

                                                    <!-- Content area -->
                                                    <div class="p-6">
                                                        <!-- Single consolidated information box -->
                                                        <div class="border border-gray-200 rounded-lg p-6 bg-gray-50">
                                                            <div class="space-y-6">
                                                                <!-- Description -->
                                                                <div class="flex items-start space-x-3">
                                                                    <svg class="w-5 h-5 text-gray-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                                                                    </svg>
                                                                    <div class="flex-1">
                                                                        <span class="font-semibold text-gray-800 block mb-2">Description</span>
                                                                        <p class="text-gray-700 leading-relaxed"><?= nl2br($desc) ?></p>
                                                                    </div>
                                                                </div>

                                                                <!-- Start and End Date side by side -->
                                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                                    <!-- Start Date -->
                                                                    <div class="flex items-center space-x-3">
                                                                        <svg class="w-5 h-5 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                        </svg>
                                                                        <div>
                                                                            <span class="font-semibold text-gray-800 block">Start</span>
                                                                            <span class="text-gray-700"><?php $start = strtotime($event['start_datetime']); echo date('F j, Y', $start) . ' at ' . date('h:i A', $start); ?></span>
                                                                        </div>
                                                                    </div>

                                                                    <!-- End Date -->
                                                                    <div class="flex items-center space-x-3">
                                                                        <svg class="w-5 h-5 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                        </svg>
                                                                        <div>
                                                                            <span class="font-semibold text-gray-800 block">End</span>
                                                                            <span class="text-gray-700"><?php $end = strtotime($event['end_datetime']); echo date('F j, Y', $end) . ' at ' . date('h:i A', $end); ?></span>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Location -->
                                                                <div class="flex items-center space-x-3">
                                                                    <svg class="w-5 h-5 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                                    </svg>
                                                                    <div>
                                                                        <span class="font-semibold text-gray-800 block">Location</span>
                                                                        <span class="text-gray-700"><?= esc($event['location']) ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- NO Action buttons for KK users - View only -->
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                        
                                        <!-- Dynamic No Events Message (will be shown/hidden by JS) -->
                                        <div id="noEventsMessage" class="text-center text-gray-500 py-16" style="display: none;">
                                            <div class="max-w-md mx-auto">
                                                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gray-100 mb-6">
                                                    <i class="fas fa-calendar-times text-5xl text-gray-300"></i>
                                                </div>
                                                <h3 class="text-xl font-bold text-gray-700 mb-3">No events found.</h3>
                                                <p class="text-sm text-gray-500 leading-relaxed">There are no events available. Please check back later.</p>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center text-gray-500 py-16">
                                            <div class="max-w-md mx-auto">
                                                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gray-100 mb-6">
                                                    <i class="fas fa-calendar-times text-5xl text-gray-300"></i>
                                                </div>
                                                <h3 class="text-xl font-bold text-gray-700 mb-3">No events found.</h3>
                                                <p class="text-sm text-gray-500 leading-relaxed">There are no events available. Please check back later.</p>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Calendar View - Takes 2/5 of the space (40%) -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg shadow-lg px-6 pt-4 pb-6 cursor-pointer hover:shadow-xl transition-shadow duration-200 group h-full flex flex-col" onclick="openCalendarModal()">
                            <div class="flex items-center justify-between mb-3 flex-shrink-0">
                                <h2 class="text-xl font-bold text-gray-800">Calendar View</h2>
                                <div class="text-blue-600 group-hover:text-blue-700 transition-colors">
                                    <i class="fas fa-expand-alt text-sm"></i>
                                    <span class="text-xs ml-1">Click to enlarge</span>
                                </div>
                            </div>
                            <?php if (!empty($calendar_id)): ?>
                                <div class="flex justify-center relative flex-1">
                                    <div class="absolute inset-0 bg-blue-50 opacity-0 group-hover:opacity-10 transition-opacity duration-200 rounded-lg"></div>
                                    <iframe 
                                        src="https://calendar.google.com/calendar/embed?src=<?= urlencode($calendar_id) ?>&src=en.philippines%23holiday%40group.v.calendar.google.com&ctz=Asia%2FManila&showTitle=0&showPrint=0&showCalendars=0&mode=MONTH&bgcolor=%23FFFFFF"
                                        style="border: 0; opacity: 0; transition: opacity 0.3s ease; pointer-events: none; width: 100%; height: 100%;" 
                                        frameborder="0" 
                                        scrolling="no"
                                        class="rounded-lg shadow-md calendar-iframe"
                                        loading="lazy"
                                        onload="this.style.opacity=1"
                                    ></iframe>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-12">
                                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gray-100 mb-6">
                                        <i class="fas fa-calendar-times text-5xl text-gray-300"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-700 mb-3">Calendar Not Configured</h3>
                                    <p class="text-sm text-gray-500 leading-relaxed">Google Calendar is not set up for this barangay.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Calendar Modal - Full screen view -->
<div id="calendarModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-[10000] hidden" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; margin: 0; padding: 0;">
    <div class="bg-white rounded-xl shadow-2xl max-w-6xl w-full mx-4 transform scale-95 transition-all duration-300 ease-in-out" style="height: 90vh;">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Calendar View</h2>
                <p class="text-sm text-gray-600 mt-1"><?= esc($barangay_name) ?> Event Calendar</p>
            </div>
            <button onclick="closeCalendarModal()" class="text-gray-400 hover:text-gray-600 text-2xl font-bold w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100 transition-all duration-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <!-- Modal Content -->
        <div class="p-6" style="height: calc(90vh - 100px);">
            <?php if (!empty($calendar_id)): ?>
                <iframe 
                    id="calendarModalIframe"
                    src="https://calendar.google.com/calendar/embed?src=<?= urlencode($calendar_id) ?>&src=en.philippines%23holiday%40group.v.calendar.google.com&ctz=Asia%2FManila&showTitle=0&showPrint=0&showCalendars=0&mode=MONTH&bgcolor=%23FFFFFF"
                    style="border: 0; width: 100%; height: 100%;" 
                    frameborder="0" 
                    scrolling="no"
                    class="rounded-lg shadow-lg"
                ></iframe>
            <?php else: ?>
                <div class="flex items-center justify-center h-full">
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gray-100 mb-6">
                            <i class="fas fa-calendar-times text-5xl text-gray-300"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-700 mb-3">Calendar Not Configured</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">Google Calendar is not set up for this barangay.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Event modal functions
function openEventModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeEventModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

// Calendar modal functions
function openCalendarModal() {
    const modal = document.getElementById('calendarModal');
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        setTimeout(() => {
            const modalContent = modal.querySelector('.bg-white');
            if (modalContent) {
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }
        }, 10);
    }
}

function closeCalendarModal() {
    const modal = document.getElementById('calendarModal');
    if (modal) {
        const modalContent = modal.querySelector('.bg-white');
        if (modalContent) {
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
        }
        
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 200);
    }
}

// Combined status and category filtering
(function() {
    const tabs = document.querySelectorAll('.status-tab');
    const rows = document.querySelectorAll('.event-row');
    const categoryFilter = document.getElementById('categoryFilter');
    const clearFiltersBtn = document.getElementById('clearFilters');
    let activeStatus = 'all'; // Default to all tab
    
    
    // Auto-refresh mechanism to check for newly published scheduled events
    let autoRefreshInterval = null;
    let lastPublishTimestamp = 0;
    
    function startAutoRefresh() {
        // Check every 5 seconds for newly published events (trigger-based)
        autoRefreshInterval = setInterval(function() {
            checkForNewlyPublishedEvents();
        }, 5000); // 5000 ms = 5 seconds
        
        console.log('Auto-refresh started: Checking for newly published events every 5 seconds');
    }
    
    function stopAutoRefresh() {
        if (autoRefreshInterval) {
            clearInterval(autoRefreshInterval);
            autoRefreshInterval = null;
            console.log('Auto-refresh stopped');
        }
    }
    
    function checkForNewlyPublishedEvents() {
        // Check the publish trigger timestamp (very lightweight check)
        fetch('/events/check-publish-trigger', {
            method: 'GET',
            headers: { 
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.timestamp !== undefined) {
                const triggerTimestamp = data.timestamp;
                
                // If trigger timestamp changed, events were just published
                if (triggerTimestamp > lastPublishTimestamp && lastPublishTimestamp > 0) {
                    console.log(`New events published! Trigger timestamp: ${triggerTimestamp}, Last timestamp: ${lastPublishTimestamp}`);
                    // Reload the page to show newly published events
                    window.location.reload();
                }
                
                // Update last known timestamp
                lastPublishTimestamp = triggerTimestamp;
            }
        })
        .catch(error => {
            console.error('Error checking for newly published events:', error);
        });
    }


    // Function to show/hide no events message
    function updateNoEventsMessage(visibleCount) {
        let noEventsMsg = document.getElementById('noEventsMessage');
        
        // Get the current status for dynamic message
        let statusText = '';
        let suggestion = '';
        
        switch(activeStatus) {
            case 'all':
                statusText = 'No events found.';
                suggestion = 'There are no ongoing, upcoming, or completed events. Please check back later.';
                break;
            case 'ongoing':
                statusText = 'No ongoing events found.';
                suggestion = 'There are no events currently in progress. Check upcoming events or try a different tab.';
                break;
            case 'upcoming':
                statusText = 'No upcoming events found.';
                suggestion = 'There are no scheduled upcoming events. Check other tabs or try adjusting your filters.';
                break;
            case 'completed':
                statusText = 'No completed events found.';
                suggestion = 'There are no completed events yet. Check ongoing or upcoming events.';
                break;
            default:
                statusText = 'No events found.';
                suggestion = 'There are no events available. Please check back later.';
        }
        
        if (visibleCount === 0) {
            if (noEventsMsg) {
                // Update the message content with dynamic status
                noEventsMsg.innerHTML = `
                    <div class="max-w-md mx-auto">
                        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gray-100 mb-6">
                            <i class="fas fa-calendar-times text-5xl text-gray-300"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-700 mb-3">${statusText}</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">${suggestion}</p>
                    </div>
                `;
                noEventsMsg.style.display = 'block';
            }
        } else {
            if (noEventsMsg) {
                noEventsMsg.style.display = 'none';
            }
        }
    }

    function filterEvents() {
        const activeCategory = categoryFilter ? categoryFilter.value : '';
        let visibleCount = 0;
        
        rows.forEach(row => {
            const rowStatus = row.dataset.status;
            const rowCategory = row.dataset.category || '';
            const rowTemporal = row.dataset.temporal || '';
            
            let showRow = true;
            
            // Filter by status
            if (activeStatus === 'all') {
                // Show only ongoing, upcoming, and completed
                if (rowStatus !== 'Published' || !['ongoing', 'upcoming', 'completed'].includes(rowTemporal)) {
                    showRow = false;
                }
            } else if (activeStatus === 'ongoing' || activeStatus === 'upcoming' || activeStatus === 'completed') {
                // For temporal statuses, show only Published events with matching temporal status
                if (rowStatus !== 'Published' || rowTemporal !== activeStatus) {
                    showRow = false;
                }
            }
            
            // Filter by category
            if (activeCategory && rowCategory !== activeCategory) {
                showRow = false;
            }
            
            row.style.display = showRow ? '' : 'none';
            if (showRow) visibleCount++;
        });
        
        // Show/hide "no events" message
        updateNoEventsMessage(visibleCount);
    }

    function setActiveTab(status) {
        tabs.forEach(tab => {
            if (tab.dataset.status === status) {
                tab.classList.add('bg-blue-600', 'text-white', 'border-blue-600');
                tab.classList.remove('bg-white', 'text-gray-600', 'border-gray-300', 'hover:border-blue-600', 'hover:text-blue-600');
            } else {
                tab.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
                tab.classList.add('bg-white', 'text-gray-600', 'border-gray-300', 'hover:border-blue-600', 'hover:text-blue-600');
            }
        });
        activeStatus = status;
        filterEvents();
    }

    // Status tab event listeners
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            setActiveTab(this.dataset.status);
        });
    });

    // Category filter
    if (categoryFilter) {
        categoryFilter.addEventListener('change', filterEvents);
    }
    
    // Clear filters event listener
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
            if (categoryFilter) {
                categoryFilter.value = '';
            }
            filterEvents();
        });
    }
    
    // Initialize with "all" status as default
    setActiveTab('all');
        
    // Initialize publish trigger timestamp and start auto-refresh for scheduled events
    fetch('/events/check-publish-trigger', {
        method: 'GET',
        headers: { 
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.timestamp !== undefined) {
            lastPublishTimestamp = data.timestamp;
            console.log('Initial publish trigger timestamp:', lastPublishTimestamp);
        }
    })
    .catch(error => {
        console.error('Error fetching initial trigger timestamp:', error);
    });
    
    // Start auto-refresh to check for newly published scheduled events
    startAutoRefresh();
})();

// Stop auto-refresh when user leaves the page
window.addEventListener('beforeunload', function() {
    stopAutoRefresh();
});


// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('bg-black') && e.target.classList.contains('bg-opacity-50')) {
        const modals = document.querySelectorAll('[id^="eventModal_"]');
        modals.forEach(modal => {
            modal.classList.add('hidden');
        });
        document.body.style.overflow = 'auto';
    }
});

// Close calendar modal when clicking outside or ESC key
document.addEventListener('DOMContentLoaded', function() {
    const calendarModal = document.getElementById('calendarModal');
    if (calendarModal) {
        calendarModal.addEventListener('click', function(e) {
            if (e.target === calendarModal) {
                closeCalendarModal();
            }
        });
    }
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modals = document.querySelectorAll('[id^="eventModal_"]');
            modals.forEach(modal => {
                modal.classList.add('hidden');
            });
            
            const modal = document.getElementById('calendarModal');
            if (modal && !modal.classList.contains('hidden')) {
                closeCalendarModal();
            }
            
            document.body.style.overflow = 'auto';
        }
    });
});

// Initialize calendar iframe
const calendarIframe = document.querySelector('.calendar-iframe');
if (calendarIframe) {
    calendarIframe.style.opacity = '1';
}
</script>

<style>
/* Custom scrollbar for event list */
.custom-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: #CBD5E0 #F7FAFC;
}

.custom-scrollbar::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #F7FAFC;
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #CBD5E0;
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #A0AEC0;
}

/* Line clamp utilities */
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.status-tab.bg-blue-600 {
    box-shadow: 0 1px 3px 0 rgba(59, 130, 246, 0.3);
}
</style>

<!-- Professional Loading Screen (for future use) -->
<div id="loadingScreen" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-[100000] hidden flex items-center justify-center">
    <div class="bg-white rounded-2xl p-8 shadow-2xl max-w-sm w-full mx-4 text-center transform scale-95 opacity-0 transition-all duration-300" id="loadingContent">
        <!-- Spinner -->
        <div class="inline-flex items-center justify-center w-16 h-16 mb-4">
            <div class="relative">
                <div class="w-16 h-16 border-4 border-gray-200 border-t-blue-600 rounded-full animate-spin"></div>
                <div class="absolute inset-0 w-16 h-16 border-4 border-transparent border-r-blue-400 rounded-full animate-spin" style="animation-direction: reverse; animation-duration: 1.5s;"></div>
            </div>
        </div>
        
        <!-- Loading Text -->
        <h3 class="text-lg font-semibold text-gray-800 mb-2" id="loadingTitle">Processing...</h3>
        <p class="text-sm text-gray-600" id="loadingMessage">Please wait while we process your request.</p>
        
        <!-- Progress Dots -->
        <div class="flex justify-center space-x-1 mt-4">
            <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce"></div>
            <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.1s;"></div>
            <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
        </div>
    </div>
</div>

<!-- Sidebar Overlay for Mobile -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden hidden"></div>
