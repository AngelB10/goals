<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Habit extends Model
{
     use HasFactory;

    protected $fillable = [
        'name', 'description', 'type', 'frequency','days_of_week',
        'start_date', 'end_date', 'time', 'category_id',
        'user_id', 'completed', 'progress', 'target'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subitems()
    {
        return $this->hasMany(SubItem::class);
    }
}
