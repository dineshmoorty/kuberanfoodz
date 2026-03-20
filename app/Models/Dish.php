<?php

namespace App\Models;

use CodeIgniter\Model;

class Dish extends Model
{
    protected $table            = 'dishes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'dish_name',
        'dish_price',
        'dish_mrp',
        'dish_image',
        'dish_thumbnails',
        'dish_desc',
        'category_id',
        'is_daily',
        'status',
        'created_at',
        'updated_at',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'dish_name' => 'required|min_length[2]|max_length[150]',
        'dish_price' => 'required|decimal',
        'dish_mrp' => 'required|decimal',
        'dish_desc' => 'permit_empty|max_length[5000]',
        'category_id' => 'required|integer|greater_than[0]',
        'status' => 'required|in_list[0,1]',
    ];

    protected $validationMessages = [
        'dish_name' => [
            'required' => 'Dish name is required.',
        ],
        'dish_price' => [
            'required' => 'Dish price is required.',
            'decimal' => 'Dish price must be a valid amount.',
        ],
        'dish_mrp' => [
            'required' => 'Dish MRP is required.',
            'decimal' => 'Dish MRP must be a valid amount.',
        ],
        'category_id' => [
            'required' => 'Please select a category.',
        ],
    ];
}
