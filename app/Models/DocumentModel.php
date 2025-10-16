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
        'filepath',
        'uploaded_by',
        'uploaded_at',
        'filesize',
        'mimetype',
        'description',
        'tags',
        'downloadable',
        'thumbnail_path',
        'visibility',
        'barangay_id',
        'visibility_scope',
    ];
    protected $useTimestamps = false;
    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $validationRules = [
        'filename' => 'required|max_length[255]',
        'filepath' => 'required|max_length[255]',
        'uploaded_by' => 'required|max_length[100]',
        'uploaded_at' => 'required|valid_date',
        'filesize' => 'required|integer|greater_than[0]',
        'mimetype' => 'required|max_length[100]',
        'description' => 'permit_empty|max_length[65535]',
        'tags' => 'permit_empty|max_length[255]',
        'downloadable' => 'permit_empty|in_list[0,1]',
        'thumbnail_path' => 'permit_empty|max_length[255]',
        'visibility' => 'permit_empty|in_list[pederasyon,sk,kk]',
        'barangay_id' => 'permit_empty|integer',
        'visibility_scope' => 'permit_empty|in_list[all,specific_barangay]',
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
        'visibility' => [
            'in_list' => 'Visibility must be pederasyon, sk, or kk'
        ],
        'visibility_scope' => [
            'in_list' => 'Visibility scope must be all or specific_barangay'
        ]
    ];

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
     * Updated to support new visibility system
     */
    public function getDocumentsByVisibility($visibility, $barangayId = null, $limit = null, $offset = null)
    {
        $builder = $this->where('visibility', $visibility)
            ->orderBy('uploaded_at', 'DESC');

        // If barangay_id is specified, filter by it
        if ($barangayId !== null) {
            $builder->groupStart()
                ->where('barangay_id', $barangayId)
                ->orWhere('barangay_id', null) // Include city-wide documents
                ->groupEnd();
        }

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
     * Search documents with new visibility system
     */
    public function searchDocuments($query, $visibility = null, $barangayId = null, $limit = null, $offset = null)
    {
        $builder = $this->groupStart()
            ->like('filename', $query)
            ->orLike('description', $query)
            ->orLike('tags', $query)
            ->groupEnd();

        if ($visibility) {
            $builder->where('visibility', $visibility);
        }

        if ($barangayId !== null) {
            $builder->groupStart()
                ->where('barangay_id', $barangayId)
                ->orWhere('barangay_id', null)
                ->groupEnd();
        }

        $builder->orderBy('uploaded_at', 'DESC');

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->findAll();
    }

    /**
     * Get document statistics with new visibility system
     */
    public function getDocumentStats()
    {
        $stats = [];

        // Total documents
        $stats['total'] = $this->countAll();

        // By visibility
        $stats['pederasyon_documents'] = $this->where('visibility', 'pederasyon')->countAllResults(false);
        $stats['sk_documents'] = $this->where('visibility', 'sk')->countAllResults(false);
        $stats['kk_documents'] = $this->where('visibility', 'kk')->countAllResults(false);

        // Recent uploads (last 7 days)
        $stats['recent'] = $this->where('uploaded_at >=', date('Y-m-d H:i:s', strtotime('-7 days')))
            ->countAllResults(false);

        // By visibility scope
        $stats['city_wide'] = $this->where('visibility_scope', 'all')
            ->orWhere('barangay_id', null)
            ->countAllResults(false);
        $stats['barangay_specific'] = $this->where('visibility_scope', 'specific_barangay')
            ->where('barangay_id IS NOT NULL')
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
     * Get documents for KK users (documents with 'kk' visibility)
     * Supports barangay-specific filtering
     */
    public function getKKDocuments($barangayId = null, $username = null, $limit = null, $offset = null)
    {
        $builder = $this->where('visibility', 'kk')
            ->orderBy('uploaded_at', 'DESC');

        // Filter by barangay if specified
        if ($barangayId !== null) {
            $builder->groupStart()
                ->where('barangay_id', $barangayId)
                ->orWhere('barangay_id', null) // Include city-wide KK documents
                ->groupEnd();
        }

        // Optionally filter by uploader
        if ($username) {
            $builder->orWhere('uploaded_by', $username);
        }

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        return $builder->findAll();
    }

    /**
     * Get documents for SK users (documents with 'sk' visibility)
     * Supports barangay-specific filtering
     */
    public function getSKDocuments($barangayId = null, $username = null, $limit = null, $offset = null)
    {
        $builder = $this->where('visibility', 'sk')
            ->orderBy('uploaded_at', 'DESC');

        // Filter by barangay if specified
        if ($barangayId !== null) {
            $builder->groupStart()
                ->where('barangay_id', $barangayId)
                ->orWhere('barangay_id', null) // Include city-wide SK documents
                ->groupEnd();
        }

        // Optionally filter by uploader
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
     * Get user's barangay ID by username
     */
    public function getUserBarangayId(string $username)
    {
        $result = $this->db->table('user')
            ->select('address.barangay')
            ->join('address', 'user.id = address.user_id', 'left')
            ->where('user.username', $username)
            ->get()->getRowArray();
        
        return $result ? $result['barangay'] : null;
    }

    /**
     * Get barangay name by ID
     */
    public function getBarangayName(int $barangayId)
    {
        $result = $this->db->table('barangay')
            ->select('name')
            ->where('barangay_id', $barangayId)
            ->get()->getRowArray();
        
        return $result ? $result['name'] : null;
    }

    /**
     * Get all barangays for dropdown/selection
     */
    public function getAllBarangays()
    {
        return $this->db->table('barangay')
            ->select('barangay_id, name')
            ->orderBy('name', 'ASC')
            ->get()->getResultArray();
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
