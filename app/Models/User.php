<?php

namespace App\Models;


use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Attendence;
use App\Models\CaseModel;

class User extends Authenticatable
{
    use HasFactory, Notifiable;


    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

  
    protected $hidden = [
        'password',
        'remember_token',
    ];

   
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
   
public function assignedCases()
{
    return $this->belongsToMany(CaseModel::class, 'assignments', 'case_id');
}
public function assignments()
{
    return $this->hasMany(Assignment::class);
}

// User.php
public function cases()
{
    return $this->belongsToMany(CaseModel::class, 'assignments', 'user_id', 'case_id');
}

}
