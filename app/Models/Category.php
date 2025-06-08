<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'color', 'user_id'];

    public function habits()
    {
        return $this->hasMany(Habit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
