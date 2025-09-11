<?php

namespace App\Libraries;

class ZoneHelper
{
    public static function getZoneMap()
    {
        return [
            1 => 'Zone 1',
            2 => 'Zone 2', 
            3 => 'Zone 3',
            4 => 'Zone 4',
            5 => 'Zone 5',
            6 => 'Zone 6',
            7 => 'Zone 7',
            8 => 'Zone 8',
            9 => 'Zone 9',
            10 => 'Zone 10',
            11 => 'Zone 11',
            12 => 'Zone 12',
            13 => 'Zone 13',
            14 => 'Zone 14',
            15 => 'Zone 15',
            16 => 'Zone 16',
            17 => 'Zone 17',
            18 => 'Zone 18',
            19 => 'Zone 19',
            20 => 'Zone 20',
            // Add more zones as needed
            // You can also use Purok naming if preferred:
            // 1 => 'Purok 1',
            // 2 => 'Purok 2',
            // etc.
        ];
    }

    public static function getZoneName($zoneId)
    {
        $zoneMap = self::getZoneMap();
        return isset($zoneMap[$zoneId]) ? $zoneMap[$zoneId] : "Zone $zoneId";
    }
}
