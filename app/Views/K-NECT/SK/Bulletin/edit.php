<!-- Main Content Area -->
<div class="flex-1 lg:ml-64 pt-16 min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-50">
    <!-- Header Section with Enhanced Blue Gradient -->
    <div class="bg-gradient-to-r from-blue-600 via-blue-700 to-blue-800 shadow-lg">
        <div class="px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center space-x-3 mb-1">
                        <div class="flex-shrink-0 w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <h1 class="text-2xl font-bold text-white sm:text-3xl tracking-tight">
                            Edit Post
                        </h1>
                    </div>
                    <p class="mt-1 text-sm text-blue-100 max-w-2xl">
                        Update your post content and settings
                    </p>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-4 flex space-x-3">
                    <a href="<?= base_url('/bulletin/view/' . $post['id']) ?>" 
                       class="inline-flex items-center px-4 py-2 border-2 border-white/30 rounded-lg shadow-sm text-sm font-semibold text-white bg-white/10 backdrop-blur-sm hover:bg-white/20 hover:border-white/50 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-700 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        View Post
                    </a>
                    <a href="<?= base_url('/bulletin') ?>" 
                       class="inline-flex items-center px-4 py-2 border-2 border-white/30 rounded-lg shadow-sm text-sm font-semibold text-white bg-white/10 backdrop-blur-sm hover:bg-white/20 hover:border-white/50 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-700 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Bulletin
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Form Content -->
    <div class="px-4 sm:px-6 lg:px-8 py-6">
        <form id="bulletinEditForm" class="max-w-7xl mx-auto" enctype="multipart/form-data">
            <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Main Content Column -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Basic Information Card -->
                    <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-50 to-white px-5 py-3 border-b border-gray-100">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h3 class="text-base font-bold text-blue-900">Post Details</h3>
                            </div>
                        </div>
                        <div class="p-5">
                            <!-- Title -->
                            <div class="mb-4">
                                <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="title" name="title" required
                                       value="<?= esc($post['title']) ?>"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                       placeholder="Enter post title...">
                                <div class="mt-1 text-sm text-red-600 hidden" id="title-error"></div>
                            </div>

                            <!-- Content Editor -->
                            <div class="mb-4">
                                <label for="content" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Content <span class="text-red-500">*</span>
                                </label>
                                <textarea id="content" name="content" rows="12" required
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 font-sans resize-none"
                                          placeholder="Write your post content here..."><?= esc($post['content']) ?></textarea>
                                <div class="mt-2 flex items-start space-x-1.5 text-xs text-gray-500">
                                    <svg class="w-3.5 h-3.5 mt-0.5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Use plain text or basic HTML formatting. Maximum 10,000 characters.</span>
                                </div>
                                <div class="mt-1 text-sm text-red-600 hidden" id="content-error"></div>
                            </div>

                            <!-- Tags -->
                            <div class="mb-0">
                                <label for="tags" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Tags
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                    </div>
                                    <input type="text" id="tags" name="tags"
                                           value="<?= !empty($post_tags) ? implode(', ', array_column($post_tags, 'name')) : '' ?>"
                                           class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                           placeholder="youth, announcement, event">
                                </div>
                                <div class="mt-2 flex items-start space-x-1.5 text-xs text-gray-500">
                                    <svg class="w-3.5 h-3.5 mt-0.5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Add relevant tags to help categorize your post. Separate multiple tags with commas.</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Media Upload Card -->
                    <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-50 to-white px-5 py-3 border-b border-gray-100">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <h3 class="text-base font-bold text-blue-900">Featured Image</h3>
                            </div>
                        </div>
                        <div class="p-5">
                            <!-- Current Image -->
                            <?php if ($post['featured_image']): ?>
                            <div class="mb-4" id="current-image">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Current Image</label>
                                <div class="relative inline-block">
                                    <img src="<?= base_url('/uploads/bulletin/' . $post['featured_image']) ?>" 
                                         alt="Current featured image" 
                                         class="max-h-32 rounded-lg border-2 border-gray-200 shadow">
                                    <button type="button" onclick="removeCurrentImage()" 
                                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-7 h-7 flex items-center justify-center text-lg font-bold hover:bg-red-600 shadow-lg transition-colors">
                                        ×
                                    </button>
                                </div>
                                <input type="hidden" id="remove_image" name="remove_image" value="0">
                            </div>
                            <?php endif; ?>
                            
                            <div class="mb-0">
                                <label for="featured_image" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <?= $post['featured_image'] ? 'Replace Image' : 'Upload Image' ?>
                                </label>
                                <div class="flex items-center justify-center w-full">
                                    <label for="featured_image" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors duration-200 group">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6" id="upload-placeholder">
                                            <div class="w-12 h-12 mb-2 bg-gray-200 rounded-full flex items-center justify-center group-hover:bg-gray-300 transition-colors">
                                                <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                </svg>
                                            </div>
                                            <p class="mb-1 text-sm text-gray-600">
                                                <span class="font-semibold text-gray-700">Click to upload</span> <span class="text-gray-500">or drag and drop</span>
                                            </p>
                                            <p class="text-xs text-gray-500">PNG, JPG or JPEG (MAX. 2MB)</p>
                                        </div>
                                        <div class="hidden p-4" id="image-preview">
                                            <img id="preview-img" src="" alt="Preview" class="max-h-24 rounded-lg shadow border border-gray-200">
                                            <p class="mt-2 text-sm font-medium text-gray-700 text-center" id="image-name"></p>
                                        </div>
                                        <input id="featured_image" name="featured_image" type="file" class="hidden" accept="image/*">
                                    </label>
                                </div>
                                <div class="mt-1 text-sm text-red-600 hidden" id="image-error"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Column -->
                <div class="lg:col-span-1 space-y-6">
                    
                    <!-- Publish Settings Card -->
                    <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-5 py-3">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <h3 class="text-base font-bold text-white">Publish Settings</h3>
                            </div>
                        </div>
                        <div class="p-5">
                            <!-- Status -->
                            <div class="mb-4">
                                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <select id="status" name="status" required
                                            class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white appearance-none">
                                        <option value="draft" <?= $post['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                                        <option value="published" <?= $post['status'] === 'published' ? 'selected' : '' ?>>Published</option>
                                        <option value="archived" <?= $post['status'] === 'archived' ? 'selected' : '' ?>>Archived</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Visibility (Locked for SK) -->
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Visibility <span class="text-red-500">*</span>
                                </label>
                                <?php if ($user_type === 'sk'): ?>
                                    <div class="px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-sm font-medium text-gray-700 flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span>Barangay Only</span>
                                    </div>
                                    <input type="hidden" name="visibility" value="barangay">
                                    <div class="mt-2 flex items-start space-x-1.5 text-xs text-gray-500">
                                        <svg class="w-3.5 h-3.5 mt-0.5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                        <span>SK posts are limited to their own barangay.</span>
                                    </div>
                                <?php else: ?>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </div>
                                        <select id="visibility" name="visibility" required
                                                class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white appearance-none">
                                            <option value="public" <?= $post['visibility'] === 'public' ? 'selected' : '' ?>>Public</option>
                                            <option value="barangay" <?= $post['visibility'] === 'barangay' ? 'selected' : '' ?>>Barangay Only</option>
                                            <option value="city" <?= $post['visibility'] === 'city' ? 'selected' : '' ?>>City-wide</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="mt-2 text-xs text-gray-500">
                                        Choose who can see this post
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Barangay Selection (Pederasyon only) -->
                            <?php if ($user_type === 'pederasyon' && !empty($barangays)): ?>
                            <div class="mb-4" id="barangay-selection">
                                <label for="barangay_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Target Barangay
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <select id="barangay_id" name="barangay_id"
                                            class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white appearance-none">
                                        <option value="">All Barangays</option>
                                        <?php foreach ($barangays as $barangay): ?>
                                        <option value="<?= $barangay['barangay_id'] ?>" <?= $post['barangay_id'] == $barangay['barangay_id'] ? 'selected' : '' ?>>
                                            <?= esc($barangay['name']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-2 text-xs text-gray-500">
                                    Select specific barangay (optional)
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Category -->
                            <div class="mb-0">
                                <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Category
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                    </div>
                                    <select id="category_id" name="category_id"
                                            class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white appearance-none">
                                        <option value="">Select Category</option>
                                        <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category['id'] ?>" <?= $post['category_id'] == $category['id'] ? 'selected' : '' ?>>
                                            <?= esc($category['name']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Post Options Card -->
                    <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-50 to-white px-5 py-3 border-b border-gray-100">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                </svg>
                                <h3 class="text-base font-bold text-blue-900">Post Options</h3>
                            </div>
                        </div>
                        <div class="p-5">
                            <!-- Featured Post -->
                            <div class="mb-4 bg-yellow-50 p-3 rounded-lg border border-yellow-200">
                                <div class="flex items-start">
                                    <input id="is_featured" name="is_featured" type="checkbox" value="1"
                                           <?= $post['is_featured'] ? 'checked' : '' ?>
                                           class="mt-0.5 h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300 rounded">
                                    <div class="ml-3">
                                        <label for="is_featured" class="flex items-center text-sm font-semibold text-gray-900 cursor-pointer">
                                            <svg class="w-4 h-4 mr-1.5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                            Featured Post
                                        </label>
                                        <p class="mt-1 text-xs text-gray-600">
                                            Featured posts appear prominently on the bulletin board
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Urgent Post -->
                            <div class="mb-0 bg-red-50 p-3 rounded-lg border border-red-200">
                                <div class="flex items-start">
                                    <input id="is_urgent" name="is_urgent" type="checkbox" value="1"
                                           <?= $post['is_urgent'] ? 'checked' : '' ?>
                                           class="mt-0.5 h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                    <div class="ml-3">
                                        <label for="is_urgent" class="flex items-center text-sm font-semibold text-gray-900 cursor-pointer">
                                            <svg class="w-4 h-4 mr-1.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                            </svg>
                                            Urgent Announcement
                                        </label>
                                        <p class="mt-1 text-xs text-gray-600">
                                            Urgent posts are highlighted with special styling
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons Card -->
                    <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-blue-800 rounded-lg shadow-lg overflow-hidden">
                        <div class="p-5">
                            <div class="space-y-3">
                                <button type="submit" id="updateBtn"
                                        class="w-full inline-flex justify-center items-center px-5 py-3 border-2 border-white/30 rounded-lg shadow-lg text-base font-bold text-blue-900 bg-white hover:bg-blue-50 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-700 transition-all duration-200 transform hover:scale-105">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                    </svg>
                                    <span id="updateBtnText">Update Post</span>
                                </button>
                                
                                <button type="button" onclick="window.location.href='<?= base_url('/bulletin/view/' . $post['id']) ?>'"
                                        class="w-full inline-flex justify-center items-center px-5 py-2.5 border-2 border-white/50 rounded-lg shadow-sm text-sm font-semibold text-white bg-white/10 backdrop-blur-sm hover:bg-white/20 hover:border-white/70 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-700 transition-all duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('bulletinEditForm');
    const updateBtn = document.getElementById('updateBtn');
    const statusSelect = document.getElementById('status');
    const updateBtnText = document.getElementById('updateBtnText');
    const imageInput = document.getElementById('featured_image');
    const uploadPlaceholder = document.getElementById('upload-placeholder');
    const imagePreview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    const imageName = document.getElementById('image-name');

    // Handle status change
    statusSelect.addEventListener('change', function() {
        updateBtnText.textContent = this.value === 'published' ? 'Update & Publish' : 'Update Post';
    });

    // Handle image upload preview
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file size (2MB limit)
            if (file.size > 2 * 1024 * 1024) {
                showMessage('File size must be less than 2MB', 'error');
                this.value = '';
                return;
            }

            // Validate file type
            if (!file.type.match(/^image\/(jpeg|jpg|png)$/)) {
                showMessage('Please select a valid image file (JPEG, JPG, or PNG)', 'error');
                this.value = '';
                return;
            }

            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imageName.textContent = file.name;
                uploadPlaceholder.classList.add('hidden');
                imagePreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    // Handle form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!validateForm()) {
            return;
        }

        const formData = new FormData(form);
        const postId = formData.get('post_id');
        
        // Show loading state
        updateBtn.disabled = true;
        updateBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Updating...
        `;

        fetch(`<?= base_url('/bulletin/update/') ?>${postId}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(async response => {
            const contentType = response.headers.get('content-type') || '';
            if (!response.ok) {
                const text = await response.text();
                throw new Error(text || 'Request failed');
            }
            if (contentType.includes('application/json')) {
                return response.json();
            }
            const text = await response.text();
            try { return JSON.parse(text); } catch (_) { throw new Error(text); }
        })
        .then(data => {
            if (data.success) {
                // Redirect with toast flag
                window.location.href = `<?= base_url('/bulletin/view/') ?>${postId}?toast=updated`;
            } else {
                console.error('Error:', data.message || 'An error occurred while updating the post');
                if (data.errors) {
                    displayValidationErrors(data.errors);
                }
                // Reset button state on error
                updateBtn.disabled = false;
                updateBtn.innerHTML = `
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    <span>${statusSelect.value === 'published' ? 'Update & Publish' : 'Update Post'}</span>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Reset button state on error
            updateBtn.disabled = false;
            updateBtn.innerHTML = `
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
                <span>${statusSelect.value === 'published' ? 'Update & Publish' : 'Update Post'}</span>
            `;
        });
    });

    function validateForm() {
        let isValid = true;
        
        // Clear previous errors
        document.querySelectorAll('.text-red-600').forEach(el => el.classList.add('hidden'));
        
        // Validate title
        const title = document.getElementById('title').value.trim();
        if (!title) {
            showFieldError('title-error', 'Title is required');
            isValid = false;
        } else if (title.length < 3) {
            showFieldError('title-error', 'Title must be at least 3 characters');
            isValid = false;
        }
        
        // Validate content
        const content = document.getElementById('content').value.trim();
        if (!content) {
            showFieldError('content-error', 'Content is required');
            isValid = false;
        } else if (content.length < 10) {
            showFieldError('content-error', 'Content must be at least 10 characters');
            isValid = false;
        }
        
        return isValid;
    }

    function showFieldError(elementId, message) {
        const errorElement = document.getElementById(elementId);
        errorElement.textContent = message;
        errorElement.classList.remove('hidden');
    }

    function displayValidationErrors(errors) {
        Object.keys(errors).forEach(field => {
            const errorElement = document.getElementById(field + '-error');
            if (errorElement) {
                errorElement.textContent = errors[field];
                errorElement.classList.remove('hidden');
            }
        });
    }
});

function removeCurrentImage() {
    document.getElementById('current-image').style.display = 'none';
    document.getElementById('remove_image').value = '1';
}
</script>
