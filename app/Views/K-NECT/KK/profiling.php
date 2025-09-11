

<div class="max-w-6xl mx-auto p-0 mt-10">
    <div class="relative z-10">
        <div class="absolute inset-0 bg-gradient-to-br from-green-100/60 to-white/80 rounded-3xl blur-xl opacity-80"></div>
        <div class="relative p-10 rounded-3xl shadow-2xl border border-green-100 bg-white/70 backdrop-blur-lg">
            <div class="text-center mb-8">
                <h1 class="text-4xl font-extrabold text-green-900 tracking-tight flex items-center justify-center gap-3 drop-shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    User Profiling
                </h1>
                <p class="text-lg text-green-700 mt-2 font-medium opacity-80">
                    KK User - Document Access System
                </p>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border border-green-100">
                <h3 class="text-xl font-bold text-green-900 mb-4">User Profile</h3>
                <p class="text-gray-700 mb-4">
                    Welcome to the KK User profiling system. As a user, you can view approved documents and manage your profile.
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-green-50 rounded-xl p-6">
                        <h4 class="text-lg font-semibold text-green-900 mb-3">Document Access</h4>
                        <p class="text-green-700 mb-4">View approved documents and resources</p>
                        <a href="<?= base_url('admin/documents') ?>" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            View Documents
                        </a>
        </div>
                    
                    <div class="bg-blue-50 rounded-xl p-6">
                        <h4 class="text-lg font-semibold text-blue-900 mb-3">Profile Management</h4>
                        <p class="text-blue-700 mb-4">Update your personal information</p>
                        <a href="<?= base_url('documents') ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            View Documents
                        </a>
        </div>
        </div>
                
                <div class="mt-8 bg-gray-50 rounded-xl p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-3">User Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-600"><strong>Role:</strong> KK User</p>
                            <p class="text-gray-600"><strong>Permissions:</strong> View approved documents</p>
                        </div>
                        <div>
                            <p class="text-gray-600"><strong>Document Access:</strong> Read-only access to approved documents</p>
                            <p class="text-gray-600"><strong>Profile:</strong> Can update personal information</p>
                        </div>
                    </div>
                </div>
                </div>
        </div>
    </div>
            </div>

 
