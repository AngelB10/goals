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

    $subItem = SubItem::with('habit')->whereHas('habit', function ($query) use ($user) {
        $query->where('user_id', $user->id);
    })->findOrFail($id);

    $subItem->done = !$subItem->done;
    $subItem->save();
    $habit = $subItem->habit;

    $completedSubitems = $habit->subItems()->where('done', true)->count();
    $totalSubitems = $habit->subItems()->count();

    $habit->progress = $totalSubitems > 0 
        ? round(($completedSubitems / $totalSubitems) * 100) 
        : 0;

    if ($completedSubitems < $totalSubitems) {
        $habit->completed = false;
    } else if ($totalSubitems > 0 && $completedSubitems === $totalSubitems) {
        $habit->completed = true;
    }

    $habit->save();

    return response()->json([
        'message' => 'Subtarea actualizada',
        'subitem' => $subItem,
        'habit' => $habit
    ]);
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
