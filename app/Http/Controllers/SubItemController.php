<?php

namespace App\Http\Controllers;

use App\Models\SubItem;
use App\Models\Habit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubItemController extends Controller
{

    public function store(Request $request, $habitId)
    {
        $user = Auth::user();

        $habit = Habit::where('id', $habitId)->where('user_id', $user->id)->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $subItem = new SubItem([
            'title' => $validated['title'],
            'done' => false,
        ]);

        $habit->subItems()->save($subItem);

        return response()->json($subItem, 201);
    }


    public function toggle($id)
    {
        $user = Auth::user();

        $subItem = SubItem::findOrFail($id);

        if ($subItem->habit->user_id !== $user->id) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $subItem->done = !$subItem->done;
        $subItem->save();

        return response()->json(['message' => 'Estado actualizado', 'subitem' => $subItem]);
    }

        public function update(Request $request, $id)
    {
        $subItem = SubItem::findOrFail($id);

        if ($subItem->habit->user_id !== Auth::id()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $subItem->update($request->validate([
            'title' => 'required|string|max:255',
            'done' => 'boolean',
        ]));

        return response()->json($subItem);
    }

    public function destroy($id)
    {
        $subItem = SubItem::findOrFail($id);

        if ($subItem->habit->user_id !== Auth::id()) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $subItem->delete();

        return response()->json(['message' => 'Subitem eliminado']);
    }
}
