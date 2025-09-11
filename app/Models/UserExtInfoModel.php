<?php

namespace App\Models;

use CodeIgniter\Model;

class UserExtInfoModel extends Model
{
    protected $table      = 'user_ext_info';
    protected $primaryKey = 'user_id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $allowedFields = ['user_id','civil_status','youth_classification','age_group','work_status', 'educational_background','sk_voter', 'sk_election', 'national_voter', 'kk_assembly', 'how_many_times', 'no_why', 'created_at', 'updated_at', 'birth_certificate', 'upload_id', 'upload_id-back', 'reason', 'profile_picture'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Validation rules for demographic profile
    protected $validationRules = [
        'civil_status' => 'required|in_list[1,2,3,4,5,6,7,8]',
        'youth_classification' => 'required|in_list[1,2,3,4,5,6,7]',
        'age_group' => 'required|in_list[1,2,3]',
        'work_status' => 'required|in_list[1,2,3,4]',
        'educational_background' => 'required|in_list[1,2,3,4,5,6,7,8,9,10,11]',
        'sk_voter' => 'required|in_list[0,1]',
        'sk_election' => 'required|in_list[0,1]',
        'national_voter' => 'required|in_list[0,1]',
        'kk_assembly' => 'required|in_list[0,1]',
        'birth_certificate' => 'permit_empty|string',
        'upload_id' => 'permit_empty|string',
    'upload_id-back' => 'permit_empty|string',
        'profile_picture' => 'required|string',
    ];

    protected $validationMessages = [
        'civil_status' => [
            'required' => 'Civil status is required.',
            'in_list' => 'Please select a valid civil status.'
        ],
        'youth_classification' => [
            'required' => 'Youth classification is required.',
            'in_list' => 'Please select a valid youth classification.'
        ],
        'age_group' => [
            'required' => 'Age group is required.',
            'in_list' => 'Please select a valid age group.'
        ],
        'work_status' => [
            'required' => 'Work status is required.',
            'in_list' => 'Please select a valid work status.'
        ],
        'educational_background' => [
            'required' => 'Educational background is required.',
            'in_list' => 'Please select a valid educational background.'
        ],
        'sk_voter' => [
            'required' => 'SK voter selection is required.',
            'in_list' => 'Please select Yes or No for SK voter.'
        ],
        'sk_election' => [
            'required' => 'SK election selection is required.',
            'in_list' => 'Please select Yes or No for SK election.'
        ],
        'national_voter' => [
            'required' => 'National voter selection is required.',
            'in_list' => 'Please select Yes or No for National voter.'
        ],
        'kk_assembly' => [
            'required' => 'KK assembly selection is required.',
            'in_list' => 'Please select Yes or No for KK assembly.'
        ],
        'birth_certificate' => [
            'required' => 'Birth certificate is required.'
        ],
        'upload_id' => [
            'required' => 'ID upload is required.'
        ],
    // Back side of ID is optional; no required message
        'profile_picture' => [
            'required' => 'Profile picture is required.'
        ],
    ];
}
