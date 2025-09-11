
<?php $title = 'Upload Document'; ?>

<div class="max-w-4xl mx-auto mt-6">
    <div class="relative">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-100/60 to-white/80 rounded-2xl blur-xl opacity-80"></div>
        <div class="relative p-6 rounded-2xl shadow-lg border border-blue-200 bg-white/70 backdrop-blur-lg">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-blue-900 tracking-tight flex items-center gap-2 drop-shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4a1 1 0 011-1h8a1 1 0 011 1v12m-4 4h-4a1 1 0 01-1-1v-4h6v4a1 1 0 01-1 1z"/>
                        </svg>
                        Upload Document
                    </h1>
                    <div class="text-sm text-blue-700 mt-1 font-medium opacity-80">Add new documents to the system</div>
                </div>
                <a href="<?= base_url('admin/documents') ?>" class="bg-white text-gray-700 px-4 py-2 rounded-lg text-sm font-medium shadow-sm hover:bg-gray-50 transition-colors flex items-center gap-2 border border-gray-200">
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
                    <h3 class="text-lg font-semibold text-blue-900 mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4a1 1 0 011-1h8a1 1 0 011 1v12m-4 4h-4a1 1 0 01-1-1v-4h6v4a1 1 0 01-1 1z"/>
                        </svg>
                        Document File
                    </h3>
                    <div id="dropzone" class="flex flex-col items-center justify-center border-2 border-dashed border-blue-300 rounded-lg p-8 bg-blue-50/50 cursor-pointer transition-all duration-300 hover:bg-blue-100/50 hover:border-blue-400 focus-within:ring-2 focus-within:ring-blue-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4a1 1 0 011-1h8a1 1 0 011 1v12m-4 4h-4a1 1 0 01-1-1v-4h6v4a1 1 0 01-1 1z"/>
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
                        <!-- Title -->
                        <div class="bg-white rounded-lg border border-blue-100 shadow-sm p-6">
                            <h3 class="text-lg font-semibold text-blue-900 mb-4 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6M4 6h16"/>
                                </svg>
                                Title
                            </h3>
                            <input type="text" name="title" class="w-full border border-blue-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-300 focus:border-blue-500 outline-none text-sm bg-white/70" placeholder="Enter a document title (optional)" value="<?= old('title') ?>" />
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
                                Visibility Settings
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-blue-50 transition-colors cursor-pointer">
                                        <input type="radio" name="visibility" value="SK" class="form-radio text-blue-600 focus:ring-2 focus:ring-blue-400" checked>
                                        <div class="ml-3">
                                            <span class="text-sm font-medium text-gray-900">SK Admins</span>
                                            <p class="text-xs text-gray-500">Visible to SK administrators only</p>
                                        </div>
                                    </label>
                                </div>
                                <div>
                                    <label class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-blue-50 transition-colors cursor-pointer">
                                        <input type="radio" name="visibility" value="KK" class="form-radio text-blue-600 focus:ring-2 focus:ring-blue-400">
                                        <div class="ml-3">
                                            <span class="text-sm font-medium text-gray-900">KK / Viewer</span>
                                            <p class="text-xs text-gray-500">Visible to KK users and viewers</p>
                                        </div>
                                    </label>
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
                <!-- Barangay-specific visibility (Super Admin only) -->
                <div class="bg-white rounded-lg border border-blue-100 shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-blue-900 mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Barangay Restrictions (Optional)
                    </h3>
                    <label class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-blue-50 transition-colors cursor-pointer">
                        <input type="checkbox" id="toggleBarangay" class="form-checkbox text-blue-600 focus:ring-2 focus:ring-blue-400 rounded">
                        <div class="ml-3">
                            <span class="text-sm font-medium text-gray-900">Limit visibility to selected barangays</span>
                            <p class="text-xs text-gray-500">Restrict document access to specific barangays</p>
                        </div>
                    </label>
                    <input type="hidden" name="barangays_serialized" id="barangaysSerialized" />
                    <div id="barangayList" class="mt-4 hidden">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 max-h-64 overflow-y-auto p-4 border border-blue-200 rounded-lg bg-blue-50/30">
                            <?php
                                $barangays = [
                                    'Antipolo','Cristo Rey','Del Rosario (Banao)','Francia','La Anunciacion','La Medalla','La Purisima','La Trinidad','Niño Jesus','Perpetual Help','Sagrada','Salvacion','San Agustin','San Andres','San Antonio','San Francisco (Pob.)','San Isidro','San Jose','San Juan','San Miguel','San Nicolas','San Pedro','San Rafael','San Ramon','San Roque (Pob.)','San Vicente Norte','San Vicente Sur','Santa Cruz Norte','Santa Cruz Sur','Santa Elena','Santa Isabel','Santa Maria','Santa Teresita','Santiago','Santo Domingo','Santo Niño'
                                ];
                                $oldBrgys = old('barangays') ?? [];
                            ?>
                            <?php foreach ($barangays as $b): ?>
                                <label class="flex items-center p-2 bg-white rounded-lg border border-blue-100 hover:border-blue-200 transition-colors cursor-pointer">
                                    <input type="checkbox" name="barangays[]" value="<?= esc($b) ?>" class="form-checkbox text-blue-600 focus:ring-2 focus:ring-blue-400 rounded" <?= in_array($b, $oldBrgys) ? 'checked' : '' ?>>
                                    <span class="ml-2 text-sm text-gray-700"><?= esc($b) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">If none are selected, the document will be visible to the chosen role above.</p>
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
<!-- SweetAlert2 for modal dialogs -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    // Barangay toggle (super admin only)
    const toggleBarangay = document.getElementById('toggleBarangay');
    const barangayList = document.getElementById('barangayList');
    const barangaysSerialized = document.getElementById('barangaysSerialized');
    if (toggleBarangay) {
        toggleBarangay.addEventListener('change', () => {
            barangayList.classList.toggle('hidden', !toggleBarangay.checked);
        });
        // Keep a serialized list if needed server-side later
        document.getElementById('uploadForm').addEventListener('change', () => {
            const selected = Array.from(document.querySelectorAll('input[name="barangays[]"]:checked')).map(i => i.value);
            barangaysSerialized.value = selected.join(',');
        });
    }

    // Progress bar for upload
    document.getElementById('uploadForm').addEventListener('submit', function(e) {
        const form = this;
        const submitBtn = form.querySelector('button[type="submit"]');
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
                Swal.fire({ title: 'Success', text: 'Document uploaded successfully!', icon: 'success', confirmButtonColor: '#2563eb' }).then(() => { window.location.href = '<?= base_url('admin/documents') ?>'; });
                form.reset(); fileName.textContent=''; document.getElementById('progressBar').style.width='0%'; document.getElementById('progressContainer').classList.add('hidden');
            } else {
                let msg = 'Upload failed. Please try again.'; if (resp && resp.error) msg = resp.error;
                Swal.fire({ title: 'Error', text: msg, icon: 'error', confirmButtonColor: '#d33' });
            }
        };
        xhr.onerror = function() { submitBtn.disabled=false; submitBtn.textContent='Upload'; Swal.fire({ title: 'Error', text: 'Upload failed. Please try again.', icon: 'error', confirmButtonColor: '#d33' }); };
        const formData = new FormData(form);
        xhr.send(formData);
        e.preventDefault();
    });

    // Tagify for tags input
    document.addEventListener('DOMContentLoaded', function() {
        var input = document.querySelector('#tagsInput');
        if (input) {
            var tagify = new Tagify(input, { whitelist: [], dropdown: { enabled: 0, maxItems: 10 } });
        }
    });
</script>
 
