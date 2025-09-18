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
        
        /* Simplified Controls */
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

        /* Screen-only: use 8px font for table data in the download KK list modal preview */
        @media screen {
            #downloadContent table thead th,
            #downloadContent table tbody td {
                font-size: 8px !important;
                line-height: 1.1 !important; /* reduce line height for tighter rows */
            }
        }
    </style>

        <!-- ===== MAIN CONTENT AREA ===== -->
        <div class="flex-1 flex flex-col min-h-0 ml-64 pt-16">
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
                                <span class="text-sm font-medium text-gray-600">Youth Profile</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <!-- Header Section -->
                <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">List of KK</h3>
                        <?php if (isset($barangay_name) && $barangay_name): ?>
                        <p class="text-sm text-gray-600 mt-1">Barangay <span class="font-semibold text-blue-600"><?= esc($barangay_name) ?></span></p>
                        <?php endif; ?>
                    </div>
                    <div class="flex gap-3">
                        <button onclick="openDownloadModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Download KK List
                        </button>
                        <a href="<?= base_url('sk/rfid-assignment') ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a5 5 0 00-10 0v2a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2z"/>
                            </svg>
                            Assign RFID
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
                                    All (<span id="countAll"><?php echo isset($status_counts['all']) ? $status_counts['all'] : 0; ?></span>)
                                </button>
                                <button class="status-tab px-4 py-2 rounded-lg text-sm font-medium transition-all" data-status="Pending">
                                    Pending (<span id="countPending"><?php echo isset($status_counts['pending']) ? $status_counts['pending'] : 0; ?></span>)
                                </button>
                                <button class="status-tab px-4 py-2 rounded-lg text-sm font-medium transition-all" data-status="Accepted">
                                    Verified (<span id="countVerified"><?php echo isset($status_counts['accepted']) ? $status_counts['accepted'] : 0; ?></span>)
                                </button>
                                <button class="status-tab px-4 py-2 rounded-lg text-sm font-medium transition-all" data-status="Rejected">
                                    Rejected (<span id="countRejected"><?php echo isset($status_counts['rejected']) ? $status_counts['rejected'] : 0; ?></span>)
                                </button>
                            </div>
                            <!-- Zone Filter -->
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-medium text-gray-600">Zone:</span>
                                <select id="zoneFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">All Zones</option>
                                </select>
                                <button id="clearFilters" class="px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    Clear Filters
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Data Table -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-6">
                        <div class="overflow-x-auto ">
                            <table id="kkTable" class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">No.</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">User ID</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Zone</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Full Name</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Age</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Birthday</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Sex</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php if (!empty($user_list)): ?>
                                        <?php foreach ($user_list as $user): ?>
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-4 py-3 text-sm text-gray-900 text-center"><?= esc($user['id']) ?></td>
                                                <td class="px-4 py-3 text-sm text-gray-900 text-center">
                                                    <?php if (isset($user['user_id']) && $user['user_id']): ?>
                                                        <?= esc($user['user_id']) ?>
                                                    <?php else: ?>
                                                        <span class="text-gray-400">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-900 text-center"><?= esc($user['zone_display']) ?></td>
                                                <td class="px-4 py-3 text-sm text-gray-900 font-medium"><?= $user['full_name'] ?></td>
                                                <td class="px-4 py-3 text-sm text-gray-900 text-center"><?= esc($user['age']) ?></td>
                                                <td class="px-4 py-3 text-sm text-gray-900 text-center"><?= esc($user['birthdate']) ?></td>
                                                <td class="px-4 py-3 text-sm text-gray-900 text-center"><?= esc($user['sex_text']) ?></td>
                                                <td class="px-4 py-3 text-center">
                                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full <?= $user['status_class'] ?>"><?= $user['status_text'] ?></span>
                                                </td>
                                                <td class="px-4 py-3 text-center">
                                                    <?php if ($user['status'] == 1): // Pending ?>
                                                        <?php if ($user['has_documents']): ?>
                                                            <button type="button" onclick="openReviewModal('<?= esc($user['id']) ?>', '<?= esc($user['birth_certificate']) ?>', '<?= esc($user['upload_id']) ?>', <?= $user['user_json'] ?>, '<?= esc($user['upload_id_back'] ?? '') ?>')" class="inline-flex items-center px-3 py-1 bg-amber-600 text-white text-sm rounded-lg hover:bg-amber-700 transition-colors">
                                                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                                </svg>
                                                                Verify
                                                            </button>
                                                        <?php else: ?>
                                                            <span class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-500 text-sm rounded-lg">
                                                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                                </svg>
                                                                Verify
                                                            </span>
                                                        <?php endif; ?>
                                                    <?php elseif ($user['status'] == 2 || $user['status'] == 3): // Verified or Rejected ?>
                                                        <button type="button" onclick="openViewModal('<?= esc($user['id']) ?>', '<?= esc($user['birth_certificate']) ?>', '<?= esc($user['upload_id']) ?>', <?= $user['user_json'] ?>, '<?= esc($user['upload_id_back'] ?? '') ?>')" class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                                                            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                            </svg>
                                                            View
                                                        </button>
                                                    <?php else: ?>
                                                        <span class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-500 text-sm rounded-lg">
                                                            No action
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                                                <div class="flex flex-col items-center">
                                                    <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m3 5.197V9a3 3 0 00-3-3H9m1.5-2-3 3 3 3"/>
                                                    </svg>
                                                    <p class="text-sm">No KK records found.</p>
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
            <!-- Modals (reuse your existing modal HTML/JS here) -->
            <!-- Modal for document preview -->
            <div id="previewModal" class="fixed inset-0 z-[9998] hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-lg shadow-xl w-[90vw] max-h-[90vh] relative overflow-hidden flex flex-col">
                    <!-- Modal Header -->
                    <div class="w-full bg-white border-b border-gray-200 p-4 flex justify-between items-center z-20">
                        <h3 class="text-lg font-semibold text-gray-900">User Profile</h3>
                        <button onclick="closePreviewModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Content Wrapper (takes remaining vertical space) -->
                    <div class="flex-1 flex overflow-hidden"> <!-- New wrapper -->
                        <!-- Left: User Info -->
                        <div class="w-[40%] bg-gray-50 p-6 flex flex-col items-center justify-start overflow-y-auto">
                            <div class="w-40 h-40 bg-gray-300 mb-4 overflow-hidden shadow-md border-4 border-white flex items-center justify-center relative" style="min-width:220px; min-height:220px; max-width:220px; max-height:220px;">
                                <img id="modalUserPhoto" src="" alt="User Profile" class="w-full h-full object-cover" style="aspect-ratio:1/1; min-width:220px; min-height:220px; max-width:220px; max-height:220px; border-radius:0;">
                            </div>
                            <h4 id="modalUserFullName" class="text-lg font-semibold text-gray-900 text-center mb-1"></h4>
                            <p id="modalUserBarangay" class="text-sm text-gray-500 text-center mb-4"></p>
                            <!-- User Info Sections -->
                            <div class="w-full space-y-6">
                                <!-- Basic Information -->
                                <div>
                                    <h5 class="text-sm font-medium text-gray-900 mb-3 pb-1 border-b border-gray-200">Basic Information</h5>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div><label class="block text-sm font-medium text-gray-500 mb-1">Full Name</label><p id="modalUserName" class="text-sm text-gray-900"></p></div>
                                        <div><label class="block text-sm font-medium text-gray-500 mb-1">KK ID</label><p id="modalUserId" class="text-sm text-gray-900"></p></div>
                                        <div><label class="block text-sm font-medium text-gray-500 mb-1">Sex</label><p id="modalUserSex" class="text-sm text-gray-900"></p></div>
                                        <div><label class="block text-sm font-medium text-gray-500 mb-1">Email</label><p id="modalUserEmail" class="text-sm text-gray-900"></p></div>
                                        <div><label class="block text-sm font-medium text-gray-500 mb-1">Birthday</label><p id="modalUserBirthday" class="text-sm text-gray-900"></p></div>
                                        <div><label class="block text-sm font-medium text-gray-500 mb-1">Age</label><p id="modalUserAge" class="text-sm text-gray-900"></p></div>
                                        <div><label class="block text-sm font-medium text-gray-500 mb-1">Civil Status</label><p id="modalUserCivilStatus" class="text-sm text-gray-900"></p></div>
                                        <div><label class="block text-sm font-medium text-gray-500 mb-1">Status</label><span id="modalUserStatus" class="inline-flex px-2 py-1 rounded-full text-sm font-medium"></span></div>
                                    </div>
                                </div>
                                <!-- Address Information -->
                                <div>
                                    <h5 class="text-sm font-medium text-gray-900 mb-3 pb-1 border-b border-gray-200">Address Information</h5>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div><label class="block text-sm font-medium text-gray-500 mb-1">Barangay</label><p id="modalUserBarangayDetail" class="text-sm text-gray-900"></p></div>
                                        <div><label class="block text-sm font-medium text-gray-500 mb-1">Zone</label><p id="modalUserZone" class="text-sm text-gray-900"></p></div>
                                        <div class="col-span-2"><label class="block text-sm font-medium text-gray-500 mb-1">Complete Address</label><p id="modalUserAddress" class="text-sm text-gray-900"></p></div>
                                    </div>
                                </div>
                                <!-- Youth Classification -->
                                <div>
                                    <h5 class="text-sm font-medium text-gray-900 mb-3 pb-1 border-b border-gray-200">Youth Classification</h5>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div><label class="block text-sm font-medium text-gray-500 mb-1">Youth Classification</label><p id="modalUserYouthClassification" class="text-sm text-gray-900"></p></div>
                                        <div><label class="block text-sm font-medium text-gray-500 mb-1">Work Status</label><p id="modalUserWorkStatus" class="text-sm text-gray-900"></p></div>
                                        <div><label class="block text-sm font-medium text-gray-500 mb-1">Youth Age Group</label><p id="modalUserYouthAgeGroup" class="text-sm text-gray-900"></p></div>
                                        <div><label class="block text-sm font-medium text-gray-500 mb-1">Educational Background</label><p id="modalUserEducation" class="text-sm text-gray-900"></p></div>
                                    </div>
                                </div>
                                <!-- Voting Information -->
                                <div>
                                    <h5 class="text-sm font-medium text-gray-900 mb-3 pb-1 border-b border-gray-200">Voting Information</h5>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div><label class="block text-sm font-medium text-gray-500 mb-1">Registered SK Voter</label><span id="modalUserSKVoter" class="inline-flex px-2 py-1 rounded-full text-sm font-medium"></span></div>
                                        <div><label class="block text-sm font-medium text-gray-500 mb-1">Voted Last SK Election</label><span id="modalUserVotedSK" class="inline-flex px-2 py-1 rounded-full text-sm font-medium"></span></div>
                                        <div class="col-span-2"><label class="block text-sm font-medium text-gray-500 mb-1">Registered National Voter</label><span id="modalUserNationalVoter" class="inline-flex px-2 py-1 rounded-full text-sm font-medium"></span></div>
                                    </div>
                                </div>
                                <!-- Assembly Attendance -->
                                <div>
                                    <h5 class="text-sm font-medium text-gray-900 mb-3 pb-1 border-b border-gray-200">KK Assembly Attendance</h5>
                                    <div class="space-y-3">
                                        <div><label class="block text-sm font-medium text-gray-500 mb-1">Have you attended a KK Assembly?</label><span id="modalUserAttendedAssembly" class="inline-flex px-2 py-1 rounded-full text-sm font-medium"></span></div>
                                        <div id="assemblyTimesContainer"><label class="block text-sm font-medium text-gray-500 mb-1">How many times?</label><p id="modalUserAssemblyTimes" class="text-sm text-gray-900"></p></div>
                                        <div id="assemblyReasonContainer" class="hidden"><label class="block text-sm font-medium text-gray-500 mb-1">If No, Why?</label><p id="modalUserAssemblyReason" class="text-sm text-gray-900"></p></div>
                                    </div>
                                </div>
                            </div>
                            <!-- Action Buttons (Accept/Reject) -->
                            <div id="actionButtonsContainer" class="mt-6 flex flex-col gap-3 pt-4 border-t w-full">
                                <button id="acceptButton" onclick="acceptUser()" class="w-full py-3 px-4 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 font-semibold transition-all duration-200 flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Accept
                                </button>
                                <button id="rejectButton" onclick="rejectUser()" class="w-full py-3 px-4 bg-red-600 text-white rounded-lg shadow hover:bg-red-700 font-semibold transition-all duration-200 flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Reject
                                </button>
                                <button id="reverifyButton" onclick="reVerifyUser()" class="w-full py-3 px-4 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 font-semibold transition-all duration-200 flex items-center justify-center gap-2" style="display: none;">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Re-verify
                                </button>
                            </div>
                        </div>
                        <!-- Right: Document Preview -->
                        <div class="w-[60%] p-6 flex flex-col gap-8 items-center justify-start relative overflow-y-auto bg-white border-l border-gray-200" id="modalDocPreview">
                            <!-- Document preview will be injected here -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modals (reuse your existing modal HTML/JS here) -->
            <!-- Modal for document preview -->
            <div id="previewModal" class="fixed inset-0 z-[9998] hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-lg shadow-xl w-[90vw] max-h-[90vh] relative overflow-hidden flex flex-col">
                    <!-- Modal Header -->
                    <div class="w-full bg-white border-b border-gray-200 p-4 flex justify-between items-center z-20">
                        <h3 class="text-lg font-semibold text-gray-900">User Profile</h3>
                        <button onclick="closePreviewModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Content Wrapper (takes remaining vertical space) -->
                    <div class="flex-1 flex overflow-hidden"> <!-- New wrapper -->
                        <!-- Left: User Info -->
                        <div class="w-[40%] bg-gray-50 p-6 flex flex-col items-center justify-start overflow-y-auto">
                            <div class="w-40 h-40 bg-gray-300 mb-4 overflow-hidden shadow-md border-4 border-white flex items-center justify-center relative" style="min-width:220px; min-height:220px; max-width:220px; max-height:220px;">
                                <img id="modalUserPhoto" src="" alt="User Profile" class="w-full h-full object-cover" style="aspect-ratio:1/1; min-width:220px; min-height:220px; max-width:220px; max-height:220px; border-radius:0;">
                            </div>
                            <h4 id="modalUserFullName" class="text-lg font-semibold text-gray-900 text-center mb-1"></h4>
                            <p id="modalUserBarangay" class="text-sm text-gray-500 text-center mb-4"></p>
                            <!-- User Info Sections -->
                            <div class="w-full space-y-6">
                                <!-- Basic Information -->
                                <div>
                                    <h5 class="text-sm font-medium text-gray-900 mb-3 pb-1 border-b border-gray-200">Basic Information</h5>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div><label class="block text-sm font-medium text-gray-500 mb-1">Full Name</label><p id="modalUserName" class="text-sm text-gray-900"></p></div>
                                        <div><label class="block text-sm font-medium text-gray-500 mb-1">KK ID</label><p id="modalUserId" class="text-sm text-gray-900"></p></div>
                                        <div><label class="block text-sm font-medium text-gray-500 mb-1">Sex</label><p id="modalUserSex" class="text-sm text-gray-900"></p></div>
                                        <div><label class="block text-sm font-medium text-gray-500 mb-1">Email</label><p id="modalUserEmail" class="text-sm text-gray-900"></p></div>
                                        <div><label class="block text-sm font-medium text-gray-500 mb-1">Birthday</label><p id="modalUserBirthday" class="text-sm text-gray-900"></p></div>
                                        <div><label class="block text-sm font-medium text-gray-500 mb-1">Age</label><p id="modalUserAge" class="text-sm text-gray-900"></p></div>
                                        <div><label class="block text-sm font-medium text-gray-500 mb-1">Civil Status</label><p id="modalUserCivilStatus" class="text-sm text-gray-900"></p></div>
                                        <div><label class="block text-sm font-medium text-gray-500 mb-1">Status</label><span id="modalUserStatus" class="inline-flex px-2 py-1 rounded-full text-sm font-medium"></span></div>
                                    </div>
                                </div>
                                <!-- Address Information -->
                                <div>
                                    <h5 class="text-sm font-medium text-gray-900 mb-3 pb-1 border-b border-gray-200">Address Information</h5>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div><label class="block text-sm font-medium text-gray-500 mb-1">Barangay</label><p id="modalUserBarangayDetail" class="text-sm text-gray-900"></p></div>
                                        <div><label class="block text-sm font-medium text-gray-500 mb-1">Zone</label><p id="modalUserZone" class="text-sm text-gray-900"></p></div>
                                        <div class="col-span-2"><label class="block text-sm font-medium text-gray-500 mb-1">Complete Address</label><p id="modalUserAddress" class="text-sm text-gray-900"></p></div>
                                    </div>
                                </div>
                                <!-- Youth Classification -->
                                <div>
                                    <h5 class="text-sm font-medium text-gray-900 mb-3 pb-1 border-b border-gray-200">Youth Classification</h5>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div><label class="block text-sm font-medium text-gray-500 mb-1">Youth Classification</label><p id="modalUserYouthClassification" class="text-sm text-gray-900"></p></div>
                                        <div><label class="block text-sm font-medium text-gray-500 mb-1">Work Status</label><p id="modalUserWorkStatus" class="text-sm text-gray-900"></p></div>
                                        <div><label class="block text-sm font-medium text-gray-500 mb-1">Youth Age Group</label><p id="modalUserYouthAgeGroup" class="text-sm text-gray-900"></p></div>
                                        <div><label class="block text-sm font-medium text-gray-500 mb-1">Educational Background</label><p id="modalUserEducation" class="text-sm text-gray-900"></p></div>
                                    </div>
                                </div>
                                <!-- Voting Information -->
                                <div>
                                    <h5 class="text-sm font-medium text-gray-900 mb-3 pb-1 border-b border-gray-200">Voting Information</h5>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div><label class="block text-sm font-medium text-gray-500 mb-1">Registered SK Voter</label><span id="modalUserSKVoter" class="inline-flex px-2 py-1 rounded-full text-sm font-medium"></span></div>
                                        <div><label class="block text-sm font-medium text-gray-500 mb-1">Voted Last SK Election</label><span id="modalUserVotedSK" class="inline-flex px-2 py-1 rounded-full text-sm font-medium"></span></div>
                                        <div class="col-span-2"><label class="block text-sm font-medium text-gray-500 mb-1">Registered National Voter</label><span id="modalUserNationalVoter" class="inline-flex px-2 py-1 rounded-full text-sm font-medium"></span></div>
                                    </div>
                                </div>
                                <!-- Assembly Attendance -->
                                <div>
                                    <h5 class="text-sm font-medium text-gray-900 mb-3 pb-1 border-b border-gray-200">KK Assembly Attendance</h5>
                                    <div class="space-y-3">
                                        <div><label class="block text-sm font-medium text-gray-500 mb-1">Have you attended a KK Assembly?</label><span id="modalUserAttendedAssembly" class="inline-flex px-2 py-1 rounded-full text-sm font-medium"></span></div>
                                        <div id="assemblyTimesContainer"><label class="block text-sm font-medium text-gray-500 mb-1">How many times?</label><p id="modalUserAssemblyTimes" class="text-sm text-gray-900"></p></div>
                                        <div id="assemblyReasonContainer" class="hidden"><label class="block text-sm font-medium text-gray-500 mb-1">If No, Why?</label><p id="modalUserAssemblyReason" class="text-sm text-gray-900"></p></div>
                                    </div>
                                </div>
                            </div>
                            <!-- Action Buttons (Accept/Reject) -->
                            <div id="actionButtonsContainer" class="mt-6 flex flex-col gap-3 pt-4 border-t w-full">
                                <button id="acceptButton" onclick="acceptUser()" class="w-full py-3 px-4 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 font-semibold transition-all duration-200 flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Accept
                                </button>
                                <button id="rejectButton" onclick="rejectUser()" class="w-full py-3 px-4 bg-red-600 text-white rounded-lg shadow hover:bg-red-700 font-semibold transition-all duration-200 flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Reject
                                </button>
                                <button id="reverifyButton" onclick="reVerifyUser()" class="w-full py-3 px-4 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 font-semibold transition-all duration-200 flex items-center justify-center gap-2" style="display: none;">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Re-verify
                                </button>
                            </div>
                        </div>
                        <!-- Right: Document Preview -->
                        <div class="w-[60%] p-6 flex flex-col gap-8 items-center justify-start relative overflow-y-auto bg-white border-l border-gray-200" id="modalDocPreview">
                            <!-- Document preview will be injected here -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- KK ID Design Preview Modal -->
            <div id="kkIdModal" class="fixed inset-0 z-[9999] hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 relative">
                    <!-- Modal Header -->
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">KK ID Preview</h3>
                        <button onclick="closeKKIdModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 focus:outline-none transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Modal Body -->
                    <div class="p-6">
                        <!-- KK ID Card Design -->
                        <div class="bg-gradient-to-br from-blue-600 to-purple-700 rounded-lg p-4 text-white shadow-lg max-w-sm mx-auto">
                            <!-- Header -->
                            <div class="text-center mb-3">
                                <h4 class="text-xs font-bold mb-1">KATAASTAASANG KAGALANG ASSOCIATION</h4>
                                <p class="text-xs opacity-90">OFFICIAL MEMBERSHIP ID</p>
                            </div>
                            
                            <!-- Profile Section -->
                            <div class="flex items-center mb-3">
                                <div class="w-16 h-16 bg-white rounded mr-3 flex items-center justify-center overflow-hidden">
                                    <img id="idCardPhoto" src="" alt="Profile" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold mb-1" id="idCardName">SAMPLE NAME</p>
                                    <p class="text-xs opacity-90" id="idCardBarangay">Barangay Sample</p>
                                </div>
                            </div>
                            
                            <!-- Details -->
                            <div class="text-xs space-y-1">
                                <div class="flex justify-between">
                                    <span class="opacity-90">ID No:</span>
                                    <span class="font-medium" id="idCardNumber">KK-2025-001</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="opacity-90">Valid Until:</span>
                                    <span class="font-medium" id="idCardExpiry">Dec 2025</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="opacity-90">Zone:</span>
                                    <span class="font-medium" id="idCardZone">Zone 1</span>
                                </div>
                            </div>
                            
                            <!-- Footer -->
                            <div class="text-center mt-3 pt-2 border-t border-white border-opacity-30">
                                <p class="text-xs opacity-80">This card is property of KK Association</p>
                            </div>
                        </div>
                        
                        <div class="mt-4 text-center">
                            <p class="text-sm text-gray-600 mb-4">This is a temporary design preview</p>
                            <div class="flex gap-3">
                                <button onclick="closeKKIdModal()" class="flex-1 px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded font-medium transition-colors">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Confirmation Modal with Enhanced Dark Effect -->
            <div id="confirmationModal" class="fixed inset-0 z-[9999] hidden opacity-0 transition-all duration-300 ease-in-out">
                <!-- Dark overlay -->
                <div class="absolute inset-0 bg-black bg-opacity-60"></div>
                
                <!-- Modal content -->
                <div class="relative flex items-center justify-center min-h-screen p-4">
                    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 transform scale-95 transition-all duration-300 ease-in-out border border-gray-200" id="confirmationModalContent">
                        <!-- Close button -->
                        <button onclick="closeConfirmationModal()" class="absolute -top-3 -right-3 bg-white rounded-full p-2 shadow-xl border border-gray-300 hover:bg-gray-100 transition-all duration-200 z-10">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                        
                        <!-- Content -->
                        <div class="p-6">
                            <div class="text-center mb-6">
                                <div id="confirmationIcon" class="mx-auto mb-4"></div>
                                <h3 id="confirmationTitle" class="text-xl font-semibold mb-2 text-gray-800"></h3>
                                <p id="confirmationMessage" class="text-gray-600"></p>
                            </div>
                            
                            <!-- Reason field (for reject only) -->
                            <div id="rejectReasonContainer" class="mb-6 hidden">
                                <label for="rejectReason" class="block text-sm font-medium text-gray-700 mb-2">Reason for Rejection</label>
                                <textarea id="rejectReason" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 resize-none" placeholder="Please provide a reason for rejection..."></textarea>
                                <p id="reasonError" class="mt-1 text-red-500 text-sm hidden">Please provide a reason for rejection.</p>
                            </div>
                            
                            <!-- Buttons -->
                            <div class="flex gap-3">
                                <button onclick="closeConfirmationModal()" class="flex-1 py-2 px-4 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg font-medium transition-all duration-200">
                                    Cancel
                                </button>
                                <button id="confirmButton" class="flex-1 py-2 px-4 rounded-lg font-medium transition-all duration-200">
                                    Confirm
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Download KK List Modal - Unified Design -->
    <div id="downloadModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-[95vw] max-h-[90vh] relative overflow-hidden flex flex-col">
            <!-- Modal Header -->
            <div class="bg-white border-b border-gray-200 px-6 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">KATIPUNAN NG KABATAAN YOUTH PROFILE</h3>
                        <p class="text-sm text-gray-600 mt-1">Barangay <span class="font-semibold"><?= esc($barangay_name ?? '') ?></span> - Only verified (Accepted) KK members included</p>
                    </div>
                    <button onclick="closeDownloadModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none transition-colors p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Content -->
            <div class="flex-1 overflow-y-auto p-6">
                <!-- Document Preview Container -->
                <div class="bg-white rounded-xl shadow-sm border-2 border-gray-300 p-6">
                    <!-- Download Format Content -->
                    <div id="downloadContent" class="bg-white">
                        <!-- Header Section -->
                        <div class="text-center mb-6 print:mb-4" style="font-family: Arial, sans-serif;">
                            <!-- Document Header with Logos -->
                            <div class="flex items-center justify-center mb-4">
                                <!-- Center Text with Barangay Logo beside it -->
                                <div class="flex items-center">
                                    <!-- Barangay Logo -->
                                    <div class="text-center mr-6">
                                        <div id="kk-list-barangay-logo" class="rounded flex items-center justify-center" style="width: 50px; height: 50px; min-width: 50px; min-height: 50px; max-width: 50px; max-height: 50px;">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <!-- Center Text -->
                                    <div class="text-center">
                                        <h2 style="font-size: 10pt; font-weight: normal; color: black; margin: 0; line-height: 1.2;">Republic of the Philippines</h2>
                                        <h3 style="font-size: 10pt; font-weight: normal; color: black; margin: 0; line-height: 1.2;">Province of Camarines Sur</h3>
                                        <h3 style="font-size: 10pt; font-weight: bold; color: black; margin: 0; line-height: 1.2;">CITY OF IRIGA</h3>
                                        <h4 style="font-size: 10pt; font-weight: bold; color: black; margin: 0; line-height: 1.2;">SANGGUNIANG KABATAAN NG BARANGAY</h4>
                                        <h4 style="font-size: 10pt; font-weight: bold; color: black; margin: 0; line-height: 1.2;"><?= strtoupper(esc($barangay_name ?? 'SAMPLE BARANGAY')) ?></h4>
                                    </div>
                                    <!-- Iriga City Logo -->
                                    <div class="text-center ml-6">
                                        <div id="kk-list-iriga-logo" class="rounded flex items-center justify-center" style="width: 50px; height: 50px; min-width: 50px; min-height: 50px; max-width: 50px; max-height: 50px;">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="border-gray-300 mb-4">
                            
                            <h2 style="font-size: 10pt; font-weight: bold; color: black; margin: 16px 0 24px 0; font-family: Arial, sans-serif;">KATIPUNAN NG KABATAAN YOUTH PROFILE</h2>
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto border border-gray-400 rounded-lg">
                            <table class="w-full border-collapse border border-gray-300 rounded-lg overflow-hidden" style="font-size: 6px;">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="border border-gray-300 px-1 py-2 text-center font-bold text-gray-700 text-xs" style="width: 3%">REGION</th>
                                        <th class="border border-gray-300 px-1 py-2 text-center font-bold text-gray-700 text-xs" style="width: 4%">PROVINCE</th>
                                        <th class="border border-gray-300 px-1 py-2 text-center font-bold text-gray-700 text-xs" style="width: 6%">CITY</th>
                                        <th class="border border-gray-300 px-1 py-2 text-center font-bold text-gray-700 text-xs" style="width: 6%">BARANGAY</th>
                                        <th class="border border-gray-300 px-1 py-2 text-center font-bold text-gray-700 text-xs" style="width: 8%">NAME</th>
                                        <th class="border border-gray-300 px-1 py-2 text-center font-bold text-gray-700 text-xs" style="width: 3%">AGE</th>
                                        <th class="border border-gray-300 px-1 py-2 text-center font-bold text-gray-700 text-xs" style="width: 6%">BIRTHDAY</th>
                                        <th class="border border-gray-300 px-1 py-2 text-center font-bold text-gray-700 text-xs" style="width: 4%">SEX<br>M/F</th>
                                        <th class="border border-gray-300 px-1 py-2 text-center font-bold text-gray-700 text-xs" style="width: 6%">CIVIL<br>STATUS</th>
                                        <th class="border border-gray-300 px-1 py-2 text-center font-bold text-gray-700 text-xs" style="width: 6%">YOUTH<br>CLASSIFICATION/<br>IN/OUT/KATIPUNAN</th>
                                        <th class="border border-gray-300 px-1 py-2 text-center font-bold text-gray-700 text-xs" style="width: 6%">YOUTH<br>AGE<br>GROUP</th>
                                        <th class="border border-gray-300 px-1 py-2 text-center font-bold text-gray-700 text-xs" style="width: 5%">EMAIL<br>ADDRESS</th>
                                        <th class="border border-gray-300 px-1 py-2 text-center font-bold text-gray-700 text-xs" style="width: 5%">CONTACT<br>NUMBER</th>
                                        <th class="border border-gray-300 px-1 py-2 text-center font-bold text-gray-700 text-xs" style="width: 6%">HOME ADDRESS</th>
                                        <th class="border border-gray-300 px-1 py-2 text-center font-bold text-gray-700 text-xs" style="width: 6%">HIGHEST<br>EDUCATIONAL<br>ATTAINMENT</th>
                                        <th class="border border-gray-300 px-1 py-2 text-center font-bold text-gray-700 text-xs" style="width: 5%">WORK<br>STATUS</th>
                                        <th class="border border-gray-300 px-1 py-2 text-center font-bold text-gray-700 text-xs" style="width: 6%">Registered<br>SK<br>Voter</th>
                                        <th class="border border-gray-300 px-1 py-2 text-center font-bold text-gray-700 text-xs" style="width: 6%">Voted Last<br>SK<br>Election?</th>
                                        <th class="border border-gray-300 px-1 py-2 text-center font-bold text-gray-700 text-xs" style="width: 6%">Attended a KK<br>assembly? Y/N</th>
                                        <th class="border border-gray-300 px-1 py-2 text-center font-bold text-gray-700 text-xs" style="width: 5%">If yes, how<br>many<br>times?</th>
                                    </tr>
                                </thead>
                                <tbody id="downloadTableBody">
                                    <!-- Dynamic content will be loaded here -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Signature Section -->
                        <div class="mt-8 print:mt-6" style="font-family: Arial, sans-serif;">
                            <div class="flex justify-center items-end">
                                <div class="flex justify-between items-end" style="width: 80%; max-width: 600px;">
                                    <div class="text-center">
                                        <p style="font-size: 9pt; margin-bottom: 48px; color: black;">Prepared by:</p>
                                        <div class="border-b border-black w-48 mb-2"></div>
                                        <p id="modalSecretaryName" style="font-size: 9pt; font-weight: bold; color: black; margin: 0;">________________</p>
                                        <p style="font-size: 9pt; font-weight: bold; color: black; margin: 0;">SK Secretary</p>
                                    </div>
                                    <div class="text-center">
                                        <p style="font-size: 9pt; margin-bottom: 48px; color: black;">Approved by:</p>
                                        <div class="border-b border-black w-48 mb-2"></div>
                                        <p id="modalChairmanName" style="font-size: 9pt; font-weight: bold; color: black; margin: 0;">________________</p>
                                        <p style="font-size: 9pt; font-weight: bold; color: black; margin: 0;">SK Chairperson</p>
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
                    <div class="text-sm font-medium text-gray-700">KK Youth Profile Export</div>
                    <div class="flex gap-3">
                        <button onclick="closeDownloadModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors duration-200">
                            Close
                        </button>
                        <button onclick="printKKList()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-200 shadow-sm">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Print
                        </button>
                        <button onclick="downloadKKListPDF()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors duration-200 shadow-sm">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download PDF
                        </button>
                        <button onclick="downloadKKListWord()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-200 shadow-sm">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download Word
                        </button>
                        <button onclick="downloadKKListExcel()" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors duration-200 shadow-sm">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path>
                            </svg>
                            Download Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Toast Container (shared across SK pages) -->
    <div id="toastContainer" class="fixed top-4 right-4 z-[100000] flex flex-col gap-2 items-end pointer-events-none"></div>
    <script>
    // Barangay mapping from PHP
    const barangayMap = <?= json_encode($barangay_map) ?>;
    
    // Zone mapping from PHP
    const zoneMap = <?= json_encode($zone_map) ?>;

    // Demographic maps from backend (keys align with DemographicsHelper::allMapsForJs)
    const civilStatusMap = <?= json_encode($field_mappings['civilStatusMap'] ?? []) ?>;
    const youthClassificationMap = <?= json_encode($field_mappings['youthClassificationMap'] ?? []) ?>;
    const ageGroupMap = <?= json_encode($field_mappings['ageGroupMap'] ?? []) ?>;
    const educationMap = <?= json_encode($field_mappings['educationMap'] ?? []) ?>;
    const workStatusMap = <?= json_encode($field_mappings['workStatusMap'] ?? []) ?>;
    const howManyTimesMap = <?= json_encode($field_mappings['howManyTimesMap'] ?? []) ?>;
    const noWhyMap = <?= json_encode($field_mappings['noWhyMap'] ?? []) ?>;
    
    let currentUserId = null;
    let currentBirthCertFile = null;
    let currentUploadIdFile = null;
    let currentUploadIdBackFile = null;
    let isAfterReVerify = false;
    let panzoomInstances = {};

    // Initialize Panzoom with Controls for an image
    function initializePanzoom(containerId, imageId) {
        const container = document.getElementById(containerId);
        const image = document.getElementById(imageId);
        
        if (!container || !image) return;
        
        // Create panzoom instance
        const panzoom = Panzoom(image, {
            maxScale: 10,
            minScale: 0.5,
            contain: 'outside',
            startScale: 1,
            startX: 0,
            startY: 0
        });
        
        // Store the instance
        panzoomInstances[containerId] = panzoom;
        
        // Create custom controls
        const controlsContainer = document.createElement('div');
        controlsContainer.className = 'panzoom-controls';
        
        // Zoom In button
        const zoomInBtn = document.createElement('button');
        zoomInBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/><path d="M11 8v6"/><path d="M8 11h6"/></svg>';
        zoomInBtn.title = 'Zoom In';
        zoomInBtn.onclick = () => panzoom.zoomIn();
        
        // Zoom Out button
        const zoomOutBtn = document.createElement('button');
        zoomOutBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/><path d="M8 11h6"/></svg>';
        zoomOutBtn.title = 'Zoom Out';
        zoomOutBtn.onclick = () => panzoom.zoomOut();
        
        // Reset button
        const resetBtn = document.createElement('button');
        resetBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 1 0 18 0 9 9 0 0 0-18 0z"/><path d="M9 12l2 2 4-4"/></svg>';
        resetBtn.title = 'Reset View';
        resetBtn.onclick = () => {
            panzoom.reset();
            // Reset rotation and flip states
            image.dataset.rotation = '0';
            image.dataset.flippedH = 'false';
            image.dataset.flippedV = 'false';
            // Let panzoom handle the transform
            image.style.transform = '';
        };
        
        // Toggle Zoom Level button
        const toggleBtn = document.createElement('button');
        toggleBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><text x="12" y="16" text-anchor="middle" font-size="12" font-weight="bold" fill="currentColor">1:1</text></svg>';
        toggleBtn.title = 'Toggle Zoom Level (1:1)';
        toggleBtn.onclick = () => {
            const currentScale = panzoom.getScale();
            if (currentScale === 1) {
                panzoom.zoomTo(2);
            } else {
                panzoom.reset();
            }
        };
        
        // Rotate Left button
        const rotateLeftBtn = document.createElement('button');
        rotateLeftBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M18.37 15a9 9 0 1 1-1.56-8.28L3 8"/></svg>';
        rotateLeftBtn.title = 'Rotate Left (Counter-clockwise)';
        rotateLeftBtn.onclick = () => {
            const currentRotation = parseInt(image.dataset.rotation || '0');
            const newRotation = currentRotation - 90;
            image.dataset.rotation = newRotation;
            // Get current panzoom transform and add rotation
            const currentScale = panzoom.getScale();
            const currentX = panzoom.getPan().x;
            const currentY = panzoom.getPan().y;
            const isFlippedH = image.dataset.flippedH === 'true';
            const isFlippedV = image.dataset.flippedV === 'true';
            const horizontalFlip = isFlippedH ? ' scaleX(-1)' : '';
            const verticalFlip = isFlippedV ? ' scaleY(-1)' : '';
            // Apply rotation to the image element directly
            image.style.transform = `translate(${currentX}px, ${currentY}px) scale(${currentScale}) rotate(${newRotation}deg)${horizontalFlip}${verticalFlip}`;
        };
        
        // Rotate Right button
        const rotateRightBtn = document.createElement('button');
        rotateRightBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M5.63 15a9 9 0 1 0 1.56-8.28L21 8"/></svg>';
        rotateRightBtn.title = 'Rotate Right (Clockwise)';
        rotateRightBtn.onclick = () => {
            const currentRotation = parseInt(image.dataset.rotation || '0');
            const newRotation = currentRotation + 90;
            image.dataset.rotation = newRotation;
            // Get current panzoom transform and add rotation
            const currentScale = panzoom.getScale();
            const currentX = panzoom.getPan().x;
            const currentY = panzoom.getPan().y;
            const isFlippedH = image.dataset.flippedH === 'true';
            const isFlippedV = image.dataset.flippedV === 'true';
            const horizontalFlip = isFlippedH ? ' scaleX(-1)' : '';
            const verticalFlip = isFlippedV ? ' scaleY(-1)' : '';
            // Apply rotation to the image element directly
            image.style.transform = `translate(${currentX}px, ${currentY}px) scale(${currentScale}) rotate(${newRotation}deg)${horizontalFlip}${verticalFlip}`;
        };
        
        // Flip Horizontal button
        const flipHBtn = document.createElement('button');
        flipHBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 3H5a2 2 0 0 0-2 2v14c0 1.1.9 2 2 2h3"/><path d="M16 3h3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-3"/><path d="M12 20v2"/><path d="M12 14v2"/><path d="M12 8v2"/><path d="M12 2v2"/></svg>';
        flipHBtn.title = 'Flip Horizontal (Mirror)';
        flipHBtn.onclick = () => {
            const isFlippedH = image.dataset.flippedH === 'true';
            image.dataset.flippedH = !isFlippedH;
            // Get current panzoom transform and add flip
            const currentScale = panzoom.getScale();
            const currentX = panzoom.getPan().x;
            const currentY = panzoom.getPan().y;
            const currentRotation = parseInt(image.dataset.rotation || '0');
            const isFlippedV = image.dataset.flippedV === 'true';
            const verticalFlip = isFlippedV ? ' scaleY(-1)' : '';
            if (!isFlippedH) {
                image.style.transform = `translate(${currentX}px, ${currentY}px) scale(${currentScale}) rotate(${currentRotation}deg) scaleX(-1)${verticalFlip}`;
            } else {
                image.style.transform = `translate(${currentX}px, ${currentY}px) scale(${currentScale}) rotate(${currentRotation}deg)${verticalFlip}`;
            }
        };
        
        // Flip Vertical button
        const flipVBtn = document.createElement('button');
        flipVBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 3H5a2 2 0 0 0-2 2v14c0 1.1.9 2 2 2h3"/><path d="M16 3h3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-3"/><path d="M12 20v2"/><path d="M12 14v2"/><path d="M12 8v2"/><path d="M12 2v2"/></svg>';
        flipVBtn.title = 'Flip Vertical (Mirror)';
        flipVBtn.style.transform = 'rotate(90deg)';
        flipVBtn.onclick = () => {
            const isFlippedV = image.dataset.flippedV === 'true';
            image.dataset.flippedV = !isFlippedV;
            // Get current panzoom transform and add flip
            const currentScale = panzoom.getScale();
            const currentX = panzoom.getPan().x;
            const currentY = panzoom.getPan().y;
            const currentRotation = parseInt(image.dataset.rotation || '0');
            const isFlippedH = image.dataset.flippedH === 'true';
            const horizontalFlip = isFlippedH ? ' scaleX(-1)' : '';
            if (!isFlippedV) {
                image.style.transform = `translate(${currentX}px, ${currentY}px) scale(${currentScale}) rotate(${currentRotation}deg)${horizontalFlip} scaleY(-1)`;
            } else {
                image.style.transform = `translate(${currentX}px, ${currentY}px) scale(${currentScale}) rotate(${currentRotation}deg)${horizontalFlip}`;
            }
        };
        
        // Add all buttons to controls container
        controlsContainer.appendChild(zoomInBtn);
        controlsContainer.appendChild(zoomOutBtn);
        controlsContainer.appendChild(toggleBtn);
        controlsContainer.appendChild(rotateLeftBtn);
        controlsContainer.appendChild(rotateRightBtn);
        controlsContainer.appendChild(flipHBtn);
        controlsContainer.appendChild(flipVBtn);
        controlsContainer.appendChild(resetBtn);
        
        // Add controls to container
        container.appendChild(controlsContainer);
        
        return panzoom;
    }

    // Clean up panzoom instances
    function cleanupPanzoom(containerId) {
        if (panzoomInstances[containerId]) {
            panzoomInstances[containerId].destroy();
            delete panzoomInstances[containerId];
        }
    }

    function openReviewModal(userId, birthCertFile, uploadIdFile, userInfo, uploadIdBackFile) {
        // Remove the old action container show - we'll handle this based on status
        currentUserId = userId;
        currentBirthCertFile = birthCertFile;
        currentUploadIdFile = uploadIdFile;
        currentUploadIdBackFile = uploadIdBackFile || null;
        // Fetch user info via AJAX
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
                    var zoneMap = {
                        1: 'Zone 1', 2: 'Zone 2', 3: 'Zone 3', 4: 'Zone 4', 5: 'Zone 5',
                        6: 'Zone 6', 7: 'Zone 7', 8: 'Zone 8', 9: 'Zone 9', 10: 'Zone 10'
                    };
                    var noWhyMap = <?= json_encode($field_mappings['noWhyMap'] ?? []) ?>;
                    var fullName = u.first_name + ' ' + (u.middle_name ? u.middle_name + ' ' : '') + u.last_name + (u.suffix ? ', ' + u.suffix : '');
                    var barangayStr = barangayMap[u.barangay] || u.barangay || '';
                    // User type mapping  
                    var userTypeMap = {
                        1: 'KK Member',
                        2: 'SK Official', 
                        3: 'Pederasyon Officer | SK Chairperson'
                    };
                    var userTypeStr = userTypeMap[u.user_type] || 'Unknown';
                    $('#modalUserFullName').text(fullName);
                    $('#modalUserName').text(fullName);
                    $('#modalUserBarangay').text(userTypeStr);
                    $('#modalUserBarangayDetail').text(barangayStr);
                    $('#modalUserId').text(u.user_id ? u.user_id : '-');
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
                    const csKey = (u.civil_status != null) ? String(u.civil_status) : '';
                    $('#modalUserCivilStatus').text(civilStatusMap[csKey] || '');
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
                    var addressParts = [];
                    if (u.zone_purok) addressParts.push(zoneMap[u.zone_purok] || u.zone_purok);
                    if (barangayStr) addressParts.push(barangayStr);
                    addressParts.push('Iriga City');
                    addressParts.push('Camarines Sur');
                    addressParts.push('Region 5');
                    var fullAddress = addressParts.join(', ');
                    $('#modalUserAddress').text(fullAddress);
                    const ycKey = (u.youth_classification != null) ? String(u.youth_classification) : '';
                    const wsKey = (u.work_status != null) ? String(u.work_status) : '';
                    const agKey = (u.age_group != null) ? String(u.age_group) : '';
                    const edKey = (u.educational_background != null) ? String(u.educational_background) : '';
                    $('#modalUserYouthClassification').text(youthClassificationMap[ycKey] || '');
                    $('#modalUserWorkStatus').text(workStatusMap[wsKey] || '');
                    $('#modalUserYouthAgeGroup').text(ageGroupMap[agKey] || '');
                    $('#modalUserEducation').text(educationMap[edKey] || '');
                    function setYesNoColor(selector, value) {
                        let text = '';
                        let colorClass = '';
                        const v = (value != null) ? String(value) : '';
                        if (v === '1') {
                            text = 'Yes';
                            colorClass = 'bg-green-100 text-green-800';
                        } else if (v === '0') {
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
                    // Profile picture with fallback
                    (function(){
                        const pp = u.profile_picture || '';
                        const defaultAvatar = window.KNECT_DEFAULTS?.avatar || '<?= get_default_image('avatar') ?>';
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
                        // Set up automatic fallback
                        $img.attr('data-type', 'avatar');
                        if (window.KNECTImages) KNECTImages.setupImageFallback($img[0], 'avatar');
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
                    
                    // Show/Hide action buttons based on status in review modal
                    const actionContainer = $("#actionButtonsContainer");
                    const generateIdButton = $("#generateIdButton");
                    const acceptButton = $("#acceptButton");
                    const rejectButton = $("#rejectButton");
                    const reverifyButton = $("#reverifyButton");
                    
                    if (u.status == 1) { // Pending
                        actionContainer.show();
                        generateIdButton.hide(); // Hide Generate ID for pending users
                        acceptButton.show();
                        rejectButton.show();
                        reverifyButton.hide();
                    } else if (u.status == 2) { // Accepted
                        actionContainer.show();
                        generateIdButton.show(); // Show Generate ID for accepted users
                        acceptButton.hide();
                        rejectButton.hide();
                        reverifyButton.show();
                    } else if (u.status == 3) { // Rejected
                        actionContainer.show();
                        generateIdButton.hide(); // Hide Generate ID for rejected users
                        acceptButton.hide();
                        rejectButton.hide();
                        reverifyButton.show();
                    } else {
                        actionContainer.hide();
                    }
                } else {
                    alert('User not found.');
                }
            },
            error: function() {
                alert('Failed to fetch user info.');
            }
        });
        let modalHtml = '';
        if (birthCertFile) {
            let url = '<?= base_url('/previewDocument/certificate/') ?>' + birthCertFile;
            let ext = birthCertFile.split('.').pop().toLowerCase();
            modalHtml += `<div class="w-full border border-gray-200 rounded-lg bg-gray-50 p-4">
                <div class='font-semibold text-gray-700 mb-2'>Birth Certificate</div>
                <div class='relative w-full'>`;
            if (['pdf'].includes(ext)) {
                modalHtml += `<iframe src='${url}' style='width: 100%; height: 600px;' class='rounded border' frameborder='0'></iframe>`;
            } else if (['jpg','jpeg','png','gif','webp'].includes(ext)) {
                modalHtml += `<div id='certPreviewWrapper' class='f-panzoom' style='max-height: 600px;'>
                    <img id='modalPreviewImgCert' class='f-panzoom__content rounded' src='${url}' alt='Birth Certificate Image' style='width: 100%; height: auto; display: block;'>
                </div>`;
            } else {
                modalHtml += `<div class='text-red-600 p-4'>Cannot preview this file type.</div>`;
            }
            modalHtml += `</div></div>`;
        }
        if (uploadIdFile) {
            let url = '<?= base_url('/previewDocument/id/') ?>' + uploadIdFile;
            let ext = uploadIdFile.split('.').pop().toLowerCase();
            modalHtml += `<div class="w-full border border-gray-200 rounded-lg mb-6 bg-gray-50 p-4">
                <div class='font-semibold text-gray-700 mb-2'>ID</div>
                <div class='relative w-full'>`;
            if (['pdf'].includes(ext)) {
                modalHtml += `<iframe src='${url}' style='width: 100%; height: 600px;' class='rounded border' frameborder='0'></iframe>`;
            } else if (['jpg','jpeg','png','gif','webp'].includes(ext)) {
                modalHtml += `<div id='idPreviewWrapper' class='f-panzoom' style='max-height: 600px;'>
                    <img id='modalPreviewImgId' class='f-panzoom__content rounded' src='${url}' alt='ID Image' style='width: 100%; height: auto; display: block;'>
                </div>`;
            } else {
                modalHtml += `<div class='text-red-600 p-4'>Cannot preview this file type.</div>`;
            }
            modalHtml += `</div></div>`;
        }
        if (uploadIdBackFile) {
            let urlBack = '<?= base_url('/previewDocument/id/') ?>' + uploadIdBackFile;
            let extBack = uploadIdBackFile.split('.').pop().toLowerCase();
            modalHtml += `<div class="w-full border border-gray-200 rounded-lg mb-6 bg-gray-50 p-4">
                <div class='font-semibold text-gray-700 mb-2'>ID (Back)</div>
                <div class='relative w-full'>`;
            if (['pdf'].includes(extBack)) {
                modalHtml += `<iframe src='${urlBack}' style='width: 100%; height: 600px;' class='rounded border' frameborder='0'></iframe>`;
            } else if (['jpg','jpeg','png','gif','webp'].includes(extBack)) {
                modalHtml += `<div id='idPreviewWrapperBack' class='f-panzoom' style='max-height: 600px;'>
                    <img id='modalPreviewImgIdBack' class='f-panzoom__content rounded' src='${urlBack}' alt='ID Back Image' style='width: 100%; height: auto; display: block;'>
                </div>`;
            } else {
                modalHtml += `<div class='text-red-600 p-4'>Cannot preview this file type.</div>`;
            }
            modalHtml += `</div></div>`;
        }
        if (!birthCertFile && !uploadIdFile && !uploadIdBackFile) {
            modalHtml = `<div class='text-red-600 p-4'>No birth certificate or ID uploaded for this user.</div>`;
        }
        document.getElementById('modalDocPreview').innerHTML = modalHtml;
        
        // Set modal title for verification
        document.querySelector('#previewModal h3').textContent = 'User Verification';
        
        document.getElementById('previewModal').classList.remove('hidden');
        
        // Initialize Panzoom for images after DOM is updated
        setTimeout(() => {
            if (birthCertFile && ['jpg','jpeg','png','gif','webp'].includes(birthCertFile.split('.').pop().toLowerCase())) {
                const certContainer = document.getElementById('certPreviewWrapper');
                if (certContainer) {
                    try {
                        // Try different ways to access Panzoom and Controls
                        let PanzoomClass = Panzoom || window.Panzoom;
                        let ControlsClass = Controls || window.Controls;
                        
                        if (PanzoomClass && ControlsClass) {
                            const certPanzoom = new PanzoomClass(certContainer, {
                                Controls: {
                                    display: [
                                        'zoomIn',
                                        'zoomOut',
                                        'toggle1to1',
                                        'toggleFull',
                                        'rotateCCW',
                                        'rotateCW',
                                        'flipX',
                                        'flipY',
                                        'reset'
                                    ]
                                }
                            }, { Controls: ControlsClass });
                        } else {
                            console.warn('Panzoom or Controls not available');
                        }
                    } catch (error) {
                        console.error('Error initializing cert Panzoom:', error);
                    }
                }
            }
            if (uploadIdFile && ['jpg','jpeg','png','gif','webp'].includes(uploadIdFile.split('.').pop().toLowerCase())) {
                const idContainer = document.getElementById('idPreviewWrapper');
                if (idContainer) {
                    try {
                        // Try different ways to access Panzoom and Controls
                        let PanzoomClass = Panzoom || window.Panzoom;
                        let ControlsClass = Controls || window.Controls;
                        
                        if (PanzoomClass && ControlsClass) {
                            const idPanzoom = new PanzoomClass(idContainer, {
                                Controls: {
                                    display: [
                                        'zoomIn',
                                        'zoomOut',
                                        'toggle1to1',
                                        'toggleFull',
                                        'rotateCCW',
                                        'rotateCW',
                                        'flipX',
                                        'flipY',
                                        'reset'
                                    ]
                                }
                            }, { Controls: ControlsClass });
                        } else {
                            console.warn('Panzoom or Controls not available');
                        }
                    } catch (error) {
                        console.error('Error initializing ID Panzoom:', error);
                    }
                }
            }
            if (uploadIdBackFile && ['jpg','jpeg','png','gif','webp'].includes(uploadIdBackFile.split('.').pop().toLowerCase())) {
                const idBackContainer = document.getElementById('idPreviewWrapperBack');
                if (idBackContainer) {
                    try {
                        let PanzoomClass = Panzoom || window.Panzoom;
                        let ControlsClass = Controls || window.Controls;
                        if (PanzoomClass && ControlsClass) {
                            const idBackPanzoom = new PanzoomClass(idBackContainer, {
                                Controls: {
                                    display: [
                                        'zoomIn',
                                        'zoomOut',
                                        'toggle1to1',
                                        'toggleFull',
                                        'rotateCCW',
                                        'rotateCW',
                                        'flipX',
                                        'flipY',
                                        'reset'
                                    ]
                                }
                            }, { Controls: ControlsClass });
                        } else {
                            console.warn('Panzoom or Controls not available');
                        }
                    } catch (error) {
                        console.error('Error initializing ID Back Panzoom:', error);
                    }
                }
            }
        }, 100);
    }

    function closePreviewModal(reloadTable = false) {
        document.getElementById('previewModal').classList.add('hidden');
        const userInfoElem = document.getElementById('modalUserInfo');
        if (userInfoElem) userInfoElem.innerHTML = '';
        
    // Clean up Panzoom instances
        cleanupPanzoom('certPreviewWrapper');
        cleanupPanzoom('idPreviewWrapper');
    cleanupPanzoom('idPreviewWrapperBack');
        
        document.getElementById('modalDocPreview').innerHTML = '';
        
        // Check if we should reload the table (after re-verification)
        const shouldReload = reloadTable || isAfterReVerify;
        
        // Show notification if closing after re-verification
        if (isAfterReVerify) {
            showNotification('User status updated successfully', 'success');
        }
        
    currentUserId = null;
    currentBirthCertFile = null;
    currentUploadIdFile = null;
    currentUploadIdBackFile = null;
        isAfterReVerify = false;
        
        // Reload table data if needed (after re-verification)
        if (shouldReload) {
            setTimeout(() => {
                location.reload();
            }, 500);
        }
    }

    function showConfirmationModal(type) {
        const modal = document.getElementById('confirmationModal');
        const modalContent = document.getElementById('confirmationModalContent');
        const title = document.getElementById('confirmationTitle');
        const message = document.getElementById('confirmationMessage');
        const icon = document.getElementById('confirmationIcon');
        const confirmBtn = document.getElementById('confirmButton');
        const rejectReasonContainer = document.getElementById('rejectReasonContainer');
        const reasonError = document.getElementById('reasonError');
        
        // Reset reason field
        document.getElementById('rejectReason').value = '';
        reasonError.classList.add('hidden');
        
        if (type === 'accept') {
            title.textContent = 'Accept User';
            message.textContent = 'Are you sure you want to accept this user? This action cannot be undone.';
            icon.innerHTML = `<svg class="w-16 h-16 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>`;
            confirmBtn.className = 'flex-1 py-2 px-4 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-all duration-200';
            confirmBtn.textContent = 'Accept';
            rejectReasonContainer.classList.add('hidden');
            confirmBtn.onclick = handleAcceptUser;
        } else if (type === 'reverify') {
            title.textContent = 'Re-verify User';
            message.textContent = 'Are you sure you want to re-verify this user? This will change their status to pending and allow them to be reviewed again.';
            icon.innerHTML = `<svg class="w-16 h-16 text-blue-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>`;
            confirmBtn.className = 'flex-1 py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-all duration-200';
            confirmBtn.textContent = 'Re-verify';
            rejectReasonContainer.classList.add('hidden');
            confirmBtn.onclick = handleReVerifyUser;
        } else {
            title.textContent = 'Reject User';
            message.textContent = 'Are you sure you want to reject this user? This action cannot be undone.';
            icon.innerHTML = `<svg class="w-16 h-16 text-red-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>`;
            confirmBtn.className = 'flex-1 py-2 px-4 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-all duration-200';
            confirmBtn.textContent = 'Reject';
            rejectReasonContainer.classList.remove('hidden');
            confirmBtn.onclick = () => {
                const reason = document.getElementById('rejectReason').value.trim();
                if (!reason) {
                    reasonError.classList.remove('hidden');
                    return;
                }
                handleRejectUser(reason);
            };
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

    function closeConfirmationModal() {
        const modal = document.getElementById('confirmationModal');
        const modalContent = document.getElementById('confirmationModalContent');
        
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

    function acceptUser() {
        showConfirmationModal('accept');
    }

    function rejectUser() {
        showConfirmationModal('reject');
    }

    function reVerifyUser() {
        showConfirmationModal('reverify');
    }

    // Close modal when clicking outside of it
    document.getElementById('confirmationModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeConfirmationModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('confirmationModal');
            if (!modal.classList.contains('hidden')) {
                closeConfirmationModal();
            }
        }
    });

    // Add functions to handle API calls
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

    function handleAcceptUser() {
        if (!currentUserId) return;
        fetch(`<?= rtrim(base_url('/approved'), '/') ?>/${currentUserId}`, {
            method: 'POST'
        })
        .then(async response => {
            const text = await response.text();
            let data = {};
            try {
                data = JSON.parse(text);
            } catch (e) {
                showNotification('An error occurred while accepting the user', 'error');
                return;
            }
            if (response.ok && data.success) {
                showNotification('User accepted successfully', 'success');
                closeConfirmationModal();
                closePreviewModal();
                setTimeout(() => location.reload(), 1200);
            } else {
                showNotification('Failed to accept user: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while accepting the user', 'error');
        });
    }

    function handleRejectUser(reason) {
        if (!currentUserId) return;
        const formData = new FormData();
        formData.append('reason', reason);
        fetch(`<?= rtrim(base_url('/reject'), '/') ?>/${currentUserId}`, {
            method: 'POST',
            body: formData
        })
        .then(async response => {
            const text = await response.text();
            let data = {};
            try {
                data = JSON.parse(text);
            } catch (e) {
                showNotification('An error occurred while rejecting the user', 'error');
                return;
            }
            if (response.ok && data.success) {
                showNotification('User rejected successfully', 'success');
                closeConfirmationModal();
                closePreviewModal();
                setTimeout(() => location.reload(), 1200);
            } else {
                showNotification('Failed to reject user: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while rejecting the user', 'error');
        });
    }

    function handleReVerifyUser() {
        if (!currentUserId) return;
        fetch(`<?= rtrim(base_url('/reverify'), '/') ?>/${currentUserId}`, {
            method: 'POST'
        })
        .then(async response => {
            const text = await response.text();
            let data = {};
            try {
                data = JSON.parse(text);
            } catch (e) {
                showNotification('An error occurred while re-verifying the user', 'error');
                return;
            }
            if (response.ok && data.success) {
                // Don't show notification here - let closePreviewModal handle it
                
                // Store the values locally before clearing them
                const userId = currentUserId;
                const birthCert = currentBirthCertFile;
                const uploadId = currentUploadIdFile;
                
                closeConfirmationModal();
                
                // Set the flag to indicate we're after re-verify
                isAfterReVerify = true;
                
                // Close the modal - this will trigger the success notification
                closePreviewModal();
            } else {
                showNotification('Failed to re-verify user: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while re-verifying the user', 'error');
        });
    }

        // DataTables logic
        $(document).ready(function () {
            const table = $('#kkTable').DataTable({
                fixedColumns: {
                    leftColumns: 0,
                    rightColumns: 1
                },
                scrollCollapse: true,
                scrollY: '300px',
                scrollX: true,
                paging: true,
                info: true,
                language: {
                    search: "",
                    searchPlaceholder: "Search..."
                },
                initComplete: function () {
                    $('#kkTable_wrapper').addClass('text-sm text-gray-700');
                    $('#kkTable_length label').addClass('inline-flex items-center gap-2');
                    $('#kkTable_length select').addClass('border border-gray-300 rounded px-2 py-1');
                    $('#kkTable_info').addClass('mt-2 text-gray-600');
                    $('#kkTable_paginate').addClass('mt-4');
                    $('#kkTable_paginate span a').addClass('px-2 py-1 border rounded mx-1');
                    // Populate zone filter options
                    populateZoneFilter();
                    
                    // Initialize "All" tab as active by default
                    $('.status-tab[data-status="all"]').trigger('click');
                }
            });

        // Status tab click handler
        $(document).on('click', '.status-tab', function() {
            // Remove active class from all tabs and restore their original colors
            $('.status-tab').removeClass('active bg-blue-500 text-white');
            $('.status-tab[data-status="all"]').removeClass('bg-blue-500 text-white').addClass('bg-gray-200');
            $('.status-tab[data-status="Pending"]').removeClass('bg-blue-500 text-white').addClass('bg-yellow-100');
            $('.status-tab[data-status="Accepted"]').removeClass('bg-blue-500 text-white').addClass('bg-green-100');
            $('.status-tab[data-status="Rejected"]').removeClass('bg-blue-500 text-white').addClass('bg-red-100');
            
            // Add active class to clicked tab
            $(this).removeClass('bg-gray-200 bg-yellow-100 bg-green-100 bg-red-100').addClass('active bg-blue-500 text-white');
            
            const status = $(this).data('status');
            if (status === 'all') {
                table.column(7).search('').draw();
            } else {
                table.column(7).search(status).draw();
            }
        });
        
        // Function to populate zone filter dropdown
        function populateZoneFilter() {
            const zones = new Set();
            $('#kkTable tbody tr').each(function() {
                const zoneCell = $(this).find('td').eq(2); // Zone is now in the 3rd column (0-indexed: 2)
                if (zoneCell.length) {
                    const zoneText = zoneCell.text().trim();
                    if (zoneText && zoneText !== '-') {
                        zones.add(zoneText);
                    }
                }
            });
            
            //
            
            const zoneFilter = $('#zoneFilter');
            Array.from(zones).sort().forEach(zone => {
                zoneFilter.append(`<option value="${zone}">${zone}</option>`);
            });
        }
        
        // Zone filter dropdown
        $('#zoneFilter').on('change', function() {
            const zoneValue = $(this).val();
            // Apply zone filter (zone is now in column 2 - 0-indexed)
            table.column(2).search(zoneValue).draw();
        });
        
        // Clear filters button
        $('#clearFilters').on('click', function() {
            // Reset zone filter
            $('#zoneFilter').val('');
            
            // Reset status tabs to their original colors
            $('.status-tab').removeClass('active bg-blue-500 text-white');
            $('.status-tab[data-status="all"]').removeClass('bg-blue-500 text-white').addClass('bg-gray-200');
            $('.status-tab[data-status="Pending"]').removeClass('bg-blue-500 text-white').addClass('bg-yellow-100');
            $('.status-tab[data-status="Accepted"]').removeClass('bg-blue-500 text-white').addClass('bg-green-100');
            $('.status-tab[data-status="Rejected"]').removeClass('bg-blue-500 text-white').addClass('bg-red-100');
            
            // Clear all column searches
            table.columns().search('').draw();
        });
    });

    // Add JS for openViewModal
    function openViewModal(userId, birthCertFile, uploadIdFile, userInfo, uploadIdBackFile) {
        currentUserId = userId;
        currentBirthCertFile = birthCertFile;
        currentUploadIdFile = uploadIdFile;
        currentUploadIdBackFile = uploadIdBackFile || null;
        // Fetch user info via AJAX (same as openReviewModal)
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
                    var zoneMap = {
                        1: 'Zone 1', 2: 'Zone 2', 3: 'Zone 3', 4: 'Zone 4', 5: 'Zone 5',
                        6: 'Zone 6', 7: 'Zone 7', 8: 'Zone 8', 9: 'Zone 9', 10: 'Zone 10'
                    };
                    var noWhyMap = <?= json_encode($field_mappings['noWhyMap'] ?? []) ?>;
                    var fullName = u.first_name + ' ' + (u.middle_name ? u.middle_name + ' ' : '') + u.last_name + (u.suffix ? ', ' + u.suffix : '');
                    var barangayStr = barangayMap[u.barangay] || u.barangay || '';
                    // User type mapping  
                    var userTypeMap = {
                        1: 'KK Member',
                        2: 'SK Official', 
                        3: 'Pederasyon Officer | SK Chairperson'
                    };
                    var userTypeStr = userTypeMap[u.user_type] || 'Unknown';
                    $('#modalUserFullName').text(fullName);
                    $('#modalUserName').text(fullName);
                    $('#modalUserBarangay').text(userTypeStr);
                    $('#modalUserBarangayDetail').text(barangayStr);
                    $('#modalUserId').text(u.user_id ? u.user_id : '-');
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
                    var addressParts = [];
                    if (u.zone_purok) addressParts.push(zoneMap[u.zone_purok] || u.zone_purok);
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
                    // Robust profile picture resolution for modal (supports absolute URL, relative path, or filename)
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
                    
                    // Show/Hide action buttons based on status for VIEW modal
                    const actionContainer = $("#actionButtonsContainer");
                    const generateIdButton = $("#generateIdButton");
                    const acceptButton = $("#acceptButton");
                    const rejectButton = $("#rejectButton");
                    const reverifyButton = $("#reverifyButton");
                    
                    //
                    
                    if (u.status == 1) { // Pending - hide all buttons in view modal
                        //
                        actionContainer.hide();
                    } else if (u.status == 2) { // Accepted - show Generate ID and Re-verify
                        //
                        actionContainer.show();
                        generateIdButton.show(); // Show Generate ID for accepted users
                        acceptButton.hide();
                        rejectButton.hide();
                        reverifyButton.show();
                    } else if (u.status == 3) { // Rejected - show only Re-verify
                        //
                        actionContainer.show();
                        generateIdButton.hide(); // Hide Generate ID for rejected users
                        acceptButton.hide();
                        rejectButton.hide();
                        reverifyButton.show();
                    } else {
                        //
                        actionContainer.hide();
                    }
                } else {
                    alert('User not found.');
                }
            },
            error: function() {
                alert('Failed to fetch user info.');
            }
        });
        let modalHtml = '';
        if (birthCertFile) {
            let url = '<?= base_url('/previewDocument/certificate/') ?>' + birthCertFile;
            let ext = birthCertFile.split('.').pop().toLowerCase();
            modalHtml += `<div class="w-full border border-gray-200 rounded-lg bg-gray-50 p-4">
                <div class='font-semibold text-gray-700 mb-2'>Birth Certificate</div>
                <div class='relative w-full'>`;
            if (['pdf'].includes(ext)) {
                modalHtml += `<iframe src='${url}' style='width: 100%; height: 600px;' class='rounded border' frameborder='0'></iframe>`;
            } else if (['jpg','jpeg','png','gif','webp'].includes(ext)) {
                modalHtml += `<div id='certPreviewWrapper' class='f-panzoom' style='max-height: 600px;'>
                    <img id='modalPreviewImgCert' class='f-panzoom__content rounded' src='${url}' alt='Birth Certificate Image' style='width: 100%; height: auto; display: block;'>
                </div>`;
            } else {
                modalHtml += `<div class='text-red-600 p-4'>Cannot preview this file type.</div>`;
            }
            modalHtml += `</div></div>`;
        }
        if (uploadIdFile) {
            let url = '<?= base_url('/previewDocument/id/') ?>' + uploadIdFile;
            let ext = uploadIdFile.split('.').pop().toLowerCase();
            modalHtml += `<div class="w-full border border-gray-200 rounded-lg mb-6 bg-gray-50 p-4">
                <div class='font-semibold text-gray-700 mb-2'>ID</div>
                <div class='relative w-full'>`;
            if (['pdf'].includes(ext)) {
                modalHtml += `<iframe src='${url}' style='width: 100%; height: 600px;' class='rounded border' frameborder='0'></iframe>`;
            } else if (['jpg','jpeg','png','gif','webp'].includes(ext)) {
                modalHtml += `<div id='idPreviewWrapper' class='f-panzoom' style='max-height: 600px;'>
                    <img id='modalPreviewImgId' class='f-panzoom__content rounded' src='${url}' alt='ID Image' style='width: 100%; height: auto; display: block;'>
                </div>`;
            } else {
                modalHtml += `<div class='text-red-600 p-4'>Cannot preview this file type.</div>`;
            }
            modalHtml += `</div></div>`;
        }
        if (uploadIdBackFile) {
            let urlBack = '<?= base_url('/previewDocument/id/') ?>' + uploadIdBackFile;
            let extBack = uploadIdBackFile.split('.').pop().toLowerCase();
            modalHtml += `<div class="w-full border border-gray-200 rounded-lg mb-6 bg-gray-50 p-4">
                <div class='font-semibold text-gray-700 mb-2'>ID (Back)</div>
                <div class='relative w-full'>`;
            if (['pdf'].includes(extBack)) {
                modalHtml += `<iframe src='${urlBack}' style='width: 100%; height: 600px;' class='rounded border' frameborder='0'></iframe>`;
            } else if (['jpg','jpeg','png','gif','webp'].includes(extBack)) {
                modalHtml += `<div id='idPreviewWrapperBack' class='f-panzoom' style='max-height: 600px;'>
                    <img id='modalPreviewImgIdBack' class='f-panzoom__content rounded' src='${urlBack}' alt='ID Back Image' style='width: 100%; height: auto; display: block;'>
                </div>`;
            } else {
                modalHtml += `<div class='text-red-600 p-4'>Cannot preview this file type.</div>`;
            }
            modalHtml += `</div></div>`;
        }
        if (!birthCertFile && !uploadIdFile && !uploadIdBackFile) {
            modalHtml = `<div class='text-red-600 p-4'>No birth certificate or ID uploaded for this user.</div>`;
        }
        document.getElementById('modalDocPreview').innerHTML = modalHtml;
        
        // Set modal title for profile view
        document.querySelector('#previewModal h3').textContent = 'User Profile';
        
        document.getElementById('previewModal').classList.remove('hidden');
        // Initialize Panzoom for images after DOM is updated
        setTimeout(() => {
            if (birthCertFile && ['jpg','jpeg','png','gif','webp'].includes(birthCertFile.split('.').pop().toLowerCase())) {
                const certPanzoom = Panzoom(document.getElementById('certPreviewWrapper'), {
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
                }, { Controls });
                certPanzoom.init();
            }
            if (uploadIdFile && ['jpg','jpeg','png','gif','webp'].includes(uploadIdFile.split('.').pop().toLowerCase())) {
                const idPanzoom = Panzoom(document.getElementById('idPreviewWrapper'), {
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
                }, { Controls });
                idPanzoom.init();
            }
            if (uploadIdBackFile && ['jpg','jpeg','png','gif','webp'].includes(uploadIdBackFile.split('.').pop().toLowerCase())) {
                const idBackPanzoom = Panzoom(document.getElementById('idPreviewWrapperBack'), {
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
                }, { Controls });
                idBackPanzoom.init();
            }
        }, 100);
    }

    // ...existing code...
    // Inactive Users functionality has been removed. No leftover function block remains here.
    // ...existing code...

    // KK ID Generation Functions
    function generateKKId() {
        // Get current user data from the modal
        const userName = document.getElementById('modalUserName').textContent;
        const userBarangay = document.getElementById('modalUserBarangayDetail').textContent;
        const userZone = document.getElementById('modalUserZone').textContent;
        const userPhoto = document.getElementById('modalUserPhoto').src;
        const existingId = document.getElementById('modalUserId').textContent.trim();
        
        // Prefer the assigned KK ID if already accepted
        let idNumber = existingId && existingId !== '-' ? existingId : '';
        if (!idNumber) {
            const yy = new Date().getFullYear().toString().slice(-2);
            const suffix = Math.floor(Math.random() * 1000000).toString().padStart(6, '0');
            idNumber = `${yy}-${suffix}`; // Preview format
        }
        
        // Generate expiry date (1 year from now)
        const expiryDate = new Date();
        expiryDate.setFullYear(expiryDate.getFullYear() + 1);
        const expiryMonth = expiryDate.toLocaleString('default', { month: 'short' });
        const expiryYear = expiryDate.getFullYear();
        
        // Populate the ID card preview
        document.getElementById('idCardName').textContent = userName.toUpperCase();
        document.getElementById('idCardBarangay').textContent = userBarangay;
        document.getElementById('idCardZone').textContent = userZone;
    document.getElementById('idCardNumber').textContent = idNumber;
        document.getElementById('idCardExpiry').textContent = `${expiryMonth} ${expiryYear}`;
        document.getElementById('idCardPhoto').src = userPhoto;
        
        // Show the KK ID modal
        document.getElementById('kkIdModal').classList.remove('hidden');
    }
    
    function closeKKIdModal() {
        document.getElementById('kkIdModal').classList.add('hidden');
    }
    
    // Function to add Generate ID button to action containers
    function addGenerateIdButton() {
        const actionContainers = document.querySelectorAll('#actionButtonsContainer');
        actionContainers.forEach(container => {
            // Check if button already exists
            if (!container.querySelector('#generateIdButton')) {
                const generateIdButton = document.createElement('button');
                generateIdButton.id = 'generateIdButton';
                generateIdButton.onclick = generateKKId;
                generateIdButton.className = 'w-full py-3 px-4 bg-purple-600 text-white rounded-lg shadow hover:bg-purple-700 font-semibold transition-all duration-200 flex items-center justify-center gap-2';
                generateIdButton.style.display = 'none'; // Hidden by default
                generateIdButton.innerHTML = `
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 114 0v2m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                    </svg>
                    Generate KK ID
                `;
                // Insert as first child
                container.insertBefore(generateIdButton, container.firstChild);
            }
        });
    }
    
    // Add Generate ID button when page loads
    document.addEventListener('DOMContentLoaded', addGenerateIdButton);
    
    // Close KK ID modal when clicking outside
    document.getElementById('kkIdModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeKKIdModal();
        }
    });

    // Close KK ID modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('kkIdModal').classList.contains('hidden')) {
            closeKKIdModal();
        }
    });

    // Download Modal Functions
    function openDownloadModal() {
        document.getElementById('downloadModal').classList.remove('hidden');
        loadKKListData();
    }

    function closeDownloadModal() {
        document.getElementById('downloadModal').classList.add('hidden');
    }

    function loadKKListData() {
        const tbody = document.getElementById('downloadTableBody');
        tbody.innerHTML = '<tr><td colspan="20" class="text-center py-4">Loading data...</td></tr>';
        
    // Always use Accepted (verified) users for the download list
    const statusFilter = 'Accepted';
    const zoneFilter = document.getElementById('zoneFilter').value;
        
    //
        
        // Fetch data from server
        fetch(`<?= base_url('sk/getKKListData') ?>?status=${statusFilter}&zone=${zoneFilter}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => {
                //
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                //
                if (data.success) {
                    populateDownloadTable(data.users);
                    
                    // Update signature names
                    const secretaryNameElement = document.getElementById('modalSecretaryName');
                    const chairmanNameElement = document.getElementById('modalChairmanName');
                    
                    if (secretaryNameElement) {
                        secretaryNameElement.textContent = data.secretary_name || '________________';
                    }
                    if (chairmanNameElement) {
                        chairmanNameElement.textContent = data.chairman_name || '________________';
                    }
                } else {
                    console.error('Server returned error:', data);
                    tbody.innerHTML = '<tr><td colspan="20" class="text-center py-4 text-red-600">Error: Server returned an error</td></tr>';
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                tbody.innerHTML = `<tr><td colspan="20" class="text-center py-4 text-red-600">Error: ${error.message}</td></tr>`;
            });
    }

    function populateDownloadTable(users) {
        const tbody = document.getElementById('downloadTableBody');
        
    //
        
        if (!users || users.length === 0) {
            tbody.innerHTML = '<tr><td colspan="20" class="text-center py-4">No data available</td></tr>';
            return;
        }

    let html = '';
    const timesMap = { '1': '1-2 times', '2': '3-4 times', '3': '5 or more times' };
        users.forEach((user, index) => {
            const rowClass = index % 2 === 0 ? 'bg-white' : 'bg-gray-50';
            html += `
                <tr class="${rowClass}">
                    <td class="border border-gray-300 px-1 py-1 text-center text-xs text-gray-900">V</td>
                    <td class="border border-gray-300 px-1 py-1 text-center text-xs text-gray-900">Camarines Sur</td>
                    <td class="border border-gray-300 px-1 py-1 text-center text-xs text-gray-900">Iriga City</td>
                    <td class="border border-gray-300 px-1 py-1 text-center text-xs text-gray-900">${user.barangay_name || ''}</td>
                    <td class="border border-gray-300 px-1 py-1 text-xs text-gray-900">${user.full_name || ''}</td>
                    <td class="border border-gray-300 px-1 py-1 text-center text-xs text-gray-900">${user.age || ''}</td>
                    <td class="border border-gray-300 px-1 py-1 text-center text-xs text-gray-900">${user.birthdate || ''}</td>
                    <td class="border border-gray-300 px-1 py-1 text-center text-xs text-gray-900">${user.sex === '1' ? 'M' : user.sex === '2' ? 'F' : ''}</td>
                    <td class="border border-gray-300 px-1 py-1 text-center text-xs text-gray-900">${getCivilStatusText(user.civil_status)}</td>
                    <td class="border border-gray-300 px-1 py-1 text-center text-xs text-gray-900">${getYouthClassificationText(user.youth_classification)}</td>
                    <td class="border border-gray-300 px-1 py-1 text-center text-xs text-gray-900">${getAgeGroupText(user.age_group)}</td>
                    <td class="border border-gray-300 px-1 py-1 text-xs text-gray-900">${user.email || ''}</td>
                    <td class="border border-gray-300 px-1 py-1 text-center text-xs text-gray-900">${user.phone_number || ''}</td>
                    <td class="border border-gray-300 px-1 py-1 text-center text-xs text-gray-900">${getFullAddress(user)}</td>
                    <td class="border border-gray-300 px-1 py-1 text-center text-xs text-gray-900">${getEducationText(user.educational_background)}</td>
                    <td class="border border-gray-300 px-1 py-1 text-center text-xs text-gray-900">${getWorkStatusText(user.work_status)}</td>
                    <td class="border border-gray-300 px-1 py-1 text-center text-xs text-gray-900">${user.sk_voter == 1 ? 'Yes' : user.sk_voter == 0 ? 'No' : ''}</td>
                    <td class="border border-gray-300 px-1 py-1 text-center text-xs text-gray-900">${user.sk_election == 1 ? 'Yes' : user.sk_election == 0 ? 'No' : ''}</td>
                    <td class="border border-gray-300 px-1 py-1 text-center text-xs text-gray-900">${user.kk_assembly == 1 ? 'Yes' : user.kk_assembly == 0 ? 'No' : ''}</td>
                    <td class="border border-gray-300 px-1 py-1 text-center text-xs text-gray-900">${user.kk_assembly == 1 ? (timesMap[String(user.how_many_times)] || '') : ''}</td>
                </tr>
            `;
        });
        
        tbody.innerHTML = html;
    //
    }

    // Helper functions for data formatting
    function getCivilStatusText(status) {
        return civilStatusMap[String(status)] || '';
    }

    function getYouthClassificationText(classification) {
        return youthClassificationMap[String(classification)] || '';
    }

    function getAgeGroupText(ageGroup) {
        return ageGroupMap[String(ageGroup)] || '';
    }

    function getEducationText(education) {
        return educationMap[String(education)] || '';
    }

    function getWorkStatusText(workStatus) {
        return workStatusMap[String(workStatus)] || '';
    }

    function getFullAddress(user) {
        let address = '';
        if (user.house_number) address += user.house_number + ' ';
        if (user.street) address += user.street + ' ';
        if (user.subdivision) address += user.subdivision + ' ';
        if (user.zone_purok) address += user.zone_purok + ' ';
        return address.trim();
    }

    function printKKList() {
        const printContent = document.getElementById('downloadContent').cloneNode(true);
        const printWindow = window.open('', '_blank');
        
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>KK List - ${<?= json_encode($barangay_name ?? '') ?>}</title>
                <style>
                    @page {
                        size: 13in 8.5in; /* Landscape: width x height */
                        margin: 0.5in;
                        -webkit-print-color-adjust: exact;
                        color-adjust: exact;
                    }
                    
                    body { 
                        font-family: Arial, sans-serif !important; 
                        margin: 0; 
                        color: black !important;
                        -webkit-print-color-adjust: exact;
                    }
                    table { border-collapse: collapse; width: 100%; }
                    th, td { 
                        border: 1px solid black !important; 
                        padding: 1px !important; 
                        text-align: center; 
                        font-size: 6px !important;
                        color: black !important;
                        font-family: Arial, sans-serif !important;
                    }
                    th { background-color: #f0f0f0; font-weight: bold; }
                    .text-left { text-align: left; }
                    .flex { display: flex; }
                    .items-center { align-items: center; }
                    .justify-center { justify-content: center; }
                    .justify-between { justify-content: space-between; }
                    .text-center { text-align: center; }
                    .mb-4 { margin-bottom: 1rem; }
                    .mx-4 { margin-left: 1rem; margin-right: 1rem; }
                    .mr-6 { margin-right: 1.5rem; }
                    .ml-6 { margin-left: 1.5rem; }
                    .flex-1 { flex: 1; }
                    .bg-gray-100 { background-color: #f3f4f6; }
                    .font-bold { font-weight: bold; }
                    .font-semibold { font-weight: 600; }
                    .font-medium { font-weight: 500; }
                    hr { border: 1px solid #d1d5db; margin: 1rem 0; }
                    
                    /* Header specific styles */
                    h2, h3, h4 {
                        font-family: Arial, sans-serif !important;
                        color: black !important;
                        margin: 0;
                        line-height: 1.2;
                    }
                </style>
            </head>
            <body>
                ${printContent.innerHTML}
            </body>
            </html>
        `);
        
        printWindow.document.close();
        printWindow.focus();
        printWindow.print();
    }

    async function downloadKKListPDF() {
        const button = event.target;
        const originalHTML = button.innerHTML;
        button.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Generating PDF...';
        button.disabled = true;

        try {
            // Ensure jsPDF and autoTable are loaded (like ped-officers)
            await ensureJsPDFLoaded();

            // Fetch KK list data (Accepted users)
            const dataResp = await fetch('<?= base_url('sk/kk-list-data') ?>?status=Accepted', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (!dataResp.ok) throw new Error('Failed to load KK list data');
            const dataJson = await dataResp.json();
            if (!dataJson.success) throw new Error(dataJson.error || 'Failed to load data');

            // Fetch logos
            const logosResp = await fetch('<?= base_url('documents/logos') ?>');
            const logosJson = (logosResp.ok ? await logosResp.json() : { success: false, data: {} });
            const logos = logosJson.success ? logosJson.data : {};

            const barangayLogoPath = (logos.barangay?.file_path) || (logos.sk?.file_path) || '';
            const irigaLogoPath = (logos.iriga_city?.file_path) || '';
            const barangayLogoUrl = barangayLogoPath ? '<?= base_url() ?>' + barangayLogoPath : '';
            const irigaLogoUrl = irigaLogoPath ? '<?= base_url() ?>' + irigaLogoPath : '';

            const [barangayLogoDataUrl, irigaLogoDataUrl] = await Promise.all([
                barangayLogoUrl ? imageUrlToDataUrl(barangayLogoUrl) : Promise.resolve(null),
                irigaLogoUrl ? imageUrlToDataUrl(irigaLogoUrl) : Promise.resolve(null)
            ]);

            // Build PDF (legal, landscape) matching the formal layout
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('l', 'mm', 'legal');
            const pageWidth = doc.internal.pageSize.getWidth();
            const pageHeight = doc.internal.pageSize.getHeight();
            const margin = 12.7; // 0.5 inch margins
            let y = margin + 5;

            // Header with logos beside the center header text
            const logoSize = 22; // logo physical size in mm
            const centerX = pageWidth / 2;
            
            // Helper to get image format from data URL
            const getImgFmt = (dataUrl) => {
                if (!dataUrl) return 'PNG';
                const m = /^data:(image\/(png|jpeg|jpg|webp));/i.exec(dataUrl);
                if (!m) return 'PNG';
                const ext = m[2].toLowerCase();
                if (ext === 'png') return 'PNG';
                if (ext === 'jpeg' || ext === 'jpg') return 'JPEG';
                if (ext === 'webp') return 'WEBP';
                return 'PNG';
            };

            // Header text - formal government format
            doc.setFont('helvetica', 'normal');
            doc.setFontSize(10);
            doc.text('Republic of the Philippines', centerX, y + 3, { align: 'center' });
            doc.text('Province of Camarines Sur', centerX, y + 8, { align: 'center' });
            
            doc.setFont('helvetica', 'bold');
            doc.setFontSize(10);
            doc.text('CITY OF IRIGA', centerX, y + 13, { align: 'center' });
            
            doc.setFont('helvetica', 'normal');
            doc.setFontSize(9);
            // Split into two lines: "SANGGUNIANG KABATAAN NG" and "BARANGAY [Name]"
            doc.text('SANGGUNIANG KABATAAN NG', centerX, y + 18, { align: 'center' });

            const barangayName = (dataJson.users?.[0]?.barangay_name) || '';
            doc.setFont('helvetica', 'bold');
            doc.setFontSize(9);
            const barangayNameUpper = (barangayName || '').toString().toUpperCase();
            doc.text(('BARANGAY ' + barangayNameUpper).trim(), centerX, y + 23, { align: 'center' });

            // Compute header block width to place logos with ~20px gap on both sides
            const pxToMm = (px) => px * 0.264583; // 96 DPI
            const gapMM = pxToMm(50); // ≈13.23mm (requested 50px)
            // Recompute widths using exact fonts and sizes used
            doc.setFont('helvetica', 'normal'); doc.setFontSize(10);
            const w1 = doc.getTextWidth('Republic of the Philippines');
            const w2 = doc.getTextWidth('Province of Camarines Sur');
            doc.setFont('helvetica', 'bold'); doc.setFontSize(10);
            const w3 = doc.getTextWidth('CITY OF IRIGA');
            doc.setFont('helvetica', 'normal'); doc.setFontSize(9);
            const w4 = doc.getTextWidth('SANGGUNIANG KABATAAN NG');
            doc.setFont('helvetica', 'bold'); doc.setFontSize(9);
            const w5 = doc.getTextWidth(('BARANGAY ' + barangayNameUpper).trim());
            const maxHeaderWidth = Math.max(w1, w2, w3, w4, w5);
            const headerLeftX = centerX - (maxHeaderWidth / 2);
            const headerRightX = centerX + (maxHeaderWidth / 2);

            // Position logos vertically centered beside header block
            const headerTop = y + 0;
            const headerBottom = y + 23;
            const headerMidY = headerTop + (headerBottom - headerTop) / 2 - (logoSize / 2);
            // Compute logo X based on desired gap
            let leftLogoX = headerLeftX - gapMM - logoSize;
            let rightLogoX = headerRightX + gapMM;
            // Clamp within page margins
            if (leftLogoX < margin) leftLogoX = margin;
            if (rightLogoX > pageWidth - margin - logoSize) rightLogoX = pageWidth - margin - logoSize;
            // Left logo (Barangay/SK)
            if (barangayLogoDataUrl) {
                doc.addImage(barangayLogoDataUrl, getImgFmt(barangayLogoDataUrl), leftLogoX, headerMidY, logoSize, logoSize, undefined, 'FAST');
            } else {
                doc.setLineWidth(0.2);
                doc.rect(leftLogoX, headerMidY, logoSize, logoSize);
                doc.setFontSize(8);
                doc.text('BRGY LOGO', leftLogoX + logoSize/2, headerMidY + logoSize/2, { align: 'center' });
            }
            // Right logo (Iriga City)
            if (irigaLogoDataUrl) {
                doc.addImage(irigaLogoDataUrl, getImgFmt(irigaLogoDataUrl), rightLogoX, headerMidY, logoSize, logoSize, undefined, 'FAST');
            } else {
                doc.setLineWidth(0.2);
                doc.rect(rightLogoX, headerMidY, logoSize, logoSize);
                doc.setFontSize(8);
                doc.text('IRIGA LOGO', rightLogoX + logoSize/2, headerMidY + logoSize/2, { align: 'center' });
            }

            // Horizontal line under header
            y += 28;
            // Thinner separator line
            doc.setLineWidth(0.2);
            doc.line(margin, y, pageWidth - margin, y);

            // Title
            y += 8;
            doc.setFont('helvetica', 'bold');
            doc.setFontSize(11);
            doc.text('KATIPUNAN NG KABATAAN YOUTH PROFILE', centerX, y, { align: 'center' });
            y += 8;

            // Table headers matching the Word document exactly
            const headers = [
                'REGION',
                'PROVINCE',
                'CITY',
                'BARANGAY',
                'FAMILY NAME, FIRST NAME, MIDDLE NAME',
                'AGE',
                'BIRTHDAY',
                'SEX',
                'CIVIL STATUS',
                'YOUTH CLASSIFICATION',
                'AGE GROUP',
                'EMAIL ADDRESS',
                'CONTACT NO.',
                'HOME ADDRESS',
                'WORK STATUS',
                'EDUCATIONAL BACKGROUND',
                'SK VOTER',
                'SK ELECTION',
                'KK ASSEMBLY',
                'HOW MANY TIMES'
            ];

            // Helpers to format like Word output
            const fmtBirthday = (dateStr) => {
                if (!dateStr) return '';
                const d = new Date(dateStr);
                if (isNaN(d)) return '';
                const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                return `${months[d.getMonth()]} ${String(d.getDate()).padStart(2,'0')}, ${d.getFullYear()}`;
            };
            const calcAge = (dateStr) => {
                if (!dateStr) return '';
                const dob = new Date(dateStr);
                if (isNaN(dob)) return '';
                const today = new Date();
                let age = today.getFullYear() - dob.getFullYear();
                const m = today.getMonth() - dob.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) age--;
                return age;
            };

            const body = (dataJson.users || []).map(u => {
                // Build name in Word format: Last Name, First Name Middle Name
                let name = (u.last_name || '');
                if (u.first_name) name += ', ' + (u.first_name || '');
                if (u.middle_name) name += ' ' + (u.middle_name || '');

                // Address
                const parts = [];
                if (u.house_number) parts.push(u.house_number);
                if (u.street) parts.push(u.street);
                if (u.subdivision) parts.push(u.subdivision);
                if (u.zone_purok) parts.push(u.zone_purok);
                const address = parts.join(' ').trim();

                // Fields to match Word
                const age = (u.age && String(u.age).trim() !== '') ? u.age : calcAge(u.birthdate);
                const birthday = fmtBirthday(u.birthdate);
                const sex = u.sex == '1' ? 'Male' : (u.sex == '2' ? 'Female' : '');
                const skVoter = u.sk_voter == '1' ? 'Yes' : 'No';
                const skElection = u.sk_election == '1' ? 'Yes' : 'No';
                const kkAssembly = u.kk_assembly == '1' ? 'Yes' : 'No';
                const timesMap = { '1': '1-2 times', '2': '3-4 times', '3': '5 or more times' };
                const howMany = u.kk_assembly == '1' ? (timesMap[String(u.how_many_times)] || '') : '';

                return [
                    'V',
                    'Camarines Sur',
                    'Iriga City',
                    u.barangay_name || '',
                    name,
                    age || '',
                    birthday,
                    sex,
                    getCivilStatusText(u.civil_status),
                    getYouthClassificationText(u.youth_classification),
                    getAgeGroupText(u.age_group),
                    u.email || '',
                    u.phone_number || '',
                    address,
                    getWorkStatusText(u.work_status),
                    getEducationText(u.educational_background),
                    skVoter,
                    skElection,
                    kkAssembly,
                    howMany
                ];
            });

            // Guard: ensure rows match header count to avoid any extra empty column
            const finalHeaders = headers.slice(0);
            const finalBody = body.map(r => r.slice(0, finalHeaders.length));

            // Create table with formal styling matching the image
            doc.setFont('helvetica', 'normal');
        doc.setFontSize(6); // Smaller font for table content
            doc.autoTable({
                head: [finalHeaders],
                body: finalBody,
                startY: y + 2,
                margin: { left: margin, right: margin },
                tableWidth: 'wrap',
                styles: { 
            fontSize: 6,
            cellPadding: 1.2,
            halign: 'center',
            valign: 'middle',
            textColor: [0,0,0],
            lineColor: [0,0,0],
            lineWidth: 0.2,
            overflow: 'linebreak'
                },
                headStyles: { 
            fillColor: [255,255,255],
            textColor: [0,0,0],
                    fontStyle: 'bold',
                    fontSize: 6,
            cellPadding: 1.2,
                    halign: 'center',
                    valign: 'middle'
                },
                bodyStyles: { 
                    fillColor: [255,255,255],
            fontSize: 6,
            cellPadding: 1.2
                },
                // Column widths proportional to Word layout (legal landscape, 0.5in margins)
                columnStyles: {
                    0: { cellWidth: 11.7 }, // REGION (600)
                    1: { cellWidth: 15.6 }, // PROVINCE (800)
                    2: { cellWidth: 19.5 }, // CITY (1000)
                    3: { cellWidth: 15.6 }, // BARANGAY (800)
                    4: { cellWidth: 29.3, halign: 'left' }, // NAME (1500)
                    5: { cellWidth: 7.8 },  // AGE (400)
                    6: { cellWidth: 15.6 }, // BIRTHDAY (800)
                    7: { cellWidth: 7.8 },  // SEX (400)
                    8: { cellWidth: 15.6 }, // CIVIL STATUS (800)
                    9: { cellWidth: 19.5 }, // YOUTH CLASSIFICATION (1000)
                    10:{ cellWidth: 15.6 }, // AGE GROUP (800)
                    11:{ cellWidth: 23.4, halign: 'left' }, // EMAIL (1200)
                    12:{ cellWidth: 15.6 }, // CONTACT NO. (800)
                    13:{ cellWidth: 23.4, halign: 'left' }, // HOME ADDRESS (1200)
                    14:{ cellWidth: 15.6 }, // WORK STATUS (800)
                    15:{ cellWidth: 19.5 }, // EDUCATIONAL BACKGROUND (1000)
                    16:{ cellWidth: 11.7 }, // SK VOTER (600)
                    17:{ cellWidth: 15.6 }, // SK ELECTION (800)
                    18:{ cellWidth: 15.6 }, // KK ASSEMBLY (800)
                    19:{ cellWidth: 15.6 }  // HOW MANY TIMES (800)
                },
                theme: 'grid',
                tableLineColor: [0, 0, 0],
                tableLineWidth: 0
            });

            // Signatures section - formal layout matching the image
            const finalY = (doc.lastAutoTable && doc.lastAutoTable.finalY) ? doc.lastAutoTable.finalY + 20 : (y + 50);
            const leftSigX = margin + 60;
            const rightSigX = pageWidth - margin - 80;
            
            doc.setFont('helvetica', 'normal');
            doc.setFontSize(9);
            
            // Left signature block (Prepared by)
            doc.text('Prepared by:', leftSigX, finalY, { align: 'center' });
            doc.line(leftSigX - 30, finalY + 15, leftSigX + 30, finalY + 15); // Signature line
            doc.setFont('helvetica', 'bold');
            doc.text((dataJson.secretary_name || '________________'), leftSigX, finalY + 20, { align: 'center' });
            doc.setFont('helvetica', 'normal');
            doc.text('SK Secretary', leftSigX, finalY + 26, { align: 'center' });
            
            // Ensure thin lines for signatures as well
            doc.setLineWidth(0.2);
            // Right signature block (Approved by)
            doc.text('Approved by:', rightSigX, finalY, { align: 'center' });
            doc.line(rightSigX - 30, finalY + 15, rightSigX + 30, finalY + 15); // Signature line
            doc.setFont('helvetica', 'bold');
            doc.text((dataJson.chairman_name || '________________'), rightSigX, finalY + 20, { align: 'center' });
            doc.setFont('helvetica', 'normal');
            doc.text('SK Chairperson', rightSigX, finalY + 26, { align: 'center' });

            // Save
            const safeBarangay = (barangayName || 'Barangay').replace(/\s+/g, '_');
            const ts = new Date();
            const tsStr = `${ts.getFullYear()}-${String(ts.getMonth()+1).padStart(2,'0')}-${String(ts.getDate()).padStart(2,'0')}_${String(ts.getHours()).padStart(2,'0')}-${String(ts.getMinutes()).padStart(2,'0')}-${String(ts.getSeconds()).padStart(2,'0')}`;
            doc.save(`KK_List_${safeBarangay}_${tsStr}.pdf`);

            showNotification('PDF generated and downloaded successfully!', 'success');
        } catch (err) {
            console.error(err);
            showNotification('Error generating PDF: ' + err.message, 'error');
        } finally {
            button.innerHTML = originalHTML;
            button.disabled = false;
        }
    }

    // Dynamically load jsPDF and autoTable if not present
    function ensureJsPDFLoaded() {
        if (window.jspdf && window.jspdf.jsPDF && window.jsPDF) return Promise.resolve();
        const loadScript = (src) => new Promise((resolve, reject) => {
            const s = document.createElement('script');
            s.src = src;
            s.onload = resolve;
            s.onerror = () => reject(new Error('Failed to load ' + src));
            document.head.appendChild(s);
        });
        const libs = [
            'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js'
        ];
        return libs.reduce((p, src) => p.then(() => loadScript(src)), Promise.resolve());
    }

    // Convert image URL to data URL
    function imageUrlToDataUrl(url) {
        return fetch(url)
            .then(r => r.blob())
            .then(blob => new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onloadend = () => resolve(reader.result);
                reader.onerror = reject;
                reader.readAsDataURL(blob);
            }));
    }

    function downloadKKListWord() {
        // Show loading state
        const button = event.target;
        const originalText = button.textContent;
        const originalHTML = button.innerHTML;
        button.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Generating Word...';
        button.disabled = true;
        
        // Make AJAX request to generate Word document
        fetch('<?= base_url('sk/generate-kk-word') ?>', {
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
            showNotification('Word document generated and downloaded successfully!', 'success');
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

    function downloadKKListExcel() {
        // Show loading state
        const button = event.target;
        const originalText = button.textContent;
        const originalHTML = button.innerHTML;
        button.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Generating Excel...';
        button.disabled = true;
        
        // Make AJAX request to generate Excel document
        fetch('<?= base_url('sk/generate-kk-excel') ?>', {
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
            showNotification('Excel document generated and downloaded successfully!', 'success');
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

    // Close download modal when clicking outside
    document.getElementById('downloadModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDownloadModal();
        }
    });

    // Close download modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('downloadModal').classList.contains('hidden')) {
            closeDownloadModal();
        }
    });

    // Load logos for KK List header
    function loadKKListLogos() {
        fetch('<?= base_url('documents/logos') ?>')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateKKListLogos(data.data);
                }
            })
            .catch(error => {
                console.error('Error loading logos for KK List:', error);
            });
    }

    function updateKKListLogos(logos) {
        // Update Iriga City logo
        const irigaLogo = logos.iriga_city;
        const kkListIrigaElement = document.getElementById('kk-list-iriga-logo');
        if (irigaLogo && kkListIrigaElement) {
            kkListIrigaElement.innerHTML = `<img src="<?= base_url() ?>${irigaLogo.file_path}" class="w-full h-full object-contain">`;
        }

        // Update Barangay logo (prioritize barangay over SK)
        const barangayLogo = logos.barangay;
        const skLogo = logos.sk;
        const kkListBarangayElement = document.getElementById('kk-list-barangay-logo');
        
        if (kkListBarangayElement) {
            // Prioritize barangay logo, then SK logo
            const logoToUse = barangayLogo || skLogo;
            if (logoToUse) {
                kkListBarangayElement.innerHTML = `<img src="<?= base_url() ?>${logoToUse.file_path}" class="w-full h-full object-contain">`;
            }
        }
    }

    // Load logos when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        loadKKListLogos();
    });

    // Also load logos when the download modal opens
    function openDownloadModal() {
        document.getElementById('downloadModal').classList.remove('hidden');
        loadKKListData();
        loadKKListLogos(); // Refresh logos when modal opens
    }
    </script>
</body>
</html>
