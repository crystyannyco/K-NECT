
<?php $title = 'Add Category'; ?>
<div class="max-w-md mx-auto p-8 mt-12 bg-white shadow-2xl rounded-2xl border border-blue-100 animate-fade-in">
    <h1 class="text-3xl font-extrabold text-blue-800 mb-8 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 21V7a2 2 0 00-2-2H6a2 2 0 00-2 2v14" /></svg>
        Add Category
    </h1>
    <?php if (!empty($errorMsg)): ?>
        <div class="mb-4 flex items-center gap-2 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg animate-fade-in">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span><?= esc($errorMsg) ?></span>
            <button onclick="this.parentElement.remove()" class="ml-auto text-red-700 hover:text-red-900 text-xl">&times;</button>
        </div>
    <?php endif; ?>
    <form action="<?= base_url('admin/categories/add') ?>" method="post" class="space-y-8">
        <?= csrf_field() ?>
        <div>
            <label class="block font-semibold mb-2 text-blue-700">Category Name</label>
            <input type="text" name="name" maxlength="100" value="<?= esc($oldName ?? '') ?>" class="w-full border border-blue-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none shadow-sm transition" required />
        </div>
        <div class="flex gap-4 mt-6">
            <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-400 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:from-blue-700 hover:to-blue-500 transition-all flex items-center gap-3 text-lg border-2 border-blue-200 hover:border-blue-400 focus:ring-4 focus:ring-blue-200 outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Add
            </button>
            <a href="<?= base_url('admin/categories') ?>" class="bg-gray-100 text-gray-700 px-8 py-3 rounded-xl font-semibold shadow hover:bg-gray-200 transition flex items-center gap-2 border-2 border-gray-200 hover:border-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6" /></svg>
                Cancel
            </a>
        </div>
    </form>
</div>
 
