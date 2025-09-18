<?php
$session = session();
$user_id = $session->get('user_id');
$username = $session->get('username');
$barangay_id = $session->get('barangay_id');
$barangay_name = isset($barangay_name) ? $barangay_name : 'Unknown Barangay';
?>

<!-- ===== MAIN CONTENT AREA ===== -->
<div class="flex-1 flex flex-col min-h-0 ml-64 pt-16">
    <main class="flex-1 overflow-auto p-6 bg-gray-50">
        
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-blue-700"><?= esc($barangay_name) ?> Events</h1>
                    <p class="text-gray-600 mt-1">View events happening in your barangay</p>
                </div>
            </div>

            <!-- Status Filter Tabs - Only Published for KK users -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="p-4 border-b border-gray-200">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <!-- Status Filter Tabs - Only Published -->
                        <div class="flex flex-wrap gap-2">
                            <button class="status-tab px-4 py-2 rounded-lg text-sm font-medium transition-all bg-blue-600 text-white border border-blue-600" data-status="Published">
                                <i class="fas fa-check-circle mr-2"></i>Published
                            </button>
                        </div>
                        
                        <!-- Category Filter -->
                        <div class="flex items-center gap-4">
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
            </div>

            <!-- Side-by-side layout: Events Table on left, Calendar on right -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Events Table - Takes 2/3 of the space -->
                <div class="lg:col-span-2">
                    <div class="space-y-4">
                        <div class="w-full max-w-5xl mx-auto">
                            <div>
                                <div class="space-y-4 flex flex-col" style="cursor: auto;">
                                    <?php if (!empty($events)): ?>
                                        <?php foreach ($events as $event): ?>
                                            <?php
                                                $desc = esc($event['description']);
                                                $shortDesc = mb_strlen($desc) > 120 ? mb_substr($desc, 0, 120) . '...' : $desc;
                                                $modalId = 'eventModal_' . $event['event_id'];
                                                $status = isset($event['status']) ? $event['status'] : 'Published';
                                                $banner = !empty($event['event_banner']) ? "/uploads/event/" . esc($event['event_banner']) : "/assets/images/default-event-banner.svg";
                                                $category = isset($event['category']) ? $event['category'] : '';
                                            ?>
                                            <div class="flex items-center w-full event-row" data-status="<?= $status ?>" data-category="<?= esc($category) ?>">
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
                                                            <span class="inline-flex items-center leading-none px-2.5 py-1.5 text-xs font-medium bg-green-100 text-green-800 rounded-full border border-green-200">
                                                                <svg class="mr-1.5 h-2 w-2 text-green-500" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"></circle></svg>
                                                                <?= esc($status) ?>
                                                            </span>
                                                        </div>
                                                        <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2"><?= esc($event['title']) ?></h3>
                                                        <p class="text-gray-600 text-sm mb-4 line-clamp-3"><?= $shortDesc ?></p>
                                                    </div>
                                                    <div class="mt-auto">
                                                        <!-- Event Meta Information -->
                                                        <div class="flex flex-col text-xs text-gray-500 mb-2">
                                                            <span><strong>Start:</strong> <?= date('m-d-Y h:i A', strtotime($event['start_datetime'])) ?></span>
                                                            <span><strong>End:</strong> <?= date('m-d-Y h:i A', strtotime($event['end_datetime'])) ?></span>
                                                            <span><strong>Location:</strong> <?= esc($event['location']) ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            </div>
                                            
                                            <!-- Modal for this event -->
                                            <div id="<?= $modalId ?>" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[9997] hidden" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; margin: 0; padding: 0;">
                                                <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 relative max-h-[90vh] overflow-hidden">
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
                                                    <div class="p-6 overflow-y-auto max-h-96">
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

                <!-- Calendar View - Takes 1/3 of the space -->
                <div class="lg:col-span-1">
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

// Category filtering functionality
(function() {
    const categoryFilter = document.getElementById('categoryFilter');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const eventRows = document.querySelectorAll('.event-row');

    function filterEvents() {
        const selectedCategory = categoryFilter ? categoryFilter.value.toLowerCase() : '';
        
        eventRows.forEach(row => {
            const eventCategory = row.dataset.category ? row.dataset.category.toLowerCase() : '';
            
            // Show row if no category filter or category matches
            const categoryMatch = !selectedCategory || eventCategory === selectedCategory;
            
            if (categoryMatch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Event listeners
    if (categoryFilter) {
        categoryFilter.addEventListener('change', filterEvents);
    }

    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
            // Reset category filter
            if (categoryFilter) {
                categoryFilter.value = '';
            }
            
            // Apply filters (show all)
            filterEvents();
        });
    }
})();
</script>
