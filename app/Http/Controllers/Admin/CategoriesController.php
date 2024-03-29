<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CategoriesController extends Controller
{
    public function showPage() {
        $categories = Category::all();
        return view('admin.categories', compact('categories'));
    }

    public function addCategory(CategoryRequest $req) {
        $addedCategory = Category::create($req->validated());
        if (!$addedCategory) {
            return redirect()->back()->withErrors('add_category_status', [
                'status' => 0,
                'message' => 'Failed to add category, please try again'
            ]);
        }

        return redirect()->back()->with('add_category_status', [
            'status' => 1,
            'message' => 'Category added successfully',
            'id' => $addedCategory->id
        ]);
    }
}
