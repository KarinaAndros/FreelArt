<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'price',
        'discount',
        'application_category_id'
    ];

    public function application_category(){
        return $this->belongsTo(ApplicationCategory::class);
    }
}
