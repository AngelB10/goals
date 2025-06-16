<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HabitController;
use App\Http\Controllers\SubItemController;

Route::middleware('auth:sanctum')->group(function () {
    // CATEGORÍAS
    Route::get('/categories', [CategoryController::class, 'index']);     
    Route::post('/categories', [CategoryController::class, 'store']);    
    Route::put('/categories/{id}', [CategoryController::class, 'update']); 
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']); 
    Route::put('/habits/{id}/progress', [HabitController::class, 'updateProgress']);

    // HÁBITOS
    Route::get('/habits', [HabitController::class, 'index']);         
    Route::get('/habits/{id}', [HabitController::class, 'show']);       
    Route::post('/habits', [HabitController::class, 'store']);       
    Route::put('/habits/{id}', [HabitController::class, 'update']);    
    Route::delete('/habits/{id}', [HabitController::class, 'destroy']); 
    Route::post('/habits/{id}/complete', [HabitController::class, 'complete']); 
    Route::post('/habits/{id}/progress', [HabitController::class, 'progressHabit']); 
    Route::post('/habits/{id}/uncomplete', [HabitController::class, 'uncomplete']);


    // SUBITEMS
    Route::post('/habits/{id}/subitems', [SubItemController::class, 'store']); 
    Route::put('/subitems/{id}/toggle', [SubItemController::class, 'toggle']); 
    Route::put('/subitems/{id}', [SubItemController::class, 'update']);        
    Route::delete('/subitems/{id}', [SubItemController::class, 'destroy']);    
});
