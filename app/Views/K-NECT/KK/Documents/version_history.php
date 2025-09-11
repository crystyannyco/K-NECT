
<?php $title = 'Version History: ' . esc($doc['filename']); ?>
<div class="max-w-3xl mx-auto p-8 mt-10 bg-white/90 shadow-2xl rounded-2xl border border-blue-100">
    <h1 class="text-2xl font-extrabold text-blue-800 mb-8 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
        Version History for <span class="ml-2 text-blue-700">"<?= esc($doc['filename']) ?>"</span>
    </h1>
    <div class="overflow-x-auto rounded-xl shadow-lg">
        <table class="min-w-full bg-white rounded-xl">
            <thead class="bg-blue-50 sticky top-0 z-10">
                <tr>
                    <th class="py-3 px-4 text-left font-semibold text-blue-800">Version</th>
                    <th class="py-3 px-4 text-left font-semibold text-blue-800">Uploaded At</th>
                    <th class="py-3 px-4 text-left font-semibold text-blue-800">User</th>
                    <th class="py-3 px-4 text-left font-semibold text-blue-800">Size</th>
                    <th class="py-3 px-4 text-left font-semibold text-blue-800">Type</th>
                    <th class="py-3 px-4 text-center font-semibold text-blue-800">Download</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($versions)): ?>
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-400 italic text-lg flex flex-col items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-100" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h4m0 0V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2h4" /></svg>
                            No version history found.
                        </td>
                    </tr>
                <?php else: ?>
                <?php foreach ($versions as $i => $ver): ?>
                    <tr class="<?= $i === 0 ? 'bg-blue-50 font-bold' : ($i % 2 === 0 ? 'bg-gray-50' : 'bg-white') ?> hover:bg-blue-100 transition">
                        <td class="py-3 px-4">
                            <?= esc($ver['version_number']) ?>
                            <?php if ($i === 0): ?>
                                <span class="ml-2 inline-block px-2 py-1 rounded text-xs bg-green-100 text-green-800 font-semibold">Current</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-4"> <?= esc($ver['uploaded_at']) ?> </td>
                        <td class="py-3 px-4"> <?= esc($ver['uploaded_by']) ?> </td>
                        <td class="py-3 px-4"> <?= number_format($ver['filesize']/1024, 2) ?> KB </td>
                        <td class="py-3 px-4"> <?= esc($ver['mimetype']) ?> </td>
                        <td class="py-3 px-4 text-center">
                            <a href="/admin/documents/download/<?= $ver['id'] ?>" class="inline-flex items-center gap-1 text-blue-700 hover:bg-blue-50 px-2 py-1 rounded transition" title="Download this version">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" /></svg>
                                Download
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="mt-8">
        <a href="/admin/documents" class="bg-gray-200 text-gray-700 px-5 py-2 rounded-lg font-semibold shadow hover:bg-gray-300 transition flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>Back to Documents</a>
    </div>
</div>
 
