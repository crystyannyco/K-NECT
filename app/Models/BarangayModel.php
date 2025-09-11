<?php
namespace App\Models;

use CodeIgniter\Model;

class BarangayModel extends Model
{
    protected $table = 'barangay';
    protected $primaryKey = 'barangay_id';
    protected $allowedFields = ['name', 'google_calendar_id'];
} 