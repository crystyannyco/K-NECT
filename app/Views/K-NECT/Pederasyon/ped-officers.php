
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
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Prevent horizontal overflow issues */
        .dataTables_scrollHead,
        .dataTables_scrollBody,
        .dataTables_scrollFoot {
            overflow-x: auto !important;
        }

        /* Ensure table responsiveness */
        #myTable {
            width: 100% !important;
            table-layout: auto;
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
    </style>
        
        <!-- ===== MAIN CONTENT AREA ===== -->
        <div class="flex-1 flex flex-col min-h-0 ml-0 lg:ml-64 pt-16">
            <main class="flex-1 overflow-auto p-4 lg:p-6 bg-gray-50">
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
                    </div>
                </div>
                
                <!-- Filter Tabs and Barangay Selector -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <!-- Position Filter Tabs -->
                            <div class="flex flex-wrap gap-2">
                                <button class="status-tab active px-4 py-2 rounded-lg text-sm font-medium transition-all" data-position="all">
                                    All (<span id="countAll">0</span>)
                                </button>
                                <button class="status-tab px-4 py-2 rounded-lg text-sm font-medium transition-all" data-position="president">
                                    President (<span id="countPresident">0</span>)
                                </button>
                                <button class="status-tab px-4 py-2 rounded-lg text-sm font-medium transition-all" data-position="vicepresident">
                                    Vice President (<span id="countVicePresident">0</span>)
                                </button>
                                <button class="status-tab px-4 py-2 rounded-lg text-sm font-medium transition-all" data-position="secretary">
                                    Secretary (<span id="countSecretary">0</span>)
                                </button>
                                <button class="status-tab px-4 py-2 rounded-lg text-sm font-medium transition-all" data-position="treasurer">
                                    Treasurer (<span id="countTreasurer">0</span>)
                                </button>
                                <button class="status-tab px-4 py-2 rounded-lg text-sm font-medium transition-all" data-position="others">
                                    Others (<span id="countOthers">0</span>)
                                </button>
                            </div>
                            <!-- Barangay Filter -->
                            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                                <span class="text-sm font-medium text-gray-600">Barangay:</span>
                                <div class="flex gap-3">
                                    <select id="barangayFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">All Barangays</option>
                                        <!-- Barangay options will be populated dynamically -->
                                    </select>
                                    <button id="clearFilters" class="px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                                        Clear Filters
                                    </button>
                                </div>
                            </div>
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
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <?= esc($officer['barangay_name'] ?? ($officer['barangay'] ?? '')) ?>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($officer['last_name']) ?>, <?= esc($officer['first_name']) ?> <?= esc($officer['middle_name']) ?></td>
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
                                                        $pedPosition = isset($officer['ped_position']) ? (int)$officer['ped_position'] : 0;
                                                        switch($pedPosition) {
                                                            case 1:
                                                                echo 'SK Pederasyon President';
                                                                break;
                                                            case 2:
                                                                echo 'SK Pederasyon Vice President';
                                                                break;
                                                            case 3:
                                                                echo 'SK Pederasyon Secretary';
                                                                break;
                                                            case 4:
                                                                echo 'SK Pederasyon Treasurer';
                                                                break;
                                                            case 5:
                                                                echo 'SK Pederasyon Auditor';
                                                                break;
                                                            case 6:
                                                                echo 'SK Pederasyon Public Information Officer';
                                                                break;
                                                            case 7:
                                                                echo 'SK Pederasyon Sergeant at Arms';
                                                                break;
                                                            default:
                                                                echo 'SK Pederasyon Member';
                                                                break;
                                                        }
                                                    ?>
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
                                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Pederasyon Officers found</h3>
                                                    <p class="text-gray-500">There are no Pederasyon Officers in the database yet.</p>
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
                    <option value="NULL">SK Pederasyon Member</option>
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
                            <option value="NULL">SK Pederasyon Member</option>
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
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-[99999] p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
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
        
        // Store original counts globally
        let originalCounts = {
            all: 0,
            president: 0,
            vicepresident: 0,
            secretary: 0,
            treasurer: 0,
            others: 0
        };
        
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
                    { orderable: false, targets: 0 }
                ],
                order: [[1, 'asc']],
                scrollCollapse: true,
                scrollY: '500px',
                scrollX: true,
                paging: true,
                pageLength: 25,
                info: true,
                searching: true,
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
                let allCount = 0, presidentCount = 0, vicePresidentCount = 0, secretaryCount = 0, treasurerCount = 0, othersCount = 0;
                
                // Count all rows, not just visible ones
                $('#myTable tbody tr').each(function() {
                    if ($(this).find('td').length > 1) { // Skip "no data" rows
                        const position = $(this).find('td').eq(7).text().trim();
                        allCount++;
                        if (position === 'SK Pederasyon President') {
                            presidentCount++;
                        } else if (position === 'SK Pederasyon Vice President') {
                            vicePresidentCount++;
                        } else if (position === 'SK Pederasyon Secretary') {
                            secretaryCount++;
                        } else if (position === 'SK Pederasyon Treasurer') {
                            treasurerCount++;
                        } else {
                            othersCount++; // Auditor, Public Information Officer, Sergeant at Arms, Member
                        }
                    }
                });
                
                // Store original counts
                originalCounts = {
                    all: allCount,
                    president: presidentCount,
                    vicepresident: vicePresidentCount,
                    secretary: secretaryCount,
                    treasurer: treasurerCount,
                    others: othersCount
                };
            }

            // Update displayed counts (always show original counts)
            function updateDisplayedCounts() {
                $('#countAll').text(originalCounts.all);
                $('#countPresident').text(originalCounts.president);
                $('#countVicePresident').text(originalCounts.vicepresident);
                $('#countSecretary').text(originalCounts.secretary);
                $('#countTreasurer').text(originalCounts.treasurer);
                $('#countOthers').text(originalCounts.others);
            }

            // Position tab filtering logic
            function setActivePositionTab(tab) {
                $('.status-tab').removeClass('active bg-blue-500 text-white')
                    .addClass('bg-gray-100');
                $('.status-tab[data-position="president"]').removeClass('bg-gray-100').addClass('bg-blue-100');
                $('.status-tab[data-position="vicepresident"]').removeClass('bg-gray-100').addClass('bg-blue-100');
                $('.status-tab[data-position="secretary"]').removeClass('bg-gray-100').addClass('bg-blue-100');
                $('.status-tab[data-position="treasurer"]').removeClass('bg-gray-100').addClass('bg-green-100');
                $('.status-tab[data-position="others"]').removeClass('bg-gray-100').addClass('bg-yellow-100');
                
                tab.removeClass('bg-gray-100 bg-purple-100 bg-indigo-100 bg-blue-100 bg-green-100 bg-yellow-100')
                    .addClass('active bg-blue-500 text-white');
            }

            // Apply filters with DataTable integration
            function applyFilters() {
                const positionFilter = $('.status-tab.active').data('position');
                const barangayFilter = $('#barangayFilter').val();
                
                // Clear existing DataTable search
                table.search('').columns().search('');
                
                // Apply position filter using DataTable column search
                if (positionFilter !== 'all') {
                    let searchTerms = [];
                    if (positionFilter === 'president') {
                        searchTerms = ['SK Pederasyon President'];
                    } else if (positionFilter === 'vicepresident') {
                        searchTerms = ['SK Pederasyon Vice President'];
                    } else if (positionFilter === 'secretary') {
                        searchTerms = ['SK Pederasyon Secretary'];
                    } else if (positionFilter === 'treasurer') {
                        searchTerms = ['SK Pederasyon Treasurer'];
                    } else if (positionFilter === 'others') {
                        searchTerms = ['SK Pederasyon Auditor', 'SK Pederasyon Public Information Officer', 'SK Pederasyon Sergeant at Arms', 'SK Pederasyon Member'];
                    }
                    
                    if (searchTerms.length > 0) {
                        const regex = searchTerms.join('|');
                        table.column(7).search(regex, true, false);
                    }
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

            // Position tab click handlers
            $('.status-tab').on('click', function() {
                setActivePositionTab($(this));
                applyFilters();
                localStorage.setItem('activePositionTab', $(this).data('position'));
            });

            // Barangay filter change handler
            $('#barangayFilter').on('change', function() {
                applyFilters();
                localStorage.setItem('activeBarangayFilter', $(this).val());
            });

            // Clear filters
            $('#clearFilters').on('click', function() {
                $('.status-tab[data-position="all"]').trigger('click');
                $('#barangayFilter').val('');
                table.search('').columns().search('').draw();
                localStorage.removeItem('activePositionTab');
                localStorage.removeItem('activeBarangayFilter');
                updateDisplayedCounts();
            });

            // Function to restore saved filters
            function restoreFilters() {
                const savedPositionTab = localStorage.getItem('activePositionTab') || 'all';
                const savedBarangayFilter = localStorage.getItem('activeBarangayFilter') || '';
                
                $('.status-tab[data-position="' + savedPositionTab + '"]').trigger('click');
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
                            $('#modalUserAge').text(u.age + ' years old');
                            $('#modalUserSex').text(u.sex == '1' ? 'Male' : (u.sex == '2' ? 'Female' : ''));
                            $('#modalOfficerPosition').val(String(u.ped_position || 0));
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
                                        imgUrl = '<?= base_url('/previewDocument/profile_pictures/') ?>' + pp;
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
            let pendingPositionChange = { userId: null, newPosition: null };
            
            $('#saveOfficerPositionBtn').on('click', function() {
                // Store the intended change
                pendingPositionChange.userId = $('#modalUserId').text();
                pendingPositionChange.newPosition = $('#modalOfficerPosition').val();
                
                // Show confirmation modal
                $('#roleChangeModal').removeClass('hidden');
                $('#roleChangeModal').css('display', 'flex');
            });

            // Confirm position change
            $('#confirmRoleChangeBtn').on('click', function() {
                const userId = pendingPositionChange.userId;
                const newPosition = pendingPositionChange.newPosition;
                
                // Show loading state
                $(this).prop('disabled', true).text('Updating...');
                
                // Find the user row to get the database ID
                const userRow = $(`tr[data-ped_username]`).filter(function() {
                    return $(this).find('td').eq(1).text().trim() === userId;
                });
                
                const dbId = userRow.find('.rowCheckbox').val();
                
                $.ajax({
                    url: '<?= base_url('updateOfficerPosition') ?>',
                    method: 'POST',
                    data: { user_id: dbId || userId, ped_position: parseInt(newPosition, 10) },
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
    </script>

    <script>
        // ==================== OFFICIAL LIST FUNCTIONALITY (moved from youthlist) ==================== //
        
        // Open official list modal
        function openOfficialListModal() {
            const button = document.getElementById('downloadOfficialListBtn');
            const originalHTML = button.innerHTML;
            button.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Loading Official List...';
            button.disabled = true;
            setTimeout(() => {
                document.getElementById('officialListModal').classList.remove('hidden');
                loadOfficialList();
                setTimeout(() => { button.innerHTML = originalHTML; button.disabled = false; }, 500);
            }, 100);
        }
        
        // Close official list modal
        function closeOfficialListModal() {
            document.getElementById('officialListModal').classList.add('hidden');
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
                // Build a quick lookup for user info by user_id
                <?php if (!empty($ped_officers)): ?>
                const pedOfficersRaw = <?= json_encode($ped_officers) ?>;
                <?php else: ?>
                const pedOfficersRaw = [];
                <?php endif; ?>
                
                // Ensure we have a valid array - handle both array and object cases
                let pedOfficersList = [];
                if (Array.isArray(pedOfficersRaw)) {
                    pedOfficersList = pedOfficersRaw;
                } else if (pedOfficersRaw && typeof pedOfficersRaw === 'object') {
                    // Convert object to array if needed
                    pedOfficersList = Object.values(pedOfficersRaw);
                }
                
                const byId = Object.create(null);
                if (pedOfficersList.length > 0) {
                    pedOfficersList.forEach(u => { 
                        if (u && u.user_id != null) {
                            byId[String(u.user_id)] = u; 
                        }
                    });
                }

                // Prefer DataTables API if initialized to ensure we iterate all rows reliably
                // Simple and reliable approach: always use DOM iteration
                $('#myTable tbody tr:visible').each(function () {
                    try {
                        const $row = $(this);
                        const $cells = $row.find('td');
                        if ($cells.length < 8) return; // Skip rows with insufficient columns

                        // Extract cell values safely
                        const statusSpan = $cells.eq(6).find('span');
                        const statusText = statusSpan.length > 0 ? statusSpan.text().trim() : $cells.eq(6).text().trim();
                        
                        // Only include accepted officers
                        if (statusText.toLowerCase() !== 'accepted') return;

                        const displayUserId = $cells.eq(1).text().trim();
                        const barangay = $cells.eq(2).text().trim();
                        const name = $cells.eq(3).text().trim();
                        const age = $cells.eq(4).text().trim();
                        const sex = $cells.eq(5).text().trim();
                        const positionText = $cells.eq(7).text().trim();

                        // Skip if essential data is missing
                        if (!displayUserId || !name) return;

                        const userData = byId[String(displayUserId)];
                        let birthday = 'N/A';
                        let position = positionText ? `SK Pederasyon ${positionText}` : 'SK Pederasyon Officer';

                        if (userData) {
                            if (userData.birthdate) {
                                const birthDate = new Date(userData.birthdate);
                                if (!isNaN(birthDate) && birthDate.getFullYear() > 1900) {
                                    birthday = birthDate.toLocaleDateString('en-US', { month: '2-digit', day: '2-digit', year: 'numeric' });
                                }
                            }
                            const pedPos = parseInt(userData.ped_position) || 0;
                            if (pedPos === 1) { presidentName = formatFullNameFromUser(userData); }
                            if (pedPos === 3) { secretaryName = formatFullNameFromUser(userData); }
                        }

                        officials.push({ 
                            userId: displayUserId, 
                            barangay, 
                            name, 
                            age, 
                            birthday, 
                            sex, 
                            position 
                        });
                    } catch (rowError) {
                        console.warn('Error processing row:', rowError);
                        // Continue processing other rows
                    }
                });

                // Store signature names globally for PDF generation
                window.pederasyonSecretary = secretaryName;
                window.pederasyonPresident = presidentName;

                // Update UI
                const noOfficialsEl = document.getElementById('noOfficials');
                const signatureEl = document.getElementById('signatureSection');
                if (officials.length > 0) {
                    displayOfficialList(officials);
                    if (noOfficialsEl) noOfficialsEl.classList.add('hidden');
                    if (signatureEl) signatureEl.classList.remove('hidden');
                } else {
                    if (noOfficialsEl) noOfficialsEl.classList.remove('hidden');
                    if (signatureEl) signatureEl.classList.add('hidden');
                    document.getElementById('officialListTableBody').innerHTML = '';
                }
                document.getElementById('officialListCount').textContent = `Total Officials: ${officials.length}`;
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
                document.getElementById('officialListCount').textContent = 'Total Officials: 0';
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
            officials.forEach((official, index) => {
                const row = document.createElement('tr');
                row.className = index % 2 === 0 ? 'bg-white' : 'bg-gray-50';
                row.innerHTML = `
                    <td class="border border-gray-300 text-center px-2 py-2 text-gray-900 text-xs">${official.userId}</td>
                    <td class="border border-gray-300 text-center px-2 py-2 text-gray-900 text-xs">${official.barangay}</td>
                    <td class="border border-gray-300 text-center px-2 py-2 text-gray-900 text-xs">${official.name}</td>
                    <td class="border border-gray-300 text-center px-2 py-2 text-gray-900 text-xs">${official.age}</td>
                    <td class="border border-gray-300 text-center px-2 py-2 text-gray-900 text-xs">${official.birthday}</td>
                    <td class="border border-gray-300 text-center px-2 py-2 text-gray-900 text-xs">${official.sex}</td>
                    <td class="border border-gray-300 text-center px-2 py-2 text-gray-900 text-xs">${official.position}</td>`;
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
        function downloadOfficialListPDF() {
            const button = event.target;
            const originalHTML = button.innerHTML;
            button.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Generating PDF...';
            button.disabled = true;
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('l', 'mm', 'a4');
            fetch('<?= base_url('documents/logos') ?>')
                .then(r => r.json())
                .then(data => {
                    const promises = [];
                    let pLogo = null, iLogo = null;
                    const logos = (data && data.success) ? (data.data || {}) : {};
                    if (logos.pederasyon) {
                        promises.push(new Promise(resolve => { const img = new Image(); img.crossOrigin='anonymous'; img.onload=function(){pLogo=this; resolve();}; img.onerror=()=>resolve(); img.src='<?= base_url() ?>'+logos.pederasyon.file_path; }));
                    }
                    if (logos.iriga_city) {
                        promises.push(new Promise(resolve => { const img = new Image(); img.crossOrigin='anonymous'; img.onload=function(){iLogo=this; resolve();}; img.onerror=()=>resolve(); img.src='<?= base_url() ?>'+logos.iriga_city.file_path; }));
                    }
                    Promise.all(promises).then(()=>generatePDFWithLogos(doc, pLogo, iLogo));
                })
                .catch(() => generatePDFWithLogos(doc, null, null));
        }
        
        function generatePDFWithLogos(doc, pLogo, iLogo) {
            const pageWidth = doc.internal.pageSize.getWidth();
            const center = pageWidth / 2;
            let headerY = 20;
            const logoSize = 15, textBlockWidth = 100;
            if (pLogo) doc.addImage(pLogo, 'PNG', center - (textBlockWidth/2) - logoSize - 5, headerY + 8, logoSize, logoSize);
            if (iLogo) doc.addImage(iLogo, 'PNG', center + (textBlockWidth/2) + 5, headerY + 8, logoSize, logoSize);
            doc.setFontSize(14); doc.setFont(undefined, 'bold');
            doc.text('REPUBLIC OF THE PHILIPPINES', center, headerY + 8, { align: 'center' });
            doc.text('PROVINCE OF CAMARINES SUR', center, headerY + 16, { align: 'center' });
            doc.text('CITY OF IRIGA', center, headerY + 24, { align: 'center' });
            doc.setFontSize(10); doc.setFont(undefined, 'normal');
            doc.text('PANLUNGSOD NA PEDERASYON NG MGA', center, headerY + 28, { align: 'center' });
            doc.text('SANGGUNIANG KABATAAN NG IRIGA', center, headerY + 34, { align: 'center' });
            doc.line(30, headerY + 40, pageWidth - 30, headerY + 40);
            doc.setFontSize(12); doc.setFont(undefined, 'bold');
            doc.text('PANLUNGSOD NA PEDERASYON NG MGA KABATAAN', center, headerY + 50, { align: 'center' });
            doc.setFontSize(10); doc.text('OFFICIAL LIST', center, headerY + 58, { align: 'center' });
            const headers = ['User ID', 'Barangay', 'Name', 'Age', 'Birthday', 'Sex', 'Position'];
            const tableData = [];
            $('#officialListTableBody tr').each(function(){ const row=[]; $(this).find('td').each(function(){ row.push($(this).text().trim()); }); if(row.length>0) tableData.push(row); });
            const tableWidth = 225; const startX = (pageWidth - tableWidth) / 2;
            doc.autoTable({ head:[headers], body:tableData, startY: headerY + 65, margin:{ left:startX }, styles:{ fontSize:8, cellPadding:1, halign:'center', textColor:[0,0,0], lineColor:[0,0,0], lineWidth:0.2 }, headStyles:{ fillColor:[255,255,255], textColor:[0,0,0], halign:'center', fontStyle:'bold', cellPadding:1, lineColor:[0,0,0], lineWidth:0.2 }, bodyStyles:{ lineColor:[0,0,0], lineWidth:0.2, fillColor:[255,255,255] }, columnStyles:{ 0:{cellWidth:25}, 1:{cellWidth:35}, 2:{cellWidth:60}, 3:{cellWidth:20}, 4:{cellWidth:25}, 5:{cellWidth:20}, 6:{cellWidth:40} }, tableWidth:'wrap', theme:'grid' });
            const finalY = doc.lastAutoTable.finalY + 20; const spacing = 80; const leftX = center - spacing; const rightX = center + spacing - 40;
            doc.setFont(undefined, 'normal'); doc.setFontSize(10);
            doc.text('Prepared by:', leftX, finalY, { align: 'center' });
            doc.text('________________', leftX, finalY + 20, { align: 'center' });
            doc.setFont(undefined, 'bold'); doc.text(window.pederasyonSecretary || '________________', leftX, finalY + 25, { align: 'center' });
            doc.setFont(undefined, 'normal'); doc.text('Pederasyon Secretary', leftX, finalY + 30, { align: 'center' });
            doc.text('Approved by:', rightX, finalY, { align: 'center' });
            doc.text('________________', rightX, finalY + 20, { align: 'center' });
            doc.setFont(undefined, 'bold'); doc.text(window.pederasyonPresident || '________________', rightX, finalY + 25, { align: 'center' });
            doc.setFont(undefined, 'normal'); doc.text('Pederasyon President', rightX, finalY + 30, { align: 'center' });
            doc.save('PEDERASYON_Official_List.pdf');
            showNotification('Official List PDF document generated and downloaded successfully!', 'success');
            const trigger = document.querySelector('#officialListModal button[onclick="downloadOfficialListPDF()"]');
            if (trigger) { trigger.innerHTML = 'Download PDF'; trigger.disabled = false; }
        }
        
        // Download Word (server-generated)
        function downloadOfficialListWord() {
            const button = event.target; const originalHTML = button.innerHTML;
            button.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Generating Word...'; button.disabled = true;
            fetch('<?= base_url('pederasyon/generate-official-list-word') ?>', { method:'POST', headers:{ 'Content-Type':'application/json', 'X-Requested-With':'XMLHttpRequest' }, body: JSON.stringify({}) })
                .then(res => res.json())
                .then(data => {
                    if (data && data.success && data.download_url) {
                        const a = document.createElement('a'); a.href = data.download_url; a.download = data.download_url.split('/').pop(); a.style.display='none'; document.body.appendChild(a); a.click(); a.remove();
                        showNotification('Official List Word document generated and downloaded successfully!', 'success');
                    } else { showNotification('Error generating Word document: ' + (data.message || 'Unknown error.'), 'error'); }
                })
                .catch(err => { showNotification('Error generating Word document: ' + err.message, 'error'); })
                .finally(() => { button.innerHTML = originalHTML; button.disabled = false; });
        }
        
        // Download Excel (server-generated)
        function downloadOfficialListExcel() {
            const button = event.target; const originalHTML = button.innerHTML;
            button.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Generating Excel...'; button.disabled = true;
            fetch('<?= base_url('pederasyon/generate-official-list-excel') ?>', { method:'POST', headers:{ 'Content-Type':'application/json', 'X-Requested-With':'XMLHttpRequest' }, body: JSON.stringify({}) })
                .then(res => res.json())
                .then(data => {
                    if (data && data.success && data.download_url) {
                        const a = document.createElement('a'); a.href = data.download_url; a.download = data.download_url.split('/').pop(); a.style.display='none'; document.body.appendChild(a); a.click(); a.remove();
                        showNotification('Official List Excel document generated and downloaded successfully!', 'success');
                    } else { showNotification('Error generating Excel document: ' + (data.message || 'Unknown error.'), 'error'); }
                })
                .catch(err => { showNotification('Error generating Excel document: ' + err.message, 'error'); })
                .finally(() => { button.innerHTML = originalHTML; button.disabled = false; });
        }
        
        // Event listeners for official list
        document.getElementById('downloadOfficialListBtn').addEventListener('click', openOfficialListModal);
        $('#officialListModal').on('click', function(e) { if (e.target === this) { closeOfficialListModal(); } });
    </script>

    <!-- Official List Modal - Unified Design -->
    <div id="officialListModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
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
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8l-4 4-4-4m0 0V3"></path>
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
                    <div class="flex gap-3">
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
