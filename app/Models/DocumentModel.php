<?php
namespace App\Models;

use CodeIgniter\Model;

class DocumentModel extends Model
{
    protected $table = 'documents';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'filename',
        'title',
        'filepath',
        'uploaded_by',
        'uploaded_at',
        'filesize',
        'mimetype',
        'description',
        'tags',
        'downloadable',
        'approval_status',
        'approver',
        'approval_at',
        'approval_comment',
        'thumbnail_path',
        'visibility',
    ];
    protected $useTimestamps = false;
    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $validationRules = [
        'title' => 'permit_empty|max_length[255]',
        'filename' => 'required|max_length[255]',
        'filepath' => 'required|max_length[255]',
        'uploaded_by' => 'required|max_length[100]',
        'uploaded_at' => 'required|valid_date',
        'filesize' => 'required|integer|greater_than[0]',
        'mimetype' => 'required|max_length[100]',
        'description' => 'permit_empty|max_length[65535]',
        'tags' => 'permit_empty|max_length[255]',
        'downloadable' => 'permit_empty|in_list[0,1]',
        'approval_status' => 'permit_empty|in_list[pending,approved,rejected]',
        'approver' => 'permit_empty|max_length[100]',
        'approval_at' => 'permit_empty|valid_date',
        'approval_comment' => 'permit_empty|max_length[65535]',
        'thumbnail_path' => 'permit_empty|max_length[255]',
        'visibility' => 'permit_empty|in_list[SK,KK]',
    ];

    protected $validationMessages = [
        'filename' => [
            'required' => 'Filename is required',
            'max_length' => 'Filename cannot exceed 255 characters'
        ],
        'filepath' => [
            'required' => 'File path is required',
            'max_length' => 'File path cannot exceed 255 characters'
        ],
        'uploaded_by' => [
            'required' => 'Uploader information is required',
            'max_length' => 'Uploader name cannot exceed 100 characters'
        ],
        'uploaded_at' => [
            'required' => 'Upload date is required',
            'valid_date' => 'Upload date must be a valid date'
        ],
        'filesize' => [
            'required' => 'File size is required',
            'integer' => 'File size must be a valid integer',
            'greater_than' => 'File size must be greater than 0'
        ],
        'mimetype' => [
            'required' => 'MIME type is required',
            'max_length' => 'MIME type cannot exceed 100 characters'
        ],
        'approval_status' => [
            'in_list' => 'Approval status must be pending, approved, or rejected'
        ],
        'visibility' => [
            'in_list' => 'Visibility must be SK or KK'
        ]
    ];

    /**
     * Submit a document for approval (set status to pending)
     */
    public function submitForApproval($documentId)
    {
        return $this->update($documentId, [
            'approval_status' => 'pending',
            'approver' => null,
            'approval_at' => null,
            'approval_comment' => null,
        ]);
    }

    /**
     * Approve a document
     */
    public function approve($documentId, $approver, $comment = null)
    {
        return $this->update($documentId, [
            'approval_status' => 'approved',
            'approver' => $approver,
            'approval_at' => date('Y-m-d H:i:s'),
            'approval_comment' => $comment,
        ]);
    }

    /**
     * Reject a document
     */
    public function reject($documentId, $approver, $comment = null)
    {
        return $this->update($documentId, [
            'approval_status' => 'rejected',
            'approver' => $approver,
            'approval_at' => date('Y-m-d H:i:s'),
            'approval_comment' => $comment,
        ]);
    }

    /**
     * Get approval status and history for a document
     */
    public function getApprovalStatus($documentId)
    {
        return $this->select('approval_status, approver, approval_at, approval_comment')
            ->find($documentId);
    }

    /**
     * Get documents with their categories
     */
    public function getDocumentsWithCategories($limit = null, $offset = null)
    {
        $builder = $this->db->table($this->table)
            ->select('documents.*, GROUP_CONCAT(categories.name) as category_names')
            ->join('document_category', 'document_category.document_id = documents.id', 'left')
            ->join('categories', 'categories.id = document_category.category_id', 'left')
            ->groupBy('documents.id')
            ->orderBy('documents.uploaded_at', 'DESC');

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Get documents by visibility and role
     */
    public function getDocumentsByRole($role, $limit = null, $offset = null)
    {
        $builder = $this->where('visibility', $role)
            ->where('approval_status', 'approved')
            ->orderBy('uploaded_at', 'DESC');

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->findAll();
    }

    /**
     * Get documents by uploader
     */
    public function getDocumentsByUploader($uploader, $limit = null, $offset = null)
    {
        $builder = $this->where('uploaded_by', $uploader)
            ->orderBy('uploaded_at', 'DESC');

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->findAll();
    }

    /**
     * Search documents
     */
    public function searchDocuments($query, $role = null, $limit = null, $offset = null)
    {
        $builder = $this->groupStart()
            ->like('filename', $query)
            ->orLike('description', $query)
            ->orLike('tags', $query)
            ->groupEnd();

        if ($role) {
            $builder->where('visibility', $role);
        }

        $builder->where('approval_status', 'approved')
            ->orderBy('uploaded_at', 'DESC');

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->findAll();
    }

    /**
     * Get document statistics
     */
    public function getDocumentStats()
    {
        $stats = [];

        // Total documents
        $stats['total'] = $this->countAll();

        // By approval status
        $stats['pending'] = $this->where('approval_status', 'pending')->countAllResults(false);
        $stats['approved'] = $this->where('approval_status', 'approved')->countAllResults(false);
        $stats['rejected'] = $this->where('approval_status', 'rejected')->countAllResults(false);

        // By visibility
        $stats['sk_documents'] = $this->where('visibility', 'SK')->countAllResults(false);
        $stats['kk_documents'] = $this->where('visibility', 'KK')->countAllResults(false);

        // Recent uploads (last 7 days)
        $stats['recent'] = $this->where('uploaded_at >=', date('Y-m-d H:i:s', strtotime('-7 days')))
            ->countAllResults(false);

        return $stats;
    }

    /**
     * Validate file upload
     */
    public function validateFileUpload($file, $allowedTypes = [], $maxSize = null)
    {
        $errors = [];

        if (!$file->isValid()) {
            $errors[] = 'Invalid file upload';
            return $errors;
        }

        // Check file size
        if ($maxSize && $file->getSize() > $maxSize) {
            $errors[] = 'File size exceeds maximum allowed size';
        }

        // Check file type
        if (!empty($allowedTypes) && !in_array($file->getMimeType(), $allowedTypes)) {
            $errors[] = 'File type not allowed';
        }

        return $errors;
    }

    /**
     * Get documents for KK users (approved documents from SK/super_admin)
     */
    public function getKKDocuments($username = null, $limit = null, $offset = null)
    {
        $builder = $this->where('approval_status', 'approved')
            ->where('visibility', 'KK')
            ->orderBy('uploaded_at', 'DESC');

        if ($username) {
            $builder->orWhere('uploaded_by', $username);
        }

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->findAll();
    }

    /**
     * Get documents for SK users (approved documents from super_admin)
     */
    public function getSKDocuments($username = null, $limit = null, $offset = null)
    {
        $builder = $this->where('approval_status', 'approved')
            ->where('visibility', 'SK')
            ->orderBy('uploaded_at', 'DESC');

        if ($username) {
            $builder->orWhere('uploaded_by', $username);
        }

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->findAll();
    }

    /**
     * Get all documents for super admin
     */
    public function getAllDocumentsForSuperAdmin($limit = null, $offset = null)
    {
        $builder = $this->orderBy('uploaded_at', 'DESC');

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->findAll();
    }

    // =====================================
    // DOCUMENT SHARING FUNCTIONALITY
    // =====================================

    /**
     * Get active shares for a document
     */
    public function getActiveShares($documentId)
    {
        return $this->db->table('document_shares')
                   ->where('document_id', $documentId)
                   ->where('is_active', true)
                   ->where('(expires_at IS NULL OR expires_at > NOW())')
                   ->get()->getResultArray();
    }

    /**
     * Check if user has permission to access document
     */
    public function hasPermission($documentId, $user, $permission = 'view')
    {
        $share = $this->db->table('document_shares')
                     ->where('document_id', $documentId)
                     ->where('shared_with', $user)
                     ->where('is_active', true)
                     ->where('(expires_at IS NULL OR expires_at > NOW())')
                     ->get()->getRowArray();
        
        if (!$share) {
            return false;
        }
        
        $permissionLevels = [
            'view' => 1,
            'download' => 2,
            'edit' => 3,
            'admin' => 4
        ];
        
        $userLevel = $permissionLevels[$share['permissions']] ?? 0;
        $requiredLevel = $permissionLevels[$permission] ?? 0;
        
        return $userLevel >= $requiredLevel;
    }

    /**
     * Get documents shared with a user
     */
    public function getSharedDocuments($user)
    {
        return $this->db->table('document_shares')
                   ->select('document_shares.*, documents.*')
                   ->join('documents', 'documents.id = document_shares.document_id')
                   ->where('shared_with', $user)
                   ->where('is_active', true)
                   ->where('(expires_at IS NULL OR expires_at > NOW())')
                   ->get()->getResultArray();
    }

    /**
     * Create a document share
     */
    public function createShare($documentId, $sharedBy, $sharedWith, $permissions, $expiresAt = null)
    {
        return $this->db->table('document_shares')->insert([
            'document_id' => $documentId,
            'shared_by' => $sharedBy,
            'shared_with' => $sharedWith,
            'permissions' => $permissions,
            'expires_at' => $expiresAt,
            'shared_at' => date('Y-m-d H:i:s'),
            'is_active' => true
        ]);
    }

    /**
     * Revoke a document share
     */
    public function revokeShare($shareId)
    {
        return $this->db->table('document_shares')
                   ->where('id', $shareId)
                   ->update(['is_active' => false]);
    }

    /**
     * Check if a document is shared with a specific user
     */
    public function isDocumentSharedWithUser($documentId, $username)
    {
        $usernameLower = strtolower(trim($username));
        $share = $this->db->table('document_shares')
            ->where('document_id', $documentId)
            ->groupStart()
                ->where('shared_with', $username)
                ->orWhere('LOWER(shared_with)', $usernameLower)
            ->groupEnd()
            ->where('is_active', true)
            ->where('(expires_at IS NULL OR expires_at > NOW())')
            ->get()->getRowArray();

        return (bool) $share;
    }

    /**
     * Deactivate expired shares
     */
    public function deactivateExpiredShares()
    {
        return $this->db->table('document_shares')
                   ->where('expires_at IS NOT NULL')
                   ->where('expires_at < NOW()')
                   ->where('is_active', true)
                   ->update(['is_active' => false]);
    }

    // =====================================
    // CATEGORY FUNCTIONALITY
    // =====================================

    /**
     * Get all categories
     */
    public function getCategories()
    {
        return $this->db->table('categories')
            ->orderBy('name', 'ASC')
            ->get()->getResultArray();
    }

    /**
     * Get categories with document count
     */
    public function getCategoriesWithDocumentCount()
    {
        return $this->db->table('categories')
            ->select('categories.*, COUNT(document_category.document_id) as document_count')
            ->join('document_category', 'document_category.category_id = categories.id', 'left')
            ->groupBy('categories.id')
            ->orderBy('categories.name', 'ASC')
            ->get()->getResultArray();
    }

    /**
     * Create a new category
     */
    public function createCategory($name)
    {
        return $this->db->table('categories')->insert([
            'name' => $name,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Update category
     */
    public function updateCategory($categoryId, $name)
    {
        return $this->db->table('categories')
            ->where('id', $categoryId)
            ->update([
                'name' => $name,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
    }

    /**
     * Delete category
     */
    public function deleteCategory($categoryId)
    {
        return $this->db->table('categories')
            ->where('id', $categoryId)
            ->delete();
    }

    /**
     * Get all tag names
     */
    public function getAllTagNames()
    {
        $result = $this->db->table('tags')
            ->select('name')
            ->orderBy('name', 'ASC')
            ->get()->getResultArray();
        
        // Extract just the tag names as strings
        return array_column($result, 'name');
    }

    /**
     * Get tag names for a specific document
     */
    public function getTagNamesForDocument($documentId)
    {
        $result = $this->db->table('document_tag')
            ->select('tags.name')
            ->join('tags', 'tags.id = document_tag.tag_id')
            ->where('document_tag.document_id', $documentId)
            ->orderBy('tags.name', 'ASC')
            ->get()->getResultArray();
        
        // Extract just the names into a simple array
        return array_column($result, 'name');
    }

    // =====================================
    // AUDIT LOG FUNCTIONALITY
    // =====================================

    /**
     * Log an action performed on a document
     */
    public function logAction($documentId, $action, $performedBy)
    {
        return $this->db->table('audit_logs')->insert([
            'document_id' => $documentId,
            'action' => $action,
            'performed_by' => $performedBy,
            'performed_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Get audit log for a specific document
     */
    public function getDocumentAuditLog($documentId, $limit = null, $offset = null)
    {
        $builder = $this->db->table('audit_logs')
            ->where('document_id', $documentId)
            ->orderBy('performed_at', 'DESC');

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Get recent audit activities
     */
    public function getRecentActivities($limit = 50)
    {
        return $this->db->table('audit_logs')
            ->select('audit_logs.*, documents.filename')
            ->join('documents', 'documents.id = audit_logs.document_id', 'left')
            ->orderBy('audit_logs.performed_at', 'DESC')
            ->limit($limit)
            ->get()->getResultArray();
    }

    // =====================================
    // USER FUNCTIONALITY (for document module)
    // =====================================

    /**
     * Get user by username
     */
    public function getUserByUsername(string $username)
    {
        $user = $this->db->table('user')
            ->where('username', $username)
            ->get()->getRowArray();
        
        if ($user) {
            // Map user_type to role for consistency with authentication system
            switch ($user['user_type']) {
                case 1:
                    $user['role'] = 'user'; // KK users
                    break;
                case 2:
                    $user['role'] = 'admin'; // SK users
                    break;
                case 3:
                    $user['role'] = 'super_admin'; // Pederasyon users
                    break;
                default:
                    $user['role'] = 'user'; // Default fallback
                    break;
            }
        }
        
        return $user;
    }

    /**
     * Verify user credentials
     */
    public function verifyCredentials(string $username, string $password)
    {
        $user = $this->getUserByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    /**
     * Get users by role
     */
    public function getUsersByRole(string $role)
    {
        return $this->db->table('user')
            ->where('role', $role)
            ->get()->getResultArray();
    }

    /**
     * Get users by multiple usernames
     */
    public function getUsersByUsernames(array $usernames)
    {
        if (empty($usernames)) {
            return [];
        }
        $users = $this->db->table('user')
            ->select('username, user_type')
            ->whereIn('username', $usernames)
            ->get()->getResultArray();
        
        // Map user_type to role for consistency with authentication system
        foreach ($users as &$user) {
            switch ($user['user_type']) {
                case 1:
                    $user['role'] = 'user'; // KK users
                    break;
                case 2:
                    $user['role'] = 'admin'; // SK users
                    break;
                case 3:
                    $user['role'] = 'super_admin'; // Pederasyon users
                    break;
                default:
                    $user['role'] = 'user'; // Default fallback
                    break;
            }
        }
        
        return $users;
    }

    /**
     * Get user role by username
     */
    public function getUserRole(string $username)
    {
        $user = $this->db->table('user')
            ->select('user_type')
            ->where('username', $username)
            ->get()->getRowArray();
        
        if (!$user) {
            return null;
        }
        
        // Map user_type to role for consistency with authentication system
        switch ($user['user_type']) {
            case 1:
                return 'user'; // KK users
            case 2:
                return 'admin'; // SK users
            case 3:
                return 'super_admin'; // Pederasyon users
            default:
                return 'user'; // Default fallback
        }
    }

    /**
     * Get latest version number for a document
     */
    public function getLatestVersionNumber($documentId)
    {
        $result = $this->db->table('document_versions')
            ->select('version_number')
            ->where('document_id', $documentId)
            ->orderBy('version_number', 'DESC')
            ->limit(1)
            ->get()->getRowArray();
        return $result ? $result['version_number'] : 0;
    }

    /**
     * Add a new document version
     */
    public function addDocumentVersion($documentId, $versionData)
    {
        $versionData['document_id'] = $documentId;
        // Note: removed created_at as this column doesn't exist in document_versions table
        return $this->db->table('document_versions')->insert($versionData);
    }

    /**
     * Get or create a tag by name
     */
    public function getOrCreateTagByName($tagName)
    {
        // First, try to find existing tag
        $tag = $this->db->table('tags')
            ->where('name', $tagName)
            ->get()->getRowArray();
        
        if ($tag) {
            return $tag;
        }
        
        // Create new tag if it doesn't exist
        $this->db->table('tags')->insert([
            'name' => $tagName
            // Note: removed created_at/updated_at as these might not exist in tags table
        ]);
        
        $tagId = $this->db->insertID();
        return $this->db->table('tags')->where('id', $tagId)->get()->getRowArray();
    }

    /**
     * Link a document to a tag
     */
    public function linkDocumentTag($documentId, $tagId)
    {
        // Check if link already exists
        $existing = $this->db->table('document_tag')
            ->where('document_id', $documentId)
            ->where('tag_id', $tagId)
            ->get()->getRowArray();
        
        if (!$existing) {
            return $this->db->table('document_tag')->insert([
                'document_id' => $documentId,
                'tag_id' => $tagId
            ]);
        }
        
        return true;
    }

    /**
     * Clear all tag links for a document
     */
    public function clearDocumentTags($documentId)
    {
        return $this->db->table('document_tag')
            ->where('document_id', $documentId)
            ->delete();
    }

    /**
     * Get all documents (simple method for compatibility)
     */
    public function getAllDocuments()
    {
        return $this->findAll();
    }

    /**
     * Get all categories (simple method for compatibility)
     */
    public function getAllCategories()
    {
        return $this->db->table('categories')->get()->getResultArray();
    }
}
