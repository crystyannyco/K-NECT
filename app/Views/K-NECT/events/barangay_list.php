<?php
$session = session();
$user_id = $session->get('user_id');
$username = $session->get('username');
$sk_barangay = $session->get('sk_barangay');
$barangay_name = isset($barangay_name) ? $barangay_name : 'Unknown Barangay';
?>

<!-- ===== MAIN CONTENT AREA ===== -->
<div class="flex-1 flex flex-col min-h-0 ml-64 pt-16">
    <main class="flex-1 overflow-auto p-6 bg-gray-50">
        
        <div class="max-w-7xl mx-auto">
            <!-- Header with Title and Add Event Button -->
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-blue-700"><?= esc($barangay_name) ?> Event List</h1>
                    <p class="text-sm text-gray-600 mt-0.5">Manage events for your barangay</p>
                </div>
                <div>
                    <button onclick="openEventModal('add')" class="bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200">
                        <i class="fas fa-plus mr-2"></i>Add New Event
                    </button>
                </div>
            </div>
            
            <!-- Improved Side-by-side layout: Calendar on left (larger), Events on right -->
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
                                <button class="status-tab px-4 py-2 rounded-lg text-sm font-semibold transition-all border border-gray-300 bg-white text-gray-700 hover:border-blue-600 hover:text-blue-600" data-status="Draft">
                                    <i class="fas fa-file mr-2"></i>Draft
                                </button>
                                <button class="status-tab px-4 py-2 rounded-lg text-sm font-semibold transition-all border border-gray-300 bg-white text-gray-700 hover:border-blue-600 hover:text-blue-600" data-status="Scheduled">
                                    <i class="fas fa-clock mr-2"></i>Scheduled
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Select All and Category Filter below status tabs -->
                    <div class="mb-4 flex items-center justify-between gap-4 flex-shrink-0">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="selectAllCheckbox" class="w-4 h-4 cursor-pointer">
                            <span class="text-sm font-medium text-gray-700">Select All</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <label for="categoryFilter" class="text-sm font-medium text-gray-700 whitespace-nowrap flex items-center">
                                <i class="fas fa-filter mr-1.5"></i>Category:
                            </label>
                            <select id="categoryFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
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
                    </div>
                    
                    <!-- Scrollable Events Container with Fixed Height -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden flex flex-col" style="height: calc(100vh - 320px);">
                        <div class="overflow-y-auto custom-scrollbar flex-1">
                            <div class="px-4 pt-2 pb-4 space-y-4">
                                    <?php if (!empty($events)): ?>
                                        <?php foreach ($events as $event): ?>
                                            <?php
                                                $desc = esc($event['description']);
                                                $shortDesc = mb_strlen($desc) > 120 ? mb_substr($desc, 0, 120) . '...' : $desc;
                                                $modalId = 'eventModal_' . $event['event_id'];
                                                $status = isset($event['status']) ? $event['status'] : 'Published';
                                                $banner = !empty($event['event_banner']) ? "/uploads/event/" . esc($event['event_banner']) : "/assets/images/default-event-banner.svg";
                                                $category = isset($event['category']) ? $event['category'] : '';
                                                
                                                // Determine temporal status for published events
                                                $temporalStatus = null;
                                                $canEdit = true;
                                                if ($status === 'Published') {
                                                    $currentDateTime = new DateTime('now', new DateTimeZone('Asia/Manila'));
                                                    $startDateTime = new DateTime($event['start_datetime'], new DateTimeZone('Asia/Manila'));
                                                    $endDateTime = new DateTime($event['end_datetime'], new DateTimeZone('Asia/Manila'));
                                                    
                                                    if ($currentDateTime < $startDateTime) {
                                                        $temporalStatus = 'upcoming';
                                                    } elseif ($currentDateTime >= $startDateTime && $currentDateTime <= $endDateTime) {
                                                        $temporalStatus = 'ongoing';
                                                    } else {
                                                        $temporalStatus = 'completed';
                                                        $canEdit = false; // Completed events cannot be edited
                                                    }
                                                }
                                            ?>
                                            <div class="flex items-center gap-2 w-full event-row" data-status="<?= $status ?>" data-category="<?= esc($category) ?>" data-temporal="<?= $temporalStatus ?>">
                                                <div class="flex-shrink-0 self-center" onclick="event.stopPropagation();">
                                                    <input type="checkbox" class="event-checkbox w-5 h-5 cursor-pointer text-blue-600 focus:ring-blue-500 focus:ring-2 rounded" value="<?= $event['event_id'] ?>">
                                                </div>
                                                <div class="group bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex flex-col sm:flex-row gap-4 w-full cursor-pointer transition-all duration-200 hover:shadow-md hover:border-blue-300" onclick="openEventModal('<?= $modalId ?>')">
                                                    <div class="flex-shrink-0 w-full sm:w-56 h-40 overflow-hidden rounded-lg">
                                                        <img class="object-cover w-full h-full transition-transform duration-200 group-hover:scale-105" src="<?= $banner ?>" alt="Event Banner">
                                                    </div>
                                                    <div class="flex flex-col flex-1 min-w-0">
                                                        <div class="flex flex-wrap gap-1.5 mb-2">
                                                            <?php if ($category): ?>
                                                                <span class="inline-flex items-center leading-none px-3 py-1.5 text-[11px] font-medium bg-blue-50 text-blue-700 border border-blue-300 rounded-full">
                                                                    <i class="fas fa-tag mr-1 text-[10px]"></i>
                                                                    <?= ucfirst($category) ?>
                                                                </span>
                                                            <?php endif; ?>
                                                            <!-- Status Badge -->
                                                            <span class="inline-flex items-center leading-none px-3 py-1.5 text-[11px] font-medium rounded-full border
                                                                <?php
                                                                switch($status) {
                                                                    case 'Draft':
                                                                        echo 'bg-yellow-50 text-yellow-700 border-yellow-300';
                                                                        break;
                                                                    case 'Scheduled':
                                                                        echo 'bg-orange-50 text-orange-700 border-orange-300';
                                                                        break;
                                                                    case 'Published':
                                                                        echo 'bg-green-50 text-green-700 border-green-300';
                                                                        break;
                                                                    default:
                                                                        echo 'bg-gray-50 text-gray-700 border-gray-300';
                                                                }
                                                                ?>">
                                                                <?php
                                                                switch($status) {
                                                                    case 'Draft':
                                                                        echo '<i class="far fa-file-alt mr-1 text-[10px]"></i>';
                                                                        break;
                                                                    case 'Scheduled':
                                                                        echo '<i class="far fa-clock mr-1 text-[10px]"></i>';
                                                                        break;
                                                                    case 'Published':
                                                                        echo '<i class="fas fa-check-circle mr-1 text-[10px]"></i>';
                                                                        break;
                                                                    default:
                                                                        echo '<i class="fas fa-circle mr-1 text-[8px]"></i>';
                                                                }
                                                                ?>
                                                                <?= $status ?>
                                                            </span>
                                                            
                                                            <!-- Temporal Status Badge for Published Events -->
                                                            <?php if ($status === 'Published' && $temporalStatus): ?>
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
                                                            <?php endif; ?>
                                                        </div>
                                                        <h3 class="text-lg font-bold text-gray-900 mb-1.5 line-clamp-2 group-hover:text-blue-600 transition-colors"><?= esc($event['title']) ?></h3>
                                                        <p class="text-sm text-gray-600 line-clamp-2"><?= $shortDesc ?></p>
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
                                                    <!-- Action buttons -->
                                                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-between items-center">
                                                        <!-- Status information for completed events -->
                                                        <?php if ($temporalStatus === 'completed'): ?>
                                                            <div class="flex items-center text-sm text-gray-600">
                                                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                </svg>
                                                                This event has been completed and cannot be edited.
                                                            </div>
                                                        <?php elseif ($temporalStatus === 'ongoing'): ?>
                                                            <div class="flex items-center text-sm text-amber-600">
                                                                <svg class="w-4 h-4 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                </svg>
                                                                This event is ongoing. Start date and time cannot be modified.
                                                            </div>
                                                        <?php else: ?>
                                                            <div></div>
                                                        <?php endif; ?>
                                                        
                                                        <div class="flex space-x-4">
                                                            <?php if ($canEdit): ?>
                                                                <button onclick="openEventModal('edit', <?= $event['event_id'] ?>)" class="bg-blue-600 text-white font-semibold py-2 px-6 rounded-lg hover:bg-blue-700 transition duration-200 flex items-center">
                                                                    <i class="fas fa-edit mr-2"></i>Edit
                                                                </button>
                                                            <?php else: ?>
                                                                <button disabled class="bg-gray-400 text-white font-semibold py-2 px-6 rounded-lg cursor-not-allowed flex items-center opacity-50">
                                                                    <i class="fas fa-edit mr-2"></i>Edit
                                                                </button>
                                                            <?php endif; ?>
                                                            <button onclick="showDeleteConfirmationModal('single', <?= $event['event_id'] ?>)" class="bg-red-600 text-white font-semibold py-2 px-6 rounded-lg hover:bg-red-700 transition duration-200 flex items-center">
                                                                <i class="fas fa-trash mr-2"></i>Delete
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="text-center text-gray-500 py-12">
                                            <i class="fas fa-calendar-times text-5xl text-gray-300 mb-4"></i>
                                            <p class="text-lg font-medium">No events found.</p>
                                            <p class="text-sm text-gray-400 mt-2">Try adjusting your filters or create a new event.</p>
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
                                        src="https://calendar.google.com/calendar/embed?src=<?= urlencode($calendar_id) ?>&ctz=Asia%2FManila&showTitle=0&showPrint=0&showCalendars=0&mode=MONTH&bgcolor=%23FFFFFF"
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
                    <!-- Bulk Delete Bar -->
                    <div id="bulkDeleteBar" class="fixed bottom-6 left-1/2 transform -translate-x-1/2 z-50 hidden">
                        <div class="bg-white rounded-lg shadow-xl border border-gray-200 p-4 flex items-center space-x-4">
                            <span id="selectedCount" class="text-gray-700 font-medium">
                                <i class="fas fa-check-circle text-blue-600 mr-2"></i>
                                <span class="font-bold text-blue-600">0</span> events selected
                            </span>
                            <button id="bulkDeleteBtn" class="bg-red-600 text-white font-semibold py-2.5 px-6 rounded-lg hover:bg-red-700 transition duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-trash-alt mr-2"></i>Delete Selected
                            </button>
                        </div>
                    </div>
                </div>
                

            </div>
        </div>
    </main>
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
    </div>
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
                    src="https://calendar.google.com/calendar/embed?src=<?= urlencode($calendar_id) ?>&ctz=Asia%2FManila&showTitle=0&showPrint=0&showCalendars=0&mode=MONTH&bgcolor=%23FFFFFF"
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

        // Initialize toggle functionality after modal content is loaded
        initializeToggleFunctionality();

        // Initialize date/time restrictions for dynamically loaded form
        initializeDateTimeRestrictions();

        // Initialize file upload handlers (runs in the parent because injected scripts may not execute)
        if (typeof initializeEventFormUpload === 'function') {
            initializeEventFormUpload();
        }

        // Add form submission handler
        const form = document.getElementById('eventForm');
        if (form) {
            // Remove any existing submit listeners to avoid conflicts
            form.removeEventListener('submit', handleFormSubmit);
            form.addEventListener('submit', handleFormSubmit);

            // Also ensure the form's built-in submit_action handling works
            form.addEventListener('submit', function(e) {
                const submitAction = e.submitter?.value || 'publish';
                const submitActionInput = document.createElement('input');
                submitActionInput.type = 'hidden';
                submitActionInput.name = 'submit_action';
                submitActionInput.value = submitAction;
                form.appendChild(submitActionInput);
            });
        }
    })
    .catch(error => {
        console.error('Error loading form:', error);
    });
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
    console.log('Submit action captured:', submitAction);
    console.log('Submitter element:', e.submitter);
    
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

    // Show loading screen with appropriate message
    let loadingTitle = 'Processing...';
    let loadingMessage = 'Please wait while we process your request.';
    
    switch(submitAction) {
        case 'publish':
            loadingTitle = 'Publishing Event...';
            loadingMessage = 'Publishing your event and syncing with Google Calendar.';
            break;
        case 'draft':
            loadingTitle = 'Saving Draft...';
            loadingMessage = 'Saving your event as a draft.';
            break;
        case 'schedule':
            loadingTitle = 'Scheduling Event...';
            loadingMessage = 'Scheduling your event for automatic publishing.';
            break;
        default:
            loadingTitle = 'Updating Event...';
            loadingMessage = 'Saving your changes and updating the event.';
    }
    
    showLoadingScreen(loadingTitle, loadingMessage);
    
    // Log form data
    console.log('Form action:', form.action);
    console.log('Submit action:', submitAction);
    for (let [key, value] of formData.entries()) {
        console.log('Form data:', key, '=', value);
    }

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
            // Hide loading screen and show success notification
            showNotificationWithLoading('Event ' + (submitAction === 'draft' ? 'saved as draft' : submitAction === 'schedule' ? 'scheduled' : 'published') + ' successfully!', 'success');
            
            // Check Google Calendar sync status for published events
            if (submitAction === 'publish' && data.google_calendar_sync === false) {
                showNotification('Event published successfully, but failed to sync with Google Calendar. Please check calendar permissions.', 'warning');
            } else {
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
            }
            
            // Close modal
            closeEventModal();
            
            // For publish actions, wait a bit longer to ensure Google Calendar sync completes
            if (submitAction === 'publish') {
                setTimeout(() => {
                    window.location.reload();
                }, 2500); // Give more time for Google Calendar sync
            } else {
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            }
        } else {
            // Handle various types of errors
            if (data.message && data.message.includes('Google Calendar')) {
                // Google Calendar sync failed - show warning but don't prevent use
                showNotificationWithLoading('Event was saved but Google Calendar sync failed: ' + data.message, 'warning');
                
                // Still close modal and refresh after showing the warning
                closeEventModal();
                setTimeout(() => {
                    window.location.reload();
                }, 3000);
            } else {
                // Other errors - handle as before
                hideLoadingScreen();
                
                // Check if error is related to target_participants
                if (data.message && data.message.toLowerCase().includes('target') && data.message.toLowerCase().includes('participant')) {
                    const targetErrorEl = document.getElementById('target_participants-error');
                    const targetInput = document.getElementById('target_participants');
                    if (targetErrorEl && targetInput) {
                        targetErrorEl.textContent = data.message;
                        targetErrorEl.style.display = 'block';
                        targetInput.classList.add('field-error', 'shake');
                        targetInput.focus();
                        setTimeout(() => {
                            targetInput.classList.remove('shake');
                        }, 500);
                    } else {
                        showNotificationWithLoading('Error: ' + data.message, 'error');
                    }
                }
                // Check if error is related to file upload
                else {
                    const fileErrorEl = document.getElementById('file-error');
                    if (fileErrorEl && data.message && (data.message.toLowerCase().includes('file') || data.message.toLowerCase().includes('banner') || data.message.toLowerCase().includes('upload'))) {
                        fileErrorEl.textContent = data.message;
                        fileErrorEl.classList.remove('hidden');
                        const input = document.getElementById('event_banner');
                        if (input) {
                            input.classList.add('border-red-500');
                            input.classList.remove('border-gray-300');
                        }
                    } else {
                        showNotificationWithLoading('Error: ' + (data.message || 'Unknown error occurred'), 'error');
                    }
                }
            }
        }
    })
    .catch(error => {
        console.error('Error submitting form:', error);
        showNotificationWithLoading('Error submitting form. Please try again.', 'error');
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
    const errorFields = ['title', 'category', 'location', 'target_participants', 'description', 'start_datetime', 'end_datetime'];
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
        { id: 'target_participants', name: 'Target No. of Participants' },
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
    
    // Additional validation for target_participants (must be >= 1)
    const targetParticipantsField = form.querySelector('#target_participants');
    if (targetParticipantsField && targetParticipantsField.value.trim()) {
        const value = parseInt(targetParticipantsField.value);
        if (isNaN(value) || value < 1) {
            showFieldError('target_participants', 'Target No. of Participants must be at least 1.');
            isValid = false;
            if (!firstErrorField) firstErrorField = targetParticipantsField;
        }
    }
    
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

// ===== DATE/TIME PICKER RESTRICTIONS FOR DYNAMIC FORMS =====
function initializeDateTimeRestrictions() {
    console.log('Initializing date/time restrictions for dynamically loaded form');
    
    // Function to get current date and time in local timezone
    function getCurrentDateTime() {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        return `${year}-${month}-${day}T${hours}:${minutes}`;
    }

    const currentDateTime = getCurrentDateTime();
    
    // Set minimum for event start datetime
    const startDatetimeInput = document.getElementById('start_datetime');
    if (startDatetimeInput) {
        startDatetimeInput.min = currentDateTime;
        
        // Update end datetime minimum when start changes (without showing alerts)
        startDatetimeInput.addEventListener('change', function() {
            const endDatetimeInput = document.getElementById('end_datetime');
            if (endDatetimeInput && this.value) {
                endDatetimeInput.min = this.value;
            }
        });
    }
    
    // Set minimum for event end datetime
    const endDatetimeInput = document.getElementById('end_datetime');
    if (endDatetimeInput) {
        endDatetimeInput.min = currentDateTime;
    }
    
    // Set minimum for scheduled publish datetime
    const scheduledDatetimeInput = document.getElementById('scheduled_publish_datetime');
    if (scheduledDatetimeInput) {
        scheduledDatetimeInput.min = currentDateTime;
    }
    
    console.log('Date/time restrictions initialized');
}

// Initialize toggle functionality for modal forms
function initializeToggleFunctionality() {
    console.log('Initializing toggle functionality...');
    
    // Scheduling toggle functionality
    const schedulingEnabled = document.getElementById('scheduling_enabled');
    const schedulingDatetimeGroup = document.getElementById('scheduling_datetime_group');
    
    console.log('Scheduling enabled element:', schedulingEnabled);
    console.log('Scheduling datetime group element:', schedulingDatetimeGroup);

    if (schedulingEnabled) {
        console.log('Adding scheduling toggle listener...');
        
        // Check initial state and show/hide accordingly
        if (schedulingEnabled.checked) {
            console.log('Scheduling is checked, showing datetime group...');
            schedulingDatetimeGroup.classList.remove('hidden');
            console.log('Initial state: Scheduling datetime group shown');
        } else {
            console.log('Scheduling is not checked, datetime group should be hidden');
        }
        
        schedulingEnabled.addEventListener('change', function() {
            console.log('Scheduling toggle changed, checked:', this.checked);
            if (this.checked) {
                schedulingDatetimeGroup.classList.remove('hidden');
                console.log('Scheduling datetime group shown');
            } else {
                schedulingDatetimeGroup.classList.add('hidden');
                console.log('Scheduling datetime group hidden');
            }
        });
    } else {
        console.error('Scheduling enabled element not found!');
    }

    // SMS notification toggle functionality
    const smsNotificationEnabled = document.getElementById('sms_notification_enabled');
    const smsRecipientGroup = document.getElementById('sms_recipient_group');
    
    console.log('SMS notification enabled element:', smsNotificationEnabled);
    console.log('SMS recipient group element:', smsRecipientGroup);

    if (smsNotificationEnabled) {
        console.log('Adding SMS notification toggle listener...');
        
        // Check initial state and show/hide accordingly
        if (smsNotificationEnabled.checked) {
            console.log('SMS notification is checked, showing recipient group...');
            smsRecipientGroup.classList.remove('hidden');
            console.log('Initial state: SMS recipient group shown');
        } else {
            console.log('SMS notification is not checked, recipient group should be hidden');
        }
        
        smsNotificationEnabled.addEventListener('change', function() {
            console.log('SMS notification toggle changed, checked:', this.checked);
            if (this.checked) {
                smsRecipientGroup.classList.remove('hidden');
                console.log('SMS recipient group shown');
            } else {
                smsRecipientGroup.classList.add('hidden');
                console.log('SMS recipient group hidden');
            }
        });
    } else {
        console.error('SMS notification enabled element not found!');
    }

    // Recipient roles mutual exclusivity
    const allPederasyonOfficialsCheckbox = document.querySelector('.all-pederasyon-officials-checkbox');
    const pederasyonRoleCheckboxes = document.querySelectorAll('.pederasyon-role-checkbox');
    const allOfficialsCheckbox = document.querySelector('.all-officials-checkbox');
    const individualRoleCheckboxes = document.querySelectorAll('.individual-role-checkbox');
    
    console.log('All Pederasyon officials checkbox element:', allPederasyonOfficialsCheckbox);
    console.log('Pederasyon role checkboxes found:', pederasyonRoleCheckboxes.length);
    console.log('All officials checkbox element:', allOfficialsCheckbox);
    console.log('Individual role checkboxes found:', individualRoleCheckboxes.length);

    // Handle "All Pederasyon Officials" checkbox logic
    if (allPederasyonOfficialsCheckbox) {
        allPederasyonOfficialsCheckbox.addEventListener('change', function() {
            if (this.checked) {
                // When "All Pederasyon Officials" is checked, check all Pederasyon suboptions
                pederasyonRoleCheckboxes.forEach(checkbox => {
                    checkbox.checked = true;
                });
                console.log('All Pederasyon role checkboxes checked');
            } else {
                // When "All Pederasyon Officials" is unchecked, uncheck all Pederasyon suboptions
                pederasyonRoleCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
                console.log('All Pederasyon role checkboxes unchecked');
            }
        });
    }

    // Handle Pederasyon suboption checkboxes
    pederasyonRoleCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            // If any Pederasyon suboption is unchecked, uncheck "All Pederasyon Officials"
            if (!this.checked && allPederasyonOfficialsCheckbox) {
                allPederasyonOfficialsCheckbox.checked = false;
                console.log('All Pederasyon Officials checkbox unchecked');
            }
            // If all Pederasyon suboptions are checked, check "All Pederasyon Officials"
            else if (this.checked && allPederasyonOfficialsCheckbox) {
                const allPederasyonChecked = Array.from(pederasyonRoleCheckboxes).every(cb => cb.checked);
                if (allPederasyonChecked) {
                    allPederasyonOfficialsCheckbox.checked = true;
                    console.log('All Pederasyon Officials checkbox checked (all suboptions selected)');
                }
            }
        });
    });

    if (allOfficialsCheckbox) {
        allOfficialsCheckbox.addEventListener('change', function() {
            if (this.checked) {
                // When "All SK Officials" is checked, check all individual SK role checkboxes
                individualRoleCheckboxes.forEach(checkbox => {
                    checkbox.checked = true;
                });
                console.log('All individual role checkboxes checked');
            } else {
                // When "All SK Officials" is unchecked, uncheck all individual SK role checkboxes
                individualRoleCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
                console.log('All individual role checkboxes unchecked');
            }
        });
    }

    // Individual role checkboxes
    individualRoleCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                // Individual role selected - no cross-group interference
            }
            // If any individual role is unchecked, uncheck "All SK Officials"
            if (!this.checked && allOfficialsCheckbox) {
                allOfficialsCheckbox.checked = false;
                console.log('All SK Officials checkbox unchecked');
            }
            // If all individual roles are checked, check "All SK Officials"
            else if (this.checked && allOfficialsCheckbox) {
                const allIndividualChecked = Array.from(individualRoleCheckboxes).every(cb => cb.checked);
                if (allIndividualChecked) {
                    allOfficialsCheckbox.checked = true;
                    console.log('All SK Officials checkbox checked (all individual roles selected)');
                }
            }
        });
    });
    
    console.log('Toggle functionality initialization complete');
}

// Bulk delete functionality
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.event-checkbox');
    const bulkBar = document.getElementById('bulkDeleteBar');
    const bulkBtn = document.getElementById('bulkDeleteBtn');
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    
    // Initialize calendar iframe
    const calendarIframe = document.querySelector('.calendar-iframe');
    if (calendarIframe) {
        calendarIframe.style.opacity = '1';
    }
    
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
    
    function updateSelectAllCheckbox() {
        const visibleCheckboxes = getVisibleCheckboxes();
        const checked = visibleCheckboxes.filter(cb => cb.checked);
        const allChecked = checked.length === visibleCheckboxes.length && visibleCheckboxes.length > 0;
        const partiallyChecked = checked.length > 0 && checked.length < visibleCheckboxes.length;
        
        // Update checkbox state
        selectAllCheckbox.checked = allChecked;
        selectAllCheckbox.indeterminate = partiallyChecked;
    }
    
    checkboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            updateBulkBar();
            updateSelectAllCheckbox();
        });
    });
    
    // Select All checkbox functionality
    selectAllCheckbox.addEventListener('change', function() {
        const allChecked = this.checked;
        const visibleCheckboxes = getVisibleCheckboxes();
        
        visibleCheckboxes.forEach(cb => {
            cb.checked = allChecked;
        });
        
        updateBulkBar();
        updateSelectAllCheckbox();
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
        updateSelectAllCheckbox();
    });
    
    // Initialize select all checkbox state
    updateSelectAllCheckbox();
});

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
        let statusIcon = 'fa-calendar-times';
        let iconColor = 'text-gray-300';
        let suggestion = 'Try adjusting your filters or creating a new event.';
        
        switch(activeStatus) {
            case 'all':
                statusText = 'No events found.';
                suggestion = 'There are no ongoing, upcoming, or completed events. Create a new event to get started.';
                break;
            case 'ongoing':
                statusText = 'No ongoing events found.';
                suggestion = 'There are no events currently in progress. Check upcoming events or create a new one.';
                break;
            case 'upcoming':
                statusText = 'No upcoming events found.';
                suggestion = 'There are no scheduled upcoming events. Check other tabs or create a new event.';
                break;
            case 'completed':
                statusText = 'No completed events found.';
                suggestion = 'There are no completed events yet. Check ongoing or upcoming events.';
                break;
            case 'Draft':
                statusText = 'No draft events found.';
                suggestion = 'There are no draft events. All events may be published or you can create a new draft.';
                break;
            case 'Scheduled':
                statusText = 'No scheduled events found.';
                suggestion = 'There are no events scheduled for auto-publishing. Check other status tabs.';
                break;
            default:
                statusText = 'No events found.';
        }
        
        if (visibleCount === 0) {
            if (!noEventsMsg) {
                // Create the message if it doesn't exist
                noEventsMsg = document.createElement('div');
                noEventsMsg.id = 'noEventsMessage';
                noEventsMsg.className = 'text-center text-gray-500 py-16';
                // Insert after the last event-row or at the beginning if no events
                const container = document.querySelector('.custom-scrollbar > div');
                if (container) {
                    container.appendChild(noEventsMsg);
                }
            }
            
            // Update the message content with dynamic status
            noEventsMsg.innerHTML = `
                <div class="max-w-md mx-auto">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gray-100 mb-6">
                        <i class="fas ${statusIcon} text-5xl ${iconColor}"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-700 mb-3">${statusText}</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">${suggestion}</p>
                </div>
            `;
            noEventsMsg.style.display = 'block';
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
                // Show only ongoing, upcoming, and completed (exclude Draft and Scheduled)
                if (rowStatus !== 'Published' || !['ongoing', 'upcoming', 'completed'].includes(rowTemporal)) {
                    showRow = false;
                }
            } else if (activeStatus === 'ongoing' || activeStatus === 'upcoming' || activeStatus === 'completed') {
                // For temporal statuses, show only Published events with matching temporal status
                if (rowStatus !== 'Published' || rowTemporal !== activeStatus) {
                    showRow = false;
                }
            } else {
                // For Draft and Scheduled, filter by exact status
                if (rowStatus !== activeStatus) {
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
        
        // Update bulk selection UI when filters change
        setTimeout(() => {
            const updateEvent = new Event('tabChanged');
            document.dispatchEvent(updateEvent);
        }, 0);
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
    
    // Category filter event listener
    if (categoryFilter) {
        categoryFilter.addEventListener('change', filterEvents);
    }
    
    // Clear filters event listener
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
            // Don't reset status filter - keep current tab
            // Only reset category filter
            if (categoryFilter) {
                categoryFilter.value = '';
            }
            
            // Apply filters
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
        fileError.classList.remove('hidden');
        fileError.style.display = 'block';
    }

    function clearFileErrorMessage() {
        if (!fileError) return;
        fileError.textContent = '';
        fileError.classList.add('hidden');
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

    fileInput.addEventListener('change', function(e) {
        clearFileErrorMessage();
        const file = e.target.files[0];
        if (!file) {
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

    if (button) {
        button.addEventListener('click', function() { fileInput.click(); });
        button.addEventListener('dragover', function(e) { e.preventDefault(); button.classList.add('dragover'); });
        button.addEventListener('dragleave', function(e) { e.preventDefault(); button.classList.remove('dragover'); });
        button.addEventListener('drop', function(e) { e.preventDefault(); button.classList.remove('dragover'); const files = e.dataTransfer.files; if (files.length > 0) { fileInput.files = files; validateAndHandle(files[0]); } });
    }
}

// Professional Loading Screen Functions
function showLoadingScreen(title = 'Processing...', message = 'Please wait while we process your request.') {
    const loadingScreen = document.getElementById('loadingScreen');
    const loadingContent = document.getElementById('loadingContent');
    const loadingTitle = document.getElementById('loadingTitle');
    const loadingMessage = document.getElementById('loadingMessage');
    
    if (loadingScreen && loadingContent) {
        loadingTitle.textContent = title;
        loadingMessage.textContent = message;
        
        // Show the overlay
        loadingScreen.classList.remove('hidden');
        
        // Animate in the content
        setTimeout(() => {
            loadingContent.classList.remove('scale-95', 'opacity-0');
            loadingContent.classList.add('scale-100', 'opacity-100');
        }, 50);
    }
}

function hideLoadingScreen() {
    const loadingScreen = document.getElementById('loadingScreen');
    const loadingContent = document.getElementById('loadingContent');
    
    if (loadingScreen && loadingContent) {
        // Animate out the content
        loadingContent.classList.remove('scale-100', 'opacity-100');
        loadingContent.classList.add('scale-95', 'opacity-0');
        
        // Hide the overlay after animation
        setTimeout(() => {
            loadingScreen.classList.add('hidden');
        }, 300);
    }
}

// Enhanced notification function that also hides loading screen
function showNotificationWithLoading(message, type = 'info') {
    hideLoadingScreen();
    setTimeout(() => {
        showNotification(message, type);
    }, 100);
}

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
        case 'warning':
            notification.className += ' bg-yellow-500 text-white';
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

// Calendar modal functions
function openCalendarModal() {
    const modal = document.getElementById('calendarModal');
    if (modal) {
        modal.classList.remove('hidden');
        // Prevent body scroll when modal is open
        document.body.style.overflow = 'hidden';
        
        // Add animation
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
            // Restore body scroll
            document.body.style.overflow = '';
        }, 200);
    }
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const calendarModal = document.getElementById('calendarModal');
    if (calendarModal) {
        calendarModal.addEventListener('click', function(e) {
            if (e.target === calendarModal) {
                closeCalendarModal();
            }
        });
    }
    
    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('calendarModal');
            if (modal && !modal.classList.contains('hidden')) {
                closeCalendarModal();
            }
        }
    });
});

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
    
    // Show loading screen for delete operation
    showLoadingScreen('Deleting Event...', 'Please wait while we delete the event.');
    
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
            // Show success notification and hide loading
            showNotificationWithLoading(data.message || 'Event deleted successfully', 'success');
            
            // Reload page after a short delay
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            // Show error notification and hide loading
            showNotificationWithLoading(data.message || 'Failed to delete event', 'error');
        }
    })
    .catch(error => {
        console.error('Delete error:', error);
        showNotificationWithLoading('Failed to delete event: ' + error.message, 'error');
    });
}

function handleBulkDelete() {
    closeDeleteConfirmationModal();
    
    const visibleCheckboxes = getVisibleCheckboxes();
    const checked = visibleCheckboxes.filter(cb => cb.checked);
    const bulkBtn = document.getElementById('bulkDeleteBtn');
    
    // Show loading screen for bulk delete
    showLoadingScreen('Deleting Events...', `Deleting ${checked.length} selected event(s). Please wait...`);
    
    // Disable the button to prevent multiple clicks
    bulkBtn.disabled = true;
    bulkBtn.textContent = 'Deleting...';
    
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
            showNotificationWithLoading(successMessage, 'success');
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            // Show detailed error message
            let errorMessage = data.message || 'Bulk delete failed.';
            if (data.errors && data.errors.length > 0) {
                errorMessage += ' Errors: ' + data.errors.join(', ');
            }
            showNotificationWithLoading(errorMessage, 'error');
        }
    })
    .catch(error => {
        console.error('Bulk delete error:', error);
        showNotificationWithLoading('Bulk delete failed: ' + error.message, 'error');
    })
    .finally(() => {
        // Re-enable the button
        bulkBtn.disabled = false;
        bulkBtn.textContent = 'Delete Selected';
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

/* Custom scrollbar for event list */
.custom-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: #CBD5E0 #F7FAFC;
}

.custom-scrollbar::-webkit-scrollbar {
    width: 8px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #F7FAFC;
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #CBD5E0;
    border-radius: 10px;
    transition: background 0.2s;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #A0AEC0;
}

/* Smooth scroll behavior */
.custom-scrollbar {
    scroll-behavior: smooth;
}

/* Event card hover effect enhancement */
.event-row .group {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.event-row .group:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Calendar sticky positioning */
@media (min-width: 1024px) {
    .sticky {
        position: -webkit-sticky;
        position: sticky;
    }
}

/* Line clamp utilities */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Status tab active state */
.status-tab.bg-blue-600 {
    box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.3), 0 2px 4px -1px rgba(37, 99, 235, 0.2);
}

/* Improved focus states */
select:focus, input[type="checkbox"]:focus {
    outline: none;
}

/* Smooth transitions for interactive elements */
button, select, input[type="checkbox"] {
    transition: all 0.2s ease-in-out;
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
- Loading screen: z-[100000] (highest)
- Toast notifications: z-99999
*/
</style>

<!-- Professional Loading Screen -->
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
