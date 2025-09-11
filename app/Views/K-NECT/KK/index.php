

<div class="max-w-6xl mx-auto p-0 mt-10">
    <div class="relative z-10">
        <div class="absolute inset-0 bg-gradient-to-br from-green-100/60 to-white/80 rounded-3xl blur-xl opacity-80"></div>
        <div class="relative p-10 rounded-3xl shadow-2xl border border-green-100 bg-white/70 backdrop-blur-lg">
            <div class="text-center mb-8">
                <h1 class="text-4xl font-extrabold text-green-900 tracking-tight flex items-center justify-center gap-3 drop-shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                    </svg>
                    Users
                </h1>
                <p class="text-lg text-green-700 mt-2 font-medium opacity-80">
                    KK User - System Users
                </p>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 border border-green-100">
                <h3 class="text-xl font-bold text-green-900 mb-4">System Users</h3>
                <p class="text-gray-700 mb-6">
                    View information about system users. As a KK user, you have limited access to user information.
                </p>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                        <thead class="bg-green-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (!empty($users)): ?>
                                <?php foreach ($users as $user): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <?= esc($user['first_name'] . ' ' . $user['last_name']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= esc($user['email']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?= $user['role'] === 'super_admin' ? 'bg-purple-100 text-purple-800' : 
                                               ($user['role'] === 'SK' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') ?>">
                                            <?= ucfirst($user['role']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?= $user['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                            <?= $user['is_active'] ? 'Active' : 'Inactive' ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No users found
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-6 flex justify-center">
                    <a href="<?= base_url('documents') ?>" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        View Documents
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

 
