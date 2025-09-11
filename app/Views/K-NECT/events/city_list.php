<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>City-wide Event List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body, .font-sans {
            font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji' !important;
        }
        
        /* Ensure Font Awesome icons display correctly */
        .fa, .fas, .far, .fab {
            font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands" !important;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen font-sans">
<!-- ===== MAIN CONTENT AREA ===== -->
<div class="flex-1 flex flex-col min-h-0 ml-64 pt-16">
    <main class="flex-1 overflow-auto p-6 bg-gray-50">
        
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-blue-700">City-wide Event List</h1>
                    <p class="text-gray-600 mt-1">Manage events across all barangays</p>
                </div>
                <div>
                    <button id="addCityWideBtn" onclick="openEventModal('add')" class="bg-blue-600 text-white font-semibold py-2 px-6 rounded-lg hover:bg-blue-700 transition duration-200">
                        <i class="fas fa-plus mr-2"></i>Add New City-wide Event
                    </button>
                </div>
            </div>

            <!-- Tabbed Google Calendar UI -->
            <?php if (!empty($calendar_tabs)): ?>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                    <div class="p-4">
                        <div class="flex items-center gap-2">
                            <!-- Left Arrow Button -->
                            <button 
                                id="scrollLeft" 
                                class="bg-white border border-gray-300 rounded-full p-2 shadow-sm hover:bg-gray-50 transition duration-200 flex-shrink-0"
                                type="button"
                                aria-label="Scroll left"
                            >
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <!-- Scrollable tabs container -->
                            <div id="tabBarWrapper" class="overflow-x-auto whitespace-nowrap scrollbar-hide cursor-grab select-none bg-gray-100 p-1 rounded-lg relative flex-1 min-w-0" style="user-select: none;">
                                <?php foreach ($calendar_tabs as $i => $tab): ?>
                                    <button 
                                        data-barangay-id="<?= isset($barangays[$i]['barangay_id']) ? (int)$barangays[$i]['barangay_id'] : 0 ?>"
                                        class="<?= $i === 0 ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 hover:text-gray-900' ?> px-4 py-2 rounded-md font-medium transition duration-200 inline-block"
                                        onclick="showCalendar('cal<?= $i ?>', this)"
                                        style="min-width: 120px; margin-right: 4px;"
                                    >
                                        <?= esc($tab['label']) ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                            <!-- Right Arrow Button -->
                            <button 
                                id="scrollRight" 
                                class="bg-white border border-gray-300 rounded-full p-2 shadow-sm hover:bg-gray-50 transition duration-200 flex-shrink-0"
                                type="button"
                                aria-label="Scroll right"
                            >
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            <script>
            // Drag-to-scroll for tab bar
            (function() {
                const tabBar = document.getElementById('tabBarWrapper');
                let isDown = false;
                let startX, scrollLeft;
                tabBar.addEventListener('mousedown', (e) => {
                    isDown = true;
                    tabBar.classList.add('cursor-grabbing');
                    startX = e.pageX - tabBar.offsetLeft;
                    scrollLeft = tabBar.scrollLeft;
                });
                tabBar.addEventListener('mouseleave', () => {
                    isDown = false;
                    tabBar.classList.remove('cursor-grabbing');
                });
                tabBar.addEventListener('mouseup', () => {
                    isDown = false;
                    tabBar.classList.remove('cursor-grabbing');
                });
                tabBar.addEventListener('mousemove', (e) => {
                    if (!isDown) return;
                    e.preventDefault();
                    const x = e.pageX - tabBar.offsetLeft;
                    const walk = (x - startX) * 1.5; // scroll-fast
                    tabBar.scrollLeft = scrollLeft - walk;
                });
                // Arrow button scroll
                document.getElementById('scrollLeft').addEventListener('click', function() {
                    tabBar.scrollBy({ left: -200, behavior: 'smooth' });
                });
                document.getElementById('scrollRight').addEventListener('click', function() {
                    tabBar.scrollBy({ left: 200, behavior: 'smooth' });
                });
            })();

            // Global filtering state
            let globalActiveStatus = 'Published';
            let globalActiveBarangayId = 0;
            let globalActiveCategory = '';

            // Consolidated filtering function
            function filterEvents() {
                document.querySelectorAll('.event-row').forEach(function(row) {
                    const rowStatus = row.getAttribute('data-status');
                    const rowBarangayId = parseInt(row.getAttribute('data-barangay-id'));
                    const rowCategory = row.getAttribute('data-category') || '';
                    
                    let shouldShow = (globalActiveBarangayId === 0
                        ? rowStatus === globalActiveStatus
                        : rowStatus === 'Published') && rowBarangayId === globalActiveBarangayId;
                    
                    // Apply category filter
                    if (shouldShow && globalActiveCategory && rowCategory !== globalActiveCategory) {
                        shouldShow = false;
                    }
                    
                    row.style.display = shouldShow ? '' : 'none';
                });
            }

            // On tab click, filter events
            const tabButtons = document.querySelectorAll('#tabBarWrapper button');
            tabButtons.forEach((btn, idx) => {
                btn.addEventListener('click', function() {
                    // Remove selected style from all tabs
                    tabButtons.forEach(b => b.classList.remove('bg-blue-600', 'text-white'));
                    tabButtons.forEach(b => b.classList.add('bg-white', 'text-gray-600'));
                    // Add selected style to clicked tab
                    btn.classList.remove('bg-white', 'text-gray-600');
                    btn.classList.add('bg-blue-600', 'text-white');
                    // Get barangay_id for this tab
                    let barangayId = 0;
                    <?php foreach ($calendar_tabs as $i => $tab): ?>
                        if (idx === <?= $i ?>) barangayId = <?= isset($barangays[$i]['barangay_id']) ? (int)$barangays[$i]['barangay_id'] : 0 ?>;
                    <?php endforeach; ?>
                    globalActiveBarangayId = barangayId;
                    filterEvents();
                    
                    // Update bulk selection UI when tab changes
                    setTimeout(() => {
                        const updateEvent = new Event('tabChanged');
                        document.dispatchEvent(updateEvent);
                    }, 0);
                    
                    // Show/hide Add City-wide Event button
                    const addBtn = document.getElementById('addCityWideBtn');
                    if (idx === 0) {
                        addBtn.style.display = '';
                    } else {
                        addBtn.style.display = 'none';
                    }
                });
            });
            // On page load, show only events for the first tab and show/hide add button
            window.addEventListener('DOMContentLoaded', function() {
                let firstBarangayId = 0;
                <?php if (isset($barangays[0]['barangay_id'])): ?>
                    firstBarangayId = <?= (int)$barangays[0]['barangay_id'] ?>;
                <?php endif; ?>
                globalActiveBarangayId = firstBarangayId;
                filterEvents();
                // Show add button only for first tab
                const addBtn = document.getElementById('addCityWideBtn');
                addBtn.style.display = '';
            });
            </script>
            
            <!-- Side-by-side layout: Events Table on left, Calendar on right -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Events Cards - Takes 2/3 of the space -->
                <div class="lg:col-span-2">
                    <!-- Dynamic Container for City-wide Events -->
                    <div id="cityWideContainer" class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                        <div class="p-4 border-b border-gray-200">
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <!-- Status Filter Tabs -->
                                <div id="statusTabsWrapper" class="flex flex-wrap gap-2">
                                    <button class="status-tab px-4 py-2 rounded-lg text-sm font-medium transition-all bg-blue-600 text-white border border-blue-600" data-status="Published">
                                        <i class="fas fa-check-circle mr-2"></i>Published
                                    </button>
                                    <button class="status-tab px-4 py-2 rounded-lg text-sm font-medium transition-all border border-gray-300 bg-white text-gray-600 hover:border-blue-600 hover:text-blue-600" data-status="Draft">
                                        <i class="fas fa-file-alt mr-2"></i>Draft
                                    </button>
                                    <button class="status-tab px-4 py-2 rounded-lg text-sm font-medium transition-all border border-gray-300 bg-white text-gray-600 hover:border-blue-600 hover:text-blue-600" data-status="Scheduled">
                                        <i class="fas fa-clock mr-2"></i>Scheduled
                                    </button>
                                </div>
                                
                                <!-- Add New Event Button and Category Filter -->
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
                    
                    <!-- Header for Barangay-specific Events -->
                    <div id="barangaySpecificContainer" class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 hidden">
                        <div class="p-4 border-b border-gray-200">
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <!-- Barangay-specific Events Title -->
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-800">Barangay-specific Events</h2>
                                    <p class="text-sm text-gray-600 mt-1">Published events from this barangay</p>
                                </div>
                                
                                <!-- Category Filter for Barangay Events -->
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-medium text-gray-600">Category:</span>
                                        <select id="barangayCategoryFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="">All Categories</option>
                                            <?php if (!empty($categories)): ?>
                                                <?php foreach ($categories as $category): ?>
                                                    <option value="<?= esc($category) ?>"><?= esc(ucfirst($category)) ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <button id="clearBarangayFilters" class="px-3 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                            Clear Filters
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
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
                                        $isCityWide = ((int)$event['barangay_id'] === 0);
                                        
                                        // Determine if user can edit/delete this event
                                        $canEditDelete = false;
                                        if (isset($calendar_role)) {
                                            if ($calendar_role === 'super_admin') {
                                                $canEditDelete = true; // Super admin can edit/delete any event
                                            } elseif ($calendar_role === 'admin') {
                                                // Regular admin can edit events from their own barangay or city-wide events
                                                $userBarangayId = session('barangay_id');
                                                $canEditDelete = ($event['barangay_id'] == $userBarangayId || $event['barangay_id'] == 0);
                                            }
                                        }
                                    ?>
                                    <div class="flex items-center w-full event-row" data-barangay-id="<?= (int)$event['barangay_id'] ?>" data-status="<?= $status ?>" data-category="<?= esc($category) ?>">
                                        <?php if ($canEditDelete && $isCityWide): ?>
                                        <div class="flex-shrink-0 flex flex-col items-center justify-center h-full pr-2" onclick="event.stopPropagation();">
                                            <input type="checkbox" class="event-checkbox w-5 h-5" value="<?= $event['event_id'] ?>">
                                        </div>
                                        <?php endif; ?>
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
                                                                <?= ucfirst($category) ?>
                                                            </span>
                                                        <?php endif; ?>
                                                        <!-- Status Badge -->
                                                        <span class="inline-flex items-center leading-none px-2.5 py-1.5 text-xs font-medium rounded-full border
                                                            <?php
                                                            switch($status) {
                                                                case 'Draft':
                                                                    echo 'bg-yellow-100 text-yellow-800 border-yellow-200';
                                                                    break;
                                                                case 'Scheduled':
                                                                    echo 'bg-orange-100 text-orange-800 border-orange-200';
                                                                    break;
                                                                case 'Published':
                                                                    echo 'bg-green-100 text-green-800 border-green-200';
                                                                    break;
                                                                default:
                                                                    echo 'bg-gray-100 text-gray-800 border-gray-200';
                                                            }
                                                            ?>">
                                                            <?= $status ?>
                                                        </span>
                                                    </div>
                                                    <div class="text-xl font-bold text-gray-900 mb-2 text-left"><?= esc($event['title']) ?></div>
                                                    <p class="mt-1 text-sm font-normal text-gray-700 leading-5 mb-2"><?= $shortDesc ?></p>
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
                                            <?php if ($canEditDelete && $isCityWide): ?>
                                            <!-- Action buttons -->
                                            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end space-x-4">
                                                <button onclick="openEventModal('edit', <?= $event['event_id'] ?>)" class="bg-blue-600 text-white font-semibold py-2 px-6 rounded-lg hover:bg-blue-700 transition duration-200 flex items-center">
                                                    <i class="fas fa-edit mr-2"></i>Edit
                                                </button>
                                                <button onclick="showDeleteConfirmationModal('single', <?= $event['event_id'] ?>)" class="bg-red-600 text-white font-semibold py-2 px-6 rounded-lg hover:bg-red-700 transition duration-200 flex items-center">
                                                    <i class="fas fa-trash mr-2"></i>Delete
                                                </button>
                                            </div>
                                            <?php endif; ?>
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
                    
                    <!-- Bulk Delete Bar -->
                    <div id="bulkDeleteBar" class="fixed bottom-6 left-1/2 transform -translate-x-1/2 z-50 hidden">
                        <div class="bg-white rounded-lg shadow-lg p-4 flex items-center space-x-4">
                            <span id="selectedCount" class="text-gray-700 font-medium">0 events selected</span>
                            <button id="bulkDeleteBtn" class="bg-red-600 text-white font-semibold py-2 px-6 rounded-lg hover:bg-red-700 transition duration-200">
                                <i class="fas fa-trash-alt mr-2"></i>Delete Selected
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Calendar - Takes 1/3 of the space -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Calendar View</h2>
                        <?php foreach ($calendar_tabs as $i => $tab): ?>
                            <div id="cal<?= $i ?>" class="calendar-embed<?= $i === 0 ? ' active' : '' ?>">
                                <div class="flex justify-center">
                                    <iframe 
                                        src="<?= $i === 0 ? 'https://calendar.google.com/calendar/embed?src=' . urlencode($tab['calendar_id']) . '&ctz=Asia%2FManila&showTitle=0&showPrint=0&showCalendars=0&mode=MONTH&bgcolor=%23FFFFFF' : 'about:blank' ?>"
                                        data-src="https://calendar.google.com/calendar/embed?src=<?= urlencode($tab['calendar_id']) ?>&ctz=Asia%2FManila&showTitle=0&showPrint=0&showCalendars=0&mode=MONTH&bgcolor=%23FFFFFF"
                                        style="border: 0; opacity: 0; transition: opacity 0.3s ease;" 
                                        width="100%" 
                                        height="400" 
                                        frameborder="0" 
                                        scrolling="no"
                                        class="rounded-lg shadow-md calendar-iframe"
                                        loading="lazy"
                                        onload="this.style.opacity=1"
                                    ></iframe>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        </div>

        <!-- Add/Edit Event Modal - Moved outside main content to cover full viewport -->
        <div id="eventModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[9998] hidden" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; margin: 0; padding: 0;">
            <div class="bg-white rounded-lg shadow-xl p-8 max-w-6xl w-full mx-4 relative max-h-[90vh] overflow-y-auto">
                <div id="eventModalContent">
                    <!-- The form will be loaded here via AJAX -->
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal - Moved outside main content with improved positioning -->
        <div id="deleteConfirmationModal" class="fixed inset-0 z-[9999] hidden opacity-0 transition-all duration-300 ease-in-out" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; margin: 0; padding: 0;">
            <!-- Dark overlay -->
            <div class="absolute inset-0 bg-black bg-opacity-60"></div>
            
            <!-- Modal content -->
            <div class="relative flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 transform scale-95 transition-all duration-300 ease-in-out border border-gray-200" id="deleteConfirmationModalContent">
                    <!-- Close button -->
                    <button onclick="closeDeleteConfirmationModal()" class="absolute -top-3 -right-3 bg-white rounded-full p-2 shadow-xl border border-gray-300 hover:bg-gray-100 transition-all duration-200 z-10">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    
                    <!-- Content -->
                    <div class="p-6">
                        <div class="text-center mb-6">
                            <div id="deleteConfirmationIcon" class="mx-auto mb-4">
                                <svg class="w-16 h-16 text-red-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </div>
                            <h3 id="deleteConfirmationTitle" class="text-xl font-semibold mb-2 text-gray-800">Delete Event</h3>
                            <p id="deleteConfirmationMessage" class="text-gray-600">Are you sure you want to delete this event? This action cannot be undone.</p>
                        </div>
                        
                        <!-- Buttons -->
                        <div class="flex gap-3">
                            <button onclick="closeDeleteConfirmationModal()" class="flex-1 py-2 px-4 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg font-medium transition-all duration-200">
                                <i class="fas fa-times mr-2"></i>Cancel
                            </button>
                            <button id="deleteConfirmButton" class="flex-1 py-2 px-4 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-all duration-200">
                                <i class="fas fa-trash mr-2"></i>Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .calendar-embed { display: none; }
        .calendar-embed.active { display: block; }
        
        /* Hide scrollbar but keep functionality */
        .scrollbar-hide {
            -ms-overflow-style: none;  /* Internet Explorer 10+ */
            scrollbar-width: none;  /* Firefox */
        }
        .scrollbar-hide::-webkit-scrollbar { 
            display: none;  /* Safari and Chrome */
        }
        
        /* Prevent text selection during drag */
        .select-none {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
    </style>

    <script>
        // Drag functionality variables
        let isDragging = false;
        let startX = 0;
        let scrollLeft = 0;
        let isClickingTab = false;

        function showCalendar(id, btn) {
            // Hide all calendar embeds
            document.querySelectorAll('.calendar-embed').forEach(el => el.classList.remove('active'));
            
            // Show selected calendar
            const selectedCalendar = document.getElementById(id);
            selectedCalendar.classList.add('active');
            
            // Load iframe if not already loaded
            const iframe = selectedCalendar.querySelector('iframe');
            if (iframe && (iframe.src === 'about:blank' || iframe.src === window.location.href)) {
                iframe.style.opacity = '0';
                iframe.src = iframe.dataset.src;
            }
            
            // Update tab buttons
            const tabContainer = btn.parentElement;
            tabContainer.querySelectorAll('button').forEach(el => {
                el.classList.remove('bg-blue-600', 'text-white', 'border-blue-700', 'shadow-lg');
                el.classList.add('text-gray-700', 'bg-white', 'border-gray-300');
            });
            btn.classList.remove('text-gray-700', 'bg-white', 'border-gray-300');
            btn.classList.add('bg-blue-600', 'text-white', 'border-blue-700', 'shadow-lg');
            
            // Scroll to the selected tab
            scrollToTab(btn);
        }
        
        function scrollTabs(direction) {
            const container = document.getElementById('tabsContainer');
            const scrollAmount = 200; // Adjust scroll amount as needed
            
            if (direction === 'left') {
                container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            } else {
                container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            }
            
            // Update scroll button visibility
            updateScrollButtons();
        }
        
        function scrollToTab(selectedTab) {
            const container = document.getElementById('tabsContainer');
            const containerRect = container.getBoundingClientRect();
            const tabRect = selectedTab.getBoundingClientRect();
            
            // Calculate if tab is visible
            const isVisible = tabRect.left >= containerRect.left && tabRect.right <= containerRect.right;
            
            if (!isVisible) {
                // Scroll to make the tab visible
                const scrollLeft = tabRect.left - containerRect.left - (containerRect.width / 2) + (tabRect.width / 2);
                container.scrollBy({ left: scrollLeft, behavior: 'smooth' });
            }
        }
        
        function updateScrollButtons() {
            const container = document.getElementById('tabsContainer');
            const scrollLeft = container.scrollLeft;
            const scrollWidth = container.scrollWidth;
            const clientWidth = container.clientWidth;
            
            // Show/hide left arrow
            document.getElementById('scrollLeft').style.display = scrollLeft > 0 ? 'block' : 'none';
            
            // Show/hide right arrow
            document.getElementById('scrollRight').style.display = scrollLeft < scrollWidth - clientWidth - 1 ? 'block' : 'none';
        }
        
        // Initialize scroll buttons on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateScrollButtons();
            
            // Initialize first calendar iframe
            const firstIframe = document.querySelector('.calendar-embed.active iframe');
            if (firstIframe) {
                firstIframe.style.opacity = '1';
            }
        });

        function openEventModal(mode, eventId = null) {
            let url;
            if (mode === 'add') {
                url = '/events/create';
            } else if (mode === 'edit') {
                url = `/events/edit/${eventId}`;
            } else {
                // View mode - show existing modal
                document.getElementById(mode).classList.remove('hidden');
                return;
            }
            
            // Load form via AJAX
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                document.getElementById('eventModalContent').innerHTML = html;
                document.getElementById('eventModal').classList.remove('hidden');

                // Initialize toggle functionality for dynamically loaded form
                initializeToggleFunctionality();

                // Initialize file upload handlers from parent scope (injected scripts may not run)
                if (typeof initializeEventFormUpload === 'function') {
                    initializeEventFormUpload();
                }

                // Add form submission handler
                const form = document.getElementById('eventForm');
                if (form) {
                    // Ensure single handler
                    form.removeEventListener('submit', handleFormSubmit);
                    form.addEventListener('submit', handleFormSubmit);
                }
            })
            .catch(error => {
                console.error('Error loading form:', error);
            });
        }

        function initializeToggleFunctionality() {
            console.log('Initializing toggle functionality for dynamically loaded form');
            
            // Get all required elements
            const schedulingEnabled = document.getElementById('scheduling_enabled');
            const schedulingDatetimeGroup = document.getElementById('scheduling_datetime_group');
            const smsNotificationEnabled = document.getElementById('sms_notification_enabled');
            const smsRecipientGroup = document.getElementById('sms_recipient_group');
            const smsRecipientScope = document.querySelectorAll('input[name="sms_recipient_scope"]');
            const specificBarangaysGroup = document.getElementById('specific_barangays_group');
            
            console.log('Elements found:', {
                schedulingEnabled: !!schedulingEnabled,
                schedulingDatetimeGroup: !!schedulingDatetimeGroup,
                smsNotificationEnabled: !!smsNotificationEnabled,
                smsRecipientGroup: !!smsRecipientGroup,
                smsRecipientScope: smsRecipientScope.length,
                specificBarangaysGroup: !!specificBarangaysGroup
            });
            
            // Check initial states
            console.log('Initial toggle states:', {
                schedulingEnabled: schedulingEnabled ? schedulingEnabled.checked : 'element not found',
                smsNotificationEnabled: smsNotificationEnabled ? smsNotificationEnabled.checked : 'element not found'
            });
            
            // Scheduling toggle functionality
            if (schedulingEnabled) {
                console.log('Setting up scheduling toggle listener');
                schedulingEnabled.addEventListener('change', function() {
                    console.log('Scheduling toggle changed:', this.checked);
                    if (this.checked) {
                        schedulingDatetimeGroup.classList.remove('hidden');
                        console.log('Scheduling datetime group shown');
                    } else {
                        schedulingDatetimeGroup.classList.add('hidden');
                        console.log('Scheduling datetime group hidden');
                    }
                });
                
                // Initialize scheduling visibility
                if (schedulingEnabled.checked) {
                    console.log('Initializing scheduling visibility - toggle is checked');
                    schedulingDatetimeGroup.classList.remove('hidden');
                }
            } else {
                console.error('Scheduling enabled element not found!');
            }
            
            // SMS notification toggle functionality
            if (smsNotificationEnabled) {
                console.log('Setting up SMS notification toggle listener');
                smsNotificationEnabled.addEventListener('change', function() {
                    console.log('SMS notification toggle changed:', this.checked);
                    if (this.checked) {
                        smsRecipientGroup.classList.remove('hidden');
                        console.log('SMS recipient group shown');
                    } else {
                        smsRecipientGroup.classList.add('hidden');
                        console.log('SMS recipient group hidden');
                    }
                });
                
                // Initialize SMS visibility
                if (smsNotificationEnabled.checked) {
                    console.log('Initializing SMS visibility - toggle is checked');
                    smsRecipientGroup.classList.remove('hidden');
                }
            } else {
                console.error('SMS notification enabled element not found!');
            }
            
            // SMS recipient scope radio button functionality
            if (smsRecipientScope.length > 0) {
                console.log('Setting up SMS recipient scope listeners');
                smsRecipientScope.forEach(radio => {
                    radio.addEventListener('change', function() {
                        console.log('SMS recipient scope changed:', this.value);
                        if (this.value === 'specific_barangays') {
                            specificBarangaysGroup.classList.remove('hidden');
                            console.log('Specific barangays group shown');
                            // Clear recipient roles when switching to specific barangays
                            clearRecipientRoles();
                            // Disable recipient roles until barangays are selected
                            updateRecipientRolesAvailability();
                        } else {
                            specificBarangaysGroup.classList.add('hidden');
                            console.log('Specific barangays group hidden');
                            // Clear recipient roles when switching to all barangays
                            clearRecipientRoles();
                            // Enable recipient roles for "all barangays"
                            enableRecipientRoles();
                        }
                    });
                });
            } else {
                console.log('No SMS recipient scope radio buttons found');
            }
            
            // Barangay selection functionality
            const barangayCheckboxes = document.querySelectorAll('input[name="sms_recipient_barangays[]"]');
            barangayCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    console.log('Barangay selection changed');
                    updateRecipientRolesAvailability();
                    
                    // Check if any barangays are selected
                    const selectedBarangays = document.querySelectorAll('input[name="sms_recipient_barangays[]"]:checked');
                    if (selectedBarangays.length === 0) {
                        console.log('No barangays selected, clearing recipient roles');
                        clearRecipientRoles();
                    }
                });
            });
            
            // All SK Officials vs Individual Roles logic
            const allOfficialsCheckbox = document.querySelector('.all-officials-checkbox');
            const individualRoleCheckboxes = document.querySelectorAll('.individual-role-checkbox');
            
            if (allOfficialsCheckbox) {
                allOfficialsCheckbox.addEventListener('change', function() {
                    console.log('All SK Officials checkbox changed:', this.checked);
                    if (this.checked) {
                        // Uncheck all individual roles
                        individualRoleCheckboxes.forEach(checkbox => {
                            checkbox.checked = false;
                        });
                        console.log('Individual role checkboxes unchecked');
                    }
                });
            }
            
            // Individual role checkboxes
            individualRoleCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    console.log('Individual role checkbox changed:', this.value, this.checked);
                    if (this.checked) {
                        // Uncheck "All SK Officials"
                        if (allOfficialsCheckbox) {
                            allOfficialsCheckbox.checked = false;
                            console.log('All SK Officials checkbox unchecked');
                        }
                    }
                });
            });
            
            // Helper functions
            function updateRecipientRolesAvailability() {
                const recipientRolesGroup = document.getElementById('recipient_roles_group');
                const selectedBarangays = document.querySelectorAll('input[name="sms_recipient_barangays[]"]:checked');
                
                if (selectedBarangays.length > 0) {
                    console.log('Barangays selected, enabling recipient roles');
                    recipientRolesGroup.classList.remove('opacity-50', 'pointer-events-none');
                } else {
                    console.log('No barangays selected, disabling recipient roles');
                    recipientRolesGroup.classList.add('opacity-50', 'pointer-events-none');
                }
            }
            
            function clearRecipientRoles() {
                const allRecipientRoleCheckboxes = document.querySelectorAll('input[name="sms_recipient_roles[]"]');
                allRecipientRoleCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
                console.log('All recipient role checkboxes cleared');
            }
            
            function enableRecipientRoles() {
                const recipientRolesGroup = document.getElementById('recipient_roles_group');
                console.log('Enabling recipient roles for all barangays');
                recipientRolesGroup.classList.remove('opacity-50', 'pointer-events-none');
            }
            
            // Initialize recipient roles availability
            updateRecipientRolesAvailability();
            
            // Schedule button functionality
            const scheduleBtn = document.getElementById('schedule_btn');
            if (scheduleBtn) {
                console.log('Setting up schedule button listener');
                scheduleBtn.addEventListener('click', function(e) {
                    const isSchedulingEnabled = schedulingEnabled && schedulingEnabled.checked;
                    const scheduledDatetime = document.getElementById('scheduled_publish_datetime').value;
                    
                    if (isSchedulingEnabled && !scheduledDatetime) {
                        e.preventDefault();
                        showNotification('Please select a scheduled publish date and time.', 'error');
                    }
                });
            } else {
                console.log('Schedule button not found');
            }
            
            console.log('Toggle functionality initialization complete');
        }

        function closeEventModal(modalId = null) {
            if (modalId) {
                document.getElementById(modalId).classList.add('hidden');
            } else {
                document.getElementById('eventModal').classList.add('hidden');
                document.getElementById('eventModalContent').innerHTML = '';
            }
        }

        function handleFormSubmit(e) {
            e.preventDefault();
            
            const form = e.target;
            const formData = new FormData(form);
            
            // Get the submit action from the clicked button
            const submitAction = e.submitter?.value || 'publish';
            formData.set('submit_action', submitAction);
            
            console.log('Form submission - submit action:', submitAction);
            
            // Clear all existing field errors
            clearAllFieldErrors();
            clearSchedulingError();
            
            let hasErrors = false;
            let firstErrorField = null;
            
            // Validate fields based on submission type
            const validationResult = validateFields(form, submitAction);
            if (!validationResult.isValid) {
                hasErrors = true;
                if (!firstErrorField) firstErrorField = validationResult.firstErrorField;
            }
            
            // Validate scheduling when publishing or scheduling
            if (submitAction === 'publish' || submitAction === 'schedule') {
                const schedulingEnabled = document.getElementById('scheduling_enabled');
                const scheduledDatetime = document.getElementById('scheduled_publish_datetime');
                
                // For schedule action, always require datetime
                if (submitAction === 'schedule') {
                    // Auto-enable scheduling if not already enabled (show the section first)
                    if (schedulingEnabled && !schedulingEnabled.checked) {
                        schedulingEnabled.checked = true;
                        schedulingEnabled.dispatchEvent(new Event('change'));
                    }
                    
                    if (!scheduledDatetime || !scheduledDatetime.value) {
                        showSchedulingError('Please select a scheduled publish date and time.');
                        hasErrors = true;
                        if (!firstErrorField) firstErrorField = scheduledDatetime;
                    }
                }
                
                // For publish action with scheduling enabled, require datetime
                if (submitAction === 'publish' && schedulingEnabled && schedulingEnabled.checked && 
                    scheduledDatetime && !scheduledDatetime.value) {
                    showSchedulingError('Please select a scheduled publish date and time when scheduling is enabled.');
                    hasErrors = true;
                    if (!firstErrorField) firstErrorField = scheduledDatetime;
                }
                
                // Validate that scheduled datetime is in the future
                if (scheduledDatetime && scheduledDatetime.value) {
                    const currentTime = new Date();
                    const scheduledTime = new Date(scheduledDatetime.value);
                    
                    if (scheduledTime <= currentTime) {
                        showSchedulingError('Scheduled publish date and time must be after the current date and time.');
                        hasErrors = true;
                        if (!firstErrorField) firstErrorField = scheduledDatetime;
                    }
                }
            }
            
            // If any validation failed, focus on first error and don't submit
            if (hasErrors) {
                if (firstErrorField) {
                    firstErrorField.focus();
                }
                return;
            }
            
            // Submit the form via AJAX
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success notification based on submit action
                    let successMessage = '';
                    switch(submitAction) {
                        case 'draft':
                            successMessage = 'Event saved as draft successfully!';
                            break;
                        case 'schedule':
                            successMessage = 'Event scheduled successfully!';
                            break;
                        case 'publish':
                            successMessage = 'Event published successfully!';
                            break;
                        default:
                            successMessage = 'Event saved successfully!';
                    }
                    showNotification(successMessage, 'success');
                    
                    // Close modal and refresh page after a short delay
                    closeEventModal();
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    // If server returned a file-specific error, display it inline in the form
                    const fileErrorEl = document.getElementById('file-error');
                    if (fileErrorEl && data.message) {
                        fileErrorEl.textContent = data.message;
                        fileErrorEl.classList.remove('hidden');
                        const input = document.getElementById('event_banner');
                        if (input) {
                            input.classList.add('border-red-500');
                            input.classList.remove('border-gray-300');
                        }
                    } else {
                        showNotification('Error: ' + (data.message || 'Unknown error occurred'), 'error');
                    }
                }
            })
            .catch(error => {
                console.error('Error submitting form:', error);
                showNotification('Error submitting form. Please try again.', 'error');
            });
        }

        // Helper functions for scheduling error messages
        function showSchedulingError(message) {
            // Get or create scheduling error element
            let errorElement = document.getElementById('scheduling-error');
            if (!errorElement) {
                errorElement = document.createElement('p');
                errorElement.id = 'scheduling-error';
                errorElement.className = 'text-red-500 text-xs sm:text-sm mt-1';
                
                // Insert after the scheduled datetime field
                const datetimeField = document.getElementById('scheduled_publish_datetime');
                if (datetimeField && datetimeField.parentNode) {
                    datetimeField.parentNode.insertBefore(errorElement, datetimeField.nextSibling);
                } else {
                    // Fallback: insert after scheduling section
                    const schedulingSection = document.getElementById('scheduling_datetime_group');
                    if (schedulingSection) {
                        schedulingSection.appendChild(errorElement);
                    }
                }
            }
            
            errorElement.textContent = message;
            errorElement.style.display = 'block';
            
            // Add field error styling and shake animation to datetime input
            const datetimeInput = document.getElementById('scheduled_publish_datetime');
            if (datetimeInput) {
                datetimeInput.classList.add('field-error', 'shake');
                // Remove shake animation after it completes
                setTimeout(() => {
                    datetimeInput.classList.remove('shake');
                }, 500);
            }
        }

        function clearSchedulingError() {
            const errorElement = document.getElementById('scheduling-error');
            if (errorElement) {
                errorElement.style.display = 'none';
            }
            
            // Remove field error styling from datetime input
            const datetimeInput = document.getElementById('scheduled_publish_datetime');
            if (datetimeInput) {
                datetimeInput.classList.remove('field-error', 'shake');
            }
        }

        function clearAllFieldErrors() {
            const errorFields = ['title', 'category', 'location', 'description', 'start_datetime', 'end_datetime'];
            errorFields.forEach(fieldName => {
                const errorElement = document.getElementById(fieldName + '-error');
                const inputElement = document.getElementById(fieldName);
                
                if (errorElement) {
                    errorElement.style.display = 'none';
                }
                
                if (inputElement) {
                    inputElement.classList.remove('field-error', 'shake');
                }
            });
        }

        function showFieldError(fieldName, message) {
            const errorElement = document.getElementById(fieldName + '-error');
            const inputElement = document.getElementById(fieldName);
            
            if (errorElement) {
                errorElement.textContent = message;
                errorElement.style.display = 'block';
            }
            
            if (inputElement) {
                inputElement.classList.add('field-error', 'shake');
                // Remove shake animation after it completes
                setTimeout(() => {
                    inputElement.classList.remove('shake');
                }, 500);
            }
        }

        function validateFields(form, submitAction) {
            let isValid = true;
            let firstErrorField = null;
            
            // For drafts, only validate title
            if (submitAction === 'draft') {
                const titleField = form.querySelector('#title');
                if (!titleField || !titleField.value.trim()) {
                    showFieldError('title', 'Title is required.');
                    isValid = false;
                    if (!firstErrorField) firstErrorField = titleField;
                }
                return { isValid, firstErrorField };
            }
            
            // For publish/schedule, validate all required fields
            const requiredFields = [
                { id: 'title', name: 'Title' },
                { id: 'category', name: 'Category' },
                { id: 'location', name: 'Location' },
                { id: 'description', name: 'Description' },
                { id: 'start_datetime', name: 'Start Date & Time' },
                { id: 'end_datetime', name: 'End Date & Time' }
            ];
            
            requiredFields.forEach(field => {
                const fieldElement = form.querySelector('#' + field.id);
                if (!fieldElement || !fieldElement.value.trim()) {
                    showFieldError(field.id, field.name + ' is required.');
                    isValid = false;
                    if (!firstErrorField) firstErrorField = fieldElement;
                }
            });
            
            // Validate datetime order if both are provided
            const startField = form.querySelector('#start_datetime');
            const endField = form.querySelector('#end_datetime');
            if (startField && endField && startField.value && endField.value) {
                const startTime = new Date(startField.value);
                const endTime = new Date(endField.value);
                
                if (startTime >= endTime) {
                    showFieldError('end_datetime', 'End date must be after start date.');
                    isValid = false;
                    if (!firstErrorField) firstErrorField = endField;
                }
            }
            
            return { isValid, firstErrorField };
        }

        // Status tab filtering - consolidated
        (function() {
            const statusTabsWrapper = document.getElementById('statusTabsWrapper');
            const statusTabs = document.querySelectorAll('.status-tab');

            function updateStatusTabsVisibility() {
                if (globalActiveBarangayId === 0) {
                    statusTabsWrapper.style.display = '';
                } else {
                    statusTabsWrapper.style.display = 'none';
                }
            }

            statusTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    globalActiveStatus = this.dataset.status;
                    statusTabs.forEach(t => {
                        t.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
                        t.classList.add('bg-white', 'text-gray-600', 'border-gray-300', 'hover:border-blue-600', 'hover:text-blue-600');
                    });
                    this.classList.add('bg-blue-600', 'text-white', 'border-blue-600');
                    this.classList.remove('bg-white', 'text-gray-600', 'border-gray-300', 'hover:border-blue-600', 'hover:text-blue-600');
                    filterEvents();
                    
                    // Update bulk selection UI when tab changes
                    setTimeout(() => {
                        const updateEvent = new Event('tabChanged');
                        document.dispatchEvent(updateEvent);
                    }, 0);
                });
            });

            // Category filter event listener
            const categoryFilter = document.getElementById('categoryFilter');
            if (categoryFilter) {
                categoryFilter.addEventListener('change', function() {
                    globalActiveCategory = this.value;
                    
                    // Sync with barangay category filter
                    const barangayCategoryFilter = document.getElementById('barangayCategoryFilter');
                    if (barangayCategoryFilter) {
                        barangayCategoryFilter.value = this.value;
                    }
                    
                    filterEvents();
                    
                    // Update bulk selection UI when category changes
                    setTimeout(() => {
                        const updateEvent = new Event('tabChanged');
                        document.dispatchEvent(updateEvent);
                    }, 0);
                });
            }

            // Barangay category filter event listener
            const barangayCategoryFilter = document.getElementById('barangayCategoryFilter');
            if (barangayCategoryFilter) {
                barangayCategoryFilter.addEventListener('change', function() {
                    globalActiveCategory = this.value;
                    
                    // Sync with city-wide category filter
                    const categoryFilter = document.getElementById('categoryFilter');
                    if (categoryFilter) {
                        categoryFilter.value = this.value;
                    }
                    
                    filterEvents();
                    
                    // Update bulk selection UI when category changes
                    setTimeout(() => {
                        const updateEvent = new Event('tabChanged');
                        document.dispatchEvent(updateEvent);
                    }, 0);
                });
            }

            // Clear filters event listener
            const clearFiltersBtn = document.getElementById('clearFilters');
            if (clearFiltersBtn) {
                clearFiltersBtn.addEventListener('click', function() {
                    // Reset status filter to Published
                    globalActiveStatus = 'Published';
                    statusTabs.forEach(t => {
                        t.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
                        t.classList.add('bg-white', 'text-gray-600', 'border-gray-300', 'hover:border-blue-600', 'hover:text-blue-600');
                    });
                    const publishedTab = document.querySelector('.status-tab[data-status="Published"]');
                    if (publishedTab) {
                        publishedTab.classList.add('bg-blue-600', 'text-white', 'border-blue-600');
                        publishedTab.classList.remove('bg-white', 'text-gray-600', 'border-gray-300', 'hover:border-blue-600', 'hover:text-blue-600');
                    }
                    
                    // Reset category filter
                    globalActiveCategory = '';
                    if (categoryFilter) {
                        categoryFilter.value = '';
                    }
                    
                    // Also reset barangay category filter
                    const barangayCategoryFilter = document.getElementById('barangayCategoryFilter');
                    if (barangayCategoryFilter) {
                        barangayCategoryFilter.value = '';
                    }
                    
                    // Apply filters
                    filterEvents();
                    
                    // Update bulk selection UI
                    setTimeout(() => {
                        const updateEvent = new Event('tabChanged');
                        document.dispatchEvent(updateEvent);
                    }, 0);
                });
            }

            // Clear barangay filters event listener
            const clearBarangayFiltersBtn = document.getElementById('clearBarangayFilters');
            if (clearBarangayFiltersBtn) {
                clearBarangayFiltersBtn.addEventListener('click', function() {
                    // Reset category filter for barangay events
                    globalActiveCategory = '';
                    const barangayCategoryFilter = document.getElementById('barangayCategoryFilter');
                    if (barangayCategoryFilter) {
                        barangayCategoryFilter.value = '';
                    }
                    
                    // Apply filters
                    filterEvents();
                    
                    // Update bulk selection UI
                    setTimeout(() => {
                        const updateEvent = new Event('tabChanged');
                        document.dispatchEvent(updateEvent);
                    }, 0);
                });
            }

            // Initialize on page load
            document.addEventListener('DOMContentLoaded', function() {
                updateStatusTabsVisibility();
                filterEvents();
            });
        })();

// Initialize event form upload behaviors from parent scope
function initializeEventFormUpload() {
    const fileInput = document.getElementById('event_banner');
    const fileError = document.getElementById('file-error');
    if (!fileInput) return;

    const container = fileInput.closest('.file-upload-container');
    const button = container ? container.querySelector('.file-upload-button') : null;
    const textElement = container ? container.querySelector('#event_banner_text') : null;

    function setFileErrorMessage(msg) {
        if (!fileError) return;
        fileError.textContent = msg;
        fileError.style.display = 'block';
    }

    function clearFileErrorMessage() {
        if (!fileError) return;
        fileError.textContent = '';
        fileError.style.display = 'none';
    }

    function showInvalidState(msg) {
        if (button) {
            button.classList.add('error');
            button.classList.remove('has-file');
        }
        fileInput.classList.add('border-red-500');
        fileInput.classList.remove('border-gray-300');
        setFileErrorMessage(msg);
        if (textElement) {
            textElement.innerHTML = `
                Click to upload or drag and drop<br>
                <span class="text-xs text-gray-500">JPG, PNG, WEBP up to 5MB</span>
            `;
        }
    }

    function showSelectedState(file) {
        if (button) {
            button.classList.remove('error');
            button.classList.add('has-file');
        }
        fileInput.classList.remove('border-red-500');
        fileInput.classList.add('border-gray-300');
        clearFileErrorMessage();
        if (textElement) {
            const name = file.name.length > 30 ? file.name.substring(0, 30) + '...' : file.name;
            textElement.innerHTML = `
                        <strong>New file selected:</strong><br>
                        <span class="text-green-600">${name}</span><br>
                        <span class="text-xs text-blue-500">Ready to upload</span>
                    `;
        }
    }

    function validateAndHandle(file) {
        const maxSize = 5 * 1024 * 1024;
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];

        if (!file) return;
        if (!allowedTypes.includes(file.type)) {
            showInvalidState(`Invalid file type. Allowed formats: JPG, JPEG, PNG, WEBP.`);
            fileInput.value = '';
            return false;
        }
        if (file.size > maxSize) {
            const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
            showInvalidState(`File is too large (${fileSizeMB} MB). Maximum allowed size is 5MB.`);
            fileInput.value = '';
            return false;
        }
        showSelectedState(file);
        return true;
    }

    // Wire input change
    fileInput.addEventListener('change', function(e) {
        clearFileErrorMessage();
        const file = e.target.files[0];
        if (!file) {
            // reset
            if (button) {
                button.classList.remove('has-file');
                button.classList.remove('error');
            }
            if (textElement) {
                textElement.innerHTML = `
                    Click to upload or drag and drop<br>
                    <span class="text-xs text-gray-500">JPG, PNG, WEBP up to 5MB</span>
                `;
            }
            return;
        }
        validateAndHandle(file);
    });

    // Click, drag & drop behavior on button
    if (button) {
        button.addEventListener('click', function() { fileInput.click(); });

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
                fileInput.files = files;
                validateAndHandle(files[0]);
            }
        });
    }
}

        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.event-checkbox');
            const bulkBar = document.getElementById('bulkDeleteBar');
            const bulkBtn = document.getElementById('bulkDeleteBtn');
            
            // Helper function to get only visible checkboxes (in current active tab)
            function getVisibleCheckboxes() {
                return Array.from(checkboxes).filter(cb => {
                    const row = cb.closest('.event-row');
                    return row && row.style.display !== 'none';
                });
            }
            
            function updateBulkBar() {
                const visibleCheckboxes = getVisibleCheckboxes();
                const checked = visibleCheckboxes.filter(cb => cb.checked);
                const selectedCount = document.getElementById('selectedCount');
                
                if (checked.length > 0) {
                    bulkBar.classList.remove('hidden');
                    selectedCount.textContent = `${checked.length} event(s) selected`;
                } else {
                    bulkBar.classList.add('hidden');
                    selectedCount.textContent = '0 events selected';
                }
            }
            
            checkboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    updateBulkBar();
                });
            });
            
            bulkBtn.addEventListener('click', function() {
                const visibleCheckboxes = getVisibleCheckboxes();
                const checked = visibleCheckboxes.filter(cb => cb.checked);
                if (checked.length === 0) {
                    showNotification('Please select at least one event to delete.', 'error');
                    return;
                }
                
                showDeleteConfirmationModal('bulk', null, checked.length);
            });
            
            // Listen for tab changes to update bulk selection UI
            document.addEventListener('tabChanged', function() {
                updateBulkBar();
            });
        });

        // Update heading and button visibility on tab click
        tabButtons.forEach((btn, idx) => {
            btn.addEventListener('click', function() {
                // Remove selected style from all tabs
                tabButtons.forEach(b => b.classList.remove('bg-blue-600', 'text-white'));
                tabButtons.forEach(b => b.classList.add('bg-white', 'text-gray-600'));
                // Add selected style to clicked tab
                btn.classList.remove('bg-white', 'text-gray-600');
                btn.classList.add('bg-blue-600', 'text-white');
                // Get barangay_id for this tab
                let barangayId = 0;
                <?php foreach ($calendar_tabs as $i => $tab): ?>
                    if (idx === <?= $i ?>) barangayId = <?= isset($barangays[$i]['barangay_id']) ? (int)$barangays[$i]['barangay_id'] : 0 ?>;
                <?php endforeach; ?>
                globalActiveBarangayId = barangayId;
                filterEvents();
                // Show/hide containers based on whether it's city-wide or barangay-specific
                const cityWideContainer = document.getElementById('cityWideContainer');
                const barangaySpecificContainer = document.getElementById('barangaySpecificContainer');
                
                if (idx === 0) {
                    // Show city-wide controls
                    cityWideContainer.style.display = '';
                    barangaySpecificContainer.classList.add('hidden');
                    
                    // Sync category filter from barangay to city-wide
                    const barangayCategoryFilter = document.getElementById('barangayCategoryFilter');
                    const cityWideCategoryFilter = document.getElementById('categoryFilter');
                    if (barangayCategoryFilter && cityWideCategoryFilter) {
                        cityWideCategoryFilter.value = barangayCategoryFilter.value;
                    }
                } else {
                    // Show barangay-specific container
                    cityWideContainer.style.display = 'none';
                    barangaySpecificContainer.classList.remove('hidden');
                    
                    // Sync category filter from city-wide to barangay
                    const cityWideCategoryFilter = document.getElementById('categoryFilter');
                    const barangayCategoryFilter = document.getElementById('barangayCategoryFilter');
                    if (cityWideCategoryFilter && barangayCategoryFilter) {
                        barangayCategoryFilter.value = cityWideCategoryFilter.value;
                    }
                }
            });
        });
        window.addEventListener('DOMContentLoaded', function() {
            // On page load, show only events for the first tab and initialize containers
            let firstBarangayId = 0;
            <?php if (isset($barangays[0]['barangay_id'])): ?>
                firstBarangayId = <?= (int)$barangays[0]['barangay_id'] ?>;
            <?php endif; ?>
            globalActiveBarangayId = firstBarangayId;
            filterEvents();
            // Initialize container visibility for first tab (city-wide)
            const cityWideContainer = document.getElementById('cityWideContainer');
            const barangaySpecificContainer = document.getElementById('barangaySpecificContainer');
            cityWideContainer.style.display = '';
            barangaySpecificContainer.classList.add('hidden');
        });

        // Utility function to show notifications (same as Pederasyon member.php)
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-[99999] p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
            
            switch(type) {
                case 'success':
                    notification.className += ' bg-green-500 text-white';
                    break;
                case 'error':
                    notification.className += ' bg-red-500 text-white';
                    break;
                default:
                    notification.className += ' bg-blue-500 text-white';
            }
            
            notification.innerHTML = `
                <div class="flex items-center">
                    <span class="mr-2">${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-white hover:text-gray-200">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
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

        // Global helper function to get visible checkboxes
        function getVisibleCheckboxes() {
            const checkboxes = document.querySelectorAll('.event-checkbox');
            return Array.from(checkboxes).filter(cb => {
                const row = cb.closest('.event-row');
                return row && row.style.display !== 'none';
            });
        }

        // Delete confirmation modal functions
        function showDeleteConfirmationModal(type, eventId = null, count = null) {
            const modal = document.getElementById('deleteConfirmationModal');
            const modalContent = document.getElementById('deleteConfirmationModalContent');
            const title = document.getElementById('deleteConfirmationTitle');
            const message = document.getElementById('deleteConfirmationMessage');
            const confirmBtn = document.getElementById('deleteConfirmButton');
            
            if (type === 'single') {
                title.textContent = 'Delete Event';
                message.textContent = 'Are you sure you want to delete this event? This action cannot be undone.';
                confirmBtn.onclick = () => handleSingleDelete(eventId);
            } else if (type === 'bulk') {
                title.textContent = 'Delete Events';
                message.textContent = `Are you sure you want to delete ${count} selected event(s)? This action cannot be undone.`;
                confirmBtn.onclick = handleBulkDelete;
            }
            
            // Show modal with animation
            modal.classList.remove('hidden');
            
            // Force reflow to ensure the transition works
            requestAnimationFrame(() => {
                modal.classList.remove('opacity-0');
                modal.classList.add('opacity-100');
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            });
        }

        function closeDeleteConfirmationModal() {
            const modal = document.getElementById('deleteConfirmationModal');
            const modalContent = document.getElementById('deleteConfirmationModalContent');
            
            // Hide modal with animation
            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            
            // Hide modal after animation completes
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        function handleSingleDelete(eventId) {
            closeDeleteConfirmationModal();
            
            // Use AJAX to delete the event and show toast notification
            fetch(`/events/delete/${eventId}`, {
                method: 'GET',
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Show success notification
                    showNotification(data.message || 'Event deleted successfully', 'success');
                    
                    // Reload page after a short delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    // Show error notification
                    showNotification(data.message || 'Failed to delete event', 'error');
                }
            })
            .catch(error => {
                console.error('Delete error:', error);
                showNotification('Failed to delete event: ' + error.message, 'error');
            });
        }

        function handleBulkDelete() {
            closeDeleteConfirmationModal();
            
            const visibleCheckboxes = getVisibleCheckboxes();
            const checked = visibleCheckboxes.filter(cb => cb.checked);
            const bulkBtn = document.getElementById('bulkDeleteBtn');
            
            // Disable the button to prevent multiple clicks
            bulkBtn.disabled = true;
            bulkBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Deleting...';
            
            const ids = checked.map(cb => cb.value);
            fetch('/events/bulk_delete', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 
                    'X-Requested-With': 'XMLHttpRequest' 
                },
                body: JSON.stringify({ event_ids: ids })
            })
            .then(res => {
                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    // Show success notification before reloading
                    const successMessage = data.message || `Successfully deleted ${data.deleted_count || checked.length} event(s).`;
                    showNotification(successMessage, 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    // Show detailed error message
                    let errorMessage = data.message || 'Bulk delete failed.';
                    if (data.errors && data.errors.length > 0) {
                        errorMessage += ' Errors: ' + data.errors.join(', ');
                    }
                    showNotification(errorMessage, 'error');
                }
            })
            .catch(error => {
                console.error('Bulk delete error:', error);
                showNotification('Bulk delete failed: ' + error.message, 'error');
            })
            .finally(() => {
                // Re-enable the button
                bulkBtn.disabled = false;
                bulkBtn.innerHTML = '<i class="fas fa-trash-alt mr-2"></i>Delete Selected';
            });
        }

        // Close modal when clicking outside or pressing Escape
        document.getElementById('deleteConfirmationModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteConfirmationModal();
            }
        });

        document.addEventListener('keydown', function(e) {
            const modal = document.getElementById('deleteConfirmationModal');
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeDeleteConfirmationModal();
            }
        });

        // Move modals to body to ensure they cover the entire viewport
        document.addEventListener('DOMContentLoaded', function() {
            // Move main event modal to body
            const eventModal = document.getElementById('eventModal');
            if (eventModal && eventModal.parentElement !== document.body) {
                document.body.appendChild(eventModal);
            }
            
            // Move delete confirmation modal to body
            const deleteModal = document.getElementById('deleteConfirmationModal');
            if (deleteModal && deleteModal.parentElement !== document.body) {
                document.body.appendChild(deleteModal);
            }
            
            // Move all individual event modals to body
            const individualModals = document.querySelectorAll('[id^="eventModal_"]');
            individualModals.forEach(modal => {
                if (modal.parentElement !== document.body) {
                    document.body.appendChild(modal);
                }
            });
        });
    </script>

<style>
/* Field validation styles */
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

.shake {
    animation: shake 0.5s ease-in-out;
}

.field-error {
    border-color: #ef4444 !important;
    background-color: #fef2f2 !important;
}

/* Ensure modals cover the entire viewport regardless of parent container */
#eventModal,
#deleteConfirmationModal,
[id^="eventModal_"] {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    margin: 0 !important;
    padding: 0 !important;
    z-index: 9997 !important;
}

/* Ensure delete confirmation modal has highest z-index */
#deleteConfirmationModal {
    z-index: 9999 !important;
}

/* Ensure event add/edit modal has proper z-index */
#eventModal {
    z-index: 9998 !important;
}

/* 
Z-Index Hierarchy:
- Individual event modals: z-9997
- Add/Edit event modal: z-9998  
- Delete confirmation modal: z-9999
- Toast notifications: z-99999 (highest)
*/
    </style>
</body>
</html> 