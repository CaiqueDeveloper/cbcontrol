<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use MBarlow\Megaphone\HasMegaphone;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasMegaphone;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

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
        'birthday' => 'date',
        'password' => 'hashed',
    ];

    public function image(): MorphMany
    {
        
        return $this->morphMany(Image::class, 'images');
    }
    public function address(): MorphMany
    {
        
        return $this->morphMany(Address::class, 'address');
    }
    public function company()
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }
    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_users');
    }
    public function groups(): BelongsToMany
    {
       return $this->belongsToMany(Group::class, 'group_users');
    }
}
