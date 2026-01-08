<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLogs extends Model
{
  protected $table = 'user_logs';

  protected $fillable = [
    'id',
    'user_id',
    'ip',
    'user_agent',
    'del_flg',
    'deleted_at',
    'created_at',
    'updated_at',
  ];
}
