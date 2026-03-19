<?php

namespace App\Models;

use CodeIgniter\Model;

class Category extends Model
{
  protected $table = 'categories';
  protected $primaryKey = 'id';
  protected $allowedFields = ['category_name'];
  protected $useTimestamps = true;

  protected $validationRules = [
    'category_name' => 'required|max_length[100]',
  ];
  protected $validationMessages = [
    'category_name' => [
      'required' => 'Category name is required.',
      'max_length' => 'Category name must be 100 characters or fewer.',
    ],
  ];
}
