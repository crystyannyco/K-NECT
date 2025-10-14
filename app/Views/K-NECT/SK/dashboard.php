<!-- Main content wrapper (aligned beside fixed sidebar) -->
<div class="flex-1 flex flex-col min-h-0 ml-0 lg:ml-64 pt-16 overflow-x-hidden">
    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
        <div class="max-w-7xl mx-auto p-6 w-full">
            <div class="space-y-8 overflow-x-hidden">
    <!-- Welcome Section (Unified Style) -->
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 border-l-4 border-blue-600 relative overflow-hidden">
        <div class="flex items-start justify-between gap-6">
            <div class="space-y-1">
                <h1 class="text-xl font-bold leading-snug">Welcome back, <?= esc(session('username')) ?>!</h1>
                <p class="text-sm text-gray-700">You're logged in as <span class="font-semibold">SK Admin</span></p>
                <p class="text-xs text-gray-500">Manage your documents and collaborate with your team.</p>
                <div class="pt-3 flex flex-wrap gap-2">
                    <a href="<?= base_url('admin/documents/upload') ?>" class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded-md text-xs font-medium shadow-sm transition"><i class="fa-solid fa-file-arrow-up"></i> Upload</a>
                    <a href="<?= base_url('/bulletin/create') ?>" class="inline-flex items-center gap-1.5 bg-white border border-blue-200 hover:bg-blue-50 text-blue-600 px-4 py-1.5 rounded-md text-xs font-medium shadow-sm transition"><i class="fa-solid fa-plus"></i> Bulletin</a>
                    <a href="<?= base_url('/bulletin') ?>" class="inline-flex items-center gap-1.5 bg-white border border-blue-200 hover:bg-blue-50 text-blue-600 px-4 py-1.5 rounded-md text-xs font-medium shadow-sm transition"><i class="fa-solid fa-grid"></i> Manage</a>
                </div>
            </div>
            <div class="hidden md:flex items-start gap-3">
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                </div>
                <span class="inline-flex items-center px-2 py-0.5 rounded bg-blue-100 text-blue-700 text-[10px] font-semibold h-fit">Beta</span>
            </div>
        </div>
    </div>

    <!-- Stats Cards (Unified Slim) -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <?php $skStats = [
            ['label'=>'My Documents','value'=>$totalDocuments ?? 0,'color'=>'blue','icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2m4 0v-2a6 6 0 00-6-6V5a2 2 0 10-4 0v4a6 6 0 00-6 6v2h16z"/>'],
            ['label'=>'Pending','value'=>$pendingApproval ?? 0,'color'=>'amber','icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
            ['label'=>'Approved','value'=>$approvedDocuments ?? 0,'color'=>'emerald','icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>'],
            ['label'=>'Shared','value'=>$sharedDocuments ?? 0,'color'=>'indigo','icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>'],
        ];
        $colorMap = [
            'blue'=>'bg-blue-50 text-blue-600','amber'=>'bg-amber-50 text-amber-600','emerald'=>'bg-emerald-50 text-emerald-600','indigo'=>'bg-indigo-50 text-indigo-600'
        ]; ?>
        <?php foreach($skStats as $s): $clr=$colorMap[$s['color']]; ?>
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
            <div class="space-y-10">
    <!-- Bulletin Overview Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-2xl p-8 shadow-lg flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div class="space-y-2">
            <h1 class="text-3xl font-bold">Bulletin Overview</h1>
            <p class="text-blue-100 text-sm">A quick glance at featured posts, upcoming events, and documents.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="<?= base_url('/bulletin/create') ?>" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 px-4 py-2 rounded-lg text-sm font-semibold"><i class="fa-solid fa-plus"></i> New Post</a>
            <a href="<?= base_url('admin/documents/upload') ?>" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 px-4 py-2 rounded-lg text-sm font-semibold"><i class="fa-solid fa-file-arrow-up"></i> Upload Doc</a>
            <a href="<?= base_url('/bulletin') ?>" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 px-4 py-2 rounded-lg text-sm font-semibold"><i class="fa-solid fa-grid"></i> Manage Posts</a>
        </div>
    </div>

    <!-- Featured / Urgent Posts -->
    <?php if(!empty($featuredPosts) || !empty($urgentPosts)): ?>
    <section class="space-y-6">
        <?php if(!empty($urgentPosts)): ?>
        <div class="rounded-xl border border-red-200 bg-red-50/70 p-5">
            <h2 class="text-sm font-bold uppercase tracking-wide text-red-700 mb-3 flex items-center gap-2"><i class="fa-solid fa-triangle-exclamation"></i> Urgent</h2>
            <div class="grid gap-4 md:grid-cols-3">
                <?php foreach($urgentPosts as $u): ?>
                <article class="bg-white rounded-lg border border-red-200 p-4 shadow-sm hover:shadow-md transition">
                    <h3 class="font-semibold text-red-800 text-sm mb-1 line-clamp-2"><a href="<?= base_url('/bulletin/view/'.$u['id']) ?>" class="hover:underline"><?= esc($u['title']) ?></a></h3>
                    <p class="text-xs text-gray-600 mb-2 line-clamp-2"><?= esc($u['excerpt'] ?: substr(strip_tags($u['content']??''),0,100).'...') ?></p>
                    <div class="text-[11px] text-gray-500 flex items-center gap-2"><i class="fa-regular fa-calendar"></i> <?= !empty($u['published_at'])?date('M d, Y',strtotime($u['published_at'])):'' ?></div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        <?php if(!empty($featuredPosts)): ?>
        <div class="grid gap-5 lg:grid-cols-3 auto-rows-[220px]">
            <?php $fpPrimary = $featuredPosts[0]; $fpOthers = array_slice($featuredPosts,1,4); ?>
            <article class="relative rounded-xl overflow-hidden border border-blue-200 shadow-sm lg:col-span-2 lg:row-span-2 bg-white">
                <?php $fpImg = !empty($fpPrimary['featured_image'])? base_url('uploads/bulletin/'.$fpPrimary['featured_image']) : null; ?>
                <div class="absolute inset-0 <?= $fpImg? '' : 'bg-gradient-to-br from-blue-600 to-blue-800' ?>">
                    <?php if($fpImg): ?><img src="<?= $fpImg ?>" alt="<?= esc($fpPrimary['title']) ?>" class="w-full h-full object-cover"><?php endif; ?>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/25 to-transparent"></div>
                </div>
                <div class="absolute top-3 left-3 flex gap-1 flex-wrap">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-100 text-blue-800">Featured</span>
                    <?php if(!empty($fpPrimary['category_name'])): ?><span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-white/90 text-gray-800"><?= esc($fpPrimary['category_name']) ?></span><?php endif; ?>
                </div>
                <div class="absolute bottom-4 left-4 right-4 text-white">
                    <h3 class="text-2xl font-bold leading-tight mb-2 line-clamp-2"><a href="<?= base_url('/bulletin/view/'.$fpPrimary['id']) ?>" class="hover:underline"><?= esc($fpPrimary['title']) ?></a></h3>
                    <p class="hidden md:block text-sm text-white/90 mb-2 line-clamp-2"><?= esc($fpPrimary['excerpt'] ?: substr(strip_tags($fpPrimary['content']??''),0,140).'...') ?></p>
                    <div class="text-xs text-white/80 flex items-center gap-2"><i class="fa-regular fa-calendar"></i> <?= !empty($fpPrimary['published_at'])?date('M d, Y',strtotime($fpPrimary['published_at'])):'' ?></div>
                </div>
            </article>
            <?php foreach($fpOthers as $f): ?>
            <article class="relative rounded-xl overflow-hidden border border-blue-200 shadow-sm bg-white">
                <?php $fi = !empty($f['featured_image'])? base_url('uploads/bulletin/'.$f['featured_image']) : null; ?>
                <div class="absolute inset-0 <?= $fi? '' : 'bg-gradient-to-br from-blue-600 to-blue-800' ?>">
                    <?php if($fi): ?><img src="<?= $fi ?>" alt="<?= esc($f['title']) ?>" class="w-full h-full object-cover"><?php endif; ?>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/65 via-black/15 to-transparent"></div>
                </div>
                <div class="absolute top-2 left-2 flex gap-1 flex-wrap">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-100 text-blue-800">Featured</span>
                    <?php if(!empty($f['category_name'])): ?><span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-white/90 text-gray-800"><?= esc($f['category_name']) ?></span><?php endif; ?>
                </div>
                <div class="absolute bottom-2 left-2 right-2 text-white">
                    <h4 class="text-sm font-semibold leading-snug line-clamp-2"><a href="<?= base_url('/bulletin/view/'.$f['id']) ?>" class="hover:underline"><?= esc($f['title']) ?></a></h4>
                    <div class="mt-1 text-[10px] text-white/80 flex items-center gap-1"><i class="fa-regular fa-calendar"></i> <?= !empty($f['published_at'])?date('M d, Y',strtotime($f['published_at'])):'' ?></div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </section>
    <?php endif; ?>

    <!-- Upcoming Events -->
    <?php if(!empty($upcomingEvents)): ?>
    <section class="space-y-4">
        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2"><i class="fa-regular fa-calendar-days text-blue-600"></i> Upcoming Events</h2>
        <div class="grid gap-5 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            <?php foreach($upcomingEvents as $event): ?>
                <?php 
                    // Use start_datetime field from events table (no event_date column)
                    $date = !empty($event['start_datetime']) ? strtotime($event['start_datetime']) : null;
                    $month = $date ? date('M',$date):''; $day=$date?date('d',$date):'';
                    $banner = !empty($event['event_banner'])? base_url('uploads/event/'.$event['event_banner']) : null;
                ?>
                <article class="overflow-hidden rounded-xl border bg-white shadow-sm hover:shadow-md transition">
                    <div class="relative h-36 w-full text-white">
                        <?php if($banner): ?><img src="<?= $banner ?>" alt="<?= esc($event['title']??'Event') ?>" class="w-full h-full object-cover"><div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div><?php else: ?><div class="absolute inset-0 bg-gradient-to-br from-blue-600 to-blue-800"></div><?php endif; ?>
                        <div class="absolute top-3 left-3 text-center bg-white/10 rounded-lg px-2 py-1">
                            <div class="text-xs uppercase tracking-wide text-white/90"><?= esc($month) ?></div>
                            <div class="text-lg font-bold -mt-1"><?= esc($day) ?></div>
                        </div>
                        <div class="absolute bottom-3 left-3 right-3"><p class="text-sm font-semibold line-clamp-2"><?= esc($event['title'] ?? 'Scheduled Event') ?></p></div>
                    </div>
                    <div class="p-4">
                        <div class="flex items-center justify-between text-xs text-gray-600">
                            <span><i class="fa-regular fa-clock mr-1"></i><?= $date?date('M d, Y g:i A',$date):'' ?></span>
                            <span class="inline-flex items-center gap-1 text-blue-700"><i class="fa-regular fa-star"></i>Upcoming</span>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Recent Documents Carousel -->
    <?php if(!empty($recentDocuments)): ?>
    <section class="space-y-4">
        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2"><i class="fa-regular fa-folder-open text-blue-600"></i> Uploaded Documents</h2>
        <div class="doc-carousel relative">
            <div class="doc-carousel-viewport overflow-x-auto">
                <div class="doc-carousel-track flex gap-5">
                    <?php foreach($recentDocuments as $doc): ?>
                        <?php 
                            $filePath = $doc['file_path'] ?? ''; $ext=strtolower(pathinfo($filePath,PATHINFO_EXTENSION));
                            $isImage=in_array($ext,['jpg','jpeg','png','gif','webp']); $docUrl=base_url($filePath);
                            $icon='fa-file-lines text-gray-500';
                            if($ext==='pdf') $icon='fa-file-pdf text-blue-600'; elseif(in_array($ext,['doc','docx'])) $icon='fa-file-word text-blue-600'; elseif(in_array($ext,['xls','xlsx','csv'])) $icon='fa-file-excel text-blue-600'; elseif(in_array($ext,['ppt','pptx'])) $icon='fa-file-powerpoint text-blue-600';
                        ?>
                        <article class="w-64 sm:w-72 flex-shrink-0 bg-white rounded-xl border shadow-sm overflow-hidden hover:shadow-md transition">
                            <div class="relative h-36 w-full overflow-hidden bg-gray-100 flex items-center justify-center">
                                <?php if($isImage): ?><img src="<?= $docUrl ?>" alt="<?= esc($doc['filename'] ?? 'Document') ?>" class="w-full h-full object-cover"><div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div><?php else: ?><i class="fa-regular <?= $icon ?> text-5xl"></i><?php endif; ?>
                                <div class="absolute top-3 left-3"><span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-white text-gray-800"><?= strtoupper($ext ?: 'FILE') ?></span></div>
                                <div class="absolute bottom-3 right-3"><a href="<?= esc($docUrl) ?>" target="_blank" class="inline-flex items-center px-3 py-1.5 rounded-md bg-white/90 text-gray-800 text-xs font-medium hover:bg-white"><i class="fa-regular fa-eye mr-1"></i> Open</a></div>
                            </div>
                            <div class="p-4">
                                <h3 class="text-sm font-semibold text-gray-900 truncate" title="<?= esc($doc['filename'] ?? 'Untitled Document') ?>"><?= esc($doc['filename'] ?? 'Untitled Document') ?></h3>
                                <div class="mt-2 flex items-center justify-between flex-wrap gap-2 text-xs text-gray-500">
                                    <span class="flex-shrink-0"><?= !empty($doc['created_at'])?date('M d, Y',strtotime($doc['created_at'])):'' ?></span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-medium flex-shrink-0 <?= ($doc['visibility'] ?? 'sk')==='pederasyon'?'bg-purple-100 text-purple-700':(($doc['visibility'] ?? 'sk')==='sk'?'bg-blue-100 text-blue-700':'bg-green-100 text-green-700') ?>">
                                        <?= ucfirst($doc['visibility'] ?? 'SK') ?>
                                    </span>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
            </div> <!-- /.space-y-10 -->
        </div><!-- /.max-w wrapper -->
    </main>
</div>


