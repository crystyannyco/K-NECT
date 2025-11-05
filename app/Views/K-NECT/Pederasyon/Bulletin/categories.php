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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.99 1.99 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                        </div>
                        <h1 class="text-2xl font-bold text-white sm:text-3xl tracking-tight">
                            Category Management
                        </h1>
                    </div>
                    <p class="mt-1 text-sm text-blue-100 max-w-2xl">
                        Organize and manage bulletin board categories
                    </p>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-4 flex space-x-3">
                    <button onclick="showCreateModal()" 
                            class="inline-flex items-center px-4 py-2 border-2 border-white/30 rounded-lg shadow-sm text-sm font-semibold text-white bg-white/10 backdrop-blur-sm hover:bg-white/20 hover:border-white/50 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-700 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Category
                    </button>
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

    <!-- Main Content -->
    <div class="px-4 sm:px-6 lg:px-8 py-6">
        <div class="max-w-7xl mx-auto">
            <!-- Categories Section -->
            <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-blue-50 to-white px-5 py-3 border-b border-gray-100">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.99 1.99 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        <h3 class="text-base font-bold text-blue-900">Your Categories</h3>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Organize bulletin posts by category</p>
                </div>
                
                <div class="p-5">
                    <div id="categoriesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Categories will be loaded here -->
                    </div>
                </div>
            </div>

            <!-- Quick Add Categories -->
            <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-50 to-white px-5 py-3 border-b border-gray-100">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        <h3 class="text-base font-bold text-blue-900">Quick Setup</h3>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Add common categories with one click</p>
                </div>
                
                <div class="p-5">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <button onclick="addQuickCategory('Announcements', 'General announcements and notifications', '#3B82F6')" 
                                class="p-3 text-center border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-all duration-200">
                            <div class="w-8 h-8 bg-blue-500 rounded-full mx-auto mb-2"></div>
                            <div class="text-sm font-medium">Announcements</div>
                        </button>
                        <button onclick="addQuickCategory('Events', 'Upcoming events and activities', '#10B981')" 
                                class="p-3 text-center border border-gray-200 rounded-lg hover:bg-green-50 hover:border-green-300 transition-all duration-200">
                            <div class="w-8 h-8 bg-green-500 rounded-full mx-auto mb-2"></div>
                            <div class="text-sm font-medium">Events</div>
                        </button>
                        <button onclick="addQuickCategory('Youth Activities', 'Youth-related programs and activities', '#F59E0B')" 
                                class="p-3 text-center border border-gray-200 rounded-lg hover:bg-yellow-50 hover:border-yellow-300 transition-all duration-200">
                            <div class="w-8 h-8 bg-yellow-500 rounded-full mx-auto mb-2"></div>
                            <div class="text-sm font-medium">Youth Activities</div>
                        </button>
                        <button onclick="addQuickCategory('Sports', 'Sports events and tournaments', '#3B82F6')" 
                                class="p-3 text-center border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-all duration-200">
                            <div class="w-8 h-8 bg-blue-500 rounded-full mx-auto mb-2"></div>
                            <div class="text-sm font-medium">Sports</div>
                        </button>
                        <button onclick="addQuickCategory('Health & Wellness', 'Health programs and wellness activities', '#EF4444')" 
                                class="p-3 text-center border border-gray-200 rounded-lg hover:bg-red-50 hover:border-red-300 transition-all duration-200">
                            <div class="w-8 h-8 bg-red-500 rounded-full mx-auto mb-2"></div>
                            <div class="text-sm font-medium">Health</div>
                        </button>
                        <button onclick="addQuickCategory('Education', 'Educational programs and scholarships', '#06B6D4')" 
                                class="p-3 text-center border border-gray-200 rounded-lg hover:bg-cyan-50 hover:border-cyan-300 transition-all duration-200">
                            <div class="w-8 h-8 bg-cyan-500 rounded-full mx-auto mb-2"></div>
                            <div class="text-sm font-medium">Education</div>
                        </button>
                        <button onclick="addQuickCategory('Community Service', 'Volunteer work and community projects', '#84CC16')" 
                                class="p-3 text-center border border-gray-200 rounded-lg hover:bg-lime-50 hover:border-lime-300 transition-all duration-200">
                            <div class="w-8 h-8 bg-lime-500 rounded-full mx-auto mb-2"></div>
                            <div class="text-sm font-medium">Community</div>
                        </button>
                        <button onclick="addQuickCategory('Emergency Alerts', 'Important emergency information', '#DC2626')" 
                                class="p-3 text-center border border-gray-200 rounded-lg hover:bg-red-50 hover:border-red-300 transition-all duration-200">
                            <div class="w-8 h-8 bg-red-600 rounded-full mx-auto mb-2"></div>
                            <div class="text-sm font-medium">Emergency</div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create/Edit Category Modal -->
<div id="categoryModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75 backdrop-blur-sm" onclick="closeModal()"></div>
        
        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-200">
            <form id="categoryForm">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.99 1.99 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg leading-6 font-bold text-white" id="modalTitle">
                            Create New Category
                        </h3>
                    </div>
                </div>
                
                <div class="bg-white px-6 py-5">
                    <input type="hidden" id="categoryId" name="category_id">
                    
                    <div class="mb-4">
                        <label for="categoryName" class="block text-sm font-semibold text-gray-700 mb-2">
                            Category Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="categoryName" name="name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                               placeholder="Enter category name">
                    </div>
                    
                    <div class="mb-4">
                        <label for="categoryDescription" class="block text-sm font-semibold text-gray-700 mb-2">
                            Description
                        </label>
                        <textarea id="categoryDescription" name="description" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                  placeholder="Enter category description"></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label for="categoryColor" class="block text-sm font-semibold text-gray-700 mb-2">
                            Color
                        </label>
                        <div class="flex items-center space-x-3">
                            <input type="color" id="categoryColor" name="color" value="#3B82F6"
                                   class="h-10 w-20 border-2 border-gray-300 rounded-lg cursor-pointer">
                            <span class="text-sm text-gray-500">Choose a color for this category</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 sm:flex sm:flex-row-reverse border-t border-gray-200">
                    <button type="submit" id="saveBtn"
                            class="w-full inline-flex justify-center items-center rounded-lg border-2 border-transparent shadow-sm px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-sm font-semibold text-white hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Save Category
                    </button>
                    <button type="button" onclick="closeModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-lg border-2 border-gray-300 shadow-sm px-5 py-2.5 bg-white text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto transition-all duration-200">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Toast Notification System -->
<script src="<?= base_url('assets/js/toast-notifications.js') ?>"></script>
<script src="<?= base_url('assets/js/confirm-modal.js') ?>"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadCategories();
    
    // Form submission
    document.getElementById('categoryForm').addEventListener('submit', function(e) {
        e.preventDefault();
        saveCategory();
    });
});

function loadCategories() {
    fetch('<?= base_url('/bulletin/categories/list') ?>', {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayCategories(data.categories || []);
            } else {
                displayCategories([]);
            }
        })
        .catch(error => {
            console.error('Error loading categories:', error);
            displayCategories([]);
        });
}

function displayCategories(categories) {
    const grid = document.getElementById('categoriesGrid');
    
    if (categories.length === 0) {
        grid.innerHTML = `
            <div class="col-span-full text-center py-12">
                <div class="text-blue-400 mb-4">
                    <svg class="mx-auto h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.99 1.99 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-gray-900 mb-1">No categories yet</h3>
                <p class="text-sm text-gray-500">Get started by creating your first category or using quick setup options below.</p>
            </div>
        `;
        return;
    }
    
    grid.innerHTML = categories.map(category => `
        <div class="group bg-gradient-to-br from-white to-gray-50 rounded-lg p-4 border-2 border-gray-200 hover:border-blue-300 hover:shadow-md transition-all duration-200">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center flex-1">
                    <div class="w-3 h-3 rounded-full mr-2.5 shadow-sm" style="background-color: ${category.color}"></div>
                    <h3 class="font-semibold text-gray-900 text-sm">${category.name}</h3>
                </div>
                <div class="flex space-x-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    <button onclick="editCategory(${category.id})" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                    <button onclick="deleteCategory(${category.id}, '${category.name.replace(/'/g, "\\\'")}')" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-md transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <p class="text-xs text-gray-600 mb-3 line-clamp-2">${category.description || 'No description'}</p>
            <div class="flex items-center justify-between pt-2 border-t border-gray-200">
                <div class="text-xs font-medium text-gray-500">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-blue-100 text-blue-700">
                        ${(category.post_count || 0)} posts
                    </span>
                </div>
            </div>
        </div>
    `).join('');
}

function showCreateModal() {
    document.getElementById('modalTitle').textContent = 'Create New Category';
    document.getElementById('categoryForm').reset();
    document.getElementById('categoryId').value = '';
    document.getElementById('categoryColor').value = '#3B82F6';
    document.getElementById('saveBtn').textContent = 'Save Category';
    document.getElementById('categoryModal').classList.remove('hidden');
}

function editCategory(id) {
    fetch(`<?= base_url('/bulletin/categories/') ?>${id}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const category = data.category;
                document.getElementById('modalTitle').textContent = 'Edit Category';
                document.getElementById('categoryId').value = category.id;
                document.getElementById('categoryName').value = category.name;
                document.getElementById('categoryDescription').value = category.description || '';
                document.getElementById('categoryColor').value = category.color;
                document.getElementById('saveBtn').textContent = 'Update Category';
                document.getElementById('categoryModal').classList.remove('hidden');
            }
        })
        .catch(() => {
            showNotification('Error loading category data', 'error');
        });
}

function saveCategory() {
    const formData = new FormData(document.getElementById('categoryForm'));
    const categoryId = document.getElementById('categoryId').value;
    const url = categoryId ? 
        `<?= base_url('/bulletin/categories/update/') ?>${categoryId}` : 
        '<?= base_url('/bulletin/categories/store') ?>';
    
    fetch(url, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            closeModal();
            loadCategories();
        } else {
            showNotification(data.message || 'Failed to save category', 'error');
        }
    })
    .catch(() => {
        showNotification('An error occurred while saving the category', 'error');
    });
}

function deleteCategory(id, name) {
    showConfirmModal({
        title: 'Delete Category?',
        message: `Are you sure you want to delete "${name}"? This action cannot be undone and will affect all posts using this category.`,
        confirmText: 'Yes, Delete',
        cancelText: 'Cancel',
        confirmColor: 'red',
        icon: 'warning',
        onConfirm: () => {
            fetch(`<?= base_url('/bulletin/categories/delete/') ?>${id}`, {
                method: 'DELETE',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    loadCategories();
                } else {
                    showNotification(data.message || 'Failed to delete category', 'error');
                }
            })
            .catch(() => {
                showNotification('An error occurred while deleting the category', 'error');
            });
        }
    });
}

function addQuickCategory(name, description, color) {
    const formData = new FormData();
    formData.append('name', name);
    formData.append('description', description);
    formData.append('color', color);
    
    fetch('<?= base_url('/bulletin/categories/store') ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(`${name} category added successfully!`, 'success');
            loadCategories();
        } else {
            showNotification(data.message || 'Failed to add category', 'error');
        }
    })
    .catch(() => {
        showNotification('An error occurred while adding the category', 'error');
    });
}

function closeModal() {
    document.getElementById('categoryModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('categoryModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
