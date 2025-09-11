<!-- ===== MAIN CONTENT AREA ===== -->
<!-- Content area positioned to the right of sidebar -->
<div class="flex-1 flex flex-col min-h-0 ml-64 pt-16">
    <!-- Main Content Container -->
    <main class="flex-1 overflow-auto p-6 bg-gray-50">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">System Settings</h1>
            <p class="text-gray-600 mt-1">Manage system configurations and document formats</p>
        </div>

        <!-- Settings Cards Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            <!-- Account Settings Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
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
                        <a href="<?= base_url('pederasyon/account-settings') ?>" class="w-full bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-2 px-3 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2">
                            Manage Account
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Logo Management Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
                <div class="p-6">
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
                            <span class="text-gray-600">Municipality Logo</span>
                            <span id="iriga-logo-status" class="inline-flex items-center">
                                <span class="w-2 h-2 bg-red-400 rounded-full mr-2"></span>
                                <span class="font-medium text-gray-900">Not uploaded</span>
                            </span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600">Pederasyon Logo</span>
                            <span id="pederasyon-logo-status" class="inline-flex items-center">
                                <span class="w-2 h-2 bg-red-400 rounded-full mr-2"></span>
                                <span class="font-medium text-gray-900">Not uploaded</span>
                            </span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600">Municipality Management</span>
                            <span class="inline-flex items-center">
                                <span class="w-2 h-2 bg-blue-400 rounded-full mr-2"></span>
                                <span class="font-medium text-gray-900">Available</span>
                            </span>
                        </div>
                    </div>
                    
                    <!-- Card Actions -->
                    <div class="flex space-x-2">
                        <button onclick="console.log('Button clicked!'); openLogoManagerModal();" class="flex-1 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium py-2 px-3 rounded-lg transition-colors duration-200">
                            Manage Logos
                        </button>
                        <button onclick="openLogoPreviewModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium py-2 px-3 rounded-lg transition-colors duration-200">
                            Preview
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
            <!-- Pederasyon-specific layout - Two columns for Municipality and Pederasyon Logos -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <!-- Municipality Logo (Pederasyon Only - Municipality Management) -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-lg p-6">
                    <div class="text-center mb-6">
                        <h4 class="text-xl font-semibold text-gray-900 mb-2">Municipality Logo</h4>
                        <p class="text-sm text-blue-600 mb-1">Municipality Management</p>
                        <p class="text-xs text-gray-500">This logo will be used across all barangays</p>
                    </div>
                    
                    <!-- Current Logo Preview -->
                    <div class="mb-6">
                        <p class="text-sm font-medium text-gray-700 mb-3 text-center">Current Municipality Logo:</p>
                        <div id="current-iriga-logo" class="w-32 h-32 mx-auto bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg overflow-hidden flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Upload New Logo -->
                    <div id="iriga-dropzone" class="bg-white border-2 border-dashed border-blue-300 rounded-lg p-6 hover:border-blue-400 hover:bg-blue-50 transition-all duration-200 cursor-pointer">
                        <input type="file" id="irigaLogo" accept="image/jpeg,image/jpg,image/png" onchange="previewLogo(this, 'iriga')" class="hidden">
                        <div class="flex flex-col items-center text-center select-none pointer-events-none">
                            <svg class="w-10 h-10 text-blue-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <p class="text-sm font-medium text-blue-700">Drag & drop your logo here or click to select</p>
                            <p class="text-xs text-blue-600 mt-1">JPEG, PNG • Max 2MB • Applied to all barangays</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button onclick="uploadSingleLogo('iriga')" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition-colors duration-200">
                            Upload Municipality Logo
                        </button>
                    </div>
                </div>

                <!-- Pederasyon Logo (Organization Logo) -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 border-2 border-blue-200 rounded-lg p-6">
                    <div class="text-center mb-6">
                        <h4 class="text-xl font-semibold text-gray-900 mb-2">Pederasyon Logo</h4>
                        <p class="text-sm text-blue-600 mb-1">Organization Logo</p>
                        <p class="text-xs text-gray-500">Official Pederasyon organization logo</p>
                    </div>
                    
                    <!-- Current Logo Preview -->
                    <div class="mb-6">
                        <p class="text-sm font-medium text-gray-700 mb-3 text-center">Current Pederasyon Logo:</p>
                        <div id="current-pederasyon-logo" class="w-32 h-32 mx-auto bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg overflow-hidden flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Upload New Logo -->
                    <div id="pederasyon-dropzone" class="bg-white border-2 border-dashed border-blue-300 rounded-lg p-6 hover:border-blue-400 hover:bg-blue-50 transition-all duration-200 cursor-pointer">
                        <input type="file" id="pederasyonLogo" accept="image/jpeg,image/jpg,image/png" onchange="previewLogo(this, 'pederasyon')" class="hidden">
                        <div class="flex flex-col items-center text-center select-none pointer-events-none">
                            <svg class="w-10 h-10 text-blue-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <p class="text-sm font-medium text-blue-700">Drag & drop your logo here or click to select</p>
                            <p class="text-xs text-blue-600 mt-1">JPEG, PNG • Max 2MB • Organization identity</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button onclick="uploadSingleLogo('pederasyon')" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg text-sm font-medium transition-colors duration-200">
                            Upload Organization Logo
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-between p-4 border-t">
            <div class="text-xs text-gray-500">
                Upload Municipality logo (Municipality) and Pederasyon organization logo individually or use "Upload All" to process both files.
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
                        <!-- Pederasyon Logo -->
                        <div class="text-center">
                            <div id="preview-pederasyon-logo" class="w-20 h-20 mx-auto mb-2 rounded flex items-center justify-center">
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
                            <h4 class="text-xs font-medium text-gray-700">PANLUNGSOD NA PEDERASYON NG MGA </h4>
                             <h4 class="text-xs font-medium text-gray-700">SANGGUNIANG KABATAAN NG IRIGA</h4>
                        </div>
                        <!-- Municipality Logo -->
                        <div class="text-center">
                            <div id="preview-iriga-logo" class="w-20 h-20 mx-auto mb-2 rounded flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="border-gray-300 mb-4">
                    
                    <div class="text-center">
                        <h4 class="text-base font-bold text-gray-900">PANLUNGSOD NA PEDERASYON NG MGA KABATAAN</h4>
                        <p class="text-sm text-gray-600 mt-2">Sample document content here...</p>
                    </div>
                </div>
            </div>
            
            <!-- Logo Status Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <div id="preview-status-pederasyon" class="w-16 h-16 mx-auto mb-2 border border-gray-300 rounded flex items-center justify-center bg-gray-100">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h5 class="font-medium text-gray-900">Pederasyon Logo</h5>
                    <p id="preview-pederasyon-status" class="text-sm text-red-600">Not uploaded</p>
                    <p class="text-xs text-gray-500 mt-1">Organization identity logo</p>
                </div>
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <div id="preview-status-iriga" class="w-16 h-16 mx-auto mb-2 border border-gray-300 rounded flex items-center justify-center bg-gray-100">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h5 class="font-medium text-gray-900">Municipality Logo</h5>
                    <p id="preview-iriga-status" class="text-sm text-red-600">Not uploaded</p>
                    <p class="text-xs text-gray-500 mt-1">Applied to all barangay documents</p>
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

<script>
// Toast notification function from youth_profile.php
function showNotification(message, type = 'info') {
    // Create a single container to stack toasts (prevents overlap)
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'fixed top-4 right-4 z-[100000] flex flex-col items-end space-y-2 pointer-events-none';
        document.body.appendChild(container);
    }

    const notification = document.createElement('div');
    // Within the container, each toast is relative and stackable
    notification.className = 'pointer-events-auto w-80 max-w-sm p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full';

    // Color by type
    switch (type) {
        case 'success':
            notification.className += ' bg-green-500 text-white';
            break;
        case 'error':
            notification.className += ' bg-red-500 text-white';
            break;
        default:
            notification.className += ' bg-blue-500 text-white';
    }

    // Icon by type
    let icon = '';
    switch (type) {
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
            <button class="ml-2 text-white hover:text-gray-200 focus:outline-none" aria-label="Close notification">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    `;

    // Wire up close button
    notification.querySelector('button')?.addEventListener('click', () => {
        notification.classList.add('translate-x-full');
        setTimeout(() => notification.remove(), 300);
    });

    // Append to container so toasts stack
    container.appendChild(notification);

    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 50);

    // Auto-remove after 5 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            if (notification.parentElement) notification.remove();
        }, 300);
    }, 5000);
}

// Reusable placeholder for current logo boxes when no logo is set
function getLogoPlaceholder() {
    return `
        <div class="w-full h-full flex items-center justify-center bg-gray-100">
            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
    `;
}

function openLogoManagerModal() {
    console.log('Opening logo manager modal...'); // Debug log
    const modal = document.getElementById('logoManagerModal');
    console.log('Modal element:', modal); // Debug log
    if (modal) {
        modal.classList.remove('hidden');
        modal.style.display = 'block'; // Fallback method
        console.log('Modal classes after opening:', modal.className); // Debug log
        // Initialize default placeholders so UI looks right instantly
        const ci = document.getElementById('current-iriga-logo');
        if (ci && !ci.querySelector('img')) ci.innerHTML = getLogoPlaceholder();
        const cp = document.getElementById('current-pederasyon-logo');
        if (cp && !cp.querySelector('img')) cp.innerHTML = getLogoPlaceholder();
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
    } else {
        loadExistingLogos();
    }
}

function uploadSingleLogo(logoType) {
    const fileInput = document.getElementById(`${logoType}Logo`);
    const file = fileInput.files[0];
    
    if (!file) {
        showNotification(`Please select an ${logoType} logo file first.`, 'error');
        return;
    }
    
    uploadLogo(file, logoType)
        .then(result => {
            if (result.success) {
                showNotification(`${logoType.charAt(0).toUpperCase() + logoType.slice(1)} logo uploaded successfully! This will now appear on all barangay documents.`, 'success');
                // Clear the file input
                fileInput.value = '';
                // Reload existing logos to show the updated one with a small delay
                setTimeout(() => {
                    loadExistingLogos();
                    // Update logo status in the main page
                    updateLogoStatus();
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
    
    // Check for Municipality logo
    const irigaFile = document.getElementById('irigaLogo').files[0];
    if (irigaFile) uploads.push(uploadLogo(irigaFile, 'iriga'));
    
    // Check for Pederasyon logo
    const pederasyonFile = document.getElementById('pederasyonLogo').files[0];
    if (pederasyonFile) uploads.push(uploadLogo(pederasyonFile, 'pederasyon'));
    
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
            document.getElementById('irigaLogo').value = '';
            document.getElementById('pederasyonLogo').value = '';
            
            // Reload existing logos to show the updated ones
            setTimeout(() => {
                loadExistingLogos();
                // Update logo status in the main page
                updateLogoStatus();
            }, 500);
        })
        .catch(error => {
            console.error('Error uploading logos:', error);
            showNotification('An error occurred while uploading logos.', 'error');
        });
}

function updateLogoStatus() {
    // Load current logo status and update the main page
    fetch('<?= base_url('documents/logos') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const logos = data.data;
                
                // Update Municipality logo status
                const irigaLogo = logos.iriga_city;
                const irigaStatusElement = document.getElementById('iriga-logo-status');
                if (irigaLogo && irigaStatusElement) {
                    irigaStatusElement.innerHTML = `
                        <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                        <span class="font-medium text-gray-900">Uploaded</span>
                    `;
                }
                
                // Update Pederasyon logo status
                const pederasyonLogo = logos.pederasyon;
                const pederasyonStatusElement = document.getElementById('pederasyon-logo-status');
                if (pederasyonLogo && pederasyonStatusElement) {
                    pederasyonStatusElement.innerHTML = `
                        <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                        <span class="font-medium text-gray-900">Uploaded</span>
                    `;
                }
            }
        })
        .catch(error => {
            console.error('Error loading logo status:', error);
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
    // Update Municipality logo
    const irigaLogo = logos.iriga_city;
    
    // Update document header preview - Iriga logo
    const headerIrigaLogoElement = document.getElementById('preview-iriga-logo');
    if (irigaLogo && headerIrigaLogoElement) {
        headerIrigaLogoElement.innerHTML = `<img src="<?= base_url() ?>${irigaLogo.file_path}" class="w-full h-full object-contain">`;
    }
    
    // Update Pederasyon logo in document header preview
    const pederasyonLogo = logos.pederasyon;
    const headerPederasyonLogoElement = document.getElementById('preview-pederasyon-logo');
    if (pederasyonLogo && headerPederasyonLogoElement) {
        headerPederasyonLogoElement.innerHTML = `<img src="<?= base_url() ?>${pederasyonLogo.file_path}" class="w-full h-full object-contain">`;
    }
    
    // Update Iriga status
    const irigaStatusLogoElement = document.getElementById('preview-status-iriga');
    const irigaStatusTextElement = document.getElementById('preview-iriga-status');
    
    if (irigaLogo) {
        if (irigaStatusLogoElement) {
            irigaStatusLogoElement.innerHTML = `<img src="<?= base_url() ?>${irigaLogo.file_path}" class="w-full h-full object-contain">`;
        }
        if (irigaStatusTextElement) {
            irigaStatusTextElement.textContent = 'Uploaded';
            irigaStatusTextElement.classList.remove('text-red-600');
            irigaStatusTextElement.classList.add('text-green-600');
        }
    } else {
        if (irigaStatusTextElement) {
            irigaStatusTextElement.textContent = 'Not uploaded';
            irigaStatusTextElement.classList.remove('text-green-600');
            irigaStatusTextElement.classList.add('text-red-600');
        }
    }
    
    // Update Pederasyon status
    const pederasyonStatusLogoElement = document.getElementById('preview-status-pederasyon');
    const pederasyonStatusTextElement = document.getElementById('preview-pederasyon-status');
    
    if (pederasyonLogo) {
        if (pederasyonStatusLogoElement) {
            pederasyonStatusLogoElement.innerHTML = `<img src="<?= base_url() ?>${pederasyonLogo.file_path}" class="w-full h-full object-contain">`;
        }
        if (pederasyonStatusTextElement) {
            pederasyonStatusTextElement.textContent = 'Uploaded';
            pederasyonStatusTextElement.classList.remove('text-red-600');
            pederasyonStatusTextElement.classList.add('text-green-600');
        }
    } else {
        if (pederasyonStatusTextElement) {
            pederasyonStatusTextElement.textContent = 'Not uploaded';
            pederasyonStatusTextElement.classList.remove('text-green-600');
            pederasyonStatusTextElement.classList.add('text-red-600');
        }
    }
}

function loadExistingLogos() {
    fetch('<?= base_url('documents/logos') ?>')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateCurrentLogoDisplays(data.data); // Use organized data for current logo sections
            } else {
                console.error('Failed to load logos:', data.message);
            }
        })
        .catch(error => {
            console.error('Error loading logos:', error);
        });
}

function updateCurrentLogoDisplays(logos) {
    // Update Municipality logo display
    const irigaLogo = logos.iriga_city;
    const currentIrigaContainer = document.getElementById('current-iriga-logo');
    
    if (irigaLogo && currentIrigaContainer) {
        currentIrigaContainer.innerHTML = `<img src="<?= base_url() ?>${irigaLogo.file_path}" class="w-full h-full object-cover">`;
        currentIrigaContainer.classList.add('border-solid', 'border-green-300', 'bg-green-50');
        currentIrigaContainer.classList.remove('border-dashed', 'border-gray-300');
    } else if (currentIrigaContainer) {
        currentIrigaContainer.innerHTML = getLogoPlaceholder();
        currentIrigaContainer.classList.remove('border-solid', 'border-green-300', 'bg-green-50');
        currentIrigaContainer.classList.add('border-dashed', 'border-gray-300');
    }
    
    // Update Pederasyon logo display
    const pederasyonLogo = logos.pederasyon;
    const currentPederasyonContainer = document.getElementById('current-pederasyon-logo');
    
    if (pederasyonLogo && currentPederasyonContainer) {
        currentPederasyonContainer.innerHTML = `<img src="<?= base_url() ?>${pederasyonLogo.file_path}" class="w-full h-full object-cover">`;
        currentPederasyonContainer.classList.add('border-solid', 'border-green-300', 'bg-green-50');
        currentPederasyonContainer.classList.remove('border-dashed', 'border-gray-300');
    } else if (currentPederasyonContainer) {
        currentPederasyonContainer.innerHTML = getLogoPlaceholder();
        currentPederasyonContainer.classList.remove('border-solid', 'border-green-300', 'bg-green-50');
        currentPederasyonContainer.classList.add('border-dashed', 'border-gray-300');
    }
}

// Update the page when it loads to show current logo status
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing Pederasyon logo management...');
    
    // Initialize placeholders immediately so default looks right before fetch completes
    const currentIriga = document.getElementById('current-iriga-logo');
    if (currentIriga) {
        currentIriga.innerHTML = getLogoPlaceholder();
        currentIriga.classList.add('border-dashed', 'border-gray-300');
    }
    const currentPederasyon = document.getElementById('current-pederasyon-logo');
    if (currentPederasyon) {
        currentPederasyon.innerHTML = getLogoPlaceholder();
        currentPederasyon.classList.add('border-dashed', 'border-gray-300');
    }

    updateLogoStatus();
    
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
    
    // Setup drag and drop functionality
    setupDragAndDrop('iriga-dropzone', 'irigaLogo', 'iriga');
    setupDragAndDrop('pederasyon-dropzone', 'pederasyonLogo', 'pederasyon');
});

function uploadLogo(file, logoType) {
    const formData = new FormData();
    formData.append('logo_file', file);
    formData.append('logo_type', logoType);
    
    return fetch('<?= base_url('pederasyon/upload-logo') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        return data;
    });
}

// Setup drag and drop functionality for file upload
function setupDragAndDrop(dropzoneId, inputId, logoType) {
    const dropzone = document.getElementById(dropzoneId);
    const input = document.getElementById(inputId);
    
    if (!dropzone || !input) return;
    
    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });
    
    // Highlight drop area when item is dragged over it
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
    
    // Handle dropped files
    dropzone.addEventListener('drop', (e) => {
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            const file = files[0];
            
            // Validate file type
            if (!file.type.startsWith('image/')) {
                showNotification('Please drop an image file (JPEG, PNG)', 'error');
                return;
            }
            
            // Validate file size (2MB = 2048000 bytes)
            if (file.size > 2048000) {
                showNotification('File size must be less than 2MB', 'error');
                return;
            }
            
            // Create a new FileList with the dropped file
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            input.files = dataTransfer.files;
            
            // Trigger preview
            previewLogo(input, logoType);
        }
    }, false);
    
    // Make dropzone clickable
    dropzone.addEventListener('click', () => {
        input.click();
    });
}

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}
</script>


