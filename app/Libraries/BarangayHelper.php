<?php

namespace App\Libraries;

/**
 * BarangayHelper - Legacy support for old barangay ID system
 * 
 * This class maintains the mapping of old numeric barangay IDs (1-36) 
 * to Iriga City barangay names for backward compatibility with existing data.
 * 
 * NEW IMPLEMENTATIONS should use the PSGC API (PSGCController) instead,
 * which provides dynamic barangay data for ALL Philippine municipalities,
 * not just Iriga City.
 * 
 * This helper is kept only for:
 * - Converting legacy barangay IDs in existing database records
 * - Supporting old data during migration period
 * - Display purposes for historical data
 */
class BarangayHelper
{
    /**
     * Get the legacy mapping of barangay IDs to names (Iriga City only)
     * 
     * @return array Map of barangay ID => barangay name
     * @deprecated Use PSGC API for new implementations
     */
    public static function getBarangayMap()
    {
        return [
            1 => 'Antipolo',
            2 => 'Cristo Rey', 
            3 => 'Del Rosario (Banao)',
            4 => 'Francia',
            5 => 'La Anunciacion',
            6 => 'La Medalla',
            7 => 'La Purisima',
            8 => 'La Trinidad',
            9 => 'Niño Jesus',
            10 => 'Perpetual Help',
            11 => 'Sagrada',
            12 => 'Salvacion',
            13 => 'San Agustin',
            14 => 'San Andres',
            15 => 'San Antonio',
            16 => 'San Francisco',
            17 => 'San Isidro',
            18 => 'San Jose',
            19 => 'San Juan',
            20 => 'San Miguel',
            21 => 'San Nicolas',
            22 => 'San Pedro',
            23 => 'San Rafael',
            24 => 'San Ramon',
            25 => 'San Roque',
            26 => 'Santiago',
            27 => 'San Vicente Norte',
            28 => 'San Vicente Sur',
            29 => 'Santa Cruz Norte',
            30 => 'Santa Cruz Sur',
            31 => 'Santa Elena',
            32 => 'Santa Isabel',
            33 => 'Santa Maria',
            34 => 'Santa Teresita',
            35 => 'Santo Domingo',
            36 => 'Santo Niño'
        ];
    }

    /**
     * Convert legacy barangay ID to barangay name (Iriga City only)
     * 
     * @param int|string $barangayId Legacy barangay ID (1-36)
     * @return string Barangay name or the original value if not found
     * @deprecated Use PSGC API for new implementations
     */
    public static function getBarangayName($barangayId)
    {
        $barangayMap = self::getBarangayMap();
        return isset($barangayMap[$barangayId]) ? $barangayMap[$barangayId] : $barangayId;
    }
}
