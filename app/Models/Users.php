<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Users extends Authenticatable
{
  protected $table = 'users';

  use Notifiable;

  protected $fillable = [
    'id',
    'user_id',
    'email',
    'name',
    'icon',
    'detail',
    'department',
    'birthday',
    'hire_date',
    'deleted_at',
    'created_at',
    'updated_at',
  ];

  protected $hidden = [
    'remember_token',
  ];
}
