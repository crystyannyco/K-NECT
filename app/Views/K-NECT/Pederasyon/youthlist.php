<?php
    $countSummary = $count_summary ?? ['all' => 0, 'sk' => 0, 'kk' => 0];
?>
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

        /* Panzoom Controls Styles from youth_profile.php */
        .panzoom-controls {
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 20;
            background: white;
            border-radius: 8px;
            padding: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            display: flex;
            gap: 4px;
            border: 1px solid #e5e7eb;
        }
        
        .panzoom-controls button {
            width: 32px;
            height: 32px;
            border: 1px solid #e5e7eb;
            background: white;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            color: #6b7280;
        }
        
        .panzoom-controls button:hover {
            background: #f8fafc;
            border-color: #3b82f6;
            color: #3b82f6;
        }
        
        .panzoom-controls button:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }
        
        .panzoom-container {
            overflow: hidden;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            background: white;
            position: relative;
        }
        
        .panzoom-element {
            cursor: grab;
        }
        
        .panzoom-element:active {
            cursor: grabbing;
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

        /* Main table alignment */
        #myTable th,
        #myTable td {
            text-align: center;
        }

        #myTable th.name-header,
        #myTable td.name-cell {
            text-align: left;
        }

        #myTable td.action-cell {
            text-align: center;
        }

        /* Responsive cell classes */
        .name-cell {
            white-space: normal !important;
            word-wrap: break-word;
            max-width: 200px;
        }
        
        .action-cell {
            white-space: nowrap !important;
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
                                <span class="text-sm font-medium text-gray-600">SK Chairperson</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <!-- Header Section -->
                <div class="mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">SK Chairperson</h3>
                        <p class="text-sm text-gray-600 mt-1">Manage user types and credentials</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button id="downloadOfficialListBtn" onclick="openOfficialListModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
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
                
                <!-- Filter Tabs and Barangay Selector -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <!-- Role Status Tabs -->
                            <div class="flex flex-wrap gap-2">
                                <button class="status-tab bg-gray-100 px-4 py-2 rounded-lg text-sm font-medium transition-all" data-role="all">
                                    All (<span id="countAll"><?= esc($countSummary['all'] ?? 0) ?></span>)
                                </button>
                                <button class="status-tab active bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all" data-role="sk">
                                    SK Chairperson (<span id="countSK"><?= esc($countSummary['sk'] ?? 0) ?></span>)
                                </button>
                                <button class="status-tab bg-red-100 px-4 py-2 rounded-lg text-sm font-medium transition-all" data-role="kk">
                                    KK Member (<span id="countKK"><?= esc($countSummary['kk'] ?? 0) ?></span>)
                                </button>
                            </div>
                            
                            <!-- Filters Section -->
                            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                                <span class="text-sm font-medium text-gray-600">Status:</span>
                                <select id="statusFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="all">All Status (<span id="countAllStatus">0</span>)</option>
                                    <option value="approved">Approved (<span id="countApproved">0</span>)</option>
                                    <option value="pending">Pending (<span id="countPending">0</span>)</option>
                                    <option value="rejected">Rejected (<span id="countRejected">0</span>)</option>
                                </select>
                                
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
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">
                                            <input type="checkbox" id="selectAllRows" class="form-checkbox h-4 w-4 text-blue-600">
                                        </th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">User ID</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">Barangay</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-600 uppercase tracking-wider name-header">Name</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">Age</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">Sex</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">Approval Status</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">User Type</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">Action</th>
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
                                                <?php
                                                    $sk_pw = $user['sk_password'] ?? '';
                                                    $is_temp = $sk_pw && !password_get_info($sk_pw)['algo'];
                                                    $sk_pw_output = $is_temp ? esc($sk_pw) : ($sk_pw ? '******' : '');
                                                ?>
                                                data-sk_password="<?= $sk_pw_output ?>"
                                                data-ped_username="<?= isset($user['ped_username']) ? esc($user['ped_username']) : '' ?>"
                                                data-ped_password="<?= isset($user['ped_password']) ? esc($user['ped_password']) : '' ?>"
                                                data-status="<?= isset($user['status']) ? (int)$user['status'] : 1 ?>"
                                                data-user-type="<?= isset($user['user_type']) ? (int)$user['user_type'] : 1 ?>"
                                                data-user-id="<?= esc($user['id']) ?>"
                                                data-display-user-id="<?= esc($user['user_id']) ?>"
                                                data-barangay-id="<?= esc($user['barangay']) ?>">
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                                    <input type="checkbox" class="rowCheckbox form-checkbox h-4 w-4 text-blue-600" value="<?= esc($user['id']) ?>">
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 text-center"><?= esc($user['user_id']) ?></td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                                    <?= esc($user['barangay_name'] ?? $user['barangay'] ?? '') ?>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 name-cell"><?= esc($user['last_name']) ?>, <?= esc($user['first_name']) ?> <?= esc($user['middle_name']) ?></td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 text-center"><?= esc($user['age']) ?></td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 text-center"><?= $user['sex'] == '1' ? 'Male' : ($user['sex'] == '2' ? 'Female' : '') ?></td>
                                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                                    <?php
                                                    $status = isset($user['status']) ? (int)$user['status'] : 1;
                                                    $statusClass = '';
                                                    $statusText = '';
                                                    switch($status) {
                                                        case 2:
                                                            $statusClass = 'bg-green-100 text-green-800';
                                                            $statusText = 'Approved';
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
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                                    <?php
                                                        $type = isset($user['user_type']) ? (int)$user['user_type'] : 1;
                                                        echo $type == 1 ? 'KK Member' : ($type == 2 ? 'SK Chairperson' : ($type == 3 ? 'SK Chairperson' : 'Unknown'));
                                                    ?>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap action-cell text-center">
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
                                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No records found</h3>
                                                    <p class="text-gray-500">There are no user records in the database yet.</p>
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

    <!-- Bulk Change Button (hidden by default, uniform style) -->
    <button id="bulkChangeBtn"
        class="fixed bottom-8 left-1/2 transform -translate-x-1/2 z-50 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-base font-medium rounded-lg shadow-lg transition-all duration-200 flex items-center gap-2 hidden">
        Change Position for Selected
    </button>

    <!-- Bulk Change Modal -->
    <div id="bulkChangeModal" class="fixed inset-0 z-[99999] hidden bg-black bg-opacity-40 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl p-8 relative"> <!-- changed max-w-md to max-w-2xl -->
            <button id="closeBulkChangeModal" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <h3 class="text-xl font-bold text-gray-900 mb-4 text-center">Bulk Change User Position</h3>
            <div class="mb-4">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-sm text-blue-800">
                    <strong>Note:</strong> Pending users will be automatically accepted and assigned a user ID when their position is changed.
                </div>
            </div>
            <div class="mb-4">
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-3 text-sm text-orange-800">
                    <strong>SK Chairperson Restriction:</strong> Only one SK Chairperson is allowed per barangay. The system will prevent conflicts automatically.
                </div>
            </div>
            <div class="mb-4">
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 text-sm text-yellow-800">
                    <strong>Note:</strong> If a barangay already has an SK Chairperson, other users in that barangay can only be KK Members. Unavailable options are disabled.
                </div>
            </div>
            <div class="mb-6">
                <label for="bulkNewPosition" class="block text-sm font-medium text-gray-700 mb-2">Select New Position</label>
                <select id="bulkNewPosition" class="w-full border border-gray-300 rounded-md px-2 py-2 text-base focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    <option value="1">KK Member</option>
                    <option value="2">SK Chairperson</option>
                </select>
                <div id="bulkRoleDynamicNote" class="hidden mt-2 text-sm text-orange-600 bg-orange-50 border border-orange-200 rounded-lg p-2"></div>
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
                        </select>
                        
                        <!-- Hidden fields for storing user data -->
                        <input type="hidden" id="modalUserBarangayId" value="">
                        <input type="hidden" id="modalUserStatusValue" value="">
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
        // Snapshot of youth records for client-side utilities (keeps modals independent of table filters)
        const youthUserList = <?= json_encode($user_list ?? []) ?>;

        function escapeHtml(value) {
            if (value === null || value === undefined) {
                return '';
            }
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        // Helper function to get barangay name
        function getBarangayName(barangayId) {
            return barangayMap[barangayId] || barangayId || '';
        }
        
        // Utility function to show notifications
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `stacked-toast fixed right-4 z-[99999] p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
            
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
            
            // Calculate stacking position based on existing notifications
            const existingToasts = document.querySelectorAll('.stacked-toast');
            let topOffset = 16; // Initial top offset (1rem = 16px)
            existingToasts.forEach(toast => {
                topOffset += toast.offsetHeight + 16; // Add height + 16px gap
            });
            notification.style.top = topOffset + 'px';
            
            // Get appropriate icon based on type
            let icon = '';
            switch(type) {
                case 'success':
                    icon = '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>';
                    break;
                case 'error':
                    icon = '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z" /></svg>';
                    break;
                case 'warning':
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
                    <button onclick="this.parentElement.parentElement.remove(); repositionToasts();" class="ml-2 text-white hover:text-gray-200 focus:outline-none">
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
                        repositionToasts();
                    }
                }, 300);
            }, 5000);
        }
        
        // Helper function to reposition remaining toasts after one is removed
        function repositionToasts() {
            const toasts = document.querySelectorAll('.stacked-toast');
            let topOffset = 16;
            toasts.forEach(toast => {
                toast.style.top = topOffset + 'px';
                topOffset += toast.offsetHeight + 16;
            });
        }

        function isHashedSkPassword(password) {
            if (!password) return false;
            const value = String(password);
            return value.startsWith('$2y$') || value.startsWith('$2b$') || value.startsWith('$argon2') || value.length > 40;
        }

        function formatSkPasswordForDisplay(password) {
            if (!password) return 'Not generated';
            return isHashedSkPassword(password) ? '********' : String(password);
        }

        function pedSanitizePasswordForDataAttr(password) {
            if (!password) return '';
            return isHashedSkPassword(password) ? '******' : String(password);
        }

        function pedComputeStatusBadge(status) {
            const resolved = parseInt(status, 10);
            if (resolved === 2) {
                return { text: 'Approved', className: 'bg-green-100 text-green-800' };
            }
            if (resolved === 3) {
                return { text: 'Rejected', className: 'bg-red-100 text-red-800' };
            }
            return { text: 'Pending', className: 'bg-yellow-100 text-yellow-800' };
        }

        function pedRenderStatusCellHtml(status) {
            const meta = pedComputeStatusBadge(status);
            return `<span class="px-2 py-1 rounded-full text-sm font-medium ${meta.className}">${meta.text}</span>`;
        }

        function pedResolveUserTypeLabel(userType) {
            const resolved = parseInt(userType, 10);
            if (resolved === 2 || resolved === 3) return 'SK Chairperson';
            if (resolved === 1) return 'KK Member';
            return 'Unknown';
        }

        // Store original counts globally
        let originalCounts = <?php
            $initialCounts = [
                'all' => (int) ($countSummary['all'] ?? 0),
                'sk' => (int) ($countSummary['sk'] ?? 0),
                'kk' => (int) ($countSummary['kk'] ?? 0),
                'approved' => 0,
                'pending' => 0,
                'rejected' => 0,
            ];
            echo json_encode($initialCounts, JSON_UNESCAPED_UNICODE);
        ?>;
        const barangayList = <?php echo json_encode(array_values(\App\Libraries\BarangayHelper::getBarangayMap()), JSON_UNESCAPED_UNICODE); ?>;
        
        let table;

    $(document).ready(function () {
            // Remove placeholder row if present to prevent DataTables column errors when empty
            (function ensureConsistentCellsForDataTables() {
                const $table = $('#myTable');
                const headerCount = $table.find('thead th').length;
                $table.find('tbody tr').each(function () {
                    const cellCount = $(this).find('td').length;
                    if (cellCount < headerCount) {
                        $(this).remove();
                    }
                });
            })();

            // DataTable and tab logic
            table = $('#myTable').DataTable({
                columnDefs: [
                    { orderable: false, targets: 0 },
                    { responsivePriority: 1, targets: 7 }, // Actions
                    { responsivePriority: 2, targets: 2 }, // Full Name
                    { responsivePriority: 3, targets: 1 }, // User ID
                    { responsivePriority: 4, targets: 5 }, // Position
                    { responsivePriority: 5, targets: 3 }, // Barangay
                    { responsivePriority: 6, targets: 4 }, // Age
                    { responsivePriority: 7, targets: 6 }  // Status
                ],
                order: [[1, 'asc']],
                responsive: true,
                autoWidth: false,
                paging: true,
                pageLength: 25,
                info: true,
                searching: true,
                language: {
                    search: "Search all records:",
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
                        updateDisplayedCounts();
                        restoreFilters();
                    }, 100);
                }
            });

            table.on('draw.dt', updateStatusCountsForFilteredData);
            updateStatusCountsForFilteredData();

            // Populate barangay filter
            function populateBarangayFilter() {
                const barangaySet = new Set(Array.isArray(barangayList) ? barangayList : []);

                $('#myTable tbody tr').each(function() {
                    const barangay = $(this).find('td').eq(2).text().trim();
                    if (barangay) {
                        barangaySet.add(barangay);
                    }
                });

                const sortedBarangays = Array.from(barangaySet).sort((a, b) =>
                    a.localeCompare(b, undefined, { sensitivity: 'base' })
                );

                const $filter = $('#barangayFilter');
                $filter.empty();
                $filter.append($('<option>', { value: '', text: 'All Barangays' }));
                sortedBarangays.forEach(name => {
                    $filter.append($('<option>', { value: name, text: name }));
                });
            }

            // Update displayed counts for role tabs
            function updateDisplayedCounts() {
                $('#countAll').text(originalCounts.all);
                $('#countSK').text(originalCounts.sk);
                $('#countKK').text(originalCounts.kk);
            }

            // Update status counts based on current table filters
            function updateStatusCountsForFilteredData() {
                if (!table) {
                    return;
                }

                const filteredCounts = {
                    total: 0,
                    approved: 0,
                    pending: 0,
                    rejected: 0
                };

                table.rows({ filter: 'applied' }).every(function() {
                    const $row = $(this.node());
                    const status = Number.parseInt($row.data('status'), 10);
                    filteredCounts.total++;

                    if (status === 2) {
                        filteredCounts.approved++;
                    } else if (status === 3) {
                        filteredCounts.rejected++;
                    } else {
                        filteredCounts.pending++;
                    }
                });

                const $countAllStatus = $('#countAllStatus');
                if ($countAllStatus.length) {
                    $countAllStatus.text(filteredCounts.total);
                }
                const $countApproved = $('#countApproved');
                if ($countApproved.length) {
                    $countApproved.text(filteredCounts.approved);
                }
                const $countPending = $('#countPending');
                if ($countPending.length) {
                    $countPending.text(filteredCounts.pending);
                }
                const $countRejected = $('#countRejected');
                if ($countRejected.length) {
                    $countRejected.text(filteredCounts.rejected);
                }

                $('#statusFilter option[value="all"]').text(`All Status (${filteredCounts.total})`);
                $('#statusFilter option[value="approved"]').text(`Approved (${filteredCounts.approved})`);
                $('#statusFilter option[value="pending"]').text(`Pending (${filteredCounts.pending})`);
                $('#statusFilter option[value="rejected"]').text(`Rejected (${filteredCounts.rejected})`);
            }

            // Update role counts using persisted totals (retained for compatibility)
            function updateRoleCounts() {
                // Always show original counts, regardless of current filter
                updateDisplayedCounts();
            }

            // Role tab filtering logic
            function setActiveRoleTab(tab) {
                $('.status-tab[data-role]').removeClass('active bg-blue-500 text-white')
                    .addClass('bg-gray-100');
                $('.status-tab[data-role="sk"]').removeClass('bg-gray-100').addClass('bg-yellow-100');
                $('.status-tab[data-role="kk"]').removeClass('bg-gray-100').addClass('bg-red-100');
                
                tab.removeClass('bg-gray-100 bg-yellow-100 bg-red-100')
                    .addClass('active bg-blue-500 text-white');
            }

            // Apply filters with DataTable integration
            function applyFilters() {
                const roleFilter = $('.status-tab[data-role].active').data('role');
                const statusFilter = $('#statusFilter').val();
                const barangayFilter = $('#barangayFilter').val();
                
                // Clear existing DataTable search
                table.search('').columns().search('');
                
                // Apply role filter using DataTable column search
                if (roleFilter !== 'all') {
                    let regex = '';
                    if (roleFilter === 'sk') {
                        regex = '^(SK Chairperson)$';
                    } else if (roleFilter === 'kk') {
                        regex = '^(KK Member)$';
                    }
                    if (regex) {
                        table.column(7).search(regex, true, false);
                    }
                }
                
                // Apply status filter using DataTable column search
                if (statusFilter !== 'all') {
                    let statusSearchTerm = '';
                    if (statusFilter === 'approved') {
                        statusSearchTerm = 'Approved';
                    } else if (statusFilter === 'pending') {
                        statusSearchTerm = 'Pending';
                    } else if (statusFilter === 'rejected') {
                        statusSearchTerm = 'Rejected';
                    }
                    
                    if (statusSearchTerm) {
                        table.column(6).search('^' + statusSearchTerm + '$', true, false);
                    }
                }
                
                // Apply barangay filter using DataTable column search
                if (barangayFilter) {
                    table.column(2).search('^' + barangayFilter + '$', true, false);
                }
                
                // Redraw table with filters applied
                table.draw();
                
                // Keep role counts static; status totals update via the draw event
                updateDisplayedCounts();
            }

            // Role tab click handlers
            $('.status-tab[data-role]').on('click', function() {
                setActiveRoleTab($(this));
                applyFilters();
                localStorage.setItem('activeRoleTab', $(this).data('role'));
            });

            // Status dropdown change handler
            $('#statusFilter').on('change', function() {
                applyFilters();
                localStorage.setItem('activeStatusFilter', $(this).val());
            });

            // Barangay filter change handler
            $('#barangayFilter').on('change', function() {
                applyFilters();
                localStorage.setItem('activeBarangayFilter', $(this).val());
            });

            // Clear filters
            $('#clearFilters').on('click', function() {
                $('.status-tab[data-role="sk"]').trigger('click'); // Changed from 'all' to 'sk'
                $('#statusFilter').val('all');
                $('#barangayFilter').val('');
                localStorage.removeItem('activeRoleTab');
                localStorage.removeItem('activeStatusFilter');
                localStorage.removeItem('activeBarangayFilter');
                updateDisplayedCounts();
                showNotification('Filters cleared successfully', 'success');
            });

            // Function to restore saved filters
            function restoreFilters() {
                const savedRoleTab = localStorage.getItem('activeRoleTab') || 'sk'; // Default to 'sk' instead of 'all'
                const savedStatusFilter = localStorage.getItem('activeStatusFilter') || 'all';
                const savedBarangayFilter = localStorage.getItem('activeBarangayFilter') || '';
                
                $('.status-tab[data-role="' + savedRoleTab + '"]').trigger('click');
                $('#statusFilter').val(savedStatusFilter);
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
                // Update bulk dropdown based on current selection
                pedUpdateBulkRoleOptions();
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
                // If chosen option is disabled, block without notification
                if ($('#bulkNewPosition option:selected').is(':disabled')) {
                    return;
                }
                
                // Show loading state
                const $btn = $(this);
                $btn.prop('disabled', true).text('Checking...');
                
                // Check SK Chairperson restrictions before proceeding
                checkBulkSKChairmanRestriction(selectedIds).then(function(canProceed) {
                    if (!canProceed) {
                        $btn.prop('disabled', false).text('Confirm');
                        return;
                    }
                    
                    $btn.text('Updating...');
                    
                    // AJAX request to bulk update
                    $.ajax({
                        url: '/bulkUpdateUserType',
                        method: 'POST',
                        data: { user_ids: selectedIds, user_type: newType },
                        success: function(response) {
                            if (response && response.success) {
                                showNotification('User positions updated successfully! Pending users have been automatically accepted.', 'success');
                                // Close modal and set post-reload prompt flag
                                try { $('#bulkChangeModal').addClass('hidden').css('display', 'none'); } catch (e) {}
                                try { if (window.localStorage) localStorage.setItem('knect_show_credentials_prompt', '1'); } catch (e) {}
                                setTimeout(() => { location.reload(); }, 600);
                            } else {
                                showNotification(response.message || 'Failed to update user positions.', 'error');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Bulk update error:', error);
                            showNotification('Failed to update user positions. Please try again.', 'error');
                        },
                        complete: function() {
                            $btn.prop('disabled', false).text('Confirm');
                            $('#bulkChangeModal').addClass('hidden').css('display', 'none');
                        }
                    });
                }).catch(function() {
                    $btn.prop('disabled', false).text('Confirm');
                    showNotification('Error checking SK Chairperson restrictions. Please try again.', 'error');
                });
            });

            // Update bulk role options whenever the select changes
            $('#bulkNewPosition').on('change', function() {
                pedUpdateBulkRoleOptions();
            });

            // User View Modal functionality (SK-style: info left, documents right)
            $(document).on('click', '.view-user-btn', function(e) {
                e.preventDefault();
                var userId = $(this).data('id');
                // Open the SK-style view modal
                if (typeof openViewModal === 'function') {
                    openViewModal(userId);
                } else {
                    console.error('openViewModal function not found'); // Debug log
                }
            });

            // SK-style View Modal (info left, documents right)
            let pedPanzoomInstances = {};

            function pedCleanupPanzoom(containerId) {
                try {
                    if (pedPanzoomInstances[containerId]) {
                        // fancyapps Panzoom instance
                        if (typeof pedPanzoomInstances[containerId].destroy === 'function') {
                            pedPanzoomInstances[containerId].destroy();
                        }
                        delete pedPanzoomInstances[containerId];
                    }
                } catch (e) {}
            }

            function pedInitPanzoom(containerEl) {
                try {
                    if (!containerEl) return;
                    // Only initialize if both Panzoom and Controls plugins are present
                    if (!window.Panzoom || !window.Controls) return;
                    const instance = window.Panzoom(containerEl, {
                        Controls: {
                            display: [
                                'zoomIn',
                                'zoomOut',
                                'toggle1to1',
                                'rotateCCW',
                                'rotateCW',
                                'flipX',
                                'flipY',
                                'reset'
                            ]
                        }
                    }, { Controls: window.Controls });
                    if (instance && typeof instance.init === 'function') instance.init();
                    pedPanzoomInstances[containerEl.id] = instance;
                } catch (e) {
                    // ignore if panzoom not available or init fails
                }
            }

            function buildDocPreviewHtml(birthCertFile, uploadIdFile, uploadIdBackFile) {
                let html = '';
                if (birthCertFile) {
                    const url = '<?= base_url('/previewDocument/certificate/') ?>' + birthCertFile;
                    const ext = birthCertFile.split('.').pop().toLowerCase();
                    html += `<div class="w-full border border-gray-200 rounded-lg bg-gray-50 p-4">
                        <div class='font-semibold text-gray-700 mb-2'>Birth Certificate</div>
                        <div class='relative w-full'>`;
                    if (['pdf'].includes(ext)) {
                        html += `<iframe src='${url}' style='width: 100%; height: 600px;' class='rounded border' frameborder='0'></iframe>`;
                    } else if (['jpg','jpeg','png','gif','webp'].includes(ext)) {
                        html += `<div id='pedCertPreviewWrapper' class='f-panzoom' style='max-height: 600px;'>
                            <img id='pedModalPreviewImgCert' class='f-panzoom__content rounded' src='${url}' alt='Birth Certificate Image' style='width: 100%; height: auto; display: block;'>
                        </div>`;
                    } else {
                        html += `<div class='text-red-600 p-4'>Cannot preview this file type.</div>`;
                    }
                    html += `</div></div>`;
                }
                if (uploadIdFile) {
                    const url = '<?= base_url('/previewDocument/id/') ?>' + uploadIdFile;
                    const ext = uploadIdFile.split('.').pop().toLowerCase();
                    html += `<div class="w-full border border-gray-200 rounded-lg bg-gray-50 p-4">
                        <div class='font-semibold text-gray-700 mb-2'>User ID</div>
                        <div class='relative w-full'>`;
                    if (['pdf'].includes(ext)) {
                        html += `<iframe src='${url}' style='width: 100%; height: 600px;' class='rounded border' frameborder='0'></iframe>`;
                    } else if (['jpg','jpeg','png','gif','webp'].includes(ext)) {
                        html += `<div id='pedIdPreviewWrapper' class='f-panzoom' style='max-height: 600px;'>
                            <img id='pedModalPreviewImgId' class='f-panzoom__content rounded' src='${url}' alt='ID Image' style='width: 100%; height: auto; display: block;'>
                        </div>`;
                    } else {
                        html += `<div class='text-red-600 p-4'>Cannot preview this file type.</div>`;
                    }
                    html += `</div></div>`;
                }
                if (uploadIdBackFile) {
                    const urlBack = '<?= base_url('/previewDocument/id/') ?>' + uploadIdBackFile;
                    const extBack = uploadIdBackFile.split('.').pop().toLowerCase();
                    html += `<div class="w-full border border-gray-200 rounded-lg bg-gray-50 p-4">
                        <div class='font-semibold text-gray-700 mb-2'>ID (Back)</div>
                        <div class='relative w-full'>`;
                    if (['pdf'].includes(extBack)) {
                        html += `<iframe src='${urlBack}' style='width: 100%; height: 600px;' class='rounded border' frameborder='0'></iframe>`;
                    } else if (['jpg','jpeg','png','gif','webp'].includes(extBack)) {
                        html += `<div id='pedIdPreviewWrapperBack' class='f-panzoom' style='max-height: 600px;'>
                            <img id='pedModalPreviewImgIdBack' class='f-panzoom__content rounded' src='${urlBack}' alt='ID Back Image' style='width: 100%; height: auto; display: block;'>
                        </div>`;
                    } else {
                        html += `<div class='text-red-600 p-4'>Cannot preview this file type.</div>`;
                    }
                    html += `</div></div>`;
                }
                if (!birthCertFile && !uploadIdFile && !uploadIdBackFile) {
                    html = `<div class='text-red-600 p-4'>No birth certificate or ID uploaded for this user.</div>`;
                }
                return html;
            }

            function openViewModal(userId) {
                // Fetch user info and then open modal
                $.ajax({
                    url: '/getUserInfo',
                    method: 'POST',
                    data: { user_id: userId },
                    success: function(response) {
                        if (!response || !response.success || !response.user) {
                            showNotification('User not found.', 'error');
                            return;
                        }
                        var u = response.user;
                        // Mappings
                        var civilStatusMap = <?= json_encode($field_mappings['civilStatusMap'] ?? []) ?>;
                        var youthClassificationMap = <?= json_encode($field_mappings['youthClassificationMap'] ?? []) ?>;
                        var ageGroupMap = <?= json_encode($field_mappings['ageGroupMap'] ?? []) ?>;
                        var workStatusMap = <?= json_encode($field_mappings['workStatusMap'] ?? []) ?>;
                        var educationMap = <?= json_encode($field_mappings['educationMap'] ?? []) ?>;
                        var howManyTimesMap = <?= json_encode($field_mappings['howManyTimesMap'] ?? []) ?>;
                        var noWhyMap = <?= json_encode($field_mappings['noWhyMap'] ?? []) ?>;

                        var fullName = (u.first_name || '') + ' ' + (u.middle_name ? u.middle_name + ' ' : '') + (u.last_name || '') + (u.suffix ? ', ' + u.suffix : '');
                        $('#pedModalUserFullName').text(fullName.trim());
                        $('#pedModalUserName').text(fullName.trim());
                        var barangayStr = u.barangay_name || u.barangay || '';
                        $('#pedModalUserBarangay').text(barangayStr);
                        $('#pedModalUserBarangayDetail').text(barangayStr);
                        $('#pedModalUserId').text(u.user_id || '-');
                        // Hidden fields for restrictions and update call
                        $('#pedModalUserBarangayId').val(u.barangay || '');
                        $('#pedModalUserStatusValue').val(u.status || '');
                        $('#pedModalUserType').val(u.user_type || '');
                        // Set the role select to current type for UX
                        $('#pedRoleSelect').val(String(u.user_type || 1));
                        // Store display and DB IDs
                        $('#pedModalDisplayUserId').val(u.user_id || '');
                        $('#pedModalDbId').val(u.id || '');
                        $('#pedModalUserAge').text((u.age ? u.age : '') + (u.age ? ' years old' : ''));
                        $('#pedModalUserSex').text(u.sex == '1' ? 'Male' : (u.sex == '2' ? 'Female' : ''));
                        $('#pedModalUserEmail').text(u.email || '');
                        if (u.birthdate) {
                            const dateObj = new Date(u.birthdate);
                            if (!Number.isNaN(dateObj.getTime())) {
                                const month = dateObj.toLocaleString('en-US', { month: 'short' });
                                const day = String(dateObj.getDate()).padStart(2, '0');
                                const year = dateObj.getFullYear();
                                $('#pedModalUserBirthday').text(`${month} ${day}, ${year}`);
                            } else {
                                $('#pedModalUserBirthday').text(u.birthdate);
                            }
                        } else {
                            $('#pedModalUserBirthday').text('');
                        }
                        $('#pedModalUserCivilStatus').text(civilStatusMap[u.civil_status] || '');
                        let statusText = '', statusClass = '';
                        if (u.status == 1) { statusText = 'Pending'; statusClass = 'bg-yellow-100 text-yellow-800'; }
                        else if (u.status == 2) { statusText = 'Accepted'; statusClass = 'bg-green-100 text-green-800'; }
                        else if (u.status == 3) { statusText = 'Rejected'; statusClass = 'bg-red-100 text-red-800'; }
                        $('#pedModalUserStatus').text(statusText).removeClass().addClass('inline-flex px-2 py-1 rounded-full text-sm font-medium ' + statusClass);
                        $('#pedModalUserZone').text(u.zone_purok || '');
                        var addressParts = [];
                        if (u.zone_purok) addressParts.push(u.zone_purok);
                        if (barangayStr) addressParts.push(barangayStr);
                        addressParts.push('Iriga City');
                        addressParts.push('Camarines Sur');
                        addressParts.push('Region 5');
                        $('#pedModalUserAddress').text(addressParts.join(', '));
                        $('#pedModalUserYouthClassification').text(youthClassificationMap[u.youth_classification] || '');
                        $('#pedModalUserWorkStatus').text(workStatusMap[u.work_status] || '');
                        $('#pedModalUserYouthAgeGroup').text(ageGroupMap[u.age_group] || '');
                        $('#pedModalUserEducation').text(educationMap[u.educational_background] || '');
                        function setYesNoColor(selector, value) {
                            let text = '', colorClass = '';
                            if (String(value) === '1') { text = 'Yes'; colorClass = 'bg-green-100 text-green-800'; }
                            else if (String(value) === '0') { text = 'No'; colorClass = 'bg-red-100 text-red-800'; }
                            else { text = ''; colorClass = 'bg-yellow-100 text-yellow-800'; }
                            $(selector).text(text).removeClass().addClass('inline-flex px-2 py-1 rounded-full text-sm font-medium ' + colorClass);
                        }
                        setYesNoColor('#pedModalUserSKVoter', u.sk_voter);
                        setYesNoColor('#pedModalUserVotedSK', u.sk_election);
                        setYesNoColor('#pedModalUserNationalVoter', u.national_voter);
                        setYesNoColor('#pedModalUserAttendedAssembly', u.kk_assembly);
                        $('#pedModalUserAssemblyTimes').text(howManyTimesMap[u.how_many_times] || '');
                        $('#pedModalUserAssemblyReason').text(noWhyMap[u.no_why] || '');
                        // Profile picture with fallback
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
                            const $img = $('#pedModalUserPhoto');
                            $img.off('error').on('error', function(){ this.onerror=null; this.src = defaultAvatar; $(this).show(); });
                            $img.attr('src', imgUrl).show();
                        })();

                        // Documents from user object (if available)
                        const birthCertFile = u.birth_certificate || '';
                        const uploadIdFile = u.upload_id || '';
                        const uploadIdBackFile = u['upload_id-back'] || '';
                        const docHtml = buildDocPreviewHtml(birthCertFile, uploadIdFile, uploadIdBackFile);
                        document.getElementById('pedModalDocPreview').innerHTML = docHtml;

                        // Show modal
                        $('#pedPreviewModal').css('display', 'flex');

                        // Initialize panzoom for images after DOM updated
                        setTimeout(() => {
                            if (birthCertFile && ['jpg','jpeg','png','gif','webp'].includes(birthCertFile.split('.').pop().toLowerCase())) {
                                const el = document.getElementById('pedCertPreviewWrapper');
                                if (el) pedInitPanzoom(el);
                            }
                            if (uploadIdFile && ['jpg','jpeg','png','gif','webp'].includes(uploadIdFile.split('.').pop().toLowerCase())) {
                                const el = document.getElementById('pedIdPreviewWrapper');
                                if (el) pedInitPanzoom(el);
                            }
                            if (uploadIdBackFile && ['jpg','jpeg','png','gif','webp'].includes(uploadIdBackFile.split('.').pop().toLowerCase())) {
                                const el = document.getElementById('pedIdPreviewWrapperBack');
                                if (el) pedInitPanzoom(el);
                            }
                        }, 100);

                        // Apply local role rules to ped modal select
                        (function(){
                            const dbId = u.id || '';
                            const displayUserId = u.user_id || '';
                            const currentType = parseInt(u.user_type || 0, 10);
                            const barangayId = u.barangay;
                            const userIdForCheck = dbId || displayUserId;
                            pedApplyPerUserRoleRules('#pedRoleSelect', barangayId, userIdForCheck, currentType, '#pedSkChairmanNote');
                        })();
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', { xhr, status, error }); // Debug log
                        console.error('Response Text:', xhr.responseText); // Debug log
                        showNotification('Failed to fetch user info.', 'error');
                    }
                });
            }

            function closePedPreviewModal() {
                try {
                    $('#pedPreviewModal').css('display', 'none');
                    pedCleanupPanzoom('pedCertPreviewWrapper');
                    pedCleanupPanzoom('pedIdPreviewWrapper');
                    pedCleanupPanzoom('pedIdPreviewWrapperBack');
                    const infoIds = [
                        'pedModalUserFullName','pedModalUserName','pedModalUserBarangay','pedModalUserBarangayDetail','pedModalUserId',
                        'pedModalUserSex','pedModalUserEmail','pedModalUserBirthday','pedModalUserAge','pedModalUserCivilStatus','pedModalUserStatus',
                        'pedModalUserZone','pedModalUserAddress','pedModalUserYouthClassification','pedModalUserWorkStatus','pedModalUserYouthAgeGroup',
                        'pedModalUserEducation','pedModalUserSKVoter','pedModalUserVotedSK','pedModalUserNationalVoter','pedModalUserAttendedAssembly',
                        'pedModalUserAssemblyTimes','pedModalUserAssemblyReason'
                    ];
                    infoIds.forEach(id => {
                        const el = document.getElementById(id);
                        if (el) el.textContent = '';
                    });
                    const doc = document.getElementById('pedModalDocPreview');
                    if (doc) doc.innerHTML = '';
                } catch (e) {}
            }

            // Expose functions globally so inline onclick works reliably
            window.openViewModal = openViewModal;
            window.closePedPreviewModal = closePedPreviewModal;

            // Close on backdrop click
            $('#pedPreviewModal').on('click', function(e) {
                if (e.target === this) {
                    closePedPreviewModal();
                }
            });

            // Close on Escape key
            document.addEventListener('keydown', function(e) {
                const modal = document.getElementById('pedPreviewModal');
                if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
                    closePedPreviewModal();
                }
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
                $('#roleChangeModal').removeClass('hidden').css('display', 'flex');
                // Ensure it's above modal content
            });

            // Confirm role change
            $('#confirmRoleChangeBtn').on('click', function() {
                const userId = pendingUserTypeChange.userId;
                const newType = pendingUserTypeChange.newType;
                const $confirmBtn = $(this);

                $confirmBtn.prop('disabled', true).text('Updating...');

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
                            showNotification('User type updated successfully! Email notification sent.', 'success');
                            try { if (window.localStorage) localStorage.setItem('knect_show_credentials_prompt', '1'); } catch (e) {}
                            try {
                                $('#roleChangeModal').addClass('hidden').css('display', 'none');
                                $('#userDetailModal').addClass('hidden');
                            } catch (e) {}
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showNotification((response && response.message) ? response.message : 'Failed to update user type.', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('User type update error:', error);
                        const msg = (xhr && xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Failed to update user type. Please try again.';
                        showNotification(msg, 'error');
                    },
                    complete: function() {
                        $confirmBtn.prop('disabled', false).text('Confirm');
                    }
                });
            });

            // Cancel role change
            $('#cancelRoleChangeBtn').on('click', function() {
                $('#roleChangeModal').addClass('hidden').css('display', 'none');
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
                const modal = document.getElementById('officialListModal');
                modal.style.display = 'flex';
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
            const modal = document.getElementById('officialListModal');
            modal.style.display = 'none';
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
            const loadingEl = document.getElementById('officialListLoading');
            const contentEl = document.getElementById('officialListContent');
            loadingEl.classList.remove('hidden');
            contentEl.classList.add('hidden');

            const source = Array.isArray(youthUserList) ? youthUserList : [];
            const officials = source
                .filter(user => {
                    const status = Number(user.status ?? user.status_value ?? 0);
                    const userType = Number(user.user_type ?? 0);
                    const position = Number(user.position ?? 0);
                    const isSkClassification = userType === 2 || userType === 3 || position === 1;
                    return status === 2 && isSkClassification;
                })
                .map(user => {
                    const barangayName = user.barangay_name || getBarangayName(user.barangay) || '';
                    const safeUserId = (user.user_id && String(user.user_id).trim() !== '') ? user.user_id : '';
                    const fullNameParts = [user.last_name, user.first_name, user.middle_name]
                        .map(part => (part ? String(part).trim() : ''));
                    const formattedName = [fullNameParts[0], [fullNameParts[1], fullNameParts[2]].filter(Boolean).join(' ')].filter(Boolean).join(', ');
                    const sexValue = String(user.sex ?? '').trim();
                    const resolvedSex = sexValue === '1' ? 'Male' : (sexValue === '2' ? 'Female' : (sexValue || ''));
                    let birthday = 'N/A';
                    if (user.birthdate) {
                        const parsed = new Date(user.birthdate);
                        if (!Number.isNaN(parsed.getTime()) && parsed.getFullYear() > 1900) {
                            const month = parsed.toLocaleString('en-US', { month: 'short' });
                            const day = String(parsed.getDate()).padStart(2, '0');
                            birthday = `${month} ${day}, ${parsed.getFullYear()}`;
                        }
                    }

                    return {
                        userId: safeUserId,
                        barangay: barangayName,
                        name: formattedName,
                        age: user.age ?? '',
                        birthday,
                        sex: resolvedSex,
                        position: 'SK Chairperson'
                    };
                })
                .sort((a, b) => {
                    const barangayCompare = a.barangay.localeCompare(b.barangay);
                    if (barangayCompare !== 0) {
                        return barangayCompare;
                    }
                    return a.name.localeCompare(b.name);
                });

            loadingEl.classList.add('hidden');
            contentEl.classList.remove('hidden');

            if (officials.length > 0) {
                displayOfficialList(officials);
                document.getElementById('noOfficials').classList.add('hidden');
            } else {
                document.getElementById('noOfficials').classList.remove('hidden');
                document.getElementById('officialListTableBody').innerHTML = '';
            }

            document.getElementById('officialListCount').textContent = `Total Officials: ${officials.length}`;
        }
        
        // Display officials in table
        function displayOfficialList(officials) {
            const tbody = document.getElementById('officialListTableBody');
            tbody.innerHTML = '';
            
            officials.forEach(official => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-blue-50 transition-colors';
                
                row.innerHTML = `
                    <td class="border border-gray-300 text-center py-3 px-4 text-sm text-gray-900">
                        ${escapeHtml(official.userId)}
                    </td>
                    <td class="border border-gray-300 text-left py-3 px-4 text-sm text-gray-900">
                        ${escapeHtml(official.name)}
                    </td>
                    <td class="border border-gray-300 text-center py-3 px-4 text-sm text-gray-900">
                        ${escapeHtml(official.barangay)}
                    </td>
                    <td class="border border-gray-300 text-center py-3 px-4 text-sm text-gray-900">
                        ${escapeHtml(official.sex)}
                    </td>
                    <td class="border border-gray-300 text-center py-3 px-4 text-sm text-gray-900">
                        ${escapeHtml(official.age)}
                    </td>
                    <td class="border border-gray-300 text-center py-3 px-4 text-sm text-gray-900">
                        ${escapeHtml(official.birthday)}
                    </td>
                    <td class="border border-gray-300 text-center py-3 px-4 text-sm text-gray-900">
                        ${escapeHtml(official.position)}
                    </td>
                `;
                
                tbody.appendChild(row);
            });
            
            // Load logos
            loadBarangayLogo();
        }
        
        // Load system logos
        function loadBarangayLogo(barangayName) {
            // Fetch logos from the API
            fetch('<?= base_url('documents/logos') ?>')
                .then(response => response.json())
                .then(data => {
                    if (data && data.success && data.data) {
                        const logos = data.data;
                        
                        // Load City Federation logo
                        const pederasyonLogoDiv = document.getElementById('official-list-pederasyon-logo');
                        if (logos.pederasyon && logos.pederasyon.file_path && pederasyonLogoDiv) {
                            pederasyonLogoDiv.innerHTML = `<img src="<?= base_url() ?>${logos.pederasyon.file_path}" alt="City Federation Logo" class="w-full h-full object-contain">`;
                        }
                        
                        // Load Iriga City logo
                        const irigaLogoDiv = document.getElementById('official-list-iriga-logo');
                        if (logos.iriga_city && logos.iriga_city.file_path && irigaLogoDiv) {
                            irigaLogoDiv.innerHTML = `<img src="<?= base_url() ?>${logos.iriga_city.file_path}" alt="Iriga City Logo" class="w-full h-full object-contain">`;
                        }
                    } else {
                        console.error('Failed to load logos:', data ? data.message : 'No data');
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
        async function downloadOfficialListPDF() {
            // Show loading state
            const button = event.target;
            const originalHTML = button.innerHTML;
            button.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Generating PDF...';
            button.disabled = true;
            
            try {
                // Fetch logos from API
                const logosResp = await fetch('<?= base_url('documents/logos') ?>');
                const logosJson = (logosResp.ok ? await logosResp.json() : { success: false, data: {} });
                const logos = (logosJson && logosJson.success && logosJson.data) ? logosJson.data : {};

                const pederasyonLogoPath = (logos.pederasyon?.file_path) || '';
                const irigaLogoPath = (logos.iriga_city?.file_path) || '';
                const pederasyonLogoUrl = pederasyonLogoPath ? '<?= base_url() ?>' + pederasyonLogoPath : '';
                const irigaLogoUrl = irigaLogoPath ? '<?= base_url() ?>' + irigaLogoPath : '';

                // Helper function to convert image URL to data URL
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
                
                // Helper function to determine image format
                const getImgFmt = (dataUrl) => {
                    if (!dataUrl) return 'PNG';
                    if (dataUrl.includes('image/jpeg') || dataUrl.includes('image/jpg')) return 'JPEG';
                    if (dataUrl.includes('image/png')) return 'PNG';
                    return 'PNG'; // default
                };
                
                // Load logos as data URLs
                const [pederasyonLogoData, irigaLogoData] = await Promise.all([
                    imageUrlToDataUrl(pederasyonLogoUrl),
                    imageUrlToDataUrl(irigaLogoUrl)
                ]);
                
                // Initialize PDF
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF('l', 'mm', 'a4'); // landscape orientation
                
                generatePDFWithLogos(doc, pederasyonLogoData, irigaLogoData, button, originalHTML);
            } catch (error) {
                console.error('Error generating PDF:', error);
                showNotification('Error generating PDF. Please try again.', 'error');
                button.innerHTML = originalHTML;
                button.disabled = false;
            }
        }
        
        function generatePDFWithLogos(doc, pederasyonLogoData, irigaLogoData, button, originalHTML) {
            const pageWidth = doc.internal.pageSize.getWidth();
            const pageHeight = doc.internal.pageSize.getHeight();
            const centerX = pageWidth / 2;
            const tableWidth = 190;
            const headerTop = 18;
            const contentTop = 70;
            const bottomMargin = 25;
            const horizontalMargin = (pageWidth - tableWidth) / 2;

            const getImgFmt = (dataUrl) => {
                if (!dataUrl) return 'PNG';
                if (dataUrl.includes('image/jpeg') || dataUrl.includes('image/jpg')) return 'JPEG';
                if (dataUrl.includes('image/png')) return 'PNG';
                return 'PNG';
            };

            const drawHeader = () => {
                const logoSize = 25;
                const leftLogoX = 40;
                const rightLogoX = pageWidth - 40 - logoSize;
                if (pederasyonLogoData) {
                    doc.addImage(pederasyonLogoData, getImgFmt(pederasyonLogoData), leftLogoX, headerTop, logoSize, logoSize, undefined, 'FAST');
                }
                if (irigaLogoData) {
                    doc.addImage(irigaLogoData, getImgFmt(irigaLogoData), rightLogoX, headerTop, logoSize, logoSize, undefined, 'FAST');
                }

                doc.setFont('helvetica', 'bold');
                doc.setFontSize(12);
                doc.text('REPUBLIC OF THE PHILIPPINES', centerX, headerTop, { align: 'center' });
                doc.text('PROVINCE OF CAMARINES SUR', centerX, headerTop + 5, { align: 'center' });
                doc.text('CITY OF IRIGA', centerX, headerTop + 10, { align: 'center' });

                doc.setFont('helvetica', 'normal');
                doc.setFontSize(10);
                doc.text('PANLUNGSOD NA PEDERASYON NG MGA', centerX, headerTop + 16, { align: 'center' });
                doc.text('SANGGUNIANG KABATAAN', centerX, headerTop + 20, { align: 'center' });

                doc.setFont('helvetica', 'bold');
                doc.setFontSize(12);
                doc.text('SANGGUNIANG KABATAAN', centerX, headerTop + 35, { align: 'center' });
                doc.text('OFFICIAL LIST', centerX, headerTop + 40, { align: 'center' });
            };

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
                head: [['User ID', 'Full Name', 'Barangay', 'Gender', 'Age', 'Birthdate', 'Position']],
                body: tableData,
                startY: contentTop,
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
                    3: { cellWidth: 15, halign: 'center' }, // Gender
                    4: { cellWidth: 15, halign: 'center' }, // Age
                    5: { cellWidth: 25, halign: 'center' }, // Birthdate
                    6: { cellWidth: 35, halign: 'center' }  // Position
                },
                tableWidth: tableWidth,
                margin: { left: horizontalMargin, top: contentTop, bottom: bottomMargin, right: horizontalMargin },
                theme: 'striped',
                alternateRowStyles: {
                    fillColor: [245, 245, 245]
                },
                didDrawPage: () => {
                    drawHeader();
                }
            });

            const totalPages = doc.internal.getNumberOfPages();
            doc.setPage(totalPages);

            // Get Pederasyon President and Secretary names from PHP data
            let presidentName = '';
            let secretaryName = '';
            
            // Get officials data from PHP
            const officials = <?= json_encode($user_list ?? []) ?>;
            officials.forEach(official => {
                const userType = parseInt(official.user_type);
                const pedPosition = parseInt(official.ped_position);
                const status = parseInt(official.status);
                
                // Only get approved Pederasyon officers
                if (userType === 3 && status === 2) {
                    const fullName = `${official.first_name || ''} ${official.middle_name || ''} ${official.last_name || ''}`.replace(/\s+/g, ' ').trim();
                    
                    if (pedPosition === 1) { // President
                        presidentName = fullName;
                    } else if (pedPosition === 3) { // Secretary
                        secretaryName = fullName;
                    }
                }
            });

            let finalY = doc.lastAutoTable && doc.lastAutoTable.finalY ? doc.lastAutoTable.finalY + 15 : contentTop;
            const signatureHeight = 35;
            if (finalY + signatureHeight > pageHeight - bottomMargin) {
                doc.addPage();
                drawHeader();
                finalY = contentTop;
            }

            const signatureSpacing = 60;
            const leftSignatureX = centerX - signatureSpacing;
            const rightSignatureX = centerX + signatureSpacing;

            doc.setFont("helvetica", "normal");
            doc.setFontSize(9);

            // Left signature (Prepared by - Secretary)
            doc.text('Prepared by:', leftSignatureX, finalY, { align: 'center' });
            doc.text('_________________________', leftSignatureX, finalY + 18, { align: 'center' });
            doc.setFont("helvetica", "bold");
            doc.text(secretaryName || '_________________________', leftSignatureX, finalY + 18, { align: 'center' });
            doc.setFont("helvetica", "normal");
            doc.text('Secretary', leftSignatureX, finalY + 23, { align: 'center' });

            // Right signature (Approved by - President)
            doc.text('Approved by:', rightSignatureX, finalY, { align: 'center' });
            doc.text('_________________________', rightSignatureX, finalY + 18, { align: 'center' });
            doc.setFont("helvetica", "bold");
            doc.text(presidentName || '_________________________', rightSignatureX, finalY + 23, { align: 'center' });
            doc.setFont("helvetica", "normal");
            doc.text('President', rightSignatureX, finalY + 28, { align: 'center' });

            // Save the PDF with timestamp
            const timestamp = new Date().toISOString().replace(/[-:]/g, '').replace('T', '_').split('.')[0];
            doc.save(`SK_Officials_List_${timestamp}.pdf`);
            
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
                // Get the filename from Content-Disposition header if available
                const contentDisposition = response.headers.get('Content-Disposition');
                let fileName = 'SK_Official_List.docx';
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
                
                showNotification('Official List Word document generated and downloaded successfully!', 'success');
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
                // Get the filename from Content-Disposition header if available
                const contentDisposition = response.headers.get('Content-Disposition');
                let fileName = 'SK_Official_List.xlsx';
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
                
                showNotification('Official List Excel document generated and downloaded successfully!', 'success');
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
            const modal = document.getElementById('credentialsPreviewModal');
            modal.style.display = 'flex';
            
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
                    if (data && data.success && data.data) {
                        const logos = data.data;
                        
                        // Load Pederasyon logo for credentials
                        const pederasyonLogoDiv = document.getElementById('credentials-pederasyon-logo');
                        if (logos.pederasyon && logos.pederasyon.file_path && pederasyonLogoDiv) {
                            pederasyonLogoDiv.innerHTML = `<img src="<?= base_url() ?>${logos.pederasyon.file_path}" alt="Pederasyon Logo" class="w-full h-full object-contain">`;
                        }
                        
                        // Load Iriga City logo for credentials
                        const irigaLogoDiv = document.getElementById('credentials-iriga-logo');
                        if (logos.iriga_city && logos.iriga_city.file_path && irigaLogoDiv) {
                            irigaLogoDiv.innerHTML = `<img src="<?= base_url() ?>${logos.iriga_city.file_path}" alt="Iriga City Logo" class="w-full h-full object-contain">`;
                        }
                    } else {
                        console.error('Failed to load logos for credentials:', data ? data.message : 'No data');
                    }
                })
                .catch(error => {
                    console.error('Error fetching logos for credentials:', error);
                    // Keep default SVG icons if API fails
                });
        }

        // Removed showCredentialsTab function since we only show SK Chairpersons now (no tabs needed)

        function getActiveCredentialsTab() {
            // Always return 'sk' since we only show SK Chairpersons
            return 'sk';
        }

        function loadCredentialsData() {
            // Fetch credentials data from the API (SK Chairpersons only)
            fetch('<?= base_url('pederasyon/credentials-data') ?>')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.success) {
                        const skCredentials = (data.data && data.data.sk) ? data.data.sk : [];
                        
                        // Populate SK credentials table
                        populateCredentialsTable('sk', skCredentials);
                        
                        // Update count
                        const countEl = document.getElementById('skCredentialsCount');
                        if (countEl) {
                            countEl.textContent = skCredentials.length;
                        }
                        
                        // Update total count
                        const totalEl = document.getElementById('credentialsCount');
                        if (totalEl) {
                            totalEl.textContent = `Total: ${skCredentials.length} SK Chairpersons with credentials`;
                        }
                        
                        // Show/hide no credentials message
                        const noCredsEl = document.getElementById('noCredentials');
                        const containerEl = document.getElementById('credentialsTablesContainer');
                        
                        if (skCredentials.length === 0) {
                            if (noCredsEl) noCredsEl.classList.remove('hidden');
                            if (containerEl) containerEl.classList.add('hidden');
                        } else {
                            if (noCredsEl) noCredsEl.classList.add('hidden');
                            if (containerEl) containerEl.classList.remove('hidden');
                        }
                    } else {
                        // Show error message
                        const noCredsEl = document.getElementById('noCredentials');
                        const containerEl = document.getElementById('credentialsTablesContainer');
                        if (noCredsEl) noCredsEl.classList.remove('hidden');
                        if (containerEl) containerEl.classList.add('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error fetching credentials data:', error);
                    console.error('Error details:', error.message, error.stack);
                    // Show error message
                    const noCredsEl = document.getElementById('noCredentials');
                    const containerEl = document.getElementById('credentialsTablesContainer');
                    if (noCredsEl) noCredsEl.classList.remove('hidden');
                    if (containerEl) containerEl.classList.add('hidden');
                });
        }

        function populateCredentialsTable(type, credentials) {
            const tableBodyId = type + 'CredentialsTableBody';
            const tableBody = document.getElementById(tableBodyId);
            
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
                                <div class="font-semibold mb-1">No ${type === 'sk' ? 'SK' : 'Pederasyon'} Credentials</div>
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
                
                // Handle username display
                let displayUsername = credential.username || 'N/A';
                let usernameClass = 'text-gray-900 font-semibold';
                if (displayUsername === 'Not Set' || displayUsername === 'N/A') {
                    displayUsername = '<span class="text-gray-400 italic">Not Set</span>';
                    usernameClass = '';
                }
                
                // Handle password display
                let displayPassword = 'N/A';
                let passwordClass = 'text-gray-900 font-semibold';
                
                if (credential.password && credential.password !== 'Not Set') {
                    // Check if password is hashed (starts with $2y$, $2b$ or is longer than 20 characters)
                    const isHashedPassword = credential.password.startsWith('$2y$') || 
                                            credential.password.startsWith('$2b$') ||
                                            credential.password.length > 20;
                    
                    if (isHashedPassword) {
                        // Show asterisks for hashed passwords
                        displayPassword = '********';
                    } else {
                        // Show actual password if it's not hashed (temporary password)
                        displayPassword = credential.password;
                    }
                } else {
                    // Show "Not Set" for missing passwords
                    displayPassword = '<span class="text-gray-400 italic">Not Set</span>';
                    passwordClass = '';
                }
                
                row.innerHTML = `
                    <td class="border border-gray-300 text-center px-2 py-2 text-gray-900 text-xs">${credential.userId || 'N/A'}</td>
                    <td class="border border-gray-300 text-center px-2 py-2 text-gray-900 text-xs">${credential.name || 'N/A'}</td>
                    <td class="border border-gray-300 text-center px-2 py-2 text-gray-900 text-xs">${credential.barangay || 'N/A'}</td>
                    <td class="border border-gray-300 text-center px-2 py-2 text-gray-900 text-xs">${credential.position || 'N/A'}</td>
                    <td class="border border-gray-300 text-center px-2 py-2 ${usernameClass} text-xs">${displayUsername}</td>
                    <td class="border border-gray-300 text-center px-2 py-2 ${passwordClass} text-xs">${displayPassword}</td>
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
                // Mark credential type as downloaded based on active tab
                try {
                    const activeTab = (typeof getActiveCredentialsTab === 'function') ? getActiveCredentialsTab() : 'sk';
                    const type = activeTab === 'pederasyon' ? 'pederasyon' : 'sk';
                    fetch('<?= base_url('pederasyon/mark-credential-downloaded') ?>', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: 'type=' + encodeURIComponent(type)
                    }).then(() => {
                        showNotification((type.charAt(0).toUpperCase() + type.slice(1)) + ' credentials download recorded.', 'success');
                    }).catch(() => {
                        showNotification('Failed to record credential download. Please try again if logout remains disabled.', 'error');
                    });
                } catch (e) { /* ignore */ }
            }, 1000);
        }

        function closeCredentialsPreviewModal() {
            const modal = document.getElementById('credentialsPreviewModal');
            modal.style.display = 'none';
        }

    // printCredentials removed as requested

        async function downloadCredentialsPDF() {
            // Show loading notification
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
                
                // Get officials data for credentials
                const officials = <?= json_encode($user_list ?? []) ?>;
                // Include SK Chairpersons (user_type=2, accepted)
                const baseSk = officials.filter(user => parseInt(user.user_type) === 2 && parseInt(user.status) === 2);
                // Pederasyon (user_type=3, accepted)
                const pederasyonOfficials = officials.filter(user => parseInt(user.user_type) === 3 && parseInt(user.status) === 2);
                // Per rule: Pederasyon officers are also SK, include them in SK list if they have SK credentials
                const pedWithSkCreds = pederasyonOfficials.filter(user => (user.sk_username && user.sk_username !== '') && (user.sk_password && user.sk_password !== ''));
                const skOfficials = [...baseSk, ...pedWithSkCreds];
                
                // Get the currently active tab
                const activeTab = getActiveCredentialsTab();
                
                // SK Officials Section - only show if SK tab is active
                if (activeTab === 'sk' && skOfficials.length > 0) {
                    // Title
                    doc.setFont("helvetica", "bold");
                    doc.setFontSize(12);
                    doc.text("SANGGUNIANG KABATAAN", centerX, 55, { align: 'center' });
                    doc.text("OFFICIALS CREDENTIALS", centerX, 60, { align: 'center' });
                    y += 8;
                    
                    // Prepare SK Officials table data
                    const skTableData = skOfficials.map(official => {
                        const fullName = `${official.first_name || ''} ${official.middle_name || ''} ${official.last_name || ''}`.trim();
                        const barangay = official.barangay_name || getBarangayName(official.barangay) || '';
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
                            0: { cellWidth: 18, halign: 'center' }, // User ID
                            1: { cellWidth: 50, halign: 'center' }, // Full Name
                            2: { cellWidth: 32, halign: 'center' }, // Barangay
                            3: { cellWidth: 35, halign: 'center' }, // Position
                            4: { cellWidth: 40, halign: 'center' }, // Username
                            5: { cellWidth: 25, halign: 'center' } // Password
                        },
                        tableWidth: 190,
                        margin: { left: (287 - 190) / 2 }, // Center table on A4 landscape (297mm width)
                        theme: 'striped',
                        alternateRowStyles: {
                            fillColor: [245, 245, 245]
                        }
                    });
                    
                    y = doc.lastAutoTable.finalY + 10;
                }
                
                // Pederasyon Officials Section - only show if Pederasyon tab is active
                if (activeTab === 'pederasyon' && pederasyonOfficials.length > 0) {
                    // Check if we need a new page
                    if (y > 170) {
                        doc.addPage();
                        y = 20;
                    }
                    
                    doc.setFont("helvetica", "bold");
                    doc.setFontSize(12);
                    doc.text("PANLUNGSOD NA PEDERASYON NG MGA SANGGUNIANG KABATAAN", centerX, 55, { align: 'center' });
                    doc.text("OFFICIALS CREDENTIALS", centerX, 60, { align: 'center' });
                     y += 8;
                    
                    // Prepare Pederasyon Officials table data
                    const pedTableData = pederasyonOfficials.map(official => {
                        const fullName = `${official.first_name || ''} ${official.middle_name || ''} ${official.last_name || ''}`.trim();
                        const barangay = official.barangay_name || getBarangayName(official.barangay) || '';
                        
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
                            0: { cellWidth: 18, halign: 'center' }, // User ID
                            1: { cellWidth: 50, halign: 'center' }, // Full Name
                            2: { cellWidth: 32, halign: 'center' }, // Barangay
                            3: { cellWidth: 35, halign: 'center' }, // Position
                            4: { cellWidth: 40, halign: 'center' }, // Username
                            5: { cellWidth: 25, halign: 'center' } // Password
                        },
                        tableWidth: 190,
                        margin: { left: (287 - 190) / 2 }, // Center table on A4 landscape (297mm width)
                        theme: 'striped',
                        alternateRowStyles: {
                            fillColor: [245, 245, 245]
                        }
                    });
                }
                
                // Get Pederasyon President and Secretary names
                let presidentName = '';
                let secretaryName = '';
                
                // Find President and Secretary from the officials data (reuse already loaded officials)
                officials.forEach(official => {
                    const userType = parseInt(official.user_type);
                    const pedPosition = parseInt(official.ped_position);
                    const status = parseInt(official.status);
                    
                    // Only get approved Pederasyon officers
                    if (userType === 3 && status === 2) {
                        const fullName = `${official.first_name || ''} ${official.middle_name || ''} ${official.last_name || ''}`.replace(/\s+/g, ' ').trim();
                        
                        if (pedPosition === 1) { // President
                            presidentName = fullName;
                        } else if (pedPosition === 3) { // Secretary
                            secretaryName = fullName;
                        }
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
                doc.text('_________________________', leftSignatureX, finalY + 15, { align: 'center' });
                doc.setFont("helvetica", "bold");
                doc.text(secretaryName || '_________________________', leftSignatureX, finalY + 18, { align: 'center' });
                doc.setFont("helvetica", "normal");
                doc.text('Secretary', leftSignatureX, finalY + 23, { align: 'center' });
                
                // Right signature (Approved by - President)
                doc.text('Approved by:', rightSignatureX, finalY, { align: 'center' });
                doc.text('_________________________', rightSignatureX, finalY + 15, { align: 'center' });
                doc.setFont("helvetica", "bold");
                doc.text(presidentName || '_________________________', rightSignatureX, finalY + 18, { align: 'center' });
                doc.setFont("helvetica", "normal");
                doc.text('President', rightSignatureX, finalY + 23, { align: 'center' });
                
                // Save the PDF
                const tabName = getActiveCredentialsTab() === 'sk' ? 'SK' : 'PEDERASYON';
                const fileName = tabName + '_Officials_Credentials_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-') + '.pdf';
                doc.save(fileName);
                
                showNotification('Credentials PDF downloaded successfully!', 'success');
                
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

        function downloadCredentialsWord() {
            // Per-button loading UI like the PDF button. No info toast while generating.
            const button = event.target;
            const originalHTML = button.innerHTML;
            button.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Generating Word...';
            button.disabled = true;

            const activeTab = getActiveCredentialsTab();

            // Make AJAX request to generate credentials Word document
            fetch('<?= base_url('pederasyon/generate-credentials-word') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ activeTab: activeTab })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                // Get the filename from Content-Disposition header if available
                const contentDisposition = response.headers.get('Content-Disposition');
                let fileName = 'Credentials.docx';
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

        function downloadCredentialsExcel() {
            // Per-button loading UI like the PDF button. No info toast while generating.
            const button = event.target;
            const originalHTML = button.innerHTML;
            button.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Generating Excel...';
            button.disabled = true;

            const activeTab = getActiveCredentialsTab();

            // Make AJAX request to generate credentials Excel document
            fetch('<?= base_url('pederasyon/generate-credentials-excel') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ activeTab: activeTab })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                // Get the filename from Content-Disposition header if available
                const contentDisposition = response.headers.get('Content-Disposition');
                let fileName = 'Credentials.xlsx';
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
                showNotification('Error generating credentials document: ' + error.message + '. Please check your connection and try again.', 'error');
            })
            .finally(() => {
                // Reset button state
                button.innerHTML = originalHTML;
                button.disabled = false;
            });
        }

        // Close credentials modal when clicking outside
        $('#credentialsPreviewModal').on('click', function(e) {
            if (e.target === this) {
                closeCredentialsPreviewModal();
            }
        });

        // ==================== SK CHAIRMAN AVAILABILITY CHECK ==================== //
        
        function checkSKChairmanAvailability(currentUserId, barangayId, currentUserType) {
            // Always disable for rejected users (status 3) - they should not be able to change type
            const userStatus = parseInt($('#modalUserStatusValue').val());
            if (userStatus === 3) {
                $('#modalUserType').prop('disabled', true);
                $('#saveUserTypeBtn').prop('disabled', true).addClass('bg-gray-300 cursor-not-allowed').removeClass('bg-blue-600 hover:bg-blue-700');
                return;
            }
            
            // Default: enable the controls
            $('#modalUserType').prop('disabled', false);
            $('#saveUserTypeBtn').prop('disabled', false).removeClass('bg-gray-300 cursor-not-allowed').addClass('bg-blue-600 hover:bg-blue-700');
            
            // Get barangay ID from hidden field
            const userBarangayId = $('#modalUserBarangayId').val();
            
            // Local check from table: build barangay occupancy and apply rules
            pedApplyPerUserRoleRules('#modalUserType', userBarangayId, currentUserId, currentUserType);
        }

        // ==================== PED PREVIEW MODAL ROLE SELECT + UPDATE ==================== //
        $(document).on('click', '#pedRoleUpdateBtn', function() {
            const newType = parseInt($('#pedRoleSelect').val(), 10);
            const dbId = $('#pedModalDbId').val() || '';
            const displayUserId = $('#pedModalDisplayUserId').val() || '';
            const currentType = parseInt($('#pedModalUserType').val() || '0', 10);
            const barangayId = $('#pedModalUserBarangayId').val();
            const userStatus = parseInt($('#pedModalUserStatusValue').val() || '0', 10);

            if (userStatus === 3) {
                showNotification('Cannot change role: user is Rejected.', 'error');
                return;
            }

            // If selected option is disabled, bail out quietly
            if ($('#pedRoleSelect option:selected').is(':disabled')) {
                return;
            }

            const proceedChange = () => {
                const labelMap = {1: 'KK Member', 2: 'SK Chairperson'};
                // Show confirmation modal instead of confirm()
                $('#pedRoleChangeMessage').text(`Are you sure you want to change the user type to "${labelMap[newType]}"?`);
                $('#pedRoleChangeModal').css('display', 'flex');

                // One-time handlers to avoid stacking
                $('#pedConfirmRoleChangeBtn').off('click').on('click', function() {
                    const $btn = $('#pedRoleUpdateBtn');
                    const original = $btn.text();
                    $btn.prop('disabled', true).text('Updating...').addClass('opacity-80');
                    // Hide modal while processing
                    $('#pedRoleChangeModal').css('display', 'none');
                    $.ajax({
                        url: '/updateUserType',
                        method: 'POST',
                        data: { user_id: dbId || displayUserId, user_type: newType },
                        success: function(resp) {
                            if (resp && resp.success) {
                                showNotification('User type updated successfully! Email notification sent.', 'success');
                                try { if (window.localStorage) localStorage.setItem('knect_show_credentials_prompt', '1'); } catch (e) {}
                                try {
                                    $('#pedRoleChangeModal').css('display', 'none');
                                    $('#pedPreviewModal').css('display', 'none');
                                } catch (e) {}
                                setTimeout(() => window.location.reload(), 1000);
                            } else {
                                showNotification((resp && resp.message) || 'Failed to update user type.', 'error');
                            }
                        },
                        error: function(xhr) {
                            const msg = (xhr && xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Failed to update user type. Please try again.';
                            showNotification(msg, 'error');
                        },
                        complete: function() {
                            $btn.prop('disabled', false).text(original).removeClass('opacity-80');
                        }
                    });
                });

                $('#pedCancelRoleChangeBtn').off('click').on('click', function() {
                    $('#pedRoleChangeModal').css('display', 'none');
                });
            };

            // Local validation based on current table state
            const canPedChange = pedValidateChangeForUser(barangayId, dbId || displayUserId, currentType, newType);
            if (canPedChange) {
                proceedChange();
            }
        });

        // Enhanced bulk change logic for SK Chairperson restriction
        // Re-implement bulk restriction locally (no server calls)
        function checkBulkSKChairmanRestriction(selectedIds) {
            const newType = parseInt($('#bulkNewPosition').val());
            // If chosen option is disabled, block
            if ($('#bulkNewPosition option:selected').is(':disabled')) return Promise.resolve(false);
            // For other types, rely on disabled options applied by pedUpdateBulkRoleOptions
            return Promise.resolve(true);
        }

        // ===== Local role rules helpers =====
        function pedScanBarangayOccupancy() {
            // Returns a map: barangayId -> { skOwner: {db:?, disp:?}|null, pedOwner: {db:?, disp:?}|null }
            const occ = {};
            $('#myTable tbody tr').each(function() {
                const $tr = $(this);
                const bId = String($tr.data('barangay-id') || '').trim();
                const uType = parseInt($tr.data('user-type') || 0, 10);
                const dbId = String($tr.data('user-id') || ($tr.find('.rowCheckbox').val() || '')).trim();
                const dispId = String($tr.data('display-user-id') || ($tr.find('td').eq(1).text() || '')).trim();
                if (!bId) return;
                if (!occ[bId]) occ[bId] = { skOwner: null, pedOwner: null };
                const val = { db: dbId, disp: dispId };
                if (uType === 2 && !occ[bId].skOwner) occ[bId].skOwner = val;
                if (uType === 3 && !occ[bId].pedOwner) occ[bId].pedOwner = val;
            });
            return occ;
        }

        function pedAllowedSetForUser(barangayId, userId, currentType) {
            // Roles: 1=KK, 2=SK, 3=Ped; Rule: Ped implies SK ownership
            const occ = pedScanBarangayOccupancy();
            const o = occ[String(barangayId)] || { skOwner: null, pedOwner: null };
            const matchOwner = (owner) => owner && (String(owner.db) === String(userId) || String(owner.disp) === String(userId));
            const isOwnerSK = matchOwner(o.skOwner);
            const isOwnerPed = matchOwner(o.pedOwner);
            const someoneIsSK = !!o.skOwner && !isOwnerSK;
            const someoneIsPed = !!o.pedOwner && !isOwnerPed;
            // Base: everyone can be KK
            const allowed = {1: true, 2: true, 3: true};
            // If another Pederasyon exists in barangay, others can only be KK
            if (someoneIsPed) {
                allowed[2] = false;
                allowed[3] = false;
            }
            // If another SK exists in barangay, others cannot be SK or Ped
            if (someoneIsSK) {
                allowed[2] = false;
                allowed[3] = false;
            }
            // Owners can switch between SK and Ped freely
            if (isOwnerSK || isOwnerPed) {
                allowed[2] = true;
                allowed[3] = true;
            }
            return allowed;
        }

        function pedApplyPerUserRoleRules(selectSelector, barangayId, userId, currentType, noteSelector) {
            const allowed = pedAllowedSetForUser(barangayId, userId, currentType);
            const $sel = $(selectSelector);
            $sel.find('option[value="1"]').prop('disabled', !allowed[1]);
            $sel.find('option[value="2"]').prop('disabled', !allowed[2]);
            $sel.find('option[value="3"]').prop('disabled', !allowed[3]);
            // If current selection is disabled, pick first enabled
            const $cur = $sel.find('option:selected');
            if ($cur.is(':disabled')) {
                const $firstEnabled = $sel.find('option:not(:disabled)').first();
                if ($firstEnabled.length) $sel.val($firstEnabled.val());
            }
            // Update or show a note near the select
            const noteId = noteSelector || '#skChairmanNote';
            const $note = $(noteId);
            const showNote = (!allowed[2] && !allowed[3]);
            if (showNote) {
                if ($note.length === 0) {
                    const idAttr = (noteId.startsWith('#') ? noteId.substring(1) : 'skChairmanNote');
                    $sel.parent().append('<div id="' + idAttr + '" class="mt-2 text-sm text-orange-600 bg-orange-50 border border-orange-200 rounded-lg p-2"><strong>Note:</strong> This barangay already has an SK Chairperson or Pederasyon. Other users can only be KK Members.</div>');
                } else {
                    $note.removeClass('hidden').text('Note: This barangay already has an SK Chairperson or Pederasyon. Other users can only be KK Members.');
                }
            } else {
                $note.addClass('hidden');
            }
        }

        function pedValidateChangeForUser(barangayId, userId, currentType, newType) {
            const allowed = pedAllowedSetForUser(barangayId, userId, currentType);
            return !!allowed[newType];
        }

        function pedUpdateBulkRoleOptions() {
            const selectedIds = $('.rowCheckbox:checked').map(function() { return $(this).val(); }).get();
            const occ = pedScanBarangayOccupancy();
            // Compute allowed intersection across selected users
            let allowedAll = {1: true, 2: true, 3: true};
            selectedIds.forEach(function(userId) {
                const $tr = $('.rowCheckbox[value="' + userId + '"]').closest('tr');
                const bId = String($tr.data('barangay-id') || '').trim();
                const curType = parseInt($tr.data('user-type') || 0, 10);
                const allowed = pedAllowedSetForUser(bId, userId, curType);
                allowedAll[1] = allowedAll[1] && !!allowed[1];
                allowedAll[2] = allowedAll[2] && !!allowed[2];
                allowedAll[3] = allowedAll[3] && !!allowed[3];
            });
            // Apply to bulk select
            $('#bulkNewPosition option[value="1"]').prop('disabled', !allowedAll[1]);
            $('#bulkNewPosition option[value="2"]').prop('disabled', !allowedAll[2]);
            $('#bulkNewPosition option[value="3"]').prop('disabled', !allowedAll[3]);
            // If selection now disabled, move to first enabled
            if ($('#bulkNewPosition option:selected').is(':disabled')) {
                const $firstEnabled = $('#bulkNewPosition option:not(:disabled)').first();
                if ($firstEnabled.length) $('#bulkNewPosition').val($firstEnabled.val());
            }
            // Show dynamic note
            const noneSKPed = !allowedAll[2] && !allowedAll[3];
            const $note = $('#bulkRoleDynamicNote');
            if (noneSKPed) {
                $note.removeClass('hidden').text('Note: At least one selected user is in a barangay that already has a Pederasyon or SK Chairperson. Only KK Member is available for those users.');
            } else {
                $note.addClass('hidden').text('');
            }
        }

    </script>
    

    <!-- Pederasyon: SK-style View Modal (Info left, Documents right) -->
    <div id="pedPreviewModal" class="fixed inset-0 z-[9998] bg-black bg-opacity-50 items-center justify-center p-4" style="display: none;">
        <div class="bg-white rounded-lg shadow-xl w-[90vw] max-h-[90vh] relative overflow-hidden flex flex-col">
            <!-- Confirmation Popup inside ped preview modal (matches ped-officers design) -->
            <div id="pedRoleChangeModal" class="absolute inset-0 z-50 items-center justify-center bg-black bg-opacity-40" style="display: none;">
                <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 18.5C3.498 20.333 4.46 22 6 22z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Change User Type</h3>
                        <p id="pedRoleChangeMessage" class="text-gray-600 mb-6">Are you sure you want to change the user type?</p>
                        <div class="flex justify-center gap-3">
                            <button id="pedConfirmRoleChangeBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium transition-all duration-200 shadow-sm">Confirm</button>
                            <button id="pedCancelRoleChangeBtn" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-medium transition-all duration-200">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Header -->
            <div class="w-full bg-white border-b border-gray-200 p-4 flex justify-between items-center z-20">
                <h3 class="text-lg font-semibold text-gray-900">User Profile</h3>
                <button onclick="closePedPreviewModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Content Wrapper (takes remaining vertical space) -->
            <div class="flex-1 flex overflow-hidden">
                <!-- Left: User Info -->
                <div class="w-[40%] bg-gray-50 p-6 flex flex-col items-center justify-start overflow-y-auto">
                    <!-- Hidden fields for role-change checks -->
                    <input type="hidden" id="pedModalUserBarangayId" value="">
                    <input type="hidden" id="pedModalUserStatusValue" value="">
                    <input type="hidden" id="pedModalUserType" value="">
                    <input type="hidden" id="pedModalDbId" value="">
                    <input type="hidden" id="pedModalDisplayUserId" value="">
                    <div class="w-40 h-40 bg-gray-300 mb-4 overflow-hidden shadow-md border-4 border-white flex items-center justify-center relative" style="min-width:220px; min-height:220px; max-width:220px; max-height:220px;">
                        <img id="pedModalUserPhoto" src="" alt="User Profile" class="w-full h-full object-cover" style="aspect-ratio:1/1; min-width:220px; min-height:220px; max-width:220px; max-height:220px; border-radius:0;">
                    </div>
                    <h4 id="pedModalUserFullName" class="text-lg font-semibold text-gray-900 text-center mb-1"></h4>
                    <p id="pedModalUserBarangay" class="text-sm text-gray-500 text-center mb-4"></p>
                    <!-- User Info Sections -->
                    <div class="w-full space-y-6">
                        <!-- Basic Information -->
                        <div>
                            <h5 class="text-sm font-medium text-gray-900 mb-3 pb-1 border-b border-gray-200">Basic Information</h5>
                            <div class="grid grid-cols-2 gap-4">
                                <div><label class="block text-sm font-medium text-gray-500 mb-1">Full Name</label><p id="pedModalUserName" class="text-sm text-gray-900"></p></div>
                                <div><label class="block text-sm font-medium text-gray-500 mb-1">KK ID</label><p id="pedModalUserId" class="text-sm text-gray-900"></p></div>
                                <div><label class="block text-sm font-medium text-gray-500 mb-1">Sex</label><p id="pedModalUserSex" class="text-sm text-gray-900"></p></div>
                                <div><label class="block text-sm font-medium text-gray-500 mb-1">Email</label><p id="pedModalUserEmail" class="text-sm text-gray-900"></p></div>
                                <div><label class="block text-sm font-medium text-gray-500 mb-1">Birthday</label><p id="pedModalUserBirthday" class="text-sm text-gray-900"></p></div>
                                <div><label class="block text-sm font-medium text-gray-500 mb-1">Age</label><p id="pedModalUserAge" class="text-sm text-gray-900"></p></div>
                                <div><label class="block text-sm font-medium text-gray-500 mb-1">Civil Status</label><p id="pedModalUserCivilStatus" class="text-sm text-gray-900"></p></div>
                                <div><label class="block text-sm font-medium text-gray-500 mb-1">Status</label><span id="pedModalUserStatus" class="inline-flex px-2 py-1 rounded-full text-sm font-medium"></span></div>
                            </div>
                        </div>
                        <!-- Address Information -->
                        <div>
                            <h5 class="text-sm font-medium text-gray-900 mb-3 pb-1 border-b border-gray-200">Address Information</h5>
                            <div class="grid grid-cols-2 gap-4">
                                <div><label class="block text-sm font-medium text-gray-500 mb-1">Barangay</label><p id="pedModalUserBarangayDetail" class="text-sm text-gray-900"></p></div>
                                <div><label class="block text-sm font-medium text-gray-500 mb-1">Zone</label><p id="pedModalUserZone" class="text-sm text-gray-900"></p></div>
                                <div class="col-span-2"><label class="block text-sm font-medium text-gray-500 mb-1">Complete Address</label><p id="pedModalUserAddress" class="text-sm text-gray-900"></p></div>
                            </div>
                        </div>
                        <!-- Youth Classification -->
                        <div>
                            <h5 class="text-sm font-medium text-gray-900 mb-3 pb-1 border-b border-gray-200">Youth Classification</h5>
                            <div class="grid grid-cols-2 gap-4">
                                <div><label class="block text-sm font-medium text-gray-500 mb-1">Youth Classification</label><p id="pedModalUserYouthClassification" class="text-sm text-gray-900"></p></div>
                                <div><label class="block text-sm font-medium text-gray-500 mb-1">Work Status</label><p id="pedModalUserWorkStatus" class="text-sm text-gray-900"></p></div>
                                <div><label class="block text-sm font-medium text-gray-500 mb-1">Youth Age Group</label><p id="pedModalUserYouthAgeGroup" class="text-sm text-gray-900"></p></div>
                                <div><label class="block text-sm font-medium text-gray-500 mb-1">Educational Background</label><p id="pedModalUserEducation" class="text-sm text-gray-900"></p></div>
                            </div>
                        </div>
                        <!-- Voting Information -->
                        <div>
                            <h5 class="text-sm font-medium text-gray-900 mb-3 pb-1 border-b border-gray-200">Voting Information</h5>
                            <div class="grid grid-cols-2 gap-4">
                                <div><label class="block text-sm font-medium text-gray-500 mb-1">Registered SK Voter</label><span id="pedModalUserSKVoter" class="inline-flex px-2 py-1 rounded-full text-sm font-medium"></span></div>
                                <div><label class="block text-sm font-medium text-gray-500 mb-1">Voted Last SK Election</label><span id="pedModalUserVotedSK" class="inline-flex px-2 py-1 rounded-full text-sm font-medium"></span></div>
                                <div class="col-span-2"><label class="block text-sm font-medium text-gray-500 mb-1">Registered National Voter</label><span id="pedModalUserNationalVoter" class="inline-flex px-2 py-1 rounded-full text-sm font-medium"></span></div>
                            </div>
                        </div>
                        <!-- Assembly Attendance -->
                        <div>
                            <h5 class="text-sm font-medium text-gray-900 mb-3 pb-1 border-b border-gray-200">KK Assembly Attendance</h5>
                            <div class="space-y-3">
                                <div><label class="block text-sm font-medium text-gray-500 mb-1">Have you attended a KK Assembly?</label><span id="pedModalUserAttendedAssembly" class="inline-flex px-2 py-1 rounded-full text-sm font-medium"></span></div>
                                <div id="pedAssemblyTimesContainer"><label class="block text-sm font-medium text-gray-500 mb-1">How many times?</label><p id="pedModalUserAssemblyTimes" class="text-sm text-gray-900"></p></div>
                                <div id="pedAssemblyReasonContainer" class="hidden"><label class="block text-sm font-medium text-gray-500 mb-1">If No, Why?</label><p id="pedModalUserAssemblyReason" class="text-sm text-gray-900"></p></div>
                            </div>
                        </div>
                    </div>

                    <!-- Officer Position Card (bottom area) -->
                    <div id="pedRoleCard" class="w-full mt-4 bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3zm0 2c-2.761 0-5 2.239-5 5v1h10v-1c0-2.761-2.239-5-5-5z"/>
                            </svg>
                            <label class="text-sm font-semibold text-gray-700">Change User Type</label>
                        </div>
                        <select id="pedRoleSelect" class="w-full border border-gray-200 rounded-md px-2 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent mb-3">
                            <option value="1">KK member</option>
                            <option value="2">SK Chairperson</option>
                        </select>
                        <div id="pedSkChairmanNote" class="hidden mt-2 mb-3 text-sm text-orange-600 bg-orange-50 border border-orange-200 rounded-lg p-2"></div>
                        <button id="pedRoleUpdateBtn" class="w-full mt-3 bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg text-sm font-medium transition-all duration-200 shadow-sm">
                            Update Position
                        </button>
                    </div>
                </div>
                <!-- Right: Document Preview -->
                <div class="w-[60%] p-6 flex flex-col gap-4 items-center justify-start relative overflow-y-auto bg-white border-l border-gray-200" id="pedModalDocPreview">
                    <!-- Document preview will be injected here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Credentials Preview Modal -->
    <div id="credentialsPreviewModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 items-center justify-center p-4" style="display: none;">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-7xl max-h-[90vh] relative overflow-hidden flex flex-col">
            <!-- Modal Header -->
            <div class="bg-white border-b border-gray-200 px-6 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Officials Credentials</h3>
                        <p class="text-sm text-gray-600 mt-1">K-NECT System Officials Login Credentials</p>
                    </div>
                    <button onclick="closeCredentialsPreviewModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none transition-colors p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Content -->
            <div class="flex-1 overflow-y-auto p-6">
                <div id="credentialsLoading" class="text-center py-12">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    <p class="mt-3 text-gray-600 font-medium">Loading credentials...</p>
                </div>

                <div id="credentialsContent" class="hidden">
                    <!-- Credentials Tables Container -->
                    <div id="credentialsTablesContainer" class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <!-- SK Credentials Table -->
                        <div id="skCredentialsSection" class="credentials-section p-6">
                            <div class="mb-4">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    <h4 class="text-lg font-semibold text-gray-900">SK Chairperson Login Credentials</h4> 
                                    <span class="text-sm font-medium text-blue-900">
                                        (<span id="skCredentialsCount">0</span>)
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600">Login information for SK Chairperson positions across all barangays</p>
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
                                                <th class="border border-gray-300 text-center font-bold py-3 px-3 text-gray-700 text-xs">SK Username</th>
                                                <th class="border border-gray-300 text-center font-bold py-3 px-3 text-gray-700 text-xs">SK Password</th>
                                            </tr>
                                        </thead>
                                        <tbody id="skCredentialsTableBody">
                                            <!-- SK credentials data will be populated here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="noCredentials" class="text-center py-12 hidden">
                        <div class="w-16 h-16 mx-auto mb-4 bg-yellow-100 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No SK Chairpersons Found</h3>
                        <p class="text-sm text-gray-600 mb-2">No SK Chairpersons or Pederasyon Officers found.</p>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 border-t border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div id="credentialsCount" class="text-sm font-medium text-gray-700"></div>
                    <div class="flex gap-3">
                        <button onclick="closeCredentialsPreviewModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors duration-200">
                            Close
                        </button>
                        <button onclick="downloadCredentialsPDF()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors duration-200 shadow-sm">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            PDF
                        </button>
                        <button onclick="downloadCredentialsWord()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-200 shadow-sm">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Word
                        </button>
                        <button onclick="downloadCredentialsExcel()" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors duration-200 shadow-sm">
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

    <!-- Official List Preview Modal -->
    <div id="officialListModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 items-center justify-center p-4" style="display: none;">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-7xl max-h-[90vh] relative overflow-hidden flex flex-col">
            <!-- Modal Header -->
            <div class="bg-white border-b border-gray-200 px-6 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Officials List</h3>
                        <p class="text-sm text-gray-600 mt-1">K-NECT System Official SK Chairpersons List</p>
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
                    <p class="mt-3 text-gray-600 font-medium">Loading officials list...</p>
                </div>

                <div id="officialListContent" class="hidden">

                    <!-- Officials List Table -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="p-6">
                            <div class="mb-4">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    <h4 class="text-lg font-semibold text-gray-900">SK Officials List</h4>
                                </div>
                                <p class="text-sm text-gray-600">Complete list of accepted SK Chairpersons and Pederasyon Officers across all barangays</p>
                                
                            </div>
                            <div class="overflow-x-auto">
                                <div class="border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                                    <table class="w-full border-collapse">
                                        <thead>
                                            <tr class="bg-blue-50">
                                                <th class="border border-gray-300 text-center font-semibold py-3 px-4 text-gray-700 text-sm">User ID</th>
                                                <th class="border border-gray-300 text-center font-semibold py-3 px-4 text-gray-700 text-sm">Full Name</th>
                                                <th class="border border-gray-300 text-center font-semibold py-3 px-4 text-gray-700 text-sm">Barangay</th>
                                                <th class="border border-gray-300 text-center font-semibold py-3 px-4 text-gray-700 text-sm">Gender</th>
                                                <th class="border border-gray-300 text-center font-semibold py-3 px-4 text-gray-700 text-sm">Age</th>
                                                <th class="border border-gray-300 text-center font-semibold py-3 px-4 text-gray-700 text-sm">Birthdate</th>
                                                <th class="border border-gray-300 text-center font-semibold py-3 px-4 text-gray-700 text-sm">Position</th>
                                            </tr>
                                        </thead>
                                        <tbody id="officialListTableBody">
                                            <!-- Officials data will be populated here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="noOfficials" class="text-center py-12 hidden">
                        <div class="w-16 h-16 mx-auto mb-4 bg-yellow-100 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Officials Found</h3>
                        <p class="text-sm text-gray-600 mb-2">No accepted SK Chairpersons or Pederasyon Officers found in the current table view.</p>
                        <p class="text-xs text-gray-500">Make sure you have officials with "Accepted" status in the list.</p>
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

</body>
</html>
