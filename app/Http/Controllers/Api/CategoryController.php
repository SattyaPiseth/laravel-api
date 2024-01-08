<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $category = Category::orderBy('name', 'ASC');

        if ($request->has('search') && !empty($request->search)) {
            $category = $category->where('name', 'like', '%' . $request->search . '%');
        }
        $category = $category->paginate(10);

        // // $category = Category::all();
        return response()->json(['category' => $category], 200);
    }

    public function show(Category $id)
    {

        return new CategoryResource($id);
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',

        ]);
        $category = Category::create($data);
        return response(new CategoryResource($category), 201);
    }
    public function update(Request $request)
    {

        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',

        ]);
        if (isset($data)) {
            $category = Category::find($request->id);
            $category->update($data);
            if (!$category) {
                return response()->json([
                    'message' => 'Category cannot be updated'
                ], 404);
            } else {
                return [
                    'data' => new CategoryResource($category),
                    'message' => 'Category has been updated successfully!',
                ];
            }
        }
    }

    public function delete(Category $cate, $id)
    {
        $category = $cate->find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Category not found Id'
            ], 404);
        } else {

            $category->delete();

            return response(['message' => 'category have been delete successfully!'], 201);
        }
        // $tblcate = $category->getTable();
        // DB::table($tblcate)->truncate(); //! clean table
        // DB::statement("ALTER TABLE $tblcate AUTO_INCREMENT = 1"); //?set to 1
    }
}
