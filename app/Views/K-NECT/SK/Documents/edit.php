
<?php $title = 'Edit Document'; ?>

<div class="max-w-4xl mx-auto p-6 mt-8">
    <!-- Beautiful Animated Background -->
    <div class="relative">
        <div class="absolute inset-0 bg-gradient-to-br from-green-100/60 to-emerald-100/80 rounded-3xl blur-xl opacity-80"></div>
        <div class="relative bg-white/95 backdrop-blur-lg rounded-3xl shadow-2xl border border-green-200/50 overflow-hidden">
            
            <!-- Breadcrumbs -->
            <div class="px-8 pt-6">
                <nav class="flex items-center space-x-2 text-sm text-gray-600">
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
                    <span class="text-green-600 font-medium">Edit</span>
                </nav>
            </div>

            <!-- Header Section -->
            <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-8 py-6 mt-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white drop-shadow-sm">Edit Document</h1>
                        <p class="text-green-100 text-sm mt-1">Update document information and manage its properties</p>
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="p-8">
                <!-- Error Message -->
                <?php if (!empty($errorMsg) || session()->getFlashdata('error')): ?>
                    <div class="mb-6 bg-red-50 border-l-4 border-red-400 rounded-lg shadow-sm animate-fade-in">
                        <div class="flex items-center p-4">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-red-800 font-medium"><?= esc($errorMsg ?: session()->getFlashdata('error')) ?></p>
                            </div>
                            <button onclick="this.parentElement.parentElement.parentElement.remove()" class="text-red-400 hover:text-red-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Document Info Card -->
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-6 mb-8">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-green-900 mb-2">Document Information</h3>
                            <p class="text-sm text-green-800 mb-3">Update the details below to modify how this document appears and behaves in the system</p>
                            <div class="flex items-center gap-4 text-sm">
                                <div class="flex items-center gap-1 text-green-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <span>File: <?= esc($doc['filename']) ?></span>
                                </div>
                                <div class="flex items-center gap-1 text-green-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Last Modified: <?php 
                                        $modifiedDate = $doc['updated_at'] ?? ($doc['created_at'] ?? null);
                                        echo $modifiedDate ? date('M j, Y', strtotime($modifiedDate)) : 'Unknown';
                                    ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <form action="<?= base_url('admin/documents/edit/' . $doc['id']) ?>" method="post" class="space-y-8" id="editDocumentForm">
                    <?= csrf_field() ?>
                    
                    <!-- Two Column Layout -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <!-- Filename (Editable) -->
                            <div class="space-y-3">
                                <label class="block text-sm font-semibold text-gray-700 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Filename <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" 
                                           name="filename"
                                           id="filename"
                                           required
                                           value="<?= esc($doc['filename']) ?>" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all duration-200 text-gray-900 placeholder-gray-400 shadow-sm hover:shadow-md" 
                                           placeholder="Enter document filename"
                                           maxlength="255" />
                                    <div class="absolute right-3 top-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                </div>
                                <p id="filenameError" class="mt-2 text-sm text-red-600 hidden"></p>
                                <p class="text-xs text-gray-500 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Provide a descriptive filename for easy identification
                                </p>
                            </div>

                            <!-- Description -->
                            <div class="space-y-3">
                                <label class="block text-sm font-semibold text-gray-700 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                                    </svg>
                                    Description
                                </label>
                                <div class="relative">
                                    <textarea name="description" 
                                              id="description"
                                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all duration-200 text-gray-900 placeholder-gray-400 shadow-sm hover:shadow-md resize-none" 
                                              rows="4" 
                                              maxlength="500"
                                              placeholder="Provide a detailed description of the document content..."><?= esc($doc['description'] ?? '') ?></textarea>
                                    <div class="absolute bottom-3 right-3 text-xs text-gray-400" id="descCounter">0/500</div>
                                </div>
                                <p class="text-xs text-gray-500 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Help users understand what this document contains
                                </p>
                            </div>

                            <!-- Download Settings -->
                            <div class="space-y-4">
                                <label class="block text-sm font-semibold text-gray-700 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Download Permissions
                                </label>
                                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox" 
                                               name="downloadable" 
                                               id="downloadable" 
                                               value="1" 
                                               <?= (isset($doc['downloadable']) && $doc['downloadable']) ? 'checked' : '' ?> 
                                               class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500 focus:ring-2">
                                        <div class="ml-3">
                                            <span class="text-sm font-medium text-gray-900">Allow document downloads</span>
                                            <p class="text-xs text-gray-500 mt-1">Users will be able to download this document to their device</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <!-- Categories -->
                            <div class="space-y-4">
                                <label class="block text-sm font-semibold text-gray-700 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a1.994 1.994 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    Categories
                                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">
                                        <?= count($selectedCats) ?> selected
                                    </span>
                                </label>
                                
                                <?php if (empty($categories)): ?>
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                                        <div class="flex items-center gap-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                            </svg>
                                            <div>
                                                <p class="text-sm font-medium text-yellow-800">No categories available</p>
                                                <p class="text-xs text-yellow-700 mt-1">
                                                    <a href='<?= base_url('admin/categories') ?>' class='underline hover:no-underline'>Create categories</a> to organize your documents
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 max-h-64 overflow-y-auto">
                                        <div class="grid grid-cols-1 gap-3">
                                            <?php foreach (($categories ?? []) as $cat): ?>
                                                <label class="flex items-center p-3 bg-white rounded-lg border border-gray-200 hover:border-green-300 hover:bg-green-50 transition-all duration-200 cursor-pointer">
                                                    <input type="checkbox" 
                                                           name="categories[]" 
                                                           value="<?= $cat['id'] ?>" 
                                                           class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500 focus:ring-2" 
                                                           <?= in_array($cat['id'], $selectedCats) ? 'checked' : '' ?>>
                                                    <div class="ml-3 flex-1">
                                                        <div class="flex items-center gap-2">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a1.994 1.994 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                                            </svg>
                                                            <span class="text-sm font-medium text-gray-900"><?= esc($cat['name']) ?></span>
                                                        </div>
                                                    </div>
                                                </label>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Select one or more categories to organize this document
                                    </p>
                                <?php endif; ?>
                            </div>

                            <!-- Tags -->
                            <div class="space-y-3">
                                <label class="block text-sm font-semibold text-gray-700 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a1.994 1.994 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    Tags
                                    <span class="text-xs text-gray-400">(Press Enter or comma to add)</span>
                                </label>
                                <div class="relative">
                                    <input type="text" 
                                           name="tags" 
                                           id="tagsInput" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all duration-200 text-gray-900 placeholder-gray-400 shadow-sm hover:shadow-md" 
                                           placeholder="Add tags to help with search and organization..." 
                                           value="<?= esc($doc['tags'] ?? '') ?>" />
                                </div>
                                <p class="text-xs text-gray-500 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Tags make your document easier to find with search
                                </p>
                            </div>

                            <!-- Visibility Settings -->
                            <div class="space-y-3">
                                <label class="block text-sm font-semibold text-gray-700 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Visibility Settings <span class="text-red-500">*</span>
                                </label>
                                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 space-y-3">
                                    <!-- SK Option (Default for SK role) -->
                                    <label class="flex items-start p-3 bg-white rounded-lg border-2 <?= (($doc['visibility'] ?? 'sk') === 'sk') ? 'border-blue-400 bg-blue-50' : 'border-gray-200' ?> hover:border-blue-300 transition-all cursor-pointer">
                                        <input type="radio" name="visibility" value="sk" class="mt-0.5 w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" <?= (($doc['visibility'] ?? 'sk') === 'sk') ? 'checked' : '' ?> required>
                                        <div class="ml-3">
                                            <div class="flex items-center gap-2">
                                                <svg class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                                <span class="text-sm font-semibold text-gray-900">SK (Sangguniang Kabataan)</span>
                                            </div>
                                            <p class="text-xs text-gray-600 mt-1">Visible to SK administrators and KK users</p>
                                        </div>
                                    </label>
                                    <!-- KK Option -->
                                    <label class="flex items-start p-3 bg-white rounded-lg border-2 <?= (($doc['visibility'] ?? '') === 'kk') ? 'border-green-400 bg-green-50' : 'border-gray-200' ?> hover:border-green-300 transition-all cursor-pointer">
                                        <input type="radio" name="visibility" value="kk" class="mt-0.5 w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500" <?= (($doc['visibility'] ?? '') === 'kk') ? 'checked' : '' ?> required>
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
                                    <!-- Pederasyon Option -->
                                    <label class="flex items-start p-3 bg-white rounded-lg border-2 <?= (($doc['visibility'] ?? '') === 'pederasyon') ? 'border-purple-400 bg-purple-50' : 'border-gray-200' ?> hover:border-purple-300 transition-all cursor-pointer">
                                        <input type="radio" name="visibility" value="pederasyon" class="mt-0.5 w-4 h-4 text-purple-600 border-gray-300 focus:ring-purple-500" <?= (($doc['visibility'] ?? '') === 'pederasyon') ? 'checked' : '' ?> required>
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

                            <!-- Barangay Scope Settings (SK users locked to their barangay) -->
                            <div class="space-y-3">
                                <label class="block text-sm font-semibold text-gray-700 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Barangay Assignment
                                </label>
                                
                                <!-- Hidden inputs - SK users automatically use their barangay -->
                                <input type="hidden" name="visibility_scope" value="specific_barangay">
                                <input type="hidden" name="barangay_id" value="<?= esc($userBarangayId ?? $doc['barangay_id'] ?? '') ?>">
                                
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
                                                As an SK officer, documents are automatically assigned to your barangay and cannot be changed.
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
                                                <strong class="font-semibold">Assigned barangay: <?= esc($barangayName) ?></strong>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                        <button type="submit" class="flex-1 bg-gradient-to-r from-green-600 to-emerald-600 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200 flex items-center justify-center gap-3 transform hover:scale-105 focus:ring-4 focus:ring-green-200 outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Update Document</span>
                        </button>
                        <a href="<?= base_url('admin/documents') ?>" class="flex-1 bg-gray-100 text-gray-700 px-6 py-3 rounded-xl font-semibold shadow-md hover:bg-gray-200 transition-all duration-200 flex items-center justify-center gap-3 transform hover:scale-105 border border-gray-200 hover:border-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span>Cancel Changes</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tagify CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css">
<script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>

<!-- Enhanced JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Description character counter
    const descTextarea = document.getElementById('description');
    const descCounter = document.getElementById('descCounter');
    
    function updateDescCounter() {
        const length = descTextarea.value.length;
        descCounter.textContent = `${length}/500`;
        
        if (length > 450) {
            descCounter.className = 'absolute bottom-3 right-3 text-xs text-red-500 font-medium';
        } else if (length > 400) {
            descCounter.className = 'absolute bottom-3 right-3 text-xs text-yellow-500 font-medium';
        } else {
            descCounter.className = 'absolute bottom-3 right-3 text-xs text-gray-400';
        }
    }
    
    if (descTextarea) {
        updateDescCounter();
        descTextarea.addEventListener('input', updateDescCounter);
    }
    
    // Enhanced Tagify for tags input
    const tagsInput = document.querySelector('#tagsInput');
    const form = tagsInput && tagsInput.form;
    let tagify;
    
    if (tagsInput) {
        tagify = new Tagify(tagsInput, {
            whitelist: [],
            maxTags: 10,
            dropdown: {
                enabled: 0,
                maxItems: 10,
            },
            templates: {
                tag: function(tagData) {
                    return `<tag title="${tagData.value}" contenteditable='false' spellcheck='false' class='tagify__tag bg-green-100 text-green-800 border-green-200' ${this.getAttributes(tagData)}>
                        <x title='Remove' class='tagify__tag__removeBtn text-green-600 hover:text-green-800'></x>
                        <div><span class='tagify__tag-text'>${tagData.value}</span></div>
                    </tag>`;
                }
            }
        });
        
        // Sync Tagify value before form submit
        if (form) {
            form.addEventListener('submit', function(e) {
                // Show loading state
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalHTML = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Updating...</span>
                `;
                
                // Prepare tags value
                if (tagify) {
                    tagsInput.value = tagify.value.length ? JSON.stringify(tagify.value.map(function(t){return t.value;})) : '';
                }
                
                // Reset after timeout in case of issues
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalHTML;
                }, 10000);
            });
        }
    }
    
    // Update category counter
    function updateCategoryCounter() {
        const checkedCategories = document.querySelectorAll('input[name="categories[]"]:checked');
        const counter = document.querySelector('.text-xs.bg-green-100');
        if (counter) {
            counter.textContent = `${checkedCategories.length} selected`;
        }
    }
    
    // Listen for category changes
    document.querySelectorAll('input[name="categories[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', updateCategoryCounter);
    });

    // SK users automatically use their barangay - no scope selection needed
});

// CSS Animation
const style = document.createElement('style');
style.textContent = `
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }
    
    /* Custom scrollbar for categories */
    .max-h-64::-webkit-scrollbar {
        width: 6px;
    }
    .max-h-64::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }
    .max-h-64::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }
    .max-h-64::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
`;
document.head.appendChild(style);
</script>

 
