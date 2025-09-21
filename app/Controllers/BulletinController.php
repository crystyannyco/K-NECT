<?php

namespace App\Controllers;

use App\Models\BulletinModel;
use App\Models\BarangayModel;
use App\Models\UserModel;



class BulletinController extends BaseController
{
    protected $bulletinModel;
    protected $barangayModel;

    public function __construct()
    {
        $this->bulletinModel = new BulletinModel();
        $this->barangayModel = new BarangayModel();
    }

    /**
     * Main bulletin page - role-based view
     */
    public function index()
    {
        $session = session();
        $userType = $session->get('user_type');
        $userId = $session->get('user_id');
        // Resolve barangay id robustly based on user type (avoid empty sk_barangay overshadowing kk)
        $barangayId = $this->getCurrentBarangayId();
        
        // Map session user_id (permanent) to DB primary key (id) for accurate stats
        $authorDbId = null;
        try {
            $userModel = new UserModel();
            $author = $userModel->where('user_id', $userId)->first();
            if ($author && !empty($author['id'])) {
                $authorDbId = (int) $author['id'];
            }
        } catch (\Throwable $t) {
            log_message('error', 'User resolution error (stats): ' . $t->getMessage());
        }

        // Initialize empty data arrays
        $posts = [];
        $featuredPosts = [];
        $urgentPosts = [];
        $categories = [];
        $recentEvents = [];
        $recentDocuments = [];
        $stats = [];

        try {
        // Get posts based on user role
        $posts = $this->bulletinModel->getVisiblePosts($userType, $barangayId, 10, 0);
        $featuredPosts = $this->bulletinModel->getFeaturedPosts(3, $userType, $barangayId);
        $urgentPosts = $this->bulletinModel->getUrgentPosts(3, $userType, $barangayId);
        $categories = $this->bulletinModel->getCategoriesWithCounts($userType, $barangayId);
        $recentEvents = $this->bulletinModel->getRecentEvents(5, $userType, $barangayId);
        if (empty($recentEvents)) {
            // Fallback to most recently created events so the section isn't empty
            $recentEvents = $this->bulletinModel->getRecentEventsAnyDate(5, $userType, $barangayId);
        }
        $recentDocuments = $this->bulletinModel->getRecentDocuments(5, $userType, $barangayId);
    $stats = $this->bulletinModel->getDashboardStats($userType, $authorDbId, $barangayId);
            
            // Debug targeted barangay visibility
            if ($userType === 'kk') {
                $barangayCounts = [];
                foreach ($posts as $p) {
                    if ($p['visibility'] === 'barangay') {
                        $barangayCounts[$p['barangay_id'] ?? 'null'] = ($barangayCounts[$p['barangay_id'] ?? 'null'] ?? 0) + 1;
                    }
                }
                log_message('debug', 'KK bulletin load: barangayId=' . ($barangayId ?? 'none') . ' barangayPostCounts=' . json_encode($barangayCounts));
            }
            log_message('debug', 'Bulletin data loaded - Posts: ' . count($posts) . ', User Type: ' . $userType . ', Barangay: ' . $barangayId);
            
        } catch (\Exception $e) {
            // Log the detailed error but don't break the page
            log_message('error', 'Bulletin system error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
            
            // Set flash message for users to see there's an issue
            session()->setFlashdata('warning', 'Some bulletin features may not be available. Please contact system administrator if the issue persists.');
        }

        $data = [
            'page_title' => 'Bulletin Board',
            'posts' => $posts,
            'featured_posts' => $featuredPosts,
            'urgent_posts' => $urgentPosts,
            'categories' => $categories,
            'recent_events' => $recentEvents,
            'recent_documents' => $recentDocuments,
            'stats' => $stats,
            'user_type' => $userType,
            'user_id' => $authorDbId,
            'barangay_id' => $barangayId,
            'barangay_name' => $barangayId ? \App\Libraries\BarangayHelper::getBarangayName($barangayId) : null
        ];

    return $this->renderBulletinView($userType, "K-NECT/{$userType}/Bulletin/index", $data);
    }

    /**
     * View single post
     */
    public function view($postId)
    {
        $session = session();
        $userType = $session->get('user_type');
        $barangayId = $this->getCurrentBarangayId();

        try {
        $post = $this->bulletinModel->getPostWithDetails($postId);

        if (!$post) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Post not found');
        }

        // Check if user can view this post
        if (!$this->bulletinModel->canUserViewPost($post, $userType, $barangayId)) {
            return redirect()->to('/bulletin')->with('error', 'You do not have permission to view this post.');
        }

        // Increment view count
            $this->bulletinModel->incrementViews($postId);

        // Get related posts
            $relatedPosts = $this->bulletinModel->getRelatedPosts($postId, $post['category_id'], 3, $userType, $barangayId);
        } catch (\Exception $e) {
            log_message('error', 'Bulletin view error: ' . $e->getMessage());
            $post = null;
            $relatedPosts = [];
        }

        if (!$post) {
            return redirect()->to('/bulletin')->with('error', 'Post not found or you do not have permission to view it.');
        }

        $data = [
            'page_title' => $post['title'],
            'post' => $post,
            'related_posts' => array_slice($relatedPosts, 0, 3),
            'user_type' => $userType,
            'user_id' => $this->resolveAuthorDbId(),
            'barangay_id' => $barangayId
        ];

        return $this->renderBulletinView($userType, "K-NECT/{$userType}/Bulletin/view", $data);
    }

    /**
     * Create new post (SK and Pederasyon only)
     */
    public function create()
    {
        $session = session();
        $userType = $session->get('user_type');

        if (!in_array($userType, ['sk', 'pederasyon'])) {
            return redirect()->to('/bulletin')->with('error', 'You do not have permission to create posts.');
        }

        try {
    $categories = $this->bulletinModel->getCategories();
        $barangays = [];

        if ($userType === 'pederasyon') {
            $barangays = $this->barangayModel->findAll();
            }
        } catch (\Exception $e) {
            // If database tables don't exist yet, provide empty data
            log_message('error', 'Bulletin create error: ' . $e->getMessage());
            $categories = [];
            $barangays = [];
        }

        $data = [
            'page_title' => 'Create New Post',
            'categories' => $categories,
            'barangays' => $barangays,
            'user_type' => $userType
        ];

        return $this->renderBulletinView($userType, "K-NECT/{$userType}/Bulletin/create", $data);
    }

    /**
     * Store new post
     */
    public function store()
    {
        $session = session();
        $userType = $session->get('user_type');
    $userId = $session->get('user_id');
    // Resolve DB primary key for permission checks
    $authorDbId = $this->resolveAuthorDbId();
    $barangayId = $this->getCurrentBarangayId();
    $barangayId = $this->getCurrentBarangayId();

        if (!in_array($userType, ['sk', 'pederasyon'])) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'You do not have permission to create posts.']);
            }
            return redirect()->to('/bulletin')->with('error', 'You do not have permission to create posts.');
        }

        // Validation rules
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'content' => 'required|min_length[10]',
            'status' => 'required|in_list[draft,published]',
            'visibility' => 'required|in_list[public,barangay,city]'
        ];

        // Make featured_image optional
        $fi = $this->request->getFile('featured_image');
        if ($fi && $fi->isValid()) {
            $rules['featured_image'] = 'max_size[featured_image,2048]|is_image[featured_image]';
        }

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Validation failed', 'errors' => $this->validator->getErrors()]);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            // Map session permanent user_id to actual DB primary key id for FK integrity
            $userModel = new UserModel();
            $author = $userModel->where('user_id', $userId)->first();
            if (!$author || empty($author['id'])) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Unable to resolve current user. Please re-login.']);
                }
                return redirect()->back()->withInput()->with('error', 'Unable to resolve current user. Please re-login.');
            }
            $authorDbId = (int) $author['id'];

        $data = [
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
            'excerpt' => $this->request->getPost('excerpt'),
            'category_id' => $this->request->getPost('category_id') ?: null,
            'author_id' => $authorDbId,
            'status' => $this->request->getPost('status'),
            // Raw visibility input (will be normalized for SK below)
            'visibility' => $this->request->getPost('visibility'),
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0,
            'is_urgent' => $this->request->getPost('is_urgent') ? 1 : 0,
            'published_at' => $this->request->getPost('status') === 'published' ? date('Y-m-d H:i:s') : null
        ];

            // Enforce barangay-only scope for SK regardless of tampered form values
            if ($userType === 'sk') {
                $data['visibility'] = 'barangay';
            }

            // Check for duplicate posts (same title by same author within last 5 minutes)
            $recentDuplicate = $this->bulletinModel
                ->where('title', $data['title'])
                ->where('author_id', $authorDbId)
                ->where('created_at >', date('Y-m-d H:i:s', strtotime('-5 minutes')))
                ->first();

            if ($recentDuplicate) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false, 
                        'message' => 'A post with the same title was recently created. Please wait before creating another similar post.'
                    ]);
                }
                return redirect()->back()->withInput()->with('error', 'A post with the same title was recently created. Please wait before creating another similar post.');
            }

            // Handle barangay_id based on user type
            if ($userType === 'sk') {
                $data['barangay_id'] = $session->get('sk_barangay');
                if (empty($data['barangay_id'])) {
                    $msg = 'Your account is not linked to a barangay. Please contact an administrator.';
                    if ($this->request->isAJAX()) {
                        return $this->response->setJSON(['success' => false, 'message' => $msg]);
                    }
                    return redirect()->back()->withInput()->with('error', $msg);
                }
                // Double-assurance (in case of future refactors) that SK posts remain barangay visibility
                $data['visibility'] = 'barangay';
        } elseif ($userType === 'pederasyon') {
            $data['barangay_id'] = $this->request->getPost('barangay_id') ?: null;
            // Validation: enforce consistent semantics
            if (!empty($data['barangay_id']) && $data['visibility'] !== 'barangay') {
                $msg = 'When targeting a specific barangay, visibility must be set to Barangay Only.';
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON(['success' => false, 'message' => $msg]);
                }
                return redirect()->back()->withInput()->with('error', $msg);
            }
            if (empty($data['barangay_id']) && $data['visibility'] === 'barangay') {
                $msg = 'Please select a Barangay or change visibility to City/Public.';
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON(['success' => false, 'message' => $msg]);
                }
                return redirect()->back()->withInput()->with('error', $msg);
            }
        }

        // Handle featured image upload
        $featuredImage = $this->request->getFile('featured_image');
            if ($featuredImage && $featuredImage->isValid() && !$featuredImage->hasMoved()) {
            $newName = $featuredImage->getRandomName();
                $featuredImage->move(FCPATH . 'uploads/bulletin/', $newName);
                $data['featured_image'] = $newName;
        }

        // Insert post with robust handling for return types across drivers
        $insertResult = $this->bulletinModel->insert($data);
        $postId = is_numeric($insertResult) ? (int) $insertResult : (int) ($this->bulletinModel->getInsertID() ?? 0);

        if ($insertResult) {
                // Process tags if provided
                $tagsRaw = (string) ($this->request->getPost('tags') ?? '');
                if ($tagsRaw !== '') {
                    $tagNames = array_filter(array_map('trim', explode(',', $tagsRaw)));
                    try {
                        $this->bulletinModel->addTagsToPost((int)$postId, $tagNames);
                    } catch (\Throwable $t) {
                        log_message('error', 'Add tags error (store): ' . $t->getMessage());
                    }
                }
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON(['success' => true, 'message' => 'Post created successfully.', 'post_id' => $postId ?: null]);
                }
                return redirect()->to('/bulletin')->with('success', 'Post created successfully.');
            } else {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Failed to create post.']);
                }
                return redirect()->back()->withInput()->with('error', 'Failed to create post.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Bulletin store error: ' . $e->getMessage());
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'An error occurred while creating the post: ' . $e->getMessage()]);
            }
            return redirect()->back()->withInput()->with('error', 'An error occurred while creating the post.');
        }
    }

    /**
     * Edit post (SK and Pederasyon only)
     */
    public function edit($postId)
    {
        $session = session();
        $userType = $session->get('user_type');
        $userId = $session->get('user_id');
    // Resolve DB primary key for current user (needed for SK ownership checks)
    $authorDbId = $this->resolveAuthorDbId();
    $barangayId = $this->getCurrentBarangayId();

        if (!in_array($userType, ['sk', 'pederasyon'])) {
            return redirect()->to('/bulletin')->with('error', 'You do not have permission to edit posts.');
        }

        try {
            $post = $this->bulletinModel->getPostWithDetails($postId);

        if (!$post) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Post not found');
        }

        // Check if user can edit this post (pederasyon always allowed; SK must own)
    if (!$this->bulletinModel->canUserEditPost($post, $userType, $authorDbId, $barangayId)) {
            return redirect()->to('/bulletin')->with('error', 'You do not have permission to edit this post.');
        }

        $categories = $this->bulletinModel->getCategories();
        $barangays = [];

        if ($userType === 'pederasyon') {
            $barangays = $this->barangayModel->findAll();
            }
        } catch (\Exception $e) {
            log_message('error', 'Bulletin edit error: ' . $e->getMessage());
            return redirect()->to('/bulletin')->with('error', 'Post not found or you do not have permission to edit it.');
        }

        // Load tags for edit view
        $postTags = [];
        try { $postTags = $this->bulletinModel->getPostTags($postId); } catch (\Throwable $t) {}

        $data = [
            'page_title' => 'Edit Post',
            'post' => $post,
            'categories' => $categories,
            'barangays' => $barangays,
            'user_type' => $userType,
            'user_id' => $authorDbId,
            'barangay_id' => $barangayId,
            'post_tags' => $postTags
        ];

        return $this->renderBulletinView($userType, "K-NECT/{$userType}/Bulletin/edit", $data);
    }

    /**
     * Update post
     */
    public function update($postId)
    {
        $session = session();
        $userType = $session->get('user_type');
    $userId = $session->get('user_id');
    $authorDbId = $this->resolveAuthorDbId();
    // Resolve current barangay for permission checks (used in canUserEditPost)
    $barangayId = $this->getCurrentBarangayId();

        if (!in_array($userType, ['sk', 'pederasyon'])) {
            return redirect()->to('/bulletin')->with('error', 'You do not have permission to edit posts.');
        }

    // Validation rules (featured_image optional on update)
        $rules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'content' => 'required|min_length[10]',
            'status' => 'required|in_list[draft,published]',
            'visibility' => 'required|in_list[public,barangay,city]'
        ];
    $fi = $this->request->getFile('featured_image');
    if ($fi && $fi->isValid()) {
            $rules['featured_image'] = 'max_size[featured_image,2048]|is_image[featured_image]';
        }

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Validation failed', 'errors' => $this->validator->getErrors()]);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $post = $this->bulletinModel->getPostWithDetails($postId);

            if (!$post) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Post not found');
            }

            // Check if user can edit this post
            if (!$this->bulletinModel->canUserEditPost($post, $userType, $authorDbId, $barangayId)) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON(['success' => false, 'message' => 'You do not have permission to edit this post.']);
                }
                return redirect()->to('/bulletin')->with('error', 'You do not have permission to edit this post.');
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
            'excerpt' => $this->request->getPost('excerpt'),
            'category_id' => $this->request->getPost('category_id') ?: null,
            'status' => $this->request->getPost('status'),
            'visibility' => $this->request->getPost('visibility'), // Will be normalized for SK below
            'is_featured' => $this->request->getPost('is_featured') ? 1 : 0,
            'is_urgent' => $this->request->getPost('is_urgent') ? 1 : 0
        ];

            if ($userType === 'sk') {
                // Force scope to barangay only on update
                $data['visibility'] = 'barangay';
            }

            // Handle published_at
            if ($post['status'] !== 'published' && $this->request->getPost('status') === 'published') {
            $data['published_at'] = date('Y-m-d H:i:s');
        }

            // Handle barangay_id based on user type
            if ($userType === 'sk') {
                $data['barangay_id'] = $session->get('sk_barangay');
                if (empty($data['barangay_id'])) {
                    $msg = 'Your account is not linked to a barangay. Please contact an administrator.';
                    if ($this->request->isAJAX()) {
                        return $this->response->setJSON(['success' => false, 'message' => $msg]);
                    }
                    return redirect()->back()->withInput()->with('error', $msg);
                }
                $data['visibility'] = 'barangay'; // ensure consistency
            } elseif ($userType === 'pederasyon') {
                $data['barangay_id'] = $this->request->getPost('barangay_id') ?: null;
                if (!empty($data['barangay_id']) && $data['visibility'] !== 'barangay') {
                    $msg = 'When targeting a specific barangay, visibility must be Barangay Only.';
                    if ($this->request->isAJAX()) {
                        return $this->response->setJSON(['success' => false, 'message' => $msg]);
                    }
                    return redirect()->back()->withInput()->with('error', $msg);
                }
                if (empty($data['barangay_id']) && $data['visibility'] === 'barangay') {
                    $msg = 'Select a Barangay or change visibility.';
                    if ($this->request->isAJAX()) {
                        return $this->response->setJSON(['success' => false, 'message' => $msg]);
                    }
                    return redirect()->back()->withInput()->with('error', $msg);
                }
            }

            // Handle featured image
            $featuredImage = $this->request->getFile('featured_image');
            if ($featuredImage && $featuredImage->isValid() && !$featuredImage->hasMoved()) {
                // Remove old image if exists
                if ($post['featured_image']) {
                    $oldImagePath = FCPATH . 'uploads/bulletin/' . $post['featured_image'];
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $newName = $featuredImage->getRandomName();
                $featuredImage->move(FCPATH . 'uploads/bulletin/', $newName);
                    $data['featured_image'] = $newName;
            }

            // Handle image removal (support both keys from views)
            if ($this->request->getPost('remove_current_image') || $this->request->getPost('remove_image')) {
                if ($post['featured_image']) {
                    $oldImagePath = FCPATH . 'uploads/bulletin/' . $post['featured_image'];
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                $data['featured_image'] = null;
        }

        if ($this->bulletinModel->update($postId, $data)) {
                // Update tags if provided
                $tagsRaw = (string) ($this->request->getPost('tags') ?? '');
                if ($tagsRaw !== '') {
                    $tagNames = array_filter(array_map('trim', explode(',', $tagsRaw)));
                    try {
                        $this->bulletinModel->addTagsToPost((int)$postId, $tagNames);
                    } catch (\Throwable $t) {
                        log_message('error', 'Add tags error (update): ' . $t->getMessage());
                    }
                } else {
                    // If tags is intentionally empty string, clear tags
                    if ($this->request->getPost('tags') !== null) {
                        try { $this->bulletinModel->addTagsToPost((int)$postId, []); } catch (\Throwable $t) {}
                    }
                }
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON(['success' => true, 'message' => 'Post updated successfully.', 'post_id' => $postId]);
                }
                return redirect()->to('/bulletin/view/' . $postId)->with('success', 'Post updated successfully.');
            } else {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Failed to update post.']);
                }
                return redirect()->back()->withInput()->with('error', 'Failed to update post.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Bulletin update error: ' . $e->getMessage());
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'An error occurred while updating the post.']);
            }
            return redirect()->back()->withInput()->with('error', 'An error occurred while updating the post.');
        }
    }

    /**
     * Delete post
     */
    public function delete($postId)
    {
        $session = session();
        $userType = $session->get('user_type');
    $userId = $session->get('user_id');
    $authorDbId = $this->resolveAuthorDbId();
    // Resolve current barangay for permission checks (used in canUserDeletePost)
    $barangayId = $this->getCurrentBarangayId();

        if (!in_array($userType, ['sk', 'pederasyon'])) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'You do not have permission to delete posts.'
                ]);
            }
            return redirect()->to('/bulletin')->with('error', 'You do not have permission to delete posts.');
        }

        try {
            $post = $this->bulletinModel->getPostWithDetails($postId);

            if (!$post) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Post not found.'
                    ]);
                }
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Post not found');
            }

            // Check if user can delete this post
            if (!$this->bulletinModel->canUserDeletePost($post, $userType, $authorDbId, $barangayId)) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'You do not have permission to delete this post.'
                    ]);
                }
                return redirect()->to('/bulletin')->with('error', 'You do not have permission to delete this post.');
            }

            // Delete featured image if exists
        if ($post['featured_image']) {
                $imagePath = FCPATH . 'uploads/bulletin/' . $post['featured_image'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        if ($this->bulletinModel->delete($postId)) {
                if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                        'message' => 'Post deleted successfully.'
                    ]);
                }
                return redirect()->to('/bulletin')->with('success', 'Post deleted successfully.');
            } else {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Failed to delete post.'
                    ]);
                }
                return redirect()->back()->withInput()->with('error', 'Failed to delete post.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Bulletin delete error: ' . $e->getMessage());
            if ($this->request->isAJAX()) {
        return $this->response->setJSON([
            'success' => false,
                    'message' => 'An error occurred while deleting the post.'
        ]);
            }
            return redirect()->back()->withInput()->with('error', 'An error occurred while deleting the post.');
        }
    }

    /**
     * Manage categories (Pederasyon only)
     */
    public function categories()
    {
        $session = session();
        $userType = $session->get('user_type');

        if ($userType !== 'pederasyon') {
            return redirect()->to('/bulletin')->with('error', 'You do not have permission to manage categories.');
        }

        try {
            $categories = $this->bulletinModel->getCategoriesWithCounts();
        } catch (\Exception $e) {
            // If database tables don't exist yet, provide empty data
            log_message('error', 'Bulletin categories error: ' . $e->getMessage());
            $categories = [];
        }

        $data = [
            'page_title' => 'Manage Categories',
            'categories' => $categories,
            'user_type' => $userType
        ];

        return $this->renderBulletinView($userType, "K-NECT/{$userType}/Bulletin/categories", $data);
    }

    /**
     * Return active categories as JSON (for AJAX forms)
     */
    public function getCategoriesList()
    {
        try {
            $categories = $this->bulletinModel->getCategories();
            return $this->response->setJSON(['success' => true, 'categories' => $categories]);
        } catch (\Exception $e) {
            log_message('error', 'getCategoriesList error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Unable to load categories']);
        }
    }

    /**
     * Return single category by id as JSON
     */
    public function getCategory($categoryId)
    {
        try {
            $category = $this->bulletinModel->getCategoryById($categoryId);
            if (!$category) {
                return $this->response->setJSON(['success' => false, 'message' => 'Category not found']);
            }
            return $this->response->setJSON(['success' => true, 'category' => $category]);
        } catch (\Exception $e) {
            log_message('error', 'getCategory error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Unable to load category']);
        }
    }

    /**
     * Store new category
     */
    public function storeCategory()
    {
        $session = session();
        $userType = $session->get('user_type');

        if ($userType !== 'pederasyon') {
            return redirect()->to('/bulletin')->with('error', 'You do not have permission to manage categories.');
        }

        // Validation rules
        $rules = [
            'name' => 'required|min_length[2]|max_length[100]|is_unique[bulletin_categories.name]',
            'description' => 'permit_empty|max_length[500]',
            'color' => 'required|regex_match[/^#[0-9A-F]{6}$/i]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'color' => $this->request->getPost('color'),
                'icon' => $this->request->getPost('icon') ?: 'newspaper'
        ];

        $ok = $this->bulletinModel->insertCategory($data);
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => (bool) $ok,
                    'message' => $ok ? 'Category created successfully.' : 'Failed to create category.'
                ]);
            }
            if ($ok) {
                return redirect()->to('/bulletin/categories')->with('success', 'Category created successfully.');
            }
            return redirect()->back()->withInput()->with('error', 'Failed to create category.');
        } catch (\Exception $e) {
            log_message('error', 'Category store error: ' . $e->getMessage());
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'An error occurred while creating the category.']);
            }
            return redirect()->back()->withInput()->with('error', 'An error occurred while creating the category.');
        }
    }

    /**
     * Update category
     */
    public function updateCategory($categoryId)
    {
        $session = session();
        $userType = $session->get('user_type');

        if ($userType !== 'pederasyon') {
            return redirect()->to('/bulletin')->with('error', 'You do not have permission to manage categories.');
        }

        // Validation rules
        $rules = [
            'name' => 'required|min_length[2]|max_length[100]|is_unique[bulletin_categories.name,id,' . $categoryId . ']',
            'description' => 'permit_empty|max_length[500]',
            'color' => 'required|regex_match[/^#[0-9A-F]{6}$/i]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'color' => $this->request->getPost('color'),
                'icon' => $this->request->getPost('icon') ?: 'newspaper'
            ];

            $ok = $this->bulletinModel->updateCategory($categoryId, $data);
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => (bool) $ok,
                    'message' => $ok ? 'Category updated successfully.' : 'Failed to update category.'
                ]);
            }
            if ($ok) {
                return redirect()->to('/bulletin/categories')->with('success', 'Category updated successfully.');
            }
            return redirect()->back()->withInput()->with('error', 'Failed to update category.');
        } catch (\Exception $e) {
            log_message('error', 'Category update error: ' . $e->getMessage());
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'An error occurred while updating the category.']);
            }
            return redirect()->back()->withInput()->with('error', 'An error occurred while updating the category.');
        }
    }

    /**
     * Delete category
     */
    public function deleteCategory($categoryId)
    {
        $session = session();
        $userType = $session->get('user_type');

        if ($userType !== 'pederasyon') {
            return redirect()->to('/bulletin')->with('error', 'You do not have permission to manage categories.');
        }

        try {
            // Check if category has associated posts
            $postCount = $this->bulletinModel->getCategoryPostCount($categoryId);
        if ($postCount > 0) {
                return redirect()->to('/bulletin/categories')->with('error', 'Cannot delete category with associated posts.');
            }

            $ok = $this->bulletinModel->deleteCategory($categoryId);
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => (bool) $ok,
                    'message' => $ok ? 'Category deleted successfully.' : 'Failed to delete category.'
                ]);
            }
            if ($ok) {
                return redirect()->to('/bulletin/categories')->with('success', 'Category deleted successfully.');
            }
            return redirect()->back()->withInput()->with('error', 'Failed to delete category.');
        } catch (\Exception $e) {
            log_message('error', 'Category delete error: ' . $e->getMessage());
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'An error occurred while deleting the category.']);
            }
            return redirect()->back()->withInput()->with('error', 'An error occurred while deleting the category.');
        }
    }

    /**
     * Get posts by category (JSON)
     */
    public function getPostsByCategory($categoryId)
    {
        $session = session();
        $userType = $session->get('user_type');
        $barangayId = $this->getCurrentBarangayId();
        try {
            // Simple pagination params
            $limit = (int) ($this->request->getGet('limit') ?? 10);
            $offset = (int) ($this->request->getGet('offset') ?? 0);
            if ($limit < 1) { $limit = 10; }
            if ($limit > 50) { $limit = 50; }
            if ($offset < 0) { $offset = 0; }

            $filters = [];
            if (is_numeric($categoryId)) {
                $filters['category_id'] = (int) $categoryId;
            }
            $posts = $this->bulletinModel->getVisiblePosts($userType, $barangayId, $limit, $offset, $filters);
            return $this->response->setJSON(['success' => true, 'posts' => $posts]);
        } catch (\Throwable $t) {
            log_message('error', 'getPostsByCategory error: ' . $t->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Unable to load posts for this category']);
        }
    }

    /**
     * Search posts (JSON)
     */
    public function search()
    {
        $session = session();
        $userType = $session->get('user_type');
        $barangayId = $this->getCurrentBarangayId();
        $q = trim((string) $this->request->getGet('q'));
        if ($q === '') {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing query']);
        }
        try {
            $limit = (int) ($this->request->getGet('limit') ?? 20);
            $offset = (int) ($this->request->getGet('offset') ?? 0);
            if ($limit < 1) { $limit = 20; }
            if ($limit > 50) { $limit = 50; }
            if ($offset < 0) { $offset = 0; }

            $posts = $this->bulletinModel->getVisiblePosts($userType, $barangayId, $limit, $offset, ['search' => $q]);
            return $this->response->setJSON(['success' => true, 'posts' => $posts]);
        } catch (\Throwable $t) {
            log_message('error', 'search error: ' . $t->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Unable to perform search']);
        }
    }

    // Export feature removed (previous export() method deprecated)

    /**
     * Render bulletin view with role-based template
     */
    private function renderBulletinView($userType, $view, $data = [])
    {
        try {
            // Load current user profile for template
            $userHelper = new \App\Libraries\UserHelper();
            $currentUser = $userHelper->getCurrentUserProfile();
            
            // Load event counts for sidebar
            $eventModel = new \App\Models\EventModel();
        $session = session();
        $barangayId = $this->getCurrentBarangayId();
            $eventCount = $eventModel->where('barangay_id', $barangayId)->countAllResults();
            
            // Merge template data
            $templateData = array_merge($data, [
                'currentUser' => $currentUser,
                'eventCount' => $eventCount
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error loading template data: ' . $e->getMessage());
            
            // Fallback template data
            $templateData = array_merge($data, [
                'currentUser' => null,
                'eventCount' => 0
            ]);
        }
        
        try {
            // Map user type to canonical folder name (fix case sensitivity across environments)
            $folder = $this->resolveUserFolder($userType);
            $templatePath = "K-NECT/{$folder}/Template/";
            // Normalize the provided view path to use the canonical folder
            $viewPath = $view;
            if (strpos($view, 'K-NECT/') === 0) {
                $parts = explode('/', $view);
                if (isset($parts[1])) {
                    $parts[1] = $folder;
                    $viewPath = implode('/', $parts);
                }
            }
            
            // Load the view with role-specific template
            return view($templatePath . 'header', $templateData)
                 . view($templatePath . 'sidebar', $templateData)
                 . view($viewPath, $templateData)
                 . view($templatePath . 'footer', $templateData);
                 
        } catch (\Exception $e) {
            log_message('error', 'Error loading template views: ' . $e->getMessage());
            
            // Fallback to just the main view without template
            return view($viewPath ?? $view, $templateData);
        }
    }

    /**
     * Resolve the canonical folder name for a given user type
     */
    private function resolveUserFolder($userType)
    {
        switch (strtolower((string)$userType)) {
            case 'pederasyon': return 'Pederasyon';
            case 'sk': return 'SK';
            case 'kk': return 'KK';
            default: return 'KK';
        }
    }

    /**
     * Map session 'user_id' (permanent) to DB primary key 'id'
     */
    private function resolveAuthorDbId()
    {
        try {
            $session = session();
            $userId = $session->get('user_id');
            if (!$userId) return null;
            $userModel = new UserModel();
            $author = $userModel->where('user_id', $userId)->first();
            return ($author && !empty($author['id'])) ? (int)$author['id'] : null;
        } catch (\Throwable $t) {
            log_message('error', 'User resolution error: ' . $t->getMessage());
            return null;
        }
    }

    /**
     * Determine the current barangay id for the active user robustly.
     * - SK: uses sk_barangay
     * - KK: prefers kk_barangay; falls back to sk_barangay only if kk not set
     * - Others: try kk first then sk
     */
    private function getCurrentBarangayId()
    {
        $session = session();
        $userType = strtolower((string) $session->get('user_type'));
    $sk = $session->get('sk_barangay');
    $kk = $session->get('kk_barangay');
    $gen = $session->get('barangay_id'); // generic key used elsewhere in app

        // Normalize empties to null for consistent fallback behavior
    $sk = ($sk === '' || $sk === 0 || $sk === '0') ? null : $sk;
    $kk = ($kk === '' || $kk === 0 || $kk === '0') ? null : $kk;
    $gen = ($gen === '' || $gen === 0 || $gen === '0') ? null : $gen;

        if ($userType === 'sk') {
            return $sk ?? $gen;
        }
        if ($userType === 'kk') {
            return $kk ?? $gen ?? $sk;
        }
        return $kk ?? $gen ?? $sk;
    }
} 