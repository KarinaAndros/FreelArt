<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;



class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'patronymic',
        'login',
        'password',
        'email',
        'phone',
        'avatar',
        'link'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function applications(){
        return $this->belongsToMany(Application::class, 'favorite_applications');
    }

    public function customer_applications()
    {
        return $this->hasMany(Application::class);
    }

    public function pictures()
    {
        return $this->belongsToMany(Picture::class, 'favorite_pictures');
    }

    public function subscription(){
        return $this->hasMany(Subscription::class);
    }

    public function completed_applications(){
        return $this->hasMany(CompletedApplication::class);
    }

    public function accounts(){
        return $this->belongsToMany(Account::class, 'account_users')->withPivot('account_id');
    }

    public function responses(){
        return $this->hasMany(ApplicationUser::class);
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }

}
