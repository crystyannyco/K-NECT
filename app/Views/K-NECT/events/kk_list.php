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
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-blue-700"><?= esc($barangay_name) ?> Events</h1>
                    <p class="text-gray-600 mt-1">View events happening in your barangay</p>
                </div>
            </div>

            <!-- Mobile Calendar View - Shows only on smaller screens -->
            <div class="xl:hidden mb-6">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Calendar View</h2>
                    <?php if (!empty($calendar_id)): ?>
                        <div class="calendar-container">
                            <iframe 
                                src="https://calendar.google.com/calendar/embed?src=<?= urlencode($calendar_id) ?>&ctz=Asia%2FManila&mode=MONTH&showTitle=0&showPrint=0&showCalendars=0&bgcolor=%23FFFFFF"
                                style="border: 0; opacity: 0; transition: opacity 0.3s ease;" 
                                width="100%" 
                                height="400" 
                                frameborder="0" 
                                scrolling="no"
                                class="rounded-lg shadow-md calendar-iframe"
                                loading="lazy"
                                onload="this.style.opacity=1">
                            </iframe>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <div class="text-gray-400 mb-4">
                                <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500">Google Calendar not configured for this barangay.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Status Filter Tabs -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="p-4 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <!-- Status Filter Tabs - Ongoing, Upcoming, Completed -->
                        <div class="flex flex-wrap gap-2">
                            <button class="status-tab px-4 py-2 rounded-lg text-sm font-medium transition-all bg-blue-600 text-white border border-blue-600" data-status="ongoing">
                                <i class="fas fa-play-circle mr-2"></i>Ongoing
                            </button>
                            <button class="status-tab px-4 py-2 rounded-lg text-sm font-medium transition-all bg-white text-gray-700 border border-gray-300 hover:bg-gray-50" data-status="upcoming">
                                <i class="fas fa-calendar-alt mr-2"></i>Upcoming
                            </button>
                            <button class="status-tab px-4 py-2 rounded-lg text-sm font-medium transition-all bg-white text-gray-700 border border-gray-300 hover:bg-gray-50" data-status="completed">
                                <i class="fas fa-check-circle mr-2"></i>Completed
                            </button>
                        </div>
                        
                        <!-- Category Filter -->
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <span class="text-sm font-medium text-gray-600 whitespace-nowrap">Category:</span>
                            <select id="categoryFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 min-w-0">
                                <option value="">All Categories</option>
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= esc($category) ?>"><?= esc(ucfirst($category)) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <button id="clearFilters" class="px-3 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors duration-200 whitespace-nowrap">
                                Clear Filters
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Side-by-side layout: Events Table on left, Calendar on right -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Events Table - Takes 2/3 of the space -->
                <div class="xl:col-span-2">
                    <div class="space-y-4">
                        <div class="w-full max-w-5xl mx-auto">
                            <div>
                                <div class="space-y-4 flex flex-col" style="cursor: auto;">
                                    <?php if (!empty($events)): ?>
                                        <?php 
                                        $currentDateTime = new DateTime();
                                        foreach ($events as $event): 
                                            $desc = esc($event['description']);
                                            $shortDesc = mb_strlen($desc) > 120 ? mb_substr($desc, 0, 120) . '...' : $desc;
                                            $modalId = 'eventModal_' . $event['event_id'];
                                            $banner = !empty($event['event_banner']) ? "/uploads/event/" . esc($event['event_banner']) : "/assets/images/default-event-banner.svg";
                                            $category = isset($event['category']) ? $event['category'] : '';
                                            
                                            // Determine event status based on dates
                                            $startDateTime = new DateTime($event['start_datetime']);
                                            $endDateTime = new DateTime($event['end_datetime']);
                                            
                                            if ($currentDateTime < $startDateTime) {
                                                $eventStatus = 'upcoming';
                                                $statusLabel = 'Upcoming';
                                                $statusColor = 'bg-yellow-100 text-yellow-800 border-yellow-200';
                                                $statusDotColor = 'text-yellow-500';
                                            } elseif ($currentDateTime >= $startDateTime && $currentDateTime <= $endDateTime) {
                                                $eventStatus = 'ongoing';
                                                $statusLabel = 'Ongoing';
                                                $statusColor = 'bg-green-100 text-green-800 border-green-200';
                                                $statusDotColor = 'text-green-500';
                                            } else {
                                                $eventStatus = 'completed';
                                                $statusLabel = 'Completed';
                                                $statusColor = 'bg-gray-100 text-gray-800 border-gray-200';
                                                $statusDotColor = 'text-gray-500';
                                            }
                                        ?>
                                            <div class="flex items-center w-full event-row" data-status="<?= $eventStatus ?>" data-category="<?= esc($category) ?>">
                                                <div class="group bg-white rounded-lg shadow p-4 flex flex-col md:flex-row items-start md:items-stretch gap-4 w-full cursor-pointer transition-transform duration-200 hover:shadow-lg hover:-translate-y-0.5" onclick="openEventModal('<?= $modalId ?>')">
                                                <div class="flex-shrink-0 w-full md:w-64 h-40 md:h-40 mb-2 md:mb-0">
                                                    <img class="object-cover shadow-lg rounded-lg group-hover:opacity-75 w-full h-full" src="<?= $banner ?>" alt="Event Banner">
                                                </div>
                                                <div class="flex flex-col flex-1 h-full justify-between">
                                                    <div>
                                                        <div class="flex flex-wrap gap-2 mb-2">
                                                            <?php if ($category): ?>
                                                                <span class="inline-flex items-center leading-none px-2.5 py-1.5 text-xs font-medium bg-blue-100 text-blue-800 rounded-full border border-blue-200">
                                                                    <svg class="mr-1.5 h-2 w-2 text-blue-500" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"></circle></svg>
                                                                    <?= ucfirst(esc($category)) ?>
                                                                </span>
                                                            <?php endif; ?>
                                                            <span class="inline-flex items-center leading-none px-2.5 py-1.5 text-xs font-medium <?= $statusColor ?> rounded-full border">
                                                                <svg class="mr-1.5 h-2 w-2 <?= $statusDotColor ?>" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"></circle></svg>
                                                                <?= $statusLabel ?>
                                                            </span>
                                                        </div>
                                                        <h4 class="text-xl font-bold text-gray-900 group-hover:text-blue-700 mb-2"><?= esc($event['title']) ?></h4>
                                                        <p class="mt-1 text-sm font-normal text-gray-700 leading-5 mb-2"><?= $shortDesc ?></p>
                                                        <div class="flex flex-col text-xs text-gray-500 mb-2">
                                                            <span><strong>Start:</strong> <?= date('F d, Y \a\t h:i A', strtotime($event['start_datetime'])) ?></span>
                                                            <span><strong>End:</strong> <?= date('F d, Y \a\t h:i A', strtotime($event['end_datetime'])) ?></span>
                                                            <span><strong>Location:</strong> <?= esc($event['location']) ?></span>
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
                                    <?php else: ?>
                                        <div class="col-span-3 text-center text-gray-500 py-8">No events found.</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Calendar View - Takes 1/3 of the space - Desktop only -->
                <div class="hidden xl:block xl:col-span-1">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Calendar View</h2>
                        <?php if (!empty($calendar_id)): ?>
                            <div class="calendar-container">
                                <iframe 
                                    src="https://calendar.google.com/calendar/embed?src=<?= urlencode($calendar_id) ?>&ctz=Asia%2FManila&mode=MONTH&showTitle=0&showPrint=0&showCalendars=0&bgcolor=%23FFFFFF"
                                    style="border: 0; opacity: 0; transition: opacity 0.3s ease;" 
                                    width="100%" 
                                    height="400" 
                                    frameborder="0" 
                                    scrolling="no"
                                    class="rounded-lg shadow-md calendar-iframe"
                                    loading="lazy"
                                    onload="this.style.opacity=1">
                                </iframe>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-8">
                                <div class="text-gray-400 mb-4">
                                    <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <p class="text-gray-500">Google Calendar not configured for this barangay.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- JavaScript for event functionality -->
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

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('bg-black') && e.target.classList.contains('bg-opacity-50')) {
        const modals = document.querySelectorAll('[id^="eventModal_"]');
        modals.forEach(modal => {
            modal.classList.add('hidden');
        });
        document.body.style.overflow = 'auto';
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modals = document.querySelectorAll('[id^="eventModal_"]');
        modals.forEach(modal => {
            modal.classList.add('hidden');
        });
        document.body.style.overflow = 'auto';
    }
});

// Status and Category filtering functionality
(function() {
    const statusTabs = document.querySelectorAll('.status-tab');
    const categoryFilter = document.getElementById('categoryFilter');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const eventRows = document.querySelectorAll('.event-row');
    let currentStatus = 'ongoing'; // Default to ongoing

    function filterEvents() {
        const selectedCategory = categoryFilter ? categoryFilter.value.toLowerCase() : '';
        
        eventRows.forEach(row => {
            const eventStatus = row.dataset.status ? row.dataset.status.toLowerCase() : '';
            const eventCategory = row.dataset.category ? row.dataset.category.toLowerCase() : '';
            
            // Show row if status matches AND (no category filter or category matches)
            const statusMatch = eventStatus === currentStatus;
            const categoryMatch = !selectedCategory || eventCategory === selectedCategory;
            
            if (statusMatch && categoryMatch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Status tab click handlers
    statusTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active class from all tabs
            statusTabs.forEach(t => {
                t.classList.remove('bg-blue-600', 'text-white');
                t.classList.add('bg-white', 'text-gray-700', 'border-gray-300', 'hover:bg-gray-50');
            });
            
            // Add active class to clicked tab
            this.classList.remove('bg-white', 'text-gray-700', 'border-gray-300', 'hover:bg-gray-50');
            this.classList.add('bg-blue-600', 'text-white');
            
            // Update current status
            currentStatus = this.dataset.status;
            
            // Apply filters
            filterEvents();
        });
    });

    // Category filter change handler
    if (categoryFilter) {
        categoryFilter.addEventListener('change', filterEvents);
    }

    // Clear filters button handler
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
            // Don't reset status filter - keep current tab
            // Only reset category filter
            if (categoryFilter) {
                categoryFilter.value = '';
            }
            
            // Apply filters (maintains current status tab)
            filterEvents();
        });
    }

    // Initial filter on page load
    filterEvents();
})();
</script>

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
