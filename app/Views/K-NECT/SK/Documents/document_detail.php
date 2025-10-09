<!-- Alpine.js for interactivity -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<div class="max-w-4xl mx-auto mt-12">
  <div class="relative z-10">
    <div class="absolute inset-0 bg-gradient-to-br from-green-200/60 via-white/80 to-green-400/40 rounded-3xl blur-xl opacity-80"></div>
    <div class="relative rounded-3xl shadow-2xl border border-green-100 bg-white/80 backdrop-blur-lg overflow-hidden">
      <!-- Header -->
      <div class="px-10 py-8 bg-gradient-to-r from-green-600 via-emerald-500 to-green-400 text-white rounded-t-3xl flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div class="flex items-center gap-5">
          <div class="flex items-center justify-center w-16 h-16 rounded-full bg-white/20 shadow-lg text-3xl font-bold">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7V3a1 1 0 011-1h8a1 1 0 011 1v4" /></svg>
                            </div>
                            <div>
            <h1 class="text-3xl md:text-4xl font-extrabold drop-shadow-lg mb-1"><?= esc($document['filename']) ?></h1>
            <div class="flex items-center gap-2">
              <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-green-100 text-green-700 font-bold text-lg">
                <?= strtoupper(substr(esc($document['uploaded_by']), 0, 1)) ?>
              </span>
              <span class="font-semibold text-white/90 text-lg"> <?= esc($document['uploaded_by']) ?> </span>
                                <?php if ($uploaderRole): ?>
                <span class="ml-2 text-xs px-2 py-0.5 rounded-full bg-white/20 text-white border border-white/30 uppercase font-semibold tracking-wide"> <?= esc($uploaderRole) ?> </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
        <div class="flex flex-col items-end gap-3">
          <!-- Visibility Badge -->
          <span class="px-5 py-2 rounded-full text-lg font-bold shadow-lg transition-all
            <?php 
                if (($document['visibility'] ?? '') === 'pederasyon') echo 'bg-purple-200 text-purple-900'; 
                elseif (($document['visibility'] ?? '') === 'sk') echo 'bg-blue-200 text-blue-900'; 
                else echo 'bg-green-200 text-green-900'; 
            ?>">
            <?php if (($document['visibility'] ?? '') === 'pederasyon'): ?>
              <svg class="inline h-6 w-6 mr-1 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            <?php elseif (($document['visibility'] ?? '') === 'sk'): ?>
              <svg class="inline h-6 w-6 mr-1 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
                            <?php else: ?>
              <svg class="inline h-6 w-6 mr-1 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
                            <?php endif; ?>
                            <?= strtoupper($document['visibility'] ?? 'N/A') ?>
          </span>
          <span class="text-xs text-white/80">Uploaded: <?= date('M j, Y g:i A', strtotime($document['uploaded_at'])) ?></span>
                        </div>
                    </div>
      <!-- Action Buttons -->
      <div class="flex flex-wrap gap-4 px-10 py-6 bg-white/70 border-b border-green-100">
        <?php 
        // Check if user can edit this document
        $canEdit = false;
        if (session('role') === 'super_admin') {
            $canEdit = true;
        } elseif (session('role') === 'admin') {
            // SK can only edit their own documents, not super_admin documents
            $canEdit = ($document['uploaded_by'] === session('username') && $uploaderRole !== 'super_admin');
        }
        ?>
        <?php if ($canEdit): ?>
          <a href="<?= base_url('admin/documents/edit/' . $document['id']) ?>" class="bg-gradient-to-r from-blue-500 to-indigo-400 text-white px-6 py-2 rounded-full font-bold shadow-md hover:from-blue-600 hover:to-indigo-500 transition-all flex items-center gap-2 focus:outline-none focus:ring-2 focus:ring-blue-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13h3l8-8a2.828 2.828 0 00-4-4l-8 8v3h3z" /></svg>
            Edit
          </a>
                    <?php endif; ?>
                    <?php if (strpos($document['mimetype'], 'image/') === 0 || $document['mimetype'] === 'application/pdf'): ?>
          <a href="<?= base_url('admin/documents/preview/' . $document['id']) ?>" target="_blank" class="bg-gradient-to-r from-indigo-500 to-blue-500 text-white px-6 py-2 rounded-full font-bold shadow-md hover:from-indigo-600 hover:to-blue-600 transition-all flex items-center gap-2 focus:outline-none focus:ring-2 focus:ring-blue-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
            Preview
                        </a>
                    <?php endif; ?>
        <a href="<?= base_url('admin/documents/download/' . $document['id']) ?>" class="bg-gradient-to-r from-emerald-500 to-teal-400 text-white px-6 py-2 rounded-full font-bold shadow-md hover:from-emerald-600 hover:to-teal-500 transition-all flex items-center gap-2 focus:outline-none focus:ring-2 focus:ring-emerald-200">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" /></svg>
                        Download
                    </a>
        <a href="<?= base_url('admin/documents/share/' . $document['id']) ?>" class="bg-gradient-to-r from-orange-500 to-amber-400 text-white px-6 py-2 rounded-full font-bold shadow-md hover:from-orange-600 hover:to-amber-500 transition-all flex items-center gap-2 focus:outline-none focus:ring-2 focus:ring-orange-200">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" /></svg>
          Share
        </a>
        <a href="<?= base_url('admin/documents/version-history/' . $document['id']) ?>" class="bg-gradient-to-r from-purple-500 to-pink-400 text-white px-6 py-2 rounded-full font-bold shadow-md hover:from-purple-600 hover:to-pink-500 transition-all flex items-center gap-2 focus:outline-none focus:ring-2 focus:ring-purple-200">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
          Version History
                    </a>
                </div>
      <!-- Info Grid -->
      <div class="p-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <div class="space-y-4">
            <div class="flex items-center gap-3">
              <span class="font-semibold text-green-700">Type:</span>
              <span class="text-gray-800"> <?= esc($document['mimetype']) ?> </span>
            </div>
            <div class="flex items-center gap-3">
              <span class="font-semibold text-green-700">Size:</span>
              <span class="text-gray-800"> <?= number_format($document['filesize']/1024, 2) ?> KB </span>
                    </div>
            <div class="flex items-center gap-3">
              <span class="font-semibold text-green-700">Document ID:</span>
              <span class="text-gray-800"> #<?= $document['id'] ?> </span>
                    </div>
                    <?php if (!empty($document['tags'])): ?>
            <div class="flex items-center gap-3 flex-wrap">
              <span class="font-semibold text-green-700">Tags:</span>
                            <?php foreach (explode(',', $document['tags']) as $tag): ?>
                <span class="inline-block bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-semibold shadow-sm">#<?= esc(trim($tag)) ?></span>
                            <?php endforeach; ?>
                        </div>
            <?php endif; ?>
            <?php if (!empty($document['description'])): ?>
            <div class="flex items-start gap-3">
              <span class="font-semibold text-green-700 mt-1">Description:</span>
              <span class="text-gray-800"> <?= esc($document['description']) ?> </span>
                    </div>
                    <?php endif; ?>
          </div>
          <div class="space-y-4">
            <div class="flex items-center gap-3">
              <span class="font-semibold text-green-700">Upload Date:</span>
              <span class="text-gray-800"> <?= date('M j, Y g:i A', strtotime($document['uploaded_at'])) ?> </span>
            </div>
            
            <!-- Visibility Information -->
            <div class="bg-gradient-to-r from-blue-50 to-green-50 rounded-lg p-4 space-y-3 border border-green-200">
              <h4 class="font-semibold text-green-800 flex items-center gap-2">
                <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                Visibility Information
              </h4>
              <div class="flex items-center gap-3">
                <span class="font-semibold text-green-700">Visible To:</span>
                <span class="px-3 py-1 rounded-lg text-sm font-semibold
                  <?php 
                    if (($document['visibility'] ?? '') === 'pederasyon') echo 'bg-purple-100 text-purple-800'; 
                    elseif (($document['visibility'] ?? '') === 'sk') echo 'bg-blue-100 text-blue-800'; 
                    else echo 'bg-green-100 text-green-800'; 
                  ?>">
                  <?= strtoupper($document['visibility'] ?? 'N/A') ?>
                </span>
              </div>
              <div class="flex items-center gap-3">
                <span class="font-semibold text-green-700">Scope:</span>
                <span class="text-gray-800">
                  <?php if (($document['visibility_scope'] ?? 'all') === 'all'): ?>
                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 text-blue-700 rounded">
                      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      City-wide
                    </span>
                  <?php else: ?>
                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-50 text-green-700 rounded">
                      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                      </svg>
                      Specific Barangay
                    </span>
                  <?php endif; ?>
                </span>
              </div>
              <?php if (($document['visibility_scope'] ?? 'all') === 'specific_barangay' && !empty($document['barangay_id'])): ?>
              <div class="flex items-center gap-3">
                <span class="font-semibold text-green-700">Barangay:</span>
                <span class="text-gray-900 font-medium">
                  <?php 
                    $docModel = new \App\Models\DocumentModel();
                    $barangayName = $docModel->getBarangayName($document['barangay_id']);
                    echo esc($barangayName ?? 'Unknown');
                  ?>
                </span>
              </div>
              <?php endif; ?>
            </div>
            
            <div class="flex items-center gap-3">
              <span class="font-semibold text-green-700">Downloadable:</span>
              <span class="text-gray-800"> <?= ($document['downloadable'] ?? 1) ? 'Yes' : 'No' ?> </span>
                </div>
            </div>
        </div>
    </div>
    </div>
                            </div>
                            </div>

<script>
        function approveDocument(id) {
    if (confirm('Are you sure you want to approve this document?')) {
        fetch(`<?= base_url('admin/documents/approve/') ?>${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
            }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                location.reload();
                } else {
                alert('Error: ' + data.message);
            }
        });
    }
}

        function rejectDocument(id) {
            const reason = prompt('Please provide a reason for rejection:');
    if (reason !== null) {
        fetch(`<?= base_url('admin/documents/reject/') ?>${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
            body: JSON.stringify({ reason: reason })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                location.reload();
                } else {
                alert('Error: ' + data.message);
            }
        });
    }
}
    </script>
