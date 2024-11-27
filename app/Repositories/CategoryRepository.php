<?php

namespace App\Repositories;

use App\Models\Admin;
use App\Models\Category;

use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Support\Facades\DB;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getAllCategories()
    {
        return Category::all();
    }
    public function createCategory(array $data)
    {
        return Category::create($data);
    }
}
