<?php
namespace App\Controllers;

class PublicController extends BaseController
{
    public function index()
    {
        $session = session();
        $userType = $session->get('user_type');
        if ($userType) {
            // Redirect authenticated users to their role dashboard
            switch ($userType) {
                case 'kk': return redirect()->to('/kk/dashboard');
                case 'sk': return redirect()->to('/sk/dashboard');
                case 'pederasyon': return redirect()->to('/pederasyon/dashboard');
            }
        }

        // Pull a curated set of public/city bulletin posts
        $posts = [];
        $events = [];
        $services = [];
        $resources = [];
        try {
            $bulletinModel = new \App\Models\BulletinModel();
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

        $data = [
            'page_title' => 'K-NECT Youth Engagement Platform',
            'posts' => $posts,
            'events' => $events,
            'services' => $services,
            'resources' => $resources,
        ];
        return view('K-NECT/public/landing', $data);
    }
}
