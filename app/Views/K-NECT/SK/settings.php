<?php
// Compute Barangay Name for preview header
$session = session();
$skBarangay = $session->get('sk_barangay');
$barangayName = \App\Libraries\BarangayHelper::getBarangayName($skBarangay);
$barangayNameText = $barangayName ? strtoupper($barangayName) : '[BARANGAY NAME]';
?>
<!-- ===== MAIN CONTENT AREA ===== -->
<!-- Content area positioned to the right of sidebar -->
<div class="flex-1 flex flex-col min-h-0 ml-64 pt-16">
    <!-- Main Content Container -->
    <main class="flex-1 overflow-auto p-6 bg-gray-50">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">System Settings</h1>
            <p class="text-gray-600 mt-1">Manage system configurations and logo settings</p>
        </div>

        <!-- Settings Cards Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            <!-- Account Settings Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200 h-full flex flex-col">
                <div class="p-6 flex flex-col h-full">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="p-3 bg-green-100 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Account Settings</h3>
                                <p class="text-sm text-gray-500">Update your profile and password</p>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-3 mb-4 text-sm text-gray-600">
                        <p>Open the settings page to change your personal information and security.</p>
                    </div>
                    <div class="mt-auto">
                        <a href="<?= base_url('sk/account-settings') ?>" class="w-full bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-2 px-3 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2">
                            Manage Account
                        </a>
                    </div>
                </div>
            </div>
            <!-- User Management Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200 h-full flex flex-col">
                <div class="p-6 flex flex-col h-full">
                    <!-- Card Header (matches Logo Management style) -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="p-3 bg-blue-100 rounded-lg">
                                <!-- Simple user outline icon for Youth Management -->
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">User Management</h3>
                                <p class="text-sm text-gray-500">Manage users accounts</p>
                            </div>
                        </div>
                    </div>
                    <!-- Card Content -->
                    <div class="space-y-3 mb-4">
                        <ul class="list-disc pl-6 text-sm text-gray-600 space-y-3">
                            <li>Aged Out (31+ Years Old)</li>
                            <li>Inactive (Over 1 Year)</li>
                            <li>Deactivate User</li>
                        </ul>
                    </div>
                    <!-- Card Actions -->
                    <div class="mt-auto">
                        <a href="<?= base_url('sk/user-management') ?>" class="w-full status-tab bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-3 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2">
                            Manage Users
                        </a>
                    </div>
                </div>
            </div>

            <!-- Logo Management Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200 h-full flex flex-col">
                <div class="p-6 flex flex-col h-full">
                    <!-- Card Header -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="p-3 bg-orange-100 rounded-lg">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Logo Management</h3>
                                <p class="text-sm text-gray-500">Manage official logos</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card Content -->
                    <div class="space-y-3 mb-4">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600">Barangay Logo</span>
                            <span id="barangay-logo-status" class="inline-flex items-center">
                                <span class="w-2 h-2 bg-red-400 rounded-full mr-2"></span>
                                <span class="font-medium text-gray-900">Not uploaded</span>
                            </span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600">SK Logo</span>
                            <span id="sk-logo-status" class="inline-flex items-center">
                                <span class="w-2 h-2 bg-red-400 rounded-full mr-2"></span>
                                <span class="font-medium text-gray-900">Not uploaded</span>
                            </span>
                        </div>
                    </div>
                    
                    <!-- Card Actions -->
                    <div class="flex space-x-2 mt-auto">
                        <button onclick="openLogoManagerModal();" class="flex-1 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium py-2 px-3 rounded-lg transition-colors duration-200">
                            Manage Logos
                        </button>
                        <button onclick="openLogoPreviewModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium py-2 px-3 rounded-lg transition-colors duration-200">
                            Preview
                        </button>
                    </div>
                </div>
            </div>

        </div>

    </main>
</div>

<!-- Toast Container -->
<div id="toastContainer" class="fixed top-6 right-6 z-[99999] flex flex-col gap-2 items-end"></div>

<!-- Logo Manager Modal -->
<div id="logoManagerModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-[9999]" style="display: none;">
    <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-5/6 lg:w-4/5 xl:w-3/4 max-w-6xl shadow-lg rounded-lg bg-white">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-4 border-b">
            <h3 class="text-lg font-semibold text-gray-900">Logo Management</h3>
            <button onclick="closeLogoManagerModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Modal Content -->
        <div class="p-6">
            <!-- SK-specific layout - Two columns for Barangay and SK Logos -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Barangay Logo -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-lg p-4">
                    <div class="text-center mb-4">
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Barangay Logo</h4>
                        <p class="text-xs text-green-600 mb-1">Local Barangay</p>
                        <p class="text-xs text-gray-500">Specific to your barangay</p>
                    </div>
                    
                    <!-- Current Logo Preview -->
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-700 mb-2 text-center">Current Logo:</p>
                        <div id="current-barangay-logo" class="w-32 h-32 mx-auto bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg overflow-hidden flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Upload New Logo (Dropzone) -->
                    <div id="barangay-dropzone" class="bg-white border-2 border-dashed border-blue-300 rounded-lg p-6 hover:border-blue-400 hover:bg-blue-50 transition-all duration-200 cursor-pointer">
                        <input type="file" id="barangayLogo" accept="image/jpeg,image/jpg,image/png" onchange="previewLogo(this, 'barangay')" class="hidden">
                        <div class="flex flex-col items-center text-center select-none pointer-events-none">
                            <svg class="w-10 h-10 text-blue-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <p class="text-sm font-medium text-blue-700">Drag & drop your logo here or click to select</p>
                            <p class="text-xs text-blue-600 mt-1">JPEG, PNG • Max 2MB • Local barangay identity</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button onclick="uploadSingleLogo('barangay')" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition-colors duration-200">
                            Upload Barangay Logo
                        </button>
                    </div>
                </div>

                <!-- SK Logo -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-lg p-4">
                    <div class="text-center mb-4">
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">SK Logo</h4>
                        <p class="text-xs text-orange-600 mb-1">SK Organization</p>
                        <p class="text-xs text-gray-500">Sangguniang Kabataan</p>
                    </div>
                    
                    <!-- Current Logo Preview -->
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-700 mb-2 text-center">Current Logo:</p>
                        <div id="current-sk-logo" class="w-32 h-32 mx-auto bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg overflow-hidden flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Upload New Logo (Dropzone) -->
                    <div id="sk-dropzone" class="bg-white border-2 border-dashed border-blue-300 rounded-lg p-6 hover:border-blue-400 hover:bg-blue-50 transition-all duration-200 cursor-pointer">
                        <input type="file" id="skLogo" accept="image/jpeg,image/jpg,image/png" onchange="previewLogo(this, 'sk')" class="hidden">
                        <div class="flex flex-col items-center text-center select-none pointer-events-none">
                            <svg class="w-10 h-10 text-blue-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <p class="text-sm font-medium text-blue-700">Drag & drop your logo here or click to select</p>
                            <p class="text-xs text-blue-600 mt-1">JPEG, PNG • Max 2MB • SK organization identity</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button onclick="uploadSingleLogo('sk')" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition-colors duration-200">
                            Upload SK Logo
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <!-- Modal Footer -->
            <div class="flex items-center justify-between p-4 border-t">
            <div class="text-xs text-gray-500">
                Upload Barangay and SK logos individually or use "Upload All" to process selected files.
            </div>
            <div class="flex space-x-2">
                <button onclick="closeLogoManagerModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded transition-colors duration-200">
                    Close
                </button>
                <button onclick="uploadAllLogos()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition-colors duration-200">
                    Upload All
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Logo Preview Modal -->
<div id="logoPreviewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-[9999]">
    <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-5/6 lg:w-4/5 xl:w-3/4 max-w-7xl shadow-lg rounded-lg bg-white">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-4 border-b">
            <h3 class="text-lg font-semibold text-gray-900">Logo Preview</h3>
            <button onclick="closeLogoPreviewModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Modal Content -->
        <div class="p-6">
            
            <!-- Document Header Preview -->
            <div class="bg-gray-50 p-6 rounded-lg mb-6">
                <h4 class="text-lg font-medium text-gray-900 mb-4">Document Header Preview</h4>
                <div class="bg-white p-6 rounded border">
                    <div class="flex items-center justify-between mb-4">
                        <!-- Barangay Logo -->
                        <div class="text-center">
                            <div id="preview-barangay-logo" class="w-20 h-20 mx-auto mb-2 rounded flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <!-- Center Text -->
                        <div class="text-center flex-1 mx-4">
                            <h2 class="text-base font-semibold text-gray-900">REPUBLIC OF THE PHILIPPINES</h2>
                            <h3 class="text-base font-semibold text-gray-900">PROVINCE OF CAMARINES SUR</h3>
                            <h3 class="text-base font-semibold text-gray-800">CITY OF IRIGA</h3>
                            <h4 class="text-xs font-medium text-gray-700">SANGGUNIANG KABATAAN NG</h4>
                            <h4 class="text-xs font-medium text-gray-700">BARANGAY <?= esc($barangayNameText) ?></h4>
                        </div>
                        <!-- SK Logo (right side) -->
                        <div class="text-center">
                            <div id="preview-sk-header-logo" class="w-20 h-20 mx-auto mb-2 rounded flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="border-gray-300 mb-4">
                    
                    <div class="text-center">
                        <h4 class="text-base font-bold text-gray-900">KATIPUNAN NG KABATAAN YOUTH PROFILE</h4>
                        <p class="text-sm text-gray-600 mt-2">Sample document content here...</p>
                    </div>
                </div>
            </div>
            
            <!-- Logo Status Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <div id="preview-status-barangay" class="w-16 h-16 mx-auto mb-2 border border-gray-300 rounded flex items-center justify-center bg-gray-100">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h5 class="font-medium text-gray-900">Barangay Logo</h5>
                    <p id="preview-barangay-status" class="text-sm text-red-600">Not uploaded</p>
                    <p class="text-xs text-gray-500 mt-1">Local barangay identity</p>
                </div>

                <div class="text-center p-4 bg-orange-50 rounded-lg">
                    <div id="preview-status-sk" class="w-16 h-16 mx-auto mb-2 border border-gray-300 rounded flex items-center justify-center bg-gray-100">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h5 class="font-medium text-gray-900">SK Logo</h5>
                    <p id="preview-sk-status" class="text-sm text-red-600">Not uploaded</p>
                    <p class="text-xs text-gray-500 mt-1">Sangguniang Kabataan logo</p>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-end p-4 border-t space-x-2">
            <button onclick="closeLogoPreviewModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded transition-colors duration-200">
                Close
            </button>
            <button onclick="openLogoManagerModal(); closeLogoPreviewModal();" class="bg-orange-600 hover:bg-orange-700 text-white font-medium py-2 px-4 rounded transition-colors duration-200">
                Manage Logos
            </button>
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
                        <input type="radio" name="deactivateReason" value="special_case" class="mr-3 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm">Overage and Inactive</span>
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

<script>
// Logo Management Variables
let currentDeactivateUserId = null;
let currentDeactivateUserName = '';

// Toast notification function
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-[100000] p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
    
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

function openLogoManagerModal() {
    console.log('Opening logo manager modal...'); // Debug log
    const modal = document.getElementById('logoManagerModal');
    console.log('Modal element:', modal); // Debug log
    if (modal) {
        modal.classList.remove('hidden');
        modal.style.display = 'block'; // Fallback method
        console.log('Modal classes after opening:', modal.className); // Debug log
        loadExistingLogos(); // Load existing logos when modal opens
    } else {
        console.error('Logo manager modal not found!');
        showNotification('Error: Logo manager modal not found. Please refresh the page and try again.', 'error');
    }
}

function closeLogoManagerModal() {
    console.log('Closing logo manager modal...'); // Debug log
    const modal = document.getElementById('logoManagerModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.style.display = 'none'; // Fallback method
    }
}

function openLogoPreviewModal() {
    document.getElementById('logoPreviewModal').classList.remove('hidden');
    loadLogoPreview(); // Load current logos for preview
}

function closeLogoPreviewModal() {
    document.getElementById('logoPreviewModal').classList.add('hidden');
}

function previewLogo(input, logoType) {
    const file = input.files[0];
    const currentLogoContainer = document.getElementById(`current-${logoType}-logo`);
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            if (currentLogoContainer) {
                currentLogoContainer.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                currentLogoContainer.classList.add('border-solid', 'border-blue-300', 'bg-blue-50');
                currentLogoContainer.classList.remove('border-dashed', 'border-gray-300');
            }
        };
        reader.readAsDataURL(file);
    }
}

function uploadSingleLogo(logoType) {
    const fileInput = document.getElementById(`${logoType}Logo`);
    const file = fileInput.files[0];
    
    if (!file) {
        showNotification(`Please select a ${logoType} logo file first.`, 'error');
        return;
    }
    
    uploadLogo(file, logoType)
        .then(result => {
            if (result.success) {
                showNotification(`${logoType.charAt(0).toUpperCase() + logoType.slice(1)} logo uploaded successfully!`, 'success');
                // Clear the file input
                fileInput.value = '';
                // Reload existing logos to show the updated one with a small delay
                setTimeout(() => {
                    loadExistingLogos();
                    // Update logo status in the main page
                    checkLogoStatus();
                }, 500);
            } else {
                showNotification(`Error uploading ${logoType} logo: ${result.message}`, 'error');
            }
        })
        .catch(error => {
            console.error(`Error uploading ${logoType} logo:`, error);
            showNotification(`An error occurred while uploading ${logoType} logo.`, 'error');
        });
}

function uploadAllLogos() {
    const uploads = [];
    
    // Check for Barangay logo
    const barangayFile = document.getElementById('barangayLogo').files[0];
    if (barangayFile) uploads.push(uploadLogo(barangayFile, 'barangay'));
    
    // Check for SK logo
    const skFile = document.getElementById('skLogo').files[0];
    if (skFile) uploads.push(uploadLogo(skFile, 'sk'));
    
    if (uploads.length === 0) {
        showNotification('No files selected for upload.', 'error');
        return;
    }
    
    Promise.all(uploads)
        .then(results => {
            const successful = results.filter(r => r.success);
            const failed = results.filter(r => !r.success);
            
            let message = `Successfully uploaded ${successful.length} logo(s).`;
            if (failed.length > 0) {
                message += ` Failed to upload ${failed.length} logo(s).`;
            }
            
            showNotification(message, successful.length > 0 ? 'success' : 'error');
            
            // Reset file inputs
            document.getElementById('barangayLogo').value = '';
            document.getElementById('skLogo').value = '';
            
            // Reload existing logos to show the updated ones with a small delay
            setTimeout(() => {
                loadExistingLogos();
                // Update logo status in the main page
                checkLogoStatus();
            }, 500);
        })
        .catch(error => {
            console.error('Error uploading logos:', error);
            showNotification('An error occurred while uploading logos.', 'error');
        });
}

function loadLogoPreview() {
    fetch('<?= base_url('documents/logos') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const logos = data.data;
                updatePreviewModal(logos);
            } else {
                console.error('Failed to load logos for preview:', data.message);
            }
        })
        .catch(error => {
            console.error('Error loading logos for preview:', error);
        });
}

function updatePreviewModal(logos) {
    // Barangay
    // Update Barangay logo in document header preview
    const barangayLogo = logos.barangay;
    const headerBarangayLogoElement = document.getElementById('preview-barangay-logo');
    if (barangayLogo && headerBarangayLogoElement) {
        headerBarangayLogoElement.innerHTML = `<img src="<?= base_url() ?>${barangayLogo.file_path}" class="w-full h-full object-contain">`;
    }
    
    // Update Barangay status
    const barangayStatusLogoElement = document.getElementById('preview-status-barangay');
    const barangayStatusTextElement = document.getElementById('preview-barangay-status');
    
    if (barangayLogo) {
        if (barangayStatusLogoElement) {
            barangayStatusLogoElement.innerHTML = `<img src="<?= base_url() ?>${barangayLogo.file_path}" class="w-full h-full object-contain">`;
        }
        if (barangayStatusTextElement) {
            barangayStatusTextElement.textContent = 'Uploaded';
            barangayStatusTextElement.classList.remove('text-red-600');
            barangayStatusTextElement.classList.add('text-green-600');
        }
    } else {
        if (barangayStatusTextElement) {
            barangayStatusTextElement.textContent = 'Not uploaded';
            barangayStatusTextElement.classList.remove('text-green-600');
            barangayStatusTextElement.classList.add('text-red-600');
        }
    }

    // Update SK status + header preview image
    const skLogo = logos.sk;
    const skStatusLogoElement = document.getElementById('preview-status-sk');
    const skStatusTextElement = document.getElementById('preview-sk-status');
    const headerSKLogoElement = document.getElementById('preview-sk-header-logo');
    if (skLogo) {
        if (skStatusLogoElement) {
            skStatusLogoElement.innerHTML = `<img src="<?= base_url() ?>${skLogo.file_path}" class="w-full h-full object-contain">`;
        }
        if (headerSKLogoElement) {
            headerSKLogoElement.innerHTML = `<img src="<?= base_url() ?>${skLogo.file_path}" class="w-full h-full object-contain">`;
        }
        if (skStatusTextElement) {
            skStatusTextElement.textContent = 'Uploaded';
            skStatusTextElement.classList.remove('text-red-600');
            skStatusTextElement.classList.add('text-green-600');
        }
    } else {
        if (skStatusTextElement) {
            skStatusTextElement.textContent = 'Not uploaded';
            skStatusTextElement.classList.remove('text-green-600');
            skStatusTextElement.classList.add('text-red-600');
        }
    }
}

function loadExistingLogos() {
    fetch('<?= base_url('documents/logos') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateCurrentLogoDisplays(data.data); 
            } else {
                console.error('Failed to load logos:', data.message);
            }
        })
        .catch(error => {
            console.error('Error loading logos:', error);
        });
}

function updateCurrentLogoDisplays(logos) {
    // No Iriga section here anymore
    
    // Update Barangay logo display
    const barangayLogo = logos.barangay;
    const currentBarangayContainer = document.getElementById('current-barangay-logo');
    
    if (barangayLogo && currentBarangayContainer) {
        // Replace the placeholder with the actual logo
        currentBarangayContainer.innerHTML = `<img src="<?= base_url() ?>${barangayLogo.file_path}" class="w-full h-full object-contain">`;
    currentBarangayContainer.classList.add('border-solid', 'border-blue-300', 'bg-blue-50');
        currentBarangayContainer.classList.remove('border-dashed', 'border-gray-300');
    } else if (currentBarangayContainer) {
        // Reset to placeholder if no logo
        currentBarangayContainer.innerHTML = `
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        `;
    currentBarangayContainer.classList.remove('border-solid', 'border-blue-300', 'bg-blue-50');
        currentBarangayContainer.classList.add('border-dashed', 'border-gray-300');
    }

    // Update SK logo display
    const skLogo = logos.sk;
    const currentSKContainer = document.getElementById('current-sk-logo');
    
    if (skLogo && currentSKContainer) {
        // Replace the placeholder with the actual logo
        currentSKContainer.innerHTML = `<img src="<?= base_url() ?>${skLogo.file_path}" class="w-full h-full object-contain">`;
    currentSKContainer.classList.add('border-solid', 'border-blue-300', 'bg-blue-50');
        currentSKContainer.classList.remove('border-dashed', 'border-gray-300');
    } else if (currentSKContainer) {
        // Reset to placeholder if no logo
        currentSKContainer.innerHTML = `
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        `;
    currentSKContainer.classList.remove('border-solid', 'border-blue-300', 'bg-blue-50');
        currentSKContainer.classList.add('border-dashed', 'border-gray-300');
    }
}

function uploadLogo(file, logoType) {
    const formData = new FormData();
    formData.append('logo_file', file);
    formData.append('logo_type', logoType);
    
    return fetch('<?= base_url('documents/upload-logo') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        return data;
    });
}

// Logo status check and display
async function checkLogoStatus() {
    try {
        const response = await fetch(`<?= base_url('documents/logos') ?>`);
        const data = await response.json();
        
        if (data.success) {
            updateLogoStatus('barangay-logo-status', data.data.barangay || null);
            updateLogoStatus('sk-logo-status', data.data.sk || null);
        }
    } catch (error) {
        console.error('Error checking logo status:', error);
    }
}

function updateLogoStatus(elementId, logoData) {
    const element = document.getElementById(elementId);
    if (!element) return;
    
    if (logoData && logoData.file_path) {
        element.innerHTML = `
            <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
            <span class="font-medium text-gray-900">Uploaded</span>
        `;
    } else {
        element.innerHTML = `
            <span class="w-2 h-2 bg-red-400 rounded-full mr-2"></span>
            <span class="font-medium text-gray-900">Not uploaded</span>
        `;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing SK logo management...');
    // Ensure placeholders show immediately before async fetch
    const ci = document.getElementById('current-iriga-logo');
    if (ci && !ci.querySelector('img')) {
        ci.innerHTML = `
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>`;
    }
    const cb = document.getElementById('current-barangay-logo');
    if (cb && !cb.querySelector('img')) {
        cb.innerHTML = `
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>`;
    }
    const cs = document.getElementById('current-sk-logo');
    if (cs && !cs.querySelector('img')) {
        cs.innerHTML = `
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>`;
    }
    checkLogoStatus();
    
    // Test if modal exists
    const modal = document.getElementById('logoManagerModal');
    console.log('Logo manager modal found:', modal !== null);
    if (modal) {
        console.log('Modal classes:', modal.className);
        
        // Add click outside to close functionality
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeLogoManagerModal();
            }
        });
    }
    
    // Test if preview modal exists
    const previewModal = document.getElementById('logoPreviewModal');
    if (previewModal) {
        previewModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeLogoPreviewModal();
            }
        });
    }
    
    // Load current logos when page loads
    loadExistingLogos();

    // Setup drag and drop functionality similar to Pederasyon view
    setupDragAndDrop('barangay-dropzone', 'barangayLogo', 'barangay');
    setupDragAndDrop('sk-dropzone', 'skLogo', 'sk');
});

// Setup drag and drop functionality for file upload
function setupDragAndDrop(dropzoneId, inputId, logoType) {
    const dropzone = document.getElementById(dropzoneId);
    const input = document.getElementById(inputId);
    if (!dropzone || !input) return;

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, () => {
            dropzone.classList.add('border-blue-500', 'bg-blue-100');
        }, false);
    });
    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, () => {
            dropzone.classList.remove('border-blue-500', 'bg-blue-100');
        }, false);
    });

    dropzone.addEventListener('drop', (e) => {
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            const file = files[0];
            if (!file.type.startsWith('image/')) {
                showNotification('Please drop an image file (JPEG, PNG)', 'error');
                return;
            }
            if (file.size > 2048000) {
                showNotification('File size must be less than 2MB', 'error');
                return;
            }
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            input.files = dataTransfer.files;
            previewLogo(input, logoType);
        }
    }, false);

    // Make dropzone clickable
    dropzone.addEventListener('click', () => input.click());
}

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
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
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            user_id: currentDeactivateUserId,
            reason: selectedReason.value
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(`User ${currentDeactivateUserName} has been deactivated`, 'success');
            closeDeactivateModal();
        } else {
            showNotification(data.message || 'Error deactivating user', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error deactivating user', 'error');
    });
}

// Close modal when clicking outside
document.getElementById('deactivateUserModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeactivateModal();
});
</script>
