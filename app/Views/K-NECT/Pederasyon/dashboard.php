<?php
// Helper to resolve image path safely
if (!function_exists('ped_resolve_image')) {
    function ped_resolve_image($path) {
        if (empty($path)) return null;
        $trim = ltrim($path, '/');
        // Absolute URL
        if (preg_match('~^https?://~i', $trim)) return $trim;
        // If file exists relative to public
        $full = FCPATH . $trim;
        if (is_file($full)) {
            return base_url($trim);
        }
        // Sometimes stored with leading 'public/' – strip it
        if (str_starts_with($trim, 'public/')) {
            $alt = substr($trim, 7);
            if (is_file(FCPATH . $alt)) return base_url($alt);
        }
        // Attempt common uploads prefixes
        $candidates = [
            'uploads/' . $trim,
            'uploads/bulletin/' . $trim,
        ];
        foreach ($candidates as $cand) {
            if (is_file(FCPATH . $cand)) return base_url($cand);
        }
        return null; // Unresolved
    }
}
?>
<div class="flex-1 flex flex-col min-h-0 ml-64 pt-16">
    <main class="flex-1 overflow-auto px-6 pb-10 pt-6 bg-gray-50">
        <!-- Welcome Card (SK-style) -->
        <div class="mb-6">
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 border-l-4 border-blue-600 relative overflow-hidden">
                <div class="flex items-start justify-between gap-4">
                    <div class="space-y-1">
                        <h1 class="text-xl font-bold leading-snug">Welcome back, <?= esc($username ?? 'User') ?>!</h1>
                        <p class="text-sm text-gray-700">You're logged in as <span class="font-semibold">Federation Admin</span></p>
                        <p class="text-xs text-gray-500">Manage federation bulletins, events, and shared documents.</p>
                        <div class="pt-3 flex flex-wrap gap-2">
                            <a href="<?= base_url('pederasyon/analytics') ?>" class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded-md text-xs font-medium shadow-sm transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3a1 1 0 00-1 1v12.382l-1.447-1.724a1 1 0 10-1.53 1.284l3 3.571a1 1 0 001.53 0l3-3.571a1 1 0 10-1.53-1.284L12 16.382V4a1 1 0 00-1-1z"/></svg>
                                Analytics
                            </a>
                            <a href="<?= base_url('bulletin/create') ?>" class="inline-flex items-center gap-1.5 bg-white border border-blue-200 hover:bg-blue-50 text-blue-600 px-4 py-1.5 rounded-md text-xs font-medium shadow-sm transition">
                                <i class="fa-solid fa-plus"></i>
                                Bulletin
                            </a>
                            <a href="<?= base_url('admin/documents/upload') ?>" class="inline-flex items-center gap-1.5 bg-white border border-blue-200 hover:bg-blue-50 text-blue-600 px-4 py-1.5 rounded-md text-xs font-medium shadow-sm transition">
                                <i class="fa-solid fa-file-arrow-up"></i>
                                Document
                            </a>
                        </div>
                    </div>
                    <div class="hidden md:flex items-center justify-center">
                        <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
<?php
// Helper to resolve image path safely
if (!function_exists('ped_resolve_image')) {
    function ped_resolve_image($path) {
        if (empty($path)) return null;
        $trim = ltrim($path, '/');
        // Absolute URL
        if (preg_match('~^https?://~i', $trim)) return $trim;
        // If file exists relative to public
        $full = FCPATH . $trim;
        if (is_file($full)) {
            return base_url($trim);
        }
        // Sometimes stored with leading 'public/' – strip it
        if (str_starts_with($trim, 'public/')) {
            $alt = substr($trim, 7);
            if (is_file(FCPATH . $alt)) return base_url($alt);
        }
        // Attempt common uploads prefixes
        $candidates = [
            'uploads/' . $trim,
            'uploads/bulletin/' . $trim,
        ];
        foreach ($candidates as $cand) {
            if (is_file(FCPATH . $cand)) return base_url($cand);
        }
        return null; // Unresolved
    }
}
?>
<div class="flex-1 flex flex-col min-h-0 ml-64 pt-16">
    <main class="flex-1 overflow-auto px-6 pb-10 pt-6 bg-gray-50">
        <!-- Welcome Card (SK-style) -->
        <div class="mb-6">
            <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 border-l-4 border-blue-600 relative overflow-hidden">
                <div class="flex items-start justify-between gap-4">
                    <div class="space-y-1">
                        <h1 class="text-xl font-bold leading-snug">Welcome back, <?= esc($username ?? 'User') ?>!</h1>
                        <p class="text-sm text-gray-700">You're logged in as <span class="font-semibold">Federation Admin</span></p>
                        <p class="text-xs text-gray-500">Manage federation bulletins, events, and shared documents.</p>
                        <div class="pt-3 flex flex-wrap gap-2">
                            <a href="<?= base_url('pederasyon/analytics') ?>" class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded-md text-xs font-medium shadow-sm transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3a1 1 0 00-1 1v12.382l-1.447-1.724a1 1 0 10-1.53 1.284l3 3.571a1 1 0 001.53 0l3-3.571a1 1 0 10-1.53-1.284L12 16.382V4a1 1 0 00-1-1z"/></svg>
                                Analytics
                            </a>
                            <a href="<?= base_url('bulletin/create') ?>" class="inline-flex items-center gap-1.5 bg-white border border-blue-200 hover:bg-blue-50 text-blue-600 px-4 py-1.5 rounded-md text-xs font-medium shadow-sm transition">
                                <i class="fa-solid fa-plus"></i>
                                Bulletin
                            </a>
                            <a href="<?= base_url('admin/documents/upload') ?>" class="inline-flex items-center gap-1.5 bg-white border border-blue-200 hover:bg-blue-50 text-blue-600 px-4 py-1.5 rounded-md text-xs font-medium shadow-sm transition">
                                <i class="fa-solid fa-file-arrow-up"></i>
                                Document
                            </a>
                        </div>
                    </div>
                    <div class="hidden md:flex items-center justify-center">
                        <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <span class="absolute top-2 right-2 inline-flex items-center px-2 py-0.5 rounded bg-blue-100 text-blue-700 text-[10px] font-semibold">Beta</span>
            </div>
        </div>

    <!-- Stats Cards (Clean Slim Layout) -->
    <div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-4 gap-4 mb-10">
            <?php
                $pedStats = [
                    [
                        'label' => 'My Documents',
                        'value' => $totalDocuments ?? 0,
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2m4 0v-2a6 6 0 00-6-6V5a2 2 0 10-4 0v4a6 6 0 00-6 6v2h16z"/>' ,
                        'color' => 'blue'
                    ],
                    [
                        'label' => 'Shared With Me',
                        'value' => $sharedDocuments ?? 0,
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>',
                        'color' => 'indigo'
                    ],
                    [
                        'label' => 'Events',
                        'value' => isset($upcomingEvents) ? count($upcomingEvents) : 0,
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>',
                        'color' => 'violet'
                    ],
                    [
                        'label' => 'Featured Posts',
                        'value' => isset($featuredPosts) ? count($featuredPosts) : 0,
                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.802 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.802-2.034a1 1 0 00-1.175 0l-2.802 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>',
                        'color' => 'blue'
                    ],
                ];
                $colorMap = [
                    'blue'=>'bg-blue-50 text-blue-600','amber'=>'bg-amber-50 text-amber-600','emerald'=>'bg-emerald-50 text-emerald-600','indigo'=>'bg-indigo-50 text-indigo-600','violet'=>'bg-violet-50 text-violet-600','rose'=>'bg-rose-50 text-rose-600'
                ]; // rose retained for backward compatibility if reintroduced
            ?>
            <?php foreach($pedStats as $s): $clr=$colorMap[$s['color']]; ?>
                <div class="group relative bg-white rounded-xl p-4 border border-transparent shadow-sm hover:shadow-md hover:border-gray-200 transition">
                    <div class="flex items-start gap-3">
                        <div class="shrink-0 w-12 h-12 rounded-lg flex items-center justify-center <?= $clr ?>">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><?= $s['icon'] ?></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[11px] tracking-wide font-medium text-gray-500 uppercase truncate"><?= esc($s['label']) ?></p>
                            <p class="mt-1 text-lg font-semibold text-gray-900 leading-none"><?= esc($s['value']) ?></p>
                        </div>
                    </div>
                    <div class="absolute inset-x-0 bottom-0 h-px bg-gradient-to-r from-transparent via-gray-100 to-transparent"></div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Bulletin Overview Banner -->
        <div class="relative mb-10 overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 via-blue-500 to-indigo-600 p-8 text-white shadow">
            <div class="relative z-10 max-w-2xl">
                <h2 class="text-2xl sm:text-3xl font-bold mb-2">Bulletin Overview</h2>
                <p class="text-blue-100 text-sm sm:text-base">Featured and urgent posts across all barangays (public + city visibility).</p>
            </div>
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
            <!-- Urgent Posts -->
            <div class="space-y-5 lg:col-span-1">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">Urgent Posts
                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-600"><?= isset($urgentPosts) ? count($urgentPosts) : 0 ?></span>
                    </h3>
                </div>
                <div class="space-y-4">
                    <?php if (!empty($urgentPosts)): foreach ($urgentPosts as $post): ?>
                        <a href="<?= base_url('bulletin/view/' . $post['id']) ?>" class="block group">
                            <div class="p-4 rounded-xl bg-white shadow-sm border border-red-100/60 hover:shadow-md transition relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-r from-red-500/0 via-red-500/0 to-red-500/5 opacity-0 group-hover:opacity-100 transition"></div>
                                <div class="flex items-start space-x-3 relative">
                                    <div class="mt-1">
                                        <span class="inline-flex items-center p-2 rounded-lg bg-red-50 text-red-600 ring-4 ring-red-50">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-semibold text-gray-900 line-clamp-2 group-hover:text-red-600 transition">
                                            <?= esc($post['title'] ?? 'Untitled') ?>
                                        </h4>
                                        <p class="mt-1 text-xs text-gray-500 line-clamp-2">
                                            <?= esc($post['excerpt'] ?? strip_tags(mb_substr($post['content'] ?? '',0,100))) ?>
                                        </p>
                                        <div class="mt-2 flex items-center text-[11px] text-gray-400 space-x-3">
                                            <span><?= isset($post['published_at']) ? date('M d, Y', strtotime($post['published_at'])) : '' ?></span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-red-50 text-red-600 text-[10px] font-medium">URGENT</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; else: ?>
                        <div class="p-6 bg-white border border-dashed border-gray-200 rounded-xl text-center">
                            <p class="text-sm text-gray-500">No urgent posts.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Featured Posts Mosaic -->
            <div class="lg:col-span-2 space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Featured Posts</h3>
                    <a href="<?= base_url('bulletin') ?>" class="text-sm text-blue-600 hover:text-blue-700 font-medium">View All</a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php if (!empty($featuredPosts)): foreach ($featuredPosts as $post): ?>
                        <?php $imgSrc = ped_resolve_image($post['featured_image'] ?? null); ?>
                        <a href="<?= base_url('bulletin/view/' . $post['id']) ?>" class="group block" title="View post">
                            <div class="relative h-48 rounded-xl overflow-hidden bg-gradient-to-br from-gray-200 to-gray-300 shadow-sm ring-1 ring-gray-200/60">
                                <?php if ($imgSrc): ?>
                                    <img src="<?= esc($imgSrc) ?>" loading="lazy" decoding="async" alt="<?= esc($post['title'] ?? 'Featured Post') ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" onerror="this.style.display='none'" />
                                <?php else: ?>
                                    <div class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 text-xs gap-2">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7l9-4 9 4-9 4-9-4zm0 6l9 4 9-4M3 7v6m18 0V7m-9 4v6"/></svg>
                                        <span>No Image</span>
                                    </div>
                                <?php endif; ?>
                                <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-gray-900/10 to-transparent pointer-events-none"></div>
                                <div class="absolute top-2 left-2 flex items-center gap-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-blue-600/90 text-white text-[10px] font-semibold shadow">FEATURED</span>
                                    <?php if (!empty($post['category_name'])): ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-white/10 text-white backdrop-blur text-[10px] font-medium border border-white/20">
                                            <?= esc($post['category_name']) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="absolute bottom-0 left-0 right-0 p-3">
                                    <h4 class="text-sm font-semibold text-white line-clamp-2 group-hover:text-blue-200 transition tracking-tight">
                                        <?= esc($post['title']) ?>
                                    </h4>
                                    <div class="mt-1 flex items-center justify-between text-[11px] text-gray-300">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <?= isset($post['published_at']) ? date('M d', strtotime($post['published_at'])) : '' ?>
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A2 2 0 0021 6.01V5a2 2 0 00-1.106-1.789L15 1 9 3.21 3.106 1.211A2 2 0 002 3v3.01a2 2 0 001.106 1.714L7 10l-4 2 4 2-4.553 2.276A2 2 0 002 18v1a2 2 0 001.106 1.789L9 23l6-2.21 5.894 1.999A2 2 0 0021 21v-3.01a2 2 0 00-1.106-1.714L15 14l4-2-4-2z"/></svg>
                                            <?= esc($post['view_count'] ?? 0) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; else: ?>
                        <div class="col-span-full p-6 bg-white border border-dashed border-gray-200 rounded-xl text-center">
                            <p class="text-sm text-gray-500">No featured posts available.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Upcoming Events & Recent Documents (Enhanced) -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Events Column -->
            <section class="xl:col-span-2 space-y-5">
                <header class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2"><svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> Upcoming Events</h3>
                    <a href="<?= base_url('events') ?>" class="text-sm font-medium text-blue-600 hover:text-blue-700">View All</a>
                </header>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <?php if (!empty($upcomingEvents)): foreach ($upcomingEvents as $event): ?>
                        <?php $startTs = strtotime($event['start_datetime']); ?>
                        <?php
                            $banner = null;
                            if (!empty($event['event_banner'])) {
                                $b = $event['event_banner'];
                                if (filter_var($b, FILTER_VALIDATE_URL)) {
                                    $banner = $b;
                                } else {
                                    // Common upload path pattern used elsewhere
                                    $tryPath = 'uploads/event/' . ltrim($b,'/');
                                    $banner = base_url($tryPath);
                                }
                            }
                        ?>
                        <a href="<?= base_url('events') ?>" class="block focus:outline-none focus:ring-2 focus:ring-blue-500/50 rounded-xl">
                        <article class="group relative bg-white rounded-xl border border-gray-200/70 hover:border-blue-200 shadow-sm hover:shadow-md transition flex flex-col overflow-hidden">
                            <?php if($banner): ?>
                                <div class="relative w-full h-24 overflow-hidden">
                                    <img src="<?= esc($banner) ?>" alt="<?= esc($event['title'] ?? 'Event Banner') ?>" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900/40 via-transparent to-transparent opacity-60 group-hover:opacity-70 transition"></div>
                                    <span class="absolute top-2 left-2 px-2 py-0.5 rounded-md bg-white/80 backdrop-blur text-[10px] font-medium text-gray-700 shadow">Banner</span>
                                </div>
                            <?php endif; ?>
                            <div class="flex items-start gap-3 p-4 pb-3 <?= $banner ? 'pt-3' : '' ?>">
                                <div class="flex flex-col items-center -space-y-0.5">
                                    <span class="px-2 py-1 rounded-full bg-blue-50 text-blue-600 text-[11px] font-medium leading-none tracking-wide"><?= date('M d',$startTs) ?></span>
                                    <span class="text-[10px] text-gray-400 mt-1"><?= date('g:i A',$startTs) ?></span>
                                </div>
                                <div class="min-w-0 flex-1 space-y-1">
                                    <h4 class="text-sm font-semibold text-gray-900 line-clamp-2 group-hover:text-blue-600 transition"><?= esc($event['title'] ?? 'Untitled Event') ?></h4>
                                    <?php if(!empty($event['description'])): ?><p class="text-xs text-gray-500 line-clamp-2"><?= esc($event['description']) ?></p><?php endif; ?>
                                </div>
                            </div>
                            <div class="mt-auto px-4 pb-3 flex items-center justify-between text-[11px] text-gray-400">
                                <span class="inline-flex items-center gap-1 truncate max-w-[70%]">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 12.414A4 4 0 1112.414 11l4.243 4.243a1 1 0 010 1.414l-.707.707a1 1 0 01-1.414 0L10.586 15"/></svg>
                                    <span class="truncate"><?= esc($event['location'] ?? 'TBD') ?></span>
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-blue-50 text-blue-600 font-medium">Event</span>
                            </div>
                            <div class="absolute inset-x-0 bottom-0 h-0.5 bg-gradient-to-r from-transparent via-blue-100 to-transparent opacity-0 group-hover:opacity-100 transition"></div>
                        </article>
                        </a>
                    <?php endforeach; else: ?>
                        <div class="col-span-full">
                            <div class="p-8 bg-white border border-dashed border-gray-200 rounded-xl text-center">
                                <div class="mx-auto w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center mb-3">
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <p class="text-sm font-medium text-gray-700">No upcoming events</p>
                                <p class="text-xs text-gray-500 mt-1">Events you add will appear here.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Recent Documents Column -->
            <section class="space-y-5">
                <header class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2"><svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2m4 0v-2a6 6 0 00-6-6V5a2 2 0 10-4 0v4a6 6 0 00-6 6v2h16z"/></svg> My Recent Documents</h3>
                    <a href="<?= base_url('admin/documents') ?>" class="text-sm font-medium text-blue-600 hover:text-blue-700">Manage</a>
                </header>
                <div class="space-y-3">
                    <?php if (!empty($recentDocuments)): foreach ($recentDocuments as $doc): ?>
                        <?php $ext = strtolower(pathinfo($doc['file_path'] ?? '', PATHINFO_EXTENSION)); ?>
                        <a href="<?= base_url('admin/documents') ?>" class="block focus:outline-none focus:ring-2 focus:ring-blue-500/50 rounded-xl">
                        <article class="group bg-white border border-gray-200/70 hover:border-blue-200 rounded-xl p-4 shadow-sm hover:shadow-md transition">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-lg bg-gray-50 flex items-center justify-center text-xs font-semibold text-gray-600 group-hover:bg-blue-50 group-hover:text-blue-600 transition uppercase">
                                    <?= esc($ext ?: 'FILE') ?>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-3">
                                        <p class="text-sm font-semibold text-gray-900 truncate group-hover:text-blue-600 transition" title="<?= esc($doc['filename'] ?? 'Untitled') ?>"><?= esc($doc['filename'] ?? 'Untitled') ?></p>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium <?php if(($doc['visibility'] ?? 'pederasyon')==='pederasyon'): ?> bg-purple-50 text-purple-600 <?php elseif(($doc['visibility'] ?? 'pederasyon')==='sk'): ?> bg-blue-50 text-blue-600 <?php else: ?> bg-green-50 text-green-600 <?php endif; ?>"><?= esc(ucfirst($doc['visibility'] ?? 'Pederasyon')) ?></span>
                                    </div>
                                    <div class="mt-1 flex items-center gap-3 text-[11px] text-gray-500 flex-wrap">
                                        <span><?= isset($doc['created_at']) ? date('M d, Y g:i A', strtotime($doc['created_at'])) : '' ?></span>
                                        <span class="inline-flex items-center gap-1"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg><?= strtoupper($ext ?: 'FILE') ?></span>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between">
                                        <button type="button" onclick="window.open('<?= !empty($doc['file_path']) ? base_url($doc['file_path']) : '#' ?>','_blank')" class="text-xs font-medium text-blue-600 hover:text-blue-700 inline-flex items-center gap-1"><i class="fa-regular fa-eye"></i> Open</button>
                                    </div>
                                </div>
                            </div>
                        </article>
                        </a>
                    <?php endforeach; else: ?>
                        <div class="p-8 bg-white border border-dashed border-gray-200 rounded-xl text-center">
                            <div class="mx-auto w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center mb-3">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2m4 0v-2a6 6 0 00-6-6V5a2 2 0 10-4 0v4a6 6 0 00-6 6v2h16z"/></svg>
                            </div>
                            <p class="text-sm font-medium text-gray-700">No documents yet</p>
                            <p class="text-xs text-gray-500 mt-1">Recently uploaded files will appear here.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </main>
</div>
