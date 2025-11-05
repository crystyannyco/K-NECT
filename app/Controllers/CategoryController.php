<?php
namespace App\Controllers;

use App\Models\DocumentModel;

use App\Controllers\BaseController;

class CategoryController extends BaseController
{
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
            $headerTemplate = 'K-NECT/SK/template/header';
            $sidebarTemplate = 'K-NECT/SK/template/sidebar';
            $footerTemplate = 'K-NECT/SK/template/footer';
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
        $html .= '<div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">';
        $html .= '<div class="container mx-auto px-6 py-8">';
        $html .= view($viewName, $data);
        $html .= '</div>';
        $html .= '</div>';
        $html .= view($footerTemplate, $data);
        
        return $html;
    }

    public function index()
    {
        $model = new DocumentModel();
        $userRole = session('role');
        $userType = session('user_type');
        
        // Get user's barangay ID for SK users
        $userBarangayId = null;
        if ($userRole === 'admin' || $userType === 'sk') {
            $userBarangayId = session('barangay_id');
        }
        
        // Get categories based on user type
        if ($userRole === 'super_admin' || $userType === 'pederasyon') {
            // Pederasyon sees only city-wide categories
            $data['categories'] = $model->getCategoriesWithDocumentCount(null, false);
            $data['barangayId'] = null;
            return $this->renderWithTemplate('K-NECT/Pederasyon/Categories/list', $data);
        } elseif ($userRole === 'admin' || $userType === 'sk') {
            // SK sees their barangay categories + city-wide categories
            $data['categories'] = $model->getCategoriesWithDocumentCount($userBarangayId, true);
            $data['barangayId'] = $userBarangayId;
            return $this->renderWithTemplate('K-NECT/SK/Categories/list', $data);
        }
        
        return redirect()->to(base_url('admin/documents'))->with('error', 'Access denied.');
    }

    public function add()
    {
        helper(['form']);
        $model = new DocumentModel();
        $userRole = session('role');
        $userType = session('user_type');
        
        // Get user's barangay ID
        $userBarangayId = null;
        if ($userRole === 'admin' || $userType === 'sk') {
            $userBarangayId = session('barangay_id');
        }

        if ($this->request->isAJAX()) {
            $name = $this->request->getPost('name');
            if (!$name || trim($name) === '') {
                return $this->response->setJSON(['success' => false, 'message' => 'Category name is required.']);
            }
            
            // Check for duplicate in same scope (barangay or city-wide)
            $existing = $model->db->table('categories')
                ->where('name', trim($name));
            
            if ($userBarangayId) {
                $existing->where('barangay_id', $userBarangayId);
            } else {
                $existing->where('barangay_id', null);
            }
            
            if ($existing->get()->getRowArray()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Category already exists in this scope.']);
            }
            
            $ok = $model->createCategory(trim($name), $userBarangayId);
            return $this->response->setJSON([
                'success' => (bool) $ok,
                'message' => $ok ? 'Category added successfully.' : 'Failed to add category.'
            ]);
        }

        $errorMsg = null;
        $oldName = '';
        if (strtolower($this->request->getMethod()) === 'post') {
            $rules = [
                'name' => [
                    'label' => 'Category Name',
                    'rules' => 'required|max_length[100]'
                ]
            ];
            $oldName = $this->request->getPost('name');
            if (!$this->validate($rules)) {
                $errorMsg = implode(' ', $this->validator->getErrors());
            } else {
                // Check for duplicate
                $existing = $model->db->table('categories')
                    ->where('name', trim($oldName));
                
                if ($userBarangayId) {
                    $existing->where('barangay_id', $userBarangayId);
                } else {
                    $existing->where('barangay_id', null);
                }
                
                if ($existing->get()->getRowArray()) {
                    $errorMsg = 'Category already exists in this scope.';
                } else {
                    $ok = $model->createCategory($oldName, $userBarangayId);
                    if ($ok) {
                        return redirect()->to(base_url('admin/categories'))->with('success', 'Category added.');
                    }
                    $errorMsg = 'Save failed.';
                }
            }
        }

        $viewData = ['errorMsg' => $errorMsg, 'oldName' => $oldName, 'barangayId' => $userBarangayId];
        
        if ($userRole === 'super_admin' || $userType === 'pederasyon') {
            return $this->renderWithTemplate('K-NECT/Pederasyon/Categories/add', $viewData);
        } elseif ($userRole === 'admin' || $userType === 'sk') {
            return $this->renderWithTemplate('K-NECT/SK/Categories/add', $viewData);
        }
        
        return redirect()->to(base_url('admin/documents'))->with('error', 'Access denied.');
    }

    public function edit($id)
    {
        helper(['form']);
        $model = new DocumentModel();
        $userRole = session('role');
        $userType = session('user_type');
        
        // Get user's barangay ID
        $userBarangayId = null;
        if ($userRole === 'admin' || $userType === 'sk') {
            $userBarangayId = session('barangay_id');
        }
        
        $category = $model->db->table('categories')->where('id', $id)->get()->getRowArray();

        if (!$category) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Category not found.']);
            }
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Category not found');
        }
        
        // Check if user can manage this category
        if (!$model->canManageCategory($id, $userBarangayId, $userType)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'You do not have permission to edit this category.']);
            }
            return redirect()->to(base_url('admin/categories'))->with('error', 'You do not have permission to edit this category.');
        }

        if ($this->request->isAJAX()) {
            $name = $this->request->getPost('name');
            if (!$name || trim($name) === '') {
                return $this->response->setJSON(['success' => false, 'message' => 'Category name is required.']);
            }
            
            // Check for duplicate in same scope
            $existing = $model->db->table('categories')
                ->where('name', trim($name))
                ->where('id !=', $id);
                
            if ($category['barangay_id']) {
                $existing->where('barangay_id', $category['barangay_id']);
            } else {
                $existing->where('barangay_id', null);
            }
            
            if ($existing->get()->getRowArray()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Category name already exists in this scope.']);
            }
            
            $ok = $model->updateCategory($id, trim($name));
            return $this->response->setJSON([
                'success' => (bool) $ok,
                'message' => $ok ? 'Category updated successfully.' : 'Failed to update category.'
            ]);
        }

        $errorMsg = null;
        if (strtolower($this->request->getMethod()) === 'post') {
            $name = $this->request->getPost('name');
            if (!$name) {
                $errorMsg = 'Category name is required.';
            } else {
                // Check for duplicate
                $existing = $model->db->table('categories')
                    ->where('name', trim($name))
                    ->where('id !=', $id);
                    
                if ($category['barangay_id']) {
                    $existing->where('barangay_id', $category['barangay_id']);
                } else {
                    $existing->where('barangay_id', null);
                }
                
                if ($existing->get()->getRowArray()) {
                    $errorMsg = 'Category name already exists in this scope.';
                } else {
                    $ok = $model->updateCategory($id, $name);
                    if ($ok) {
                        return redirect()->to(base_url('admin/categories'))->with('success', 'Category updated.');
                    }
                    $errorMsg = 'Failed to update category.';
                }
            }
        }

        $viewData = ['category' => $category, 'errorMsg' => $errorMsg, 'barangayId' => $userBarangayId];
        
        if ($userRole === 'super_admin' || $userType === 'pederasyon') {
            return $this->renderWithTemplate('K-NECT/Pederasyon/Categories/edit', $viewData);
        } elseif ($userRole === 'admin' || $userType === 'sk') {
            return $this->renderWithTemplate('K-NECT/SK/Categories/edit', $viewData);
        }
        
        return redirect()->to(base_url('admin/documents'))->with('error', 'Access denied.');
    }

    public function delete($id)
    {
        // Validate ID
        if (!is_numeric($id) || $id <= 0) {
            if ($this->request->getPost('ajax_request') || $this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Invalid category ID.']);
            }
            return redirect()->to(base_url('admin/categories'))->with('error', 'Invalid category ID.');
        }

        $model = new DocumentModel();
        $userRole = session('role');
        $userType = session('user_type');
        
        // Get user's barangay ID
        $userBarangayId = null;
        if ($userRole === 'admin' || $userType === 'sk') {
            $userBarangayId = session('barangay_id');
        }
        
        try {
            // Check if category exists
            $category = $model->db->table('categories')->where('id', $id)->get()->getRowArray();

            if (!$category) {
                if ($this->request->getPost('ajax_request') || $this->request->isAJAX()) {
                    return $this->response->setJSON(['success' => false, 'message' => 'Category not found.']);
                }
                return redirect()->to(base_url('admin/categories'))->with('error', 'Category not found.');
            }
            
            // Check if user can manage this category
            if (!$model->canManageCategory($id, $userBarangayId, $userType)) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON(['success' => false, 'message' => 'You do not have permission to delete this category.']);
                }
                return redirect()->to(base_url('admin/categories'))->with('error', 'You do not have permission to delete this category.');
            }

            // Check if category is in use by documents
            $db = \Config\Database::connect();
            $documentsUsingCategory = $db->table('document_category')
                ->select('documents.title')
                ->join('documents', 'documents.id = document_category.document_id', 'left')
                ->where('document_category.category_id', $id)
                ->get()
                ->getResult();

            if (!empty($documentsUsingCategory)) {
                $documentTitles = array_slice(array_map(function($doc) { 
                    return $doc->title ?? 'Untitled Document'; 
                }, $documentsUsingCategory), 0, 3);
                
                $msg = 'Cannot delete category "' . $category['name'] . '". It is being used by ' . count($documentsUsingCategory) . ' document(s)';
                if (count($documentsUsingCategory) <= 3) {
                    $msg .= ': ' . implode(', ', $documentTitles);
                } else {
                    $msg .= ' including: ' . implode(', ', $documentTitles) . ' and ' . (count($documentsUsingCategory) - 3) . ' more';
                }
                
                if ($this->request->getPost('ajax_request') || $this->request->isAJAX()) {
                    return $this->response->setJSON(['success' => false, 'message' => $msg]);
                }
                return redirect()->to(base_url('admin/documents'))->with('error', $msg);
            }

            // Attempt to delete the category
            $deleted = $model->deleteCategory($id);
            
            if ($deleted) {
                if ($this->request->getPost('ajax_request') || $this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => 'Category "' . $category['name'] . '" has been deleted successfully.'
                    ]);
                }
                return redirect()->to(base_url('admin/documents'))->with('success', 'Category "' . $category['name'] . '" has been deleted successfully.');
            } else {
                if ($this->request->getPost('ajax_request') || $this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Failed to delete category "' . $category['name'] . '". Please try again.'
                    ]);
                }
                return redirect()->to(base_url('admin/documents'))->with('error', 'Failed to delete category.');
            }
            
        } catch (\Exception $e) {
            error_log('Delete category error for ID ' . $id . ': ' . $e->getMessage());
            
            if ($this->request->getPost('ajax_request') || $this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'A database error occurred while deleting the category. Please try again or contact the administrator.'
                ]);
            }
            return redirect()->to(base_url('admin/documents'))->with('error', 'A database error occurred.');
        }
    }
} 
