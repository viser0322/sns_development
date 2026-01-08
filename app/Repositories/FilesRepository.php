<?php

namespace App\Repositories;

use App\Models\Files;
use DateTime;

class FilesRepository extends AbstractRdbRepository
{
  public function getModelClass(): string
  {
    return Files::class;
  }

  /**
   * 登録
   */
  public function insert(
    $post_id,
    $file_name
  ) {
    $this->model->create([
      'post_id' => $post_id,
      'file_name' => $file_name,
    ]);
  }
}
