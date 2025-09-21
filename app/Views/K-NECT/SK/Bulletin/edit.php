<!-- Main Content Area -->
<div class="flex-1 lg:ml-64 min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="flex-1 min-w-0">
                    <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">
                        ✏️ Edit Post
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Update your post content and settings
                    </p>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-4 flex space-x-3">
                    <a href="<?= base_url('/bulletin/view/' . $post['id']) ?>" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        View Post
                    </a>
                    <a href="<?= base_url('/bulletin') ?>" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
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
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Post Details</h3>
                        
                        <!-- Title -->
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title" name="title" required
                                   value="<?= esc($post['title']) ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Enter post title...">
                            <div class="mt-1 text-sm text-red-600 hidden" id="title-error"></div>
                        </div>

                        <!-- Content Editor -->
                        <div class="mb-4">
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                                Content <span class="text-red-500">*</span>
                            </label>
                            <textarea id="content" name="content" rows="12" required
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Write your post content here..."><?= esc($post['content']) ?></textarea>
                            <div class="mt-1 text-sm text-gray-500">
                                Use plain text or basic HTML formatting. Maximum 10,000 characters.
                            </div>
                            <div class="mt-1 text-sm text-red-600 hidden" id="content-error"></div>
                        </div>

                        <!-- Tags -->
                        <div class="mb-4">
                            <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">
                                Tags
                            </label>
                            <input type="text" id="tags" name="tags"
                                   value="<?= !empty($post_tags) ? implode(', ', array_column($post_tags, 'name')) : '' ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Enter tags separated by commas (e.g., youth, announcement, event)">
                            <div class="mt-1 text-sm text-gray-500">
                                Add relevant tags to help categorize your post. Separate multiple tags with commas.
                            </div>
                        </div>
                    </div>

                    <!-- Media Upload Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Featured Image</h3>
                        
                        <!-- Current Image -->
                        <?php if ($post['featured_image']): ?>
                        <div class="mb-4" id="current-image">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                            <div class="relative inline-block">
                                <img src="<?= base_url('/uploads/bulletin/' . $post['featured_image']) ?>" 
                                     alt="Current featured image" 
                                     class="max-h-32 rounded-lg border border-gray-300">
                                <button type="button" onclick="removeCurrentImage()" 
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm hover:bg-red-600">
                                    ×
                                </button>
                            </div>
                            <input type="hidden" id="remove_image" name="remove_image" value="0">
                        </div>
                        <?php endif; ?>
                        
                        <div class="mb-4">
                            <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">
                                <?= $post['featured_image'] ? 'Replace Image' : 'Upload Image' ?>
                            </label>
                            <div class="flex items-center justify-center w-full">
                                <label for="featured_image" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6" id="upload-placeholder">
                                        <svg class="w-8 h-8 mb-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500">
                                            <span class="font-semibold">Click to upload</span> or drag and drop
                                        </p>
                                        <p class="text-xs text-gray-500">PNG, JPG or JPEG (MAX. 2MB)</p>
                                    </div>
                                    <div class="hidden" id="image-preview">
                                        <img id="preview-img" src="" alt="Preview" class="max-h-28 rounded">
                                        <p class="mt-2 text-sm text-gray-600" id="image-name"></p>
                                    </div>
                                    <input id="featured_image" name="featured_image" type="file" class="hidden" accept="image/*">
                                </label>
                            </div>
                            <div class="mt-1 text-sm text-red-600 hidden" id="image-error"></div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Column -->
                <div class="lg:col-span-1 space-y-6">
                    
                    <!-- Publish Settings Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Publish Settings</h3>
                        
                        <!-- Status -->
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select id="status" name="status" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                <option value="draft" <?= $post['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                                <option value="published" <?= $post['status'] === 'published' ? 'selected' : '' ?>>Published</option>
                                <option value="archived" <?= $post['status'] === 'archived' ? 'selected' : '' ?>>Archived</option>
                            </select>
                        </div>

                        <!-- Visibility (Locked for SK) -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Visibility <span class="text-red-500">*</span>
                            </label>
                            <?php if ($user_type === 'sk'): ?>
                                <div class="px-3 py-2 border border-gray-200 rounded-md bg-gray-50 text-sm text-gray-700">
                                    Barangay Only
                                </div>
                                <input type="hidden" name="visibility" value="barangay">
                                <div class="mt-1 text-xs text-gray-500">
                                    SK posts are limited to their own barangay.
                                </div>
                            <?php else: ?>
                                <select id="visibility" name="visibility" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                    <option value="public" <?= $post['visibility'] === 'public' ? 'selected' : '' ?>>Public</option>
                                    <option value="barangay" <?= $post['visibility'] === 'barangay' ? 'selected' : '' ?>>Barangay Only</option>
                                    <option value="city" <?= $post['visibility'] === 'city' ? 'selected' : '' ?>>City-wide</option>
                                </select>
                                <div class="mt-1 text-sm text-gray-500">
                                    Choose who can see this post
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Barangay Selection (Pederasyon only) -->
                        <?php if ($user_type === 'pederasyon' && !empty($barangays)): ?>
                        <div class="mb-4" id="barangay-selection">
                            <label for="barangay_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Target Barangay
                            </label>
                            <select id="barangay_id" name="barangay_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Barangays</option>
                                <?php foreach ($barangays as $barangay): ?>
                                <option value="<?= $barangay['barangay_id'] ?>" <?= $post['barangay_id'] == $barangay['barangay_id'] ? 'selected' : '' ?>>
                                    <?= esc($barangay['name']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="mt-1 text-sm text-gray-500">
                                Select specific barangay (optional)
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Category -->
                        <div class="mb-4">
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Category
                            </label>
                            <select id="category_id" name="category_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>" <?= $post['category_id'] == $category['id'] ? 'selected' : '' ?>>
                                    <?= esc($category['name']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Post Options Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Post Options</h3>
                        
                        <!-- Featured Post -->
                        <div class="mb-4">
                            <div class="flex items-center">
                                <input id="is_featured" name="is_featured" type="checkbox" value="1"
                                       <?= $post['is_featured'] ? 'checked' : '' ?>
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="is_featured" class="ml-2 block text-sm text-gray-900">
                                    Featured Post
                                </label>
                            </div>
                            <div class="mt-1 text-sm text-gray-500">
                                Featured posts appear prominently on the bulletin board
                            </div>
                        </div>

                        <!-- Urgent Post -->
                        <div class="mb-4">
                            <div class="flex items-center">
                                <input id="is_urgent" name="is_urgent" type="checkbox" value="1"
                                       <?= $post['is_urgent'] ? 'checked' : '' ?>
                                       class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                <label for="is_urgent" class="ml-2 block text-sm text-gray-900">
                                    Urgent Announcement
                                </label>
                            </div>
                            <div class="mt-1 text-sm text-gray-500">
                                Urgent posts are highlighted with special styling
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <div class="space-y-3">
                            <button type="submit" id="updateBtn"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                <span id="updateBtnText">Update Post</span>
                            </button>
                            
                            <button type="button" onclick="window.location.href='<?= base_url('/bulletin/view/' . $post['id']) ?>'"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Success/Error Messages -->
<div id="messageContainer" class="fixed top-4 right-4 z-50 hidden">
    <div id="messageAlert" class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden">
        <div class="p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg id="messageIcon" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-3 w-0 flex-1 pt-0.5">
                    <p id="messageText" class="text-sm font-medium text-gray-900"></p>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button onclick="hideMessage()" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none">
                        <span class="sr-only">Close</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
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
                showMessage('Post updated successfully!', 'success');
                setTimeout(() => {
                    window.location.href = `<?= base_url('/bulletin/view/') ?>${postId}?toast=updated`;
                }, 1200);
            } else {
                showMessage(data.message || 'An error occurred while updating the post', 'error');
                if (data.errors) {
                    displayValidationErrors(data.errors);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('An error occurred while updating the post', 'error');
        })
        .finally(() => {
            // Reset button state
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

function showMessage(message, type) {
    const container = document.getElementById('messageContainer');
    const alert = document.getElementById('messageAlert');
    const icon = document.getElementById('messageIcon');
    const text = document.getElementById('messageText');
    
    text.textContent = message;
    
    // Reset classes
    alert.className = 'max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden';
    
    if (type === 'success') {
        alert.classList.add('border-l-4', 'border-green-400');
        icon.className = 'h-6 w-6 text-green-400';
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>';
    } else {
        alert.classList.add('border-l-4', 'border-red-400');
        icon.className = 'h-6 w-6 text-red-400';
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>';
    }
    
    container.classList.remove('hidden');
    
    // Auto hide after 5 seconds
    setTimeout(hideMessage, 5000);
}

function hideMessage() {
    document.getElementById('messageContainer').classList.add('hidden');
}
</script>
