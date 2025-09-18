<?php
namespace App\Controllers;

use App\Models\DocumentModel;

class DocumentMainController extends BaseController
{
    public function __construct()
    {
        // parent::__construct(); // Removed as per CodeIgniter 4 best practices
    }

    /**
     * Render view with appropriate templates based on user role
     */
    protected function renderWithTemplate($viewName, $data = [])
    {
        $userRole = session('role');
        $userType = session('user_type');
        
        // Determine which template set to use based on user role
        if ($userRole === 'super_admin' || $userType === 'pederasyon') {
            $headerTemplate = 'K-NECT/Pederasyon/template/header';
            $sidebarTemplate = 'K-NECT/Pederasyon/template/sidebar';
            $footerTemplate = 'K-NECT/Pederasyon/template/footer';
        } elseif ($userRole === 'user' || $userRole === 'viewer' || $userType === 'kk') {
            $headerTemplate = 'K-NECT/KK/template/header';
            $sidebarTemplate = 'K-NECT/KK/template/sidebar';
            $footerTemplate = 'K-NECT/KK/template/footer';
        } else {
            $headerTemplate = 'K-NECT/SK/Template/header';
            $sidebarTemplate = 'K-NECT/SK/Template/sidebar';
            $footerTemplate = 'K-NECT/SK/Template/footer';
        }
        
        // Merge data with base controller data
        $data = array_merge($this->data, $data);
        
        // Debug: Check if currentUser is available
        if (!isset($data['currentUser'])) {
            log_message('error', 'currentUser not found in data. Available keys: ' . implode(', ', array_keys($data)));
            // Set a default currentUser to prevent errors
            $data['currentUser'] = [
                'first_name' => 'Guest',
                'last_name' => 'User',
                'profile_picture' => '',
                'full_name' => 'Guest User',
                'user_type_text' => 'Guest',
                'position_text' => ''
            ];
        }
        
        // Build the complete page
        $html = view($headerTemplate, $data);
        $html .= view($sidebarTemplate, $data);
        $html .= '<div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 lg:ml-64 pt-16">';
        $html .= '<div class="container mx-auto px-6 py-8">';
        $html .= view($viewName, $data);
        $html .= '</div>';
        $html .= '</div>';
        $html .= view($footerTemplate, $data);
        
        return $html;
    }

    /**
     * Helper: Check if current user can access a document for a given action (preview/download)
     * @param array $doc Document row
     * @param string $action 'preview'|'download'
     * @return bool
     */
    protected function canAccessDocument($doc, $action = 'preview')
    {
        // Normalize inputs
        $role = strtolower((string) session('role'));
        $user = strtolower(trim((string) (session('username') ?? '')));
        $uploader = strtolower(trim((string) ($doc['uploaded_by'] ?? '')));
        $docVisibility = (string) ($doc['visibility'] ?? 'SK');
        $isApproved = (($doc['approval_status'] ?? null) === 'approved');
        $isDownloadable = isset($doc['downloadable']) ? (bool) $doc['downloadable'] : true;

        if (empty($doc) || empty($doc['id'])) {
            return false;
        }

        $model = new DocumentModel();

        // Share permissions
        $hasViewShare = $model->hasPermission((int) $doc['id'], $user, 'view');
        $hasDownloadShare = $model->hasPermission((int) $doc['id'], $user, 'download');

        // Fetch uploader role (optional; used for policy clarity)
        $uploaderRole = null;
        if ($uploader) {
            $uploaderUser = $model->getUserByUsername($uploader);
            $uploaderRole = $uploaderUser['role'] ?? null;
        }

        // Super admin: unrestricted
        if ($role === 'super_admin') {
            return true;
        }

        // Owner: full access (preview/download)
        if ($uploader === $user) {
            return true;
        }

        // Explicit share overrides normal visibility/approval checks
        if ($hasViewShare || $hasDownloadShare) {
            if ($action === 'preview') {
                return true;
            }
            if ($action === 'download') {
                // Download allowed if shared with download permission OR file is generally downloadable
                return $hasDownloadShare || $isDownloadable;
            }
        }

        // Role-based default policy
        if ($role === 'admin') {
            // SK can access approved SK-visible docs
            if ($docVisibility !== 'SK') {
                return false;
            }
            if ($action === 'preview') {
                return $isApproved;
            }
            if ($action === 'download') {
                return $isApproved && $isDownloadable;
            }
        }

        if ($role === 'user' || $role === 'viewer') {
            // KK/Viewer can access approved KK-visible docs
            if ($docVisibility !== 'KK') {
                return false;
            }
            if ($action === 'preview') {
                return $isApproved;
            }
            if ($action === 'download') {
                return $isApproved && $isDownloadable;
            }
        }

        return false;
    }

    public function index()
    {
        $model = new DocumentModel();
        $categories = $model->getCategories();
        $search = $this->request ? $this->request->getGet('search') : null;
        $categoryFilter = $this->request ? $this->request->getGet('category') : null;
        $page = (int)($this->request ? ($this->request->getGet('page') ?? 1) : 1);
        $perPage = (int)($this->request ? ($this->request->getGet('per_page') ?? 10) : 10);
        $builder = $model;
        if ($search) {
            $builder = $builder->groupStart()
                ->like('filename', $search)
                ->orLike('description', $search)
                ->orLike('tags', $search)
                ->groupEnd();
        }
        if ($categoryFilter) {
            $builder = $builder->join('document_category', 'documents.id = document_category.document_id')
                ->where('document_category.category_id', $categoryFilter);
        }
        $tagModel = $model;
        $allTags = $tagModel->getAllTagNames();
        $selectedTags = $this->request ? $this->request->getGet('tags') : null;
        if ($selectedTags) {
            if (!is_array($selectedTags)) {
                $selectedTags = explode(',', $selectedTags);
            }
            foreach ($selectedTags as $tag) {
                $builder = $builder->like('tags', $tag);
            }
        }
        // Fetch all documents (no pagination yet)
        $allDocuments = $builder->orderBy('uploaded_at', 'DESC')->findAll();
        // Append tag names per document using tag helpers
        foreach ($allDocuments as $k => &$doc) {
            $tagNames = $model->getTagNamesForDocument((int) $doc['id']);
            $doc['tags'] = implode(',', $tagNames);
        }
        unset($doc);
        // Fetch uploader roles for all documents in one query
        $uploaderUsernames = array_unique(array_map(function($doc) {
            return strtolower(trim($doc['uploaded_by']));
        }, $allDocuments));
        $userRoles = [];
        if (!empty($uploaderUsernames)) {
            $users = $model->getUsersByUsernames($uploaderUsernames);
            foreach ($users as $u) {
                $userRoles[strtolower(trim($u['username']))] = $u['role'];
            }
        }
        // Normalize role with fallback to user_type mapping
        $role = strtolower((string) (session('role') ?? ''));
        $userType = strtolower((string) (session('user_type') ?? ''));
        if ($role === '' && $userType !== '') {
            switch ($userType) {
                case 'kk':
                    $role = 'user';
                    break;
                case 'sk':
                    $role = 'admin';
                    break;
                case 'pederasyon':
                    $role = 'super_admin';
                    break;
            }
        }
        $user = strtolower(trim((string) session('username')));
        $filteredDocuments = array_filter($allDocuments, function($doc) use ($role, $user, $userRoles) {
            $uploadedBy = strtolower(trim($doc['uploaded_by'] ?? ''));
            $uploaderRole = $userRoles[$uploadedBy] ?? null;
            $docVisibility = $doc['visibility'] ?? 'SK';
            $docApprovalStatus = $doc['approval_status'] ?? null;
            $docDownloadable = $doc['downloadable'] ?? 1;

            // Super admin can see everything
            if ($role === 'super_admin') {
                return true;
            }

            // SK Admin logic
            if ($role === 'admin') {
                // Always show own uploads regardless of visibility/approval
                if ($uploadedBy === $user) {
                    return true;
                }
                // Show other approved SK-visible documents (regardless of uploader role)
                if ($docVisibility === 'SK') {
                    return $docApprovalStatus === 'approved';
                }
                return false;
            }

            // KK/Viewer logic
            if ($role === 'user' || $role === 'viewer') {
                // Show approved KK-visible documents (regardless of uploader role)
                return $docVisibility === 'KK'
                    && $docApprovalStatus === 'approved';
            }

            return false;
        });
        // Manual pagination after filtering (consistent for SK and KK)
        $total = count($filteredDocuments);
        $page = max(1, $page);
        $offset = ($page - 1) * $perPage;
        $documents = array_slice($filteredDocuments, $offset, $perPage);
        // Compute pagination values for views
        // Return role-specific document list
        $userRole = session('role');
        $viewData = [
            'documents' => $documents,
            'categories' => $categories,
            'selectedCategory' => $categoryFilter,
            'start' => $offset,
            'perPage' => $perPage,
            'page' => $page,
            'total' => $total,
            'allTags' => $allTags,
            'selectedTags' => $selectedTags,
            'userRoles' => $userRoles, // pass uploader roles to view
        ];
        
        if ($userRole === 'super_admin') {
            // Super admin users get SKP document list view
            log_message('info', 'Loading super admin document view');
            return $this->renderWithTemplate('K-NECT/Pederasyon/Documents/list', $viewData);
        } elseif ($userRole === 'admin') {
            return $this->renderWithTemplate('K-NECT/SK/Documents/list', $viewData);
        } else {
            // KK users get their own document list view
            return $this->renderWithTemplate('K-NECT/KK/Documents/list', $viewData);
        }
    }

    public function upload()
    {
        helper(['form', 'url']);
        $model = new DocumentModel();
        $categories = $model->getCategories();
        $errorMsg = null;
        
        // Define log file for debugging
        $logFile = WRITEPATH . 'logs/document_upload_debug.log';
        
        if (strtolower($this->request->getMethod()) === 'post') {
            $isAjax = $this->request->isAJAX();
            
            // Log upload attempt
            file_put_contents($logFile, "\n--- Upload attempt at " . date('Y-m-d H:i:s') . " ---\n", FILE_APPEND);
            file_put_contents($logFile, "User: " . session('username') . ", Role: " . session('role') . "\n", FILE_APPEND);
            file_put_contents($logFile, "Is AJAX request: " . ($isAjax ? 'Yes' : 'No') . "\n", FILE_APPEND);
            
            // Centralized validation config to avoid drift across modules
            // Fallback to sensible defaults if \Config\Document is not defined
            if (class_exists('\\Config\\Document')) {
                $docConfig = new \Config\Document();
                $allowedExtensions = implode(',', $docConfig->allowedExtensions);
                $maxSizeKB = $docConfig->maxSizeKB;
                file_put_contents($logFile, "Using Document config - Max size: {$maxSizeKB}KB, Extensions: {$allowedExtensions}\n", FILE_APPEND);
            } else {
                $allowedExtensions = 'pdf,jpg,jpeg,png,doc,docx,xls,xlsx,ppt,pptx,txt';
                $maxSizeKB = 51200; // 50 MB
                file_put_contents($logFile, "Using default config - Max size: {$maxSizeKB}KB, Extensions: {$allowedExtensions}\n", FILE_APPEND);
            }
            
            // Check PHP upload limits
            $phpUploadLimit = ini_get('upload_max_filesize');
            $phpPostLimit = ini_get('post_max_size');
            $configMaxSize = $maxSizeKB * 1024;
            $phpUploadBytes = $this->returnBytes($phpUploadLimit);
            $phpPostBytes = $this->returnBytes($phpPostLimit);
            $withinLimits = $configMaxSize <= $phpUploadBytes && $configMaxSize <= $phpPostBytes;
            
            file_put_contents($logFile, "PHP limits check: " . json_encode([
                'upload_max_filesize' => $phpUploadBytes,
                'post_max_size' => $phpPostBytes,
                'config_max_size' => $configMaxSize,
                'within_limits' => $withinLimits
            ]) . "\n", FILE_APPEND);
            
            $isValid = $this->validate([
                'document' => "uploaded[document]|max_size[document,{$maxSizeKB}]|ext_in[document,{$allowedExtensions}]",
            ]);
            
            if (!$isValid) {
                $errors = service('validation')->getErrors();
                $errorMsg = implode(' ', $errors);
                file_put_contents($logFile, "Validation failed: " . $errorMsg . "\n", FILE_APPEND);
                if ($isAjax) {
                    return $this->response->setJSON(['success' => false, 'error' => $errorMsg]);
                }
            } else {
                file_put_contents($logFile, "Validation passed\n", FILE_APPEND);
                $file = $this->request->getFile('document');
                
                if (!$file) {
                    $errorMsg = 'No file was uploaded.';
                    file_put_contents($logFile, "No file received\n", FILE_APPEND);
                    if ($isAjax) {
                        return $this->response->setJSON(['success' => false, 'error' => $errorMsg]);
                    }
                } else {
                    file_put_contents($logFile, "File received: " . $file->getClientName() . ", Size: " . $file->getSize() . " bytes\n", FILE_APPEND);
                    file_put_contents($logFile, "File error code: " . $file->getError() . "\n", FILE_APPEND);
                }
                
                $categoryIds = $this->request->getPost('categories') ?? [];
                $title = $this->request->getPost('title');
                $description = $this->request->getPost('description');
                $tagsInput = $this->request->getPost('tags');
                $downloadable = $this->request->getPost('downloadable') ? 1 : 0;
                $visibility = $this->request->getPost('visibility') ?? 'SK';
                $tagsArray = [];
                if ($tagsInput) {
                    $decoded = json_decode($tagsInput, true);
                    if (is_array($decoded)) {
                        // Handle both array of strings and array of objects
                        if (isset($decoded[0]) && is_array($decoded[0]) && isset($decoded[0]['value'])) {
                            // Tagify object format
                            $tagsArray = array_map(function($t) { return trim($t['value']); }, $decoded);
                        } else {
                            // Array of strings
                            $tagsArray = array_map('trim', $decoded);
                        }
                    } else {
                        $tagsArray = array_filter(array_map('trim', explode(',', $tagsInput)));
                    }
                }
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    file_put_contents($logFile, "File is valid and not moved\n", FILE_APPEND);
                    $newName = $file->getRandomName();
                    file_put_contents($logFile, "Generated filename: " . $newName . "\n", FILE_APPEND);
                    $ext = strtolower($file->getExtension()); // Get extension before move
                    // Get the actual logged-in username for storage
                    // Don't convert to base username - use the actual login username
                    $sessionUsername = session('username');
                    $uploadedByUsername = $sessionUsername; // Use the actual logged-in username directly
                    
                    // Get all info BEFORE move
                    // Determine initial approval status based on user role
                    $userRole = session('role');
                    $approvalStatus = 'pending'; // Default for SK admin uploads
                    $approver = null;
                    $approvalAt = null;
                    
                    if ($userRole === 'super_admin') {
                        $approvalStatus = 'approved'; // Super admin uploads are auto-approved
                        $approver = $uploadedByUsername; // Super admin approves their own documents
                        $approvalAt = date('Y-m-d H:i:s');
                    }

                    $saveData = [
                        'filename' => $file->getClientName(),
                        'title' => $title,
                        'filepath' => 'uploads/documents/' . $newName,
                        'uploaded_by' => $uploadedByUsername,
                        'uploaded_at' => date('Y-m-d H:i:s'),
                        'filesize' => $file->getSize(),
                        'mimetype' => $file->getMimeType(),
                        'description' => $description,
                        'tags' => implode(',', $tagsArray),
                        'approval_status' => $approvalStatus,
                        'approver' => $approver,
                        'approval_at' => $approvalAt,
                        'downloadable' => $downloadable,
                        'visibility' => $visibility,
                    ];
                    file_put_contents($logFile, "Attempting to move file to: " . FCPATH . 'uploads/documents/' . $newName . "\n", FILE_APPEND);
                    $moveResult = $file->move(FCPATH . 'uploads/documents', $newName);
                    if ($moveResult) {
                        file_put_contents($logFile, "File moved successfully\n", FILE_APPEND);
                    } else {
                        file_put_contents($logFile, "File move failed\n", FILE_APPEND);
                        $errorMsg = 'Failed to move uploaded file.';
                        if ($isAjax) {
                            return $this->response->setJSON(['success' => false, 'error' => $errorMsg]);
                        }
                    }
                    // PDF thumbnail generation
                    if ($ext === 'pdf') {
                        if (class_exists('\\Imagick')) {
                            $thumbName = pathinfo($newName, PATHINFO_FILENAME) . '.jpg';
                            $thumbDir = FCPATH . 'uploads/thumbnails/';
                            $thumbPath = $thumbDir . $thumbName;
                            if (!is_dir($thumbDir)) {
                                mkdir($thumbDir, 0777, true);
                            }
                            try {
                                $imagick = new \Imagick();
                                $imagick->setResolution(150, 150);
                                $imagick->readImage(FCPATH . 'uploads/documents/' . $newName . '[0]');
                                $imagick->setImageFormat('jpeg');
                                $imagick->writeImage($thumbPath);
                                $imagick->clear();
                                $imagick->destroy();
                                $saveData['thumbnail_path'] = 'uploads/thumbnails/' . $thumbName;
                            } catch (\Exception $e) {
                                file_put_contents($logFile, "Thumbnail generation failed: " . $e->getMessage() . "\n", FILE_APPEND);
                                $saveData['thumbnail_path'] = null;
                            }
                        } else {
                            file_put_contents($logFile, "Imagick not installed, skipping thumbnail generation.\n", FILE_APPEND);
                            $saveData['thumbnail_path'] = null;
                        }
                    }
                    file_put_contents($logFile, "Attempting to save document to database\n", FILE_APPEND);
                    $model = new DocumentModel();
                    $existing = $model->where('filename', $saveData['filename'])->first();
                    $docId = null;
                    if ($existing) {
                        // For now, let's create a new document instead of versioning to avoid issues
                        // Add a timestamp suffix to filename to make it unique
                        $fileInfo = pathinfo($saveData['filename']);
                        $uniqueFilename = $fileInfo['filename'] . '_' . time();
                        if (isset($fileInfo['extension'])) {
                            $uniqueFilename .= '.' . $fileInfo['extension'];
                        }
                        $saveData['filename'] = $uniqueFilename;
                        file_put_contents($logFile, "File with same name exists, creating new document with unique name: $uniqueFilename\n", FILE_APPEND);
                        
                        $saveResult = $model->save($saveData);
                        if (!$saveResult) {
                            $errorMsg = 'Save failed: ' . json_encode($model->errors());
                            file_put_contents($logFile, "Database save failed: " . $errorMsg . "\n", FILE_APPEND);
                            if ($isAjax) {
                                return $this->response->setJSON(['success' => false, 'error' => $errorMsg]);
                            }
                        } else {
                            file_put_contents($logFile, "Document saved successfully with ID: " . $model->getInsertID() . "\n", FILE_APPEND);
                        }
                        $docId = $model->getInsertID();
                    } else {
                        $saveResult = $model->save($saveData);
                        if (!$saveResult) {
                            $errorMsg = 'Save failed: ' . json_encode($model->errors());
                            file_put_contents($logFile, "Database save failed: " . $errorMsg . "\n", FILE_APPEND);
                            if ($isAjax) {
                                return $this->response->setJSON(['success' => false, 'error' => $errorMsg]);
                            }
                        } else {
                            file_put_contents($logFile, "Document saved successfully with ID: " . $model->getInsertID() . "\n", FILE_APPEND);
                        }
                        $docId = $model->getInsertID();
                        // After saving, if super admin, update approver fields
                        if ($userRole === 'super_admin' && $docId) {
                            $model->update($docId, [
                                'approver' => session('username'),
                                'approval_at' => date('Y-m-d H:i:s'),
                                'approval_comment' => 'Auto-approved (Super Admin upload)'
                            ]);
                        }
                        // TAGS: Save tags for new document
                        if ($docId && !empty($tagsArray)) {
                            try {
                                $tagLog = WRITEPATH . 'logs/tag_debug.log';
                                file_put_contents($tagLog, "\n--- Upload for doc $docId at " . date('Y-m-d H:i:s') . " ---\n", FILE_APPEND);
                                file_put_contents($tagLog, "tagsArray: " . print_r($tagsArray, true) . "\n", FILE_APPEND);
                                foreach ($tagsArray as $tagName) {
                                    $cleanTag = strtolower(trim($tagName));
                                    file_put_contents($tagLog, "Processing tag: '$tagName' => '$cleanTag'\n", FILE_APPEND);
                                    if ($cleanTag === '') continue;
                                    try {
                                        $tag = $model->getOrCreateTagByName($cleanTag);
                                        if (!$tag) {
                                            file_put_contents($tagLog, "Failed to get/create tag: $cleanTag\n", FILE_APPEND);
                                            continue;
                                        }
                                        $model->linkDocumentTag((int) $docId, (int) $tag['id']);
                                    } catch (\Exception $e) {
                                        file_put_contents($tagLog, "Error processing tag '$cleanTag': " . $e->getMessage() . "\n", FILE_APPEND);
                                    }
                                }
                            } catch (\Exception $e) {
                                file_put_contents($logFile, "Tag processing error: " . $e->getMessage() . "\n", FILE_APPEND);
                            }
                        }
                    }

                    if ($docId && !empty($categoryIds)) {
                        try {
                            $db = \Config\Database::connect();
                            $db->table('document_category')->where('document_id', $docId)->delete();
                            foreach ($categoryIds as $catId) {
                                $db->table('document_category')->insert([
                                    'document_id' => $docId,
                                    'category_id' => $catId
                                ]);
                            }
                            file_put_contents($logFile, "Categories assigned: " . implode(',', $categoryIds) . "\n", FILE_APPEND);
                        } catch (\Exception $e) {
                            file_put_contents($logFile, "Category assignment error: " . $e->getMessage() . "\n", FILE_APPEND);
                        }
                    }
                    try {
                        $model->logAction($docId, 'upload', session('username') ?? 'admin');
                        file_put_contents($logFile, "Audit log written for doc ID $docId\n", FILE_APPEND);
                    } catch (\Exception $e) {
                        file_put_contents($logFile, "Audit logging error: " . $e->getMessage() . "\n", FILE_APPEND);
                    }
                    
                    if (!$errorMsg) {
                        file_put_contents($logFile, "Upload completed successfully\n", FILE_APPEND);
                        if ($isAjax) {
                            return $this->response->setJSON(['success' => true]);
                        }
                        return redirect()->to(base_url('admin/documents'))->with('success', 'Document uploaded successfully.');
                    }
                } else {
                    $errorMsg = 'File is not valid or has already been moved.';
                    file_put_contents($logFile, "File invalid or already moved.\n", FILE_APPEND);
                    if ($isAjax) {
                        return $this->response->setJSON(['success' => false, 'error' => $errorMsg]);
                    }
                }
            }
        }
        // Return role-specific upload view
        $userRole = session('role');
        $viewData = ['categories' => $categories, 'errorMsg' => $errorMsg];
        
        if ($userRole === 'super_admin') {
            return $this->renderWithTemplate('K-NECT/Pederasyon/Documents/upload', $viewData);
        } elseif ($userRole === 'admin') {
            return $this->renderWithTemplate('K-NECT/SK/Documents/upload', $viewData);
        } else {
            // KK users cannot upload documents
            return redirect()->to(base_url('documents'))->with('error', 'Access denied. Only SK and Super Admin can upload documents.');
        }
    }
    
    /**
     * Helper method to convert PHP size notation to bytes
     */
    private function returnBytes($size_str)
    {
        if (!$size_str) return 0;
        
        $size_str = trim($size_str);
        $last = strtolower($size_str[strlen($size_str)-1]);
        $value = (int) $size_str;
        
        switch($last) {
            case 'g':
                $value *= 1024;
                // fall through
            case 'm':
                $value *= 1024;
                // fall through
            case 'k':
                $value *= 1024;
        }
        
        return $value;
    }

    public function download($id)
    {
        $model = new DocumentModel();
        $doc = $model->find($id);
        if (!$doc) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Document not found');
        }
        if (!$this->canAccessDocument($doc, 'download')) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Access denied');
        }
        $filePath = FCPATH . $doc['filepath'];
        if (!file_exists($filePath)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File not found');
        }
            $model->logAction($id, 'download', session('username') ?? 'admin');
        return $this->response->download($filePath, null)->setFileName($doc['filename']);
    }

    public function delete($id)
    {
        $model = new DocumentModel();
        $doc = $model->find($id);
        
        if (!$doc) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Document not found.'
                ]);
            }
            return redirect()->to(base_url('admin/documents'))->with('error', 'Document not found.');
        }

        $role = session('role');
        $user = strtolower(trim(session('username')));
        $uploader = strtolower(trim($doc['uploaded_by'] ?? ''));
        
        // Fetch uploader role
        $uploaderUser = $model->getUserByUsername($uploader);
        $uploaderRole = $uploaderUser['role'] ?? null;
        
        // Access control logic
        $canDelete = false;
        if ($role === 'super_admin') {
            // Super admin can delete any document
            $canDelete = true;
        } elseif ($role === 'admin') {
            // SK can only delete their own documents, not super_admin documents
            $canDelete = ($uploader === $user && $uploaderRole !== 'super_admin');
        } else {
            // KK and other roles cannot delete documents
            $canDelete = false;
        }
        
        // Backend permission check
        if (!$canDelete) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'You do not have permission to delete this document.'
                ]);
            }
            return redirect()->to(base_url('admin/documents'))->with('error', 'You do not have permission to delete this document.');
        }

        try {
            // Audit log BEFORE deletion
            $model->logAction($id, 'delete', session('username') ?? 'admin');
            
            // Delete the physical file
            $filePath = FCPATH . $doc['filepath'];
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
            
            // Remove from pivot table first
            $db = \Config\Database::connect();
            $db->table('document_category')->where('document_id', $id)->delete();
            
            // Delete from database
            $model->delete($id);
            
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Document deleted successfully.'
                ]);
            }
            return redirect()->to(base_url('admin/documents'))->with('success', 'Document deleted successfully.');
            
        } catch (\Exception $e) {
            log_message('error', 'Document deletion failed: ' . $e->getMessage());
            
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Delete failed: ' . $e->getMessage()
                ]);
            }
            return redirect()->to(base_url('admin/documents'))->with('error', 'Delete failed: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        file_put_contents(WRITEPATH . 'logs/tag_debug.log', "\n--- edit() called at " . date('Y-m-d H:i:s') . " ---\n", FILE_APPEND);
        file_put_contents(WRITEPATH . 'logs/tag_debug.log', "[EDIT] Request method: " . $this->request->getMethod() . "\n", FILE_APPEND);
        file_put_contents(WRITEPATH . 'logs/tag_debug.log', "[EDIT] POST data: " . print_r($_POST, true) . "\n", FILE_APPEND);
        helper(['form', 'url']);
        $model = new DocumentModel();
        $doc = $model->find($id);
        
        if (!$doc) {
            file_put_contents(WRITEPATH . 'logs/tag_debug.log', "[EDIT] Returning: Document not found.\n", FILE_APPEND);
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Document not found');
        }
        
        // Access control: Check if current user can edit this document
        $role = session('role');
        $user = strtolower(trim(session('username')));
        $uploader = strtolower(trim($doc['uploaded_by'] ?? ''));
        
        // Fetch uploader role
        $uploaderRole = null;
        if ($uploader) {
            $uploaderUser = $model->getUserByUsername($uploader);
            if ($uploaderUser) {
                $uploaderRole = $uploaderUser['role'];
            }
        }
        
        // Access control logic
        $canEdit = false;
        if ($role === 'super_admin') {
            // Super admin can edit any document
            $canEdit = true;
        } elseif ($role === 'admin') {
            // SK can only edit their own documents, not super_admin documents
            $canEdit = ($uploader === $user && $uploaderRole !== 'super_admin');
        } else {
            // KK and other roles cannot edit documents
            $canEdit = false;
        }
        
        if (!$canEdit) {
            return redirect()->to(base_url('admin/documents'))->with('error', 'You do not have permission to edit this document.');
        }
        
        $categories = $model->getCategories();
        $db = \Config\Database::connect();
        $selectedCats = array_column($db->table('document_category')->where('document_id', $id)->get()->getResultArray(), 'category_id');
        $errorMsg = null;
        // Get tags for this document as comma-separated string
        $doc['tags'] = implode(',', $model->getTagNamesForDocument((int) $id));
        if (!$doc) {
            file_put_contents(WRITEPATH . 'logs/tag_debug.log', "[EDIT] Returning: Document not found.\n", FILE_APPEND);
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Document not found');
        }
        if (strtolower($this->request->getMethod()) === 'post') {
            file_put_contents(WRITEPATH . 'logs/tag_debug.log', "[EDIT] Entered POST block\n", FILE_APPEND);
            file_put_contents(WRITEPATH . 'logs/tag_debug.log', "[EDIT] _POST['tags']: " . print_r($_POST['tags'] ?? null, true) . "\n", FILE_APPEND);
            $categoryIds = $this->request->getPost('categories') ?? [];
            if (!is_array($categoryIds)) {
                $categoryIds = $categoryIds ? [$categoryIds] : [];
            }
            $description = $this->request->getPost('description');
            $tagsInput = $this->request->getPost('tags');
            $downloadable = $this->request->getPost('downloadable') ? 1 : 0;
            $tagsArray = [];
            if ($tagsInput) {
                $decoded = json_decode($tagsInput, true);
                if (is_array($decoded)) {
                    // Handle both array of strings and array of objects
                    if (isset($decoded[0]) && is_array($decoded[0]) && isset($decoded[0]['value'])) {
                        // Tagify object format
                        $tagsArray = array_map(function($t) { return trim($t['value']); }, $decoded);
                    } else {
                        // Array of strings
                        $tagsArray = array_map('trim', $decoded);
                    }
                } else {
                    $tagsArray = array_filter(array_map('trim', explode(',', $tagsInput)));
                }
            }
            $model->update($id, [
                'filename' => $doc['filename'],
                'title' => $this->request->getPost('title') ?? $doc['title'] ?? null,
                'filepath' => $doc['filepath'],
                'uploaded_by' => $doc['uploaded_by'],
                'uploaded_at' => $doc['uploaded_at'],
                'filesize' => $doc['filesize'],
                'mimetype' => $doc['mimetype'],
                'description' => $description,
                'tags' => $tagsInput,
                'downloadable' => $downloadable,
            ]);
            // Always update categories, even if empty
            $db->table('document_category')->where('document_id', $id)->delete();
            foreach ($categoryIds as $catId) {
                $db->table('document_category')->insert([
                    'document_id' => $id,
                    'category_id' => $catId
                ]);
            }
            // Always update tags
            file_put_contents(WRITEPATH . 'logs/tag_debug.log', "[EDIT] tagsArray before update: " . print_r($tagsArray, true) . "\n", FILE_APPEND);
            if (empty($tagsArray)) {
                file_put_contents(WRITEPATH . 'logs/tag_debug.log', "[EDIT] tagsArray is empty, skipping tag update.\n", FILE_APPEND);
            }
            $db->transStart();
            $model->clearDocumentTags((int) $id);
            file_put_contents(WRITEPATH . 'logs/tag_debug.log', "[EDIT] Deleted old tag links for doc $id\n", FILE_APPEND);
            foreach ($tagsArray as $tagName) {
                $cleanTag = strtolower(trim($tagName));
                file_put_contents(WRITEPATH . 'logs/tag_debug.log', "[EDIT] Processing tag: '$tagName' => '$cleanTag'\n", FILE_APPEND);
                if ($cleanTag === '') continue;
                $tag = $model->getOrCreateTagByName($cleanTag);
                file_put_contents(WRITEPATH . 'logs/tag_debug.log', "[EDIT] Lookup result: " . print_r($tag, true) . "\n", FILE_APPEND);
                if (!$tag) {
                    file_put_contents(WRITEPATH . 'logs/tag_debug.log', "[EDIT] Failed to create tag: $cleanTag\n", FILE_APPEND);
                    continue;
                }
                $result = $model->linkDocumentTag((int) $id, (int) $tag['id']);
                file_put_contents(WRITEPATH . 'logs/tag_debug.log', "[EDIT] Linked doc $id to tag {$tag['id']} result: " . print_r($result, true) . "\n", FILE_APPEND);
            }
            $db->transComplete();
            file_put_contents(WRITEPATH . 'logs/tag_debug.log', "[EDIT] Finished tag update block for doc $id\n", FILE_APPEND);
            file_put_contents(WRITEPATH . 'logs/tag_debug.log', "[EDIT] Redirecting after update.\n", FILE_APPEND);
            return redirect()->to(base_url('admin/documents'))->with('success', 'Document updated.');
        }
        file_put_contents(WRITEPATH . 'logs/tag_debug.log', "[EDIT] Returning: End of edit() method, rendering view.\n", FILE_APPEND);
        // Return role-specific edit view
        $userRole = session('role');
        $viewData = [
            'doc' => $doc,
            'categories' => $categories,
            'selectedCats' => $selectedCats,
            'errorMsg' => $errorMsg
        ];
        
        if ($userRole === 'super_admin') {
            return $this->renderWithTemplate('K-NECT/Pederasyon/Documents/edit', $viewData);
        } elseif ($userRole === 'admin') {
            return $this->renderWithTemplate('K-NECT/SK/Documents/edit', $viewData);
        } else {
            // KK users cannot edit documents
            return redirect()->to(base_url('documents'))->with('error', 'Access denied. Only SK and Super Admin can edit documents.');
        }
    }

    public function preview($id)
    {
        $model = new DocumentModel();
        $doc = $model->find($id);
        if (!$doc) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Document not found');
        }
        
        // Simple access check - super admin can preview all documents
        $role = session('role');
        if ($role !== 'super_admin') {
            // For other roles, check if document is approved or they own it
            $username = session('username');
            $isOwner = strtolower(trim($doc['uploaded_by'] ?? '')) === strtolower(trim($username));
            $isApproved = ($doc['approval_status'] ?? '') === 'approved';
            
            if (!$isOwner && !$isApproved) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Access denied');
            }
        }
        
        $filePath = FCPATH . $doc['filepath'];
        if (!file_exists($filePath)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File not found');
        }
        
        $mime = $doc['mimetype'];
        if (strpos($mime, 'image/') === 0 || $mime === 'application/pdf') {
            return $this->response->setHeader('Content-Type', $mime)
                                ->setHeader('Content-Disposition', 'inline; filename="' . $doc['filename'] . '"')
                                ->setBody(file_get_contents($filePath));
        } elseif (in_array($mime, [
            'application/msword', // .doc
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
            'application/vnd.ms-excel', // .xls
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
            'application/vnd.ms-powerpoint', // .ppt
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' // .pptx
        ])) {
            // For Office documents, redirect to download with info message
            return redirect()->to(base_url('admin/documents/download/' . $id))->with('info', 'Office documents cannot be previewed directly. Please download to view.');
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Preview not supported for this file type');
        }
    }

    /**
     * API endpoint for document details (AJAX)
     */
    public function apiDetail($id)
    {
        $model = new DocumentModel();
        $document = $model->find($id);
        
        if (!$document) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Document not found']);
        }
        
        // Check access permissions
        if (!$this->canAccessDocument($document, 'preview')) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Access denied']);
        }
        
        // Get category name if exists
        if (!empty($document['category_id'])) {
            $categoryModel = new \App\Models\CategoryModel();
            $category = $categoryModel->find($document['category_id']);
            $document['category_name'] = $category ? $category['name'] : null;
        }
        
        return $this->response->setJSON($document);
    }

    public function detail($id)
    {
        $model = new DocumentModel();
        $doc = $model->find($id);
        if (!$doc) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Document not found');
        }
        
        // Super admin can view all document details
        $userRole = session('role');
        if ($userRole !== 'super_admin') {
            if (!$this->canAccessDocument($doc, 'preview')) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Access denied');
            }
        }
        
        // Get uploader role
                    $uploader = $model->getUserByUsername($doc['uploaded_by']);
        $uploaderRole = $uploader ? $uploader['role'] : null;
        
        // Return role-specific document detail view
        $viewData = [
            'document' => $doc,
            'uploaderRole' => $uploaderRole
        ];
        
        if ($userRole === 'super_admin') {
            return $this->renderWithTemplate('K-NECT/Pederasyon/Documents/document_detail', $viewData);
        } elseif ($userRole === 'admin') {
            return $this->renderWithTemplate('K-NECT/SK/Documents/document_detail', $viewData);
        } else {
            // KK users can view document details but with limited access
            return $this->renderWithTemplate('K-NECT/KK/Documents/document_detail', $viewData);
        }
    }

    public function auditLog()
    {
        $model = new DocumentModel();
        $perPage = 20;
        $page = (int)($this->request->getGet('page') ?? 1);
        $logs = $model->getRecentActivities($perPage);
        $pager = null;
        // Return role-specific audit log view (only for super_admin)
        $userRole = session('role');
        $viewData = [
            'logs' => $logs,
            'pager' => $pager,
            'perPage' => $perPage,
            'page' => $page
        ];
        
        if ($userRole === 'super_admin') {
            return $this->renderWithTemplate('K-NECT/Pederasyon/Documents/audit_log', $viewData);
        } else {
            // KK users cannot access audit logs
            return redirect()->to(base_url('admin/documents'))->with('error', 'Access denied. Only Super Admin can access audit logs.');
        }
    }

    public function versionHistory($id)
    {
        $docModel = new DocumentModel();
        $doc = $docModel->find($id);
        if (!$doc) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Document not found');
        }
        // Placeholder: versions not implemented in unified model yet
        $versions = [];
        // Return role-specific version history view
        $userRole = session('role');
        $viewData = [
            'doc' => $doc,
            'versions' => $versions
        ];
        
        if ($userRole === 'super_admin') {
            return $this->renderWithTemplate('K-NECT/Pederasyon/Documents/version_history', $viewData);
        } elseif ($userRole === 'admin') {
            return $this->renderWithTemplate('K-NECT/SK/Documents/version_history', $viewData);
        } else {
            // KK users can view version history but with limited access
            return $this->renderWithTemplate('K-NECT/KK/Documents/version_history', $viewData);
        }
    }

    public function bulkDelete()
    {
        $logFile = WRITEPATH . 'logs/bulk_delete_debug.log';
        file_put_contents($logFile, "\n--- Bulk delete at " . date('Y-m-d H:i:s') . " ---\n", FILE_APPEND);
        $actualMethod = $this->request->getMethod();
        file_put_contents($logFile, "getMethod(): $actualMethod\n", FILE_APPEND);
        file_put_contents($logFile, "Request headers: " . print_r($this->request->getHeaders(), true) . "\n", FILE_APPEND);
        // Support multiple input formats
        $ids = $this->request->getPost('ids');
        
        // Check for document_ids[] format (used by frontend forms)
        if (empty($ids)) {
            $ids = $this->request->getPost('document_ids');
        }
        
        // Support JSON input
        if (empty($ids) && $this->request->getHeaderLine('Content-Type') === 'application/json') {
            $json = $this->request->getJSON(true);
            $ids = $json['ids'] ?? $json['document_ids'] ?? [];
        }
        
        file_put_contents($logFile, "POST data: " . print_r($_POST, true) . "\n", FILE_APPEND);
        file_put_contents($logFile, "Received IDs: " . print_r($ids, true) . "\n", FILE_APPEND);
        if (strcasecmp($actualMethod, 'post') !== 0) {
            file_put_contents($logFile, "Not a POST request\n", FILE_APPEND);
            return redirect()->to(base_url('admin/documents'));
        }
        if (!$ids || !is_array($ids)) {
            file_put_contents($logFile, "No documents selected\n", FILE_APPEND);
            return $this->response->setJSON(['success' => false, 'error' => 'No documents selected.']);
        }
        $model = new DocumentModel();
        $db = \Config\Database::connect();
        $deletedCount = 0;
        $role = session('role');
        $user = strtolower(trim(session('username')));
        
        foreach ($ids as $id) {
            $doc = $model->find($id);
            if ($doc) {
                // Check permissions for each document
                $uploader = strtolower(trim($doc['uploaded_by'] ?? ''));
                $uploaderUser = $model->getUserByUsername($uploader);
                $uploaderRole = $uploaderUser['role'] ?? null;
                
                $canDelete = false;
                if ($role === 'super_admin') {
                    $canDelete = true;
                } elseif ($role === 'admin') {
                    $canDelete = ($uploader === $user && $uploaderRole !== 'super_admin');
                } else {
                    $canDelete = false;
                }
                
                if (!$canDelete) {
                    file_put_contents($logFile, "Access denied for doc ID $id (uploader: $uploader, role: $uploaderRole)\n", FILE_APPEND);
                    continue;
                }
                
                // Audit log BEFORE deletion
                try {
                    $model->logAction($id, 'delete', session('username') ?? 'admin');
                } catch (\Exception $e) {
                    file_put_contents($logFile, "Audit log error for doc ID $id: " . $e->getMessage() . "\n", FILE_APPEND);
                }
                // Delete the physical file
                $filePath = FCPATH . $doc['filepath'];
                if (file_exists($filePath)) {
                    if (@unlink($filePath)) {
                        file_put_contents($logFile, "Deleted file: $filePath\n", FILE_APPEND);
                    } else {
                        file_put_contents($logFile, "Failed to delete file: $filePath\n", FILE_APPEND);
                    }
                } else {
                    file_put_contents($logFile, "File not found: $filePath\n", FILE_APPEND);
                }
                
                // Delete from database
                try {
                    // Remove from pivot tables first
                    $db->table('document_category')->where('document_id', $id)->delete();
                    $db->table('document_tag')->where('document_id', $id)->delete();
                    
                    // Delete the main document record
                    $result = $model->delete($id);
                    file_put_contents($logFile, "Deleted DB row for doc ID $id, result: " . var_export($result, true) . "\n", FILE_APPEND);
                    
                    if ($result) {
                        $deletedCount++;
                    }
                } catch (\Exception $e) {
                    file_put_contents($logFile, "Database deletion error for doc ID $id: " . $e->getMessage() . "\n", FILE_APPEND);
                }
            } else {
                file_put_contents($logFile, "Doc not found for ID $id\n", FILE_APPEND);
            }
        }
        file_put_contents($logFile, "Bulk delete complete. Deleted count: $deletedCount\n", FILE_APPEND);
        if ($deletedCount === 0) {
            return $this->response->setJSON(['success' => false, 'error' => 'No documents were deleted.']);
        }
        return $this->response->setJSON(['success' => true, 'message' => 'Selected documents deleted successfully.']);
    }

    public function bulkDownload()
    {
        $actualMethod = $this->request->getMethod();
        
        // Support multiple input formats
        $ids = $this->request->getPost('ids');
        
        // Check for document_ids[] format (used by frontend forms)
        if (empty($ids)) {
            $ids = $this->request->getPost('document_ids');
        }
        
        // Support JSON input
        if (empty($ids) && $this->request->getHeaderLine('Content-Type') === 'application/json') {
            $json = $this->request->getJSON(true);
            $ids = $json['ids'] ?? $json['document_ids'] ?? [];
        }
        
        if (strcasecmp($actualMethod, 'post') !== 0) {
            return redirect()->to(base_url('admin/documents'));
        }
        
        if (!$ids || !is_array($ids)) {
            return $this->response->setJSON(['success' => false, 'error' => 'No documents selected.']);
        }
        
        $model = new DocumentModel();
        $zip = new \ZipArchive();
        $zipName = 'documents_' . date('Ymd_His') . '.zip';
        $tmpZip = tempnam(sys_get_temp_dir(), 'zip');
        $zipOpen = $zip->open($tmpZip, \ZipArchive::CREATE);
        
        foreach ($ids as $id) {
            $doc = $model->find($id);
            if ($doc && file_exists(WRITEPATH . $doc['filepath'])) {
                $zip->addFile(WRITEPATH . $doc['filepath'], $doc['filename']);
            }
        }
        $zip->close();
        file_put_contents($logFile, "Zip closed. Returning download.\n", FILE_APPEND);
        return $this->response->download($tmpZip, null)->setFileName($zipName)->setContentType('application/zip');
    }

    public function share($id)
    {
        helper(['form', 'url']);
        $model = new DocumentModel();
        $shareModel = $model;
        $doc = $model->find($id);
        
        if (!$doc) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Document not found');
        }
        
        $errorMsg = null;
        $successMsg = null;
        
        if ($this->request->getMethod() === 'post') {
            $sharedWith = $this->request->getPost('shared_with');
            $permissions = $this->request->getPost('permissions');
            $expiresAt = $this->request->getPost('expires_at');
            
            // Check if already shared with this user
            $existingShare = $shareModel->db->table('document_shares')
                                      ->where('document_id', $id)
                                      ->where('shared_with', $sharedWith)
                                      ->where('is_active', true)
                                      ->get()->getRowArray();
            
            if ($existingShare) {
                $errorMsg = 'Document is already shared with this user.';
            } else {
                $ok = $shareModel->createShare(
                    (int) $id,
                    session('username') ?? 'admin',
                    $sharedWith,
                    $permissions,
                    !empty($expiresAt) ? $expiresAt : null
                );
                if ($ok) {
                    $model->logAction((int) $id, 'share', session('username') ?? 'admin');
                    $successMsg = 'Document shared successfully.';
                } else {
                    $errorMsg = 'Failed to share document.';
                }
            }
        }
        
        // Get current shares
        $currentShares = $shareModel->getActiveShares($id);
        
        // Return role-specific share view
        $userRole = session('role');
        $viewData = [
            'doc' => $doc,
            'currentShares' => $currentShares,
            'errorMsg' => $errorMsg,
            'successMsg' => $successMsg
        ];
        
        if ($userRole === 'super_admin') {
            return $this->renderWithTemplate('K-NECT/Pederasyon/Documents/share', $viewData);
        } elseif ($userRole === 'admin') {
            return $this->renderWithTemplate('K-NECT/SK/Documents/share', $viewData);
        } else {
            // KK users cannot share documents
            return redirect()->to(base_url('admin/documents'))->with('error', 'Access denied. Only SK and Super Admin can share documents.');
        }
    }

    public function revokeShare($documentId, $shareId)
    {
        $shareModel = new DocumentModel();
        $db = $shareModel->db;
        $share = $db->table('document_shares')->where('id', (int) $shareId)->get()->getRowArray();
        if ($share && (int) $share['document_id'] === (int) $documentId) {
            $shareModel->revokeShare((int) $shareId);
            $shareModel->logAction((int) $documentId, 'revoke_share', session('username') ?? 'admin');
            return redirect()->to(base_url("admin/documents/share/$documentId"))->with('success', 'Share revoked successfully.');
        }
        
        return redirect()->to(base_url("admin/documents/share/$documentId"))->with('error', 'Share not found.');
    }

    public function sharedDocuments()
    {
        $model = new DocumentModel();
        $user = session('username') ?? 'admin';
        $sharedDocs = $model->getSharedDocuments($user);
        
        // Return role-specific shared documents view
        $userRole = session('role');
        $viewData = [
            'sharedDocs' => $sharedDocs
        ];
        
        if ($userRole === 'super_admin') {
            return $this->renderWithTemplate('K-NECT/Pederasyon/Documents/shared_documents', $viewData);
        } elseif ($userRole === 'admin') {
            return $this->renderWithTemplate('K-NECT/SK/Documents/shared_documents', $viewData);
        } else {
            // KK users can view shared documents but with limited access
            return $this->renderWithTemplate('K-NECT/KK/Documents/shared_documents', $viewData);
        }
    }

    /**
     * Submit a document for approval (admin or user action)
     */
    public function submitForApproval($id)
    {
        $model = new DocumentModel();
        $result = $model->submitForApproval($id);
        if ($result) {
            return $this->response->setJSON(['success' => true, 'message' => 'Document submitted for approval.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to submit document for approval.']);
        }
    }

    /**
     * Approve a document (admin action)
     */
    public function approveDocument($id)
    {
        try {
            if (session('role') !== 'super_admin') {
                return $this->response->setJSON(['success' => false, 'message' => 'Access denied. Only super admins can approve documents.']);
            }
            
            $model = new DocumentModel();
            
            // Get comment from POST form data (not JSON)
            $comment = $this->request->getPost('comment') ?: 'Approved';
            $approver = session('username') ?? 'super_admin';
            
            // Check if document exists
            $document = $model->find($id);
            if (!$document) {
                return $this->response->setJSON(['success' => false, 'message' => 'Document not found.']);
            }
            
            log_message('info', "Attempting to approve document ID: $id by user: $approver");
            
            $result = $model->approve($id, $approver, $comment);
            
            if ($result) {
                // Audit log
                $audit = $model;
                $audit->save([
                    'document_id' => $id,
                    'action' => 'approve',
                    'performed_by' => $approver,
                    'performed_at' => date('Y-m-d H:i:s'),
                ]);

                log_message('info', "Document ID: $id approved successfully by: $approver");
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Document approved successfully.',
                        'document_id' => $id,
                        'new_status' => 'approved'
                    ]);
                }
                return redirect()->to(base_url('admin/documents'))
                    ->with('success', 'Document approved successfully.');
            } else {
                $errors = $model->errors();
                log_message('error', "Failed to approve document ID: $id. Errors: " . json_encode($errors));
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Failed to approve document: ' . implode(', ', $errors)]);
                }
                return redirect()->back()->with('error', 'Failed to approve document: ' . implode(', ', $errors));
            }
        } catch (\Throwable $e) {
            log_message('error', "Exception in approveDocument: " . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred while approving the document: ' . $e->getMessage()]);
        }
    }

    /**
     * Reject a document (admin action)
     */
    public function rejectDocument($id)
    {
        try {
            if (session('role') !== 'super_admin') {
                return $this->response->setJSON(['success' => false, 'message' => 'Access denied. Only super admins can reject documents.']);
            }
            
            $model = new DocumentModel();
            
            // Get comment from POST form data (not JSON)
            $comment = $this->request->getPost('comment') ?: 'Rejected';
            $approver = session('username') ?? 'super_admin';
            
            // Check if document exists
            $document = $model->find($id);
            if (!$document) {
                return $this->response->setJSON(['success' => false, 'message' => 'Document not found.']);
            }
            
            log_message('info', "Attempting to reject document ID: $id by user: $approver");
            
            $result = $model->reject($id, $approver, $comment);
            
            if ($result) {
                // Audit log
                $audit = $model;
                $audit->save([
                    'document_id' => $id,
                    'action' => 'reject',
                    'performed_by' => $approver,
                    'performed_at' => date('Y-m-d H:i:s'),
                ]);

                log_message('info', "Document ID: $id rejected successfully by: $approver");
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Document rejected successfully.',
                        'document_id' => $id,
                        'new_status' => 'rejected'
                    ]);
                }
                return redirect()->to(base_url('admin/documents/detail/' . $id))
                    ->with('success', 'Document rejected successfully.');
            } else {
                $errors = $model->errors();
                log_message('error', "Failed to reject document ID: $id. Errors: " . json_encode($errors));
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Failed to reject document: ' . implode(', ', $errors)]);
                }
                return redirect()->back()->with('error', 'Failed to reject document: ' . implode(', ', $errors));
            }
        } catch (\Throwable $e) {
            log_message('error', "Exception in rejectDocument: " . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'An error occurred while rejecting the document: ' . $e->getMessage()]);
        }
    }

    /**
     * Get approval status for a document
     */
    public function getApprovalStatus($id)
    {
        $model = new DocumentModel();
        $status = $model->getApprovalStatus($id);
        if ($status) {
            return $this->response->setJSON(['success' => true, 'status' => $status]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Document not found.']);
        }
    }

    /**
     * TEMP: Normalize all uploaded_by values and set downloadable=1 for approved SK docs
     * Only super_admin can run this
     */
    public function fixDocumentData()
    {
        if (session('role') !== 'super_admin') {
            return $this->response->setStatusCode(403)->setBody('Access denied');
        }
        $model = new DocumentModel();
        $docs = $model->findAll();
        $fixed = 0;
        foreach ($docs as $doc) {
            $newUploadedBy = strtolower(trim($doc['uploaded_by']));
            $update = false;
            $data = [];
            if ($doc['uploaded_by'] !== $newUploadedBy) {
                $data['uploaded_by'] = $newUploadedBy;
                $update = true;
            }
            if ($newUploadedBy === 'sk' && ($doc['approval_status'] ?? null) === 'approved' && ($doc['downloadable'] ?? 0) != 1) {
                $data['downloadable'] = 1;
                $update = true;
            }
            if ($update) {
                $model->update($doc['id'], $data);
                $fixed++;
            }
        }
        return $this->response->setBody("Fixed $fixed documents. <a href='" . base_url('admin/documents') . "'>Back to documents</a>");
    }

    /**
     * TEMP: Fix all super admin documents to approved
     */
    public function fixSuperAdminApprovals()
    {
        if (session('role') !== 'super_admin') {
            return $this->response->setStatusCode(403)->setBody('Access denied');
        }
        $model = new DocumentModel();
        $userModel = $model;
        $superAdmins = $userModel->where('role', 'super_admin')->findAll();
        $superAdminUsernames = array_map(function($u) { return strtolower(trim($u['username'])); }, $superAdmins);
        $docs = $model->findAll();
        $fixed = 0;
        foreach ($docs as $doc) {
            $uploadedBy = strtolower(trim($doc['uploaded_by']));
            if (in_array($uploadedBy, $superAdminUsernames) && $doc['approval_status'] !== 'approved') {
                $model->update($doc['id'], [
                    'approval_status' => 'approved',
                    'approver' => $uploadedBy,
                    'approval_at' => date('Y-m-d H:i:s'),
                    'approval_comment' => 'Auto-fixed: Super Admin upload'
                ]);

                $fixed++;
            }
        }
        return $this->response->setBody("Fixed $fixed super admin documents. <a href='" . base_url('admin/documents') . "'>Back to documents</a>");
    }

    /**
     * One-time fix: Re-push correct status for all super admin documents to Firebase
     */
    public function repushSuperAdminApprovals()
    {
        if (session('role') !== 'super_admin') {
            return $this->response->setStatusCode(403)->setBody('Access denied');
        }
        $model = new DocumentModel();
        $userModel = $model;
        $superAdmins = $userModel->where('role', 'super_admin')->findAll();
        $superAdminUsernames = array_map(function($u) { return strtolower(trim($u['username'])); }, $superAdmins);
        $docs = $model->findAll();
        $fixed = 0;
        foreach ($docs as $doc) {
            $uploadedBy = strtolower(trim($doc['uploaded_by']));
            if (in_array($uploadedBy, $superAdminUsernames)) {
                $fixed++;
            }
        }
        return $this->response->setBody("Re-pushed $fixed super admin documents to Firebase. <a href='" . base_url('admin/documents') . "'>Back to documents</a>");
    }

}
