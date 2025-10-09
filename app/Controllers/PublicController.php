<?php
namespace App\Controllers;

class PublicController extends BaseController
{
    public function index()
    {
        $session = session();
        $userType = $session->get('user_type');
        
        // Note: Public website is accessible to everyone (logged in or not)
        // We don't redirect authenticated users away from the public page
        
        // Pull a curated set of public/city bulletin posts
        $posts = [];
        $events = [];
        $services = [];
        $resources = [];
        $siteLogoUrl = null;
        $canonicalUrl = base_url('/');
        $pageDescription = 'K-NECT: Unified youth engagement platform for announcements, events, resources, and data-driven community impact.';
        $siteLogoUrl = null;
        $canonicalUrl = base_url('/');
        $pageDescription = 'K-NECT: Unified youth engagement platform for announcements, events, resources, and data-driven community impact.';
        try {
            $bulletinModel = new \App\Models\BulletinModel();
            $analyticsModel = new \App\Models\AnalyticsModel();
            $systemLogoModel = new \App\Models\SystemLogoModel();
            $analyticsModel = new \App\Models\AnalyticsModel();
            $systemLogoModel = new \App\Models\SystemLogoModel();
            // Limit to published + visibility public or city
            $posts = $bulletinModel->builder()
                ->select('bp.id,bp.title,bp.excerpt,bp.content,bp.featured_image,bp.published_at,bp.view_count,bp.is_featured,bp.is_urgent,bc.name as category_name,bc.color as category_color,u.first_name,u.last_name')
                ->from('bulletin_posts bp')
                ->join('bulletin_categories bc','bc.id=bp.category_id','left')
                ->join('user u','u.id=bp.author_id','left')
                ->where('bp.status','published')
                ->groupStart()
                    ->where('bp.visibility','public')
                    ->orWhere('bp.visibility','city')
                ->groupEnd()
                // Ensure we don't get duplicate rows if future joins (e.g. tags) create multiplicity
                ->groupBy('bp.id')
                ->orderBy('bp.is_featured','DESC')
                ->orderBy('bp.is_urgent','DESC')
                ->orderBy('bp.published_at','DESC')
                ->limit(6)->get()->getResultArray();

            // Recent upcoming events (public view): reuse model method
            $events = $bulletinModel->getRecentEvents(4, 'pederasyon', null);
            // Fallback: if no upcoming events are found, show the most recent published events
            if (empty($events)) {
                $events = $bulletinModel->getRecentEventsAnyDate(4, 'pederasyon', null);
            }

            // Top performing barangays (30-day window)
            $topBarangays = $analyticsModel->getTopPerformingBarangays(3, 30);
            // Active SK logo (optional adornment in public ranking section)
            $skLogo = $systemLogoModel->getActiveLogoByType('sk');
            // Fallback: if no upcoming events are found, show the most recent published events
            if (empty($events)) {
                $events = $bulletinModel->getRecentEventsAnyDate(4, 'pederasyon', null);
            }

            // Top performing barangays (30-day window)
            $topBarangays = $analyticsModel->getTopPerformingBarangays(3, 30);
            // Active SK logo (optional adornment in public ranking section)
            $skLogo = $systemLogoModel->getActiveLogoByType('sk');
        } catch (\Throwable $e) {
            log_message('error','Public landing data error: '.$e->getMessage());
        }

        // Static placeholder service/resource blocks (could be DB driven later)
        $services = [
            ['icon'=>'fa-handshake-angle','title'=>'Youth Partnership','desc'=>'Programs connecting youth initiatives with civic partners.'],
            ['icon'=>'fa-graduation-cap','title'=>'Scholarship Support','desc'=>'Centralized guidance for education & grants.'],
            ['icon'=>'fa-people-group','title'=>'Community Outreach','desc'=>'Coordinated volunteer and barangay engagement.'],
            ['icon'=>'fa-chart-simple','title'=>'Data Insights','desc'=>'Evidence-based planning dashboards for leaders.'],
        ];
        $resources = [
            ['title'=>'Event Playbook','desc'=>'Plan and evaluate youth events effectively.','icon'=>'fa-book-open'],
            ['title'=>'Document Kit','desc'=>'Standard templates for rapid generation.','icon'=>'fa-file-lines'],
            ['title'=>'Engagement Guide','desc'=>'Best practices for inclusive participation.','icon'=>'fa-lightbulb'],
            ['title'=>'Analytics Overview','desc'=>'Understand key demographic trends.','icon'=>'fa-chart-pie'],
        ];

        // Determine site logo URL (fallback to first available logo if logo.png is missing)
        try {
            $logoRelPath = 'uploads/logos/logo.png';
            $logoFsPath = FCPATH . $logoRelPath; // FCPATH = public/ path
            if (is_file($logoFsPath)) {
                $siteLogoUrl = base_url($logoRelPath);
            } else {
                $logosDir = FCPATH . 'uploads/logos/';
                if (is_dir($logosDir)) {
                    $files = scandir($logosDir);
                    foreach ($files as $f) {
                        if ($f === '.' || $f === '..') { continue; }
                        $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
                        if (in_array($ext, ['png','jpg','jpeg','webp'])) {
                            $siteLogoUrl = base_url('uploads/logos/' . $f);
                            break;
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            // noop, fallback below
        }
        if (!$siteLogoUrl) {
            $siteLogoUrl = base_url('favicon.ico');
        }

        $data = [
            'page_title' => 'K-NECT Youth Engagement Platform',
            'posts' => $posts,
            'events' => $events,
            'topBarangays' => $topBarangays ?? [],
            'topBarangaysWindowDays' => 30,
            'topBarangays' => $topBarangays ?? [],
            'topBarangaysWindowDays' => 30,
            'services' => $services,
            'resources' => $resources,
            'siteLogoUrl' => $siteLogoUrl,
            'canonicalUrl' => $canonicalUrl,
            'pageDescription' => $pageDescription,
            'skLogo' => $skLogo['file_path'] ?? null,
            'siteLogoUrl' => $siteLogoUrl,
            'canonicalUrl' => $canonicalUrl,
            'pageDescription' => $pageDescription,
            'skLogo' => $skLogo['file_path'] ?? null,
        ];
        return view('K-NECT/public/landing', $data);
    }

    /**
     * Public JSON endpoint: Top Performing Barangays ranking
     * GET params:
     *  - days: lookback window (default 30, min 7, max 120)
     *  - limit: number of rows (default 10, 0 or negative = all)
     */
    public function topBarangaysData($days = null)
    {
        $this->response->setHeader('Content-Type','application/json');
        try {
            $req = service('request');
            $daysParam = $days ?? (int)$req->getGet('days');
            if(!$daysParam) { $daysParam = 30; }
            $daysParam = max(7, min(120, (int)$daysParam));
            $limit = (int)$req->getGet('limit');
            if(!$limit) { $limit = 10; }
            $analyticsModel = new \App\Models\AnalyticsModel();
            $rows = $analyticsModel->getTopPerformingBarangays($limit, $daysParam, true);
            return $this->response->setStatusCode(200)->setJSON([
                'ok' => true,
                'days' => $daysParam,
                'limit' => $limit,
                'count' => count($rows),
                'data' => $rows,
            ]);
        } catch (\Throwable $e) {
            log_message('error','topBarangaysData error: '.$e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'ok' => false,
                'error' => 'Unable to load rankings'
            ]);
        }
    }

    /**
     * Public event detail page
     * Route: /event/{id}
     * Only published events are visible; returns 404 otherwise.
     */
    public function event($id)
    {
        $eventModel = new \App\Models\EventModel();
        $bulletinModel = new \App\Models\BulletinModel(); // leverage DB connection for joins if needed
        $db = \Config\Database::connect();
        $event = null;
        $siteLogoUrl = base_url('favicon.ico');
        try {
            $builder = $db->table('event e')
                ->select('e.*, u.first_name, u.last_name, b.name as barangay_name')
                ->join('user u','u.id = e.created_by','left')
                ->join('barangay b','b.barangay_id = e.barangay_id','left')
                ->where('e.event_id', $id)
                ->where('e.status','Published')
                ->limit(1);
            $event = $builder->get()->getRowArray();
            // Determine site logo (same fallback as landing)
            try {
                $logoRelPath = 'uploads/logos/logo.png';
                $logoFsPath = FCPATH . $logoRelPath;
                if (is_file($logoFsPath)) {
                    $siteLogoUrl = base_url($logoRelPath);
                } else {
                    $logosDir = FCPATH . 'uploads/logos/';
                    if (is_dir($logosDir)) {
                        $files = scandir($logosDir);
                        foreach ($files as $f) {
                            if ($f === '.' || $f === '..') { continue; }
                            $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
                            if (in_array($ext, ['png','jpg','jpeg','webp'])) {
                                $siteLogoUrl = base_url('uploads/logos/' . $f);
                                break;
                            }
                        }
                    }
                }
            } catch (\Throwable $ie) { /* ignore logo errors */ }
        } catch (\Throwable $e) {
            log_message('error','Public event detail query error: '.$e->getMessage());
        }

        if(!$event) {
            // Graceful 404
            return \Config\Services::response()->setStatusCode(404)->setBody('Event not found');
        }

        // Temporal status (upcoming / ongoing / completed)
        $temporalStatus = null;
        try {
            $temporalStatus = $eventModel->getEventTemporalStatus($event);
        } catch (\Throwable $e) {
            // ignore temporal status failure
        }

        $page_title = $event['title'] . ' | K-NECT Event';
        $canonicalUrl = base_url('event/'.$event['event_id']);
        $pageDescription = mb_strimwidth(strip_tags($event['description'] ?? 'K-NECT Event'), 0, 150, '…');

        // Related upcoming events (exclude current)
        $related = [];
        try {
            $relQ = $db->table('event e')
                ->select('e.event_id as id, e.title, e.start_datetime, e.event_banner')
                ->where('e.status','Published')
                ->where('e.event_id !=', $event['event_id'])
                ->where('e.start_datetime >=', date('Y-m-d 00:00:00'))
                ->orderBy('e.start_datetime','ASC')
                ->limit(3)
                ->get()->getResultArray();
            $related = $relQ;
        } catch (\Throwable $ex) { /* ignore related failure */ }

        $data = [
            'page_title' => $page_title,
            'event' => $event,
            'temporalStatus' => $temporalStatus,
            'canonicalUrl' => $canonicalUrl,
            'pageDescription' => $pageDescription,
            'siteLogoUrl' => $siteLogoUrl,
            'relatedEvents' => $related,
        ];
        return view('K-NECT/public/event_detail', $data);
    }
}
