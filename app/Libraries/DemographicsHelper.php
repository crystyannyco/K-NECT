<?php

namespace App\Libraries;

class DemographicsHelper
{
    public static function civilStatusMap(): array
    {
        return [
            '1' => 'Single',
            '2' => 'Married',
            '3' => 'Widowed',
            '4' => 'Divorced',
            '5' => 'Separated',
            '6' => 'Annulled',
            '7' => 'Live-In',
            '8' => 'Unknown',
        ];
    }

    public static function youthClassificationMap(): array
    {
        return [
            '1' => 'In School Youth',
            '2' => 'Out-of-School Youth',
            '3' => 'Working Youth',
            '4' => 'Youth with Special Needs',
            '5' => 'Person with Disability',
            '6' => 'Children in Conflict with the Law',
            '7' => 'Indigenous People',
        ];
    }

    public static function youthAgeGroupMap(): array
    {
        // Note: App logic groups ages as 15-17, 18-24, 25-30.
        // Label for group 3 follows 25-30 to match logic.
        return [
            '1' => 'Child Youth (15-17 yrs old)',
            '2' => 'Core Youth (18-24 yrs old)',
            '3' => 'Young Adult (25-30 yrs old)',
        ];
    }

    public static function workStatusMap(): array
    {
        return [
            '1' => 'Employed',
            '2' => 'Unemployed',
            '3' => 'Currently looking for a Job',
            '4' => 'Not Interested in finding Job',
        ];
    }

    public static function educationalBackgroundMap(): array
    {
        return [
            '1' => 'Elementary Level',
            '2' => 'Elementary Graduate',
            '3' => 'High School Level',
            '4' => 'High School Graduate',
            '5' => 'Vocational Level',
            '6' => 'College Level',
            '7' => 'College Graduate',
            '8' => 'Master Level',
            '9' => 'Master Graduate',
            '10' => 'Doctorate Level',
            '11' => 'Doctorate Graduate',
        ];
    }

    public static function howManyTimesMap(): array
    {
        return [
            '1' => '1-2 times',
            '2' => '3-4 times',
            '3' => '5 or more times',
        ];
    }

    public static function noWhyMap(): array
    {
        // If No, Why
        return [
            '1' => 'There was no KK Assembly Meeting',
            '0' => 'Not Interested to Attend',
            // Backward-compatibility alias if legacy data used 2 for "Not Interested"
            '2' => 'Not Interested to Attend',
        ];
    }

    public static function allMapsForJs(): array
    {
        return [
            'civilStatusMap' => self::civilStatusMap(),
            'youthClassificationMap' => self::youthClassificationMap(),
            'ageGroupMap' => self::youthAgeGroupMap(),
            'workStatusMap' => self::workStatusMap(),
            'educationMap' => self::educationalBackgroundMap(),
            'howManyTimesMap' => self::howManyTimesMap(),
            'noWhyMap' => self::noWhyMap(),
        ];
    }
}
