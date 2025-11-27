<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class PSGCController extends Controller
{
    protected $cache;
    
    // PSGC Codes
    const REGION_V_CODE = '050000000'; // Bicol Region
    const CAMARINES_SUR_CODE = '051700000'; // Camarines Sur Province
    const IRIGA_CITY_CODE = '051716000'; // Iriga City
    
    // Cache Keys
    const CACHE_KEY_REGIONS = 'psgc_regions';
    const CACHE_KEY_PROVINCES = 'psgc_provinces_region_';
    const CACHE_KEY_MUNICIPALITIES = 'psgc_municipalities_province_';
    const CACHE_KEY_BARANGAYS = 'psgc_barangays_city_';
    const CACHE_TTL = 86400; // 24 hours
    
    public function __construct()
    {
        $this->cache = \Config\Services::cache();
    }
    
    /**
     * Get all regions from PSGC API
     * Returns JSON response
     */
    public function getRegions()
    {
        try {
            // Try to get from cache first
            $cachedData = $this->cache->get(self::CACHE_KEY_REGIONS);
            
            if ($cachedData !== null) {
                return $this->response->setJSON([
                    'success' => true,
                    'data' => $cachedData,
                    'source' => 'cache'
                ]);
            }
            
            // Fetch from API
            $apiUrl = "https://psgc.gitlab.io/api/regions/";
            
            // Use file_get_contents with stream context for better compatibility
            $context = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'header' => "Accept: application/json\r\n" .
                                "User-Agent: K-NECT/1.0\r\n",
                    'timeout' => 15,
                    'ignore_errors' => true
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false
                ]
            ]);
            
            $responseBody = @file_get_contents($apiUrl, false, $context);
            
            if ($responseBody === false) {
                throw new \Exception('Failed to fetch regions from PSGC API');
            }
            
            $regions = json_decode($responseBody, true);
            
            if (!is_array($regions)) {
                throw new \Exception('Invalid response from PSGC API');
            }
            
            // Transform data
            $transformedData = [];
            foreach ($regions as $region) {
                $transformedData[] = [
                    'code' => $region['code'] ?? '',
                    'name' => $region['name'] ?? '',
                    'regionName' => $region['regionName'] ?? null
                ];
            }
            
            // Cache the result
            $this->cache->save(self::CACHE_KEY_REGIONS, $transformedData, self::CACHE_TTL);
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $transformedData,
                'source' => 'api'
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'PSGC API Error (Regions): ' . $e->getMessage());
            
            // Return comprehensive fallback with all Philippine regions
            $fallbackRegions = [
                ['code' => '010000000', 'name' => 'Region I', 'regionName' => 'Ilocos Region'],
                ['code' => '020000000', 'name' => 'Region II', 'regionName' => 'Cagayan Valley'],
                ['code' => '030000000', 'name' => 'Region III', 'regionName' => 'Central Luzon'],
                ['code' => '040000000', 'name' => 'Region IV-A', 'regionName' => 'CALABARZON'],
                ['code' => '170000000', 'name' => 'Region IV-B', 'regionName' => 'MIMAROPA'],
                ['code' => '050000000', 'name' => 'Region V', 'regionName' => 'Bicol Region'],
                ['code' => '060000000', 'name' => 'Region VI', 'regionName' => 'Western Visayas'],
                ['code' => '070000000', 'name' => 'Region VII', 'regionName' => 'Central Visayas'],
                ['code' => '080000000', 'name' => 'Region VIII', 'regionName' => 'Eastern Visayas'],
                ['code' => '090000000', 'name' => 'Region IX', 'regionName' => 'Zamboanga Peninsula'],
                ['code' => '100000000', 'name' => 'Region X', 'regionName' => 'Northern Mindanao'],
                ['code' => '110000000', 'name' => 'Region XI', 'regionName' => 'Davao Region'],
                ['code' => '120000000', 'name' => 'Region XII', 'regionName' => 'SOCCSKSARGEN'],
                ['code' => '130000000', 'name' => 'Region XIII', 'regionName' => 'Caraga'],
                ['code' => '140000000', 'name' => 'NCR', 'regionName' => 'National Capital Region'],
                ['code' => '150000000', 'name' => 'CAR', 'regionName' => 'Cordillera Administrative Region'],
                ['code' => '160000000', 'name' => 'BARMM', 'regionName' => 'Bangsamoro Autonomous Region in Muslim Mindanao']
            ];
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $fallbackRegions,
                'source' => 'fallback',
                'warning' => 'Using offline data. PSGC API unavailable.'
            ]);
        }
    }
    
    /**
     * Get provinces for a specific region
     * @param string $regionCode
     */
    public function getProvinces($regionCode = null)
    {
        // Default to Region V if not provided
        $regionCode = $regionCode ?: self::REGION_V_CODE;
        
        try {
            $cacheKey = self::CACHE_KEY_PROVINCES . $regionCode;
            $cachedData = $this->cache->get($cacheKey);
            
            if ($cachedData !== null) {
                return $this->response->setJSON([
                    'success' => true,
                    'data' => $cachedData,
                    'source' => 'cache'
                ]);
            }
            
            // Fetch from API
            $apiUrl = "https://psgc.gitlab.io/api/regions/{$regionCode}/provinces/";
            
            // Use file_get_contents with stream context
            $context = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'header' => "Accept: application/json\r\n" .
                                "User-Agent: K-NECT/1.0\r\n",
                    'timeout' => 15,
                    'ignore_errors' => true
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false
                ]
            ]);
            
            $responseBody = @file_get_contents($apiUrl, false, $context);
            
            if ($responseBody === false) {
                throw new \Exception('Failed to fetch provinces from PSGC API');
            }
            
            $provinces = json_decode($responseBody, true);
            
            // Special handling for regions without provinces (e.g., NCR)
            if (!is_array($provinces) || empty($provinces)) {
                // Try to fetch cities/municipalities directly from region
                log_message('info', "No provinces found for region {$regionCode}, checking for direct cities/municipalities");
                
                $citiesUrl = "https://psgc.gitlab.io/api/regions/{$regionCode}/cities/";
                $munisUrl = "https://psgc.gitlab.io/api/regions/{$regionCode}/municipalities/";
                
                $directCities = [];
                
                // Get cities
                $citiesBody = @file_get_contents($citiesUrl, false, $context);
                if ($citiesBody !== false) {
                    $cities = json_decode($citiesBody, true);
                    if (is_array($cities)) {
                        $directCities = array_merge($directCities, $cities);
                    }
                }
                
                // Get municipalities
                $munisBody = @file_get_contents($munisUrl, false, $context);
                if ($munisBody !== false) {
                    $munis = json_decode($munisBody, true);
                    if (is_array($munis)) {
                        $directCities = array_merge($directCities, $munis);
                    }
                }
                
                if (!empty($directCities)) {
                    // Transform cities/municipalities to look like "provinces" for UI consistency
                    $transformedData = [];
                    foreach ($directCities as $city) {
                        $transformedData[] = [
                            'code' => $city['code'] ?? '',
                            'name' => $city['name'] ?? '',
                            'regionCode' => $regionCode,
                            'isDirectCity' => true // Flag to indicate this is a city, not a province
                        ];
                    }
                    
                    // Sort alphabetically
                    usort($transformedData, function($a, $b) {
                        return strcmp($a['name'], $b['name']);
                    });
                    
                    // Cache the result
                    $this->cache->save($cacheKey, $transformedData, self::CACHE_TTL);
                    
                    return $this->response->setJSON([
                        'success' => true,
                        'data' => $transformedData,
                        'source' => 'api',
                        'info' => 'Region has no provinces, showing cities/municipalities directly'
                    ]);
                }
                
                throw new \Exception('No provinces or cities found for region code: ' . $regionCode);
            }
            
            // Transform data
            $transformedData = [];
            foreach ($provinces as $province) {
                $transformedData[] = [
                    'code' => $province['code'] ?? '',
                    'name' => $province['name'] ?? '',
                    'regionCode' => $province['regionCode'] ?? $regionCode
                ];
            }
            
            // Sort alphabetically
            usort($transformedData, function($a, $b) {
                return strcmp($a['name'], $b['name']);
            });
            
            // Cache the result
            $this->cache->save($cacheKey, $transformedData, self::CACHE_TTL);
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $transformedData,
                'source' => 'api'
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'PSGC API Error (Provinces): ' . $e->getMessage());
            
            // Return fallback provinces based on region
            $fallbackProvinces = [];
            
            // For Region V (Bicol) - most common case
            if ($regionCode === self::REGION_V_CODE) {
                $fallbackProvinces = [
                    ['code' => '051600000', 'name' => 'Albay', 'regionCode' => self::REGION_V_CODE],
                    ['code' => '051700000', 'name' => 'Camarines Sur', 'regionCode' => self::REGION_V_CODE],
                    ['code' => '051800000', 'name' => 'Camarines Norte', 'regionCode' => self::REGION_V_CODE],
                    ['code' => '051900000', 'name' => 'Catanduanes', 'regionCode' => self::REGION_V_CODE],
                    ['code' => '052000000', 'name' => 'Masbate', 'regionCode' => self::REGION_V_CODE],
                    ['code' => '052100000', 'name' => 'Sorsogon', 'regionCode' => self::REGION_V_CODE]
                ];
            } else {
                // Generic fallback for other regions
                $fallbackProvinces = [
                    ['code' => $regionCode, 'name' => 'Province data unavailable', 'regionCode' => $regionCode]
                ];
            }
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $fallbackProvinces,
                'source' => 'fallback',
                'warning' => 'Using offline data. PSGC API unavailable.'
            ]);
        }
    }
    
    /**
     * Get cities/municipalities for a specific province
     * @param string $provinceCode
     */
    public function getMunicipalities($provinceCode = null)
    {
        // Default to Camarines Sur if not provided
        $provinceCode = $provinceCode ?: self::CAMARINES_SUR_CODE;
        
        try {
            $cacheKey = self::CACHE_KEY_MUNICIPALITIES . $provinceCode;
            $cachedData = $this->cache->get($cacheKey);
            
            if ($cachedData !== null) {
                return $this->response->setJSON([
                    'success' => true,
                    'data' => $cachedData,
                    'source' => 'cache'
                ]);
            }
            
            // Fetch cities and municipalities
            $apiUrls = [
                "https://psgc.gitlab.io/api/provinces/{$provinceCode}/cities/",
                "https://psgc.gitlab.io/api/provinces/{$provinceCode}/municipalities/"
            ];
            
            // Use file_get_contents with stream context
            $context = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'header' => "Accept: application/json\r\n" .
                                "User-Agent: K-NECT/1.0\r\n",
                    'timeout' => 15,
                    'ignore_errors' => true
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false
                ]
            ]);
            
            $allData = [];
            
            foreach ($apiUrls as $apiUrl) {
                $responseBody = @file_get_contents($apiUrl, false, $context);
                
                if ($responseBody !== false) {
                    $items = json_decode($responseBody, true);
                    if (is_array($items)) {
                        $allData = array_merge($allData, $items);
                    }
                }
            }
            
            if (empty($allData)) {
                throw new \Exception('Failed to fetch municipalities from PSGC API');
            }
            
            // Transform data
            $transformedData = [];
            foreach ($allData as $item) {
                $transformedData[] = [
                    'code' => $item['code'] ?? '',
                    'name' => $item['name'] ?? '',
                    'provinceCode' => $item['provinceCode'] ?? $provinceCode,
                    'isCity' => strpos($item['name'] ?? '', 'City') !== false
                ];
            }
            
            // Sort alphabetically
            usort($transformedData, function($a, $b) {
                return strcmp($a['name'], $b['name']);
            });
            
            // Cache the result
            $this->cache->save($cacheKey, $transformedData, self::CACHE_TTL);
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $transformedData,
                'source' => 'api'
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'PSGC API Error (Municipalities): ' . $e->getMessage());
            
            // Return fallback municipalities based on province
            $fallbackMunicipalities = [];
            
            // For Camarines Sur - most common case
            if ($provinceCode === self::CAMARINES_SUR_CODE) {
                $fallbackMunicipalities = [
                    ['code' => '051701000', 'name' => 'Baao', 'provinceCode' => self::CAMARINES_SUR_CODE, 'isCity' => false],
                    ['code' => '051702000', 'name' => 'Balatan', 'provinceCode' => self::CAMARINES_SUR_CODE, 'isCity' => false],
                    ['code' => '051703000', 'name' => 'Bato', 'provinceCode' => self::CAMARINES_SUR_CODE, 'isCity' => false],
                    ['code' => '051704000', 'name' => 'Bombon', 'provinceCode' => self::CAMARINES_SUR_CODE, 'isCity' => false],
                    ['code' => '051705000', 'name' => 'Buhi', 'provinceCode' => self::CAMARINES_SUR_CODE, 'isCity' => false],
                    ['code' => '051706000', 'name' => 'Bula', 'provinceCode' => self::CAMARINES_SUR_CODE, 'isCity' => false],
                    ['code' => '051707000', 'name' => 'Cabusao', 'provinceCode' => self::CAMARINES_SUR_CODE, 'isCity' => false],
                    ['code' => '051708000', 'name' => 'Calabanga', 'provinceCode' => self::CAMARINES_SUR_CODE, 'isCity' => false],
                    ['code' => '051709000', 'name' => 'Camaligan', 'provinceCode' => self::CAMARINES_SUR_CODE, 'isCity' => false],
                    ['code' => '051710000', 'name' => 'Canaman', 'provinceCode' => self::CAMARINES_SUR_CODE, 'isCity' => false],
                    ['code' => '051711000', 'name' => 'Caramoan', 'provinceCode' => self::CAMARINES_SUR_CODE, 'isCity' => false],
                    ['code' => '051712000', 'name' => 'Del Gallego', 'provinceCode' => self::CAMARINES_SUR_CODE, 'isCity' => false],
                    ['code' => '051713000', 'name' => 'Gainza', 'provinceCode' => self::CAMARINES_SUR_CODE, 'isCity' => false],
                    ['code' => '051714000', 'name' => 'Garchitorena', 'provinceCode' => self::CAMARINES_SUR_CODE, 'isCity' => false],
                    ['code' => '051715000', 'name' => 'Goa', 'provinceCode' => self::CAMARINES_SUR_CODE, 'isCity' => false],
                    ['code' => '051716000', 'name' => 'Iriga City', 'provinceCode' => self::CAMARINES_SUR_CODE, 'isCity' => true],
                    ['code' => '051717000', 'name' => 'Lagonoy', 'provinceCode' => self::CAMARINES_SUR_CODE, 'isCity' => false],
                    ['code' => '051718000', 'name' => 'Libmanan', 'provinceCode' => self::CAMARINES_SUR_CODE, 'isCity' => false],
                    ['code' => '051719000', 'name' => 'Lupi', 'provinceCode' => self::CAMARINES_SUR_CODE, 'isCity' => false],
                    ['code' => '051720000', 'name' => 'Magarao', 'provinceCode' => self::CAMARINES_SUR_CODE, 'isCity' => false],
                    ['code' => '051721000', 'name' => 'Milaor', 'provinceCode' => self::CAMARINES_SUR_CODE, 'isCity' => false],
                    ['code' => '051722000', 'name' => 'Minalabac', 'provinceCode' => self::CAMARINES_SUR_CODE, 'isCity' => false],
                    ['code' => '051723000', 'name' => 'Nabua', 'provinceCode' => self::CAMARINES_SUR_CODE, 'isCity' => false],
                    ['code' => '051724000', 'name' => 'Naga City', 'provinceCode' => self::CAMARINES_SUR_CODE, 'isCity' => true],
                    ['code' => '051725000', 'name' => 'Ocampo', 'provinceCode' => self::CAMARINES_SUR_CODE, 'isCity' => false],
                    ['code' => '051726000', 'name' => 'Pamplona', 'provinceCode' => self::CAMARINES_SUR_CODE, 'isCity' => false],
                    ['code' => '051727000', 'name' => 'Pasacao', 'provinceCode' => self::CAMARINES_SUR_CODE, 'isCity' => false],
                    ['code' => '051728000', 'name' => 'Pili', 'provinceCode' => self::CAMARINES_SUR_CODE, 'isCity' => false],
                    ['code' => '051729000', 'name' => 'Presentacion', 'provinceCode' => self::CAMARINES_SUR_CODE, 'isCity' => false],
                    ['code' => '051730000', 'name' => 'Ragay', 'provinceCode' => self::CAMARINES_SUR_CODE, 'isCity' => false],
                    ['code' => '051731000', 'name' => 'Sagñay', 'provinceCode' => self::CAMARINES_SUR_CODE, 'isCity' => false],
                    ['code' => '051732000', 'name' => 'San Fernando', 'provinceCode' => self::CAMARINES_SUR_CODE, 'isCity' => false],
                    ['code' => '051733000', 'name' => 'San Jose', 'provinceCode' => self::CAMARINES_SUR_CODE, 'isCity' => false],
                    ['code' => '051734000', 'name' => 'Sipocot', 'provinceCode' => self::CAMARINES_SUR_CODE, 'isCity' => false],
                    ['code' => '051735000', 'name' => 'Siruma', 'provinceCode' => self::CAMARINES_SUR_CODE, 'isCity' => false],
                    ['code' => '051736000', 'name' => 'Tigaon', 'provinceCode' => self::CAMARINES_SUR_CODE, 'isCity' => false],
                    ['code' => '051737000', 'name' => 'Tinambac', 'provinceCode' => self::CAMARINES_SUR_CODE, 'isCity' => false]
                ];
            } else {
                // Generic fallback for other provinces
                $fallbackMunicipalities = [
                    ['code' => $provinceCode, 'name' => 'Municipality data unavailable', 'provinceCode' => $provinceCode, 'isCity' => false]
                ];
            }
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $fallbackMunicipalities,
                'source' => 'fallback',
                'warning' => 'Using offline data. PSGC API unavailable.'
            ]);
        }
    }
    
    /**
     * Get barangays for a specific city/municipality
     * @param string $cityCode
     */
    public function getBarangays($cityCode = null)
    {
        // Default to Iriga City if not provided
        $cityCode = $cityCode ?: self::IRIGA_CITY_CODE;
        
        try {
            $cacheKey = self::CACHE_KEY_BARANGAYS . $cityCode;
            $cachedData = $this->cache->get($cacheKey);
            
            if ($cachedData !== null) {
                return $this->response->setJSON([
                    'success' => true,
                    'data' => $cachedData,
                    'source' => 'cache'
                ]);
            }
            
            // Fetch from API
            $apiUrl = "https://psgc.gitlab.io/api/cities-municipalities/{$cityCode}/barangays/";
            
            // Use file_get_contents with stream context
            $context = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'header' => "Accept: application/json\r\n" .
                                "User-Agent: K-NECT/1.0\r\n",
                    'timeout' => 15,
                    'ignore_errors' => true
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false
                ]
            ]);
            
            $responseBody = @file_get_contents($apiUrl, false, $context);
            
            if ($responseBody === false) {
                throw new \Exception('Failed to fetch barangays from PSGC API');
            }
            
            $barangays = json_decode($responseBody, true);
            
            if (!is_array($barangays)) {
                throw new \Exception('Invalid response from PSGC API');
            }
            
            // Transform data
            $transformedData = [];
            foreach ($barangays as $barangay) {
                $transformedData[] = [
                    'code' => $barangay['code'] ?? '',
                    'name' => $barangay['name'] ?? '',
                    'oldName' => $barangay['oldName'] ?? null,
                    'cityCode' => $barangay['cityCode'] ?? $cityCode
                ];
            }
            
            // Sort alphabetically
            usort($transformedData, function($a, $b) {
                return strcmp($a['name'], $b['name']);
            });
            
            // Cache the result
            $this->cache->save($cacheKey, $transformedData, self::CACHE_TTL);
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $transformedData,
                'source' => 'api'
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'PSGC API Error (Barangays): ' . $e->getMessage());
            
            // Return fallback for Iriga City
            return $this->getFallbackBarangays();
        }
    }
    
    /**
     * Legacy method - Get barangays for Iriga City (backward compatibility)
     * Returns JSON response
     */
    public function getIrigaBarangays()
    {
        return $this->getBarangays(self::IRIGA_CITY_CODE);
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
            'source' => 'fallback',
            'warning' => 'Using offline data. PSGC API unavailable.'
        ]);
    }
    
    /**
     * Clear all PSGC caches
     * Useful for manual refresh
     */
    public function clearCache()
    {
        // Clear all PSGC-related caches
        $this->cache->delete(self::CACHE_KEY_REGIONS);
        
        // Clear province caches (Region V)
        $this->cache->delete(self::CACHE_KEY_PROVINCES . self::REGION_V_CODE);
        
        // Clear municipality caches (Camarines Sur)
        $this->cache->delete(self::CACHE_KEY_MUNICIPALITIES . self::CAMARINES_SUR_CODE);
        
        // Clear barangay caches (Iriga City)
        $this->cache->delete(self::CACHE_KEY_BARANGAYS . self::IRIGA_CITY_CODE);
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'All PSGC caches cleared successfully'
        ]);
    }
    
    /**
     * Get barangay details by PSGC code
     */
    public function getBarangayByCode($code)
    {
        try {
            $cacheKey = self::CACHE_KEY_BARANGAYS . self::IRIGA_CITY_CODE;
            $barangays = $this->cache->get($cacheKey);
            
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
