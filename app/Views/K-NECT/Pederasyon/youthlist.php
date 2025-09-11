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

        /* Credentials Modal Tabs Styling */
        .credentials-tab.active {
            border-color: #0d9488 !important;
            color: #0d9488 !important;
        }

        .credentials-tab:not(.active):hover {
            color: #374151 !important;
            border-color: #d1d5db !important;
        }

        /* Credentials content styling */
        .credentials-section {
            animation: fadeIn 0.2s ease-in-out;
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

        /* Print styles */
        @media print {
            @page {
                size: 13in 8.5in;
                margin: 0.5in;
            }
            
            body {
                font-family: Arial, sans-serif !important;
                color: black !important;
            }
            
            .print-hidden { display: none !important; }
            
            table { 
                font-size: 6px !important;
                color: black !important;
            }
            
            th, td { 
                padding: 1px !important; 
                font-size: 6px !important;
                color: black !important;
                border: 1px solid black !important;
            }
        }
    </style>
        <!-- Main Content -->
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
                                <span class="text-sm font-medium text-gray-600">Youth Lists</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <!-- Header Section -->
                <div class="mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">Youth Lists</h3>
                        <p class="text-sm text-gray-600 mt-1">Manage user types and credentials</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button id="downloadCredentialsBtn" class="inline-flex items-center px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg transition-colors justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Download Credentials
                        </button>
                    </div>
                </div>
                
                <!-- Filter Tabs and Barangay Selector -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <!-- Role Status Tabs -->
                            <div class="flex flex-wrap gap-2">
                                <button class="status-tab active px-4 py-2 rounded-lg text-sm font-medium transition-all" data-role="all">
                                    All (<span id="countAll">0</span>)
                                </button>
                                <button class="status-tab px-4 py-2 rounded-lg text-sm font-medium transition-all" data-role="pederasyon">
                                    Pederasyon (<span id="countPederasyon">0</span>)
                                </button>
                                <button class="status-tab px-4 py-2 rounded-lg text-sm font-medium transition-all" data-role="sk">
                                    SK Chairperson (<span id="countSK">0</span>)
                                </button>
                                <button class="status-tab px-4 py-2 rounded-lg text-sm font-medium transition-all" data-role="kk">
                                    KK Member (<span id="countKK">0</span>)
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
                
                <!-- Data Table -->
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
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">User Type</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php
                                    use App\Libraries\BarangayHelper;
                                    ?>
                                    <?php if (!empty($user_list)): ?>
                                        <?php foreach ($user_list as $user): ?>
                                            <tr class="hover:bg-gray-50"
                                                data-sk_username="<?= isset($user['sk_username']) ? esc($user['sk_username']) : '' ?>"
                                                data-sk_password="<?= isset($user['sk_password']) ? esc($user['sk_password']) : '' ?>"
                                                data-ped_username="<?= isset($user['ped_username']) ? esc($user['ped_username']) : '' ?>"
                                                data-ped_password="<?= isset($user['ped_password']) ? esc($user['ped_password']) : '' ?>">
                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <input type="checkbox" class="rowCheckbox form-checkbox h-4 w-4 text-blue-600" value="<?= esc($user['id']) ?>">
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($user['user_id']) ?></td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <?= esc(BarangayHelper::getBarangayName($user['barangay'])) ?>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($user['last_name']) ?>, <?= esc($user['first_name']) ?> <?= esc($user['middle_name']) ?></td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($user['age']) ?></td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900"><?= $user['sex'] == '1' ? 'Male' : ($user['sex'] == '2' ? 'Female' : '') ?></td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <?php
                                                    $status = isset($user['status']) ? (int)$user['status'] : 1;
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
                                                        $type = isset($user['user_type']) ? (int)$user['user_type'] : 1;
                                                        echo $type == 1 ? 'KK Member' : ($type == 2 ? 'SK Chairperson' : ($type == 3 ? 'Pederasyon Officer' : 'Unknown'));
                                                    ?>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <button type="button" 
                                                        class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors view-user-btn"
                                                        data-id="<?= esc($user['id']) ?>"
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
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                                    </svg>
                                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No official records found</h3>
                                                    <p class="text-gray-500">There are no official records in the database yet.</p>
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
    <button id="bulkChangeBtn" class="fixed bottom-8 left-1/2 transform -translate-x-1/2 z-50 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-lg font-semibold rounded-full shadow-lg transition-all duration-200 hidden">
        Change Position for Selected
    </button>

    <!-- Bulk Change Modal -->
    <div id="bulkChangeModal" class="fixed inset-0 z-[99999] hidden bg-black bg-opacity-40 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-8 relative">
            <button id="closeBulkChangeModal" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <h3 class="text-xl font-bold text-gray-900 mb-4 text-center">Bulk Change User Position</h3>
            <div class="mb-6">
                <label for="bulkNewPosition" class="block text-sm font-medium text-gray-700 mb-2">Select New Position</label>
                <select id="bulkNewPosition" class="w-full border border-gray-300 rounded-md px-2 py-2 text-base focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    <option value="1">KK Member</option>
                    <option value="2">SK Chairperson</option>
                    <option value="3">Pederasyon Officer</option>
                </select>
            </div>
            <div class="flex justify-center gap-4">
                <button id="confirmBulkChangeBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">Confirm</button>
                <button id="cancelBulkChangeBtn" class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">Cancel</button>
            </div>
        </div>
    </div>

    <!-- User Detail Modal - Clean & Modern Design -->
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
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Confirm Role Change</h3>
                        <p id="roleChangeMessage" class="text-gray-600 mb-6">Are you sure you want to change this user's role?</p>
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
                    <h3 class="text-lg font-semibold text-gray-900">User Profile</h3>
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
                    
                    <!-- Role Management Card -->
                    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <label class="text-sm font-semibold text-gray-700">User Role</label>
                        </div>
                        <select id="modalUserType" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent mb-3">
                            <option value="1">KK Member</option>
                            <option value="2">SK Chairperson</option>
                            <option value="3">Pederasyon Officer</option>
                        </select>
                        <button id="saveUserTypeBtn" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg text-sm font-medium transition-all duration-200 shadow-sm">
                            Update Role
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
        // Barangay mapping from PHP
        const barangayMap = <?= json_encode(BarangayHelper::getBarangayMap()) ?>;
        
        // Helper function to get barangay name
        function getBarangayName(barangayId) {
            return barangayMap[barangayId] || barangayId || '';
        }
        
        // Utility function to show notifications
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
            sk: 0,
            pederasyon: 0,
            kk: 0
        };
        
        $(document).ready(function () {
            // DataTable and tab logic
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
                    search: "Search all records:",
                    searchPlaceholder: "Type to search..."
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
                let allCount = 0, skCount = 0, pederasyonCount = 0, kkCount = 0;
                
                // Count all rows, not just visible ones
                $('#myTable tbody tr').each(function() {
                    if ($(this).find('td').length > 1) { // Skip "no data" rows
                        const userType = $(this).find('td').eq(7).text().trim();
                        allCount++;
                        if (userType === 'SK Chairman' || userType === 'SK Chairperson') {
                            skCount++;
                        } else if (userType === 'Pederasyon Officer') {
                            pederasyonCount++;
                        } else if (userType === 'KK Member') {
                            kkCount++;
                        }
                    }
                });
                
                // Store original counts
                originalCounts = {
                    all: allCount,
                    sk: skCount,
                    pederasyon: pederasyonCount,
                    kk: kkCount
                };
            }

            // Update displayed counts (always show original counts)
            function updateDisplayedCounts() {
                $('#countAll').text(originalCounts.all);
                $('#countSK').text(originalCounts.sk);
                $('#countPederasyon').text(originalCounts.pederasyon);
                $('#countKK').text(originalCounts.kk);
            }

            // Update role counts based on filtered data (now just updates display with original counts)
            function updateRoleCounts() {
                // Always show original counts, regardless of current filter
                updateDisplayedCounts();
            }

            // Role tab filtering logic
            function setActiveRoleTab(tab) {
                $('.status-tab').removeClass('active bg-blue-500 text-white')
                    .addClass('bg-gray-100');
                $('.status-tab[data-role="sk"]').removeClass('bg-gray-100').addClass('bg-yellow-100');
                $('.status-tab[data-role="pederasyon"]').removeClass('bg-gray-100').addClass('bg-green-100');
                $('.status-tab[data-role="kk"]').removeClass('bg-gray-100').addClass('bg-red-100');
                
                tab.removeClass('bg-gray-100 bg-yellow-100 bg-green-100 bg-red-100')
                    .addClass('active bg-blue-500 text-white');
            }

            // Apply filters with DataTable integration
            function applyFilters() {
                const roleFilter = $('.status-tab.active').data('role');
                const barangayFilter = $('#barangayFilter').val();
                
                // Clear existing DataTable search
                table.search('').columns().search('');
                
                // Apply role filter using DataTable column search
                if (roleFilter !== 'all') {
                    let searchTerms = [];
                    if (roleFilter === 'sk') {
                        searchTerms = ['SK Chairman', 'SK Chairperson'];
                    } else if (roleFilter === 'pederasyon') {
                        searchTerms = ['Pederasyon Officer'];
                    } else if (roleFilter === 'kk') {
                        searchTerms = ['KK Member'];
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

            // Role tab click handlers
            $('.status-tab').on('click', function() {
                setActiveRoleTab($(this));
                applyFilters();
                localStorage.setItem('activeRoleTab', $(this).data('role'));
            });

            // Barangay filter change handler
            $('#barangayFilter').on('change', function() {
                applyFilters();
                localStorage.setItem('activeBarangayFilter', $(this).val());
            });

            // Clear filters
            $('#clearFilters').on('click', function() {
                $('.status-tab[data-role="all"]').trigger('click');
                $('#barangayFilter').val('');
                table.search('').columns().search('').draw();
                localStorage.removeItem('activeRoleTab');
                localStorage.removeItem('activeBarangayFilter');
                updateDisplayedCounts();
            });

            // Function to restore saved filters
            function restoreFilters() {
                const savedRoleTab = localStorage.getItem('activeRoleTab') || 'all';
                const savedBarangayFilter = localStorage.getItem('activeBarangayFilter') || '';
                
                $('.status-tab[data-role="' + savedRoleTab + '"]').trigger('click');
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
                var newType = $('#bulkNewPosition').val();
                if (selectedIds.length === 0) {
                    showNotification('No users selected.', 'error');
                    return;
                }
                
                // Show loading state
                $(this).prop('disabled', true).text('Updating...');
                
                // AJAX request to bulk update
                $.ajax({
                    url: '/bulkUpdateUserType',
                    method: 'POST',
                    data: { user_ids: selectedIds, user_type: newType },
                    success: function(response) {
                        if (response && response.success) {
                            showNotification('User positions updated successfully!', 'success');
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        } else {
                            showNotification(response.message || 'Failed to update user positions.', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Bulk update error:', error);
                        showNotification('Failed to update user positions. Please try again.', 'error');
                    },
                    complete: function() {
                        $('#confirmBulkChangeBtn').prop('disabled', false).text('Confirm');
                        $('#bulkChangeModal').addClass('hidden').css('display', 'none');
                    }
                });
            });

            // User Detail Modal functionality
            $(document).on('click', '.view-user-btn', function(e) {
                e.preventDefault();
                var userId = $(this).data('id');
                
                // Show loading state
                $('#userDetailModal').removeClass('hidden');
                $('#modalUserFullName').text('Loading...');
                
                $.ajax({
                    url: '/getUserInfo',
                    method: 'POST',
                    data: { user_id: userId },
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
                            
                            // Barangay display (use mapping if numeric)
                            var barangayStr = barangayMap[u.barangay] || u.barangay || '';
                            $('#modalUserBarangay').text(barangayStr);
                            $('#modalUserBarangayDetail').text(barangayStr);
                            
                            $('#modalUserId').text(u.user_id || '');
                            $('#modalUserAge').text(u.age + ' years old');
                            $('#modalUserSex').text(u.sex == '1' ? 'Male' : (u.sex == '2' ? 'Female' : ''));
                            $('#modalUserType').val(String(u.user_type));
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
                            
                            // Address formatting with default region/province/municipality
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
                                    .addClass('inline-flex px-2 py-1 rounded-full text-sm font-medium ' + colorClass);
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
                            
                            // Disable user type change if status is Rejected or Pending
                            if (u.status == 3 || u.status == 1) {
                                $('#modalUserType').prop('disabled', true);
                                $('#saveUserTypeBtn').prop('disabled', true).addClass('bg-gray-300 cursor-not-allowed').removeClass('bg-blue-600 hover:bg-blue-700');
                            } else {
                                $('#modalUserType').prop('disabled', false);
                                $('#saveUserTypeBtn').prop('disabled', false).removeClass('bg-gray-300 cursor-not-allowed').addClass('bg-blue-600 hover:bg-blue-700');
                            }
                        } else {
                            showNotification('User not found.', 'error');
                        }
                    },
                    error: function() {
                        showNotification('Failed to fetch user info.', 'error');
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

                // Save user role functionality
            let pendingUserTypeChange = { userId: null, newType: null };
                $('#saveUserTypeBtn').on('click', function() {
                // Store the intended change
                pendingUserTypeChange.userId = $('#modalUserId').text();
                pendingUserTypeChange.newType = $('#modalUserType').val();
                // Show confirmation modal (now inside userDetailModal)
                $('#roleChangeModal').removeClass('hidden');
                // Ensure it's above modal content
                $('#roleChangeModal').css('display', 'flex');
            });

            // Confirm role change
            $('#confirmRoleChangeBtn').on('click', function() {
                const userId = pendingUserTypeChange.userId;
                const newType = pendingUserTypeChange.newType;
                
                // Show loading state
                $(this).prop('disabled', true).text('Updating...');
                
                // Find the user row to get the database ID
                const userRow = $(`tr[data-sk_username], tr[data-ped_username]`).filter(function() {
                    return $(this).find('td').eq(1).text().trim() === userId;
                });
                
                const dbId = userRow.find('.rowCheckbox').val();
                
                $.ajax({
                    url: '/updateUserType',
                    method: 'POST',
                    data: { user_id: dbId || userId, user_type: parseInt(newType, 10) },
                    success: function(response) {
                        if (response && response.success) {
                            showNotification('User type updated successfully!', 'success');
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        } else {
                            showNotification(response.message || 'Failed to update user type.', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('User type update error:', error);
                        showNotification('Failed to update user type. Please try again.', 'error');
                    },
                    complete: function() {
                        $('#confirmRoleChangeBtn').prop('disabled', false).text('Confirm');
                        // Close both modals
                        $('#roleChangeModal').addClass('hidden').css('display', 'none');
                        $('#userDetailModal').addClass('hidden');
                    }
                });
            });

            // Cancel role change
            $('#cancelRoleChangeBtn').on('click', function() {
                $('#roleChangeModal').addClass('hidden');
                $('#roleChangeModal').css('display', 'none');
            });

                // Prevent modal from closing when clicking inside the modal content
                $('#userDetailModal .bg-white').on('click', function(e) {
                    e.stopPropagation();
            });
        });

        // ==================== OFFICIAL LIST FUNCTIONALITY ==================== //
        
        // Open official list modal
        function openOfficialListModal() {
            // Show loading state
            const button = document.getElementById('downloadOfficialListBtn');
            const originalHTML = button.innerHTML;
            button.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Loading Official List...';
            button.disabled = true;
            
            // Load official list and then show modal
            setTimeout(() => {
                document.getElementById('officialListModal').classList.remove('hidden');
                loadOfficialList();
                
                // Reset button state after modal is shown
                setTimeout(() => {
                    button.innerHTML = originalHTML;
                    button.disabled = false;
                }, 500);
            }, 100);
        }
        
        // Close official list modal
        function closeOfficialListModal() {
            document.getElementById('officialListModal').classList.add('hidden');
        }
        
        // Helper to format names as: First Middle Last Suffix
    function formatFullNameFromUser(user) {
            if (!user) return '';
            const parts = [];
            if (user.first_name) parts.push(String(user.first_name).trim());
            if (user.middle_name) parts.push(String(user.middle_name).trim());
            if (user.last_name) parts.push(String(user.last_name).trim());
            return parts.join(' ').replace(/\s+/g, ' ').trim();
        }

        // Load official list
        function loadOfficialList() {
            document.getElementById('officialListLoading').classList.remove('hidden');
            document.getElementById('officialListContent').classList.add('hidden');
            
            // Get officials data from current table (SK Chairman, Pederasyon Officer, and specific positions)
            const officials = [];
            let secretaryName = '';
            let presidentName = '';
            
            $('#myTable tbody tr').each(function() {
                const userType = $(this).find('td').eq(7).text().trim();
                const status = $(this).find('td').eq(6).find('span').text().trim();
                
                // Include SK Chairman, Pederasyon Officer (which includes Secretary and President), and Accepted status
                if ((userType === 'SK Chairman' || userType === 'Pederasyon Officer') && status === 'Accepted') {
                    // Column indices based on table header: 0=checkbox, 1=user_id, 2=barangay, 3=name, 4=age, 5=sex, 6=status, 7=user type
                    const userIdCell = $(this).find('td').eq(1); // This contains the displayed permanent user_id
                    const primaryId = $(this).find('input.rowCheckbox').val(); // hidden primary key from checkbox value
                    const displayUserId = (userIdCell.text() || '').trim();
                    const barangay = $(this).find('td').eq(2).text().trim();
                    const name = $(this).find('td').eq(3).text().trim();
                    const age = $(this).find('td').eq(4).text().trim();
                    const sex = $(this).find('td').eq(5).text().trim();
                    
                    // Get the actual user data from the PHP data to access birthdate and user_id
                    let userData = null;
                    let actualUserId = '';
                    let birthday = 'N/A';
                    let position = userType;
                    
                    // Find user data from the PHP user_list
                    <?php if (!empty($user_list)): ?>
                        const userList = <?= json_encode($user_list) ?>;
                        // Prefer matching by permanent user_id shown in the table; fallback to primary id from checkbox
                        userData = userList.find(u => String(u.user_id) === String(displayUserId));
                        if (userData) {
                            actualUserId = userData.user_id || displayUserId || primaryId;
                            
                            // Debug: Log the user data to console to check birthdate
                            //
                            
                            // Handle birthdate with better validation
                            if (userData.birthdate && userData.birthdate !== null && userData.birthdate !== '') {
                                birthday = userData.birthdate;
                            } else {
                                birthday = 'N/A';
                            }
                            
                            // Determine specific position for Pederasyon Officers using ped_position
                            if (userType === 'Pederasyon Officer') {
                                const pedPosition = parseInt(userData.ped_position) || 0;
                                switch(pedPosition) {
                                    case 1:
                                        position = 'Pederasyon President';
                                        presidentName = formatFullNameFromUser(userData);
                                        break;
                                    case 2:
                                        position = 'Pederasyon Vice President';
                                        break;
                                    case 3:
                                        position = 'Pederasyon Secretary';
                                        secretaryName = formatFullNameFromUser(userData);
                                        break;
                                    case 4:
                                        position = 'Pederasyon Treasurer';
                                        break;
                                    case 5:
                                        position = 'Pederasyon Auditor';
                                        break;
                                    case 6:
                                        position = 'Pederasyon Public Information Officer';
                                        break;
                                    case 7:
                                        position = 'Pederasyon Sergeant at Arms';
                                        break;
                                    default:
                                        position = 'Pederasyon Officer';
                                        break;
                                }
                            } else if (userType === 'SK Chairman') {
                                const userPosition = parseInt(userData.position) || 0;
                                switch(userPosition) {
                                    case 1:
                                        position = 'SK Chairperson';
                                        break;
                                    case 2:
                                        position = 'SK Kagawad';
                                        break;
                                    case 3:
                                        position = 'SK Secretary';
                                        break;
                                    case 4:
                                        position = 'SK Treasurer';
                                        break;
                                    default:
                                        position = 'SK Official';
                                        break;
                                }
                            }
                            
                            // Format birthday for display
                            if (birthday && birthday !== 'N/A' && birthday !== null && birthday !== '') {
                                const birthDate = new Date(birthday);
                                if (!isNaN(birthDate.getTime()) && birthDate.getFullYear() > 1900) {
                                    birthday = birthDate.toLocaleDateString('en-US', {
                                        month: '2-digit',
                                        day: '2-digit', 
                                        year: 'numeric'
                                    });
                                } else {
                                    birthday = 'N/A';
                                }
                            } else {
                                birthday = 'N/A';
                            }
                        }
                    <?php endif; ?>
                    
                    // Only show permanent user_id; if missing, leave blank (do not show DB primary id)
                    const safeUserId = (actualUserId && String(actualUserId).trim() !== '')
                        ? actualUserId
                        : ((displayUserId && String(displayUserId).trim() !== '') ? displayUserId : '');

                    officials.push({
                        userId: safeUserId,
                        barangay: barangay,
                        name: name,
                        age: age,
                        birthday: birthday,
                        sex: sex,
                        position: position
                    });
                }
            });
            
            // Store secretary and president names globally for signature section
            window.pederasyonSecretary = secretaryName;
            window.pederasyonPresident = presidentName;
            
            document.getElementById('officialListLoading').classList.add('hidden');
            document.getElementById('officialListContent').classList.remove('hidden');
            
            if (officials.length > 0) {
                displayOfficialList(officials);
                document.getElementById('noOfficials').classList.add('hidden');
            } else {
                document.getElementById('noOfficials').classList.remove('hidden');
                document.getElementById('officialListTableBody').innerHTML = '';
            }
            
            document.getElementById('officialListCount').textContent = 
                `Total Officials: ${officials.length}`;
        }
        
        // Display officials in table
        function displayOfficialList(officials) {
            const tbody = document.getElementById('officialListTableBody');
            tbody.innerHTML = '';
            
            officials.forEach(official => {
                const row = document.createElement('tr');
                
                row.innerHTML = `
                    <td class="border border-black text-center" style="font-size: 8px; padding: 1px;">
                        ${official.userId}
                    </td>
                    <td class="border border-black text-center" style="font-size: 8px; padding: 1px;">
                        ${official.barangay}
                    </td>
                    <td class="border border-black text-center" style="font-size: 8px; padding: 1px;">
                        ${official.name}
                    </td>
                    <td class="border border-black text-center" style="font-size: 8px; padding: 1px;">
                        ${official.age}
                    </td>
                    <td class="border border-black text-center" style="font-size: 8px; padding: 1px;">
                        ${official.birthday}
                    </td>
                    <td class="border border-black text-center" style="font-size: 8px; padding: 1px;">
                        ${official.sex}
                    </td>
                    <td class="border border-black text-center" style="font-size: 8px; padding: 1px;">
                        ${official.position}
                    </td>
                `;
                
                tbody.appendChild(row);
            });
            
            // Always load the Pederasyon and Iriga City logos
            loadBarangayLogo();
            
            // Update signature names if available
            if (window.pederasyonSecretary) {
                document.getElementById('secretarySignature').textContent = window.pederasyonSecretary;
            }
            if (window.pederasyonPresident) {
                document.getElementById('presidentSignature').textContent = window.pederasyonPresident;
            }
        }
        
        // Load barangay and system logos
        function loadBarangayLogo(barangayName) {
            // Fetch logos from the API
            fetch('<?= base_url('documents/logos') ?>')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const logos = data.data;
                        
                        // Load Pederasyon logo
                        const pederasyonLogoDiv = document.getElementById('official-list-pederasyon-logo');
                        if (logos.pederasyon && pederasyonLogoDiv) {
                            pederasyonLogoDiv.innerHTML = `<img src="<?= base_url() ?>${logos.pederasyon.file_path}" alt="Pederasyon Logo" class="w-full h-full object-contain">`;
                        }
                        
                        // Load Iriga City logo
                        const irigaLogoDiv = document.getElementById('official-list-iriga-logo');
                        if (logos.iriga_city && irigaLogoDiv) {
                            irigaLogoDiv.innerHTML = `<img src="<?= base_url() ?>${logos.iriga_city.file_path}" alt="Iriga City Logo" class="w-full h-full object-contain">`;
                        }
                    } else {
                        console.error('Failed to load logos:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error fetching logos:', error);
                    // Keep default SVG icons if API fails
                });
        }
        
        // Print official list
        function printOfficialList() {
            // Show loading state
            const button = event.target;
            const originalHTML = button.innerHTML;
            button.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Preparing Print...';
            button.disabled = true;
            
            const printContent = document.getElementById('downloadOfficialContent').cloneNode(true);
            const originalContent = document.body.innerHTML;
            
            // Create print styles
            const printStyles = `
                <style>
                    @page {
                        size: A4;
                        margin: 0.5in;
                    }
                    body { 
                        font-family: Arial, sans-serif !important;
                        margin: 0;
                        padding: 20px;
                        -webkit-print-color-adjust: exact;
                        color-adjust: exact;
                    }
                    table {
                        width: 100% !important;
                        border-collapse: collapse !important;
                        font-size: 8px !important;
                    }
                    th, td {
                        border: 1px solid black !important;
                        padding: 1px !important;
                        text-align: center !important;
                        font-size: 8px !important;
                    }
                    .hidden { display: none !important; }
                </style>
            `;
            
            document.body.innerHTML = printStyles + printContent.outerHTML;
            window.print();
            document.body.innerHTML = originalContent;
            
            // Reset button state
            button.innerHTML = originalHTML;
            button.disabled = false;
            
            // Re-initialize the modal functionality
            setTimeout(() => {
                location.reload();
            }, 100);
        }
        
        // Download official list as PDF
        function downloadOfficialListPDF() {
            // Show loading state
            const button = event.target;
            const originalHTML = button.innerHTML;
            button.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Generating PDF...';
            button.disabled = true;
            
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('l', 'mm', 'a4'); // landscape orientation
            
            // First fetch logos to include in PDF if available
            fetch('<?= base_url('documents/logos') ?>')
                .then(response => response.json())
                .then(data => {
                    const promises = [];
                    let pederasyonLogo = null;
                    let irigaLogo = null;
                    
                    if (data.success && data.data) {
                        const logos = data.data;
                        
                        // Load Pederasyon logo
                        if (logos.pederasyon) {
                            const pederasyonPromise = new Promise((resolve) => {
                                const img = new Image();
                                img.crossOrigin = 'anonymous';
                                img.onload = function() {
                                    pederasyonLogo = this;
                                    resolve();
                                };
                                img.onerror = function() {
                                    resolve(); // Continue even if logo fails to load
                                };
                                img.src = '<?= base_url() ?>' + logos.pederasyon.file_path;
                            });
                            promises.push(pederasyonPromise);
                        }
                        
                        // Load Iriga City logo
                        if (logos.iriga_city) {
                            const irigaPromise = new Promise((resolve) => {
                                const img = new Image();
                                img.crossOrigin = 'anonymous';
                                img.onload = function() {
                                    irigaLogo = this;
                                    resolve();
                                };
                                img.onerror = function() {
                                    resolve(); // Continue even if logo fails to load
                                };
                                img.src = '<?= base_url() ?>' + logos.iriga_city.file_path;
                            });
                            promises.push(irigaPromise);
                        }
                    }
                    
                    // Wait for all logos to load, then generate PDF
                    Promise.all(promises).then(() => {
                        generatePDFWithLogos(doc, pederasyonLogo, irigaLogo);
                    });
                })
                .catch(error => {
                    console.error('Error fetching logos for PDF:', error);
                    // Continue with PDF generation without logos
                    generatePDFWithLogos(doc, null, null);
                });
        }
        
        function generatePDFWithLogos(doc, pederasyonLogo, irigaLogo) {
            const pageWidth = doc.internal.pageSize.getWidth();
            const pageCenter = pageWidth / 2;
            let headerY = 20; // Start higher for better layout
            
            // Calculate positioning to match the uploaded image layout
            const logoSize = 15; // Slightly smaller logos
            const textBlockWidth = 100; // Width of the main text block
            
            // Add logos if available - positioned directly beside the text block like in the image
            if (pederasyonLogo) {
                // Add Pederasyon logo (left side) - positioned immediately to the left of text
                doc.addImage(pederasyonLogo, 'PNG', pageCenter - (textBlockWidth/2) - logoSize - 5, headerY + 8, logoSize, logoSize);
            }
            
            if (irigaLogo) {
                // Add Iriga City logo (right side) - positioned immediately to the right of text
                doc.addImage(irigaLogo, 'PNG', pageCenter + (textBlockWidth/2) + 5, headerY + 8, logoSize, logoSize);
            }
            
            // Header text (centered) - positioned between the logos like in the image
            doc.setFontSize(14);
            doc.setFont(undefined, 'bold');
            doc.text('REPUBLIC OF THE PHILIPPINES', pageCenter, headerY + 8, { align: 'center' });
            doc.text('PROVINCE OF CAMARINES SUR', pageCenter, headerY + 16, { align: 'center' });
            doc.text('CITY OF IRIGA', pageCenter, headerY + 24, { align: 'center' });
            doc.setFontSize(10);
            doc.setFont(undefined, 'normal');
            doc.text('PANLUNGSOD NA PEDERASYON NG MGA', pageCenter, headerY + 28, { align: 'center' });
            doc.text('SANGGUNIANG KABATAAN NG IRIGA', pageCenter, headerY + 34, { align: 'center' });
            
            // Line
            doc.line(30, headerY + 40, pageWidth - 30, headerY + 40);
            
            // Title
            doc.setFontSize(12);
            doc.setFont(undefined, 'bold');
            doc.text('PANLUNGSOD NA PEDERASYON NG MGA KABATAAN', pageCenter, headerY + 50, { align: 'center' });
            doc.setFontSize(10);
            doc.text('OFFICIAL LIST', pageCenter, headerY + 58, { align: 'center' });
            
            // Table data
            const tableData = [];
            const headers = ['User ID', 'Barangay', 'Name', 'Age', 'Birthday', 'Sex', 'Position'];
            
            $('#officialListTableBody tr').each(function() {
                const row = [];
                $(this).find('td').each(function() {
                    row.push($(this).text().trim());
                });
                if (row.length > 0) {
                    tableData.push(row);
                }
            });
            
            // Add centered table with proper margins
            const tableWidth = 225; // Total width of table
            const startX = (pageWidth - tableWidth) / 2; // Center the table
            
            doc.autoTable({
                head: [headers],
                body: tableData,
                startY: headerY + 65,
                margin: { left: startX },
                styles: { 
                    fontSize: 8,
                    cellPadding: 1,
                    halign: 'center',
                    textColor: [0, 0, 0],
                    lineColor: [0, 0, 0],
                    lineWidth: 0.2
                },
                headStyles: { 
                    fillColor: [255, 255, 255], // white header
                    textColor: [0, 0, 0],       // black font
                    halign: 'center',
                    fontStyle: 'bold',
                    cellPadding: 1,
                    lineColor: [0, 0, 0],
                    lineWidth: 0.2
                },
                bodyStyles: {
                    lineColor: [0, 0, 0],
                    lineWidth: 0.2,
                    fillColor: [255, 255, 255] // white background
                },
                columnStyles: {
                    0: {cellWidth: 25, halign: 'center'}, // User ID
                    1: {cellWidth: 35, halign: 'center'}, // Barangay
                    2: {cellWidth: 60, halign: 'center'}, // Name
                    3: {cellWidth: 20, halign: 'center'}, // Age
                    4: {cellWidth: 25, halign: 'center'}, // Birthday
                    5: {cellWidth: 20, halign: 'center'}, // Sex
                    6: {cellWidth: 40, halign: 'center'}  // Position
                },
                tableWidth: 'wrap',
                theme: 'grid'
            });
            
            // Signature section - centered and aligned
            const finalY = doc.lastAutoTable.finalY + 20;
            const signatureSpacing = 80; // Distance between signature sections
            const leftSignatureX = pageCenter - signatureSpacing;
            const rightSignatureX = pageCenter + signatureSpacing - 40; // Adjust for text width
            
            doc.setFont(undefined, 'normal');
            doc.setFontSize(10);
            
            // Left signature (Prepared by)
            doc.text('Prepared by:', leftSignatureX, finalY, { align: 'center' });
            doc.text('________________', leftSignatureX, finalY + 20, { align: 'center' });
            doc.setFont(undefined, 'bold');
            const secretaryName = window.pederasyonSecretary || '________________';
            doc.text(secretaryName, leftSignatureX, finalY + 25, { align: 'center' });
            doc.setFont(undefined, 'normal');
            doc.text('Pederasyon Secretary', leftSignatureX, finalY + 30, { align: 'center' });
            
            // Right signature (Approved by)
            doc.text('Approved by:', rightSignatureX, finalY, { align: 'center' });
            doc.text('________________', rightSignatureX, finalY + 20, { align: 'center' });
            doc.setFont(undefined, 'bold');
            const presidentName = window.pederasyonPresident || '________________';
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
        }
        
        // Download official list as Word
        function downloadOfficialListWord() {
            // Show loading state
            const button = event.target;
            const originalText = button.textContent;
            const originalHTML = button.innerHTML;
            button.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Generating Word...';
            button.disabled = true;
            
            // Make AJAX request to generate Word document
            fetch('<?= base_url('pederasyon/generate-official-list-word') ?>', {
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
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Create a temporary link to download the Word document
                    const link = document.createElement('a');
                    link.href = data.download_url;
                    link.download = data.download_url.split('/').pop();
                    link.style.display = 'none';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    
                    // Show success message
                    showNotification('Official List Word document generated and downloaded successfully!', 'success');
                } else {
                    console.error('Server error:', data);
                    showNotification('Error generating Word document: ' + (data.message || 'Unknown error occurred'), 'error');
                }
            })
            .catch(error => {
                console.error('Network error:', error);
                showNotification('Error generating Word document: ' + error.message + '. Please check your connection and try again.', 'error');
            })
            .finally(() => {
                // Reset button state
                button.innerHTML = originalHTML;
                button.disabled = false;
            });
        }
        
        // Download official list as Excel
        function downloadOfficialListExcel() {
            // Show loading state
            const button = event.target;
            const originalText = button.textContent;
            const originalHTML = button.innerHTML;
            button.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Generating Excel...';
            button.disabled = true;
            
            // Make AJAX request to generate Excel document
            fetch('<?= base_url('pederasyon/generate-official-list-excel') ?>', {
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
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Create a temporary link to download the Excel document
                    const link = document.createElement('a');
                    link.href = data.download_url;
                    link.download = data.download_url.split('/').pop();
                    link.style.display = 'none';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    
                    // Show success message
                    showNotification('Official List Excel document generated and downloaded successfully!', 'success');
                } else {
                    console.error('Server error:', data);
                    showNotification('Error generating Excel document: ' + (data.message || 'Unknown error occurred'), 'error');
                }
            })
            .catch(error => {
                console.error('Network error:', error);
                showNotification('Error generating Excel document: ' + error.message + '. Please check your connection and try again.', 'error');
            })
            .finally(() => {
                // Reset button state
                button.innerHTML = originalHTML;
                button.disabled = false;
            });
        }
        
    // Event listeners for official list moved to ped-officers
        
        // Event listener for credentials download
        document.getElementById('downloadCredentialsBtn').addEventListener('click', downloadCredentials);
        
    // Official list modal handlers moved to ped-officers

        // ==================== CREDENTIALS DOWNLOAD FUNCTIONALITY ==================== //
        
        function downloadCredentials() {
            openCredentialsPreviewModal();
        }

        // ==================== CREDENTIALS PREVIEW MODAL FUNCTIONALITY ==================== //
        
        function openCredentialsPreviewModal() {
            document.getElementById('credentialsPreviewModal').classList.remove('hidden');
            
            // Show loading state
            const credentialsLoadingEl = document.getElementById('credentialsLoading');
            const credentialsContentEl = document.getElementById('credentialsContent');

            if (credentialsLoadingEl) credentialsLoadingEl.classList.remove('hidden');
            if (credentialsContentEl) credentialsContentEl.classList.add('hidden');

            // Helper to load logos and data, then toggle visibility safely
            const doLoadCredentials = () => {
                loadCredentialsLogos();
                loadCredentialsData();
                if (credentialsLoadingEl) credentialsLoadingEl.classList.add('hidden');
                if (credentialsContentEl) credentialsContentEl.classList.remove('hidden');
            };

            // If the loading element exists, keep the short delay for UX; otherwise load immediately
            if (credentialsLoadingEl) {
                setTimeout(doLoadCredentials, 800);
            } else {
                doLoadCredentials();
            }
        }

        function loadCredentialsLogos() {
            // Fetch logos from the API (same as official list)
            fetch('<?= base_url('documents/logos') ?>')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const logos = data.data;
                        
                        // Load Pederasyon logo for credentials
                        const pederasyonLogoDiv = document.getElementById('credentials-pederasyon-logo');
                        if (logos.pederasyon && pederasyonLogoDiv) {
                            pederasyonLogoDiv.innerHTML = `<img src="<?= base_url() ?>${logos.pederasyon.file_path}" alt="Pederasyon Logo" class="w-full h-full object-contain">`;
                        }
                        
                        // Load Iriga City logo for credentials
                        const irigaLogoDiv = document.getElementById('credentials-iriga-logo');
                        if (logos.iriga_city && irigaLogoDiv) {
                            irigaLogoDiv.innerHTML = `<img src="<?= base_url() ?>${logos.iriga_city.file_path}" alt="Iriga City Logo" class="w-full h-full object-contain">`;
                        }
                    } else {
                        console.error('Failed to load logos for credentials:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error fetching logos for credentials:', error);
                    // Keep default SVG icons if API fails
                });
        }

        function showCredentialsTab(tabName) {
            // Update tab styling
            document.querySelectorAll('.credentials-tab').forEach(tab => {
                tab.classList.remove('border-gray-600', 'text-gray-900', 'bg-gray-100');
                tab.classList.add('border-transparent', 'text-gray-600');
            });
            
            // Show active tab
            const activeTab = document.getElementById(tabName + 'CredentialsTab');
            activeTab.classList.add('border-gray-600', 'text-gray-900', 'bg-gray-100');
            activeTab.classList.remove('border-transparent', 'text-gray-600');
            
            // Show/hide content sections
            document.querySelectorAll('.credentials-section').forEach(section => {
                section.classList.add('hidden');
            });
            document.getElementById(tabName + 'CredentialsSection').classList.remove('hidden');
        }

        function getActiveCredentialsTab() {
            // Check which tab is currently active based on styling
            const skTab = document.getElementById('skCredentialsTab');
            const pederasyonTab = document.getElementById('pederasyonCredentialsTab');
            
            if (skTab.classList.contains('bg-gray-100')) {
                return 'sk';
            } else if (pederasyonTab.classList.contains('bg-gray-100')) {
                return 'pederasyon';
            }
            return 'sk'; // Default to SK if no active tab found
        }

        function loadCredentialsData() {
            // Fetch credentials data from the API instead of reading from table rows
            fetch('<?= base_url('pederasyon/credentials-data') ?>')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const skCredentials = data.data.sk || [];
                        const pederasyonCredentials = data.data.pederasyon || [];
                        
                        // Populate SK credentials table
                        populateCredentialsTable('sk', skCredentials);
                        
                        // Populate Pederasyon credentials table
                        populateCredentialsTable('pederasyon', pederasyonCredentials);
                        
                        // Update counts
                        document.getElementById('skCredentialsCount').textContent = skCredentials.length;
                        document.getElementById('pederasyonCredentialsCount').textContent = pederasyonCredentials.length;
                        
                        // Update total count
                        const totalCount = skCredentials.length + pederasyonCredentials.length;
                        document.getElementById('credentialsCount').textContent = `Total: ${totalCount} officials with credentials`;
                        
                        // Show/hide no credentials message
                        if (totalCount === 0) {
                            document.getElementById('noCredentials').classList.remove('hidden');
                            document.querySelector('.credentials-tables-container').classList.add('hidden');
                        } else {
                            document.getElementById('noCredentials').classList.add('hidden');
                            document.querySelector('.credentials-tables-container').classList.remove('hidden');
                            // Show first available tab
                            if (skCredentials.length > 0) {
                                showCredentialsTab('sk');
                            } else if (pederasyonCredentials.length > 0) {
                                showCredentialsTab('pederasyon');
                            }
                        }
                    } else {
                        console.error('Failed to load credentials data:', data.message);
                        // Show error message
                        document.getElementById('noCredentials').classList.remove('hidden');
                        document.querySelector('.credentials-tables-container').classList.add('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error fetching credentials data:', error);
                    // Show error message
                    document.getElementById('noCredentials').classList.remove('hidden');
                    document.querySelector('.credentials-tables-container').classList.add('hidden');
                });
        }

        function populateCredentialsTable(type, credentials) {
            const tableBody = document.getElementById(type + 'CredentialsTableBody');
            tableBody.innerHTML = '';
            
            if (credentials.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="border border-black text-center py-8" style="font-size: 11px; color: #666; font-family: Arial, sans-serif;">
                            <div class="flex flex-col items-center">
                                <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <div style="font-weight: 600; margin-bottom: 4px;">No ${type === 'sk' ? 'SK' : 'Pederasyon'} Credentials</div>
                                <div>No ${type === 'sk' ? 'SK officials' : 'Pederasyon officials'} with credentials found.</div>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }
            
            credentials.forEach((credential, index) => {
                const row = document.createElement('tr');
                row.className = index % 2 === 0 ? 'bg-white' : 'bg-gray-50';
                
                // Check if password is hashed (starts with $2y$ or is longer than 20 characters)
                const isHashedPassword = credential.password && (
                    credential.password.startsWith('$2y$') || 
                    credential.password.startsWith('$2b$') ||
                    credential.password.length > 20
                );
                
                // Display password: if hashed show asterisks, else show temporary password
                const displayPassword = isHashedPassword ? '********' : credential.password;
                
                row.innerHTML = `
                    <td class="border border-black text-center" style="font-size: 10px; padding: 1px;">${credential.userId}</td>
                    <td class="border border-black text-center" style="font-size: 10px; padding: 1px;">${credential.name}</td>
                    <td class="border border-black text-center" style="font-size: 10px; padding: 1px;">${credential.barangay}</td>
                    <td class="border border-black text-center" style="font-size: 10px; padding: 1px;">${credential.position}</td>
                    <td class="border border-black text-center" style="font-size: 10px; padding: 1px; font-weight: bold;">${credential.username}</td>
                    <td class="border border-black text-center" style="font-size: 10px; padding: 1px; font-weight: bold;">${displayPassword}</td>
                `;
                tableBody.appendChild(row);
            });
        }

        // ==================== CREDENTIALS DOWNLOAD FUNCTIONS ==================== //

        function downloadCredentialsFormat(format) {
            // Show loading notification
            // Removed generating toast per request
            
            // Close the modal
            closeCredentialsPreviewModal();
            
            let url;
            switch (format.toLowerCase()) {
                case 'excel':
                    url = '<?= base_url('pederasyon/generate-credentials') ?>';
                    break;
                case 'pdf':
                    url = '<?= base_url('pederasyon/generate-credentials-pdf') ?>';
                    break;
                case 'word':
                    url = '<?= base_url('pederasyon/generate-credentials-word') ?>';
                    break;
                default:
                    showNotification('Invalid format selected.', 'error');
                    return;
            }
            
            // Create a hidden link and trigger download
            const link = document.createElement('a');
            link.href = url;
            link.style.display = 'none';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            // Show success notification after short delay
            setTimeout(() => {
                showNotification(format.toUpperCase() + ' credentials document downloaded successfully!', 'success');
            }, 1000);
        }

        function closeCredentialsPreviewModal() {
            document.getElementById('credentialsPreviewModal').classList.add('hidden');
        }

    // printCredentials removed as requested

        function downloadCredentialsPDF() {
            // Show loading notification
            // Removed generating toast per request
            
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('l', 'mm', 'a4'); // landscape orientation
            
            // First fetch logos to include in PDF if available
            fetch('<?= base_url('documents/logos') ?>')
                .then(response => response.json())
                .then(data => {
                    const promises = [];
                    let pederasyonLogo = null;
                    let irigaLogo = null;
                    
                    if (data.success && data.data) {
                        const logos = data.data;
                        
                        // Load Pederasyon logo
                        if (logos.pederasyon) {
                            const pederasyonPromise = new Promise((resolve) => {
                                const img = new Image();
                                img.crossOrigin = 'anonymous';
                                img.onload = function() {
                                    pederasyonLogo = this;
                                    resolve();
                                };
                                img.onerror = function() {
                                    resolve(); // Continue even if logo fails to load
                                };
                                img.src = '<?= base_url() ?>' + logos.pederasyon.file_path;
                            });
                            promises.push(pederasyonPromise);
                        }
                        
                        // Load Iriga City logo
                        if (logos.iriga_city) {
                            const irigaPromise = new Promise((resolve) => {
                                const img = new Image();
                                img.crossOrigin = 'anonymous';
                                img.onload = function() {
                                    irigaLogo = this;
                                    resolve();
                                };
                                img.onerror = function() {
                                    resolve(); // Continue even if logo fails to load
                                };
                                img.src = '<?= base_url() ?>' + logos.iriga_city.file_path;
                            });
                            promises.push(irigaPromise);
                        }
                    }
                    
                    // Wait for all logos to load, then generate PDF
                    Promise.all(promises).then(() => {
                        try {
                            // Header with logos positioned beside the header
                            if (pederasyonLogo) {
                                doc.addImage(pederasyonLogo, 'JPEG', 40, 15, 25, 25);
                            }
                            if (irigaLogo) {
                                doc.addImage(irigaLogo, 'JPEG', 232, 15, 25, 25);
                            }
                            
                            // Header text (centered)
                            doc.setFont("helvetica", "bold");
                            doc.setFontSize(12);
                            doc.text("REPUBLIC OF THE PHILIPPINES", 148, 20, { align: 'center' });
                            doc.text("PROVINCE OF CAMARINES SUR", 148, 25, { align: 'center' });
                            doc.text("CITY OF IRIGA", 148, 30, { align: 'center' });
                            
                            doc.setFont("helvetica", "normal");
                            doc.setFontSize(10);
                            doc.text("PANLUNGSOD NA PEDERASYON NG MGA", 148, 35, { align: 'center' });
                            doc.text("SANGGUNIANG KABATAAN", 148, 39, { align: 'center' });
                            
                            // Title
                            doc.setFont("helvetica", "bold");
                            doc.setFontSize(12);
                            doc.text("PANLUNGSOD NA PEDERASYON NG MGA KABATAAN", 148, 50, { align: 'center' });
                            doc.text("OFFICIALS CREDENTIALS", 148, 55, { align: 'center' });
                            
                            let yPosition = 70;
                            
                            // Get officials data for credentials
                            const officials = <?= json_encode($user_list ?? []) ?>;
                            // Include SK Chairpersons (user_type=2, accepted)
                            const baseSk = officials.filter(user => parseInt(user.user_type) === 2 && parseInt(user.status) === 2);
                            // Pederasyon Officers (user_type=3, accepted)
                            const pederasyonOfficials = officials.filter(user => parseInt(user.user_type) === 3 && parseInt(user.status) === 2);
                            // Per rule: Pederasyon officers are also SK, include them in SK list if they have SK credentials
                            const pedWithSkCreds = pederasyonOfficials.filter(user => (user.sk_username && user.sk_username !== '') && (user.sk_password && user.sk_password !== ''));
                            const skOfficials = [...baseSk, ...pedWithSkCreds];
                            
                            // Get the currently active tab
                            const activeTab = getActiveCredentialsTab();
                            
                            // SK Officials Section - only show if SK tab is active
                            if (activeTab === 'sk' && skOfficials.length > 0) {
                                doc.setFont("helvetica", "bold");
                                doc.setFontSize(10);
                                doc.text("SANGGUNIANG KABATAAN OFFICIALS LOGIN CREDENTIALS", 148, yPosition, { align: 'center' });
                                yPosition += 8;
                                
                                // Prepare SK Officials table data
                                const skTableData = skOfficials.map(official => {
                                    const fullName = `${official.first_name || ''} ${official.middle_name || ''} ${official.last_name || ''}`.trim();
                                    const barangay = getBarangayName(official.barangay);
                                    const skPassword = (official.sk_password && official.sk_password.length > 20) ? '********' : (official.sk_password || 'N/A');
                                    
                                    return [
                                        official.user_id || '',
                                        fullName,
                                        barangay,
                                        'SK Chairperson',
                                        official.sk_username || 'N/A',
                                        skPassword
                                    ];
                                });
                                
                                // Add SK Officials table with simple styling
                                doc.autoTable({
                                    head: [['User ID', 'Full Name', 'Barangay', 'Position', 'Username', 'Password']],
                                    body: skTableData,
                                    startY: yPosition,
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
                                        0: { cellWidth: 18, halign: 'center' }, // User ID
                                        1: { cellWidth: 50, halign: 'center' }, // Full Name
                                        2: { cellWidth: 32, halign: 'center' }, // Barangay
                                        3: { cellWidth: 35, halign: 'center' }, // Position
                                        4: { cellWidth: 30, halign: 'center' }, // Username
                                        5: { cellWidth: 25, halign: 'center' } // Password
                                    },
                                    tableWidth: 190,
                                    margin: { left: (297 - 190) / 2 }, // Center table on A4 landscape (297mm width)
                                    theme: 'striped',
                                    alternateRowStyles: {
                                        fillColor: [245, 245, 245]
                                    }
                                });
                                
                                yPosition = doc.lastAutoTable.finalY + 10;
                            }
                            
                            // Pederasyon Officials Section - only show if Pederasyon tab is active
                            if (activeTab === 'pederasyon' && pederasyonOfficials.length > 0) {
                                // Check if we need a new page
                                if (yPosition > 170) {
                                    doc.addPage();
                                    yPosition = 20;
                                }
                                
                                doc.setFont("helvetica", "bold");
                                doc.setFontSize(10);
                                doc.text("PEDERASYON OFFICIALS LOGIN CREDENTIALS", 148, yPosition, { align: 'center' });
                                yPosition += 8;
                                
                                // Prepare Pederasyon Officials table data
                                const pedTableData = pederasyonOfficials.map(official => {
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
                                    const position = positionMap[parseInt(official.ped_position)] || 'Officer';
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
                                    startY: yPosition,
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
                                        0: { cellWidth: 18, halign: 'center' }, // User ID
                                        1: { cellWidth: 50, halign: 'center' }, // Full Name
                                        2: { cellWidth: 32, halign: 'center' }, // Barangay
                                        3: { cellWidth: 35, halign: 'center' }, // Position
                                        4: { cellWidth: 30, halign: 'center' }, // Username
                                        5: { cellWidth: 25, halign: 'center' } // Password
                                    },
                                    tableWidth: 190,
                                    margin: { left: (297 - 190) / 2 }, // Center table on A4 landscape (297mm width)
                                    theme: 'striped',
                                    alternateRowStyles: {
                                        fillColor: [245, 245, 245]
                                    }
                                });
                            }
                            
                            // Save the PDF
                            const fileName = 'PEDERASYON_Officials_Credentials_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-') + '.pdf';
                            doc.save(fileName);
                            
                            showNotification('Credentials PDF downloaded successfully!', 'success');
                        } catch (error) {
                            console.error('PDF generation error:', error);
                            showNotification('Error generating PDF: ' + error.message, 'error');
                        }
                    });
                })
                .catch(error => {
                    console.error('Error loading logos:', error);
                    // Generate PDF without logos if logo loading fails
                    try {
                        // Header text (centered)
                        doc.setFont("helvetica", "bold");
                        doc.setFontSize(12);
                        doc.text("REPUBLIC OF THE PHILIPPINES", 148, 20, { align: 'center' });
                        doc.text("PROVINCE OF CAMARINES SUR", 148, 25, { align: 'center' });
                        doc.text("CITY OF IRIGA", 148, 30, { align: 'center' });
                        
                        doc.setFont("helvetica", "normal");
                        doc.setFontSize(10);
                        doc.text("PANLUNGSOD NA PEDERASYON NG MGA", 148, 35, { align: 'center' });
                        doc.text("SANGGUNIANG KABATAAN NG IRIGA", 148, 39, { align: 'center' });
                        
                        // Title
                        doc.setFont("helvetica", "bold");
                        doc.setFontSize(12);
                        doc.text("PANLUNGSOD NA PEDERASYON NG MGA KABATAAN", 148, 50, { align: 'center' });
                        doc.text("OFFICIALS CREDENTIALS", 148, 55, { align: 'center' });
                        
                        let yPosition = 70;
                        
                        // Get officials data for credentials
                        const officials = <?= json_encode($user_list ?? []) ?>;
                        // Include SK Chairpersons (user_type=2, accepted)
                        const baseSk = officials.filter(user => parseInt(user.user_type) === 2 && parseInt(user.status) === 2);
                        // Pederasyon Officers (user_type=3, accepted)
                        const pederasyonOfficials = officials.filter(user => parseInt(user.user_type) === 3 && parseInt(user.status) === 2);
                        // Per rule: Pederasyon officers are also SK, include them in SK list if they have SK credentials
                        const pedWithSkCreds = pederasyonOfficials.filter(user => (user.sk_username && user.sk_username !== '') && (user.sk_password && user.sk_password !== ''));
                        const skOfficials = [...baseSk, ...pedWithSkCreds];
                        
                        // SK Officials Section
                        if (skOfficials.length > 0) {
                            doc.setFont("helvetica", "bold");
                            doc.setFontSize(11);
                            doc.text("SANGGUNIANG KABATAAN OFFICIALS LOGIN CREDENTIALS", 148, yPosition, { align: 'center' });
                            yPosition += 10;
                            
                            // Prepare SK Officials table data
                            const skTableData = skOfficials.map(official => {
                                const fullName = `${official.first_name || ''} ${official.middle_name || ''} ${official.last_name || ''}`.trim();
                                const barangay = getBarangayName(official.barangay);
                                const skPassword = (official.sk_password && official.sk_password.length > 20) ? '********' : (official.sk_password || 'N/A');
                                
                                return [
                                    official.user_id || '',
                                    fullName,
                                    barangay,
                                    'SK Chairperson',
                                    official.sk_username || 'N/A',
                                    skPassword
                                ];
                            });
                            
                            // Add SK Officials table
                            doc.autoTable({
                                head: [['User ID', 'Full Name', 'Barangay', 'Position', 'SK Username', 'SK Password']],
                                body: skTableData,
                                startY: yPosition,
                                styles: { 
                                    fontSize: 8,
                                    cellPadding: 2,
                                    halign: 'center',
                                    textColor: [0, 0, 0],
                                    fontStyle: 'normal',
                                    font: 'helvetica'
                                },
                                headStyles: {
                                    fillColor: [240, 240, 240],
                                    textColor: [0, 0, 0],
                                    fontStyle: 'bold',
                                    fontSize: 8,
                                    font: 'helvetica'
                                },
                                columnStyles: {
                                    0: { cellWidth: 20, font: 'helvetica' },
                                    1: { cellWidth: 50, font: 'helvetica' },
                                    2: { cellWidth: 35, font: 'helvetica' },
                                    3: { cellWidth: 35, font: 'helvetica' },
                                    4: { cellWidth: 30, font: 'helvetica', fontStyle: 'normal' },
                                    5: { cellWidth: 25, font: 'helvetica', fontStyle: 'normal' }
                                },
                                margin: { left: 20, right: 20 },
                                theme: 'grid'
                            });
                            
                            yPosition = doc.lastAutoTable.finalY + 15;
                        }
                        
                        // Pederasyon Officials Section
                        if (pederasyonOfficials.length > 0) {
                            // Check if we need a new page
                            if (yPosition > 160) {
                                doc.addPage();
                                yPosition = 20;
                            }
                            
                            doc.setFont("helvetica", "bold");
                            doc.setFontSize(11);
                            doc.text("PEDERASYON OFFICIALS LOGIN CREDENTIALS", 148, yPosition, { align: 'center' });
                            yPosition += 10;
                            
                            // Prepare Pederasyon Officials table data
                            const pedTableData = pederasyonOfficials.map(official => {
                                const fullName = `${official.first_name || ''} ${official.middle_name || ''} ${official.last_name || ''}`.trim();
                                const barangay = getBarangayName(official.barangay);
                                
                                const positionMap = {
                                    1: 'Pederasyon President',
                                    2: 'Pederasyon Vice President', 
                                    3: 'Pederasyon Secretary',
                                    4: 'Pederasyon Treasurer',
                                    5: 'Pederasyon Auditor',
                                    6: 'Pederasyon PIO',
                                    7: 'Pederasyon Sergeant at Arms'
                                };
                                const position = positionMap[parseInt(official.ped_position)] || 'Pederasyon Officer';
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
                            
                            // Add Pederasyon Officials table
                            doc.autoTable({
                                head: [['User ID', 'Full Name', 'Barangay', 'Position', 'Ped Username', 'Ped Password']],
                                body: pedTableData,
                                startY: yPosition,
                                styles: { 
                                    fontSize: 8,
                                    cellPadding: 2,
                                    halign: 'center',
                                    textColor: [0, 0, 0],
                                    fontStyle: 'normal',
                                    font: 'helvetica'
                                },
                                headStyles: {
                                    fillColor: [240, 240, 240],
                                    textColor: [0, 0, 0],
                                    fontStyle: 'bold',
                                    fontSize: 8,
                                    font: 'helvetica'
                                },
                                columnStyles: {
                                    0: { cellWidth: 20, font: 'helvetica' },
                                    1: { cellWidth: 50, font: 'helvetica' },
                                    2: { cellWidth: 35, font: 'helvetica' },
                                    3: { cellWidth: 35, font: 'helvetica' },
                                    4: { cellWidth: 30, font: 'helvetica', fontStyle: 'normal' },
                                    5: { cellWidth: 25, font: 'helvetica', fontStyle: 'normal' }
                                },
                                margin: { left: 20, right: 20 },
                                theme: 'grid'
                            });
                        }
                        
                        const fileName = 'PEDERASYON_Officials_Credentials_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-') + '.pdf';
                        doc.save(fileName);
                        
                        showNotification('Credentials PDF downloaded successfully (without logos)!', 'success');
                    } catch (pdfError) {
                        console.error('PDF generation error:', pdfError);
                        showNotification('Error generating PDF: ' + pdfError.message, 'error');
                    }
                });
        }

        function downloadCredentialsWord() {
            // Show loading state
            showNotification('Generating Word document...', 'info');
            
            console.log('Starting Word generation...');
            
            // Get the currently active tab
            const activeTab = getActiveCredentialsTab();
            
            // Make AJAX request to generate credentials Word document
            fetch('<?= base_url('pederasyon/generate-credentials-word') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    activeTab: activeTab
                })
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Server response:', data);
                if (data.success) {
                    // Multiple download methods for better compatibility
                    const downloadUrl = data.download_url;
                    const fileName = downloadUrl.split('/').pop();
                    
                    // Try primary method - temporary link
                    try {
                        const link = document.createElement('a');
                        link.href = downloadUrl;
                        link.download = fileName;
                        link.target = '_blank';
                        link.style.display = 'none';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    } catch (e) {
                        // Fallback method - window.open
                        console.log('Using fallback download method');
                        window.open(downloadUrl, '_blank');
                    }
                    
                    // Show success message
                    showNotification('Credentials Word document downloaded successfully!', 'success');
                } else {
                    console.error('Server error:', data);
                    showNotification('Error generating credentials Word: ' + (data.message || 'Unknown error occurred'), 'error');
                }
            })
            .catch(error => {
                console.error('Network error:', error);
                showNotification('Error generating credentials Word: ' + error.message + '. Please check your connection and try again.', 'error');
            });
        }

        function downloadCredentialsExcel() {
            // Show loading state
            showNotification('Generating Excel document...', 'info');
            
            console.log('Starting Excel generation...');
            
            // Get the currently active tab
            const activeTab = getActiveCredentialsTab();
            
            // Make AJAX request to generate credentials Excel document
            fetch('<?= base_url('pederasyon/generate-credentials-excel') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    activeTab: activeTab
                })
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Server response:', data);
                if (data.success) {
                    // Multiple download methods for better compatibility
                    const downloadUrl = data.download_url;
                    const fileName = downloadUrl.split('/').pop();
                    
                    // Try primary method - temporary link
                    try {
                        const link = document.createElement('a');
                        link.href = downloadUrl;
                        link.download = fileName;
                        link.target = '_blank';
                        link.style.display = 'none';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    } catch (e) {
                        // Fallback method - window.open
                        console.log('Using fallback download method');
                        window.open(downloadUrl, '_blank');
                    }
                    
                    // Show success message
                    showNotification('Credentials Excel document downloaded successfully!', 'success');
                } else {
                    console.error('Server error:', data);
                    showNotification('Error generating credentials document: ' + (data.message || 'Unknown error occurred'), 'error');
                }
            })
            .catch(error => {
                console.error('Network error:', error);
                showNotification('Error generating credentials document: ' + error.message + '. Please check your connection and try again.', 'error');
            });
        }

        // Close credentials modal when clicking outside
        $('#credentialsPreviewModal').on('click', function(e) {
            if (e.target === this) {
                closeCredentialsPreviewModal();
            }
        });

    </script>
    

    <!-- Credentials Preview Modal -->
    <div id="credentialsPreviewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div class="relative top-5 mx-auto p-5 border w-11/12 max-w-7xl shadow-lg rounded-md bg-white max-h-[90vh] overflow-y-auto">
            <div class="mt-3">
                <!-- Modal Header -->
                <div class="flex items-center justify-between pb-2 border-b">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">OFFICIALS CREDENTIALS</h3>
                        <p class="text-xs text-gray-600 mt-0.5">K-NECT System Officials Login Credentials</p>
                    </div>
                    <button onclick="closeCredentialsPreviewModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Modal Content -->
                <div class="mt-6">
                    <div id="credentialsLoading" class="text-center py-8">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                        <p class="mt-2 text-gray-600">Loading credentials...</p>
                    </div>

                    <div id="credentialsContent" class="hidden">
                        <!-- Document Header - Hidden in preview, shown in print -->
                        <div class="bg-white hidden print:block" style="font-family: Arial, sans-serif;">
                            <!-- Header Section with Logos -->
                            <div class="text-center mb-6 print:mb-4" style="font-family: Arial, sans-serif;">
                                <div class="flex items-center justify-center mb-4">
                                    <!-- Pederasyon Logo (Left) -->
                                    <div class="flex-shrink-0 mr-8">
                                        <div id="credentials-pederasyon-logo" class="w-16 h-16 rounded flex items-center justify-center">
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
                                        <div id="credentials-iriga-logo" class="w-16 h-16 rounded flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr class="border-gray-300 mb-4">
                                
                                <h2 style="font-family: Arial, sans-serif; font-size: 12pt; font-weight: bold; color: black; margin: 16px 0 24px 0;">PANLUNGSOD NA PEDERASYON NG MGA KABATAAN</h2>
                                <h3 style="font-family: Arial, sans-serif; font-size: 10pt; font-weight: bold; color: black; margin: 8px 0 16px 0;">OFFICIALS CREDENTIALS</h3>
                            </div>
                        </div>

                        <!-- Simplified Tab Navigation -->
                        <div class="border-b border-gray-300 mb-6">
                            <nav class="flex">
                                <button onclick="showCredentialsTab('sk')" id="skCredentialsTab" class="credentials-tab flex-1 py-3 px-4 border-b-2 border-gray-600 font-medium text-gray-900 bg-gray-100 transition-all duration-200">
                                    SK Credentials (<span id="skCredentialsCount">0</span>)
                                </button>
                                <button onclick="showCredentialsTab('pederasyon')" id="pederasyonCredentialsTab" class="credentials-tab flex-1 py-3 px-4 border-b-2 border-transparent font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-all duration-200">
                                    Pederasyon Credentials (<span id="pederasyonCredentialsCount">0</span>)
                                </button>
                            </nav>
                        </div>

                        <!-- Credentials Tables Container -->
                        <div class="credentials-tables-container bg-white border border-gray-300">
                            <!-- SK Credentials Table -->
                            <div id="skCredentialsSection" class="credentials-section p-4">
                                <div class="mb-4">
                                    <h4 class="text-lg font-medium text-gray-900 mb-2">SK Officials Login Credentials</h4>
                                    <p class="text-sm text-gray-600">Login information for SK Chairman and Kagawad positions</p>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="w-full border-collapse border border-black" style="font-size: 10px; font-family: Arial, sans-serif;">
                                        <thead>
                                            <tr class="bg-gray-100">
                                                <th class="border border-black text-center font-bold py-2 px-2" style="width: 12%; font-family: Arial, sans-serif;">User ID</th>
                                                <th class="border border-black text-center font-bold py-2 px-2" style="width: 22%; font-family: Arial, sans-serif;">Full Name</th>
                                                <th class="border border-black text-center font-bold py-2 px-2" style="width: 15%; font-family: Arial, sans-serif;">Barangay</th>
                                                <th class="border border-black text-center font-bold py-2 px-2" style="width: 15%; font-family: Arial, sans-serif;">Position</th>
                                                <th class="border border-black text-center font-bold py-2 px-2" style="width: 18%; font-family: Arial, sans-serif;">SK Username</th>
                                                <th class="border border-black text-center font-bold py-2 px-2" style="width: 18%; font-family: Arial, sans-serif;">SK Password</th>
                                            </tr>
                                        </thead>
                                        <tbody id="skCredentialsTableBody">
                                            <!-- SK credentials data will be populated here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Pederasyon Credentials Table -->
                            <div id="pederasyonCredentialsSection" class="credentials-section hidden p-4">
                                <div class="mb-4">
                                    <h4 class="text-lg font-medium text-gray-900 mb-2">Pederasyon Officials Login Credentials</h4>
                                    <p class="text-sm text-gray-600">Login information for Pederasyon officers and executives</p>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="w-full border-collapse border border-black" style="font-size: 10px; font-family: Arial, sans-serif;">
                                        <thead>
                                            <tr class="bg-gray-100">
                                                <th class="border border-black text-center font-bold py-2 px-2" style="width: 12%; font-family: Arial, sans-serif;">User ID</th>
                                                <th class="border border-black text-center font-bold py-2 px-2" style="width: 22%; font-family: Arial, sans-serif;">Full Name</th>
                                                <th class="border border-black text-center font-bold py-2 px-2" style="width: 15%; font-family: Arial, sans-serif;">Barangay</th>
                                                <th class="border border-black text-center font-bold py-2 px-2" style="width: 15%; font-family: Arial, sans-serif;">Position</th>
                                                <th class="border border-black text-center font-bold py-2 px-2" style="width: 18%; font-family: Arial, sans-serif;">Ped Username</th>
                                                <th class="border border-black text-center font-bold py-2 px-2" style="width: 18%; font-family: Arial, sans-serif;">Ped Password</th>
                                            </tr>
                                        </thead>
                                        <tbody id="pederasyonCredentialsTableBody">
                                            <!-- Pederasyon credentials data will be populated here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div id="noCredentials" class="text-center py-8 hidden">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No Credentials Found</h3>
                            <p class="mt-1 text-sm text-gray-500">No officials with credentials are currently registered in the system.</p>
                        </div>

                        <!-- Modal Footer -->
                        <div class="mt-6 pt-4 border-t flex justify-end gap-3">
                            <div id="credentialsCount" class="text-sm font-medium text-gray-700 mr-auto"></div>
                            <button onclick="closeCredentialsPreviewModal()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg font-medium transition-colors">
                                Close
                            </button>
                            <button onclick="downloadCredentialsPDF()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                                Download PDF
                            </button>
                            <button onclick="downloadCredentialsWord()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                                Download Word
                            </button>
                            <button onclick="downloadCredentialsExcel()" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors">
                                Download Excel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
