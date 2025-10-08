<style>
        /* Clean Color Palette: Blue (#3b82f6), Gray (#6b7280), Dark Gray (#374151), White (#ffffff) */
        table.dataTable thead th {
            @apply bg-gray-50 text-gray-600 text-sm font-medium;
        }
        table.dataTable tbody tr:hover {
            @apply bg-blue-50;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            @apply text-gray-600 hover:text-blue-600 transition;
        }

        /* Simplified Status Tabs */
        .status-tab {
            cursor: pointer;
            border: 1px solid #e5e7eb;
            background: white;
            color: #6b7280;
            transition: all 0.2s ease;
        }
        
        .status-tab:hover {
            border-color: #3b82f6;
            color: #3b82f6;
        }
        
        .status-tab.active {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        /* Simple animations */
        .animate-spin {
            animation: spin 1s linear infinite;
        }

        .animate-fadeInScale {
            animation: fadeInScale 0.2s ease-out;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes fadeInScale {
            0% { 
                opacity: 0; 
                transform: scale(0.95); 
            }
            100% { 
                opacity: 1; 
                transform: scale(1); 
            }
        }
    </style>


    <!-- ===== MAIN CONTENT AREA ===== -->
    <!-- Content area positioned to the right of sidebar -->
    <div class="flex-1 flex flex-col min-h-0 ml-64 pt-16">
        <!-- Main Content Container -->
        <main class="flex-1 overflow-auto p-6 bg-gray-50">
            <!-- Breadcrumbs -->
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-2">
                    <li class="inline-flex items-center">
                        <a href="<?= base_url('sk/dashboard') ?>" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-blue-600">
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
                            <a href="<?= base_url('sk/youth-profile') ?>" class="text-sm font-medium text-gray-600 hover:text-blue-600">Youth Profile</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-2" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-600">RFID Assignment</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">RFID Assignment</h3>
                    <?php if (isset($barangay_name) && $barangay_name): ?>
                    <p class="text-sm text-gray-600 mt-1">Barangay <span class="font-semibold text-blue-600"><?= esc($barangay_name) ?></span></p>
                    <?php endif; ?>
                </div>
                <div class="flex gap-3">
                    <a href="<?= base_url('sk/youth-profile') ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Youth Profile
                    </a>
                </div>
            </div>

            <!-- Filter Tabs and Zone Selector -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <!-- Status Tabs -->
                        <div class="flex flex-wrap gap-2">
                            <button class="status-tab active px-4 py-2 rounded-lg text-sm font-medium transition-all" data-status="all">
                                All (<?= $total_users ?>)
                            </button>
                            <button class="status-tab px-4 py-2 rounded-lg text-sm font-medium transition-all" data-status="assigned">
                                Assigned (<?= $assigned_count ?>)
                            </button>
                            <button class="status-tab px-4 py-2 rounded-lg text-sm font-medium transition-all" data-status="unassigned">
                                Unassigned (<?= $unassigned_count ?>)
                            </button>
                        </div>
                        <!-- Zone Filter -->
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-medium text-gray-600">Zone:</span>
                            <select id="zoneFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Zones</option>
                                <!-- Zone options will be populated dynamically via JavaScript -->
                            </select>
                            <button id="clearFilters" class="px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                                Clear Filters
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table id="rfidTable" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 border-b">No.</th>
                                    <th class="px-4 py-2 border-b">User ID</th>
                                    <th class="px-4 py-2 border-b">Zone</th>
                                    <th class="px-4 py-2 border-b text-left">Full Name</th>
                                    <th class="px-4 py-2 border-b">Age</th>
                                    <th class="px-4 py-2 border-b">Sex</th>
                                    <th class="px-4 py-2 border-b">RFID Status</th>
                                    <th class="px-4 py-2 border-b">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($user_list)): ?>
                                    <?php foreach ($user_list as $index => $user): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-2 border-b text-center"><?= $index + 1 ?></td>
                                            <td class="px-4 py-2 border-b text-center">
                                                <?php if (isset($user['user_id']) && $user['user_id']): ?>
                                                    <?= esc($user['user_id']) ?>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-4 py-2 border-b text-center">
                                                <?= esc($user['zone_display']) ?>
                                            </td>
                                            <td class="px-4 py-2 border-b text-left"><?= $user['full_name'] ?></td>
                                            <td class="px-4 py-2 border-b text-center"><?= esc($user['age']) ?></td>
                                            <td class="px-4 py-2 border-b text-center"><?= esc($user['sex_text']) ?></td>
                                            <td class="px-4 py-2 border-b text-center">
                                                <span class="px-2 py-1 rounded-full text-xs font-medium <?= $user['rfid_status_class'] ?>"><?= $user['rfid_status'] ?></span>
                                            </td>
                                            <td class="px-4 py-2 border-b text-center">
                                                <button type="button" title="<?= $user['has_rfid'] ? 'View RFID' : 'Assign RFID' ?>" onclick="assignRFID('<?= esc($user['id']) ?>')" class="inline-flex items-center px-3 py-1 <?= $user['has_rfid'] ? 'bg-blue-600 hover:bg-blue-700' : 'bg-green-600 hover:bg-green-700' ?> text-white rounded transition-colors duration-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a5 5 0 00-10 0v2a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2z" />
                                                    </svg>
                                                    <?= $user['has_rfid'] ? 'View' : 'Assign' ?>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="8" class="px-4 py-2 text-center">No verified users found.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- RFID Assignment Modal -->
    <div id="rfidModal" class="fixed inset-0 z-[9999] hidden bg-black bg-opacity-40 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-4 animate-fadeInScale">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">RFID Assignment</h3>
                    <button onclick="closeRFIDModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6">
                <!-- User Profile Section -->
                <div class="mb-6 bg-gradient-to-br from-blue-50 to-blue-50 rounded-lg p-4 border border-blue-100">
                    <div class="flex items-center space-x-4">
                        <!-- Profile Picture -->
                        <div class="relative flex-shrink-0">
                            <img id="rfidUserPhoto" src="" alt="User Profile" class="w-20 h-20 object-cover rounded-full border-4 border-white shadow-lg hidden">
                            <div id="rfidUserPhotoPlaceholder" class="w-20 h-20 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center border-4 border-white shadow-lg">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                        </div>
                        <!-- User Information -->
                        <div class="flex-1 min-w-0">
                            <h4 id="rfidUserFullName" class="text-xl font-bold text-gray-900 truncate mb-1"></h4>
                            <div id="rfidUserTypeContainer" class="mb-2">
                                <span id="rfidUserType" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800"></span>
                            </div>
                            <div class="space-y-1">
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                    </svg>
                                    <span class="font-medium">User ID:</span>
                                    <span id="rfidUserId" class="ml-1 font-semibold text-gray-900"></span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span class="font-medium">Zone:</span>
                                    <span id="rfidUserZone" class="ml-1 font-semibold text-gray-900"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RFID Input Section -->
                <div class="space-y-4">
                    <div>
                        <label for="rfidNumber" class="block text-sm font-medium text-gray-700 mb-3">
                            RFID Number
                        </label>
                        <div class="relative">
                            <input type="text" id="rfidNumber" 
                                class="w-full px-4 py-4 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-center font-mono text-base" 
                                placeholder="Scan or enter RFID number..." 
                                autocomplete="off">
                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2 flex items-center space-x-2">
                                <button id="clearRFIDBtn" type="button"
                                    class="px-2 py-1 bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs rounded font-medium transition-colors hidden"
                                    title="Clear RFID"
                                    onclick="clearRFIDInput()">
                                    ✕
                                </button>
                                <button id="changeRFIDBtn" type="button" 
                                    class="px-2 py-1 bg-orange-500 hover:bg-orange-600 text-white text-xs rounded font-medium transition-colors hidden" 
                                    onclick="showChangeRFIDModal()">
                                    Change
                                </button>
                            </div>
                        </div>
                        <p id="rfidStatus" class="mt-2 text-sm font-medium hidden"></p>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex gap-3">
                <button onclick="closeRFIDModal()" 
                    class="flex-1 py-3 px-4 bg-white hover:bg-gray-50 text-gray-700 rounded-lg font-medium transition-colors border border-gray-200">
                    Cancel
                </button>
                <button id="saveRFIDBtn" onclick="saveRFID()" 
                    class="flex-1 py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                    Save RFID
                </button>
            </div>
        </div>
    </div>

    <!-- Change RFID Confirmation Modal -->
    <div id="changeRFIDModal" class="fixed inset-0 z-[99999] hidden bg-black bg-opacity-40 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-sm w-full mx-4 animate-fadeInScale">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-100 text-center">
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Change RFID</h3>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6 text-center">
                <p class="text-gray-600 text-sm mb-6">
                    Are you sure you want to change the RFID number? This will clear the current value.
                </p>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex gap-3">
                <button class="flex-1 py-1.5 px-3 bg-white hover:bg-gray-50 text-gray-700 rounded-md text-sm font-medium transition-colors border border-gray-200" onclick="closeChangeRFIDModal()">
                    Cancel
                </button>
                <button class="flex-1 py-1.5 px-3 bg-orange-600 hover:bg-orange-700 text-white rounded-md text-sm font-medium transition-colors" onclick="confirmChangeRFID()">
                    Change RFID
                </button>
            </div>
        </div>
    </div>

    <script>
    // Toast notification function with icons
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
    }    // DataTable initialization
    $(document).ready(function() {
        var table = $('#rfidTable').DataTable({
            "pageLength": 25,
            "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "order": [[6, "desc"], [1, "desc"]], // Sort by RFID Status desc (Unassigned first), then User ID desc
            "responsive": true,
            "columnDefs": [
                { "orderable": false, "targets": [7] } // Disable sorting on Action column (now column 7)
            ],
            // Ensure the No. column shows correct sequential numbers after sorting/paging
            "drawCallback": function(settings) {
                var api = this.api();
                api.column(0, {order: 'applied', search: 'applied'}).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }
        });

        // Status filter functionality
        $('.status-tab').click(function() {
            // Remove active class from all tabs
            $('.status-tab').removeClass('active');
            
            // Add active class to clicked tab
            $(this).addClass('active');

            var status = $(this).data('status');
            if (status === 'all') {
                // Clear all filters
                if ($.fn.dataTable.ext.search.length > 0) {
                    $.fn.dataTable.ext.search.pop();
                }
                table.draw();
            } else {
                // Remove any existing custom search
                if ($.fn.dataTable.ext.search.length > 0) {
                    $.fn.dataTable.ext.search.pop();
                }
                
                // Add custom search function using the userListForRFID data
                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    if (settings.nTable !== document.getElementById('rfidTable')) {
                        return true;
                    }
                    
                    // Get the user ID from the first column (index 0 shows row number, we need user ID)
                    // Find the corresponding user in the userListForRFID array
                    var rowIndex = dataIndex; // This is the original array index
                    if (window.userListForRFID && window.userListForRFID[rowIndex]) {
                        var user = window.userListForRFID[rowIndex];
                        var hasRfid = user.has_rfid || (user.rfid_code && user.rfid_code.trim() !== '');
                        
                        if (status === 'assigned') {
                            return hasRfid;
                        } else if (status === 'unassigned') {
                            return !hasRfid;
                        }
                    }
                    
                    return true;
                });
                
                table.draw();
            }
        });

        // Zone filter functionality
        $('#zoneFilter').on('change', function() {
            var zoneValue = $(this).val();
            table.column(2).search(zoneValue).draw(); // Zone is now column 2
        });

        // Clear filters button functionality
        $('#clearFilters').on('click', function() {
            // Reset zone filter dropdown
            $('#zoneFilter').val('');
            
            // Reset status tab selection
            $('.status-tab').removeClass('active');
            $('.status-tab[data-status="all"]').addClass('active');
            
            // Remove any custom search functions
            if ($.fn.dataTable.ext.search.length > 0) {
                $.fn.dataTable.ext.search.pop();
            }
            
            // Clear all column searches and reset table
            table.search('').columns().search('').draw();
            
            // Show notification
            showNotification('Filters cleared successfully', 'success');
        });

        // Populate zone filter from table data
        populateZoneFilter();
    });

    // Function to populate zone filter dropdown
    function populateZoneFilter() {
        const zoneSelect = document.getElementById('zoneFilter');
        const zones = new Set();
        
        // Get unique zones from table data
        $('#rfidTable tbody tr').each(function() {
            const zoneText = $(this).find('td:nth-child(3)').text().trim(); // Zone is now column 3 (1-indexed)
            if (zoneText && zoneText !== '-') {
                zones.add(zoneText);
            }
        });
        
        // Sort zones and add to dropdown
        const sortedZones = Array.from(zones).sort();
        sortedZones.forEach(zone => {
            const option = document.createElement('option');
            option.value = zone;
            option.textContent = zone;
            zoneSelect.appendChild(option);
        });
    }

    // Helper state for RFID input locking
    window.rfidScanLocked = false;
    window.rfidLockedValue = '';

    function setRFIDClearButtonVisible(visible) {
        var clearBtn = document.getElementById('clearRFIDBtn');
        if (!clearBtn) {
            return;
        }
        if (visible) {
            clearBtn.classList.remove('hidden');
        } else {
            clearBtn.classList.add('hidden');
        }
    }

    function lockRFIDInput(value) {
        if (window.rfidScanLocked) {
            return;
        }

        window.rfidScanLocked = true;
        window.rfidLockedValue = value;

        var input = document.getElementById('rfidNumber');
        if (input) {
            input.value = value;
            input.dataset.locked = 'true';
            input.blur();
        }

        setRFIDClearButtonVisible(true);
    }

    function unlockRFIDInput() {
        window.rfidScanLocked = false;
        window.rfidLockedValue = '';

        var input = document.getElementById('rfidNumber');
        if (input) {
            input.removeAttribute('data-locked');
        }

        setRFIDClearButtonVisible(false);
    }

    function clearRFIDInput() {
        var rfidInput = document.getElementById('rfidNumber');
        if (!rfidInput || rfidInput.disabled) {
            return;
        }

        rfidInput.value = '';
        unlockRFIDInput();

        var rfidStatus = document.getElementById('rfidStatus');
        var saveBtn = document.getElementById('saveRFIDBtn');
        var changeBtn = document.getElementById('changeRFIDBtn');

        if (rfidStatus) {
            rfidStatus.classList.add('hidden');
        }

        if (saveBtn) {
            saveBtn.innerHTML = '<svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Save RFID';
            saveBtn.disabled = false;
            saveBtn.className = 'flex-1 py-3 px-4 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition-colors';
        }

        if (changeBtn) {
            changeBtn.classList.add('hidden');
        }

        setRFIDClearButtonVisible(false);

        rfidInput.focus();
    }

    // Function to load and display user profile picture
    function loadUserProfilePicture(user) {
        var userPhoto = document.getElementById('rfidUserPhoto');
        var photoPlaceholder = document.getElementById('rfidUserPhotoPlaceholder');

        // Reset both elements first
        userPhoto.classList.add('hidden');
        photoPlaceholder.classList.remove('hidden');

        var rawValue = user && user.profile_picture !== undefined ? user.profile_picture : '';
        var normalizedValue = '';

        if (Array.isArray(rawValue)) {
            normalizedValue = rawValue.find(function(item) { return !!item; }) || '';
        } else if (rawValue && typeof rawValue === 'object') {
            if (rawValue.path) {
                normalizedValue = rawValue.path;
            } else if (rawValue.url) {
                normalizedValue = rawValue.url;
            } else {
                var firstValue = Object.values(rawValue).find(function(item) { return !!item; });
                normalizedValue = firstValue ? firstValue.toString() : '';
            }
        } else if (typeof rawValue === 'string') {
            var trimmed = rawValue.trim();
            if (trimmed.startsWith('[') || trimmed.startsWith('{')) {
                try {
                    var parsed = JSON.parse(trimmed);
                    if (Array.isArray(parsed)) {
                        normalizedValue = parsed.find(function(item) { return !!item; }) || '';
                    } else if (parsed && typeof parsed === 'object') {
                        normalizedValue = parsed.path || parsed.url || (Object.values(parsed).find(function(item) { return !!item; }) || '');
                    }
                } catch (error) {
                    console.warn('Unable to parse profile_picture JSON for', user.full_name, error);
                    normalizedValue = trimmed;
                }
            } else {
                normalizedValue = trimmed;
            }
        } else if (rawValue != null) {
            normalizedValue = rawValue.toString().trim();
        }

        var profilePictureUrl = '';
        if (normalizedValue) {
            if (/^https?:\/\//i.test(normalizedValue) || normalizedValue.startsWith('data:')) {
                profilePictureUrl = normalizedValue;
            } else if (normalizedValue.includes('/')) {
                profilePictureUrl = '<?= rtrim(base_url('/'), '/') ?>/' + normalizedValue.replace(/^\/+/,'');
            } else {
                profilePictureUrl = '<?= base_url('uploads/profile_pictures/') ?>' + normalizedValue;
            }
        }

        console.log('Profile picture debug:', {
            user: user ? user.full_name : null,
            rawValue: rawValue,
            normalizedValue: normalizedValue,
            profilePictureUrl: profilePictureUrl
        });

        if (profilePictureUrl) {
            var img = new Image();
            img.onload = function() {
                userPhoto.src = profilePictureUrl;
                userPhoto.classList.remove('hidden');
                photoPlaceholder.classList.add('hidden');
            };
            img.onerror = function(error) {
                console.error('Failed to load profile picture', profilePictureUrl, error);
                userPhoto.classList.add('hidden');
                photoPlaceholder.classList.remove('hidden');
            };
            img.src = profilePictureUrl;
        } else {
            userPhoto.classList.add('hidden');
            photoPlaceholder.classList.remove('hidden');
        }
    }

    // Show RFID modal and fill user info
    function assignRFID(userId) {
        console.log('assignRFID called with userId:', userId);
        console.log('Total users in userListForRFID:', window.userListForRFID ? window.userListForRFID.length : 0);
        
        // Find user data from user_list (PHP variable rendered as JS)
        var user = null;
        if (window.userListForRFID) {
            user = window.userListForRFID.find(function(u) { return u.id == userId; });
        }
        
        if (!user) {
            console.error('User not found with id:', userId);
            return;
        }
        
        console.log('User data found:', user);
        
        // Fill user info in modal
        document.getElementById('rfidUserFullName').textContent = user.full_name || '';
        document.getElementById('rfidUserId').textContent = user.user_id || '-';
        document.getElementById('rfidUserZone').textContent = user.zone_display || '-';
        
        // Display user type badge
        var userTypeElement = document.getElementById('rfidUserType');
        if (userTypeElement && user.user_type_label) {
            userTypeElement.textContent = user.user_type_label;
            // Change badge color based on user type
            userTypeElement.className = 'inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ';
            if (user.user_type == 1) {
                userTypeElement.className += 'bg-green-100 text-green-800'; // KK Member
            } else if (user.user_type == 2) {
                userTypeElement.className += 'bg-blue-100 text-blue-800'; // SK Official
            } else if (user.user_type == 3) {
                userTypeElement.className += 'bg-blue-100 text-blue-800'; // Pederasyon Officer
            }
        }
        
    // Reset RFID input lock before filling data
    unlockRFIDInput();

    // Load profile picture using the user data
        loadUserProfilePicture(user);
        
        var rfidInput = document.getElementById('rfidNumber');
        var rfidStatus = document.getElementById('rfidStatus');
        var saveBtn = document.getElementById('saveRFIDBtn');
        var changeBtn = document.getElementById('changeRFIDBtn');
        
        // Check if RFID is already assigned
        if (user.rfid_code && user.rfid_code.trim() !== '') {
            // RFID already assigned - show as read-only
            rfidInput.value = user.rfid_code;
            rfidInput.disabled = true;
            rfidInput.classList.add('bg-gray-100', 'cursor-not-allowed');
            rfidInput.classList.remove('focus:ring-blue-500', 'focus:border-transparent');
            rfidStatus.textContent = '✓ RFID code already assigned';
            rfidStatus.className = 'mt-2 text-sm font-medium text-blue-600';
            rfidStatus.classList.remove('hidden');
            saveBtn.innerHTML = '<svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>Already Assigned';
            saveBtn.disabled = true;
            saveBtn.className = 'flex-1 py-3 px-4 bg-gray-400 text-white rounded-lg font-semibold cursor-not-allowed';
            changeBtn.classList.remove('hidden'); // Show change button when RFID is assigned
            setRFIDClearButtonVisible(false);
        } else {
            // No RFID assigned - allow input
            rfidInput.value = '';
            rfidInput.disabled = false;
            rfidInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
            rfidInput.classList.add('focus:ring-blue-500', 'focus:border-transparent');
            rfidStatus.classList.add('hidden');
            saveBtn.innerHTML = '<svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Save RFID';
            saveBtn.disabled = false;
            saveBtn.className = 'flex-1 py-3 px-4 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition-colors';
            changeBtn.classList.add('hidden'); // Hide change button when no RFID assigned
            setRFIDClearButtonVisible(false);
            
            // Auto-focus the RFID input field for scanner
            setTimeout(function() {
                rfidInput.focus();
                rfidInput.select();
            }, 200);
        }
        
        // Show modal
        document.getElementById('rfidModal').classList.remove('hidden');
        document.getElementById('rfidModal').classList.add('flex');
        window.currentRFIDUserId = userId;
    }
    
    function closeRFIDModal() {
        document.getElementById('rfidModal').classList.add('hidden');
        document.getElementById('rfidModal').classList.remove('flex');
        window.currentRFIDUserId = null;
        
        // Reset form state
        var rfidInput = document.getElementById('rfidNumber');
        var rfidStatus = document.getElementById('rfidStatus');
        var saveBtn = document.getElementById('saveRFIDBtn');
        var changeBtn = document.getElementById('changeRFIDBtn');
        
        rfidInput.value = '';
        rfidInput.disabled = false;
        rfidInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
        rfidInput.classList.add('focus:ring-blue-500', 'focus:border-transparent');
        rfidStatus.classList.add('hidden');
        saveBtn.innerHTML = '<svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Save RFID';
        saveBtn.disabled = false;
        saveBtn.className = 'flex-1 py-3 px-4 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition-colors';
        changeBtn.classList.add('hidden'); // Hide change button when modal closes
        
        unlockRFIDInput();

        // Clear any pending timeouts
        if (window.rfidCheckTimeout) {
            clearTimeout(window.rfidCheckTimeout);
        }
    }
    
    function saveRFID() {
        var rfidInput = document.getElementById('rfidNumber');
        var rfid = rfidInput.value.trim();
        var userId = window.currentRFIDUserId;
        
        // Check if button is disabled (RFID already assigned)
        if (rfidInput.disabled) {
            showNotification('RFID code is already assigned to this user.', 'error');
            return;
        }
        
        if (!rfid) {
            showNotification('Please enter an RFID number.', 'error');
            return;
        }
        
        if (!userId) {
            showNotification('User ID not found.', 'error');
            return;
        }
        
        // Show loading state
        var saveBtn = document.getElementById('saveRFIDBtn');
        var originalText = saveBtn.textContent;
        saveBtn.textContent = 'Saving...';
        saveBtn.disabled = true;
        
        // AJAX call to save RFID
        fetch('<?= base_url('sk/saveRFID') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'user_id=' + encodeURIComponent(userId) + '&rfid_number=' + encodeURIComponent(rfid)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('RFID assigned successfully!', 'success');
                closeRFIDModal();
                // Reload the page to reflect changes
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification('Error saving RFID: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error saving RFID. Please try again.', 'error');
        })
        .finally(() => {
            // Reset button state
            saveBtn.textContent = originalText;
            saveBtn.disabled = false;
        });
    }
    
    // Show Change RFID modal
    function showChangeRFIDModal() {
        document.getElementById('changeRFIDModal').classList.remove('hidden');
        document.getElementById('changeRFIDModal').classList.add('flex');
    }

    // Close Change RFID modal
    function closeChangeRFIDModal() {
        document.getElementById('changeRFIDModal').classList.add('hidden');
        document.getElementById('changeRFIDModal').classList.remove('flex');
    }

    // Confirm change: clear RFID field and enable input
    function confirmChangeRFID() {
        var rfidInput = document.getElementById('rfidNumber');
        var rfidStatus = document.getElementById('rfidStatus');
        var saveBtn = document.getElementById('saveRFIDBtn');
        var changeBtn = document.getElementById('changeRFIDBtn');
        
        rfidInput.value = '';
        rfidInput.disabled = false;
        rfidInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
        rfidInput.classList.add('focus:ring-blue-500', 'focus:border-transparent');
        rfidStatus.classList.add('hidden');
        saveBtn.innerHTML = '<svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Save RFID';
        saveBtn.disabled = false;
        saveBtn.className = 'flex-1 py-3 px-4 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition-colors';
        changeBtn.classList.add('hidden'); // Hide change button when allowing new input
        
        unlockRFIDInput();

        closeChangeRFIDModal();
        setTimeout(function() { 
            rfidInput.focus(); 
            rfidInput.select(); 
        }, 200);
    }

    // Check for duplicate RFID in real-time
    function checkRFIDDuplicate(rfidValue) {
        if (!rfidValue.trim()) return;
        
        fetch('<?= base_url('sk/checkRFIDDuplicate') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'rfid_number=' + encodeURIComponent(rfidValue) + '&current_user_id=' + encodeURIComponent(window.currentRFIDUserId || '')
        })
        .then(response => response.json())
        .then(data => {
            var rfidStatus = document.getElementById('rfidStatus');
            var saveBtn = document.getElementById('saveRFIDBtn');
            var changeBtn = document.getElementById('changeRFIDBtn');
            var rfidInput = document.getElementById('rfidNumber');
            
            if (data.duplicate) {
                // RFID already exists
                rfidStatus.textContent = '⚠ Error: RFID code already assigned to ' + (data.assigned_to || 'another user');
                rfidStatus.className = 'mt-2 text-sm font-medium text-red-600';
                rfidStatus.classList.remove('hidden');
                saveBtn.innerHTML = '<svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>Cannot Save';
                saveBtn.disabled = true;
                saveBtn.className = 'flex-1 py-3 px-4 bg-red-400 text-white rounded-lg font-semibold cursor-not-allowed';
                changeBtn.classList.remove('hidden'); // Show change button when there's an error

                unlockRFIDInput();
                if (rfidInput) {
                    rfidInput.value = '';
                    rfidInput.focus();
                }
                setRFIDClearButtonVisible(false);
            } else {
                // RFID is available
                rfidStatus.textContent = '✓ RFID code is available';
                rfidStatus.className = 'mt-2 text-sm font-medium text-green-600';
                rfidStatus.classList.remove('hidden');
                saveBtn.innerHTML = '<svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Save RFID';
                saveBtn.disabled = false;
                saveBtn.className = 'flex-1 py-3 px-4 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition-colors';
                changeBtn.classList.add('hidden'); // Hide change button when RFID is valid

                lockRFIDInput(rfidValue);
            }
        })
        .catch(error => {
            console.error('Error checking RFID:', error);
        });
    }

    // Add event listener for RFID input
    document.addEventListener('DOMContentLoaded', function() {
        var rfidInput = document.getElementById('rfidNumber');
        if (rfidInput) {
            rfidInput.addEventListener('input', function() {
                if (window.rfidScanLocked) {
                    this.value = window.rfidLockedValue || '';
                    return;
                }

                var value = this.value.trim();
                if (value.length > 0) {
                    // Very short debounce for instant feel but avoid excessive calls
                    clearTimeout(window.rfidCheckTimeout);

                    window.rfidCheckTimeout = setTimeout(function() {
                        if (!window.rfidScanLocked) {
                            checkRFIDDuplicate(value);
                        }
                    }, 100);

                    setRFIDClearButtonVisible(true);
                } else {
                    // Clear status when input is empty
                    var rfidStatus = document.getElementById('rfidStatus');
                    var saveBtn = document.getElementById('saveRFIDBtn');
                    var changeBtn = document.getElementById('changeRFIDBtn');

                    unlockRFIDInput();

                    rfidStatus.classList.add('hidden');
                    saveBtn.innerHTML = '<svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Save RFID';
                    saveBtn.disabled = false;
                    saveBtn.className = 'flex-1 py-3 px-4 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition-colors';
                    changeBtn.classList.add('hidden');
                }
            });

            // Also check on paste event for instant feedback
            rfidInput.addEventListener('paste', function() {
                if (window.rfidScanLocked) {
                    this.value = window.rfidLockedValue || '';
                    return;
                }

                var self = this;
                setTimeout(function() {
                    var value = self.value.trim();
                    if (value.length > 0) {
                        checkRFIDDuplicate(value);
                        setRFIDClearButtonVisible(true);
                    }
                }, 10);
            });

            // Check on blur (when user finishes typing)
            rfidInput.addEventListener('blur', function() {
                if (window.rfidScanLocked) {
                    this.value = window.rfidLockedValue || '';
                    return;
                }

                var value = this.value.trim();
                if (value.length > 0) {
                    clearTimeout(window.rfidCheckTimeout);
                    checkRFIDDuplicate(value);
                    setRFIDClearButtonVisible(true);
                }
            });
        }

        // Modal event listeners
        const rfidModal = document.getElementById('rfidModal');
        const changeRFIDModal = document.getElementById('changeRFIDModal');

        // Close modal on outside click
        rfidModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeRFIDModal();
            }
        });

        changeRFIDModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeChangeRFIDModal();
            }
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (!rfidModal.classList.contains('hidden')) {
                    closeRFIDModal();
                } else if (!changeRFIDModal.classList.contains('hidden')) {
                    closeChangeRFIDModal();
                }
            }
        });
    });

    // Prevent unwanted scans unless RFID field is focused and enabled
    function isRFIDInputReady() {
        var rfidInput = document.getElementById('rfidNumber');
        return rfidInput && !rfidInput.disabled && document.activeElement === rfidInput;
    }

    document.addEventListener('keydown', function(e) {
        // Most barcode scanners send input as a series of keydown events ending with Enter
        // Only allow input if RFID field is ready
        var rfidInput = document.getElementById('rfidNumber');
        if (!isRFIDInputReady()) {
            // If Enter is pressed and RFID field is not ready, prevent default
            if (e.key === 'Enter' || (e.key.length === 1 && e.key.match(/[A-Za-z0-9]/))) {
                e.preventDefault();
                e.stopPropagation();
            }
        }
    });

    // Prepare user list for JS
    window.userListForRFID = <?php echo json_encode($user_list ?? []); ?>;
    </script>
