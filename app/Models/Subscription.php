<?php

namespace App\Models;

use App\Notifications\InvoicePaid;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Subscription extends Model
{
    use HasFactory, Notifiable;


    protected $fillable =[
        'user_id',
        'user_email',
        'status'
    ];



}
