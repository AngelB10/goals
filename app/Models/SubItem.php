<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubItem extends Model
{
    use HasFactory;

    protected $fillable = ['habit_id', 'title', 'done'];

    public function habit()
    {
        return $this->belongsTo(Habit::class);
    }
}
