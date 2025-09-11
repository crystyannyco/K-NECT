<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'user';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $allowedFields = ['last_name','first_name','middle_name','sex', 'suffix','birthdate','email','phone_number', 'username','status','user_type','position','is_active','password','sk_username','sk_password','ped_username','ped_password','ped_position','created_at','updated_at', 'user_id', 'last_login', 'rfid_code'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $validationRules = [
        'last_name' => 'required|alpha_space|min_length[2]',
        'first_name' => 'required|alpha_space|min_length[2]',
        'middle_name' => 'permit_empty|alpha_space',
        'sex' => 'required|in_list[1,2]',
        'birthdate' => 'required|valid_date',
        'email' => 'required|valid_email|is_unique[user.email]',
        'phone_number' => 'required|numeric|min_length[7]',
        'username' => 'required|min_length[4]|max_length[30]|is_unique[user.username]',
        'password' => 'required|min_length[6]',
        // 'confirm_password' => 'required|matches[password]',
    ];
    protected $validationMessages = [
        'last_name' => [
            'required' => 'Last Name is required.',
            'alpha_space' => 'Last Name can only contain letters and spaces.',
            'min_length[2]' => 'Last Name must be at least 2 characters.'
        ],
        'first_name' => [
            'required' => 'First Name is required.',
            'alpha_space' => 'First Name can only contain letters and spaces.',
            'min_length[2]' => 'First Name must be at least 2 characters.'
        ],
        'middle_name' => [
            'alpha_space' => 'Middle Name can only contain letters and spaces.',
            'min_length[2]' => 'Middle Name must be at least 2 characters.'
        ],
        'sex' => [
            'required' => 'Sex is required.',
            'in_list' => 'Please select a valid sex.'
        ],
        'birthdate' => [
            'required' => 'Birthdate is required.',
            'valid_date' => 'Please enter a valid date.'
        ],
        'email' => [
            'required' => 'Email is required.',
            'valid_email' => 'Please enter a valid email address.',
            'is_unique' => 'This email is already registered.'
        ],
        'phone_number' => [
            'required' => 'Phone Number is required.',
            'numeric' => 'Phone Number must be a number.',
            'min_length' => 'Phone Number must be at least 11 digits.'
        ],
        'username' => [
            'required' => 'The Username field is required.',
            'min_length' => 'The Username field must be at least 4 characters.',
            'max_length' => 'The Username field must not exceed 30 characters.',
            'is_unique' => 'This username is already taken.'
        ],
        'password' => [
            'required' => 'The Password field is required.',
            'min_length' => 'The Password field must be at least 6 characters.'
        ],
    ];

}
