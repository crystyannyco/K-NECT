

<!-- ===== MAIN CONTENT AREA ===== -->
<!-- Content area positioned to the right of sidebar -->
<div class="flex-1 flex flex-col min-h-0 ml-0 lg:ml-64 pt-16">
    <main class="flex-1 overflow-auto p-4 lg:p-6 bg-gray-50">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-1">My Profile</h1>
                    <p class="text-gray-600">View and manage your personal information</p>
                </div>
                <div class="mt-3 sm:mt-0">
                    <a href="<?= base_url('sk/account-settings') ?>"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
                        </svg>
                        Update Information
                    </a>
                </div>
            </div>
        </div>

        <!-- Profile Overview Card -->
        <div class="bg-white rounded-xl shadow-sm p-6 sm:p-8 mb-6">
            <div class="flex flex-col lg:flex-row items-center lg:items-start space-y-6 lg:space-y-0 lg:space-x-8">
                <!-- Profile Picture -->
                <div class="flex-shrink-0">
                    <?php
                        $pp = (string)($userExtInfo['profile_picture'] ?? '');
                        // Use same logic as working logos
                        if (!empty($pp)) {
                            if (strpos($pp, '/') !== false) {
                                $ppSrc = base_url('/previewDocument/profile_pictures/' . basename($pp));
                            } else {
                                $ppSrc = base_url('/previewDocument/profile_pictures/' . $pp);
                            }
                        } else {
                            $ppSrc = base_url('assets/images/default-avatar.svg');
                        }
                    ?>
                    <img src="<?= esc($ppSrc) ?>" 
                         alt="Profile Picture" 
                         class="w-24 h-24 sm:w-32 sm:h-32 rounded-full object-cover border-4 border-white shadow-md"
                         onerror="this.src='<?= base_url('assets/images/default-avatar.svg') ?>'">
                </div>
                
                <!-- Basic Info -->
                <div class="flex-1 text-center lg:text-left">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">
                        <?= esc($user['first_name']) ?> 
                        <?= !empty($user['middle_name']) ? esc($user['middle_name']) . ' ' : '' ?>
                        <?= esc($user['last_name']) ?>
                        <?= !empty($user['suffix']) ? ', ' . esc($user['suffix']) : '' ?>
                    </h2>
                    
                    <div class="flex flex-wrap justify-center lg:justify-start gap-3 mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            SK Official
                        </span>
                        <?php 
                        $statusText = 'Unknown';
                        $statusClass = 'bg-gray-100 text-gray-800';
                        switch($user['status']) {
                            case 1:
                                $statusText = 'Pending';
                                $statusClass = 'bg-yellow-100 text-yellow-800';
                                break;
                            case 2:
                                $statusText = 'Approved';
                                $statusClass = 'bg-green-100 text-green-800';
                                break;
                            case 3:
                                $statusText = 'Rejected';
                                $statusClass = 'bg-red-100 text-red-800';
                                break;
                        }
                        ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium <?= $statusClass ?>">
                            <?= $statusText ?>
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500">ID Number</p>
                            <p class="font-semibold text-gray-900"><?= esc($user_id) ?></p>
                        </div>
                        <div>
                            <p class="text-gray-500">Username</p>
                            <p class="font-semibold text-gray-900"><?= esc($username) ?></p>
                        </div>
                        <div>
                            <p class="text-gray-500">Age</p>
                            <p class="font-semibold text-gray-900"><?= $age ? $age . ' years old' : 'Not specified' ?></p>
                        </div>
                        <div>
                            <p class="text-gray-500">Barangay</p>
                            <p class="font-semibold text-gray-900"><?= esc($barangay_name) ?: 'Not assigned' ?></p>
                        </div>
                        <?php if (!empty($user['position'])): ?>
                        <div>
                            <p class="text-gray-500">Position</p>
                            <p class="font-semibold text-gray-900">
                                <?php
                                $positionMap = [
                                    1 => 'SK Chairperson',
                                    2 => 'SK Councilor',
                                    3 => 'SK Secretary',
                                    4 => 'SK Treasurer'
                                ];
                                echo $positionMap[$user['position']] ?? 'SK Official';
                                ?>
                            </p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Information Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Personal Information -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100">Personal Information</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Email</p>
                            <p class="text-sm text-gray-900"><?= esc($user['email']) ?></p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Phone</p>
                            <p class="text-sm text-gray-900"><?= esc($user['phone_number']) ?></p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Sex</p>
                            <p class="text-sm text-gray-900"><?= $user['sex'] == '1' ? 'Male' : ($user['sex'] == '2' ? 'Female' : 'Not specified') ?></p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Date of Birth</p>
                            <p class="text-sm text-gray-900"><?= $user['birthdate'] ? date('F j, Y', strtotime($user['birthdate'])) : 'Not specified' ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <?php if ($address): ?>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100">Address Information</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Barangay</p>
                            <p class="text-sm text-gray-900"><?= esc($address_barangay_name) ?: 'Not specified' ?></p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Zone/Purok</p>
                            <p class="text-sm text-gray-900"><?= esc($address['zone_purok']) ?: 'Not specified' ?></p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">City</p>
                            <p class="text-sm text-gray-900">Iriga City</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Province</p>
                            <p class="text-sm text-gray-900">Camarines Sur</p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Demographic Information -->
            <?php if ($userExtInfo): ?>
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100">Demographics</h3>
                <div class="space-y-3">
                    <?php if (!empty($userExtInfo['civil_status'])): ?>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Civil Status</span>
                        <span class="text-sm font-medium text-gray-900">
                            <?= $field_mappings['civil_status'][$userExtInfo['civil_status']] ?? 'Not specified' ?>
                        </span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($userExtInfo['youth_classification'])): ?>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Youth Classification</span>
                        <span class="text-sm font-medium text-gray-900">
                            <?= $field_mappings['youth_classification'][$userExtInfo['youth_classification']] ?? 'Not specified' ?>
                        </span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($userExtInfo['work_status'])): ?>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Work Status</span>
                        <span class="text-sm font-medium text-gray-900">
                            <?= $field_mappings['work_status'][$userExtInfo['work_status']] ?? 'Not specified' ?>
                        </span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($userExtInfo['educational_background'])): ?>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Education</span>
                        <span class="text-sm font-medium text-gray-900">
                            <?= $field_mappings['educational_background'][$userExtInfo['educational_background']] ?? 'Not specified' ?>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Participation Information -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100">Youth Participation</h3>
                <div class="space-y-3">
                    <?php if (isset($userExtInfo['sk_voter'])): ?>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">SK Voter</span>
                        <span class="text-sm font-medium <?= $userExtInfo['sk_voter'] == 1 ? 'text-green-600' : 'text-gray-500' ?>">
                            <?= $userExtInfo['sk_voter'] == 1 ? 'Yes' : 'No' ?>
                        </span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isset($userExtInfo['sk_election'])): ?>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Voted in SK Election</span>
                        <span class="text-sm font-medium <?= $userExtInfo['sk_election'] == 1 ? 'text-green-600' : 'text-gray-500' ?>">
                            <?= $userExtInfo['sk_election'] == 1 ? 'Yes' : 'No' ?>
                        </span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isset($userExtInfo['national_voter'])): ?>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">National Voter</span>
                        <span class="text-sm font-medium <?= $userExtInfo['national_voter'] == 1 ? 'text-green-600' : 'text-gray-500' ?>">
                            <?= $userExtInfo['national_voter'] == 1 ? 'Yes' : 'No' ?>
                        </span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isset($userExtInfo['kk_assembly'])): ?>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">KK Assembly Attendance</span>
                        <span class="text-sm font-medium <?= $userExtInfo['kk_assembly'] == 1 ? 'text-green-600' : 'text-gray-500' ?>">
                            <?= $userExtInfo['kk_assembly'] == 1 ? 'Yes' : 'No' ?>
                        </span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($userExtInfo['how_many_times'])): ?>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Times Attended</span>
                        <span class="text-sm font-medium text-gray-900">
                            <?= $field_mappings['how_many_times'][$userExtInfo['how_many_times']] ?? 'Not specified' ?>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

        </div>

        <!-- Account Information -->
        <div class="bg-white rounded-xl shadow-sm p-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-100">Account Details</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Account Created</p>
                    <p class="text-sm text-gray-900"><?= $user['created_at'] ? date('F j, Y g:i A', strtotime($user['created_at'])) : 'Not specified' ?></p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Last Updated</p>
                    <p class="text-sm text-gray-900"><?= $user['updated_at'] ? date('F j, Y g:i A', strtotime($user['updated_at'])) : 'Not specified' ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
