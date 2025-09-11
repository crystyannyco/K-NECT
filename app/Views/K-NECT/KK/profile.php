

    <!-- Main Content -->
    <!-- ===== MAIN CONTENT AREA ===== -->
    <div class="flex-1 flex flex-col min-h-0 ml-0 lg:ml-64 pt-16">
        <main class="flex-1 overflow-auto p-4 lg:p-6 bg-gray-50">
            <!-- Header Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-5 lg:p-6 mb-4 sm:mb-5 lg:mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">My Profile</h1>
                        <p class="text-gray-600">View and manage your personal information</p>
                    </div>
                    <div class="flex items-center">
                        <a href="<?= base_url('kk/settings') ?>"
                           class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
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
                            $imageData = safe_image_url($pp, 'avatar');
                        ?>
                        <img src="<?= esc($imageData['src']) ?>" 
                             alt="Profile Picture" 
                             class="w-24 h-24 sm:w-32 sm:h-32 rounded-full object-cover border-4 border-white shadow-md"
                             data-type="avatar"
                             data-fallback="<?= esc($imageData['fallback']) ?>">
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
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                KK Member
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
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Municipality</p>
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
                        <?php if (isset($userExtInfo['civil_status']) && $userExtInfo['civil_status'] > 0): ?>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Civil Status</span>
                            <span class="text-sm font-medium text-gray-900">
                                <?= $field_mappings['civil_status'][$userExtInfo['civil_status']] ?? 'Not specified' ?>
                            </span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (isset($userExtInfo['youth_classification']) && $userExtInfo['youth_classification'] > 0): ?>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Youth Classification</span>
                            <span class="text-sm font-medium text-gray-900">
                                <?= $field_mappings['youth_classification'][$userExtInfo['youth_classification']] ?? 'Not specified' ?>
                            </span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (isset($userExtInfo['age_group']) && $userExtInfo['age_group'] > 0): ?>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Age Group</span>
                            <span class="text-sm font-medium text-gray-900">
                                <?= $field_mappings['age_group'][$userExtInfo['age_group']] ?? 'Not specified' ?>
                            </span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (isset($userExtInfo['work_status']) && $userExtInfo['work_status'] > 0): ?>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Work Status</span>
                            <span class="text-sm font-medium text-gray-900">
                                <?= $field_mappings['work_status'][$userExtInfo['work_status']] ?? 'Not specified' ?>
                            </span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (isset($userExtInfo['educational_background']) && $userExtInfo['educational_background'] > 0): ?>
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
                        
                        <?php if (isset($userExtInfo['how_many_times']) && $userExtInfo['how_many_times'] > 0): ?>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Times Attended</span>
                            <span class="text-sm font-medium text-gray-900">
                                <?= $field_mappings['how_many_times'][$userExtInfo['how_many_times']] ?? 'Not specified' ?>
                            </span>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (isset($userExtInfo['no_why']) && array_key_exists((string)$userExtInfo['no_why'], $field_mappings['no_why'])): ?>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Reason for Non-Attendance</span>
                            <span class="text-sm font-medium text-gray-900">
                                <?= $field_mappings['no_why'][$userExtInfo['no_why']] ?? 'Not specified' ?>
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
</div>
