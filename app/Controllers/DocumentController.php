<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\SystemLogoModel;

class DocumentController extends BaseController
{
    /**
     * Preview document files (certificates, IDs, etc.)
     * 
     * @param string $type The type of document (certificate, id, profile_pictures)
     * @param string $filename The filename to preview
     * @return ResponseInterface
     */
    public function preview($type = null, $filename = null)
    {
        // Validate parameters
        if (empty($type) || empty($filename)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Define allowed document types for security
        $allowedTypes = ['certificate', 'id', 'profile_pictures'];
        
        if (!in_array($type, $allowedTypes)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Sanitize filename to prevent directory traversal
        $filename = basename($filename);
        
        // Build file path
        $filePath = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . $type . DIRECTORY_SEPARATOR . $filename;
        
        // Check if file exists
        if (!file_exists($filePath) || !is_file($filePath)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Get file info
        $fileInfo = pathinfo($filePath);
        $fileExtension = strtolower($fileInfo['extension']);
        
        // Define allowed file extensions
        $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'gif'];
        
        if (!in_array($fileExtension, $allowedExtensions)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Set appropriate content type
        $mimeTypes = [
            'pdf'  => 'application/pdf',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
            'gif'  => 'image/gif'
        ];

        $contentType = $mimeTypes[$fileExtension] ?? 'application/octet-stream';

        // Read file content
        $fileContent = file_get_contents($filePath);
        
        if ($fileContent === false) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Return file response
        return $this->response
            ->setContentType($contentType)
            ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
            ->setHeader('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->setHeader('Pragma', 'no-cache')
            ->setHeader('Expires', '0')
            ->setBody($fileContent);
    }

    /**
     * Generate KK List Report in SK Format
     * 
     * @return ResponseInterface
     */
    public function getLogos()
    {
        $session = session();
        $sessionUserId = $session->get('user_id');
        $userType = $session->get('user_type');
        
        // Get user's barangay_id for filtering logos (only for non-Pederasyon users)
        $barangayId = null;
        if ($userType !== 'pederasyon') {
            $userModel = new \App\Models\UserModel();
            $user = $userModel->where('user_id', $sessionUserId)->first();
            $actualUserId = $user ? $user['id'] : null;
            
            $addressModel = new \App\Models\AddressModel();
            $userAddress = $addressModel->where('user_id', $actualUserId)->first();
            $barangayId = $userAddress ? $userAddress['barangay'] : null;
        }
        
        $logoModel = new SystemLogoModel();
        
        // Get logos based on user type
        if ($userType === 'pederasyon') {
            // Pederasyon sees global Iriga logos and Pederasyon logos
            $logos = $logoModel->where('is_active', true)
                              ->whereIn('logo_type', ['iriga_city', 'pederasyon'])
                              ->where('barangay_id IS NULL')
                              ->orderBy('created_at', 'DESC')
                              ->findAll();
        } else {
            // SK users see barangay-specific logos + global Iriga logos
            $logos = $logoModel->where('is_active', true)
                              ->groupStart()
                                  ->groupStart()
                                      ->where('logo_type', 'iriga_city')
                                      ->where('barangay_id IS NULL') // Global Iriga logos
                                  ->groupEnd()
                                  ->orGroupStart()
                                      ->whereIn('logo_type', ['barangay', 'sk'])
                                      ->where('barangay_id', $barangayId) // Barangay-specific logos
                                  ->groupEnd()
                              ->groupEnd()
                              ->orderBy('logo_type', 'ASC')
                              ->orderBy('created_at', 'DESC')
                              ->findAll();
        }
        
        // Organize logos by type
        $organizedLogos = [
            'iriga_city' => null,
            'pederasyon' => null,
            'municipality' => null,
            'barangay' => null,
            'sk' => null
        ];
        
        foreach ($logos as $logo) {
            if (!isset($organizedLogos[$logo['logo_type']]) || $organizedLogos[$logo['logo_type']] === null) {
                $organizedLogos[$logo['logo_type']] = [
                    'id' => $logo['id'],
                    'name' => $logo['logo_name'],
                    'file_path' => $logo['file_path'],
                    'dimensions' => $logo['dimensions'],
                    'file_size' => $logo['file_size'],
                    'created_at' => $logo['created_at'],
                    'updated_at' => $logo['updated_at']
                ];
            }
        }
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $organizedLogos,
            'raw_data' => $logos // Include raw array for functions that need it
        ]);
    }

    /**
     * Generate KK List Report in SK Format
     * 
     * @return ResponseInterface
     */
    public function generateKKList()
    {
        $session = session();
        $skBarangay = $session->get('sk_barangay');
        
        if (!$skBarangay) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'SK Barangay assignment not found'
            ]);
        }

        // Get KK members data
        $userModel = new \App\Models\UserModel();
        $addressModel = new \App\Models\AddressModel();
        $userExtInfoModel = new \App\Models\UserExtInfoModel();

        $query = $userModel
            ->select('user.id, user.user_id, user.status, user.position, address.barangay, address.zone_purok, user.last_name, user.first_name, user.middle_name, user.birthdate, user.sex, user.email, user.phone_number')
            ->join('address', 'address.user_id = user.id', 'left')
            ->join('user_ext_info', 'user_ext_info.user_id = user.id', 'left')
            ->where('address.barangay', $skBarangay)
            ->where('user.status', 2); // Only active members

        $kkMembers = $query->findAll();

        // Process the data
        $processedMembers = [];
        foreach ($kkMembers as $member) {
            // Calculate age
            $age = null;
            if (!empty($member['birthdate'])) {
                $birthdate = new \DateTime($member['birthdate']);
                $today = new \DateTime();
                $age = $today->diff($birthdate)->y;
            }

            $processedMembers[] = [
                'name' => trim($member['first_name'] . ' ' . $member['middle_name'] . ' ' . $member['last_name']),
                'age' => $age,
                'sex' => $member['sex'] == 1 ? 'Male' : ($member['sex'] == 2 ? 'Female' : ''),
                'zone' => $member['zone_purok'],
                'email' => $member['email'],
                'phone' => $member['phone_number'],
                'user_id' => $member['user_id']
            ];
        }

        // Get barangay information
        $barangayHelper = new \App\Libraries\BarangayHelper();
        $barangayName = $barangayHelper::getBarangayName($skBarangay);

        $reportData = [
            'barangay_name' => $barangayName,
            'generation_date' => date('F d, Y'),
            'total_members' => count($processedMembers),
            'members' => $processedMembers
        ];

        // In a real implementation, you would generate a PDF here
        // For now, return the data structure that would be used for the report
        return $this->response->setJSON([
            'success' => true,
            'message' => 'KK List Report generated successfully',
            'data' => $reportData,
            'format' => 'sk_official',
            'download_url' => base_url('uploads/generated/kk_list_' . $skBarangay . '_' . time() . '.pdf')
        ]);
    }

    /**
     * Upload logo files
     * 
     * @return ResponseInterface
     */
    public function uploadLogo()
    {
        $session = session();
        $input = $this->request->getPost();
        $logoType = $input['logo_type'] ?? '';
        $userType = $session->get('user_type'); // Get user type (kk, sk, pederasyon)
        $sessionUserId = $session->get('user_id');
        if (!$sessionUserId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Session expired or user not authenticated'
            ]);
        }
        
        // Validate user permissions based on user type
        if ($userType === 'pederasyon') {
            // Pederasyon can upload Iriga City logo and Pederasyon logo
            if (!in_array($logoType, ['iriga', 'pederasyon'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Pederasyon users can only upload Iriga City logo and Pederasyon logo'
                ]);
            }
        } elseif ($userType === 'sk') {
            // SK users can only upload Barangay and SK logos
            if (!in_array($logoType, ['barangay', 'sk'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'SK users can only upload Barangay and SK logos'
                ]);
            }
        } else {
            // KK users cannot upload any logos
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You do not have permission to upload logos'
            ]);
        }
        
        // Get the actual user.id from the user table using the session user_id
    $userModel = new \App\Models\UserModel();
    $user = $userModel->where('user_id', $sessionUserId)->first();
        $actualUserId = $user ? $user['id'] : null;
        
        // Get user's barangay_id from address table (only for non-Pederasyon users)
        $barangayId = null;
        if ($userType !== 'pederasyon') {
            $addressModel = new \App\Models\AddressModel();
            $userAddress = $addressModel->where('user_id', $actualUserId)->first();
            $barangayId = $userAddress ? $userAddress['barangay'] : null;
        }
        // For Pederasyon users uploading Iriga logo, barangayId stays null (global)
        
        
        // Map frontend logo types to database enum values
        $logoTypeMapping = [
            'iriga' => 'iriga_city',
            'barangay' => 'barangay',
            'sk' => 'sk',
            'pederasyon' => 'pederasyon',
            'municipality' => 'municipality'
        ];
        
        if (!isset($logoTypeMapping[$logoType])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid logo type'
            ]);
        }
        
        $dbLogoType = $logoTypeMapping[$logoType];
        $file = $this->request->getFile('logo_file');
        
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No file uploaded or file is invalid'
            ]);
        }

        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        
        // Get mime type safely with fallback
        $mimeType = '';
        try {
            $mimeType = $file->getMimeType();
        } catch (\Throwable $e) {
            // Fallback to extension-based validation if mime type detection fails
            $extension = strtolower($file->getExtension());
            if (in_array($extension, $allowedExtensions)) {
                $mimeType = ($extension === 'png') ? 'image/png' : 'image/jpeg';
            }
        }
        
        if (!in_array($mimeType, $allowedTypes) && !in_array(strtolower($file->getExtension()), $allowedExtensions)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Only JPEG and PNG files are allowed'
            ]);
        }

        // Validate file size (max 2MB)
        if ($file->getSize() > 2048000) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'File size must be less than 2MB'
            ]);
        }

        // Create uploads directory if it doesn't exist
        $uploadPath = FCPATH . 'uploads/logos/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Generate unique filename
        $fileName = $dbLogoType . '_logo_' . time() . '.' . $file->getExtension();
        
        // Check if we're updating an existing logo before moving the file
        $logoModel = new SystemLogoModel();
        $existingLogo = $logoModel->findExistingLogo($dbLogoType, $barangayId);
        $oldFilePath = null;
        
        if ($existingLogo && $existingLogo['file_path']) {
            $oldFilePath = FCPATH . $existingLogo['file_path'];
        }
        
        // Move file to uploads directory
        $moved = false;
        try {
            $moved = $file->move($uploadPath, $fileName);
        } catch (\Throwable $e) {
            log_message('error', 'Failed to move uploaded logo file: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to save uploaded file. Please check server permissions.'
            ]);
        }

        if ($moved) {
            // Get image dimensions
            $dimensions = '';
            $fullPath = $uploadPath . $fileName;
            if (function_exists('getimagesize')) {
                $imageInfo = getimagesize($fullPath);
                if ($imageInfo) {
                    $dimensions = $imageInfo[0] . 'x' . $imageInfo[1];
                }
            }
            
            // Prepare logo data
            $logoData = [
                'logo_type' => $dbLogoType,
                'logo_name' => $file->getClientName(),
                'file_path' => 'uploads/logos/' . $fileName,
                'file_size' => $file->getSize(),
                'mime_type' => $mimeType, // Use the safely obtained mime type
                'dimensions' => $dimensions,
                'is_active' => true,
                'uploaded_by' => $actualUserId,
                'barangay_id' => $barangayId // Add barangay_id to prevent duplicates per barangay
            ];
            
            try {
                $result = $logoModel->updateOrCreate($logoData);
                
                if ($result) {
                    // Only delete old file after successful database update
                    if ($oldFilePath && file_exists($oldFilePath)) {
                        if (unlink($oldFilePath)) {
                            log_message('info', "Successfully deleted old logo file: {$oldFilePath}");
                        } else {
                            log_message('error', "Failed to delete old logo file: {$oldFilePath}");
                        }
                    }
                    
                    $message = $existingLogo ? 'Logo updated successfully' : 'Logo uploaded successfully';
                    return $this->response->setJSON([
                        'success' => true,
                        'message' => ucfirst($logoType) . ' ' . $message,
                        'data' => [
                            'file_path' => 'uploads/logos/' . $fileName,
                            'dimensions' => $dimensions,
                            'file_size' => $file->getSize(),
                            'action' => $existingLogo ? 'updated' : 'created'
                        ]
                    ]);
                } else {
                    // Delete uploaded file if database operation failed
                    unlink($fullPath);
                    $errors = $logoModel->errors();
                    log_message('error', 'Logo model validation errors: ' . json_encode($errors));
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Failed to save logo information to database',
                        'errors' => $errors
                    ]);
                }
            } catch (\Exception $e) {
                // Delete uploaded file if exception occurred
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
                log_message('error', 'Logo upload exception: ' . $e->getMessage());
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'An error occurred while saving the logo: ' . $e->getMessage()
                ]);
            }
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Failed to upload logo'
            ]);
        }
    }
}