
<?php $title = 'Share Document'; ?>

<div class="max-w-4xl mx-auto p-8 mt-10 bg-white/90 shadow-2xl rounded-2xl border border-blue-100 animate-fade-in">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-extrabold text-blue-800 tracking-tight flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
            </svg>
            Share Document
        </h1>
        <a href="<?= base_url('admin/documents') ?>" class="bg-gray-100 text-gray-700 px-6 py-2 rounded-xl font-semibold shadow hover:bg-gray-200 transition flex items-center gap-2 border-2 border-gray-200 hover:border-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Documents
        </a>
    </div>
    <!-- Document Info -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-8 animate-fade-in">
        <h2 class="text-xl font-semibold text-blue-800 mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Document Information
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
            <div><strong>Filename:</strong> <?= esc($doc['filename']) ?></div>
            <div><strong>Type:</strong> <?= esc($doc['mimetype']) ?></div>
            <div><strong>Size:</strong> <?= number_format($doc['filesize']/1024, 2) ?> KB</div>
            <div><strong>Uploaded:</strong> <?= esc($doc['uploaded_at']) ?></div>
        </div>
    </div>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="mb-6 flex items-center justify-between bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-lg shadow-sm animate-fade-in" role="alert">
            <span class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <?= session()->getFlashdata('success') ?>
            </span>
            <button onclick="this.parentElement.remove()" class="ml-4 text-green-700 hover:text-green-900 text-xl">&times;</button>
        </div>
    <?php endif; ?>
    <?php if (isset($successMsg) && $successMsg): ?>
        <div class="mb-6 flex items-center justify-between bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-lg shadow-sm animate-fade-in" role="alert">
            <span class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <?= $successMsg ?>
            </span>
            <button onclick="this.parentElement.remove()" class="ml-4 text-green-700 hover:text-green-900 text-xl">&times;</button>
        </div>
    <?php endif; ?>
    <?php if (isset($errorMsg) && $errorMsg): ?>
        <div class="mb-6 flex items-center justify-between bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-lg shadow-sm animate-fade-in" role="alert">
            <span class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <?= $errorMsg ?>
            </span>
            <button onclick="this.parentElement.remove()" class="ml-4 text-red-700 hover:text-red-900 text-xl">&times;</button>
        </div>
    <?php endif; ?>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Share Form -->
        <div class="bg-white border border-gray-200 rounded-xl p-8 shadow-lg animate-fade-in">
            <h3 class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Share with New User
            </h3>
            <form action="<?= base_url('admin/documents/share/' . $doc['id']) ?>" method="post" class="space-y-8">
                <div>
                    <label for="shared_with" class="block text-sm font-semibold text-blue-700 mb-2">Username or Email</label>
                    <input type="text" id="shared_with" name="shared_with" required 
                           class="w-full border border-blue-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none shadow-sm transition" placeholder="Enter username or email">
                </div>
                <div>
                    <label for="permissions" class="block text-sm font-semibold text-blue-700 mb-2">Permissions</label>
                    <select id="permissions" name="permissions" required 
                            class="w-full border border-blue-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none shadow-sm transition">
                        <option value="view">View Only</option>
                        <option value="download">View & Download</option>
                        <option value="edit">View, Download & Edit</option>
                        <option value="admin">Full Access (Admin)</option>
                    </select>
                </div>
                <div>
                    <label for="expires_at" class="block text-sm font-semibold text-blue-700 mb-2">Expires At (Optional)</label>
                    <input type="datetime-local" id="expires_at" name="expires_at" 
                           class="w-full border border-blue-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none shadow-sm transition">
                    <p class="text-sm text-gray-500 mt-1">Leave empty for no expiration</p>
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-400 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:from-blue-700 hover:to-blue-500 transition-all flex items-center justify-center gap-3 text-lg border-2 border-blue-200 hover:border-blue-400 focus:ring-4 focus:ring-blue-200 outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
                    </svg>
                    Share Document
                </button>
            </form>
        </div>
        <!-- Current Shares -->
        <div class="bg-white border border-gray-200 rounded-xl p-8 shadow-lg animate-fade-in">
            <h3 class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            Current Shares (<?= count($currentShares) ?>)
            </h3>
            <?php if (empty($currentShares)): ?>
                <div class="text-center text-gray-500 py-8 animate-fade-in">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-300 mb-4 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <p>No active shares</p>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($currentShares as $share): ?>
                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 flex items-center justify-between animate-fade-in">
                            <div class="flex-1">
                                <div class="font-medium text-gray-800 flex items-center gap-2">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-100 text-blue-700 font-bold text-sm">
                                        <?= strtoupper(substr(esc($share['shared_with']), 0, 1)) ?>
                                    </span>
                                    <?= esc($share['shared_with']) ?>
                                </div>
                                <div class="text-sm text-gray-600 mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                        <?= ucfirst($share['permissions']) ?>
                                    </span>
                                    <?php if ($share['expires_at']): ?>
                                        <span class="ml-2 text-orange-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            Expires: <?= date('M j, Y', strtotime($share['expires_at'])) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    Shared by <?= esc($share['shared_by']) ?> on <?= date('M j, Y', strtotime($share['shared_at'])) ?>
                                </div>
                            </div>
                            <button onclick="revokeShare(<?= $share['id'] ?>)" 
                                    class="text-red-600 hover:text-red-800 p-2 rounded-lg hover:bg-red-50 transition flex items-center gap-1 ml-4" title="Revoke access">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <span class="hidden md:inline">Revoke</span>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function revokeShare(shareId) {
    Swal.fire({
        title: 'Revoke Access?',
        text: 'This will immediately remove access for this user.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, revoke access!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= base_url('admin/documents/revoke-share/' . $doc['id'] . '/') ?>' + shareId;
        }
    });
}
</script>

 
