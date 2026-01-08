<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    use HasFactory;
    
    protected $table = 'Files';

    protected $fillable = [
      'id',
      'post_id',
      'file_name',
      'del_flg',
      'deleted_at',
      'created_at',
      'updated_at',
    ];
}
