<?php

namespace App\Models;

use CodeIgniter\Model;

class SystemLogoModel extends Model
{
    protected $table = 'system_logo';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'logo_type',
        'logo_name', 
        'file_path',
        'file_size',
        'mime_type',
        'dimensions',
        'is_active',
        'uploaded_by',
        'barangay_id'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'logo_type' => 'required|in_list[iriga_city,municipality,barangay,sk,pederasyon]',
        'logo_name' => 'required|max_length[255]',
        'file_path' => 'required|max_length[500]',
        'mime_type' => 'permit_empty|max_length[100]',
        'dimensions' => 'permit_empty|max_length[20]',
        'uploaded_by' => 'permit_empty|integer',
        'barangay_id' => 'permit_empty|integer'
    ];

    protected $validationMessages = [
        'logo_type' => [
            'required' => 'Logo type is required',
            'in_list' => 'Invalid logo type'
        ],
        'logo_name' => [
            'required' => 'Logo name is required',
            'max_length' => 'Logo name cannot exceed 255 characters'
        ],
        'file_path' => [
            'required' => 'File path is required',
            'max_length' => 'File path cannot exceed 500 characters'
        ]
    ];

    // Skip validation
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Get active logo by type
     *
     * @param string $logoType
     * @return array|null
     */
    public function getActiveLogoByType(string $logoType): ?array
    {
        return $this->where('logo_type', $logoType)
                    ->where('is_active', true)
                    ->orderBy('created_at', 'DESC')
                    ->first();
    }

    /**
     * Get all active logos
     *
     * @return array
     */
    public function getAllActiveLogos(): array
    {
        return $this->where('is_active', true)
                    ->orderBy('logo_type', 'ASC')
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get logos by type with pagination
     *
     * @param string $logoType
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getLogosByType(string $logoType, int $limit = 10, int $offset = 0): array
    {
        return $this->where('logo_type', $logoType)
                    ->orderBy('created_at', 'DESC')
                    ->findAll($limit, $offset);
    }

    /**
     * Deactivate old logos when uploading new one
     *
     * @param string $logoType
     * @param int $excludeId
     * @return bool
     */
    public function deactivateOldLogos(string $logoType, int $excludeId = 0): bool
    {
        $builder = $this->builder();
        $builder->where('logo_type', $logoType);
        $builder->where('is_active', true);
        
        if ($excludeId > 0) {
            $builder->where('id !=', $excludeId);
        }
        
        return $builder->update(['is_active' => false]);
    }

    /**
     * Get logo statistics
     *
     * @return array
     */
    public function getLogoStats(): array
    {
        $builder = $this->builder();
        $result = $builder->select('logo_type, COUNT(*) as count, SUM(file_size) as total_size')
                         ->where('is_active', true)
                         ->groupBy('logo_type')
                         ->get()
                         ->getResultArray();
        
        $stats = [];
        foreach ($result as $row) {
            $stats[$row['logo_type']] = [
                'count' => $row['count'],
                'total_size' => $row['total_size']
            ];
        }
        
        return $stats;
    }

    /**
     * Find existing logo by type and barangay
     *
     * @param string $logoType
     * @param int|null $barangayId
     * @return array|null
     */
    public function findExistingLogo(string $logoType, ?int $barangayId = null): ?array
    {
        $builder = $this->where('logo_type', $logoType);
        
        if ($barangayId !== null) {
            $builder->where('barangay_id', $barangayId);
        } else {
            $builder->where('barangay_id IS NULL');
        }
        
        return $builder->first();
    }

    /**
     * Update existing logo or create new one
     *
     * @param array $logoData
     * @return bool
     */
    public function updateOrCreate(array $logoData): bool
    {
        $existing = $this->findExistingLogo(
            $logoData['logo_type'], 
            $logoData['barangay_id'] ?? null
        );
        
        if ($existing) {
            // Update existing logo
            return $this->update($existing['id'], $logoData);
        } else {
            // Create new logo
            return $this->insert($logoData) !== false;
        }
    }

    /**
     * Clean up orphaned logo files from filesystem
     * Files that exist in uploads/logos but not in database
     *
     * @return array Cleanup results
     */
    public function cleanupOrphanedFiles(): array
    {
        $uploadPath = FCPATH . 'uploads/logos/';
        $results = [
            'scanned' => 0,
            'deleted' => 0,
            'errors' => 0,
            'files' => []
        ];
        
        if (!is_dir($uploadPath)) {
            return $results;
        }
        
        // Get all logo files in database
        $dbFiles = $this->select('file_path')->findAll();
        $dbFilePaths = array_column($dbFiles, 'file_path');
        
        // Scan filesystem for logo files
        $files = glob($uploadPath . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
        
        foreach ($files as $filePath) {
            $results['scanned']++;
            $relativePath = 'uploads/logos/' . basename($filePath);
            
            // Check if file exists in database
            if (!in_array($relativePath, $dbFilePaths)) {
                $results['files'][] = $relativePath;
                
                // Delete orphaned file
                if (unlink($filePath)) {
                    $results['deleted']++;
                    log_message('info', "Deleted orphaned logo file: {$relativePath}");
                } else {
                    $results['errors']++;
                    log_message('error', "Failed to delete orphaned logo file: {$relativePath}");
                }
            }
        }
        
        return $results;
    }
}
