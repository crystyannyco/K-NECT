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
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-blue-700"><?= esc($barangay_name) ?> Event List</h1>
                    <p class="text-gray-600 mt-1">Manage events for your barangay</p>
                </div>
                <div>
                    <button onclick="openEventModal('add')" class="bg-blue-600 text-white font-semibold py-2 px-6 rounded-lg hover:bg-blue-700 transition duration-200">
                        <i class="fas fa-plus mr-2"></i>Add New Event
                    </button>
                </div>
            </div>

            <!-- Status Filter Tabs -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="p-4 border-b border-gray-200">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <!-- Status Filter Tabs -->
                        <div class="flex flex-wrap gap-2">
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

            <!-- Side-by-side layout: Events Table on left, Calendar on right -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Events Table - Takes 2/3 of the space -->
                <div class="lg:col-span-2">
                    <div class="space-y-4">
                        <div class="w-full max-w-5xl mx-auto">
                            <div>
                                <!-- Select All Checkbox above event cards -->
                                <div class="mb-4 flex items-center gap-2">
                                    <input type="checkbox" id="selectAllCheckbox" class="w-4 h-4">
                                    <span class="text-sm text-gray-600">Select All</span>
                                </div>
                                
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
                                            <div class="flex items-center w-full event-row" data-status="<?= $status ?>" data-category="<?= esc($category) ?>">
                                                <div class="flex-shrink-0 flex flex-col items-center justify-center h-full pr-2" onclick="event.stopPropagation();">
                                                    <input type="checkbox" class="event-checkbox w-5 h-5" value="<?= $event['event_id'] ?>">
                                                </div>
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
                                                            
                                                            <!-- Temporal Status Badge for Published Events -->
                                                            <?php if ($status === 'Published' && $temporalStatus): ?>
                                                                <span class="inline-flex items-center leading-none px-2.5 py-1.5 text-xs font-medium rounded-full border
                                                                    <?php
                                                                    switch($temporalStatus) {
                                                                        case 'upcoming':
                                                                            echo 'bg-blue-100 text-blue-800 border-blue-200';
                                                                            break;
                                                                        case 'ongoing':
                                                                            echo 'bg-purple-100 text-purple-800 border-purple-200';
                                                                            break;
                                                                        case 'completed':
                                                                            echo 'bg-gray-100 text-gray-800 border-gray-200';
                                                                            break;
                                                                    }
                                                                    ?>">
                                                                    <?= ucfirst($temporalStatus) ?>
                                                                </span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <h4 class="text-xl font-bold text-gray-900 group-hover:text-blue-700 mb-2"><?= esc($event['title']) ?></h4>
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
                                        <div class="col-span-3 text-center text-gray-500 py-8">No events found.</div>
                                    <?php endif; ?>
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
                    showNotificationWithLoading('Error: ' + (data.message || 'Unknown error occurred'), 'error');
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
    let activeStatus = 'Published';
    
    function filterEvents() {
        const activeCategory = categoryFilter ? categoryFilter.value : '';
        
        rows.forEach(row => {
            const rowStatus = row.dataset.status;
            const rowCategory = row.dataset.category || '';
            
            let showRow = true;
            
            // Filter by status
            if (rowStatus !== activeStatus) {
                showRow = false;
            }
            
            // Filter by category
            if (activeCategory && rowCategory !== activeCategory) {
                showRow = false;
            }
            
            row.style.display = showRow ? '' : 'none';
        });
        
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
            // Reset status filter to Published
            setActiveTab('Published');
            
            // Reset category filter
            if (categoryFilter) {
                categoryFilter.value = '';
            }
            
            // Apply filters
            filterEvents();
        });
    }
    
    // Initialize with Published status
    setActiveTab('Published');
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
