
    <style>
        table.dataTable thead th {
            @apply bg-gray-50 text-gray-500 text-sm uppercase tracking-wide;
        }
        table.dataTable tbody tr:hover {
            @apply bg-indigo-50;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            @apply text-gray-700 hover:text-indigo-600 transition;
        }
        .restricted-row {
            background-color: #f9fafb !important;
            opacity: 0.7;
        }
        .restricted-row:hover {
            background-color: #f3f4f6 !important;
        }
        .restricted-checkbox {
            opacity: 0.5;
            cursor: not-allowed;
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
    </style>
        
        <!-- ===== MAIN CONTENT AREA ===== -->
        <!-- Content area positioned to the right of sidebar -->
        <div class="flex-1 flex flex-col min-h-0 ml-64 pt-16">
            <!-- Main Content Container -->
            <main class="flex-1 overflow-auto p-6 bg-gray-50">
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
                        <a href="<?= base_url('sk/sk-official') ?>" class="text-sm font-medium text-gray-600 hover:text-blue-600">SK Official</a>
                    </div>
                </li>
            </ol>
        </nav>    
            <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">SK Official</h3>
                        <?php if (isset($barangay_name) && $barangay_name): ?>
                        <p class="text-sm text-gray-600 mt-1">Barangay <span class="font-semibold text-blue-600"><?= esc($barangay_name) ?></span></p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Filter Tabs and Barangay Selector -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-4">
                    <div class="p-4 border-b border-gray-200">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <!-- Status Tabs -->
                            <div class="flex flex-wrap gap-2">
                                <button class="status-tab active px-4 py-2 rounded-lg text-sm font-medium transition-all" data-status="all">
                                    All
                                </button>
                                <button class="status-tab px-4 py-2 rounded-lg text-sm font-medium transition-all" data-status="chairman">
                                    SK Chairperson
                                </button>
                                <button class="status-tab px-4 py-2 rounded-lg text-sm font-medium transition-all" data-status="councilor">
                                    SK Councilor
                                </button>
                                <button class="status-tab px-4 py-2 rounded-lg text-sm font-medium transition-all" data-status="appointed">
                                    Appointed Officials
                                </button>
                                <button class="status-tab px-4 py-2 rounded-lg text-sm font-medium transition-all" data-status="kkmember">
                                    KK Member
                                </button>
                            </div>
                            <!-- Barangay Filter -->
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-medium text-gray-600">Barangay:</span>
                                <select id="barangayFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">All Barangays</option>
                                    <!-- Barangay options will be populated dynamically -->
                                </select>
                                <button id="clearFilters" class="px-3 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                    Clear Filters
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tabs for filtering -->
                <div class="mb-4 flex justify-center" style="display: none;">
                  <div class="inline-flex space-x-2 border border-gray-200 rounded-xl bg-gray-50 p-1">
                    <button id="tabAll" class="px-6 py-2 text-sm font-medium rounded-lg focus:outline-none text-gray-700 bg-transparent transition active-tab">All</button>
                    <button id="tabChairman" class="px-6 py-2 text-sm font-medium rounded-lg focus:outline-none text-gray-700 bg-transparent transition">SK Chairman</button>
                    <button id="tabSK" class="px-6 py-2 text-sm font-medium rounded-lg focus:outline-none text-gray-700 bg-transparent transition">SK Kagawad</button>
                    <button id="tabPederasyon" class="px-6 py-2 text-sm font-medium rounded-lg focus:outline-none text-gray-700 bg-transparent transition">Appointed Officials</button>
                  </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table id="myTable" class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">
                                            <input type="checkbox" id="selectAllRows" class="form-checkbox h-4 w-4 text-indigo-600">
                                        </th>
                                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Barangay</th>
                                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Zone</th>
                                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Age</th>
                                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Sex</th>
                                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Position</th>
                                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">View</th>
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
                                                data-ped_password="<?= isset($user['ped_password']) ? esc($user['ped_password']) : '' ?>"
                                                data-user_id="<?= esc($user['id']) ?>"
                                                data-position="<?= isset($user['position']) ? (int)$user['position'] : 5 ?>">
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <input type="checkbox" 
                                                           class="rowCheckbox form-checkbox h-4 w-4 text-indigo-600" 
                                                           value="<?= esc($user['id']) ?>"
                                                           data-user_id="<?= esc($user['id']) ?>"
                                                           data-position="<?= isset($user['position']) ? (int)$user['position'] : 5 ?>">
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($user['id']) ?></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <?= esc(BarangayHelper::getBarangayName($user['barangay'])) ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($user['zone_purok'] ?? '') ?></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($user['last_name']) ?>, <?= esc($user['first_name']) ?> <?= esc($user['middle_name']) ?></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($user['age']) ?></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $user['sex'] == '1' ? 'Male' : ($user['sex'] == '2' ? 'Female' : '') ?></td>
                                                <td class="px-6 py-4 whitespace-nowrap">
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
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <?php
                                                        $position = isset($user['position']) ? (int)$user['position'] : 5;
                                                        echo $position == 1 ? 'SK Chairman' : ($position == 2 ? 'SK Kagawad' : ($position == 3 ? 'Secretary' : ($position == 4 ? 'Treasurer' : 'KK Member')));
                                                    ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <a href="#" 
                                                        class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors view-user-btn"
                                                        data-id="<?= esc($user['id']) ?>"
                                                    >
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        View
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="11" class="px-6 py-4 text-center text-gray-500">
                                                <div class="flex flex-col items-center justify-center py-8">
                                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                                    </svg>
                                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No KK records found</h3>
                                                    <p class="text-gray-500">There are no KK records in the database yet.</p>
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
    <button id="bulkChangeBtn" class="fixed bottom-8 left-1/2 transform -translate-x-1/2 z-50 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-lg font-semibold rounded-full shadow-lg transition-all duration-200 hidden">
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
            <p class="text-sm text-gray-600 mb-4 text-center">Change the position for selected users. Note: Some positions cannot be selected or changed due to restrictions.</p>
            <div class="mb-6">
                <label for="bulkNewPosition" class="block text-sm font-medium text-gray-700 mb-2">Select New Position</label>
                <select id="bulkNewPosition" class="w-full border border-gray-300 rounded-md px-2 py-2 text-base focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    <option value="1" disabled style="color: #888;">SK Chairman (Cannot be assigned in bulk)</option>
                    <option value="2" selected>SK Kagawad</option>
                    <option value="3">Secretary</option>
                    <option value="4">Treasurer</option>
                    <option value="5">KK Member</option>
                </select>
            </div>
            <div class="flex justify-center gap-4">
                <button id="confirmBulkChangeBtn" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">Confirm</button>
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
                            <label class="text-sm font-semibold text-gray-700">Position</label>
                        </div>
                        <select id="modalUserType" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent mb-3">
                            <option value="1">SK Chairman</option>
                            <option value="2">SK Kagawad</option>
                            <option value="3">Secretary</option>
                            <option value="4">Treasurer</option>
                            <option value="5">KK Member</option>
                        </select>
                        <button id="saveUserTypeBtn" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg text-sm font-medium transition-all duration-200 shadow-sm">
                            Save Changes
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
                            <div class="space-y-3">
                                <div><label class="block text-sm font-medium text-gray-500 mb-1">Have you attended a KK Assembly?</label><span id="modalUserAttendedAssembly" class="inline-flex px-2 py-1 rounded-full text-sm font-medium"></span></div>
                                <div id="assemblyTimesContainer"><label class="block text-sm font-medium text-gray-500 mb-1">How many times?</label><p id="modalUserAssemblyTimes" class="text-sm text-gray-900"></p></div>
                                <div id="assemblyReasonContainer" class="hidden"><label class="block text-sm font-medium text-gray-500 mb-1">If No, Why?</label><p id="modalUserAssemblyReason" class="text-sm text-gray-900"></p></div>
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
        
        // Current user data for restrictions
        const currentUserId = <?= json_encode($current_user_id ?? null) ?>;
        
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
        }
        $(document).ready(function () {
            // DataTable and tab logic
            const table = $('#myTable').DataTable({
                columnDefs: [
                    { orderable: false, targets: 0 }
                ],
                order: [[1, 'asc']],
                fixedColumns: {
                    leftColumns: 0,
                    rightColumns: 1
                },
                scrollCollapse: true,
                scrollY: '300px',
                scrollX: true,
                paging: true,
                info: true, // Keep the "Showing x to y of z entries"
                language: {
                    search: "", // Removes default label
                    searchPlaceholder: "Search..." // (not used here but optional)
                },
                initComplete: function () {
                    // Apply Tailwind utility classes to DataTable components
                    $('#myTable_wrapper').addClass('text-sm text-gray-700');
                    $('#myTable_length label').addClass('inline-flex items-center gap-2');
                    $('#myTable_length select').addClass('border border-gray-300 rounded px-2 py-1');
                    $('#myTable_info').addClass('mt-2 text-gray-600');
                    $('#myTable_paginate').addClass('mt-4');
                    $('#myTable_paginate span a').addClass('px-2 py-1 border rounded mx-1');
                    
                    // Populate barangay filter options
                    populateBarangayFilter();
                    
                    // Initialize "All" tab as active by default
                    $('.status-tab[data-status="all"]').trigger('click');
                }
            });

            // Apply visual restrictions to checkboxes and rows
            function applyVisualRestrictions() {
                $('.rowCheckbox').each(function() {
                    var userId = $(this).data('user_id');
                    var position = $(this).data('position');
                    var isCurrentUser = (userId == currentUserId);
                    var isChairman = (position == 1);
                    
                    if (isCurrentUser || isChairman) {
                        $(this).addClass('restricted-checkbox')
                               .attr('title', isCurrentUser ? 'You cannot change your own position' : 'SK Chairman position cannot be changed');
                        $(this).closest('tr').addClass('restricted-row');
                    }
                });
            }
            
            // Apply restrictions on page load
            applyVisualRestrictions();

            // Keep our custom search input functional
            $('#kkSearch').on('keyup', function () {
                table.search(this.value).draw();
            });

            // Tab filtering logic

            // Status tab click handler
            $(document).on('click', '.status-tab', function() {
                // Remove active class from all tabs
                $('.status-tab').removeClass('active');
                
                // Add active class to clicked tab
                $(this).addClass('active');

                const status = $(this).data('status');
                if (status === 'all') {
                    $('#myTable tbody tr').show();
                } else if (status === 'chairman') {
                    $('#myTable tbody tr').each(function() {
                        var userType = $(this).find('td').eq(8).text().trim();
                        if (userType === 'SK Chairman') {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                } else if (status === 'councilor') {
                    $('#myTable tbody tr').each(function() {
                        var userType = $(this).find('td').eq(8).text().trim();
                        if (userType === 'SK Kagawad') {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                } else if (status === 'appointed') {
                    $('#myTable tbody tr').each(function() {
                        var userType = $(this).find('td').eq(8).text().trim();
                        if (userType === 'Secretary' || userType === 'Treasurer') {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                } else if (status === 'kkmember') {
                    $('#myTable tbody tr').each(function() {
                        var userType = $(this).find('td').eq(8).text().trim();
                        if (userType === 'KK Member') {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                }
                localStorage.setItem('memberTab', status);
            });
            
            // Function to populate barangay filter dropdown
            function populateBarangayFilter() {
                const barangays = new Set();
                $('#myTable tbody tr').each(function() {
                    const barangayCell = $(this).find('td').eq(2); // Barangay is in the 3rd column (0-indexed: 2)
                    if (barangayCell.length) {
                        const barangayText = barangayCell.text().trim();
                        if (barangayText && barangayText !== '-') {
                            barangays.add(barangayText);
                        }
                    }
                });
                
                //
                
                const barangayFilter = $('#barangayFilter');
                Array.from(barangays).sort().forEach(barangay => {
                    barangayFilter.append(`<option value="${barangay}">${barangay}</option>`);
                });
            }
            
            // Barangay filter dropdown
            $('#barangayFilter').on('change', function() {
                const barangayValue = $(this).val();
                // Apply barangay filter (barangay is in column 2 - 0-indexed)
                table.column(2).search(barangayValue).draw();
            });
            
            // Clear filters button
            $('#clearFilters').on('click', function() {
                // Reset barangay filter
                $('#barangayFilter').val('');
                
                // Reset status tabs
                $('.status-tab').removeClass('active');
                $('.status-tab[data-status="all"]').addClass('active');
                
                // Clear all column searches
                table.columns().search('').draw();
                
                // Show all rows
                $('#myTable tbody tr').show();
                
                localStorage.setItem('memberTab', 'all');
                
                // Show notification
                showNotification('Filters cleared successfully', 'success');
            });
            
            // On page load, restore last selected tab
            var savedTab = localStorage.getItem('memberTab') || 'all';
            $('.status-tab[data-status="' + savedTab + '"]').trigger('click');

            // Populate barangay filter options after table is loaded
            populateBarangayFilter();
            
            function setActiveTab(tab) {
                $('#tabAll, #tabChairman, #tabSK, #tabPederasyon, #tabKK')
                  .removeClass('bg-white border font-semibold shadow text-gray-900 active-tab')
                  .addClass('bg-transparent text-gray-700 font-normal border-0');
                tab
                  .removeClass('bg-transparent text-gray-700 font-normal border-0')
                  .addClass('bg-white border font-semibold shadow text-gray-900 active-tab');
            }
            // OLD Filtering logic for legacy tabs (now hidden)
            $('#tabAll').on('click', function() {
                setActiveTab($(this));
                $('#myTable tbody tr').show();
                localStorage.setItem('kkTab', 'tabAll');
            });
            $('#tabChairman').on('click', function() {
                setActiveTab($(this));
                $('#myTable tbody tr').each(function() {
                    var userType = $(this).find('td').eq(8).text().trim();
                    if (userType === 'SK Chairman') {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
                localStorage.setItem('kkTab', 'tabChairman');
            });
            $('#tabSK').on('click', function() {
                setActiveTab($(this));
                $('#myTable tbody tr').each(function() {
                    var userType = $(this).find('td').eq(8).text().trim();
                    if (userType === 'SK Kagawad') {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
                localStorage.setItem('kkTab', 'tabSK');
            });
            $('#tabPederasyon').on('click', function() {
                setActiveTab($(this));
                $('#myTable tbody tr').each(function() {
                    var userType = $(this).find('td').eq(8).text().trim();
                    if (userType === 'Secretary' || userType === 'Treasurer') {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
                localStorage.setItem('kkTab', 'tabPederasyon');
            });
            $('#tabKK').on('click', function() {
                setActiveTab($(this));
                $('#myTable tbody tr').each(function() {
                    var userType = $(this).find('td').eq(8).text().trim();
                    if (userType === 'KK Member') {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
                localStorage.setItem('kkTab', 'tabKK');
            });
            // On page load, restore last selected tab for legacy
            var savedLegacyTab = localStorage.getItem('kkTab') || 'tabAll';
            $('#' + savedLegacyTab).trigger('click');

            // Bulk select checkboxes
            $('#selectAllRows').on('change', function() {
                var checked = $(this).is(':checked');
                $('.rowCheckbox').each(function() {
                    var userId = $(this).data('user_id');
                    var position = $(this).data('position');
                    var isCurrentUser = (userId == currentUserId);
                    var isChairman = (position == 1);
                    
                    // Only check/uncheck if it's not the current user or chairman
                    if (!isCurrentUser && !isChairman) {
                        $(this).prop('checked', checked);
                    } else if (checked) {
                        // If trying to check all but this is restricted, keep it unchecked
                        $(this).prop('checked', false);
                    }
                });
                updateBulkChangeBtn();
            });
            $(document).on('change', '.rowCheckbox', function() {
                // Apply restrictions - don't allow selection of current user or chairman
                var userId = $(this).data('user_id');
                var position = $(this).data('position');
                var isCurrentUser = (userId == currentUserId);
                var isChairman = (position == 1);
                
                if ($(this).is(':checked') && (isCurrentUser || isChairman)) {
                    $(this).prop('checked', false);
                    var errorMsg = isCurrentUser ? 
                        'You cannot select your own record for bulk changes.' : 
                        'SK Chairman position cannot be changed.';
                    showNotification(errorMsg, 'error');
                    return;
                }
                
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
                var selectedIds = [];
                var invalidSelections = [];
                
                $('.rowCheckbox:checked').each(function() {
                    var userId = $(this).data('user_id');
                    var position = $(this).data('position');
                    var isCurrentUser = (userId == currentUserId);
                    var isChairman = (position == 1);
                    
                    if (isCurrentUser || isChairman) {
                        invalidSelections.push(userId);
                    } else {
                        selectedIds.push(userId);
                    }
                });
                
                if (invalidSelections.length > 0) {
                    showNotification('Some selected users cannot be changed (current user or SK Chairman). Please unselect them.', 'error');
                    return;
                }
                
                if (selectedIds.length === 0) {
                    showNotification('No valid users selected.', 'error');
                    return;
                }
                
                var newType = $('#bulkNewPosition').val();
                
                // Prevent selecting SK Chairman in bulk update
                if (newType == '1') {
                    showNotification('SK Chairman position cannot be assigned in bulk. Please select a different position.', 'error');
                    return;
                }
                
                // AJAX request to bulk update positions for SK users only
                $.ajax({
                    url: '/bulkUpdateUserPosition',
                    method: 'POST',
                    data: { user_ids: selectedIds, position: newType },
                    success: function(response) {
                        if (response.success) {
                            showNotification(response.message, 'success');
                        } else {
                            showNotification(response.message || 'Update failed', 'error');
                        }
                        setTimeout(() => location.reload(), 1200);
                    },
                    error: function(xhr) {
                        var errorMessage = 'Failed to update user positions.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showNotification(errorMessage, 'error');
                    }
                });
                $('#bulkChangeModal').addClass('hidden').css('display', 'none');
            });

            // User Detail Modal functionality
                // User Detail Modal functionality
                $(document).on('click', '.view-user-btn', function(e) {
                    e.preventDefault();
                    var userId = $(this).data('id');
                    $.ajax({
                        url: '/getUserInfo',
                        method: 'POST',
                        data: { user_id: userId },
                        success: function(response) {
                            if (response.success) {
                                var u = response.user;
                                // Centralized mappings injected from backend
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
                                // User type mapping
                                var userTypeMap = {
                                    1: 'KK Member',
                                    2: 'SK Official',
                                    3: 'SK Official'
                                };
                                var userTypeStr = userTypeMap[u.user_type] || 'Unknown';
                                $('#modalUserBarangay').text(userTypeStr);
                                $('#modalUserBarangayDetail').text(barangayStr);
                                $('#modalUserId').text(u.user_id || u.id);
                                $('#modalUserId').data('actual-id', u.id); // Store actual ID for backend operations
                                $('#modalUserAge').text(u.age + ' years old');
                                $('#modalUserSex').text(u.sex == '1' ? 'Male' : (u.sex == '2' ? 'Female' : ''));
                                // Set position dropdown for all users
                                $('#modalUserType').val(String(u.position || 5));
                                
                                // Implement restrictions: user can't change their own position or the chairman's position
                                let isCurrentUser = (u.id == currentUserId);
                                let isChairman = (u.position == 1);
                                let canEdit = !isCurrentUser && !isChairman;
                                
                                if (!canEdit) {
                                    $('#modalUserType').prop('disabled', true);
                                    $('#saveUserTypeBtn').prop('disabled', true)
                                        .removeClass('bg-blue-600 hover:bg-blue-700')
                                        .addClass('bg-gray-300 cursor-not-allowed');
                                    
                                    if (isCurrentUser) {
                                        $('#saveUserTypeBtn').attr('title', 'You cannot change your own position');
                                    } else if (isChairman) {
                                        $('#saveUserTypeBtn').attr('title', 'SK Chairman position cannot be changed');
                                    }
                                } else {
                                    $('#modalUserType').prop('disabled', false);
                                    $('#saveUserTypeBtn').prop('disabled', false)
                                        .removeClass('bg-gray-300 cursor-not-allowed')
                                        .addClass('bg-blue-600 hover:bg-blue-700')
                                        .removeAttr('title');
                                }
                                
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
                                // Robust profile picture resolution for modal (supports absolute URL, relative path, or filename) with fallback
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
                                
                                $('#userDetailModal').removeClass('hidden');
                            } else {
                                showNotification('User not found.', 'error');
                            }
                        },
                        error: function() {
                            showNotification('Failed to fetch user info.', 'error');
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
                pendingUserTypeChange.userId = $('#modalUserId').data('actual-id');
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
                    $.ajax({
                        url: '/updateUserPosition',
                        method: 'POST',
                        data: { user_id: userId, position: parseInt(newType, 10) },
                        success: function(response) {
                            showNotification('User position updated successfully!', 'success');
                            setTimeout(() => location.reload(), 1200);
                        },
                        error: function() {
                            showNotification('Failed to update user position.', 'error');
                        }
                    });
                // Close both modals
                $('#roleChangeModal').addClass('hidden');
                $('#roleChangeModal').css('display', 'none');
                    $('#userDetailModal').addClass('hidden');
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
    </script>



</body>
</html>
