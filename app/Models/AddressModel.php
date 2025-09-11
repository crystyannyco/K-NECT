<?php

namespace App\Models;

use CodeIgniter\Model;

class AddressModel extends Model
{
    protected $table      = 'address';
    protected $primaryKey = 'user_id';
    // user_id is a foreign key to user.id and not auto-incrementing here
    protected $useAutoIncrement = false;
    protected $returnType     = 'array';
    protected $allowedFields = ['user_id', 'address_type','house_number','street','subdivision','barangay','municipality','province','region','zone_purok','zip_code','created_at','updated_at'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Validation Rules
    protected $validationRules = [
        // 'address_type' => 'required|in_list[Permanent,Present]',
        // 'house_number' => 'permit_empty|max_length[50]',
        // 'street' => 'required|min_length[2]|max_length[100]',
        // 'subdivision' => 'permit_empty|max_length[100]',
    'barangay' => 'required|in_list[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36]',
        // 'municipality' => 'required|min_length[2]|max_length[100]|alpha_space',
        // 'province' => 'required|min_length[2]|max_length[100]|alpha_space',
        // 'region' => 'required|min_length[2]|max_length[50]|alpha_numeric_space',
        'zone_purok' => 'required|max_length[50]',
        // 'zip_code' => 'required|numeric|exact_length[4]'
    ];

    // Validation Messages
    protected $validationMessages = [
        // 'address_type' => [
        //     'required' => 'Address type is required',
        //     'in_list' => 'Address type must be either Permanent or Present'
        // ],
        // 'house_number' => [
        //     'max_length' => 'House number cannot exceed 50 characters'
        // ],
        // 'street' => [
        //     'required' => 'Street name is required',
        //     'min_length' => 'Street name must be at least 2 characters long',
        //     'max_length' => 'Street name cannot exceed 100 characters'
        // ],
        // 'subdivision' => [
        //     'max_length' => 'Subdivision name cannot exceed 100 characters'
        // ],
    'barangay' => [
            'required' => 'Barangay is required',
            'in_list' => 'Barangay must be a valid option',
        ],
        // 'municipality' => [
        //     'required' => 'Municipality is required',
        //     'min_length' => 'Municipality name must be at least 2 characters long',
        //     'max_length' => 'Municipality name cannot exceed 100 characters',
        //     'alpha_space' => 'Municipality name can only contain letters and spaces'
        // ],
        // 'province' => [
        //     'required' => 'Province is required',
        //     'min_length' => 'Province name must be at least 2 characters long',
        //     'max_length' => 'Province name cannot exceed 100 characters',
        //     'alpha_space' => 'Province name can only contain letters and spaces'
        // ],
        // 'region' => [
        //     'required' => 'Region is required',
        //     'min_length' => 'Region must be at least 2 characters long',
        //     'max_length' => 'Region cannot exceed 50 characters',
        //     'alpha_numeric_space' => 'Region can only contain letters, numbers, and spaces'
        // ],
        'zone_purok' => [
            'required' => 'Zone/Purok is required',
            'max_length' => 'Zone/Purok cannot exceed 50 characters'
        ],
        // 'zip_code' => [
        //     'required' => 'ZIP code is required',
        //     'numeric' => 'ZIP code must contain only numbers',
        //     'exact_length' => 'ZIP code must be exactly 4 digits'
        // ]
    ];
    
    protected $validationRules_skip_on_update = ['user_id'];
}
