<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'account_id',
        'start_action',
        'end_action',
        'status'
    ];
}
