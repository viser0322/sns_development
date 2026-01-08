<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reactions extends Model
{
    use HasFactory;
    
    protected $table = 'reactions';

    protected $fillable = [
      'id',
      'emoji_id',
      'emoji_skin',
      'post_id',
      'user_id',
      'del_flg',
      'deleted_at',
      'created_at',
      'updated_at',
    ];
}
