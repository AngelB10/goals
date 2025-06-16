<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HabitController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $habits = Habit::where('user_id', $user->id)->with('category', 'subItems')->get();
        return response()->json($habits);
    }

   public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'type' => 'required|in:diaria,semanal,mensual,anual,unica,recurrente',
        'frequency' => 'nullable|integer|min:1',
        'start_date' => 'required|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'time' => 'nullable',
        'category_id' => 'required|exists:categories,id',
        'target' => 'nullable|integer|min:1',
        'days_of_week' => 'nullable|array',
        'days_of_week.*' => 'string',
        'subItems' => 'nullable|array',
        'subItems.*.title' => 'required|string|max:255',
        'subItems.*.done' => 'required|boolean',
    ]);

    $validated['user_id'] = Auth::id();

    $habit = Habit::create($validated);


    if (!empty($validated['days_of_week'])) {
        $habit->days_of_week = $validated['days_of_week']; 
        $habit->save();
    }

    if (!empty($validated['subItems'])) {
        foreach ($validated['subItems'] as $item) {
            $habit->subItems()->create([
                'title' => $item['title'],
                'done' => $item['done']
            ]);
        }
    }

    return response()->json($habit->load('subItems'), 201);
}



    public function complete($id)
    {
        $user = Auth::user();
        $habit = Habit::where('id', $id)->where('user_id', $user->id)->firstOrFail();
        $habit->completed = true;
        $habit->progress = 100;
        $habit->save();

        return response()->json(['message' => 'Hábito marcado como completado', 'habit' => $habit]);
    }

public function uncomplete($id)
{
    $user = Auth::user();
    $habit = Habit::where('id', $id)
        ->where('user_id', $user->id)
        ->with('subItems') 
        ->firstOrFail();

    $habit->completed = false;

    $completedSubitems = $habit->subItems()->where('done', true)->count();
    $totalSubitems = $habit->subItems()->count();

    $habit->progress = $totalSubitems > 0 
        ? round(($completedSubitems / $totalSubitems) * 100) 
        : 0;

    $habit->save();

    return response()->json([
        'message' => 'Hábito desmarcado como no cumplido.',
        'habit' => $habit
    ]);
}



    public function show($id)
    {
        $habit = Habit::with('subItems', 'category')->findOrFail($id);
        return response()->json($habit);
    }

    public function update(Request $request, $id)
    {
        $habit = Habit::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $habit->update($request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:diaria,semanal,única,recurrente',
            'frequency' => 'nullable|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'time' => 'nullable',
            'category_id' => 'required|exists:categories,id',
            'target' => 'nullable|integer|min:1',
        ]));

        return response()->json($habit);
    }

    public function destroy($id)
    {
        $habit = Habit::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $habit->delete();

        return response()->json(['message' => 'Hábito eliminado']);
    }

    public function updateProgress($habitId)
    {
        $habit = Habit::with('subItems')
            ->where('id', $habitId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $total = $habit->subItems->count();
        $completed = $habit->subItems->where('done', true)->count();

        $progress = $total > 0 ? intval(($completed / $total) * 100) : 0;
        $habit->progress = $progress;
        $habit->save();

        return response()->json([
            'message' => 'Progress updated',
            'progress' => $progress
        ]);
    }

    

}

