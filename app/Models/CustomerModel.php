<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table            = 'customers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'full_name',
        'email',
        'phone',
        'created_at',
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    // Validation
    protected $validationRules = [
        'full_name' => 'required|min_length[2]|max_length[100]',
        'email'     => 'required|max_length[100]|valid_email',
        'phone'     => 'permit_empty|max_length[20]',
    ];

    protected $validationMessages = [
        'full_name' => [
            'required'   => 'Full Name is required.',
            'min_length' => 'Full Name must be at least 2 characters.',
            'max_length' => 'Full Name cannot exceed 100 characters.',
        ],
        'email' => [
            'required'    => 'Email Address is required.',
            'valid_email' => 'Please provide a valid email address.',
            'max_length'  => 'Email Address cannot exceed 100 characters.',
        ],
    ];
}
