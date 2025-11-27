<?php

namespace App\Models;

use CodeIgniter\Model;

class SystemSettingsModel extends Model
{
    protected $table = 'system_settings';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'setting_key',
        'setting_value',
        'setting_type',
        'description',
        'updated_by'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    /**
     * Get a setting value by key
     */
    public function getSetting($key, $default = null)
    {
        $setting = $this->where('setting_key', $key)->first();
        return $setting ? $setting['setting_value'] : $default;
    }
    
    /**
     * Set a setting value
     */
    public function setSetting($key, $value, $type = 'string', $description = '', $updatedBy = null)
    {
        $existing = $this->where('setting_key', $key)->first();
        
        $data = [
            'setting_key' => $key,
            'setting_value' => $value,
            'setting_type' => $type,
            'description' => $description,
            'updated_by' => $updatedBy
        ];
        
        if ($existing) {
            return $this->update($existing['id'], $data);
        } else {
            return $this->insert($data);
        }
    }
    
    /**
     * Get multiple settings as key-value pairs
     */
    public function getSettings(array $keys)
    {
        $settings = $this->whereIn('setting_key', $keys)->findAll();
        $result = [];
        foreach ($settings as $setting) {
            $result[$setting['setting_key']] = $setting['setting_value'];
        }
        return $result;
    }
    
    /**
     * Get location defaults for profiling
     */
    public function getLocationDefaults()
    {
        $keys = [
            'default_region_code',
            'default_region_name',
            'default_province_code',
            'default_province_name',
            'default_municipality_code',
            'default_municipality_name'
        ];
        
        return $this->getSettings($keys);
    }
    
    /**
     * Set location defaults
     */
    public function setLocationDefaults($data, $updatedBy = null)
    {
        $keys = [
            'default_region_code' => $data['region_code'] ?? '',
            'default_region_name' => $data['region_name'] ?? '',
            'default_province_code' => $data['province_code'] ?? '',
            'default_province_name' => $data['province_name'] ?? '',
            'default_municipality_code' => $data['municipality_code'] ?? '',
            'default_municipality_name' => $data['municipality_name'] ?? ''
        ];
        
        $success = true;
        foreach ($keys as $key => $value) {
            $result = $this->setSetting($key, $value, 'string', 'Default location setting', $updatedBy);
            if (!$result) {
                $success = false;
            }
        }
        
        return $success;
    }
}
