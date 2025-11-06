<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class PSGCController extends Controller
{
    protected $cache;
    
    // Iriga City PSGC Code
    const IRIGA_CITY_CODE = '051716000';
    const CACHE_KEY = 'psgc_iriga_barangays';
    const CACHE_TTL = 86400; // 24 hours
    
    public function __construct()
    {
        $this->cache = \Config\Services::cache();
    }
    
    /**
     * Get barangays for Iriga City from PSGC API
     * Returns JSON response
     */
    public function getIrigaBarangays()
    {
        try {
            // Try to get from cache first
            $cachedData = $this->cache->get(self::CACHE_KEY);
            
            if ($cachedData !== null) {
                return $this->response->setJSON([
                    'success' => true,
                    'data' => $cachedData,
                    'source' => 'cache'
                ]);
            }
            
            // Fetch from API if not in cache
            $apiUrl = "https://psgc.gitlab.io/api/cities/" . self::IRIGA_CITY_CODE . "/barangays/";
            
            $client = \Config\Services::curlrequest();
            $response = $client->get($apiUrl, [
                'timeout' => 10,
                'http_errors' => false
            ]);
            
            if ($response->getStatusCode() !== 200) {
                throw new \Exception('Failed to fetch data from PSGC API');
            }
            
            $barangays = json_decode($response->getBody(), true);
            
            if (!is_array($barangays)) {
                throw new \Exception('Invalid response from PSGC API');
            }
            
            // Transform data for easier use
            $transformedData = [];
            foreach ($barangays as $barangay) {
                $transformedData[] = [
                    'code' => $barangay['code'] ?? '',
                    'name' => $barangay['name'] ?? '',
                    'oldName' => $barangay['oldName'] ?? null,
                    'cityCode' => $barangay['cityCode'] ?? self::IRIGA_CITY_CODE
                ];
            }
            
            // Sort alphabetically by name
            usort($transformedData, function($a, $b) {
                return strcmp($a['name'], $b['name']);
            });
            
            // Cache the result
            $this->cache->save(self::CACHE_KEY, $transformedData, self::CACHE_TTL);
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $transformedData,
                'source' => 'api'
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'PSGC API Error: ' . $e->getMessage());
            
            // Return fallback data from BarangayHelper
            return $this->getFallbackBarangays();
        }
    }
    
    /**
     * Get fallback barangay data when API is unavailable
     * Uses the existing BarangayHelper data with proper PSGC codes
     */
    private function getFallbackBarangays()
    {
        $barangayMap = \App\Libraries\BarangayHelper::getBarangayMap();
        
        $fallbackData = [];
        foreach ($barangayMap as $id => $name) {
            // Generate proper PSGC code based on barangay sequence
            $psgcCode = '05171600' . str_pad($id, 1, '0', STR_PAD_LEFT);
            
            $fallbackData[] = [
                'code' => $psgcCode,
                'name' => $name,
                'oldName' => null,
                'cityCode' => self::IRIGA_CITY_CODE
            ];
        }
        
        // Sort alphabetically
        usort($fallbackData, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $fallbackData,
            'source' => 'fallback'
        ]);
    }
    
    /**
     * Clear the PSGC cache
     * Useful for manual refresh
     */
    public function clearCache()
    {
        $this->cache->delete(self::CACHE_KEY);
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'PSGC cache cleared successfully'
        ]);
    }
    
    /**
     * Get barangay details by PSGC code
     */
    public function getBarangayByCode($code)
    {
        try {
            $barangays = $this->cache->get(self::CACHE_KEY);
            
            if (!$barangays) {
                // Fetch fresh data
                $result = $this->getIrigaBarangays();
                $responseData = json_decode($result->getBody(), true);
                $barangays = $responseData['data'] ?? [];
            }
            
            foreach ($barangays as $barangay) {
                if ($barangay['code'] === $code) {
                    return $this->response->setJSON([
                        'success' => true,
                        'data' => $barangay
                    ]);
                }
            }
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Barangay not found'
            ], 404);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
