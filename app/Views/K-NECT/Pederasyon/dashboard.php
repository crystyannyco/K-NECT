        <!-- ===== MAIN CONTENT AREA ===== -->
        <div class="flex-1 flex flex-col min-h-0 ml-64 pt-16">
            <main class="flex-1 overflow-auto p-6 bg-gray-50">
    <!-- Welcome Section 
    <div class="mb-8">
        <div class="w-full max-w-md bg-white rounded-lg shadow-md p-8 text-center mx-auto">
            <h2 class="text-2xl font-bold mb-4">Welcome, Pederasyon Officer!</h2>
            <p class="mb-2">Your Pederasyon ID: <span class="font-mono text-blue-600"><?= esc($user_id) ?></span></p>
            <p class="mb-4">Your Username: <span class="font-mono text-green-600"><?= esc($username) ?></span></p>
            
            <div class="mt-6">
                <p class="text-gray-600 mb-4">Navigate using the sidebar menu to access different sections.</p>
                <div class="grid grid-cols-1 gap-3">
                    <a href="<?= base_url('pederasyon/my-profile') ?>" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded transition-colors">
                        View My Profile
                    </a>
                    <a href="<?= base_url('pederasyon/member') ?>" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded transition-colors">
                        Member Management
                    </a>
                </div>
            </div>
        </div>
    </div>-->

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg p-6 shadow-sm">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Active Members</p>
                        <p class="text-2xl font-bold text-gray-900">127</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg p-6 shadow-sm">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Upcoming Events</p>
                        <p class="text-2xl font-bold text-gray-900">5</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg p-6 shadow-sm">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Documents</p>
                        <p class="text-2xl font-bold text-gray-900">23</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg p-6 shadow-sm">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 00-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Attendance Rate</p>
                        <p class="text-2xl font-bold text-gray-900">89%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Modules Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Profiling Module -->
            <div class="bg-white rounded-lg shadow-sm transition-all duration-300 ease-in-out hover:-translate-y-1 hover:shadow-lg cursor-pointer">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <span class="text-sm text-gray-500">Updated 2h ago</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Profiling</h3>
                    <p class="text-gray-600 text-sm mb-4">Manage member profiles, roles, and permissions within the youth governance system.</p>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-blue-600 font-medium">View Profiles</span>
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Event Scheduling Module -->
            <div class="bg-white rounded-lg shadow-sm transition-all duration-300 ease-in-out hover:-translate-y-1 hover:shadow-lg cursor-pointer">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <span class="text-sm text-green-600 font-medium">5 upcoming</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Event Scheduling</h3>
                    <p class="text-gray-600 text-sm mb-4">Plan, schedule, and coordinate youth governance meetings and community events.</p>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-green-600 font-medium">Schedule Event</span>
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Document Storage Module -->
            <a href="<?= base_url('admin/documents') ?>" class="block">
                <div class="bg-white rounded-lg shadow-sm transition-all duration-300 ease-in-out hover:-translate-y-1 hover:shadow-lg cursor-pointer">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-blue-100 rounded-lg">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <span class="text-sm text-gray-500">2.3 GB used</span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Document Storage</h3>
                        <p class="text-gray-600 text-sm mb-4">Store, organize, and share important documents, policies, and meeting minutes.</p>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-blue-600 font-medium">Browse Files</span>
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Attendance Tracking Module -->
            <div class="bg-white rounded-lg shadow-sm transition-all duration-300 ease-in-out hover:-translate-y-1 hover:shadow-lg cursor-pointer">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-yellow-100 rounded-lg">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        <span class="text-sm text-yellow-600 font-medium">89% avg</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Attendance Tracking</h3>
                    <p class="text-gray-600 text-sm mb-4">Monitor and track member attendance for meetings, events, and activities.</p>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-yellow-600 font-medium">View Reports</span>
                        <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm transition-all duration-300 ease-in-out hover:-translate-y-1 hover:shadow-lg cursor-pointer">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <span class="text-sm text-gray-500">Quick access</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Quick Actions</h3>
                    <p class="text-gray-600 text-sm mb-4">Frequently used tools and shortcuts for efficient governance management.</p>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-blue-600 font-medium">View Actions</span>
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-900">New member <strong>Sarah Johnson</strong> joined the Youth Council</p>
                            <p class="text-xs text-gray-500">2 hours ago</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-900">Monthly meeting scheduled for <strong>March 15th</strong></p>
                            <p class="text-xs text-gray-500">5 hours ago</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-900">Document <strong>"Community Policy Draft"</strong> was updated</p>
                            <p class="text-xs text-gray-500">1 day ago</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-900">Attendance report generated for February meetings</p>
                            <p class="text-xs text-gray-500">2 days ago</p>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </main>
    </div>
</body>
</html>
