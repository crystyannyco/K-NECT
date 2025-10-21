<!-- Main Content Area -->
<div class="flex-1 lg:ml-64 min-h-screen bg-gray-50 pt-16">
    <!-- Main Content -->
    <div class="px-4 sm:px-6 lg:px-8 py-6">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Main Article Column -->
                <div class="lg:col-span-2">
                    <article class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        
                        <!-- Featured Image -->
                        <?php if ($post['featured_image']): ?>
                        <div class="aspect-w-16 aspect-h-9">
                            <img src="<?= base_url('/uploads/bulletin/' . $post['featured_image']) ?>" 
                                 alt="<?= esc($post['title']) ?>" 
                                 class="w-full h-64 object-cover">
                        </div>
                        <?php endif; ?>

                        <div class="p-6">
                            <!-- Post Header -->
                            <div class="flex items-center flex-wrap gap-2 mb-4">
                                <?php if ($post['is_urgent']): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    🚨 Urgent
                                </span>
                                <?php endif; ?>
                                
                                <?php if ($post['is_featured']): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    ⭐ Featured
                                </span>
                                <?php endif; ?>

                                <?php if ($post['category_name']): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                      style="background-color: <?= $post['category_color'] ?>20; color: <?= $post['category_color'] ?>">
                                    <?= esc($post['category_name']) ?>
                                </span>
                                <?php endif; ?>

                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <?= ucfirst($post['visibility']) ?>
                                </span>
                            </div>

                            <!-- Post Title -->
                            <h1 class="text-3xl font-bold text-gray-900 mb-4">
                                <?= esc($post['title']) ?>
                            </h1>

                            <!-- Post Meta -->
                            <div class="flex items-center justify-between text-sm text-gray-500 mb-6 pb-6 border-b border-gray-200">
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-white text-sm font-medium">
                                                <?= strtoupper(substr($post['first_name'], 0, 1)) ?>
                                            </span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">
                                                <?= esc($post['first_name'] . ' ' . $post['last_name']) ?>
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                <?= ucfirst($post['user_type']) ?> 
                                                <?php if ($post['barangay_name']): ?>
                                                • <?= esc($post['barangay_name']) ?>
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <?= date('M j, Y • g:i A', strtotime($post['published_at'])) ?>
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <?= number_format($post['view_count']) ?> views
                                    </span>
                                </div>
                            </div>

                            <!-- Post Content -->
                            <div class="prose prose-lg max-w-none">
                                <?= nl2br(esc($post['content'])) ?>
                            </div>

                            <!-- Post Footer -->
                            <div class="mt-8 pt-6 border-t border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <!-- Share buttons can be added here -->
                                        <span class="text-sm text-gray-500">Share this post:</span>
                                        <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            📋 Copy Link
                                        </button>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Last updated: <?= date('M j, Y', strtotime($post['updated_at'])) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    
                    <!-- Author Info Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">About the Author</h3>
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mr-4">
                                <span class="text-white text-lg font-medium">
                                    <?= strtoupper(substr($post['first_name'], 0, 1)) ?>
                                </span>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">
                                    <?= esc($post['first_name'] . ' ' . $post['last_name']) ?>
                                </h4>
                                <p class="text-sm text-gray-500">
                                    <?= ucfirst($post['user_type']) ?>
                                    <?php if ($post['barangay_name']): ?>
                                    <br><?= esc($post['barangay_name']) ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Related Posts -->
                    <?php if (!empty($related_posts)): ?>
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Related Posts</h3>
                        <div class="space-y-4">
                            <?php foreach ($related_posts as $related): ?>
                            <div class="border-b border-gray-200 pb-4 last:border-b-0 last:pb-0">
                                <h4 class="text-sm font-medium text-gray-900 mb-1">
                                    <a href="<?= base_url('/bulletin/view/' . $related['id']) ?>" class="hover:text-blue-600">
                                        <?= esc($related['title']) ?>
                                    </a>
                                </h4>
                                <p class="text-xs text-gray-500">
                                    <?= date('M j, Y', strtotime($related['published_at'])) ?> • 
                                    <?= esc($related['first_name'] . ' ' . $related['last_name']) ?>
                                </p>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Quick Actions -->
                    <?php if (in_array($user_type, ['sk', 'pederasyon'])): ?>
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <?php if (
                                ($user_type === 'pederasyon') ||
                                ($user_type === 'sk' && $post['author_id'] == ($user_id ?? session('user_id')) && (string)($post['barangay_id'] ?? '') === (string)($barangay_id ?? ''))
                            ): ?>
                            <!-- Edit Post Button -->
                            <a href="<?= base_url('/bulletin/edit/' . $post['id']) ?>" 
                               class="w-full inline-flex justify-center items-center px-4 py-2 border border-blue-300 rounded-md shadow-sm text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit This Post
                            </a>
                            <!-- Delete Post Button -->
                            <button onclick="deletePost(<?= $post['id'] ?>)" 
                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete This Post
                            </button>
                            <div class="border-t border-gray-200 my-3"></div>
                            <?php endif; ?>
                            <a href="<?= base_url('/bulletin/create') ?>" 
                               class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Create New Post
                            </a>
                            <a href="<?= base_url('/bulletin') ?>" 
                               class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                </svg>
                                All Posts
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4">Delete Post</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Are you sure you want to delete this post? This action cannot be undone.
                </p>
            </div>
            <div class="flex justify-center space-x-4 px-4 py-3">
                <button id="cancelDelete" class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Cancel
                </button>
                <button id="confirmDelete" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let postToDelete = null;

function deletePost(postId) {
    postToDelete = postId;
    document.getElementById('deleteModal').classList.remove('hidden');
}

document.getElementById('cancelDelete').addEventListener('click', function() {
    document.getElementById('deleteModal').classList.add('hidden');
    postToDelete = null;
});

document.getElementById('confirmDelete').addEventListener('click', function() {
    if (postToDelete) {
        fetch(`<?= base_url('/bulletin/delete/') ?>${postToDelete}`, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
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
                window.location.href = '<?= base_url('/bulletin') ?>?toast=deleted';
            } else {
                showToast(data.message || 'Failed to delete post', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast(error.message || 'An error occurred while deleting the post', 'error');
        });
    }
    
    document.getElementById('deleteModal').classList.add('hidden');
    postToDelete = null;
});

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        this.classList.add('hidden');
        postToDelete = null;
    }
});
</script>
<script>
// Unified toast notification function (matches youthlist.php style)
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `stacked-toast fixed right-4 z-[99999] p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
    
    switch(type) {
        case 'success':
            notification.className += ' bg-green-500 text-white';
            break;
        case 'error':
            notification.className += ' bg-red-500 text-white';
            break;
        case 'warning':
            notification.className += ' bg-yellow-500 text-white';
            break;
        default:
            notification.className += ' bg-blue-500 text-white';
    }
    
    // Calculate stacking position based on existing notifications
    const existingToasts = document.querySelectorAll('.stacked-toast');
    let topOffset = 16; // Initial top offset (1rem = 16px)
    existingToasts.forEach(toast => {
        topOffset += toast.offsetHeight + 16; // Add height + 16px gap
    });
    notification.style.top = topOffset + 'px';
    
    // Get appropriate icon based on type
    let icon = '';
    switch(type) {
        case 'success':
            icon = '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>';
            break;
        case 'error':
            icon = '<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z" /></svg>';
            break;
        case 'warning':
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
            <button onclick="this.parentElement.parentElement.remove(); repositionToasts();" class="ml-2 text-white hover:text-gray-200 focus:outline-none">
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
                repositionToasts();
            }
        }, 300);
    }, 5000);
}

// Helper function to reposition remaining toasts after one is removed
function repositionToasts() {
    const toasts = document.querySelectorAll('.stacked-toast');
    let topOffset = 16;
    toasts.forEach(toast => {
        toast.style.top = topOffset + 'px';
        topOffset += toast.offsetHeight + 16;
    });
}

// Legacy alias for backward compatibility
function showToast(message, type='success') {
    showNotification(message, type);
}
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    const t = params.get('toast');
    if (t === 'updated') {
        showNotification('Bulletin post updated successfully', 'success');
        window.history.replaceState({}, document.title, window.location.pathname);
    }
});
</script>
