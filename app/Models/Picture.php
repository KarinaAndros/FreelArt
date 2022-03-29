<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'genre_id',
        'user_id',
        'description',
        'price',
        'discount',
        'size',
        'writing_technique',
        'img'
    ];

    public function genre(){
        return $this->belongsTo(Genre::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
