

<!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-h-0 ml-0 lg:ml-64 pt-16">
        <main class="flex-1 overflow-auto p-4 lg:p-6 bg-gray-50">
                <!-- Page Header -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-5 lg:p-6 mb-4 sm:mb-5 lg:mb-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
                        <div class="flex-1">
                            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">Settings</h1>
                            <p class="text-gray-600 mt-1 text-sm sm:text-base">Manage your account preferences and information</p>
                        </div>
                    </div>
                </div>

                <!-- Toasts are appended to body by showNotification() -->

                <!-- Settings Tabs and Content -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <!-- Tabs Navigation -->
                    <div class="border-b border-gray-200">
                        <div class="flex overflow-x-auto scrollbar-hide" id="tabs-container" role="tablist" aria-label="Settings tabs">
                            <button id="tab-profile" class="tab-button whitespace-nowrap px-5 py-3 text-sm font-medium active" data-tab="profile" role="tab" aria-selected="true" aria-controls="profile-tab">
                                <svg class="inline-block w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Profile
                            </button>
                            <button id="tab-security" class="tab-button whitespace-nowrap px-5 py-3 text-sm font-medium" data-tab="security" role="tab" aria-selected="false" aria-controls="security-tab">
                                <svg class="inline-block w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Security
                            </button>
                        </div>
                    </div>

                    <!-- Tab Content -->
                    <div class="p-6">
                        <!-- Profile Tab -->
                        <div id="profile-tab" class="tab-content" role="tabpanel" aria-labelledby="tab-profile">
                            <form action="<?= base_url('kk/settings/profile') ?>" method="post" enctype="multipart/form-data">
                                <!-- Profile Picture Section -->
                                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 mb-8 pb-8 border-b border-gray-200">
                                    <div class="flex-shrink-0">
                                        <div class="relative">
                                            <?php if (!empty($userExtInfo['profile_picture'])): ?>
                                                <?php 
                                                    $pp = $userExtInfo['profile_picture'];
                                                    $ppUrl = (strpos($pp, '/') !== false) ? base_url($pp) : base_url('uploads/profile_pictures/' . $pp);
                                                ?>
                                                <img id="profile-image" src="<?= esc($ppUrl) ?>" alt="Profile Picture" class="w-32 h-32 object-cover border-4 border-white shadow-lg rounded-full">
                                            <?php else: ?>
                                                <div id="profile-placeholder" class="w-32 h-32 bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center shadow-lg border-4 border-white rounded-full">
                                                    <span class="text-white text-3xl font-bold">
                                                        <?= $user ? strtoupper(substr($user['first_name'] ?? 'U', 0, 1) . substr($user['last_name'] ?? '', 0, 1)) : 'U' ?>
                                                    </span>
                                                </div>
                                                <img id="profile-image" src="" alt="Profile Picture" class="w-32 h-32 object-cover border-4 border-white shadow-lg rounded-full hidden">
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="flex-1 text-center sm:text-left">
                                        <h3 class="text-lg font-semibold mb-2">Profile Picture</h3>
                                        <p class="text-sm text-gray-600 mb-4">Upload a clear photo to help others recognize you</p>
                                        <div class="flex flex-col sm:flex-row items-center gap-3 justify-center sm:justify-start">
                                            <!-- Hidden file input controlled by the button below (inside main form) -->
        									<input type="file" id="profile-upload" name="profile_picture" accept="image/*" class="hidden">

                                            <!-- Choose file button -->
                                            <label for="profile-upload" class="cursor-pointer inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                Choose Photo
                                            </label>

                                            <!-- Helper text and selected file name -->
                                            <div class="text-xs text-gray-500 sm:ml-2">
                                                <div>JPEG/PNG/GIF up to 2MB</div>
                                                <div id="selected-file-name" class="text-gray-600"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Personal Information Section -->
                                <h3 class="text-lg font-semibold mb-4">Personal Information</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                    <div>
                                        <label for="first-name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                        <input type="text" id="first-name" name="first_name" value="<?= esc($user['first_name'] ?? '') ?>" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                               disabled>
                                        <input type="hidden" name="first_name" value="<?= esc($user['first_name'] ?? '') ?>">
                                    </div>
                                    <div>
                                        <label for="last-name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                        <input type="text" id="last-name" name="last_name" value="<?= esc($user['last_name'] ?? '') ?>" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                               disabled>
                                        <input type="hidden" name="last_name" value="<?= esc($user['last_name'] ?? '') ?>">
                                    </div>
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                        <input type="email" id="email" name="email" value="<?= esc($user['email'] ?? '') ?>" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                         <input type="tel" id="phone" name="phone" value="<?= esc($user['phone_number'] ?? '') ?>" 
                             class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" inputmode="tel" autocomplete="tel">
                         <div id="phone-hint" class="mt-1 text-xs text-gray-500">Format: +639XXXXXXXXX</div>
                         <div id="phone-error" class="mt-1 text-xs text-red-500 hidden">Please enter a valid Philippine mobile number in the format +639XXXXXXXXX.</div>
                                    </div>
                                    <div>
                                        <label for="birthdate" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                                        <input type="date" id="birthdate" name="birthdate" value="<?= esc($user['birthdate'] ?? '') ?>" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                               disabled>
                                        <input type="hidden" name="birthdate" value="<?= esc($user['birthdate'] ?? '') ?>">
                                    </div>
                                    <div>
                                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                                        <select id="gender" name="gender" 
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                                disabled>
                                            <option value="">Select Gender</option>
                                            <option value="1" <?= ($user['sex'] ?? '') == '1' ? 'selected' : '' ?>>Male</option>
                                            <option value="2" <?= ($user['sex'] ?? '') == '2' ? 'selected' : '' ?>>Female</option>
                                        </select>
                                        <input type="hidden" name="gender" value="<?= esc($user['sex'] ?? '') ?>">
                                    </div>
                                </div>

                                <!-- Address Information Section -->
                                <h3 class="text-lg font-semibold mb-4">Address Information</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                    <div class="md:col-span-2">
                                        <label for="street" class="block text-sm font-medium text-gray-700 mb-1">Street Address / Zone/Purok</label>
                                        <input type="text" id="street" name="street" value="<?= esc($address['zone_purok'] ?? '') ?>" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label for="barangay" class="block text-sm font-medium text-gray-700 mb-1">Barangay</label>
                         <input type="text" id="barangay" name="barangay" 
                             value="<?= !empty($address_barangay_name) ? esc($address_barangay_name) : '' ?>" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                               disabled>
                                        <input type="hidden" name="barangay" value="<?= esc($address['barangay'] ?? '') ?>">
                                    </div>
                                    <div>
                                        <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                        <input type="text" id="city" name="city" value="Iriga City" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                               disabled>
                                        <input type="hidden" name="city" value="Iriga City">
                                    </div>
                                    <div>
                                        <label for="province" class="block text-sm font-medium text-gray-700 mb-1">Province</label>
                                        <input type="text" id="province" name="province" value="Camarines Sur" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                               disabled>
                                        <input type="hidden" name="province" value="Camarines Sur">
                                    </div>
                                    <div>
                                        <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">Postal Code</label>
                                        <input type="text" id="postal_code" name="postal_code" value="4431" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                               disabled>
                                        <input type="hidden" name="postal_code" value="4431">
                                    </div>
                                </div>

                                <!-- Form Buttons -->
                                <div class="flex justify-end space-x-3">
                                    <button type="submit" class="px-4 py-2 bg-blue-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Save Changes</button>
                                </div>
                            </form>
                        </div>

                        <!-- Security Tab -->
                        <div id="security-tab" class="tab-content hidden" role="tabpanel" aria-labelledby="tab-security">
                            <!-- Change Password Section -->
                            <div class="mb-8 pb-8 border-b border-gray-200">
                                <h3 class="text-lg font-semibold mb-4">Change Password</h3>
                                <form action="<?= base_url('kk/settings/password') ?>" method="post">
                                    <div class="space-y-4 max-w-md">
                                        <div>
                                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                                            <input type="password" id="current_password" name="current_password" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Confirm your new password">
                                        </div>
                                        <div>
                                            <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                                            <div class="relative">
                                                <input type="password" id="new_password" name="new_password" class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required minlength="8" placeholder="Enter your new password">
                                                <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700 password-toggle focus:outline-none focus:ring-0 focus:ring-offset-0" data-toggle-password data-target="#new_password" aria-label="Toggle password visibility">
                                                    <!-- show icon -->
                                                    <svg data-icon="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path d="M2 12c2.5-4 6.5-7 10-7s7.5 3 10 7c-2.5 4-6.5 7-10 7s-7.5-3-10-7z"/>
                                                        <circle cx="12" cy="12" r="2.5"/>
                                                    </svg>
                                                    <!-- hide icon -->
                                                    <svg data-icon="hide" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path d="M2 12c2.5-4 6.5-7 10-7s7.5 3 10 7c-2.5 4-6.5 7-10 7s-7.5-3-10-7z"/>
                                                        <circle cx="12" cy="12" r="2.5"/>
                                                        <path d="M4 4c5 5 11 11 16 16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                            <!-- Strength Indicator -->
                                            <div id="password-strength" class="mt-2 hidden">
                                                <div class="flex items-center space-x-2">
                                                    <div class="flex space-x-1">
                                                        <div id="strength-bar-1" class="w-6 h-1 bg-gray-300 rounded"></div>
                                                        <div id="strength-bar-2" class="w-6 h-1 bg-gray-300 rounded"></div>
                                                        <div id="strength-bar-3" class="w-6 h-1 bg-gray-300 rounded"></div>
                                                        <div id="strength-bar-4" class="w-6 h-1 bg-gray-300 rounded"></div>
                                                    </div>
                                                    <span id="strength-text" class="text-xs font-medium">Weak</span>
                                                </div>
                                                <div id="password-requirements" class="mt-2 space-y-1 text-xs">
                                                    <div id="req-length" class="flex items-center space-x-2">
                                                        <span class="requirement-icon">✗</span>
                                                        <span class="text-gray-600">At least 8 characters</span>
                                                    </div>
                                                    <div id="req-uppercase" class="flex items-center space-x-2">
                                                        <span class="requirement-icon">✗</span>
                                                        <span class="text-gray-600">One uppercase letter</span>
                                                    </div>
                                                    <div id="req-lowercase" class="flex items-center space-x-2">
                                                        <span class="requirement-icon">✗</span>
                                                        <span class="text-gray-600">One lowercase letter</span>
                                                    </div>
                                                    <div id="req-number" class="flex items-center space-x-2">
                                                        <span class="requirement-icon">✗</span>
                                                        <span class="text-gray-600">One number</span>
                                                    </div>
                                                    <div id="req-special" class="flex items-center space-x-2">
                                                        <span class="requirement-icon">✗</span>
                                                        <span class="text-gray-600">One special character</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                                            <div class="relative">
                                                <input type="password" id="confirm_password" name="confirm_password" class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required minlength="8" placeholder="Confirm your new password">
                                                <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700 password-toggle focus:outline-none focus:ring-0 focus:ring-offset-0" data-toggle-password data-target="#confirm_password" aria-label="Toggle password visibility">
                                                    <!-- show icon -->
                                                    <svg data-icon="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path d="M2 12c2.5-4 6.5-7 10-7s7.5 3 10 7c-2.5 4-6.5 7-10 7s-7.5-3-10-7z"/>
                                                        <circle cx="12" cy="12" r="2.5"/>
                                                    </svg>
                                                    <!-- hide icon -->
                                                    <svg data-icon="hide" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path d="M2 12c2.5-4 6.5-7 10-7s7.5 3 10 7c-2.5 4-6.5 7-10 7s-7.5-3-10-7z"/>
                                                        <circle cx="12" cy="12" r="2.5"/>
                                                        <path d="M4 4c5 5 11 11 16 16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                            <div id="confirm-password-error" class="mt-1 text-xs text-red-500 hidden">Passwords do not match</div>
                                            <div id="confirm-password-success" class="mt-1 text-xs text-green-500 hidden">Passwords match</div>
                                        </div>
                                        <div class="pt-2">
                                            <button type="submit" id="submitButton" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                Update Password
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>

/* Tabs scrolling */
#tabs-container {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

#tabs-container::-webkit-scrollbar {
    display: none;
}

/* Underline tab style (original) */
.tab-button { border-bottom: 2px solid transparent; color: #4b5563; }
.tab-button:hover { color: #111827; }
.tab-button.active { color: #2563eb; border-color: #2563eb; }

/* Focus styles for better accessibility */
*:focus { outline: 2px solid #3b82f6; outline-offset: 2px; }
/* Remove side focus ring on tabs; keep it simple */
.tab-button:focus, .tab-button:focus-visible { outline: none; box-shadow: none; }
.tab-button:not(.active):focus-visible { background: #f9fafb; }
/* Suppress focus ring on password eye buttons */
.password-toggle:focus,
.password-toggle:focus-visible { outline: none !important; box-shadow: none !important; }
</style>

<script>
// Toast notifications (ped-officers style)
function showNotification(message, type = 'info') {
    if (!message) return;
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
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
            </button>
        </div>
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);

    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentElement) notification.remove();
        }, 300);
    }, 5000);
}

document.addEventListener('DOMContentLoaded', function() {
    // Render flash messages as toasts
    const flashSuccess = <?= json_encode(session()->getFlashdata('success')) ?>;
    const flashError = <?= json_encode(session()->getFlashdata('error')) ?>;
    if (flashSuccess) showNotification(flashSuccess, 'success');
    if (flashError) showNotification(flashError, 'error');
    // Tab functionality
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');
    const TAB_KEY = 'kk_settings_active_tab';

    function activateTab(tabId) {
        if (!tabId) tabId = 'profile';
        // Remove active class from all tab buttons and contents
        tabButtons.forEach(btn => { btn.classList.remove('active'); btn.setAttribute('aria-selected', 'false'); });
        tabContents.forEach(content => content.classList.add('hidden'));
        // Activate matching button and content if exist
        const btn = Array.from(tabButtons).find(b => b.dataset.tab === tabId) || tabButtons[0];
        const content = document.getElementById(`${tabId}-tab`) || tabContents[0];
        if (btn) { btn.classList.add('active'); btn.setAttribute('aria-selected', 'true'); }
        if (content) content.classList.remove('hidden');
    }
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabId = this.dataset.tab;
            activateTab(tabId);
            try { localStorage.setItem(TAB_KEY, tabId); } catch (e) {}
        });
    });

    // Initialize tab from URL (?tab=security) or saved selection; fallback to profile
    let initialTab = 'profile';
    try {
        const params = new URLSearchParams(window.location.search);
        initialTab = params.get('tab') || localStorage.getItem(TAB_KEY) || 'profile';
    } catch (e) {
        initialTab = 'profile';
    }
    activateTab(initialTab);
    
    // Profile picture upload preview
    const profileUpload = document.getElementById('profile-upload');
    if (profileUpload) {
        profileUpload.addEventListener('change', function() {
            const file = this.files[0];
            if (file && file.type.match('image.*')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imgEl = document.getElementById('profile-image');
                    const placeholder = document.getElementById('profile-placeholder');
                    if (imgEl) {
                        imgEl.src = e.target.result;
                        imgEl.classList.remove('hidden');
                    }
                    if (placeholder) {
                        placeholder.classList.add('hidden');
                    }
                };
                reader.readAsDataURL(file);
                // Update selected filename and enable upload button
                const nameEl = document.getElementById('selected-file-name');
                if (nameEl) nameEl.textContent = file.name;
            }
        });
    }
    
    // Phone number validation (profiling-style) for Philippines numbers
    (function initPhoneValidation() {
        const phoneEl = document.getElementById('phone');
        const errorEl = document.getElementById('phone-error');
        if (!phoneEl) return;

        // If empty on load, set +63 default; if starts with 09 or 9, normalize
        function normalizeOnLoad(val) {
            const v = (val || '').trim();
            if (!v) return '+63';
            return normalizeValue(v);
        }

        function normalizeValue(val) {
            let digits = String(val || '').replace(/\D/g, '');
            if (digits.startsWith('63')) digits = digits.slice(2);
            else if (digits.startsWith('09')) digits = digits.slice(1);
            else if (digits.startsWith('0')) digits = digits.slice(1);
            digits = digits.slice(0, 10);
            return '+63' + digits;
        }

        function isValid(val) {
            return /^\+63\d{10}$/.test(val);
        }

        function clampCaret() {
            // Ensure caret never goes before "+63"
            try {
                const start = phoneEl.selectionStart ?? 0;
                const end = phoneEl.selectionEnd ?? 0;
                const s = Math.max(3, start);
                const e = Math.max(3, end);
                if (s !== start || e !== end) phoneEl.setSelectionRange(s, e);
            } catch (e) {}
        }

        function getDigitsLen(raw) {
            const v = String(raw ?? '');
            return v.replace(/^\+?63/, '').replace(/\D/g, '').length;
        }

        // Initialize value
        phoneEl.value = normalizeOnLoad(phoneEl.value);

        phoneEl.addEventListener('focus', () => {
            if (!phoneEl.value || phoneEl.value === '+63') phoneEl.value = '+63';
            setTimeout(clampCaret, 0);
        });

        // Keep caret after prefix on click/selection
        ['click', 'mouseup', 'keyup'].forEach(evt => {
            phoneEl.addEventListener(evt, () => {
                setTimeout(clampCaret, 0);
            });
        });

        // Prevent deleting the "+63" prefix
        phoneEl.addEventListener('keydown', (e) => {
            const key = e.key;
            const start = phoneEl.selectionStart ?? 0;
            const end = phoneEl.selectionEnd ?? 0;
            // Remember previous caret and digits for smarter repositioning
            phoneEl._prevPos = end;
            phoneEl._prevDigitsLen = getDigitsLen(phoneEl.value);
            phoneEl._deleting = (key === 'Backspace' || key === 'Delete');

            // Block backspace/delete when selection is entirely within prefix
            if ((key === 'Backspace' && start <= 3 && end <= 3) ||
                (key === 'Delete' && start < 3 && end <= 3)) {
                e.preventDefault();
                setTimeout(clampCaret, 0);
                return;
            }
            // Keep Home from going before prefix
            if (key === 'Home') {
                e.preventDefault();
                phoneEl.setSelectionRange(3, 3);
                return;
            }
        });

        phoneEl.addEventListener('input', () => {
            const prevPos = phoneEl._prevPos ?? phoneEl.value.length;
            const prevDigits = phoneEl._prevDigitsLen ?? getDigitsLen(phoneEl.value);
            phoneEl.value = normalizeValue(phoneEl.value);
            const newDigits = getDigitsLen(phoneEl.value);
            // Compute a stable new caret position relative to digits change
            let newPos;
            if (phoneEl._deleting) {
                newPos = Math.max(3, prevPos - 1);
            } else {
                const deltaDigits = newDigits - prevDigits;
                newPos = Math.max(3, prevPos + deltaDigits);
            }
            try { phoneEl.setSelectionRange(newPos, newPos); } catch (e) {}
            // Live error toggle
            if (phoneEl.value === '+63' || isValid(phoneEl.value)) {
                errorEl && errorEl.classList.add('hidden');
                phoneEl.classList.remove('border-red-500');
            } else {
                errorEl && errorEl.classList.remove('hidden');
                phoneEl.classList.add('border-red-500');
            }
        });

        phoneEl.addEventListener('blur', () => {
            // Hide error if empty prefix only; don't save just '+63'
            if (phoneEl.value === '+63') {
                errorEl && errorEl.classList.add('hidden');
                phoneEl.classList.remove('border-red-500');
            } else if (!isValid(phoneEl.value)) {
                errorEl && errorEl.classList.remove('hidden');
                phoneEl.classList.add('border-red-500');
            } else {
                errorEl && errorEl.classList.add('hidden');
                phoneEl.classList.remove('border-red-500');
            }
        });
    })();
    
    // Form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            // Get all required fields
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500');
                    
                    // Add error message if it doesn't exist
                    let errorMsg = field.parentElement.querySelector('.error-message');
                    if (!errorMsg) {
                        errorMsg = document.createElement('p');
                        errorMsg.classList.add('text-red-500', 'text-xs', 'mt-1', 'error-message');
                        errorMsg.textContent = 'This field is required';
                        field.parentElement.appendChild(errorMsg);
                    }
                } else {
                    field.classList.remove('border-red-500');
                    const errorMsg = field.parentElement.querySelector('.error-message');
                    if (errorMsg) {
                        errorMsg.remove();
                    }
                }
            });
            
            // Check if passwords match in security tab
            const newPassword = document.getElementById('new_password');
            const confirmPassword = document.getElementById('confirm_password');
            
            if (newPassword && confirmPassword && newPassword.value && confirmPassword.value) {
                if (newPassword.value !== confirmPassword.value) {
                    isValid = false;
                    confirmPassword.classList.add('border-red-500');
                    
                    // Add or update error message
                    let errorMsg = confirmPassword.parentElement.querySelector('.error-message');
                    if (!errorMsg) {
                        errorMsg = document.createElement('p');
                        errorMsg.classList.add('text-red-500', 'text-xs', 'mt-1', 'error-message');
                        confirmPassword.parentElement.appendChild(errorMsg);
                    }
                    errorMsg.textContent = 'Passwords do not match';
                }
            }
            
            if (!isValid) {
                event.preventDefault();
            }

            // Phone: don't submit bare '+63'; validate if filled
            const phoneEl = form.querySelector('#phone');
            const phoneErr = document.getElementById('phone-error');
            if (phoneEl) {
                if (phoneEl.value === '+63') {
                    phoneEl.value = '';
                    phoneErr && phoneErr.classList.add('hidden');
                    phoneEl.classList.remove('border-red-500');
                } else if (phoneEl.value && !/^\+63\d{10}$/.test(phoneEl.value)) {
                    event.preventDefault();
                    isValid = false;
                    phoneErr && phoneErr.classList.remove('hidden');
                    phoneEl.classList.add('border-red-500');
                }
            }
        });
    });

    // Email: validity + optional uniqueness (debounced)
    (function initEmailValidation(){
        const emailInput = document.querySelector('#profile-tab form input[type="email"], #profile-tab form input[name="email"], #email');
        if (!emailInput) return;
        const initialEmail = emailInput.value || '';
        let emailTimer;
        const EMAIL_CHECK_URL = '<?= base_url('kk/settings/check-email') ?>';
        const emailErrId = 'kk-email-error';
        function getOrMakeErrorEl(){
            let el = document.getElementById(emailErrId);
            if (el) return el;
            el = document.createElement('p');
            el.id = emailErrId;
            el.className = 'mt-1 text-xs text-red-600 hidden';
            emailInput.insertAdjacentElement('afterend', el);
            return el;
        }
        const emailErr = getOrMakeErrorEl();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/i;
        function showError(msg){
            emailInput.classList.add('border-red-500');
            emailInput.classList.remove('border-green-500');
            emailInput.setAttribute('aria-invalid','true');
            emailErr.textContent = msg || '';
            emailErr.classList.remove('hidden');
        }
        function clearError(){
            emailInput.classList.remove('border-red-500');
            emailInput.classList.add('border-green-500');
            emailInput.removeAttribute('aria-invalid');
            emailErr.textContent = '';
            emailErr.classList.add('hidden');
        }
        async function isEmailAvailable(email){
            if (!emailRegex.test(email) || email === initialEmail) return true;
            try{
                const res = await fetch(EMAIL_CHECK_URL + '?email=' + encodeURIComponent(email), { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                if (!res.ok) return true; // fail-open if endpoint unavailable
                const data = await res.json().catch(()=>null);
                if (data && typeof data.available === 'boolean') return data.available;
                return true;
            }catch{ return true; }
        }
        async function validate(){
            const v = (emailInput.value || '').trim();
            if (!v){ showError('Email is required.'); return false; }
            if (!emailRegex.test(v)){ showError('Please enter a valid email address.'); return false; }
            clearError();
            clearTimeout(emailTimer);
            emailTimer = setTimeout(async ()=>{
                const available = await isEmailAvailable(v);
                if (!available){ showError('This email is already in use.'); if (typeof showNotification==='function'){ showNotification('Email already in use.', 'error'); } }
            }, 350);
            return true;
        }
        emailInput.addEventListener('input', validate);
        emailInput.addEventListener('blur', validate);
        const form = document.querySelector('#profile-tab form');
        // Prepare signature for change detection
        function fieldSignature(){
            const els = form.querySelectorAll('input, select, textarea');
            const parts = [];
            els.forEach((el, idx)=>{
                const type = (el.type||'').toLowerCase();
                if (type === 'submit' || type === 'button' || type === 'file') return;
                let val = '';
                if (type === 'checkbox' || type === 'radio'){
                    val = el.checked ? (el.value||'1') : '';
                } else if (el.id === 'phone' || el.id === 'phone_number' || el.name === 'phone' || el.name === 'phone_number'){
                    // use existing normalization rules
                    let digits = String((el.value||'')).replace(/\D/g,'');
                    if (digits.startsWith('63')) digits = digits.slice(2);
                    if (digits.startsWith('0')) digits = digits.slice(1);
                    digits = digits.slice(0,10);
                    val = '+63' + digits;
                } else {
                    val = (el.value||'').trim();
                }
                const key = (el.name || el.id || ('idx'+idx));
                parts.push(key+'='+val);
            });
            return parts.sort().join('|');
        }
        const initialSig = fieldSignature();
        if (form){
            form.addEventListener('submit', async (e)=>{
                // Required fields: email and phone
                const missing = [];
                const phoneEl = document.getElementById('phone');
                let phoneDigits = '';
                if (phoneEl){
                    let digits = String((phoneEl.value||'')).replace(/\D/g,'');
                    if (digits.startsWith('63')) digits = digits.slice(2);
                    if (digits.startsWith('0')) digits = digits.slice(1);
                    digits = digits.slice(0,10);
                    phoneDigits = digits;
                }
                const missingEmail = !((emailInput.value||'').trim());
                if (missingEmail) missing.push('Email');
                if (!phoneDigits) missing.push('Phone');
                if (missing.length){
                    e.preventDefault();
                    if (typeof showNotification==='function') showNotification('Please fill in: '+missing.join(', ')+'.', 'warning');
                    return;
                }
                // No-change guard (allow submit if photo selected)
                const currentSig = fieldSignature();
                const fileChanged = !!(form.querySelector('input[type="file"][name="profile_picture"]')?.files?.length);
                if (currentSig === initialSig && !fileChanged){
                    e.preventDefault();
                    if (typeof showNotification==='function') showNotification('No changes to save.', 'info');
                    return;
                }

                const ok = await validate();
                let okUnique = true;
                const v = (emailInput.value||'').trim();
                if (emailRegex.test(v) && v !== initialEmail){
                    try{ okUnique = await isEmailAvailable(v); }catch{ okUnique = true; }
                    if (!okUnique) showError('This email is already in use.');
                }
                if (!(ok && okUnique)){
                    e.preventDefault();
                    if (typeof showNotification==='function') showNotification('Please fix the highlighted fields before saving.', 'error');
                }
            });
        }
    })();

    // Zone numeric-only (if a dedicated field exists)
    (function initZoneNumericOnly(){
        const zoneInput = document.querySelector('#profile-tab form #zone, #profile-tab form input[name="zone"], #profile-tab form input[name="zone_purok"], #profile-tab form input[name="purok"], #profile-tab form input[name="zoneNo"], #profile-tab form input[name="zone_no"]');
        if (!zoneInput) return; // KK form may not have a separate zone field
        const errId = 'kk-zone-error';
        let err = document.getElementById(errId);
        if (!err){
            err = document.createElement('p');
            err.id = errId;
            err.className = 'mt-1 text-xs text-red-600 hidden';
            zoneInput.insertAdjacentElement('afterend', err);
        }
        function validate(){
            const before = zoneInput.value || '';
            const after = before.replace(/\D/g,'');
            if (after !== before) zoneInput.value = after;
            if (after && !/^\d+$/.test(after)){
                err.textContent = 'Zone must be numbers only.';
                err.classList.remove('hidden');
                zoneInput.classList.add('border-red-500');
                zoneInput.classList.remove('border-green-500');
                return false;
            }
            err.textContent = '';
            err.classList.add('hidden');
            zoneInput.classList.remove('border-red-500');
            zoneInput.classList.add('border-green-500');
            return true;
        }
        zoneInput.addEventListener('input', validate);
        zoneInput.addEventListener('blur', validate);
        const form = document.querySelector('#profile-tab form');
        if (form){
            form.addEventListener('submit', (e)=>{ if (!validate()){ e.preventDefault(); if (typeof showNotification==='function') showNotification('Please fix the highlighted fields before saving.', 'error'); } });
        }
    })();
});

// Password visibility toggles (match account_settings style)
(function() {
    const handler = (btn) => {
        const targetSel = btn.getAttribute('data-target');
        if (!targetSel) return;
        const input = document.querySelector(targetSel);
        if (!input) return;
        const showIcon = btn.querySelector('[data-icon="show"]');
        const hideIcon = btn.querySelector('[data-icon="hide"]');
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        if (showIcon && hideIcon) {
            if (isPassword) { showIcon.classList.add('hidden'); hideIcon.classList.remove('hidden'); }
            else { hideIcon.classList.add('hidden'); showIcon.classList.remove('hidden'); }
        }
    };
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-toggle-password]');
        if (!btn) return;
        handler(btn);
    });
})();

// Strong password helpers
function validatePasswordMatch() {
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('confirm_password');
    const errorMessage = document.getElementById('confirm-password-error');
    const successMessage = document.getElementById('confirm-password-success');
    const submitButton = document.getElementById('submitButton');

    if (!newPassword || !confirmPassword) return;

    if (confirmPassword.value.length > 0) {
        if (newPassword.value !== confirmPassword.value) {
            confirmPassword.classList.add('border-red-500');
            confirmPassword.classList.remove('border-green-500');
            errorMessage.classList.remove('hidden');
            successMessage.classList.add('hidden');
            submitButton.disabled = true;
            submitButton.classList.add('bg-gray-400', 'cursor-not-allowed');
            submitButton.classList.remove('bg-blue-600', 'hover:bg-blue-700');
        } else {
            confirmPassword.classList.remove('border-red-500');
            confirmPassword.classList.add('border-green-500');
            errorMessage.classList.add('hidden');
            successMessage.classList.remove('hidden');
            checkSubmitButtonState();
        }
    } else {
        confirmPassword.classList.remove('border-red-500', 'border-green-500');
        errorMessage.classList.add('hidden');
        successMessage.classList.add('hidden');
        submitButton.disabled = true;
        submitButton.classList.add('bg-gray-400', 'cursor-not-allowed');
        submitButton.classList.remove('bg-blue-600', 'hover:bg-blue-700');
    }
}

function checkSubmitButtonState() {
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('confirm_password');
    const submitButton = document.getElementById('submitButton');
    if (!newPassword || !confirmPassword || !submitButton) return;

    const pwd = newPassword.value;
    const checks = {
        length: pwd.length >= 8,
        uppercase: /[A-Z]/.test(pwd),
        lowercase: /[a-z]/.test(pwd),
        number: /\d/.test(pwd),
        special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>?]/.test(pwd)
    };
    const allOk = Object.values(checks).every(Boolean);
    const match = pwd === confirmPassword.value && confirmPassword.value.length > 0;
    if (allOk && match) {
        submitButton.disabled = false;
        submitButton.classList.remove('bg-gray-400', 'cursor-not-allowed');
        submitButton.classList.add('bg-blue-600', 'hover:bg-blue-700');
    } else {
        submitButton.disabled = true;
        submitButton.classList.add('bg-gray-400', 'cursor-not-allowed');
        submitButton.classList.remove('bg-blue-600', 'hover:bg-blue-700');
    }
}

function initializePasswordValidation() {
    const passwordInput = document.getElementById('new_password');
    const strengthContainer = document.getElementById('password-strength');
    const strengthText = document.getElementById('strength-text');
    const strengthBars = [
        document.getElementById('strength-bar-1'),
        document.getElementById('strength-bar-2'),
        document.getElementById('strength-bar-3'),
        document.getElementById('strength-bar-4')
    ];
    const requirements = {
        length: document.getElementById('req-length'),
        uppercase: document.getElementById('req-uppercase'),
        lowercase: document.getElementById('req-lowercase'),
        number: document.getElementById('req-number'),
        special: document.getElementById('req-special')
    };
    if (!passwordInput) return;
    passwordInput.addEventListener('input', function (e) {
        const password = e.target.value;
        if (password.length > 0) {
            strengthContainer.classList.remove('hidden');
        } else {
            strengthContainer.classList.add('hidden');
            return;
        }
        const checks = {
            length: password.length >= 8,
            uppercase: /[A-Z]/.test(password),
            lowercase: /[a-z]/.test(password),
            number: /\d/.test(password),
            special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>?]/.test(password)
        };
        Object.keys(checks).forEach(key => {
            const el = requirements[key];
            const icon = el.querySelector('.requirement-icon');
            const text = el.querySelector('span:last-child');
            if (checks[key]) {
                icon.textContent = '✓';
                icon.style.color = '#10b981';
                text.style.color = '#10b981';
            } else {
                icon.textContent = '✗';
                icon.style.color = '#ef4444';
                text.style.color = '#6b7280';
            }
        });
        const score = Object.values(checks).filter(Boolean).length;
        let strength = 'Weak';
        let color = '#ef4444';
        if (score >= 5) { strength = 'Very Strong'; color = '#10b981'; }
        else if (score >= 4) { strength = 'Strong'; color = '#059669'; }
        else if (score >= 3) { strength = 'Medium'; color = '#f59e0b'; }
        else if (score >= 2) { strength = 'Fair'; color = '#f97316'; }
        strengthBars.forEach((bar, i) => { bar.style.backgroundColor = (i < score) ? color : '#d1d5db'; });
        strengthText.textContent = strength;
        strengthText.style.color = color;
        checkSubmitButtonState();
    });
}

// Wire up listeners
document.addEventListener('DOMContentLoaded', function() {
    const confirmEl = document.getElementById('confirm_password');
    const newEl = document.getElementById('new_password');
    if (confirmEl) confirmEl.addEventListener('input', validatePasswordMatch);
    if (newEl) newEl.addEventListener('input', validatePasswordMatch);
    initializePasswordValidation();
    checkSubmitButtonState();
});
</script>

<!-- Removed separate upload form; upload is now handled by Save Changes -->
