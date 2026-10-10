<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = $request->user()->categories;

        return response()->json([
            "message" => "Get All Categories",
            "categories" => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'unique:categories,name'],
        ]);

        $category = $request->user()->categories()->create($validated);

        return response()->json([
            "message" => "Category created successfully",
            "category" => $category
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $category = $request->user()->categories()->find($id);

        if ($category === null) {
            return response()->json([
                "message" => "Category not found"
            ], 404);
        }

        return response()->json([
            "message" => "Get Category By ID Successfully",
            "category" => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = $request->user()->categories()->find($id);

        if ($category === null) {
            return response()->json([
                "message" => "Category not found"
            ], 404);
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:3',
                Rule::unique('categories', 'name')->ignore($category->id),
            ],
        ]);

        $category->update($validated);

        return response()->json([
            "message" => "Category updated successfully",
            "category" => $category->refresh()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $category = $request->user()->categories()->find($id);

        if ($category === null) {
            return response()->json([
                "message" => "Category not found"
            ], 404);
        }

        $category->delete();

        return response()->noContent();
    }
}
