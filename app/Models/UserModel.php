<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'username',
        'full_name',
        'avatar',
        'password',
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
        'id'        => 'permit_empty|is_natural_no_zero',
        'full_name' => 'required|min_length[2]|max_length[100]',
        'username'  => 'required|min_length[3]|max_length[50]|is_unique[users.username,id,{id}]',
        'avatar'    => 'permit_empty|max_length[255]',
        'password'  => 'permit_empty|max_length[255]',
    ];

    protected $validationMessages = [
        'full_name' => [
            'required'   => 'Full Name is required.',
            'min_length' => 'Full Name must be at least 2 characters.',
            'max_length' => 'Full Name cannot exceed 100 characters.',
        ],
        'username' => [
            'required'   => 'Username is required.',
            'min_length' => 'Username must be at least 3 characters.',
            'max_length' => 'Username cannot exceed 50 characters.',
            'is_unique'  => 'This username is already taken. Please choose another.',
        ],
        'password' => [
            'max_length' => 'Password hash cannot exceed 255 characters.',
        ],
    ];

    /**
     * Ensure the primary key is present in the dataset so that {id}
     * placeholder replacement functions correctly during Model validation.
     */
    public function update($id = null, $row = null): bool
    {
        if (is_numeric($id) && is_array($row) && ! isset($row[$this->primaryKey])) {
            $row[$this->primaryKey] = $id;
        }

        return parent::update($id, $row);
    }
}
