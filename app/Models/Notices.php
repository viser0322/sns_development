<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notices extends Model
{
    use HasFactory;
    
    protected $table = 'notices';

    protected $fillable = [
      'id',
      'to_id',
      'from_id',
      'to_user_id',
      'from_user_id',
      'type',
      'del_flg',
      'deleted_at',
      'created_at',
      'updated_at',
    ];
}
