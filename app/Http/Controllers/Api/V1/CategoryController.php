<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryWorkerResource;
use App\Http\Resources\FavoriteResource;
use App\Http\Resources\WorkerResource;
use App\Models\Category;
use App\Traits\HttpResponses;


class CategoryController extends Controller
{

    use HttpResponses;

    public function getCategory()
    {
        $category = Category::withCount('workers')->get();

        if ($category->isEmpty()) {
            return $this->error('', 'No categories found', 404);
        }
        return CategoryResource::collection($category);
    }

    public function getWorkersFromCategory($id)
    {
        $category = Category::find($id);
        if ($category) {
            return FavoriteResource::collection($category->workers);
        } else {
            return $this->error('', 'Category not found', 404);
        }
    }
}

