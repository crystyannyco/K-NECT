
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

        /* Responsive DataTable Wrapper */
        .dataTables_wrapper {
            width: 100%;
            overflow: hidden;
        }

        /* Responsive table container */
        .table-container {
            width: 100%;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }

        /* Prevent horizontal overflow issues */
        .dataTables_scrollHead,
        .dataTables_scrollBody,
        .dataTables_scrollFoot {
            overflow-x: hidden !important;
        }

        /* Ensure table responsiveness */
        #myTable {
            width: 100% !important;
            table-layout: auto;
        }

        #myTable th:nth-child(3),
        #myTable td:nth-child(3),
        #myTable th:nth-child(4),
        #myTable td:nth-child(4) {
            white-space: normal !important;
        }

        #myTable td:nth-child(3),
        #myTable td:nth-child(4) {
            word-break: break-word;
        }

        /* Mobile responsive adjustments */
        @media (max-width: 768px) {
            .status-tab {
                font-size: 0.75rem;
                padding: 0.5rem 0.75rem;
            }
            
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter,
            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_paginate {
                margin: 0.5rem 0;
            }

            #myTable th,
            #myTable td {
                padding: 0.5rem 0.25rem !important;
                font-size: 0.75rem;
            }

            .table-container {
                margin: -0.5rem;
                padding: 0.5rem;
                overflow-x: auto;
            }
            
            .dataTables_scrollBody {
                overflow-x: auto !important;
            }
            
            /* Ensure header spans full width on mobile */
            header {
                left: 0 !important;
            }
            
            /* Adjust main content margin for mobile */
            .flex-1.flex.flex-col.min-h-0 {
                margin-left: 0 !important;
            }
        }

        @media (max-width: 640px) {
            #myTable th,
            #myTable td {
                padding: 0.25rem !important;
                font-size: 0.7rem;
            }
        }
        
        /* Simplified Status Tabs */
        .status-tab {
            cursor: pointer;
            border: 1px solid #e5e7eb;
            background: white;
            color: #6b7280;
            transition: all 0.2s ease;
            flex-shrink: 0;
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

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Position column ellipsis and tooltip styles */
        .position-cell {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            position: relative;
            cursor: help;
        }

        .position-cell:hover::after {
            content: attr(data-full-text);
            position: absolute;
            left: 0;
            top: 100%;
            z-index: 1000;
            background-color: #1f2937;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 0.875rem;
            white-space: normal;
            min-width: 200px;
            max-width: 300px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            margin-top: 4px;
            word-wrap: break-word;
        }

        /* Arrow for tooltip */
        .position-cell:hover::before {
            content: '';
            position: absolute;
            left: 20px;
            top: 100%;
            z-index: 1001;
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            border-bottom: 6px solid #1f2937;
        }

        /* Occupied position styling */
        option.position-occupied {
            color: #9ca3af !important;
            background-color: #f3f4f6 !important;
            font-style: italic;
        }

        select option.position-occupied {
            color: #9ca3af;
        }
    </style>
        
        <!-- ===== MAIN CONTENT AREA ===== -->
        <div class="flex-1 flex flex-col min-h-0 ml-0 lg:ml-64 pt-16">
            <main class="flex-1 overflow-auto overflow-x-hidden p-4 lg:p-6 bg-gray-50">
                <!-- Breadcrumbs -->
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
                                <span class="text-sm font-medium text-gray-600">Pederasyon Officers</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <!-- Header Section -->
                <div class="mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">Pederasyon Officers List</h3>
                        <p class="text-sm text-gray-600 mt-1">Manage Pederasyon officer positions and profiles</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button id="downloadOfficialListBtn" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Download Official List
                        </button>
                        <button id="downloadCredentialsBtn" class="inline-flex items-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg transition-colors justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Download Credentials
                        </button>
                    </div>
                </div>
                
                <!-- Filter Tabs and Dropdowns -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                    <div class="p-4">
                        <div class="flex flex-wrap items-center gap-3">
                            <!-- Main Category Tabs -->
                            <button class="status-tab active px-4 py-2 rounded-lg text-sm font-medium transition-all" data-category="all">
                                All (<span id="countAll">0</span>)
                            </button>
                            <button class="status-tab px-4 py-2 rounded-lg text-sm font-medium transition-all" data-category="officers">
                                Officers (<span id="countOfficers">0</span>)
                            </button>
                            <button class="status-tab px-4 py-2 rounded-lg text-sm font-medium transition-all" data-category="members">
                                Members (<span id="countMembers">0</span>)
                            </button>
                            
                            <!-- Vertical Divider -->
                            <div class="hidden lg:block h-8 w-px bg-gray-300"></div>
                            
                            <!-- Position Dropdown -->
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-medium text-gray-600 whitespace-nowrap">Position:</span>
                                <select id="positionFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">All Positions</option>
                                    <option value="president">President</option>
                                    <option value="vicepresident">Vice President</option>
                                    <option value="secretary">Secretary</option>
                                    <option value="treasurer">Treasurer</option>
                                    <option value="auditor">Auditor</option>
                                    <option value="pio">Public Information Officer</option>
                                    <option value="sergeant">Sergeant at Arms</option>
                                    <option value="member">Member</option>
                                </select>
                            </div>
                            
                            <!-- Barangay Filter -->
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-medium text-gray-600 whitespace-nowrap">Barangay:</span>
                                <select id="barangayFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">All Barangays</option>
                                    <!-- Barangay options will be populated dynamically -->
                                </select>
                            </div>
                            
                            <!-- Clear Filters Button -->
                            <button id="clearFilters" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors whitespace-nowrap">
                                Clear Filters
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6">
                        <div class="table-container">
                            <table id="myTable" class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                            <input type="checkbox" id="selectAllRows" class="form-checkbox h-4 w-4 text-blue-600">
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">ID</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Barangay</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Name</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Age</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Sex</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Position</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php if (!empty($ped_officers)): ?>
                                        <?php foreach ($ped_officers as $officer): ?>
                                            <tr class="hover:bg-gray-50"
                                                data-ped_username="<?= isset($officer['ped_username']) ? esc($officer['ped_username']) : '' ?>"
                                                data-ped_password="<?= isset($officer['ped_password']) ? esc($officer['ped_password']) : '' ?>">
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <input type="checkbox" class="rowCheckbox form-checkbox h-4 w-4 text-blue-600" value="<?= esc($officer['id']) ?>">
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($officer['user_id']) ?></td>
                                                <td class="px-4 py-4 text-sm text-gray-900">
                                                    <?= esc($officer['barangay_name'] ?? ($officer['barangay'] ?? '')) ?>
                                                </td>
                                                <td class="px-4 py-4 text-sm text-gray-900"><?= esc($officer['last_name']) ?>, <?= esc($officer['first_name']) ?> <?= esc($officer['middle_name']) ?></td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($officer['age']) ?></td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900"><?= $officer['sex'] == '1' ? 'Male' : ($officer['sex'] == '2' ? 'Female' : '') ?></td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <?php
                                                    $status = isset($officer['status']) ? (int)$officer['status'] : 1;
                                                    $statusClass = '';
                                                    $statusText = '';
                                                    switch($status) {
                                                        case 2:
                                                            $statusClass = 'bg-green-100 text-green-800';
                                                            $statusText = 'Accepted';
                                                            break;
                                                        case 3:
                                                            $statusClass = 'bg-red-100 text-red-800';
                                                            $statusText = 'Rejected';
                                                            break;
                                                        default:
                                                            $statusClass = 'bg-yellow-100 text-yellow-800';
                                                            $statusText = 'Pending';
                                                    }
                                                    ?>
                                                    <span class="px-2 py-1 rounded-full text-sm font-medium <?= $statusClass ?>"><?= $statusText ?></span>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <?php
                                                        $positionMap = [
                                                            1 => 'President',
                                                            2 => 'Vice President',
                                                            3 => 'Secretary',
                                                            4 => 'Treasurer',
                                                            5 => 'Auditor',
                                                            6 => 'Public Information Officer',
                                                            7 => 'Sergeant at Arms'
                                                        ];

                                                        $positionText = trim($officer['position_display'] ?? '');
                                                        if ($positionText === '') {
                                                            $pedPosition = isset($officer['ped_position']) ? (int)$officer['ped_position'] : 0;
                                                            $positionText = $positionMap[$pedPosition] ?? 'Member';
                                                        }
                                                        $displayText = $positionText;
                                                    ?>
                                                    <span class="position-cell" data-full-text="<?= esc($positionText) ?>">
                                                        <?= esc($displayText) ?>
                                                    </span>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <button type="button" 
                                                        class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors view-user-btn"
                                                        data-id="<?= esc($officer['id']) ?>"
                                                    >
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        View
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="px-4 py-4 text-center text-gray-500">
                                                <div class="flex flex-col items-center justify-center py-8">
                                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Officers Found</h3>
                                                    <p class="text-gray-500">No SK Chairpersons or Pederasyon Officers have been added yet.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Bulk Change Button (hidden by default) -->
    <button id="bulkChangeBtn" class="fixed bottom-8 left-1/2 transform -translate-x-1/2 z-50 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-base font-medium rounded-lg shadow-lg transition-all duration-200 flex items-center gap-2 hidden">
        <span>Change Position for Selected</span>
    </button>

    <!-- Bulk Change Modal -->
    <div id="bulkChangeModal" class="fixed inset-0 z-[99999] hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl relative overflow-hidden flex flex-col">
            <!-- Modal Header -->
            <div class="bg-white border-b border-gray-200 px-6 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Bulk Change Officer Position</h3>
                        <p class="text-sm text-gray-500 mt-1">Apply a new position to all selected officers.</p>
                    </div>
                    <button id="closeBulkChangeModal" class="text-gray-400 hover:text-gray-600 focus:outline-none transition-colors p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Content -->
            <div class="px-6 py-5">
                <label for="bulkNewPosition" class="block text-sm font-medium text-gray-700 mb-2">Select New Position</label>
                <select id="bulkNewPosition" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="1">President</option>
                    <option value="2">Vice President</option>
                    <option value="3">Secretary</option>
                    <option value="4">Treasurer</option>
                    <option value="5">Auditor</option>
                    <option value="6">Public Information Officer</option>
                    <option value="7">Sergeant at Arms</option>
                    <option value="NULL">Member</option>
                </select>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 border-t border-gray-200 px-6 py-4">
                <div class="flex justify-end gap-3">
                    <button id="cancelBulkChangeBtn" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors duration-200">
                        Cancel
                    </button>
                    <button id="confirmBulkChangeBtn" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-200 shadow-sm">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- User Detail Modal - Enhanced Design from member.php -->
    <div id="userDetailModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] relative overflow-hidden">
            <!-- Confirmation Popup inside Modal -->
            <div id="roleChangeModal" class="absolute inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-40">
                <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 18.5C3.498 20.333 4.46 22 6 22z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Change Officer Position</h3>
                        <p id="roleChangeMessage" class="text-gray-600 mb-6">Are you sure you want to change the position?</p>
                        <div class="flex justify-center gap-3">
                            <button id="confirmRoleChangeBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium transition-all duration-200 shadow-sm">Confirm</button>
                            <button id="cancelRoleChangeBtn" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-medium transition-all duration-200">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Header -->
            <div class="bg-white border-b border-gray-200 px-6 py-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Pederasyon Officer Profile</h3>
                    <button id="closeUserDetailModal" class="text-gray-400 hover:text-gray-600 focus:outline-none transition-colors p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Content -->
            <div class="flex" style="height: calc(100% - 64px);">
                <!-- Left Side - Profile Card -->
                <div class="w-1/3 bg-gray-50 p-6 flex flex-col">
                    <!-- Profile Photo -->
                    <div class="text-center mb-4">
                        <div class="relative inline-block">
                            <div class="w-36 h-36 mx-auto bg-gray-200 rounded-lg overflow-hidden shadow-lg border-4 border-white">
                                <img id="modalUserPhoto" src="" alt="Profile" class="w-full h-full object-cover">
                            </div>
                        </div>
                        <h4 id="modalUserFullName" class="text-lg font-bold text-gray-900 mt-2 mb-1"></h4>
                        <p id="modalUserBarangay" class="text-sm text-gray-500 mb-2"></p>
                        <span id="modalUserStatus" class="inline-flex px-3 py-1 rounded-full text-xs font-medium"></span>
                    </div>
                    
                    <!-- Position Management Card -->
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <label class="text-sm font-semibold text-gray-700">Officer Position</label>
                        </div>
                        <select id="modalOfficerPosition" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent mb-3">
                            <option value="1">President</option>
                            <option value="2">Vice President</option>
                            <option value="3">Secretary</option>
                            <option value="4">Treasurer</option>
                            <option value="5">Auditor</option>
                            <option value="6">Public Information Officer</option>
                            <option value="7">Sergeant at Arms</option>
                            <option value="NULL">Member</option>
                        </select>
                        <button id="saveOfficerPositionBtn" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg text-sm font-medium transition-all duration-200 shadow-sm">
                            Update Position
                        </button>
                    </div>
                </div>

                <!-- Right Side - Information Sections -->
                <div class="w-2/3 p-6 overflow-y-auto" style="max-height: calc(90vh - 140px);">
                    <div class="space-y-6">
                        <!-- Basic Information -->
                        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                            <div class="flex items-center gap-2 mb-4">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <h5 class="text-lg font-semibold text-gray-900">Basic Information</h5>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Full Name</label>
                                    <p id="modalUserName" class="text-sm font-medium text-gray-900"></p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">User ID</label>
                                    <p id="modalUserId" class="text-sm font-medium text-gray-900"></p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Gender</label>
                                    <p id="modalUserSex" class="text-sm font-medium text-gray-900"></p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Email</label>
                                    <p id="modalUserEmail" class="text-sm font-medium text-gray-900"></p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Birthday</label>
                                    <p id="modalUserBirthday" class="text-sm font-medium text-gray-900"></p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Age</label>
                                    <p id="modalUserAge" class="text-sm font-medium text-gray-900"></p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Civil Status</label>
                                    <p id="modalUserCivilStatus" class="text-sm font-medium text-gray-900"></p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Address Information -->
                        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                            <div class="flex items-center gap-2 mb-4">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <h5 class="text-lg font-semibold text-gray-900">Address Information</h5>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Barangay</label>
                                    <p id="modalUserBarangayDetail" class="text-sm font-medium text-gray-900"></p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Zone</label>
                                    <p id="modalUserZone" class="text-sm font-medium text-gray-900"></p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Complete Address</label>
                                    <p id="modalUserAddress" class="text-sm font-medium text-gray-900"></p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Youth Classification -->
                        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                            <div class="flex items-center gap-2 mb-4">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                </svg>
                                <h5 class="text-lg font-semibold text-gray-900">Youth Classification</h5>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Youth Classification</label>
                                    <p id="modalUserYouthClassification" class="text-sm font-medium text-gray-900"></p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Work Status</label>
                                    <p id="modalUserWorkStatus" class="text-sm font-medium text-gray-900"></p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Youth Age Group</label>
                                    <p id="modalUserYouthAgeGroup" class="text-sm font-medium text-gray-900"></p>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Educational Background</label>
                                    <p id="modalUserEducation" class="text-sm font-medium text-gray-900"></p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Voting Information -->
                        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                            <div class="flex items-center gap-2 mb-4">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <h5 class="text-lg font-semibold text-gray-900">Voting Information</h5>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Registered SK Voter</label>
                                    <span id="modalUserSKVoter" class="inline-flex px-2 py-1 rounded-full text-xs font-medium"></span>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Voted Last SK Election</label>
                                    <span id="modalUserVotedSK" class="inline-flex px-2 py-1 rounded-full text-xs font-medium"></span>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Registered National Voter</label>
                                    <span id="modalUserNationalVoter" class="inline-flex px-2 py-1 rounded-full text-xs font-medium"></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Assembly Attendance -->
                        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                            <div class="flex items-center gap-2 mb-4">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <h5 class="text-lg font-semibold text-gray-900">KK Assembly Attendance</h5>
                            </div>
                            <div class="space-y-3">
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">Have you attended a KK Assembly?</label>
                                    <span id="modalUserAttendedAssembly" class="inline-flex px-2 py-1 rounded-full text-xs font-medium"></span>
                                </div>
                                <div id="assemblyTimesContainer" class="bg-gray-50 rounded-lg p-3">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">How many times?</label>
                                    <p id="modalUserAssemblyTimes" class="text-sm font-medium text-gray-900"></p>
                                </div>
                                <div id="assemblyReasonContainer" class="hidden bg-gray-50 rounded-lg p-3">
                                    <label class="block text-xs font-medium text-gray-500 mb-1">If No, Why?</label>
                                    <p id="modalUserAssemblyReason" class="text-sm font-medium text-gray-900"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    // Barangay mapping provided by backend
    const barangayMap = <?= isset($barangay_map) ? json_encode($barangay_map) : json_encode([]) ?>;
        
        // Helper function to get barangay name
        function getBarangayName(barangayId) {
            return barangayMap[barangayId] || barangayId || '';
        }
        
        function showNotification(message, type = 'info') {
            // Ensure notification container exists
            let container = document.getElementById('notificationStackContainer');
            if (!container) {
                container = document.createElement('div');
                container.id = 'notificationStackContainer';
                container.className = 'fixed top-4 right-4 z-[99999] flex flex-col gap-2 items-end';
                document.body.appendChild(container);
            }

            // Notification element
            const notification = document.createElement('div');
            let bgClass = '', icon = '';
            switch(type) {
                case 'success':
                    bgClass = 'bg-green-500 text-white';
                    icon = '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>';
                    break;
                case 'error':
                    bgClass = 'bg-red-500 text-white';
                    icon = '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z" /></svg>';
                    break;
                case 'info':
                default:
                    bgClass = 'bg-blue-500 text-white';
                    icon = '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01" /></svg>';
                    break;
            }
            notification.className = `p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full min-w-[250px] flex items-center ${bgClass}`;
            notification.setAttribute('role', 'alert');
            notification.innerHTML = `
                ${icon}
                <span class="flex-1 mr-2">${message}</span>
                <button type="button" aria-label="Close notification" class="ml-2 text-white hover:text-gray-200 focus:outline-none" tabindex="0">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            `;
            // Close button handler
            notification.querySelector('button').onclick = function() {
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 300);
            };
            // Add to stack
            container.appendChild(notification);
            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 300);
            }, 5000);
        }
        
        // Store original counts globally
        let originalCounts = {
            all: 0,
            officers: 0,
            members: 0
        };
        
        // Function to mark occupied positions in the dropdown
        function updateOccupiedPositions(currentUserId) {
            // Get all occupied positions from the table
            const occupiedPositions = {};
            
            // Scan through all table rows to find occupied positions
            $('#myTable tbody tr').each(function() {
                const rowUserId = $(this).find('.rowCheckbox').val();
                // Get the full position text from data-full-text attribute (handles truncated display)
                const positionCell = $(this).find('td').eq(7).find('.position-cell');
                const positionText = positionCell.length > 0 ? positionCell.attr('data-full-text') : $(this).find('td').eq(7).text().trim();
                
                // Skip the current user being edited
                if (rowUserId == currentUserId) {
                    return;
                }
                
                // Map position text to position values
                const positionMap = {
                    'President': '1',
                    'Vice President': '2',
                    'Secretary': '3',
                    'Treasurer': '4',
                    'Auditor': '5',
                    'Public Information Officer': '6',
                    'Sergeant at Arms': '7'
                };
                
                // Check if this row has an occupied position
                for (const [posName, posValue] of Object.entries(positionMap)) {
                    if (positionText.includes(posName)) {
                        occupiedPositions[posValue] = true;
                        break;
                    }
                }
            });
            
            // Update the dropdown options
            $('#modalOfficerPosition option').each(function() {
                const optionValue = $(this).val();
                
                // Skip the "Member" option (NULL)
                if (optionValue === 'NULL' || optionValue === '0') {
                    $(this).removeClass('position-occupied');
                    return;
                }
                
                // Mark as occupied if position is taken
                if (occupiedPositions[optionValue]) {
                    $(this).addClass('position-occupied');
                    const currentText = $(this).text();
                    if (!currentText.includes('(Occupied)')) {
                        $(this).text(currentText + ' (Occupied)');
                    }
                } else {
                    $(this).removeClass('position-occupied');
                    $(this).text($(this).text().replace(' (Occupied)', ''));
                }
            });
        }
        
        // Function to mark occupied positions in bulk modal (excluding selected users)
        function updateOccupiedPositionsForBulk(excludeUserIds) {
            const occupiedPositions = {};
            
            // Scan through all table rows to find occupied positions
            $('#myTable tbody tr').each(function() {
                const rowUserId = $(this).find('.rowCheckbox').val();
                // Get the full position text from data-full-text attribute (handles truncated display)
                const positionCell = $(this).find('td').eq(7).find('.position-cell');
                const positionText = positionCell.length > 0 ? positionCell.attr('data-full-text') : $(this).find('td').eq(7).text().trim();
                
                // Skip users that are selected for bulk update
                if (excludeUserIds.includes(rowUserId)) {
                    return;
                }
                
                // Map position text to position values
                const positionMap = {
                    'President': '1',
                    'Vice President': '2',
                    'Secretary': '3',
                    'Treasurer': '4',
                    'Auditor': '5',
                    'Public Information Officer': '6',
                    'Sergeant at Arms': '7'
                };
                
                // Check if this row has an occupied position
                for (const [posName, posValue] of Object.entries(positionMap)) {
                    if (positionText.includes(posName)) {
                        occupiedPositions[posValue] = true;
                        break;
                    }
                }
            });
            
            // Update the bulk modal dropdown options
            $('#bulkNewPosition option').each(function() {
                const optionValue = $(this).val();
                
                // Skip the "Member" option (NULL)
                if (optionValue === 'NULL') {
                    $(this).removeClass('position-occupied');
                    return;
                }
                
                // Mark as occupied if position is taken
                if (occupiedPositions[optionValue]) {
                    $(this).addClass('position-occupied');
                    const currentText = $(this).text();
                    if (!currentText.includes('(Occupied)')) {
                        $(this).text(currentText + ' (Occupied)');
                    }
                } else {
                    $(this).removeClass('position-occupied');
                    $(this).text($(this).text().replace(' (Occupied)', ''));
                }
            });
        }
        
        $(document).ready(function () {
            // Clean up placeholder rows to avoid DataTables column mismatch when no data
            (function ensureConsistentCellsForDataTables() {
                const $table = $('#myTable');
                const headerCount = $table.find('thead th').length;
                $table.find('tbody tr').each(function () {
                    const cellCount = $(this).find('td').length;
                    if (cellCount < headerCount) {
                        // Remove any placeholder row (e.g., a single colspan cell)
                        $(this).remove();
                    }
                });
            })();

            // DataTable initialization
            const table = $('#myTable').DataTable({
                columnDefs: [
                    { orderable: false, targets: 0, width: '40px' },  // Checkbox column
                    { width: '60px', targets: 1 },   // ID column
                    { width: '120px', targets: 2 },  // Barangay column
                    { width: 'auto', targets: 3 },   // Name column
                    { width: '50px', targets: 4 },   // Age column
                    { width: '60px', targets: 5 },   // Sex column
                    { width: '100px', targets: 6 },  // Status column
                    { width: '120px', targets: 7 },  // Position column
                    { width: '80px', targets: 8 }    // Action column
                ],
                order: [[1, 'asc']],
                scrollCollapse: true,
                scrollY: '500px',
                scrollX: false,
                paging: true,
                pageLength: 25,
                info: true,
                searching: true,
                autoWidth: false,
                responsive: false,
                language: {
                    search: "Search officers:",
                    searchPlaceholder: "Type to search...",
                    emptyTable: "No records found",
                    zeroRecords: "No matching records found"
                },
                initComplete: function () {
                    // Apply Tailwind utility classes to DataTable components
                    $('#myTable_wrapper').addClass('text-sm text-gray-700');
                    $('#myTable_length label').addClass('inline-flex items-center gap-2');
                    $('#myTable_length select').addClass('border border-gray-300 rounded px-2 py-1');
                    $('#myTable_info').addClass('mt-2 text-gray-600');
                    $('#myTable_paginate').addClass('mt-4');
                    $('#myTable_paginate span a').addClass('px-2 py-1 border rounded mx-1');
                    
                    // Initialize filters after table is ready
                    setTimeout(() => {
                        populateBarangayFilter();
                        calculateOriginalCounts();
                        updateDisplayedCounts();
                        restoreFilters();
                    }, 100);
                }
            });

            // Populate barangay filter
            function populateBarangayFilter() {
                const barangays = new Set();
                $('#myTable tbody tr').each(function() {
                    const barangay = $(this).find('td').eq(2).text().trim();
                    if (barangay && barangay !== '') {
                        barangays.add(barangay);
                    }
                });
                
                $('#barangayFilter').empty().append('<option value="">All Barangays</option>');
                Array.from(barangays).sort().forEach(barangay => {
                    $('#barangayFilter').append(`<option value="${barangay}">${barangay}</option>`);
                });
            }

            // Calculate original counts from all data (not filtered)
            function calculateOriginalCounts() {
                let allCount = 0, officersCount = 0, membersCount = 0;
                
                // Count all rows, not just visible ones
                $('#myTable tbody tr').each(function() {
                    if ($(this).find('td').length > 1) { // Skip "no data" rows
                        const position = $(this).find('td').eq(7).text().trim();
                        allCount++;
                        
                        // Officers: President, VP, Secretary, Treasurer, Auditor, PIO, Sergeant
                        if (position === 'President' || 
                            position === 'Vice President' ||
                            position === 'Secretary' ||
                            position === 'Treasurer' ||
                            position === 'Auditor' ||
                            position === 'Public Information Officer' ||
                            position === 'Sergeant at Arms') {
                            officersCount++;
                        } else {
                            membersCount++; // Member
                        }
                    }
                });
                
                // Store original counts
                originalCounts = {
                    all: allCount,
                    officers: officersCount,
                    members: membersCount
                };
            }

            // Update displayed counts (always show original counts)
            function updateDisplayedCounts() {
                $('#countAll').text(originalCounts.all);
                $('#countOfficers').text(originalCounts.officers);
                $('#countMembers').text(originalCounts.members);
            }

            // Position tab filtering logic
            function setActiveCategoryTab(tab) {
                $('.status-tab').removeClass('active bg-blue-500 text-white')
                    .addClass('bg-gray-100');
                
                tab.removeClass('bg-gray-100')
                    .addClass('active bg-blue-500 text-white');
            }

            // Apply filters with DataTable integration
            function applyFilters() {
                const categoryFilter = $('.status-tab.active').data('category');
                const positionFilter = $('#positionFilter').val();
                const barangayFilter = $('#barangayFilter').val();
                
                // Clear existing DataTable search
                table.search('').columns().search('');
                
                // Apply category and position filters using DataTable column search
                let searchTerms = [];
                
                if (categoryFilter === 'officers') {
                    // Show all officers (President, VP, Secretary, Treasurer, Auditor, PIO, Sergeant)
                    searchTerms = [
                        'President',
                        'Vice President',
                        'Secretary',
                        'Treasurer',
                        'Auditor',
                        'Public Information Officer',
                        'Sergeant at Arms'
                    ];
                } else if (categoryFilter === 'members') {
                    // Show only members
                    searchTerms = ['Member'];
                }
                
                // If a specific position is selected in dropdown, override category filter
                if (positionFilter) {
                    searchTerms = [];
                    if (positionFilter === 'president') {
                        searchTerms = ['President'];
                    } else if (positionFilter === 'vicepresident') {
                        searchTerms = ['Vice President'];
                    } else if (positionFilter === 'secretary') {
                        searchTerms = ['Secretary'];
                    } else if (positionFilter === 'treasurer') {
                        searchTerms = ['Treasurer'];
                    } else if (positionFilter === 'auditor') {
                        searchTerms = ['Auditor'];
                    } else if (positionFilter === 'pio') {
                        searchTerms = ['Public Information Officer'];
                    } else if (positionFilter === 'sergeant') {
                        searchTerms = ['Sergeant at Arms'];
                    } else if (positionFilter === 'member') {
                        searchTerms = ['Member'];
                    }
                }
                
                if (searchTerms.length > 0) {
                    const regex = searchTerms.join('|');
                    table.column(7).search(regex, true, false);
                }
                
                // Apply barangay filter using DataTable column search
                if (barangayFilter) {
                    table.column(2).search('^' + barangayFilter + '$', true, false);
                }
                
                // Redraw table with filters applied
                table.draw();
                
                // Keep displaying original counts (don't update them based on filtered results)
                updateDisplayedCounts();
            }

            // Category tab click handlers
            $('.status-tab').on('click', function() {
                setActiveCategoryTab($(this));
                applyFilters();
                localStorage.setItem('activeCategoryTab', $(this).data('category'));
            });

            // Position dropdown change handler
            $('#positionFilter').on('change', function() {
                applyFilters();
                localStorage.setItem('activePositionFilter', $(this).val());
            });

            // Barangay filter change handler
            $('#barangayFilter').on('change', function() {
                applyFilters();
                localStorage.setItem('activeBarangayFilter', $(this).val());
            });

            // Clear filters
            $('#clearFilters').on('click', function() {
                $('.status-tab[data-category="all"]').trigger('click');
                $('#positionFilter').val('');
                $('#barangayFilter').val('');
                table.search('').columns().search('').draw();
                localStorage.removeItem('activeCategoryTab');
                localStorage.removeItem('activePositionFilter');
                localStorage.removeItem('activeBarangayFilter');
                updateDisplayedCounts();
                showNotification('Filters cleared successfully', 'success');
            });

            // Function to restore saved filters
            function restoreFilters() {
                const savedCategoryTab = localStorage.getItem('activeCategoryTab') || 'all';
                const savedPositionFilter = localStorage.getItem('activePositionFilter') || '';
                const savedBarangayFilter = localStorage.getItem('activeBarangayFilter') || '';
                
                $('.status-tab[data-category="' + savedCategoryTab + '"]').trigger('click');
                $('#positionFilter').val(savedPositionFilter);
                $('#barangayFilter').val(savedBarangayFilter);
                applyFilters();
            }

            // Bulk select checkboxes
            $('#selectAllRows').on('change', function() {
                var checked = $(this).is(':checked');
                $('.rowCheckbox').prop('checked', checked);
                updateBulkChangeBtn();
            });
            
            $(document).on('change', '.rowCheckbox', function() {
                if (!$(this).is(':checked')) {
                    $('#selectAllRows').prop('checked', false);
                } else if ($('.rowCheckbox:checked').length === $('.rowCheckbox').length) {
                    $('#selectAllRows').prop('checked', true);
                }
                updateBulkChangeBtn();
            });
            
            // Show/hide bulk change button
            function updateBulkChangeBtn() {
                if ($('.rowCheckbox:checked').length > 0) {
                    $('#bulkChangeBtn').removeClass('hidden');
                } else {
                    $('#bulkChangeBtn').addClass('hidden');
                }
            }
            
            // Open bulk change modal
            $('#bulkChangeBtn').on('click', function() {
                // Update occupied positions for bulk modal (excluding selected users)
                const selectedIds = $('.rowCheckbox:checked').map(function() { return $(this).val(); }).get();
                updateOccupiedPositionsForBulk(selectedIds);
                
                $('#bulkChangeModal').removeClass('hidden').css('display', 'flex');
            });
            
            // Close modal handlers
            $('#closeBulkChangeModal, #cancelBulkChangeBtn').on('click', function() {
                $('#bulkChangeModal').addClass('hidden').css('display', 'none');
            });
            
            // Confirm bulk change
            $('#confirmBulkChangeBtn').on('click', function() {
                var selectedIds = $('.rowCheckbox:checked').map(function() { return $(this).val(); }).get();
                var newPosition = $('#bulkNewPosition').val();
                
                if (selectedIds.length === 0) {
                    showNotification('No officers selected.', 'error');
                    return;
                }
                
                // Show loading state
                $(this).prop('disabled', true).text('Updating...');
                
                // AJAX request to bulk update positions
                $.ajax({
                    url: '<?= base_url('bulkUpdateOfficerPosition') ?>',
                    method: 'POST',
                    data: { officer_ids: selectedIds, ped_position: newPosition },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            showNotification(response.message || 'Officer positions updated successfully!', 'success');
                            
                            // Reload the page to reflect changes and check for officer warning
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        } else {
                            showNotification(response.message || 'Failed to update officer positions.', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', {xhr, status, error});
                        let errorMessage = 'Failed to update officer positions.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showNotification(errorMessage, 'error');
                    },
                    complete: function() {
                        $('#confirmBulkChangeBtn').prop('disabled', false).text('Confirm');
                        $('#bulkChangeModal').addClass('hidden').css('display', 'none');
                    }
                });
            });

            // User Detail Modal functionality (adapted for officers)
            $(document).on('click', '.view-user-btn', function(e) {
                e.preventDefault();
                var userId = $(this).data('id');
                
                // Show loading state
                $('#userDetailModal').removeClass('hidden');
                $('#modalUserFullName').text('Loading...');
                
                $.ajax({
                    url: '<?= base_url('getUserInfo') ?>',
                    method: 'POST',
                    data: { user_id: userId },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            var u = response.user;
                            
                            // Mappings for profiling fields
                            var civilStatusMap = <?= json_encode($field_mappings['civilStatusMap'] ?? []) ?>;
                            var youthClassificationMap = <?= json_encode($field_mappings['youthClassificationMap'] ?? []) ?>;
                            var ageGroupMap = <?= json_encode($field_mappings['ageGroupMap'] ?? []) ?>;
                            var workStatusMap = <?= json_encode($field_mappings['workStatusMap'] ?? []) ?>;
                            var educationMap = <?= json_encode($field_mappings['educationMap'] ?? []) ?>;
                            var howManyTimesMap = <?= json_encode($field_mappings['howManyTimesMap'] ?? []) ?>;
                            var noWhyMap = <?= json_encode($field_mappings['noWhyMap'] ?? []) ?>;
                            
                            // Populate modal fields
                            var fullName = u.first_name + ' ' + (u.middle_name ? u.middle_name + ' ' : '') + u.last_name + (u.suffix ? ', ' + u.suffix : '');
                            $('#modalUserFullName').text(fullName);
                            $('#modalUserName').text(fullName);
                            
                            // Barangay display
                            var barangayStr = barangayMap[u.barangay] || u.barangay || '';
                            $('#modalUserBarangay').text(barangayStr);
                            $('#modalUserBarangayDetail').text(barangayStr);
                            
                            $('#modalUserId').text(u.user_id || '');
                            // Store the database ID for later use in position updates
                            $('#modalUserId').data('db-id', u.id);
                            $('#modalUserAge').text(u.age + ' years old');
                            $('#modalUserSex').text(u.sex == '1' ? 'Male' : (u.sex == '2' ? 'Female' : ''));
                            $('#modalOfficerPosition').val(String(u.ped_position || 0));
                            
                            // Mark occupied positions in grey
                            updateOccupiedPositions(u.id);
                            
                            $('#modalUserEmail').text(u.email || '');
                            
                            if (u.birthdate) {
                                const dateObj = new Date(u.birthdate);
                                if (!isNaN(dateObj)) {
                                    const day = dateObj.getDate();
                                    const month = dateObj.toLocaleString('default', { month: 'long' });
                                    const year = dateObj.getFullYear();
                                    $('#modalUserBirthday').text(`${day}, ${month}, ${year}`);
                                } else {
                                    $('#modalUserBirthday').text(u.birthdate);
                                }
                            } else {
                                $('#modalUserBirthday').text('');
                            }
                            
                            $('#modalUserCivilStatus').text(civilStatusMap[u.civil_status] || '');
                            
                            let statusText = '';
                            let statusClass = '';
                            if (u.status == 1) {
                                statusText = 'Pending';
                                statusClass = 'bg-yellow-100 text-yellow-800';
                            } else if (u.status == 2) {
                                statusText = 'Accepted';
                                statusClass = 'bg-green-100 text-green-800';
                            } else if (u.status == 3) {
                                statusText = 'Rejected';
                                statusClass = 'bg-red-100 text-red-800';
                            }
                            $('#modalUserStatus').text(statusText)
                                .removeClass()
                                .addClass('inline-flex px-2 py-1 rounded-full text-sm font-medium ' + statusClass);
                                
                            $('#modalUserZone').text(u.zone_purok || '');
                            
                            // Address formatting
                            var addressParts = [];
                            if (u.zone_purok) addressParts.push(u.zone_purok);
                            if (barangayStr) addressParts.push(barangayStr);
                            addressParts.push('Iriga City');
                            addressParts.push('Camarines Sur');
                            addressParts.push('Region 5');
                            var fullAddress = addressParts.join(', ');
                            $('#modalUserAddress').text(fullAddress);
                            
                            $('#modalUserYouthClassification').text(youthClassificationMap[u.youth_classification] || '');
                            $('#modalUserWorkStatus').text(workStatusMap[u.work_status] || '');
                            $('#modalUserYouthAgeGroup').text(ageGroupMap[u.age_group] || '');
                            $('#modalUserEducation').text(educationMap[u.educational_background] || '');
                            
                            // Yes/No fields with color
                            function setYesNoColor(selector, value) {
                                let text = '';
                                let colorClass = '';
                                if (value === '1') {
                                    text = 'Yes';
                                    colorClass = 'bg-green-100 text-green-800';
                                } else if (value === '0') {
                                    text = 'No';
                                    colorClass = 'bg-red-100 text-red-800';
                                } else {
                                    text = '';
                                    colorClass = 'bg-yellow-100 text-yellow-800';
                                }
                                $(selector).text(text)
                                    .removeClass()
                                    .addClass('inline-flex px-2 py-1 rounded-full text-xs font-medium ' + colorClass);
                            }
                            
                            setYesNoColor('#modalUserSKVoter', u.sk_voter);
                            setYesNoColor('#modalUserVotedSK', u.sk_election);
                            setYesNoColor('#modalUserNationalVoter', u.national_voter);
                            setYesNoColor('#modalUserAttendedAssembly', u.kk_assembly);
                            
                            $('#modalUserAssemblyTimes').text(howManyTimesMap[u.how_many_times] || '');
                            $('#modalUserAssemblyReason').text(noWhyMap[u.no_why] || '');
                            
                            // Robust profile picture resolution for modal (absolute URL, relative path, or filename) with fallback
                            (function(){
                                const pp = u.profile_picture || '';
                                const defaultAvatar = '<?= base_url('assets/images/default-avatar.svg') ?>';
                                let imgUrl = defaultAvatar;
                                if (pp) {
                                    if (/^https?:\/\//i.test(pp) || pp.startsWith('data:')) {
                                        imgUrl = pp;
                                    } else if (pp.includes('/')) {
                                        imgUrl = '<?= rtrim(base_url('/'), '/') ?>/' + pp.replace(/^\/+/, '');
                                    } else {
                                        imgUrl = '<?= base_url('uploads/profile_pictures/') ?>' + pp;
                                    }
                                }
                                const $img = $('#modalUserPhoto');
                                $img.off('error').on('error', function(){ this.onerror=null; this.src = defaultAvatar; $(this).show(); });
                                $img.attr('src', imgUrl).show();
                            })();
                            
                            // Disable position change if status is Rejected or Pending
                            if (u.status == 3 || u.status == 1) {
                                $('#modalOfficerPosition').prop('disabled', true);
                                $('#saveOfficerPositionBtn').prop('disabled', true).addClass('bg-gray-300 cursor-not-allowed').removeClass('bg-blue-600 hover:bg-blue-700');
                            } else {
                                $('#modalOfficerPosition').prop('disabled', false);
                                $('#saveOfficerPositionBtn').prop('disabled', false).removeClass('bg-gray-300 cursor-not-allowed').addClass('bg-blue-600 hover:bg-blue-700');
                            }
                        } else {
                            showNotification('Officer not found.', 'error');
                            $('#userDetailModal').addClass('hidden');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', {xhr, status, error});
                        let errorMessage = 'Failed to fetch officer info.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showNotification(errorMessage, 'error');
                        $('#userDetailModal').addClass('hidden');
                    }
                });
            });

            // Close modal functionality
            $('#closeUserDetailModal').on('click', function() {
                $('#userDetailModal').addClass('hidden');
            });

            // Close modal when clicking outside
            $('#userDetailModal').on('click', function(e) {
                if (e.target === this) {
                    $('#userDetailModal').addClass('hidden');
                }
            });

            // Save officer position functionality
            let pendingPositionChange = { userId: null, dbId: null, newPosition: null };
            
            $('#saveOfficerPositionBtn').on('click', function() {
                // Store the intended change with both user_id and database id
                pendingPositionChange.userId = $('#modalUserId').text();
                pendingPositionChange.dbId = $('#modalUserId').data('db-id');
                pendingPositionChange.newPosition = $('#modalOfficerPosition').val();
                
                // Show confirmation modal
                $('#roleChangeModal').removeClass('hidden');
                $('#roleChangeModal').css('display', 'flex');
            });

            // Confirm position change
            $('#confirmRoleChangeBtn').on('click', function() {
                const dbId = pendingPositionChange.dbId;
                const newPosition = pendingPositionChange.newPosition;
                
                // Validate that we have a database ID
                if (!dbId) {
                    showNotification('Error: Unable to identify user. Please try again.', 'error');
                    $('#roleChangeModal').addClass('hidden').css('display', 'none');
                    $('#userDetailModal').addClass('hidden');
                    return;
                }
                
                // Show loading state
                $(this).prop('disabled', true).text('Updating...');
                
                // Find the user row to get the database ID
                const userRow = $(`tr[data-ped_username]`).filter(function() {
                    return $(this).find('td').eq(1).text().trim() === userId;
                });
                
                const dbId = userRow.find('.rowCheckbox').val();
                
                // Handle "NULL" string for SK Pederasyon Member position
                let pedPositionValue;
                if (newPosition === 'NULL') {
                    pedPositionValue = 'NULL';
                } else {
                    pedPositionValue = parseInt(newPosition, 10);
                }
                
                $.ajax({
                    url: '<?= base_url('updateOfficerPosition') ?>',
                    method: 'POST',
                    data: { user_id: dbId || userId, ped_position: pedPositionValue },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            showNotification(response.message || 'Officer position updated successfully!', 'success');
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        } else {
                            showNotification(response.message || 'Failed to update officer position.', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', {xhr, status, error});
                        let errorMessage = 'Failed to update officer position.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showNotification(errorMessage, 'error');
                    },
                    complete: function() {
                        $('#confirmRoleChangeBtn').prop('disabled', false).text('Confirm');
                        // Close both modals
                        $('#roleChangeModal').addClass('hidden').css('display', 'none');
                        $('#userDetailModal').addClass('hidden');
                    },
                    success: function(response) {
                        if (response.success) {
                            showNotification(response.message, 'success');
                            
                            // Reload the page to reflect changes and check for officer warning
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        } else {
                            showNotification(response.message || 'Failed to update position', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        showNotification('Error: ' + error, 'error');
                    }
                });
            });

            // Cancel position change
            $('#cancelRoleChangeBtn').on('click', function() {
                $('#roleChangeModal').addClass('hidden');
                $('#roleChangeModal').css('display', 'none');
            });

            // Prevent modal from closing when clicking inside the modal content
            $('#userDetailModal .bg-white').on('click', function(e) {
                e.stopPropagation();
            });
        });
        
        // ==================== OFFICER WARNING MODAL ==================== //
        
        // Show officer warning modal when no officers with credentials remain
        function showOfficerWarningModal(officers) {
            const modal = document.getElementById('officerWarningModal');
            const currentOfficersSection = document.getElementById('currentOfficersSection');
            const currentOfficersTableBody = document.getElementById('currentOfficersTableBody');
            
            if (!modal) return;
            
            // If there are officers, show the section and populate the table
            if (officers && officers.length > 0) {
                currentOfficersSection.classList.remove('hidden');
                currentOfficersTableBody.innerHTML = '';
                
                officers.forEach(officer => {
                    const row = document.createElement('tr');
                    row.className = 'hover:bg-gray-50';
                    row.innerHTML = `
                        <td class="px-4 py-2 text-gray-900">${officer.name}</td>
                        <td class="px-4 py-2 text-gray-700">${officer.position}</td>
                        <td class="px-4 py-2 text-gray-700 font-mono text-xs">${officer.username}</td>
                    `;
                    currentOfficersTableBody.appendChild(row);
                });
            } else {
                currentOfficersSection.classList.add('hidden');
            }
            
            // Show modal
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
        }
        
        // Close officer warning modal
        function closeOfficerWarningModal() {
            const modal = document.getElementById('officerWarningModal');
            if (!modal) return;
            modal.classList.add('hidden');
            modal.style.display = 'none';
        }
        
        // Download credentials from warning modal
        function downloadCredentialsFromWarning() {
            closeOfficerWarningModal();
            // Open the credentials modal
            openPedCredentialsPreviewModal();
        }
    </script>

    <script>
        // ==================== CREDENTIALS FUNCTIONALITY ==================== //
        
        // Open credentials preview modal
        function openPedCredentialsPreviewModal() {
            const modal = document.getElementById('pedCredentialsPreviewModal');
            modal.style.display = 'flex';
            
            // Show loading state
            const credentialsLoadingEl = document.getElementById('pedCredentialsLoading');
            const credentialsContentEl = document.getElementById('pedCredentialsContent');

            if (credentialsLoadingEl) credentialsLoadingEl.classList.remove('hidden');
            if (credentialsContentEl) credentialsContentEl.classList.add('hidden');

            // Load credentials data after short delay for UX
            const doLoadCredentials = () => {
                loadPedCredentialsLogos();
                loadPedCredentialsData();
                if (credentialsLoadingEl) credentialsLoadingEl.classList.add('hidden');
                if (credentialsContentEl) credentialsContentEl.classList.remove('hidden');
            };

            if (credentialsLoadingEl) {
                setTimeout(doLoadCredentials, 800);
            } else {
                doLoadCredentials();
            }
        }
        
        // Close credentials preview modal
        function closePedCredentialsPreviewModal() {
            const modal = document.getElementById('pedCredentialsPreviewModal');
            if (!modal) return;
            modal.style.display = 'none';
        }
        
        // Load logos for credentials modal
        function loadPedCredentialsLogos() {
            fetch('<?= base_url('documents/logos') ?>')
                .then(response => response.json())
                .then(data => {
                    if (data && data.success && data.data) {
                        const logos = data.data;
                        
                        // Load Pederasyon logo
                        const pederasyonLogoDiv = document.getElementById('ped-credentials-pederasyon-logo');
                        if (logos.pederasyon && logos.pederasyon.file_path && pederasyonLogoDiv) {
                            pederasyonLogoDiv.innerHTML = `<img src="<?= base_url() ?>${logos.pederasyon.file_path}" alt="Pederasyon Logo" class="w-full h-full object-contain">`;
                        }
                        
                        // Load Iriga City logo
                        const irigaLogoDiv = document.getElementById('ped-credentials-iriga-logo');
                        if (logos.iriga_city && logos.iriga_city.file_path && irigaLogoDiv) {
                            irigaLogoDiv.innerHTML = `<img src="<?= base_url() ?>${logos.iriga_city.file_path}" alt="Iriga City Logo" class="w-full h-full object-contain">`;
                        }
                    } else {
                        console.error('Failed to load logos for credentials:', data ? data.message : 'No data');
                    }
                })
                .catch(error => {
                    console.error('Error fetching logos for credentials:', error);
                });
        }
        
        // Load credentials data from API
        function loadPedCredentialsData() {
            fetch('<?= base_url('pederasyon/ped-officers-credentials-data') ?>')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.success) {
                        const pedCredentials = (data.data && data.data.ped) ? data.data.ped : [];
                        
                        // Populate credentials table
                        populatePedCredentialsTable(pedCredentials);
                        
                        // Update count
                        const countEl = document.getElementById('pedCredentialsCount');
                        if (countEl) {
                            countEl.textContent = pedCredentials.length;
                        }
                        
                        // Update total count
                        const totalEl = document.getElementById('pedCredentialsTotalCount');
                        if (totalEl) {
                            totalEl.textContent = `Total: ${pedCredentials.length} Pederasyon Officers with credentials`;
                        }
                        
                        // Show/hide no credentials message
                        const noCredsEl = document.getElementById('pedNoCredentials');
                        const containerEl = document.getElementById('pedCredentialsTablesContainer');
                        
                        if (pedCredentials.length === 0) {
                            if (noCredsEl) noCredsEl.classList.remove('hidden');
                            if (containerEl) containerEl.classList.add('hidden');
                        } else {
                            if (noCredsEl) noCredsEl.classList.add('hidden');
                            if (containerEl) containerEl.classList.remove('hidden');
                        }
                    } else {
                        // Show error message
                        const noCredsEl = document.getElementById('pedNoCredentials');
                        const containerEl = document.getElementById('pedCredentialsTablesContainer');
                        if (noCredsEl) noCredsEl.classList.remove('hidden');
                        if (containerEl) containerEl.classList.add('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error fetching credentials data:', error);
                    // Show error message
                    const noCredsEl = document.getElementById('pedNoCredentials');
                    const containerEl = document.getElementById('pedCredentialsTablesContainer');
                    if (noCredsEl) noCredsEl.classList.remove('hidden');
                    if (containerEl) containerEl.classList.add('hidden');
                });
        }
        
        // Populate credentials table
        function populatePedCredentialsTable(credentials) {
            const tableBody = document.getElementById('pedCredentialsTableBody');
            
            if (!tableBody) {
                return;
            }
            
            tableBody.innerHTML = '';
            
            if (!credentials || credentials.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="border border-gray-300 text-center py-8 bg-gray-50 text-sm text-gray-600">
                            <div class="flex flex-col items-center">
                                <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <div class="font-semibold mb-1">No Pederasyon Credentials</div>
                                <div>No Pederasyon officers with credentials found.</div>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }
            
            credentials.forEach((credential, index) => {
                const row = document.createElement('tr');
                row.className = index % 2 === 0 ? 'bg-white' : 'bg-gray-50';
                
                // Format full name
                const fullName = `${credential.first_name || ''} ${credential.middle_name || ''} ${credential.last_name || ''}`.trim() || 'N/A';
                const barangayName = getBarangayName(credential.barangay);
                
                // Handle username display
                let displayUsername = credential.ped_username || 'N/A';
                let usernameClass = 'text-gray-900 font-semibold';
                if (displayUsername === 'Not Set' || displayUsername === 'N/A') {
                    displayUsername = '<span class="text-gray-400 italic">Not Set</span>';
                    usernameClass = '';
                }
                
                // Handle password display
                let displayPassword = 'N/A';
                let passwordClass = 'text-gray-900 font-semibold';
                
                if (credential.ped_password && credential.ped_password !== 'Not Set') {
                    // Check if password is hashed (starts with $2y$, $2b$ or is longer than 20 characters)
                    const isHashedPassword = credential.ped_password.startsWith('$2y$') || 
                                            credential.ped_password.startsWith('$2b$') ||
                                            credential.ped_password.length > 20;
                    
                    if (isHashedPassword) {
                        // Show asterisks for hashed passwords
                        displayPassword = '********';
                    } else {
                        // Show actual password if it's not hashed (temporary password)
                        displayPassword = credential.ped_password;
                    }
                } else {
                    // Show "Not Set" for missing passwords
                    displayPassword = '<span class="text-gray-400 italic">Not Set</span>';
                    passwordClass = '';
                }
                
                row.innerHTML = `
                    <td class="border border-gray-300 text-center px-2 py-2 text-gray-900 text-xs">${credential.user_id || 'N/A'}</td>
                    <td class="border border-gray-300 text-center px-2 py-2 text-gray-900 text-xs">${fullName}</td>
                    <td class="border border-gray-300 text-center px-2 py-2 text-gray-900 text-xs">${barangayName || 'N/A'}</td>
                    <td class="border border-gray-300 text-center px-2 py-2 text-gray-900 text-xs">${credential.position || 'N/A'}</td>
                    <td class="border border-gray-300 text-center px-2 py-2 ${usernameClass} text-xs">${displayUsername}</td>
                    <td class="border border-gray-300 text-center px-2 py-2 ${passwordClass} text-xs">${displayPassword}</td>
                `;
                tableBody.appendChild(row);
            });
        }
        
        // Download credentials as PDF (client-side generation using jsPDF)
        async function downloadPedCredentialsPDF() {
            const button = event.target;
            const originalHTML = button.innerHTML;
            button.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Generating PDF...';
            button.disabled = true;

            try {
                // Fetch logos with proper error handling
                const logosResp = await fetch('<?= base_url('documents/logos') ?>');
                const logosJson = (logosResp.ok ? await logosResp.json() : { success: false, data: {} });
                const logos = (logosJson && logosJson.success && logosJson.data) ? logosJson.data : {};

                const pederasyonLogoPath = (logos.pederasyon?.file_path) || '';
                const irigaLogoPath = (logos.iriga_city?.file_path) || '';
                const pederasyonLogoUrl = pederasyonLogoPath ? '<?= base_url() ?>' + pederasyonLogoPath : '';
                const irigaLogoUrl = irigaLogoPath ? '<?= base_url() ?>' + irigaLogoPath : '';

                // Helper to convert image URL to data URL
                const imageUrlToDataUrl = (url) => {
                    return new Promise((resolve) => {
                        if (!url) {
                            resolve(null);
                            return;
                        }
                        const img = new Image();
                        img.crossOrigin = 'anonymous';
                        img.onload = function() {
                            const canvas = document.createElement('canvas');
                            canvas.width = this.naturalWidth;
                            canvas.height = this.naturalHeight;
                            const ctx = canvas.getContext('2d');
                            ctx.drawImage(this, 0, 0);
                            try {
                                resolve(canvas.toDataURL('image/png'));
                            } catch (e) {
                                console.error('Canvas toDataURL failed:', e);
                                resolve(null);
                            }
                        };
                        img.onerror = () => resolve(null);
                        img.src = url;
                    });
                };

                // Load both logos as data URLs
                const [pederasyonLogoDataUrl, irigaLogoDataUrl] = await Promise.all([
                    imageUrlToDataUrl(pederasyonLogoUrl),
                    imageUrlToDataUrl(irigaLogoUrl)
                ]);

                // Fetch credentials data
                const credResp = await fetch('<?= base_url('pederasyon/ped-officers-credentials-data') ?>');
                const credJson = await credResp.json();
                
                if (!credJson.success || !credJson.data || !credJson.data.ped || credJson.data.ped.length === 0) {
                    throw new Error('No Pederasyon credentials found');
                }
                
                // Extract the ped array from the nested structure
                const pedOfficials = credJson.data.ped;

                // Generate PDF with loaded logos
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF('l', 'mm', 'a4'); // landscape orientation
                const pageWidth = doc.internal.pageSize.getWidth();
                const centerX = pageWidth / 2;
                let y = 20;

                // Helper to get image format from data URL
                const getImgFmt = (dataUrl) => {
                    if (!dataUrl) return 'PNG';
                    if (dataUrl.includes('image/jpeg') || dataUrl.includes('image/jpg')) return 'JPEG';
                    if (dataUrl.includes('image/png')) return 'PNG';
                    return 'PNG';
                };

                // Add logos if available
                const logoSize = 25;
                if (pederasyonLogoDataUrl) {
                    doc.addImage(pederasyonLogoDataUrl, getImgFmt(pederasyonLogoDataUrl), 40, 15, logoSize, logoSize, undefined, 'FAST');
                }
                if (irigaLogoDataUrl) {
                    doc.addImage(irigaLogoDataUrl, getImgFmt(irigaLogoDataUrl), 232, 15, logoSize, logoSize, undefined, 'FAST');
                }
                
                // Header text (centered)
                doc.setFont("helvetica", "bold");
                doc.setFontSize(12);
                doc.text("REPUBLIC OF THE PHILIPPINES", centerX, 20, { align: 'center' });
                doc.text("PROVINCE OF CAMARINES SUR", centerX, 25, { align: 'center' });
                doc.text("CITY OF IRIGA", centerX, 30, { align: 'center' });
                
                doc.setFont("helvetica", "normal");
                doc.setFontSize(10);
                doc.text("PANLUNGSOD NA PEDERASYON NG MGA", centerX, 35, { align: 'center' });
                doc.text("SANGGUNIANG KABATAAN", centerX, 39, { align: 'center' });
                
                y = 60;
                
                // Title
                doc.setFont("helvetica", "bold");
                doc.setFontSize(12);
                doc.text("PANLUNGSOD NA PEDERASYON NG MGA SANGGUNIANG KABATAAN", centerX, 55, { align: 'center' });
                doc.text("OFFICIALS CREDENTIALS", centerX, 60, { align: 'center' });
                y += 8;
                
                // Prepare Pederasyon Officials table data
                const pedTableData = pedOfficials.map(official => {
                    const fullName = `${official.first_name || ''} ${official.middle_name || ''} ${official.last_name || ''}`.trim();
                    const barangay = getBarangayName(official.barangay);
                    
                    const positionMap = {
                        1: 'President',
                        2: 'Vice President', 
                        3: 'Secretary',
                        4: 'Treasurer',
                        5: 'Auditor',
                        6: 'PIO',
                        7: 'Sergeant at Arms'
                    };
                    const position = positionMap[parseInt(official.ped_position)] || 'Member';
                    const pedPassword = (official.ped_password && official.ped_password.length > 20) ? '********' : (official.ped_password || 'N/A');
                    
                    return [
                        official.user_id || '',
                        fullName,
                        barangay,
                        position,
                        official.ped_username || 'N/A',
                        pedPassword
                    ];
                });
                
                // Add Pederasyon Officials table with simple styling
                doc.autoTable({
                    head: [['User ID', 'Full Name', 'Barangay', 'Position', 'Username', 'Password']],
                    body: pedTableData,
                    startY: y,
                    styles: { 
                        fontSize: 7,
                        cellPadding: 1.5,
                        halign: 'center',
                        valign: 'middle',
                        textColor: [0, 0, 0],
                        fontStyle: 'normal',
                        font: 'helvetica',
                        lineWidth: 0.1,
                        lineColor: [0, 0, 0]
                    },
                    headStyles: {
                        fillColor: [220, 220, 220],
                        textColor: [0, 0, 0],
                        fontStyle: 'bold',
                        fontSize: 7,
                        font: 'helvetica',
                        halign: 'center'
                    },
                    columnStyles: {
                        0: { cellWidth: 16, halign: 'center' }, // User ID - reduced from 18
                        1: { cellWidth: 48, halign: 'center' }, // Full Name - reduced from 50
                        2: { cellWidth: 30, halign: 'center' }, // Barangay - reduced from 32
                        3: { cellWidth: 36, halign: 'center' }, // Position - increased from 35
                        4: { cellWidth: 38, halign: 'center' }, // Username - reduced from 40
                        5: { cellWidth: 22, halign: 'center' } // Password - reduced from 25
                    },
                    tableWidth: 190,
                    margin: { left: (287 - 190) / 2 }, // Center table on A4 landscape (297mm width)
                    theme: 'striped',
                    alternateRowStyles: {
                        fillColor: [245, 245, 245]
                    }
                });
                
                // Get Pederasyon President and Secretary names
                let presidentName = '';
                let secretaryName = '';
                
                pedOfficials.forEach(official => {
                    const fullName = `${official.first_name || ''} ${official.middle_name || ''} ${official.last_name || ''}`.replace(/\s+/g, ' ').trim();
                    const pedPosition = parseInt(official.ped_position);
                    
                    if (pedPosition === 1) { // President
                        presidentName = fullName;
                    } else if (pedPosition === 3) { // Secretary
                        secretaryName = fullName;
                    }
                });
                
                // Add signature section
                const finalY = doc.lastAutoTable.finalY + 15;
                const signatureSpacing = 60;
                const leftSignatureX = centerX - signatureSpacing;
                const rightSignatureX = centerX + signatureSpacing;
                
                doc.setFont("helvetica", "normal");
                doc.setFontSize(9);
                
                // Left signature (Prepared by - Secretary)
                doc.text('Prepared by:', leftSignatureX, finalY, { align: 'center' });
                doc.text('_________________________', leftSignatureX, finalY + 18, { align: 'center' });
                doc.setFont("helvetica", "bold");
                doc.text(secretaryName || '_________________________', leftSignatureX, finalY + 23, { align: 'center' });
                doc.setFont("helvetica", "normal");
                doc.text('Secretary', leftSignatureX, finalY + 28, { align: 'center' });
                
                // Right signature (Approved by - President)
                doc.text('Approved by:', rightSignatureX, finalY, { align: 'center' });
                doc.text('_________________________', rightSignatureX, finalY + 18, { align: 'center' });
                doc.setFont("helvetica", "bold");
                doc.text(presidentName || '_________________________', rightSignatureX, finalY + 23, { align: 'center' });
                doc.setFont("helvetica", "normal");
                doc.text('President', rightSignatureX, finalY + 28, { align: 'center' });
                
                // Save the PDF
                const fileName = 'Pederasyon_Officials_Credentials_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-') + '.pdf';
                doc.save(fileName);
                
                showNotification('Credentials PDF downloaded successfully!', 'success');
                
            } catch (error) {
                console.error('PDF generation error:', error);
                showNotification('Error generating PDF: ' + error.message, 'error');
            } finally {
                // Reset button state
                button.innerHTML = originalHTML;
                button.disabled = false;
            }
        }
        
        // Download credentials as Word (server-generated)
        function downloadPedCredentialsWord() {
            const button = event.target;
            const originalHTML = button.innerHTML;
            button.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Generating Word...';
            button.disabled = true;

            // Make AJAX request to generate Pederasyon credentials Word document
            fetch('<?= base_url('pederasyon/generate-ped-credentials-word') ?>', {
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
                // Get the filename from Content-Disposition header if available
                const contentDisposition = response.headers.get('Content-Disposition');
                let fileName = 'Pederasyon_Credentials.docx';
                if (contentDisposition) {
                    const matches = /filename="([^"]+)"/.exec(contentDisposition);
                    if (matches && matches[1]) {
                        fileName = matches[1];
                    }
                }
                return response.blob().then(blob => ({ blob, fileName }));
            })
            .then(({ blob, fileName }) => {
                // Create download link
                const url = window.URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                link.download = fileName;
                link.style.display = 'none';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                window.URL.revokeObjectURL(url);
                
                showNotification('Credentials Word document generated and downloaded successfully!', 'success');
            })
            .catch(error => {
                showNotification('Error generating credentials Word: ' + error.message + '. Please check your connection and try again.', 'error');
            })
            .finally(() => {
                // Reset button state
                button.innerHTML = originalHTML;
                button.disabled = false;
            });
        }
        
        // Download credentials as Excel (server-generated)
        function downloadPedCredentialsExcel() {
            const button = event.target;
            const originalHTML = button.innerHTML;
            button.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Generating Excel...';
            button.disabled = true;

            // Make AJAX request to generate Pederasyon credentials Excel document
            fetch('<?= base_url('pederasyon/generate-ped-credentials-excel') ?>', {
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
                // Get the filename from Content-Disposition header if available
                const contentDisposition = response.headers.get('Content-Disposition');
                let fileName = 'Pederasyon_Credentials.xlsx';
                if (contentDisposition) {
                    const matches = /filename="([^"]+)"/.exec(contentDisposition);
                    if (matches && matches[1]) {
                        fileName = matches[1];
                    }
                }
                return response.blob().then(blob => ({ blob, fileName }));
            })
            .then(({ blob, fileName }) => {
                // Create download link
                const url = window.URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                link.download = fileName;
                link.style.display = 'none';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                window.URL.revokeObjectURL(url);
                
                showNotification('Credentials Excel document generated and downloaded successfully!', 'success');
            })
            .catch(error => {
                showNotification('Error generating credentials Excel: ' + error.message + '. Please check your connection and try again.', 'error');
            })
            .finally(() => {
                // Reset button state
                button.innerHTML = originalHTML;
                button.disabled = false;
            });
        }
        
        // ==================== OFFICIAL LIST FUNCTIONALITY (moved from youthlist) ==================== //
        
        // Open official list modal
        function openOfficialListModal() {
            const button = document.getElementById('downloadOfficialListBtn');
            if (!button) return;
            const originalHTML = button.innerHTML;
            
            button.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Loading Official List...';
            button.disabled = true;
            setTimeout(() => {
                const modal = document.getElementById('officialListModal');
                if (!modal) {
                    console.error('Official list modal not found');
                    button.innerHTML = originalHTML;
                    button.disabled = false;
                    return;
                }
                modal.classList.remove('hidden');
                modal.style.display = 'flex';
                loadOfficialList();
                setTimeout(() => { button.innerHTML = originalHTML; button.disabled = false; }, 500);
            }, 100);
        }
        
        // Close official list modal
        function closeOfficialListModal() {
            const modal = document.getElementById('officialListModal');
            if (!modal) return;
            modal.classList.add('hidden');
            modal.style.display = 'none';
        }
        
        // Helper to format names as: First Middle Last
        function formatFullNameFromUser(user) {
            if (!user) return '';
            const parts = [];
            if (user.first_name) parts.push(String(user.first_name).trim());
            if (user.middle_name) parts.push(String(user.middle_name).trim());
            if (user.last_name) parts.push(String(user.last_name).trim());
            return parts.join(' ').replace(/\s+/g, ' ').trim();
        }
        
        // Load official list (from officers table on this page)
        function loadOfficialList() {
            const loading = document.getElementById('officialListLoading');
            const content = document.getElementById('officialListContent');
            // Show loader immediately
            if (loading) loading.classList.remove('hidden');
            if (content) content.classList.add('hidden');

            let officials = [];
            let secretaryName = '';
            let presidentName = '';

            try {
                <?php if (!empty($ped_officers)): ?>
                const pedOfficersRaw = <?= json_encode($ped_officers) ?>;
                <?php else: ?>
                const pedOfficersRaw = [];
                <?php endif; ?>

                let pedOfficersList = [];
                if (Array.isArray(pedOfficersRaw)) {
                    pedOfficersList = pedOfficersRaw;
                } else if (pedOfficersRaw && typeof pedOfficersRaw === 'object') {
                    pedOfficersList = Object.values(pedOfficersRaw);
                }

                const positionLabels = {
                    1: 'President',
                    2: 'Vice President',
                    3: 'Secretary',
                    4: 'Treasurer',
                    5: 'Auditor',
                    6: 'Public Information Officer',
                    7: 'Sergeant at Arms'
                };

                const formatBirthday = (value) => {
                    if (!value) return 'N/A';
                    const date = new Date(value);
                    if (Number.isNaN(date.getTime()) || date.getFullYear() < 1900) return 'N/A';
                    return date.toLocaleDateString('en-US', { month: '2-digit', day: '2-digit', year: 'numeric' });
                };

                const resolveSex = (value) => {
                    const normalized = String(value ?? '').trim().toLowerCase();
                    if (normalized === '1' || normalized === 'male') return 'Male';
                    if (normalized === '2' || normalized === 'female') return 'Female';
                    return '';
                };

                const formatDisplayName = (user) => {
                    const last = (user.last_name ?? '').toString().trim();
                    const first = (user.first_name ?? '').toString().trim();
                    const middle = (user.middle_name ?? '').toString().trim();
                    let composed = '';
                    if (last) composed += last;
                    if (first) composed += (composed ? ', ' : '') + first;
                    if (middle) composed += ' ' + middle;
                    return composed || formatFullNameFromUser(user);
                };

                pedOfficersList.forEach((user) => {
                    if (!user) return;
                    const status = parseInt(user.status ?? 0, 10);
                    if (status !== 2) return; // only accepted officials

                    const pedPos = parseInt(user.ped_position ?? 0, 10);
                    if (Number.isNaN(pedPos) || pedPos < 1 || pedPos > 7) {
                        return; // only official positions 1-7
                    }

                    const position = positionLabels[pedPos] ?? 'Member';
                    if (pedPos === 1) {
                        presidentName = formatFullNameFromUser(user);
                    } else if (pedPos === 3) {
                        secretaryName = formatFullNameFromUser(user);
                    }

                    const officialRecord = {
                        userId: (user.user_id ?? '').toString().trim(),
                        barangay: (user.barangay_name ?? user.barangay ?? '').toString().trim(),
                        name: formatDisplayName(user),
                        age: user.age !== undefined && user.age !== null ? String(user.age) : '',
                        birthday: formatBirthday(user.birthdate ?? user.birth_date ?? null),
                        sex: resolveSex(user.sex),
                        position
                    };

                    if (!officialRecord.age) {
                        const rawBirth = user.birthdate ?? user.birth_date ?? null;
                        const birthDate = rawBirth ? new Date(rawBirth) : null;
                        if (birthDate && !Number.isNaN(birthDate.getTime())) {
                            const today = new Date();
                            let computedAge = today.getFullYear() - birthDate.getFullYear();
                            const monthDiff = today.getMonth() - birthDate.getMonth();
                            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                                computedAge--;
                            }
                            if (computedAge >= 0 && computedAge <= 120) {
                                officialRecord.age = String(computedAge);
                            }
                        }
                    }

                    if (!officialRecord.sex) {
                        officialRecord.sex = 'N/A';
                    }
                    if (!officialRecord.barangay) {
                        officialRecord.barangay = 'N/A';
                    }
                    if (!officialRecord.age) {
                        officialRecord.age = 'N/A';
                    }

                    if (officialRecord.userId) {
                        officials.push(officialRecord);
                    }
                });

                // Store signature names globally for PDF generation
                window.pederasyonSecretary = secretaryName;
                window.pederasyonPresident = presidentName;

                // Sort officials by position hierarchy (highest position first)
                officials.sort((a, b) => {
                    const getPositionRank = (position) => {
                        if (position.includes('President') && !position.includes('Vice')) return 1; // President
                        if (position.includes('Vice President')) return 2; // Vice President
                        if (position.includes('Secretary')) return 3; // Secretary
                        if (position.includes('Treasurer')) return 4; // Treasurer
                        if (position.includes('Auditor')) return 5; // Auditor
                        if (position.includes('Public Information Officer')) return 6; // PIO
                        if (position.includes('Sergeant at Arms')) return 7; // Sergeant at Arms
                        return 8; // Member or others
                    };
                    return getPositionRank(a.position) - getPositionRank(b.position);
                });

                // Update UI
                const noOfficialsEl = document.getElementById('noOfficials');
                const signatureEl = document.getElementById('signatureSection');
                const countEl = document.getElementById('officialListCount');
                if (officials.length > 0) {
                    displayOfficialList(officials);
                    if (noOfficialsEl) noOfficialsEl.classList.add('hidden');
                    if (signatureEl) signatureEl.classList.remove('hidden');
                } else {
                    if (noOfficialsEl) noOfficialsEl.classList.remove('hidden');
                    if (signatureEl) signatureEl.classList.add('hidden');
                    document.getElementById('officialListTableBody').innerHTML = '';
                }
                if (countEl) countEl.textContent = `Total Officials: ${officials.length}`;
            } catch (err) {
                console.error('Failed to load official list:', err);
                console.error('Error details:', {
                    message: err.message,
                    stack: err.stack,
                    officialsCount: officials.length
                });
                showNotification('Failed to load official list. Please check console for details.', 'error');
                // Ensure the table body is cleared on error
                const tbody = document.getElementById('officialListTableBody');
                if (tbody) tbody.innerHTML = '';
                const noOfficialsEl = document.getElementById('noOfficials');
                if (noOfficialsEl) noOfficialsEl.classList.remove('hidden');
                const signatureEl = document.getElementById('signatureSection');
                if (signatureEl) signatureEl.classList.add('hidden');
                const countEl = document.getElementById('officialListCount');
                if (countEl) countEl.textContent = 'Total Officials: 0';
            } finally {
                // Always reveal content and hide loader
                if (loading) loading.classList.add('hidden');
                if (content) content.classList.remove('hidden');
            }
        }
        
        // Display officials in table
        function displayOfficialList(officials) {
            const tbody = document.getElementById('officialListTableBody');
            tbody.innerHTML = '';
            const formatCell = (value) => {
                if (value === undefined || value === null) return 'N/A';
                const trimmed = value.toString().trim();
                return trimmed !== '' ? trimmed : 'N/A';
            };
            officials.forEach((official, index) => {
                const row = document.createElement('tr');
                row.className = index % 2 === 0 ? 'bg-white' : 'bg-gray-50';
                row.innerHTML = `
                    <td class="border border-gray-300 text-center px-2 py-2 text-gray-900 text-xs">${formatCell(official.userId)}</td>
                    <td class="border border-gray-300 text-center px-2 py-2 text-gray-900 text-xs">${formatCell(official.barangay)}</td>
                    <td class="border border-gray-300 text-center px-2 py-2 text-gray-900 text-xs">${formatCell(official.name)}</td>
                    <td class="border border-gray-300 text-center px-2 py-2 text-gray-900 text-xs">${formatCell(official.age)}</td>
                    <td class="border border-gray-300 text-center px-2 py-2 text-gray-900 text-xs">${formatCell(official.birthday)}</td>
                    <td class="border border-gray-300 text-center px-2 py-2 text-gray-900 text-xs">${formatCell(official.sex)}</td>
                    <td class="border border-gray-300 text-center px-2 py-2 text-gray-900 text-xs">${formatCell(official.position)}</td>`;
                tbody.appendChild(row);
            });
            loadBarangayLogo();
            if (window.pederasyonSecretary) document.getElementById('secretarySignature').textContent = window.pederasyonSecretary;
            if (window.pederasyonPresident) document.getElementById('presidentSignature').textContent = window.pederasyonPresident;
        }
        
        // Load logos for header
        function loadBarangayLogo() {
            fetch('<?= base_url('documents/logos') ?>')
                .then(r => r.json())
                .then(data => {
                    if (!data.success) return;
                    const logos = data.data || {};
                    const pDiv = document.getElementById('official-list-pederasyon-logo');
                    if (logos.pederasyon && pDiv) {
                        pDiv.innerHTML = `<img src="<?= base_url() ?>${logos.pederasyon.file_path}" alt="Pederasyon Logo" class="w-full h-full object-contain">`;
                    }
                    const iDiv = document.getElementById('official-list-iriga-logo');
                    if (logos.iriga_city && iDiv) {
                        iDiv.innerHTML = `<img src="<?= base_url() ?>${logos.iriga_city.file_path}" alt="Iriga City Logo" class="w-full h-full object-contain">`;
                    }
                })
                .catch(() => {});
        }
        
        // Print official list
        function printOfficialList() {
            const button = event.target;
            const originalHTML = button.innerHTML;
            button.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Preparing Print...';
            button.disabled = true;
            const officialsCount = document.getElementById('officialListTableBody').children.length;
            if (officialsCount === 0) {
                showNotification('No officials to print.', 'error');
                button.innerHTML = originalHTML;
                button.disabled = false;
                return;
            }
            const printContent = document.getElementById('downloadOfficialContent').cloneNode(true);
            const originalContent = document.body.innerHTML;
            const styles = `
                <style>
                    @page { size: A4 landscape; margin: 0.5in; }
                    body { font-family: Arial, sans-serif !important; margin:0; padding:20px; -webkit-print-color-adjust: exact; color-adjust: exact; }
                    table { width:100% !important; border-collapse: collapse !important; font-size: 8px !important; }
                    th, td { border: 1px solid #d1d5db !important; padding: 4px !important; text-align: center !important; font-size: 8px !important; }
                    th { background-color: #f9fafb !important; font-weight: 600 !important; }
                    tbody tr:nth-child(even) { background-color: #f9fafb !important; }
                    tbody tr:nth-child(odd) { background-color: #ffffff !important; }
                    .hidden { display: none !important; }
                </style>`;
            document.body.innerHTML = styles + printContent.outerHTML;
            window.print();
            document.body.innerHTML = originalContent;
            button.innerHTML = originalHTML;
            button.disabled = false;
            setTimeout(() => { location.reload(); }, 100);
        }
        
        // Download PDF (client-side)
        async function downloadOfficialListPDF() {
            // Per-button loading UI (no info toast)
            const button = event.target;
            const originalHTML = button.innerHTML;
            button.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Generating PDF...';
            button.disabled = true;

            try {
                // Check if there are officials to download
                const officialsCount = document.getElementById('officialListTableBody').children.length;
                if (officialsCount === 0) {
                    showNotification('No officials to download.', 'error');
                    button.innerHTML = originalHTML;
                    button.disabled = false;
                    return;
                }

                // Fetch logos with proper error handling
                const logosResp = await fetch('<?= base_url('documents/logos') ?>');
                const logosJson = (logosResp.ok ? await logosResp.json() : { success: false, data: {} });
                const logos = (logosJson && logosJson.success && logosJson.data) ? logosJson.data : {};

                const pederasyonLogoPath = (logos.pederasyon?.file_path) || '';
                const irigaLogoPath = (logos.iriga_city?.file_path) || '';
                const pederasyonLogoUrl = pederasyonLogoPath ? '<?= base_url() ?>' + pederasyonLogoPath : '';
                const irigaLogoUrl = irigaLogoPath ? '<?= base_url() ?>' + irigaLogoPath : '';

                // Helper to convert image URL to data URL
                const imageUrlToDataUrl = (url) => {
                    return new Promise((resolve) => {
                        if (!url) {
                            resolve(null);
                            return;
                        }
                        const img = new Image();
                        img.crossOrigin = 'anonymous';
                        img.onload = function() {
                            const canvas = document.createElement('canvas');
                            canvas.width = this.naturalWidth;
                            canvas.height = this.naturalHeight;
                            const ctx = canvas.getContext('2d');
                            ctx.drawImage(this, 0, 0);
                            try {
                                resolve(canvas.toDataURL('image/png'));
                            } catch (e) {
                                console.error('Canvas toDataURL failed:', e);
                                resolve(null);
                            }
                        };
                        img.onerror = () => resolve(null);
                        img.src = url;
                    });
                };

                // Load both logos as data URLs
                const [pederasyonLogoDataUrl, irigaLogoDataUrl] = await Promise.all([
                    imageUrlToDataUrl(pederasyonLogoUrl),
                    imageUrlToDataUrl(irigaLogoUrl)
                ]);

                const { jsPDF } = window.jspdf;
                const doc = new jsPDF('l', 'mm', 'a4');
                const pageWidth = doc.internal.pageSize.getWidth();
                const pageHeight = doc.internal.pageSize.getHeight();
                const pageCenter = pageWidth / 2;
                const headerTop = 20;
                const tableWidth = 225;
                const contentTop = headerTop + 48;
                const bottomMargin = 25;
                const horizontalMargin = (pageWidth - tableWidth) / 2;

                const getImgFmt = (dataUrl) => {
                    if (!dataUrl) return 'PNG';
                    if (dataUrl.includes('image/jpeg') || dataUrl.includes('image/jpg')) return 'JPEG';
                    if (dataUrl.includes('image/png')) return 'PNG';
                    return 'PNG';
                };

                const drawHeader = () => {
                    const logoSize = 20;
                    const leftLogoX = 35;
                    const rightLogoX = pageWidth - 35 - logoSize;
                    if (pederasyonLogoDataUrl) {
                        doc.addImage(pederasyonLogoDataUrl, getImgFmt(pederasyonLogoDataUrl), leftLogoX, headerTop, logoSize, logoSize, undefined, 'FAST');
                    }
                    if (irigaLogoDataUrl) {
                        doc.addImage(irigaLogoDataUrl, getImgFmt(irigaLogoDataUrl), rightLogoX, headerTop, logoSize, logoSize, undefined, 'FAST');
                    }

                    doc.setFontSize(14);
                    doc.setFont(undefined, 'bold');
                    doc.text('REPUBLIC OF THE PHILIPPINES', pageCenter, headerTop + 6, { align: 'center' });
                    doc.text('PROVINCE OF CAMARINES SUR', pageCenter, headerTop + 12, { align: 'center' });
                    doc.text('CITY OF IRIGA', pageCenter, headerTop + 18, { align: 'center' });
                    doc.setFontSize(10);
                    doc.setFont(undefined, 'normal');
                    doc.text('PANLUNGSOD NA PEDERASYON NG MGA', pageCenter, headerTop + 23, { align: 'center' });
                    doc.text('SANGGUNIANG KABATAAN NG IRIGA', pageCenter, headerTop + 28, { align: 'center' });

                    doc.setLineWidth(0.3);
                    doc.line(30, headerTop + 32, pageWidth - 30, headerTop + 32);

                    doc.setFontSize(12);
                    doc.setFont(undefined, 'bold');
                    doc.text('PANLUNGSOD NA PEDERASYON NG MGA KABATAAN', pageCenter, headerTop + 40, { align: 'center' });
                    doc.setFontSize(10);
                    doc.text('OFFICIAL LIST', pageCenter, headerTop + 45, { align: 'center' });
                };

                const headers = ['User ID', 'Barangay', 'Name', 'Age', 'Birthday', 'Sex', 'Position'];
                const tableData = [];
                $('#officialListTableBody tr').each(function() {
                    const row = [];
                    $(this).find('td').each(function() {
                        row.push($(this).text().trim());
                    });
                    if (row.length > 0) {
                        tableData.push(row);
                    }
                });

                doc.autoTable({
                    head: [headers],
                    body: tableData,
                    startY: contentTop,
                    margin: { left: horizontalMargin, top: contentTop, right: horizontalMargin, bottom: bottomMargin },
                    styles: {
                        fontSize: 8,
                        cellPadding: 1,
                        halign: 'center',
                        textColor: [0, 0, 0],
                        lineColor: [0, 0, 0],
                        lineWidth: 0.2
                    },
                    headStyles: {
                        fillColor: [255, 255, 255],
                        textColor: [0, 0, 0],
                        halign: 'center',
                        fontStyle: 'bold',
                        cellPadding: 1,
                        lineColor: [0, 0, 0],
                        lineWidth: 0.2
                    },
                    bodyStyles: {
                        lineColor: [0, 0, 0],
                        lineWidth: 0.2,
                        fillColor: [255, 255, 255]
                    },
                    columnStyles: {
                        0: { cellWidth: 25 },
                        1: { cellWidth: 35 },
                        2: { cellWidth: 60 },
                        3: { cellWidth: 20 },
                        4: { cellWidth: 25 },
                        5: { cellWidth: 20 },
                        6: { cellWidth: 40 }
                    },
                    tableWidth: tableWidth,
                    theme: 'grid',
                    didDrawPage: () => {
                        drawHeader();
                    }
                });

                const totalPages = doc.internal.getNumberOfPages();
                doc.setPage(totalPages);

                let finalY = doc.lastAutoTable && doc.lastAutoTable.finalY ? doc.lastAutoTable.finalY + 20 : contentTop;
                const signatureHeight = 40;
                if (finalY + signatureHeight > pageHeight - bottomMargin) {
                    doc.addPage();
                    drawHeader();
                    finalY = contentTop;
                }

                const signatureSpacing = 80;
                const leftSignatureX = pageCenter - signatureSpacing;
                const rightSignatureX = pageCenter + signatureSpacing - 40;

                doc.setFont(undefined, 'normal');
                doc.setFontSize(10);

                const secretaryName = window.pederasyonSecretary || '________________';
                doc.text('Prepared by:', leftSignatureX, finalY, { align: 'center' });
                doc.text('________________', leftSignatureX, finalY + 20, { align: 'center' });
                doc.setFont(undefined, 'bold');
                doc.text(secretaryName, leftSignatureX, finalY + 25, { align: 'center' });
                doc.setFont(undefined, 'normal');
                doc.text('Pederasyon Secretary', leftSignatureX, finalY + 30, { align: 'center' });

                const presidentName = window.pederasyonPresident || '________________';
                doc.text('Approved by:', rightSignatureX, finalY, { align: 'center' });
                doc.text('________________', rightSignatureX, finalY + 20, { align: 'center' });
                doc.setFont(undefined, 'bold');
                doc.text(presidentName, rightSignatureX, finalY + 25, { align: 'center' });
                doc.setFont(undefined, 'normal');
                doc.text('Pederasyon President', rightSignatureX, finalY + 30, { align: 'center' });

                // Save the PDF
                doc.save('PEDERASYON_Official_List.pdf');

                // Show success notification
                showNotification('Official List PDF document generated and downloaded successfully!', 'success');

                // Reset button state
                button.innerHTML = originalHTML;
                button.disabled = false;

            } catch (error) {
                console.error('PDF generation error:', error);
                showNotification('Error generating PDF: ' + error.message, 'error');

                // Reset button state
                button.innerHTML = originalHTML;
                button.disabled = false;
            }
        }
        
        // Download Word (server-generated)
        function downloadOfficialListWord() {
            const button = event.target; const originalHTML = button.innerHTML;
            button.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Generating Word...'; button.disabled = true;
            const officialsCount = document.getElementById('officialListTableBody').children.length;
            if (officialsCount === 0) {
                showNotification('No officials to download.', 'error');
                button.innerHTML = originalHTML;
                button.disabled = false;
                return;
            }
            fetch('<?= base_url('pederasyon/generate-official-list-word') ?>', { method:'POST', headers:{ 'Content-Type':'application/json', 'X-Requested-With':'XMLHttpRequest' }, body: JSON.stringify({}) })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    const contentDisposition = response.headers.get('Content-Disposition');
                    let fileName = 'PEDERASYON_Official_List.docx';
                    if (contentDisposition) {
                        const matches = /filename="([^"]+)"/.exec(contentDisposition);
                        if (matches && matches[1]) { fileName = matches[1]; }
                    }
                    return response.blob().then(blob => ({ blob, fileName }));
                })
                .then(({ blob, fileName }) => {
                    const url = window.URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.href = url; link.download = fileName; link.style.display = 'none';
                    document.body.appendChild(link); link.click(); document.body.removeChild(link);
                    window.URL.revokeObjectURL(url);
                    showNotification('Official List Word document generated and downloaded successfully!', 'success');
                })
                .catch(err => { showNotification('Error generating Word document: ' + err.message, 'error'); })
                .finally(() => { button.innerHTML = originalHTML; button.disabled = false; });
        }
        
        // Download Excel (server-generated)
        function downloadOfficialListExcel() {
            const button = event.target; const originalHTML = button.innerHTML;
            button.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Generating Excel...'; button.disabled = true;
            const officialsCount = document.getElementById('officialListTableBody').children.length;
            if (officialsCount === 0) {
                showNotification('No officials to download.', 'error');
                button.innerHTML = originalHTML;
                button.disabled = false;
                return;
            }
            fetch('<?= base_url('pederasyon/generate-official-list-excel') ?>', { method:'POST', headers:{ 'Content-Type':'application/json', 'X-Requested-With':'XMLHttpRequest' }, body: JSON.stringify({}) })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    const contentDisposition = response.headers.get('Content-Disposition');
                    let fileName = 'PEDERASYON_Official_List.xlsx';
                    if (contentDisposition) {
                        const matches = /filename="([^"]+)"/.exec(contentDisposition);
                        if (matches && matches[1]) { fileName = matches[1]; }
                    }
                    return response.blob().then(blob => ({ blob, fileName }));
                })
                .then(({ blob, fileName }) => {
                    const url = window.URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.href = url; link.download = fileName; link.style.display = 'none';
                    document.body.appendChild(link); link.click(); document.body.removeChild(link);
                    window.URL.revokeObjectURL(url);
                    showNotification('Official List Excel document generated and downloaded successfully!', 'success');
                })
                .catch(err => { showNotification('Error generating Excel document: ' + err.message, 'error'); })
                .finally(() => { button.innerHTML = originalHTML; button.disabled = false; });
        }
        
        // ==================== EVENT LISTENERS - Initialize after DOM ready ==================== //
        
        // Wait for DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Check on page load if there are no officers with credentials
            <?php if (isset($has_officers_with_credentials) && !$has_officers_with_credentials): ?>
                // No officers with credentials - show warning modal after page loads
                setTimeout(function() {
                    const officers = <?= json_encode($officers_with_credentials ?? []) ?>;
                    showOfficerWarningModal(officers);
                }, 500); // Small delay to ensure page is fully loaded
            <?php endif; ?>
            
            // Official List button
            const officialListBtn = document.getElementById('downloadOfficialListBtn');
            if (officialListBtn) {
                officialListBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    openOfficialListModal();
                });
            }
            
            // Click outside to close modals
            $('#officialListModal').on('click', function(e) { 
                if (e.target === this) { 
                    closeOfficialListModal(); 
                } 
            });
            
            // Credentials button
            const credentialsBtn = document.getElementById('downloadCredentialsBtn');
            if (credentialsBtn) {
                credentialsBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    openPedCredentialsPreviewModal();
                });
            }
            
            // Click outside to close credentials modal
            $('#pedCredentialsPreviewModal').on('click', function(e) { 
                if (e.target === this) { 
                    closePedCredentialsPreviewModal(); 
                } 
            });
        });
    </script>

    <!-- Credentials Preview Modal -->
    <div id="pedCredentialsPreviewModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 items-center justify-center p-4" style="display: none;">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-7xl max-h-[90vh] relative overflow-hidden flex flex-col">
            <!-- Modal Header -->
            <div class="bg-white border-b border-gray-200 px-6 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Pederasyon Officers Credentials</h3>
                        <p class="text-sm text-gray-600 mt-1">K-NECT System Pederasyon Officers Login Credentials</p>
                    </div>
                    <button onclick="closePedCredentialsPreviewModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none transition-colors p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Content -->
            <div class="flex-1 overflow-y-auto p-6">
                <div id="pedCredentialsLoading" class="text-center py-12">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    <p class="mt-3 text-gray-600 font-medium">Loading credentials...</p>
                </div>

                <div id="pedCredentialsContent" class="hidden">
                    <!-- Document Header - Hidden in preview, shown in print -->
                    <div class="bg-white hidden print:block" style="font-family: Arial, sans-serif;">
                        <!-- Header Section with Logos -->
                        <div class="text-center mb-6 print:mb-4" style="font-family: Arial, sans-serif;">
                            <div class="flex items-center justify-center mb-4">
                                <!-- Pederasyon Logo (Left) -->
                                <div class="flex-shrink-0 mr-8">
                                    <div id="ped-credentials-pederasyon-logo" class="w-16 h-16 rounded flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                
                                <!-- Center Text -->
                                <div class="text-center" style="font-family: Arial, sans-serif;">
                                    <h2 style="font-family: Arial, sans-serif; font-size: 12pt; font-weight: bold; color: black; margin: 0; line-height: 1.2;">REPUBLIC OF THE PHILIPPINES</h2>
                                    <h3 style="font-family: Arial, sans-serif; font-size: 12pt; font-weight: bold; color: black; margin: 0; line-height: 1.2;">PROVINCE OF CAMARINES SUR</h3>
                                    <h3 style="font-family: Arial, sans-serif; font-size: 12pt; font-weight: bold; color: black; margin: 0; line-height: 1.2;">CITY OF IRIGA</h3>
                                    <h4 style="font-family: Arial, sans-serif; font-size: 9pt; font-weight: normal; color: black; margin: 0; line-height: 1.2;">PANLUNGSOD NA PEDERASYON NG MGA</h4>
                                    <h4 style="font-family: Arial, sans-serif; font-size: 9pt; font-weight: normal; color: black; margin: 0; line-height: 1.2;">SANGGUNIANG KABATAAN NG IRIGA</h4>
                                </div>
                                
                                <!-- Iriga City Logo (Right) -->
                                <div class="flex-shrink-0 ml-8">
                                    <div id="ped-credentials-iriga-logo" class="w-16 h-16 rounded flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="border-gray-300 mb-4">
                            
                            <h2 style="font-family: Arial, sans-serif; font-size: 12pt; font-weight: bold; color: black; margin: 16px 0 24px 0;">PANLUNGSOD NA PEDERASYON NG MGA KABATAAN</h2>
                            <h3 style="font-family: Arial, sans-serif; font-size: 10pt; font-weight: bold; color: black; margin: 8px 0 16px 0;">PEDERASYON OFFICERS CREDENTIALS</h3>
                        </div>
                    </div>

                    <!-- Credentials Tables Container -->
                    <div id="pedCredentialsTablesContainer" class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <!-- Pederasyon Officers Credentials Table -->
                        <div id="pedCredentialsSection" class="credentials-section p-6">
                            <div class="mb-4">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    <h4 class="text-lg font-semibold text-gray-900">Pederasyon Officers Login Credentials</h4> 
                                    <span class="text-sm font-medium text-blue-900">
                                        (<span id="pedCredentialsCount">0</span>)
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mb-3">Login information for Pederasyon Officers (SK Chairpersons and Pederasyon Officers with positions)</p>
                                
                                <!-- Important Notes -->
                                <div class="mt-3 p-3 bg-blue-50 border-l-4 border-blue-400 rounded-r-lg">
                                    <p class="text-xs text-blue-800 mb-1"><strong>Password Display Guide:</strong></p>
                                    <ul class="text-xs text-blue-700 space-y-0.5 ml-4">
                                        <li>• <strong>Plain text passwords</strong> = Temporary passwords (not yet changed by user)</li>
                                        <li>• <strong>******** (asterisks)</strong> = Password has been changed by user (hashed for security)</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="overflow-x-auto">
                                <div class="border-2 border-gray-400 rounded-lg overflow-hidden">
                                    <table class="w-full border-collapse border border-gray-300 rounded-lg overflow-hidden">
                                        <thead>
                                            <tr class="bg-gray-50">
                                                <th class="border border-gray-300 text-center font-bold py-3 px-3 text-gray-700 text-xs">User ID</th>
                                                <th class="border border-gray-300 text-center font-bold py-3 px-3 text-gray-700 text-xs">Full Name</th>
                                                <th class="border border-gray-300 text-center font-bold py-3 px-3 text-gray-700 text-xs">Barangay</th>
                                                <th class="border border-gray-300 text-center font-bold py-3 px-3 text-gray-700 text-xs">Position</th>
                                                <th class="border border-gray-300 text-center font-bold py-3 px-3 text-gray-700 text-xs">Pederasyon Username</th>
                                                <th class="border border-gray-300 text-center font-bold py-3 px-3 text-gray-700 text-xs">Pederasyon Password</th>
                                            </tr>
                                        </thead>
                                        <tbody id="pedCredentialsTableBody">
                                            <!-- Pederasyon credentials data will be populated here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="pedNoCredentials" class="text-center py-12 hidden">
                        <div class="w-16 h-16 mx-auto mb-4 bg-yellow-100 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Pederasyon Officers Found</h3>
                        <p class="text-sm text-gray-600 mb-2">No SK Chairpersons or Pederasyon Officers found.</p>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 border-t border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div id="pedCredentialsTotalCount" class="text-sm font-medium text-gray-700"></div>
                    <div class="flex gap-3">
                        <button onclick="closePedCredentialsPreviewModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors duration-200">
                            Close
                        </button>
                        <button onclick="downloadPedCredentialsPDF()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors duration-200 shadow-sm">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            PDF
                        </button>
                        <button onclick="downloadPedCredentialsWord()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-200 shadow-sm">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Word
                        </button>
                        <button onclick="downloadPedCredentialsExcel()" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors duration-200 shadow-sm">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Officer Warning Modal - No Officers with Credentials -->
    <div id="officerWarningModal" class="fixed inset-0 z-[99999] hidden items-center justify-center p-4" style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl relative overflow-hidden flex flex-col">
            <!-- Modal Header -->
            <div class="bg-red-50 border-b border-red-200 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-red-900">System Management Warning</h3>
                        <p class="text-sm text-red-700 mt-1">Action Required: No Officers with Login Credentials</p>
                    </div>
                </div>
            </div>

            <!-- Modal Content -->
            <div class="px-6 py-6 overflow-y-auto max-h-[60vh]">
                <div class="space-y-6">
                    <!-- Warning Message -->
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <p class="text-red-800 text-sm leading-relaxed">
                            <strong class="font-semibold">⚠️ Critical Notice:</strong> There are currently no Pederasyon officers assigned to key positions with login credentials. 
                            At least one officer must be assigned to one of the following positions to manage the system:
                        </p>
                    </div>

                    <!-- Required Positions List -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="font-semibold text-blue-900 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Positions with Login Credentials:
                        </h4>
                        <ul class="space-y-2 text-sm text-blue-800">
                            <li class="flex items-center gap-2">
                                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                President
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                Vice President
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                Secretary
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                Treasurer
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                Auditor
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                Public Information Officer
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                Sergeant at Arms
                            </li>
                        </ul>
                        <p class="text-xs text-blue-700 mt-3 italic">
                            Note: Members do not have login credentials.
                        </p>
                    </div>

                    <!-- Current Officers Section (conditionally shown) -->
                    <div id="currentOfficersSection" class="hidden">
                        <h4 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Current Officers with Credentials:
                        </h4>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg overflow-hidden">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-100 border-b border-gray-200">
                                    <tr>
                                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Name</th>
                                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Position</th>
                                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Username</th>
                                    </tr>
                                </thead>
                                <tbody id="currentOfficersTableBody" class="divide-y divide-gray-200">
                                    <!-- Officers will be populated here -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Action Required -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <h4 class="font-semibold text-yellow-900 mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            What You Need to Do:
                        </h4>
                        <ol class="list-decimal list-inside space-y-1 text-sm text-yellow-800">
                            <li>Assign at least one officer to a key position (listed above)</li>
                            <li>Download the official credentials for the assigned officer(s)</li>
                            <li>Provide the credentials to the officer(s) so they can log in and manage the system</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 border-t border-gray-200 px-6 py-4">
                <div class="flex flex-col sm:flex-row justify-end gap-3">
                    <button onclick="downloadCredentialsFromWarning()" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download Credentials
                    </button>
                    <button onclick="closeOfficerWarningModal()" class="px-4 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                        I Understand
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Official List Modal - Unified Design -->
    <div id="officialListModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-7xl max-h-[90vh] relative overflow-hidden flex flex-col">
            <!-- Modal Header -->
            <div class="bg-white border-b border-gray-200 px-6 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">SK Pederasyon Official List</h3>
                        <p class="text-sm text-gray-600 mt-1">Panlungsod na Pederasyon ng mga Sangguniang Kabataan Officials</p>
                    </div>
                    <button onclick="closeOfficialListModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none transition-colors p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Content -->
            <div class="flex-1 overflow-y-auto p-6">
                <div id="officialListLoading" class="text-center py-12">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    <p class="mt-3 text-gray-600 font-medium">Loading official list...</p>
                </div>
                
                <div id="officialListContent" class="hidden">
                    <!-- Document Preview Container -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <!-- Download Format Content -->
                        <div id="downloadOfficialContent" class="bg-white">
                            <!-- Header Section -->
                            <div class="text-center mb-6 print:mb-4" style="font-family: Arial, sans-serif;">
                                <!-- Document Header with Logos -->
                                <div class="flex items-center justify-center mb-4">
                                    <!-- Pederasyon Logo (Left) -->
                                    <div class="flex-shrink-0 mr-8">
                                        <div id="official-list-pederasyon-logo" class="w-16 h-16 rounded flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    
                                    <!-- Center Text -->
                                    <div class="text-center">
                                        <h2 style="font-size: 12pt; font-weight: bold; color: black; margin: 0; line-height: 1.2;">REPUBLIC OF THE PHILIPPINES</h2>
                                        <h3 style="font-size: 12pt; font-weight: bold; color: black; margin: 0; line-height: 1.2;">PROVINCE OF CAMARINES SUR</h3>
                                        <h3 style="font-size: 12pt; font-weight: bold; color: black; margin: 0; line-height: 1.2;">CITY OF IRIGA</h3>
                                        <h4 style="font-size: 9pt; font-weight: normal; color: black; margin: 0; line-height: 1.2;">PANLUNGSOD NA PEDERASYON NG MGA</h4>
                                        <h4 style="font-size: 9pt; font-weight: normal; color: black; margin: 0; line-height: 1.2;">SANGGUNIANG KABATAAN NG IRIGA</h4>
                                    </div>
                                    
                                    <!-- Iriga City Logo (Right) -->
                                    <div class="flex-shrink-0 ml-8">
                                        <div id="official-list-iriga-logo" class="w-16 h-16 rounded flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr class="border-gray-300 mb-4">
                                
                                <h2 style="font-size: 12pt; font-weight: bold; color: black; margin: 16px 0 24px 0; font-family: Arial, sans-serif;">PANLUNGSOD NA PEDERASYON NG MGA KABATAAN</h2>
                                <h3 style="font-size: 10pt; font-weight: bold; color: black; margin: 8px 0 16px 0; font-family: Arial, sans-serif;">OFFICIAL LIST</h3>
                            </div>

                            <!-- Table -->
                            <div class="overflow-x-auto">
                                <div class="border-2 border-gray-400 rounded-lg overflow-hidden">
                                    <table class="w-full border border-gray-300 rounded-lg overflow-hidden">
                                        <thead>
                                            <tr class="bg-gray-50">
                                                <th class="border border-gray-300 text-center font-bold py-3 px-3 text-gray-700 text-xs">User ID</th>
                                                <th class="border border-gray-300 text-center font-bold py-3 px-3 text-gray-700 text-xs">Barangay</th>
                                                <th class="border border-gray-300 text-center font-bold py-3 px-3 text-gray-700 text-xs">Name</th>
                                                <th class="border border-gray-300 text-center font-bold py-3 px-3 text-gray-700 text-xs">Age</th>
                                                <th class="border border-gray-300 text-center font-bold py-3 px-3 text-gray-700 text-xs">Birthday</th>
                                                <th class="border border-gray-300 text-center font-bold py-3 px-3 text-gray-700 text-xs">Sex</th>
                                                <th class="border border-gray-300 text-center font-bold py-3 px-3 text-gray-700 text-xs">Position</th>
                                            </tr>
                                        </thead>
                                        <tbody id="officialListTableBody"></tbody>
                                    </table>
                                </div>
                                <!-- No officials message (shown when list is empty) -->
                                <div id="noOfficials" class="text-center py-12 hidden">
                                    <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                                        <svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Officials Found</h3>
                                    <p class="text-sm text-gray-500">No officials are currently registered in the system.</p>
                                </div>

                                <!-- Signature Section -->
                                <div id="signatureSection" class="mt-8 print:mt-6" style="font-family: Arial, sans-serif;">
                            <!-- Signature Section -->
                            <div class="mt-8 print:mt-6" style="font-family: Arial, sans-serif;">
                                <div class="flex justify-center items-end">
                                    <div class="flex justify-between items-end" style="width: 80%; max-width: 600px;">
                                        <div class="text-center">
                                            <p style="font-size: 9pt; margin-bottom: 48px; color: black;">Prepared by:</p>
                                            <div class="border-b border-black w-48 mb-2"></div>
                                            <p id="secretarySignature" style="font-size: 9pt; font-weight: bold; color: black; margin: 0;">________________</p>
                                            <p style="font-size: 9pt; font-weight: bold; color: black; margin: 0;">Pederasyon Secretary</p>
                                        </div>
                                        <div class="text-center">
                                            <p style="font-size: 9pt; margin-bottom: 48px; color: black;">Approved by:</p>
                                            <div class="border-b border-black w-48 mb-2"></div>
                                            <p id="presidentSignature" style="font-size: 9pt; font-weight: bold; color: black; margin: 0;">________________</p>
                                            <p style="font-size: 9pt; font-weight: bold; color: black; margin: 0;">Pederasyon President</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 border-t border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div id="officialListCount" class="text-sm font-medium text-gray-700"></div>
                    <div class="flex gap-2">
                        <button onclick="closeOfficialListModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors duration-200">
                            Close
                        </button>
                        <button onclick="printOfficialList()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-200 shadow-sm">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Print
                        </button>
                        <button onclick="downloadOfficialListPDF()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors duration-200 shadow-sm">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            PDF
                        </button>
                        <button onclick="downloadOfficialListWord()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-200 shadow-sm">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Word
                        </button>
                        <button onclick="downloadOfficialListExcel()" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors duration-200 shadow-sm">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
