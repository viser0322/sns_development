<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    use HasFactory;
    
    protected $table = 'posts';

    protected $fillable = [
      'id',
      'content',
      'user_id',
      'type',
      'to_posts',
      'del_flg',
      'deleted_at',
      'created_at',
      'updated_at',
    ];
}
