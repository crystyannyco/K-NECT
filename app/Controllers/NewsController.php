<?php

namespace App\Controllers;

use App\Models\BulletinModel;

class NewsController extends BaseController
{
    protected $bulletinModel;

    public function __construct()
    {
        $this->bulletinModel = new BulletinModel();
    }

    /**
     * Public news detail page
     */
    public function show($id = null)
    {
        $postId = is_numeric($id) ? (int) $id : 0;
        if ($postId <= 0) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('News not found');
        }

        try {
            $post = $this->bulletinModel->getPostWithDetails($postId);
        } catch (\Throwable $t) {
            $post = null;
        }

        if (!$post || ($post['status'] ?? '') !== 'published') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('News not found');
        }
        // Increment view count (lightweight / ignore failure)
        try {
            $this->bulletinModel->where('id',$postId)->set('view_count', ($post['view_count'] ?? 0) + 1)->update();
            $post['view_count'] = ($post['view_count'] ?? 0) + 1; // reflect in-page
        } catch (\Throwable $e) { /* ignore */ }

        // Determine site logo URL (mirror logic from PublicController)
        $siteLogoUrl = base_url('favicon.ico');
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
        } catch (\Throwable $e) { /* ignore */ }

        // Related posts (other published public/city posts) excluding current
        $relatedPosts = [];
        try {
            $db = \Config\Database::connect();
            $relQ = $db->table('bulletin_posts bp')
                ->select('bp.id,bp.title,bp.excerpt,bp.featured_image,bp.published_at,bc.name as category_name,bc.color as category_color')
                ->join('bulletin_categories bc','bc.id=bp.category_id','left')
                ->where('bp.status','published')
                ->groupStart()
                    ->where('bp.visibility','public')
                    ->orWhere('bp.visibility','city')
                ->groupEnd()
                ->where('bp.id !=', $postId)
                ->orderBy('bp.published_at','DESC')
                ->limit(3)
                ->get()->getResultArray();
            $relatedPosts = $relQ;
        } catch (\Throwable $e) { /* ignore related failure */ }

        $pageDescription = $post['excerpt'] ?? (mb_substr(strip_tags($post['content'] ?? ''), 0, 160) . '...');

        $data = [
            'page_title'    => ($post['title'] ?? 'News') . ' | K-NECT News',
            'post'          => $post,
            'canonicalUrl'  => base_url('news/' . $postId),
            'pageDescription' => $pageDescription,
            'siteLogoUrl'   => $siteLogoUrl,
            'relatedPosts'  => $relatedPosts,
        ];

        return view('K-NECT/public/news_detail', $data);
    }
}
