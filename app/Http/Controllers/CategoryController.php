<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7', 
        ]);

        $category = Category::create($validated);

        return response()->json($category, 201);
    }

    public function index()
    {
        return response()->json(Category::all());
    }

    public function update(Request $request, $id)
{
    $category = Category::findOrFail($id);
    $category->update($request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'color' => 'nullable|string|max:7',
    ]));

    return response()->json($category);
}

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Categoría eliminada']);
    }

    public function progressHabit($id)
    {
        $category = Category::with(['habits' => function ($q) {
            $q->where('user_id', Auth::id());
        }])->findOrFail($id);

        $total = $category->habits->count();
        $sum = $category->habits->sum('progress');

        $progress = $total > 0 ? intval($sum / $total) : 0;

        return response()->json(['category_id' => $id, 'progress' => $progress]);
    }

}
