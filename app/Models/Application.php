<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'genre_id',
        'user_id',
        'application_category_id',
        'description',
        'payment',
        'writing_technique',
        'deadline'
    ];

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function application_category()
    {
        return $this->belongsTo(ApplicationCategory::class);
    }

}
