<!-- DataTables and Styling -->
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
    
    /* Status Tabs Styling */
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
<div class="flex-1 flex flex-col min-h-0 ml-64 pt-16">
    <main class="flex-1 overflow-auto p-6 bg-gray-50">
        <!-- Page Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">User Management</h1>
                <p class="text-gray-600 mt-1">Manage user records and status</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="<?= base_url('sk/settings') ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    Back to Settings
                </a>
            </div>
        </div>

        <!-- Filter Tabs and Controls -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-4">
            <div class="p-4 border-b border-gray-200">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <!-- Status Tabs -->
                    <div class="flex flex-wrap gap-2">
                        <button class="status-tab active px-4 py-2 rounded-lg text-sm font-medium transition-all" data-status="verified">
                            Verified Users (<?= (int)($verified_count ?? count($user_list ?? [])) ?>)
                        </button>
                        <button class="status-tab px-4 py-2 rounded-lg text-sm font-medium transition-all" data-status="overage-inactive">
                            Overage & Inactive (<?= (int)($inactive_count ?? 0) ?>)
                        </button>
                        <button class="status-tab px-4 py-2 rounded-lg text-sm font-medium transition-all" data-status="deactivated">
                            Deactivated Users (<?= (int)($deactivated_count ?? 0) ?>)
                        </button>
                    </div>
                    
                    <!-- Filters -->
                    <div class="flex items-center gap-3">
                        <!-- Zone Filter (for verified users) -->
                        <div id="zoneFilterContainer" class="flex items-center gap-2">
                            <span class="text-sm font-medium text-gray-600">Zone:</span>
                            <select id="zoneFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Zones</option>
                            </select>
                        </div>
                        
                        <!-- User Type Filter (for verified users) -->
                        <div id="userTypeFilterContainer" class="flex items-center gap-2">
                            <span class="text-sm font-medium text-gray-600">Type:</span>
                            <select id="userTypeFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Types</option>
                                <option value="1">SK Chairperson</option>
                                <option value="2">SK Kagawad</option>
                                <option value="3">Secretary</option>
                                <option value="4">Treasurer</option>
                                <option value="5">KK Member</option>
                            </select>
                        </div>
                        
                        <!-- Reason Filter (for deactivated users) -->
                        <div id="reasonFilterContainer" class="flex items-center gap-2" style="display: none;">
                            <span class="text-sm font-medium text-gray-600">Reason:</span>
                            <select id="reasonFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Reasons</option>
                                <option value="aged_out">Aged Out (31+)</option>
                                <option value="inactive_long">Inactive 1+ Year</option>
                                <option value="manual_deactivation">Manual Deactivation</option>
                            </select>
                        </div>
                        
                        <button id="clearFilters" class="px-3 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            Clear Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6">
                <!-- Table Content - Server-side rendered like youth_profile -->
                <div id="tableContent">
                    <div class="overflow-x-auto">
                        <!-- Verified Users Table -->
                        <table id="usersTable" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">User ID</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Full Name</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Zone</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Age</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Position</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Last Login</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if (!empty($user_list)): ?>
                                    <?php foreach ($user_list as $user): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?= esc($user['id']) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                <?= esc($user['user_id']) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?= esc($user['full_name']) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?= esc($user['zone_display']) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?= esc($user['age']) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?= esc($user['position_text']) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?= esc($user['last_login_formatted']) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full <?= esc($user['status_class']) ?>">
                                                    <?= esc($user['status_reason']) ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <button onclick="viewUserDetails(<?= $user['id'] ?>)" class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        View
                                                    </button>
                                                    <button onclick="openDeactivateModal(<?= $user['id'] ?>, '<?= esc($user['first_name']) ?> <?= esc($user['last_name']) ?>')" class="inline-flex items-center px-3 py-1 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition-colors">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                        Deactivate
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>

                        <!-- Inactive/Overage Users Table -->
                        <table id="inactiveTable" class="min-w-full divide-y divide-gray-200 hidden">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">User ID</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Full Name</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Zone</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Age</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Last Login</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if (!empty($inactive_list)): ?>
                                    <?php foreach ($inactive_list as $user): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($user['id']) ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= esc($user['user_id']) ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($user['full_name']) ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($user['zone_display']) ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($user['age']) ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full <?= esc($user['inactive_class'] ?? 'bg-gray-100 text-gray-800') ?>"><?= esc($user['inactive_reason'] ?? 'Unknown') ?></span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($user['last_login_formatted'] ?? 'Never') ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <button onclick="openDeactivateModal(<?= $user['id'] ?>, '<?= esc($user['first_name']) ?> <?= esc($user['last_name']) ?>')" class="inline-flex items-center px-3 py-1 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition-colors">
                                                    Deactivate
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>

                        <!-- Deactivated Users Table -->
                        <table id="deactivatedTable" class="min-w-full divide-y divide-gray-200 hidden">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">User ID</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Full Name</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Zone</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Age</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Deactivated</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if (!empty($deactivated_list)): ?>
                                    <?php foreach ($deactivated_list as $user): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($user['id']) ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= esc($user['user_id']) ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($user['full_name']) ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($user['zone_display']) ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($user['age']) ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($user['deactivated_date'] ?? '') ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= esc($user['deactivation_reason'] ?? 'Manual Deactivation') ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <button onclick="reactivateUser(<?= $user['id'] ?>, '<?= esc($user['first_name']) ?> <?= esc($user['last_name']) ?>')" class="inline-flex items-center px-3 py-1 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors">
                                                    Reactivate
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Summary Information -->
                <?php if (!empty($user_list)): ?>
                    <div class="mt-4 flex items-center justify-between text-sm text-gray-500">
                        <div>
                            Showing <?= count($user_list) ?> verified users from <?= esc($barangay_name) ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>
                                    
<!-- User Detail Modal - SK Official style -->
<div id="userDetailModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] relative overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-white border-b border-gray-200 px-6 py-4">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">User Profile</h3>
                <button id="closeUserDetailModal" class="text-gray-400 hover:text-gray-600 focus:outline-none transition-colors p-1" onclick="hideUserDetailModal()">
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
                    <div class="mt-2 flex items-center justify-center gap-2">
                        <span id="modalUserAgeLeft" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800"></span>
                        <span id="modalUserInactiveLeft" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800"></span>
                    </div>
                </div>
            </div>

            <!-- Right Side - Information Sections -->
            <div class="w-2/3 p-6 overflow-y-auto" style="max-height: calc(90vh - 140px);">
                <div class="space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                        <div class="flex items-center gap-2 mb-4">
                            <h5 class="text-lg font-semibold text-gray-900">Basic Information</h5>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="text-xs text-gray-500">User ID</div>
                                <div id="modalUserId" class="text-sm text-gray-900"></div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="text-xs text-gray-500">Full Name</div>
                                <div id="modalUserName" class="text-sm text-gray-900"></div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="text-xs text-gray-500">Age</div>
                                <div id="modalUserAge" class="text-sm text-gray-900"></div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="text-xs text-gray-500">Sex</div>
                                <div id="modalUserSex" class="text-sm text-gray-900"></div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="text-xs text-gray-500">Position</div>
                                <div id="modalUserType" class="text-sm text-gray-900"></div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="text-xs text-gray-500">Email</div>
                                <div id="modalUserEmail" class="text-sm text-gray-900"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                        <div class="flex items-center gap-2 mb-4">
                            <h5 class="text-lg font-semibold text-gray-900">Address</h5>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="text-xs text-gray-500">Barangay</div>
                                <div id="modalUserBarangayDetail" class="text-sm text-gray-900"></div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="text-xs text-gray-500">Zone/Purok</div>
                                <div id="modalUserZone" class="text-sm text-gray-900"></div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 col-span-2">
                                <div class="text-xs text-gray-500">Full Address</div>
                                <div id="modalUserAddress" class="text-sm text-gray-900"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Youth Classification -->
                    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                        <div class="flex items-center gap-2 mb-4">
                            <h5 class="text-lg font-semibold text-gray-900">Youth Classification</h5>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="text-xs text-gray-500">Classification</div>
                                <div id="modalUserYouthClassification" class="text-sm text-gray-900"></div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="text-xs text-gray-500">Work Status</div>
                                <div id="modalUserWorkStatus" class="text-sm text-gray-900"></div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="text-xs text-gray-500">Age Group</div>
                                <div id="modalUserYouthAgeGroup" class="text-sm text-gray-900"></div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="text-xs text-gray-500">Education</div>
                                <div id="modalUserEducation" class="text-sm text-gray-900"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Voting and KK Assembly -->
                    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                        <div class="flex items-center gap-2 mb-4">
                            <h5 class="text-lg font-semibold text-gray-900">Voting & Assembly</h5>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="text-xs text-gray-500">SK Voter</div>
                                <span id="modalUserSKVoter" class="inline-flex px-2 py-1 rounded-full text-sm font-medium"></span>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="text-xs text-gray-500">Voted in SK Election</div>
                                <span id="modalUserVotedSK" class="inline-flex px-2 py-1 rounded-full text-sm font-medium"></span>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="text-xs text-gray-500">National Voter</div>
                                <span id="modalUserNationalVoter" class="inline-flex px-2 py-1 rounded-full text-sm font-medium"></span>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="text-xs text-gray-500">Attended KK Assembly</div>
                                <span id="modalUserAttendedAssembly" class="inline-flex px-2 py-1 rounded-full text-sm font-medium"></span>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="text-xs text-gray-500">Assembly Times</div>
                                <div id="modalUserAssemblyTimes" class="text-sm text-gray-900"></div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="text-xs text-gray-500">If No, Why?</div>
                                <div id="modalUserAssemblyReason" class="text-sm text-gray-900"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Deactivate User Modal -->
<div id="deactivateUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
        <div class="p-5">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-3 border-b">
                <h3 class="text-lg font-bold text-gray-900">Deactivate User</h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex items-center" onclick="closeDeactivateModal()">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="py-4">
                <p class="text-sm text-gray-600 mb-4">Select a reason for deactivating <span id="deactivateUserName" class="font-semibold"></span>:</p>
                
                <div class="space-y-3">
                    <label class="flex items-center">
                        <input type="radio" name="deactivateReason" value="aged_out" class="mr-3 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm">Aged out (31+ years old)</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="deactivateReason" value="inactive_long" class="mr-3 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm">Inactive for 1+ year</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="deactivateReason" value="manual_deactivation" class="mr-3 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm">Manual deactivation</span>
                    </label>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="flex items-center justify-end pt-3 border-t space-x-2">
                <button type="button" onclick="closeDeactivateModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded transition-colors duration-200">
                    Cancel
                </button>
                <button type="button" onclick="confirmDeactivation()" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded transition-colors duration-200">
                    Deactivate
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Reactivate User Modal -->
<div id="reactivateUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
        <div class="p-5">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-3 border-b">
                <h3 class="text-lg font-bold text-gray-900">Reactivate User</h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex items-center" onclick="closeReactivateModal()">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="py-4">
                <p class="text-sm text-gray-700">Are you sure you want to reactivate <span id="reactivateUserName" class="font-semibold"></span>?</p>
            </div>
            
            <!-- Modal Footer -->
            <div class="flex items-center justify-end pt-3 border-t space-x-2">
                <button type="button" onclick="closeReactivateModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded transition-colors duration-200">Cancel</button>
                <button type="button" onclick="confirmReactivate()" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded transition-colors duration-200">Reactivate</button>
            </div>
        </div>
    </div>
    </div>

<!-- Toast Container -->
<div id="toastContainer" class="fixed top-6 right-6 z-[99999] flex flex-col gap-2 items-end"></div>

<!-- Include DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script>
// Add JavaScript similar to youth_profile.php
// Zone mapping from PHP (if needed for filtering)
const zoneMap = <?= json_encode($zone_map ?? []) ?>;

let dataTable = null;
let currentDeactivateUserId = null;
let currentDeactivateUserName = '';
let currentReactivateUserId = null;
let currentReactivateUserName = '';

// Initialize DataTables with server-side rendered data (like youth_profile)
$(document).ready(function() {
    //
    
    // Common DataTables options
    const dtCommonOptions = {
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        dom: '<"flex items-center justify-between mb-4"<"flex items-center gap-4"l<"ml-4"i>><"flex items-center gap-2"f>>rtip',
        language: {
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            search: "Search:",
            searchPlaceholder: "Search...",
            emptyTable: "No records found.",
            zeroRecords: "No matching records found."
        },
        columnDefs: [
            { orderable: false, targets: -1 },
            { responsivePriority: 1, targets: -1 }, // Actions stays visible
            { responsivePriority: 2, targets: 2 }   // Full Name prioritized
        ],
        responsive: true,
        autoWidth: false
    };

    // Initialize DataTables for each tab
    const usersDataTable = $('#usersTable').DataTable({
        ...dtCommonOptions,
        order: [[2, 'asc']]
    });
    const inactiveDataTable = $('#inactiveTable').DataTable({
        ...dtCommonOptions,
        order: [[2, 'asc']]
    });
    const deactivatedDataTable = $('#deactivatedTable').DataTable({
        ...dtCommonOptions,
        order: [[2, 'asc']]
    });

    // Initial adjustment to avoid horizontal scroll flashes
    usersDataTable.columns.adjust().responsive.recalc();
    inactiveDataTable.columns.adjust().responsive.recalc();
    deactivatedDataTable.columns.adjust().responsive.recalc();

    // Recalculate on window resize
    $(window).on('resize', function() {
        if (dataTable) {
            dataTable.columns.adjust().responsive.recalc();
        }
    });

    // Track current active table and tab
    dataTable = usersDataTable;
    let currentTab = 'verified';

    // Hide non-active DataTables wrappers initially
    $('#inactiveTable_wrapper, #deactivatedTable_wrapper').hide();
    
    // Populate zone filter dropdown if zone data exists
    <?php if ($has_zone_data): ?>
        populateZoneFilter();
    <?php endif; ?>
    
    // Set up filter event handlers
    setupFilterHandlers();
    
    //
    // Tab switching
    $('.status-tab').on('click', function() {
    const status = $(this).data('status');
    // No-op if already on this tab to avoid unnecessary DOM work
    if (status === currentTab) return;
    $('.status-tab').removeClass('active');
    $(this).addClass('active');
    currentTab = status;
    // Persist selected tab
    try { localStorage.setItem('um_active_tab', String(status)); } catch (e) {}

        // Toggle visible table and wrappers
        if (status === 'verified') {
            $('#usersTable').removeClass('hidden');
            $('#inactiveTable, #deactivatedTable').addClass('hidden');
            $('#usersTable_wrapper').show();
            $('#inactiveTable_wrapper, #deactivatedTable_wrapper').hide();
            dataTable = usersDataTable;
            usersDataTable.columns.adjust().responsive.recalc();
            // Filters visibility
            $('#zoneFilterContainer, #userTypeFilterContainer').show();
            $('#reasonFilterContainer').hide();
        } else if (status === 'overage-inactive') {
            $('#inactiveTable').removeClass('hidden');
            $('#usersTable, #deactivatedTable').addClass('hidden');
            $('#inactiveTable_wrapper').show();
            $('#usersTable_wrapper, #deactivatedTable_wrapper').hide();
            dataTable = inactiveDataTable;
            inactiveDataTable.columns.adjust().responsive.recalc();
            // Filters visibility
            $('#zoneFilterContainer, #userTypeFilterContainer').hide();
            $('#reasonFilterContainer').hide();
        } else if (status === 'deactivated') {
            $('#deactivatedTable').removeClass('hidden');
            $('#usersTable, #inactiveTable').addClass('hidden');
            $('#deactivatedTable_wrapper').show();
            $('#usersTable_wrapper, #inactiveTable_wrapper').hide();
            dataTable = deactivatedDataTable;
            deactivatedDataTable.columns.adjust().responsive.recalc();
            // Filters visibility
            $('#zoneFilterContainer, #userTypeFilterContainer').hide();
            $('#reasonFilterContainer').show();
        }
    });

    // Restore previously selected tab on load
    try {
        const savedTab = localStorage.getItem('um_active_tab');
        if (savedTab && savedTab !== 'verified') {
            const $btn = $(`.status-tab[data-status="${savedTab}"]`);
            if ($btn.length) {
                $btn.trigger('click');
            }
        }
    } catch (e) {}

    // Reason filter for deactivated table
    $('#reasonFilter').on('change', function() {
        if (currentTab !== 'deactivated') return;
        const val = $(this).val();
        let searchText = '';
        if (val === 'aged_out') searchText = 'Overage';
        else if (val === 'inactive_long') searchText = 'Inactive';
        else if (val === 'manual_deactivation') searchText = 'Manual';
        deactivatedDataTable.column(6).search(searchText).draw();
    });

    // Clear filters adapts to active tab
    $('#clearFilters').on('click', function() {
        if (currentTab === 'verified') {
            usersDataTable.search('').columns().search('');
            usersDataTable.draw();
            $('#zoneFilter').val('');
            $('#userTypeFilter').val('');
        } else if (currentTab === 'deactivated') {
            deactivatedDataTable.search('').columns().search('');
            deactivatedDataTable.draw();
            $('#reasonFilter').val('');
        } else if (currentTab === 'overage-inactive') {
            inactiveDataTable.search('').columns().search('');
            inactiveDataTable.draw();
        }
    showNotification('Filters cleared successfully', 'success');
    });

});

// Function to populate zone filter dropdown (similar to youth_profile)
function populateZoneFilter() {
    const zoneSelect = $('#zoneFilter');
    const zones = new Set();
    
    // Extract unique zones from rendered table rows (SSR)
    $('#usersTable tbody tr').each(function() {
        const zoneCell = $(this).find('td').eq(3).text().trim();
        if (zoneCell && zoneCell !== '-') zones.add(zoneCell);
    });
    
    // Clear existing options except "All Zones"
    zoneSelect.find('option:not([value=""])').remove();
    
    // Add zone options
    Array.from(zones).sort().forEach(zone => {
        zoneSelect.append(`<option value="${zone}">${zone}</option>`);
    });
}

// Set up filter handlers (simplified version)
function setupFilterHandlers() {
    // Zone filter
    $('#zoneFilter').on('change', function() {
        const zoneValue = $(this).val();
        const table = $('#usersTable').DataTable();
        if (!zoneValue) {
            table.column(3).search('').draw();
            return;
        }
        // Exact match using regex with anchors; escape user input
        const regex = '^' + escapeRegex(zoneValue) + '$';
        table.column(3).search(regex, true, false).draw();
    });
    
    // Position/User Type filter  
    $('#userTypeFilter').on('change', function() {
        const positionValue = $(this).val();
        const table = $('#usersTable').DataTable();
        if (positionValue) {
            const positionMap = {
                '1': 'Chairperson',
                '2': 'Secretary',
                '3': 'Treasurer',
                '4': 'Member'
            };
            const searchText = positionMap[positionValue] || '';
            table.column(5).search(searchText).draw();
        } else {
            table.column(5).search('').draw();
        }
    });
}

// Escape regex special characters
function escapeRegex(text) {
    return text.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

// User detail modal functions (fetches details via API)

// Deactivation modal functions
function openDeactivateModal(userId, userName) {
    currentDeactivateUserId = userId;
    currentDeactivateUserName = userName;
    document.getElementById('deactivateUserName').textContent = userName;
    document.getElementById('deactivateUserModal').classList.remove('hidden');
}

function closeDeactivateModal() {
    document.getElementById('deactivateUserModal').classList.add('hidden');
    currentDeactivateUserId = null;
    currentDeactivateUserName = '';
    // Reset form
    document.querySelectorAll('input[name="deactivateReason"]').forEach(input => input.checked = false);
}

// removed duplicate confirmDeactivation (see single definition below)

// Toast notification function (shared style with SK pages)
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

// Removed legacy AJAX/DataTable header builder and missing-element checks to fully adopt SSR table.

function viewUserDetails(userId) {
    // Open the new SK-style modal and load content
    showUserDetailLoading();
    document.getElementById('userDetailModal').classList.remove('hidden');
    const formData = new FormData();
    formData.append('user_id', userId);
    fetch('<?= base_url('getUserInfo') ?>', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(res => {
            if (!res.success || !res.user) {
                showNotification(res.message || 'Failed to load user info', 'error');
                return;
            }
            populateUserDetailModal(res.user);
        })
        .catch(() => showNotification('Failed to load user info', 'error'));
}

function hideUserDetailModal() {
    document.getElementById('userDetailModal').classList.add('hidden');
}

function showUserDetailLoading() {
    // Minimal shimmer/placeholder
    document.getElementById('modalUserPhoto').src = '';
    document.getElementById('modalUserFullName').textContent = 'Loading…';
    document.getElementById('modalUserBarangay').textContent = '';
    document.getElementById('modalUserStatus').textContent = '';
    [
        'modalUserId','modalUserName','modalUserAge','modalUserSex','modalUserType','modalUserEmail',
        'modalUserBarangayDetail','modalUserZone','modalUserAddress','modalUserYouthClassification',
        'modalUserWorkStatus','modalUserYouthAgeGroup','modalUserEducation','modalUserSKVoter',
        'modalUserVotedSK','modalUserNationalVoter','modalUserAttendedAssembly','modalUserAssemblyTimes',
    'modalUserAssemblyReason','modalUserAgeLeft','modalUserInactiveLeft'
    ].forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;
        if (el.tagName === 'SPAN') {
            el.className = 'inline-flex px-2 py-1 rounded-full text-sm font-medium bg-gray-200 text-gray-700';
            el.textContent = '…';
        } else {
            el.textContent = '…';
        }
    });
}

function populateUserDetailModal(u) {
    const barangayMap = <?= json_encode(\App\Libraries\BarangayHelper::getBarangayMap()) ?>;
    const barangayStr = barangayMap[u.barangay] || u.barangay || '';
    const fullName = [u.first_name, u.middle_name, u.last_name].filter(Boolean).join(' ') + (u.suffix ? ', ' + u.suffix : '');
    document.getElementById('modalUserFullName').textContent = fullName;
    document.getElementById('modalUserName').textContent = fullName;
    document.getElementById('modalUserId').textContent = u.user_id || u.id || '';
    document.getElementById('modalUserId').dataset.actualId = u.id;
    document.getElementById('modalUserEmail').textContent = u.email || '';
    document.getElementById('modalUserAge').textContent = u.age ? `${u.age} years old` : '';
    document.getElementById('modalUserAgeLeft').textContent = u.age ? `${u.age} yrs` : '';
    document.getElementById('modalUserSex').textContent = u.sex == '1' ? 'Male' : (u.sex == '2' ? 'Female' : '');
    document.getElementById('modalUserType').textContent = positionText(u.position || u.user_type || 5);
    document.getElementById('modalUserBarangay').textContent = barangayStr;
    document.getElementById('modalUserBarangayDetail').textContent = barangayStr;
    document.getElementById('modalUserZone').textContent = u.zone_purok || '';
    const addressParts = [];
    if (u.zone_purok) addressParts.push(u.zone_purok);
    if (barangayStr) addressParts.push(barangayStr);
    addressParts.push('Iriga City', 'Camarines Sur', 'Region 5');
    document.getElementById('modalUserAddress').textContent = addressParts.join(', ');

    // Status pill
    const statusEl = document.getElementById('modalUserStatus');
    statusEl.textContent = '';
    statusEl.className = 'inline-flex px-3 py-1 rounded-full text-xs font-medium ' + statusClass(u.status);
    statusEl.textContent = statusText(u.status);

    // Mapped enums
    // Centralized demographic maps injected from backend
    const civilStatusMap = <?= json_encode($field_mappings['civilStatusMap'] ?? []) ?>;
    const youthClassificationMap = <?= json_encode($field_mappings['youthClassificationMap'] ?? []) ?>;
    const ageGroupMap = <?= json_encode($field_mappings['ageGroupMap'] ?? []) ?>;
    const workStatusMap = <?= json_encode($field_mappings['workStatusMap'] ?? []) ?>;
    const educationMap = <?= json_encode($field_mappings['educationMap'] ?? []) ?>;
    const howManyTimesMap = <?= json_encode($field_mappings['howManyTimesMap'] ?? []) ?>;
    const noWhyMap = <?= json_encode($field_mappings['noWhyMap'] ?? []) ?>;

    document.getElementById('modalUserYouthClassification').textContent = youthClassificationMap[String(u.youth_classification)] || '';
    document.getElementById('modalUserWorkStatus').textContent = workStatusMap[String(u.work_status)] || '';
    document.getElementById('modalUserYouthAgeGroup').textContent = ageGroupMap[String(u.age_group)] || '';
    document.getElementById('modalUserEducation').textContent = educationMap[String(u.educational_background)] || '';

    // Yes/No colored pills
    setYesNo('#modalUserSKVoter', u.sk_voter);
    setYesNo('#modalUserVotedSK', u.sk_election);
    setYesNo('#modalUserNationalVoter', u.national_voter);
    setYesNo('#modalUserAttendedAssembly', u.kk_assembly);
    document.getElementById('modalUserAssemblyTimes').textContent = howManyTimesMap[String(u.how_many_times)] || '';
    document.getElementById('modalUserAssemblyReason').textContent = noWhyMap[String(u.no_why)] || '';

    // Photo with robust resolution and fallback
    (function(){
        const img = document.getElementById('modalUserPhoto');
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
        img.onerror = function(){ this.onerror=null; this.src = defaultAvatar; };
        img.src = imgUrl;
    })();

    // Inactive time (time since last_login)
    const inactiveBadge = document.getElementById('modalUserInactiveLeft');
    if (u.last_login) {
        inactiveBadge.textContent = timeSince(u.last_login) + ' inactive';
        inactiveBadge.className = 'inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800';
    } else {
        inactiveBadge.textContent = 'Never logged in';
        inactiveBadge.className = 'inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800';
    }
}

function setYesNo(selector, val) {
    const el = document.querySelector(selector);
    if (!el) return;
    const yes = val === 1 || val === '1' || val === true || val === 'Yes' || val === 'yes';
    el.className = 'inline-flex px-2 py-1 rounded-full text-sm font-medium ' + (yes ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800');
    el.textContent = yes ? 'Yes' : 'No';
}

function positionText(p) {
    const map = { 1:'SK Chairperson',2:'SK Kagawad',3:'Secretary',4:'Treasurer',5:'KK Member' };
    return map[String(p)] || 'KK Member';
}

function statusText(s) {
    if (s == 1) return 'Pending';
    if (s == 2) return 'Approved';
    if (s == 3) return 'Rejected';
    return 'Unknown';
}

function statusClass(s) {
    if (s == 1) return 'bg-yellow-100 text-yellow-800';
    if (s == 2) return 'bg-green-100 text-green-800';
    if (s == 3) return 'bg-red-100 text-red-800';
    return 'bg-gray-100 text-gray-800';
}

// Utility: relative time since date string
function timeSince(dateStr) {
    const d = new Date(dateStr);
    const seconds = Math.floor((Date.now() - d.getTime()) / 1000);
    const intervals = [
        { label: 'year', secs: 31536000 },
        { label: 'month', secs: 2592000 },
        { label: 'week', secs: 604800 },
        { label: 'day', secs: 86400 },
        { label: 'hour', secs: 3600 },
        { label: 'min', secs: 60 }
    ];
    for (const it of intervals) {
        const v = Math.floor(seconds / it.secs);
        if (v >= 1) return v + ' ' + it.label + (v > 1 ? 's' : '');
    }
    return seconds + 's';
}

function openDeactivateModal(userId, userName) {
    currentDeactivateUserId = userId;
    currentDeactivateUserName = userName;
    document.getElementById('deactivateUserName').textContent = userName;
    document.getElementById('deactivateUserModal').classList.remove('hidden');
}

function closeDeactivateModal() {
    document.getElementById('deactivateUserModal').classList.add('hidden');
    currentDeactivateUserId = null;
    currentDeactivateUserName = '';
    document.querySelectorAll('input[name="deactivateReason"]').forEach(radio => radio.checked = false);
}

function confirmDeactivation() {
    const selectedReason = document.querySelector('input[name="deactivateReason"]:checked');
    
    if (!selectedReason) {
    showNotification('Please select a reason for deactivation', 'error');
        return;
    }
    
    if (!currentDeactivateUserId) {
    showNotification('No user selected for deactivation', 'error');
        return;
    }
    
    // Send deactivation request
    fetch('<?= base_url('user-status/deactivate') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `user_id=${currentDeactivateUserId}&reason=${selectedReason.value}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(`User ${currentDeactivateUserName} has been deactivated`, 'success');
            closeDeactivateModal();
            setTimeout(() => { location.reload(); }, 1000);
        } else {
            showNotification(data.message || 'Error deactivating user', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    showNotification('Error deactivating user', 'error');
    });
}

function reactivateUser(userId, userName) {
    currentReactivateUserId = userId;
    currentReactivateUserName = userName;
    document.getElementById('reactivateUserName').textContent = userName;
    document.getElementById('reactivateUserModal').classList.remove('hidden');
}

function closeReactivateModal() {
    document.getElementById('reactivateUserModal').classList.add('hidden');
    currentReactivateUserId = null;
    currentReactivateUserName = '';
}

function confirmReactivate() {
    if (!currentReactivateUserId) {
    showNotification('No user selected for reactivation', 'error');
        return;
    }
    fetch('<?= base_url('user-status/reactivate') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `user_id=${currentReactivateUserId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(`User ${currentReactivateUserName} has been reactivated`, 'success');
            closeReactivateModal();
            setTimeout(() => { location.reload(); }, 1000);
        } else {
            showNotification(data.message || 'Error reactivating user', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    showNotification('Error reactivating user', 'error');
    });
}

function scanUser(userId) {
    // Implement scan functionality
    showNotification('Scan functionality not implemented yet', 'info');
}

// Utility functions
function getPositionText(position) {
    const positions = {
        1: 'SK Chairperson',
        2: 'SK Kagawad',
        3: 'Secretary', 
        4: 'Treasurer',
        5: 'KK Member'
    };
    return positions[position] || 'KK Member';
}

function getStatusText(status) {
    const statuses = {
        1: 'Pending',
        2: 'Approved',
        3: 'Rejected'
    };
    return statuses[status] || 'Unknown';
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString();
}

// Toast notification function
// showToast is deprecated in favor of showNotification (kept calls updated above)

// Close modal when clicking outside
document.getElementById('userDetailModal').addEventListener('click', function(e) {
    if (e.target === this) hideUserDetailModal();
});

document.getElementById('deactivateUserModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeactivateModal();
});

// Close Reactivate modal when clicking outside
document.getElementById('reactivateUserModal').addEventListener('click', function(e) {
    if (e.target === this) closeReactivateModal();
});
</script>
