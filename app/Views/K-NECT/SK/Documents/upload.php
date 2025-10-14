
<?php $title = 'Upload Document'; ?>

<div class="max-w-4xl mx-auto mt-6">
    <div class="relative">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-100/60 to-white/80 rounded-2xl blur-xl opacity-80"></div>
        <div class="relative p-6 rounded-2xl shadow-lg border border-blue-200 bg-white/70 backdrop-blur-lg">
            
            <!-- Breadcrumbs -->
            <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-6">
                <a href="<?= base_url('sk/dashboard') ?>" class="hover:text-blue-600 transition-colors flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <a href="<?= base_url('admin/documents') ?>" class="hover:text-blue-600 transition-colors">
                    Documents
                </a>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-blue-600 font-medium">Upload</span>
            </nav>

            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-blue-900 tracking-tight flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        Upload Document
                    </h1>
                    <p class="text-sm text-gray-600 mt-2">Add new documents to the system</p>
                </div>
                <a href="<?= base_url('admin/documents') ?>" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-gray-700 rounded-lg text-sm font-medium shadow-sm hover:bg-gray-50 hover:shadow transition-all border border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Documents
                </a>
            </div>

    <?php if (!empty($errorMsg)): ?>
                <div class="mb-6 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm font-medium"><?= esc($errorMsg) ?></span>
            <button onclick="this.parentElement.remove()" class="ml-auto text-red-700 hover:text-red-900 text-xl">&times;</button>
        </div>
    <?php endif; ?>

            <form id="uploadForm" action="<?= base_url('admin/documents/upload') ?>" method="post" enctype="multipart/form-data" class="space-y-6">
        <?= csrf_field() ?>
                <!-- File Upload Section -->
                <div class="bg-white rounded-lg border border-blue-100 shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-blue-900 mb-4">
                        Document File
                    </h3>
                    <div id="dropzone" class="flex flex-col items-center justify-center border-2 border-dashed border-blue-300 rounded-lg p-8 bg-blue-50/50 cursor-pointer transition-all duration-300 hover:bg-blue-100/50 hover:border-blue-400 focus-within:ring-2 focus-within:ring-blue-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-blue-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <span class="text-blue-700 font-medium text-base text-center">Drag & drop your document here or click to select</span>
                        <span class="text-blue-500 text-sm mt-1">Supports PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX</span>
            <input type="file" name="document" id="fileInput" class="hidden" required />
        </div>
                    <div id="fileName" class="text-gray-600 text-sm mt-3 font-medium"></div>
                </div>

                <!-- Document Details Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Filename -->
                        <div class="bg-white rounded-lg border border-blue-100 shadow-sm p-6">
                            <h3 class="text-lg font-semibold text-blue-900 mb-4 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Document Name <span class="text-red-500">*</span>
                            </h3>
                            <input type="text" name="filename" required class="w-full border border-blue-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-300 focus:border-blue-500 outline-none text-sm bg-white/70" placeholder="Enter a name for this document" value="<?= old('filename') ?>" maxlength="255" />
                            <p id="filenameError" class="mt-2 text-sm text-red-600 hidden"></p>
                            <p class="text-xs text-blue-600 mt-2">This will be the display name for your document</p>
                        </div>
                        
                        <!-- Description -->
                        <div class="bg-white rounded-lg border border-blue-100 shadow-sm p-6">
                            <h3 class="text-lg font-semibold text-blue-900 mb-4 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Description
                            </h3>
                            <textarea name="description" class="w-full border border-blue-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-300 focus:border-blue-500 outline-none text-sm bg-white/70" rows="4" placeholder="Enter a description for this document (optional)"><?= old('description') ?></textarea>
                        </div>

                        <!-- Tags -->
                        <div class="bg-white rounded-lg border border-blue-100 shadow-sm p-6">
                            <h3 class="text-lg font-semibold text-blue-900 mb-4 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                Tags
                            </h3>
                            <input type="text" name="tags" id="tagsInput" class="w-full border border-blue-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-300 focus:border-blue-500 outline-none text-sm bg-white/70" placeholder="Add tags separated by commas..." />
                            <p class="text-xs text-gray-500 mt-2">Press Enter or comma to add a tag. Tags help organize and search documents.</p>
        </div>
        </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Categories -->
                        <div class="bg-white rounded-lg border border-blue-100 shadow-sm p-6">
                            <h3 class="text-lg font-semibold text-blue-900 mb-4 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                Categories
                            </h3>
            <?php if (empty($categories)): ?>
                                <div class="text-gray-500 text-sm italic">No categories available. 
                                    <button onclick="openCategoryModal()" class="text-blue-600 underline hover:text-blue-800">Add categories</button> first.
                                </div>
            <?php else: ?>
                                <div class="grid grid-cols-1 gap-3 max-h-48 overflow-y-auto">
                <?php $oldCats = old('categories') ?? []; ?>
                <?php foreach (($categories ?? []) as $cat): ?>
                                        <label class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-blue-50 transition-colors cursor-pointer">
                                            <input type="checkbox" name="categories[]" value="<?= $cat['id'] ?>" class="form-checkbox text-blue-600 focus:ring-2 focus:ring-blue-400 rounded" <?= in_array($cat['id'], $oldCats) ? 'checked' : '' ?>>
                                            <span class="ml-3 text-sm font-medium text-gray-900"><?= esc($cat['name']) ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

                        <!-- Visibility Settings -->
                        <div class="bg-white rounded-lg border border-blue-100 shadow-sm p-6">
                            <h3 class="text-lg font-semibold text-blue-900 mb-4 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Visibility Settings <span class="text-red-500">*</span>
                            </h3>
                            <div class="space-y-3">
                                <!-- SK Visibility (Default for SK role) -->
                                <div>
                                    <label class="flex items-start p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors cursor-pointer border-2 border-blue-200">
                                        <input type="radio" name="visibility" value="sk" class="form-radio text-blue-600 focus:ring-2 focus:ring-blue-400 mt-0.5" checked required>
                                        <div class="ml-3">
                                            <div class="flex items-center gap-2">
                                                <svg class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                                <span class="text-sm font-semibold text-blue-900">SK (Sangguniang Kabataan)</span>
                                            </div>
                                            <p class="text-xs text-blue-700 mt-1">Visible to SK administrators and KK users</p>
                                        </div>
                                    </label>
                                </div>
                                <!-- KK Visibility -->
                                <div>
                                    <label class="flex items-start p-3 bg-gray-50 rounded-lg hover:bg-green-50 transition-colors cursor-pointer border border-gray-200 hover:border-green-300">
                                        <input type="radio" name="visibility" value="kk" class="form-radio text-green-600 focus:ring-2 focus:ring-green-400 mt-0.5" required>
                                        <div class="ml-3">
                                            <div class="flex items-center gap-2">
                                                <svg class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                <span class="text-sm font-semibold text-gray-900">KK (Katipunan ng Kabataan)</span>
                                            </div>
                                            <p class="text-xs text-gray-600 mt-1">Visible to KK users only</p>
                                        </div>
                                    </label>
                                </div>
                                <!-- Pederasyon Visibility -->
                                <div>
                                    <label class="flex items-start p-3 bg-gray-50 rounded-lg hover:bg-purple-50 transition-colors cursor-pointer border border-gray-200 hover:border-purple-300">
                                        <input type="radio" name="visibility" value="pederasyon" class="form-radio text-purple-600 focus:ring-2 focus:ring-purple-400 mt-0.5" required>
                                        <div class="ml-3">
                                            <div class="flex items-center gap-2">
                                                <svg class="h-4 w-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                                <span class="text-sm font-semibold text-gray-900">Pederasyon (Federation)</span>
                                            </div>
                                            <p class="text-xs text-gray-600 mt-1">Visible to Pederasyon officers only (you can still see your own uploads)</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Barangay Scope Settings (SK users automatically upload to their own barangay) -->
                        <div class="bg-white rounded-lg border border-green-100 shadow-sm p-6">
                            <h3 class="text-lg font-semibold text-green-900 mb-4 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Barangay Assignment
                            </h3>
                            
                            <!-- Hidden inputs for automatic barangay assignment -->
                            <input type="hidden" name="visibility_scope" value="specific_barangay">
                            <input type="hidden" name="barangay_id" value="<?= esc($userBarangayId ?? '') ?>">
                            
                            <!-- Info message -->
                            <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-green-800">
                                            Barangay Restriction
                                        </p>
                                        <p class="text-xs text-green-700 mt-1">
                                            As an SK officer, you can only upload documents to your assigned barangay. This document will be automatically assigned to your barangay.
                                            <?php 
                                            // Get barangay name
                                            $barangayName = 'your barangay';
                                            if (!empty($barangays) && !empty($userBarangayId)) {
                                                foreach ($barangays as $b) {
                                                    if ($b['barangay_id'] == $userBarangayId) {
                                                        $barangayName = $b['name'];
                                                        break;
                                                    }
                                                }
                                            }
                                            ?>
                                            <strong class="font-semibold">Current barangay: <?= esc($barangayName) ?></strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Download Settings -->
                        <div class="bg-white rounded-lg border border-blue-100 shadow-sm p-6">
                            <h3 class="text-lg font-semibold text-blue-900 mb-4 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Download Settings
                            </h3>
                            <label class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-blue-50 transition-colors cursor-pointer">
                                <input type="checkbox" name="downloadable" id="downloadable" value="1" checked class="form-checkbox text-blue-600 focus:ring-2 focus:ring-blue-400 rounded">
                                <div class="ml-3">
                                    <span class="text-sm font-medium text-gray-900">Allow download</span>
                                    <p class="text-xs text-gray-500">Users can download this document</p>
                                </div>
                </label>
            </div>
        </div>
                </div>

                <?php if (session('role') === 'super_admin'): ?>
                <!-- Note for Super Admin -->
                <div class="bg-purple-50 rounded-lg border border-purple-200 p-4">
                    <div class="flex items-start gap-3">
                        <svg class="h-5 w-5 text-purple-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <h4 class="text-sm font-semibold text-purple-900">Super Admin Notice</h4>
                            <p class="text-xs text-purple-700 mt-1">You are uploading as SK role. For full visibility control, use the Pederasyon upload interface.</p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-6 border-t border-blue-100">
                    <div class="text-sm text-gray-500">
                        <span class="font-medium">Note:</span> All fields marked with * are required
                    </div>
                    <div class="flex gap-3">
                        <a href="<?= base_url('admin/documents') ?>" class="bg-white text-gray-700 px-6 py-2.5 rounded-lg text-sm font-medium shadow-sm hover:bg-gray-50 transition-colors flex items-center gap-2 border border-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                Cancel
            </a>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium shadow-sm hover:bg-blue-700 transition-colors flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Upload Document
                        </button>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div id="progressContainer" class="w-full bg-gray-200 rounded-lg h-2 hidden">
                    <div id="progressBar" class="bg-blue-500 h-2 rounded-lg transition-all duration-300" style="width: 0%"></div>
                </div>
            </form>
        </div>
        </div>
</div>
<!-- Toast Notification System -->
<script src="<?= base_url('assets/js/toast-notifications.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css">
<script>
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('fileInput');
    const fileName = document.getElementById('fileName');
    dropzone.addEventListener('click', () => fileInput.click());
    dropzone.addEventListener('dragover', e => { e.preventDefault(); dropzone.classList.add('bg-blue-200'); });
    dropzone.addEventListener('dragleave', e => { e.preventDefault(); dropzone.classList.remove('bg-blue-200'); });
    dropzone.addEventListener('drop', e => { e.preventDefault(); dropzone.classList.remove('bg-blue-200'); fileInput.files = e.dataTransfer.files; fileName.textContent = fileInput.files[0].name; });
    fileInput.addEventListener('change', () => { if (fileInput.files.length > 0) fileName.textContent = fileInput.files[0].name; });

    // Visibility Scope Toggle
    // SK users automatically upload to their barangay - no scope selection needed

    // Progress bar for upload
    document.getElementById('uploadForm').addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent default form submission
        
        const form = this;
        const submitBtn = form.querySelector('button[type="submit"]');
        
        // Validate form before submission
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        
        submitBtn.disabled = true;
        submitBtn.textContent = 'Uploading...';
        const xhr = new XMLHttpRequest();
        xhr.open('POST', form.action, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.upload.onprogress = function(e) { if (e.lengthComputable) { const percent = (e.loaded / e.total) * 100; document.getElementById('progressContainer').classList.remove('hidden'); document.getElementById('progressBar').style.width = percent + '%'; } };
        xhr.onload = function() {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Upload';
            let resp; try { resp = JSON.parse(xhr.responseText); } catch (err) { resp = null; }
            if (resp && resp.success) {
                showSuccessToast('Document uploaded successfully!');
                setTimeout(() => { window.location.href = '<?= base_url('admin/documents') ?>'; }, 1000);
                form.reset(); fileName.textContent=''; document.getElementById('progressBar').style.width='0%'; document.getElementById('progressContainer').classList.add('hidden');
            } else {
                // Clear previous inline errors
                const filenameErrorEl = document.getElementById('filenameError');
                if (filenameErrorEl) { filenameErrorEl.textContent = ''; filenameErrorEl.classList.add('hidden'); }
                // Inline error for filename if provided
                if (resp && resp.errors && resp.errors.filename) {
                    if (filenameErrorEl) { filenameErrorEl.textContent = resp.errors.filename; filenameErrorEl.classList.remove('hidden'); }
                }
                let msg = 'Upload failed. Please check the highlighted field(s).'; if (resp && resp.error) msg = resp.error;
                showErrorToast(msg);
            }
        };
        xhr.onerror = function() { submitBtn.disabled=false; submitBtn.textContent='Upload'; showErrorToast('Upload failed. Please try again.'); };
        const formData = new FormData(form);
        xhr.send(formData);
    });

    // Tagify for tags input
    document.addEventListener('DOMContentLoaded', function() {
        var input = document.querySelector('#tagsInput');
        if (input) {
            var tagify = new Tagify(input, { whitelist: [], dropdown: { enabled: 0, maxItems: 10 } });
        }
    });
</script>
