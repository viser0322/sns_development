<?php

namespace App\Repositories;

use App\Models\UserLogs;

class UserLogsRepository extends AbstractRdbRepository
{
  public function getModelClass(): string
  {
    return UserLogs::class;
  }

  /**
   * 指定管理者ユーザーIDのデータを返却
   */
  public function findByUserId($id)
  {
    return $this->model
      ->select(
        'id',
        'ip',
        'user_agent',
        'created_at',
      )
      ->where('user_id', $id)
      ->where('del_flg', self::FLG_OFF)
      ->get();
  }

  /**
   * 登録
   */
  public function insert($user_id, $ip, $user_agent)
  {
    $this->model->create([
      'user_id' => $user_id,
      'ip' => $ip,
      'user_agent' => $user_agent,
    ]);
  }
}
