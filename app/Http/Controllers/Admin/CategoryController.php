<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    public function GetAllCategory(Request $request)
    {
        $query = Category::query();

        if($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if($request->filled('parent_id')) {
            $query->where('parent_id', $request->parent_id);
        }

        $categories = $query->orderBy('id', 'desc')->paginate(10);
        return view('admin.category.index', compact('categories'));
    }
}
