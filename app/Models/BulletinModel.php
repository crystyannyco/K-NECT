<?php

namespace App\Models;

use CodeIgniter\Model;

class BulletinModel extends Model
{
    protected $table = 'bulletin_posts';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'title', 'content', 'excerpt', 'featured_image', 'category_id', 
        'author_id', 'barangay_id', 'status', 'visibility', 'is_featured', 
        'is_urgent', 'view_count', 'published_at'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'title' => 'required|min_length[3]|max_length[255]',
        'content' => 'required|min_length[10]',
        'category_id' => 'permit_empty|integer',
        'author_id' => 'required|integer',
        'status' => 'required|in_list[draft,published,archived]',
        'visibility' => 'required|in_list[public,barangay,city]'
    ];

    protected $validationMessages = [
        'title' => [
            'required' => 'Title is required',
            'min_length' => 'Title must be at least 3 characters',
            'max_length' => 'Title cannot exceed 255 characters'
        ],
        'content' => [
            'required' => 'Content is required',
            'min_length' => 'Content must be at least 10 characters'
        ]
    ];

    // ========== BASIC CRUD METHODS ========== //

    /**
     * Get post by ID with author and category information
     */
    public function getPostWithDetails($id)
    {
        return $this->select('bulletin_posts.*, user.first_name, user.last_name, user.username, user.user_type, bulletin_categories.name as category_name, bulletin_categories.color as category_color, b.name as barangay_name')
                    ->join('user', 'user.id = bulletin_posts.author_id', 'left')
                    ->join('bulletin_categories', 'bulletin_categories.id = bulletin_posts.category_id', 'left')
                    ->join('barangay b', 'b.barangay_id = bulletin_posts.barangay_id', 'left')
                    ->find($id);
    }

    /**
     * Get posts with details using Query Builder
     */
    public function getPostsWithDetails($limit = null, $offset = 0, $filters = [])
    {
        $builder = $this->builder()
            ->select('bp.*, u.first_name, u.last_name, u.username, bc.name as category_name, bc.color as category_color, b.name as barangay_name')
            ->from('bulletin_posts bp')
            ->join('user u', 'u.id = bp.author_id', 'left')
            ->join('bulletin_categories bc', 'bc.id = bp.category_id', 'left')
            ->join('barangay b', 'b.barangay_id = bp.barangay_id', 'left')
            ->groupBy('bp.id');

        // Apply filters
        if (!empty($filters['status'])) {
            $builder->where('bp.status', $filters['status']);
        }

        if (!empty($filters['visibility'])) {
            $builder->where('bp.visibility', $filters['visibility']);
        }

        if (!empty($filters['barangay_id'])) {
            $builder->where('bp.barangay_id', $filters['barangay_id']);
        }

        if (!empty($filters['category_id'])) {
            $builder->where('bp.category_id', $filters['category_id']);
        }

        $builder->orderBy('bp.is_urgent', 'DESC')
            ->orderBy('bp.is_featured', 'DESC')
            ->orderBy('bp.published_at', 'DESC');

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Get posts visible to specific user role and barangay
     */
    public function getVisiblePosts($userType, $barangayId = null, $limit = null, $offset = 0, $filters = [])
    {
        // Normalize role casing to avoid case-sensitive mismatches (e.g., 'KK' vs 'kk')
        $role = strtolower((string) $userType);
        $builder = $this->builder()
            ->select('bp.*, u.first_name, u.last_name, u.username, bc.name as category_name, bc.color as category_color, b.name as barangay_name')
            ->from('bulletin_posts bp')
            ->join('user u', 'u.id = bp.author_id', 'left')
            ->join('bulletin_categories bc', 'bc.id = bp.category_id', 'left')
            ->join('barangay b', 'b.barangay_id = bp.barangay_id', 'left')
            ->where('bp.status', 'published')
            ->groupBy('bp.id');

        // Role-based visibility
        switch ($role) {
            case 'kk':
                $builder->groupStart()
                    ->where('bp.visibility', 'public')
                    ->orWhere('bp.visibility', 'city')
                    // Proper barangay-scoped posts
                    ->orGroupStart()
                        ->where('bp.visibility', 'barangay')
                        ->where('bp.barangay_id', $barangayId)
                    ->groupEnd()
                    // Legacy posts: city visibility but with a specific barangay_id (treat as visible)
                    ->orGroupStart()
                        ->where('bp.visibility', 'city')
                        ->where('bp.barangay_id IS NOT NULL')
                        ->where('bp.barangay_id', $barangayId)
                    ->groupEnd()
                ->groupEnd();
                break;
            case 'sk':
                $builder->groupStart()
                    ->where('bp.visibility', 'public')
                    ->orWhere('bp.visibility', 'city')
                    ->orGroupStart()
                        ->where('bp.visibility', 'barangay')
                        ->where('bp.barangay_id', $barangayId)
                    ->groupEnd()
                    ->orGroupStart()
                        ->where('bp.visibility', 'city')
                        ->where('bp.barangay_id IS NOT NULL')
                        ->where('bp.barangay_id', $barangayId)
                    ->groupEnd()
                ->groupEnd();
                break;
            case 'pederasyon':
                // Can see all posts - no additional filter needed
                break;
            default:
                // For safety, default to public only
                $builder->where('bp.visibility', 'public');
                break;
        }

        // Apply additional filters
        if (!empty($filters['category_id'])) {
            $builder->where('bp.category_id', $filters['category_id']);
        }

        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('bp.title', $filters['search'])
                ->orLike('bp.content', $filters['search'])
                ->groupEnd();
        }

        if (!empty($filters['is_featured'])) {
            $builder->where('bp.is_featured', 1);
        }

        if (!empty($filters['is_urgent'])) {
            $builder->where('bp.is_urgent', 1);
        }

        $builder->orderBy('bp.is_urgent', 'DESC')
            ->orderBy('bp.is_featured', 'DESC')
            ->orderBy('bp.published_at', 'DESC');

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Get featured posts
     */
    public function getFeaturedPosts($limit = 5, $userType = null, $barangayId = null)
    {
        $filters = ['is_featured' => 1];
        return $this->getVisiblePosts($userType, $barangayId, $limit, 0, $filters);
    }

    /**
     * Get urgent posts
     */
    public function getUrgentPosts($limit = 5, $userType = null, $barangayId = null)
    {
        $filters = ['is_urgent' => 1];
        return $this->getVisiblePosts($userType, $barangayId, $limit, 0, $filters);
    }

    /**
     * Increment view count
     */
    public function incrementViews($postId)
    {
        return $this->builder()
            ->where('id', $postId)
            ->set('view_count', 'view_count + 1', false)
            ->update();
    }

    // ========== CATEGORY METHODS ========== //

    /**
     * Get all active categories
     */
    public function getCategories()
    {
        return $this->db->table('bulletin_categories')
            ->where('is_active', 1)
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get categories with post counts
     */
    public function getCategoriesWithCounts($userType = null, $barangayId = null)
    {
        // Normalize role casing
        $role = strtolower((string) $userType);
        $builder = $this->db->table('bulletin_categories bc')
            ->select('bc.*, COUNT(bp.id) as post_count')
            ->join('bulletin_posts bp', 'bp.category_id = bc.id AND bp.status = "published"', 'left')
            ->where('bc.is_active', 1)
            ->groupBy('bc.id')
            ->orderBy('bc.name', 'ASC');

        // Apply role-based filtering for post counts
        if ($role && $role !== 'pederasyon') {
            if ($barangayId) {
                $builder->groupStart()
                    ->where('bp.visibility', 'public')
                    ->orWhere('bp.visibility', 'city')
                    ->orGroupStart()
                        ->where('bp.visibility', 'barangay')
                        ->where('bp.barangay_id', $barangayId)
                    ->groupEnd()
                ->groupEnd();
            } else {
                $builder->whereIn('bp.visibility', ['public', 'city']);
            }
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Get category by ID
     */
    public function getCategoryById($id)
    {
        return $this->db->table('bulletin_categories')
            ->where('id', $id)
            ->get()
            ->getRowArray();
    }

    /**
     * Get category by ID (alias)
     */
    public function getCategory($categoryId)
    {
        return $this->getCategoryById($categoryId);
    }

    /**
     * Create new category
     */
    public function createCategory($data)
    {
        // Validate category data
        $validationRules = [
            'name' => 'required|min_length[3]|max_length[100]',
            'description' => 'permit_empty|max_length[500]',
            'color' => 'permit_empty|regex_match[/^#[0-9A-Fa-f]{6}$/]',
            'icon' => 'permit_empty|max_length[50]'
        ];

        $validation = \Config\Services::validation();
        $validation->setRules($validationRules);
        if (!$validation->run($data)) {
            log_message('error', 'createCategory validation failed: ' . json_encode($validation->getErrors()));
            return false;
        }

        // Check if category name already exists
        $existing = $this->db->table('bulletin_categories')
            ->where('name', $data['name'])
            ->get()
            ->getRowArray();

        if ($existing) {
            return false;
        }

        // Set default values
        $data['is_active'] = $data['is_active'] ?? 1;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        return $this->db->table('bulletin_categories')->insert($data);
    }

    /**
     * Insert new category (alias for backward compatibility)
     */
    public function insertCategory($data)
    {
        return $this->createCategory($data);
    }

    /**
     * Update category
     */
    public function updateCategory($id, $data)
    {
        // If validation data is provided
        if (isset($data['name'])) {
            // Validate category data
            $validationRules = [
                'name' => 'required|min_length[3]|max_length[100]',
                'description' => 'permit_empty|max_length[500]',
                'color' => 'permit_empty|regex_match[/^#[0-9A-Fa-f]{6}$/]',
                'icon' => 'permit_empty|max_length[50]'
            ];

            $validation = \Config\Services::validation();
            $validation->setRules($validationRules);
            if (!$validation->run($data)) {
                log_message('error', 'updateCategory validation failed: ' . json_encode($validation->getErrors()));
                return false;
            }

            // Check if category name already exists (excluding current category)
            $existing = $this->db->table('bulletin_categories')
                ->where('name', $data['name'])
                ->where('id !=', $id)
                ->get()
                ->getRowArray();

            if ($existing) {
                return false;
            }
        }

        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->table('bulletin_categories')->where('id', $id)->update($data);
    }

    /**
     * Delete category
     */
    public function deleteCategory($id)
    {
        // Check if category has posts
        $hasPost = $this->where('category_id', $id)->first();
        if ($hasPost) {
            return false; // Cannot delete category with posts
        }

        return $this->db->table('bulletin_categories')->where('id', $id)->delete();
    }

    /**
     * Get post count for a category
     */
    public function getCategoryPostCount($categoryId)
    {
        return $this->where('category_id', $categoryId)->countAllResults();
    }

    // ========== PERMISSION METHODS ========== //

    /**
     * Check if user can view post
     */
    public function canUserViewPost($post, $userType, $barangayId = null)
    {
        if ($post['status'] !== 'published') {
            return false;
        }

        $role = strtolower((string) $userType);
        switch ($role) {
            case 'kk':
            case 'sk':
                if ($post['visibility'] === 'public') return true;
                if ($post['visibility'] === 'city') return true; // allow city-wide posts
                if ($post['visibility'] === 'barangay' && $post['barangay_id'] == $barangayId) return true;
                return false;
            case 'pederasyon':
                return true;
            default:
                return false;
        }
    }

    /**
     * Check if user can edit post
     */
    public function canUserEditPost($post, $userType, $userId)
    {
        $role = strtolower((string) $userType);
        if ($role === 'pederasyon') return true;
        if ($role === 'sk' && $post['author_id'] == $userId) return true;
        return false;
    }

    /**
     * Check if user can delete post
     */
    public function canUserDeletePost($post, $userType, $userId)
    {
        return $this->canUserEditPost($post, $userType, $userId);
    }

    // ========== UTILITY METHODS ========== //

    /**
     * Get post author details
     */
    public function getPostAuthor($authorId)
    {
        return $this->db->table('user')
            ->select('id, first_name, last_name, username, user_type, profile_picture')
            ->where('id', $authorId)
            ->get()
            ->getRowArray();
    }

    /**
     * Get related posts
     */
    public function getRelatedPosts($postId, $categoryId, $limit = 3, $userType = null, $barangayId = null)
    {
        $filters = ['category_id' => $categoryId];
        $posts = $this->getVisiblePosts($userType, $barangayId, $limit + 1, 0, $filters);
        
        // Remove current post from results
        return array_filter($posts, function($post) use ($postId) {
            return $post['id'] != $postId;
        });
    }

    /**
     * Get recent events (for sidebar integration)
     */
    public function getRecentEvents($limit = 5, $userType = null, $barangayId = null)
    {
        $builder = $this->db->table('event e')
            ->select('e.event_id as id, e.title as title, e.start_datetime as event_date, e.event_banner, e.created_at')
            ->where('e.start_datetime >=', date('Y-m-d'))
            ->orderBy('e.start_datetime', 'ASC')
            ->limit($limit);

        if ($userType !== 'pederasyon' && $barangayId) {
            $builder->where('e.barangay_id', $barangayId);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Get most recently created events (no date filter) as fallback
     */
    public function getRecentEventsAnyDate($limit = 5, $userType = null, $barangayId = null)
    {
        $builder = $this->db->table('event e')
            ->select('e.event_id as id, e.title as title, e.start_datetime as event_date, e.event_banner, e.created_at')
            ->orderBy('e.created_at', 'DESC')
            ->limit($limit);

        if ($userType !== 'pederasyon' && $barangayId) {
            $builder->where('e.barangay_id', $barangayId);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Get recent documents (for sidebar integration)
     */
    public function getRecentDocuments($limit = 5, $userType = null, $barangayId = null)
    {
        $builder = $this->db->table('documents d')
            ->select('d.id, d.title, d.filepath as file_path, d.uploaded_at as created_at')
            ->orderBy('d.uploaded_at', 'DESC')
            ->limit($limit);

        // Note: documents table doesn't have barangay_id field
        // All users can see all documents for now

        return $builder->get()->getResultArray();
    }

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats($userType, $userId = null, $barangayId = null)
    {
        $stats = [];

        if ($userType === 'pederasyon') {
            $stats['total_posts'] = $this->countAllResults();
            $stats['published_posts'] = $this->where('status', 'published')->countAllResults();
            $stats['draft_posts'] = $this->where('status', 'draft')->countAllResults();
        } elseif ($userType === 'sk') {
            $stats['my_posts'] = $this->where('author_id', $userId)->countAllResults();
            $stats['barangay_posts'] = $this->where('barangay_id', $barangayId)->where('status', 'published')->countAllResults();
        } else {
            // KK stats - visible posts only
            $visiblePosts = $this->getVisiblePosts($userType, $barangayId);
            $stats['visible_posts'] = count($visiblePosts);
        }

        return $stats;
    }



    // ========== TAG MANAGEMENT METHODS ========== //

    /**
     * Get all tags
     */
    public function getTags()
    {
        return $this->db->table('bulletin_tags')
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Create slug from name
     */
    public function createSlug($name)
    {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        
        // Ensure uniqueness
        $originalSlug = $slug;
        $counter = 1;
        while ($this->db->table('bulletin_tags')->where('slug', $slug)->get()->getRowArray()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    /**
     * Get or create tag
     */
    public function getOrCreateTag($name)
    {
        $tag = $this->db->table('bulletin_tags')
            ->where('name', $name)
            ->get()
            ->getRowArray();

        if ($tag) {
            return $tag;
        }

        $slug = $this->createSlug($name);
        $data = [
            'name' => $name,
            'slug' => $slug,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->db->table('bulletin_tags')->insert($data);
        $insertId = $this->db->insertID();

        return $this->db->table('bulletin_tags')
            ->where('id', $insertId)
            ->get()
            ->getRowArray();
    }

    /**
     * Get post tags
     */
    public function getPostTags($postId)
    {
        return $this->db->table('bulletin_post_tags bpt')
            ->select('bt.*')
            ->join('bulletin_tags bt', 'bt.id = bpt.tag_id')
            ->where('bpt.post_id', $postId)
            ->get()
            ->getResultArray();
    }

    /**
     * Add tags to post
     */
    public function addTagsToPost($postId, $tagNames)
    {
        if (empty($tagNames)) {
            return true;
        }

        // Remove existing tags
        $this->db->table('bulletin_post_tags')
            ->where('post_id', $postId)
            ->delete();

        // Add new tags
        foreach ($tagNames as $tagName) {
            $tagName = trim($tagName);
            if (empty($tagName)) continue;

            $tag = $this->getOrCreateTag($tagName);
            
            $this->db->table('bulletin_post_tags')->insert([
                'post_id' => $postId,
                'tag_id' => $tag['id']
            ]);
        }

        return true;
    }

    // ========== COMMENT METHODS ========== //

    /**
     * Get post comments
     */
    public function getPostComments($postId)
    {
        return $this->db->table('bulletin_comments bc')
            ->select('bc.*, u.first_name, u.last_name, u.username')
            ->join('user u', 'u.id = bc.user_id', 'left')
            ->where('bc.post_id', $postId)
            ->orderBy('bc.created_at', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Add comment to post
     */
    public function addComment($postId, $userId, $content)
    {
        $data = [
            'post_id' => $postId,
            'user_id' => $userId,
            'content' => $content,
            'created_at' => date('Y-m-d H:i:s')
        ];

        return $this->db->table('bulletin_comments')->insert($data);
    }

    /**
     * Delete comment
     */
    public function deleteComment($commentId, $userId = null)
    {
        $builder = $this->db->table('bulletin_comments')
            ->where('id', $commentId);

        if ($userId) {
            $builder->where('user_id', $userId);
        }

        return $builder->delete();
    }
}
