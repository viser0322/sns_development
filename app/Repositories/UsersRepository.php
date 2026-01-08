<?php

namespace App\Repositories;

use App\Models\Users;

class UsersRepository extends AbstractRdbRepository
{
  public function getModelClass(): string
  {
    return Users::class;
  }

  /**
   * 登録
   */
  public function insert($name, $email)
  {
    $this->model->create([
      'name' => $name,
      'email' => $email
    ]);
  }

  /**
   * 指定メールアドレスのデータを返却
   */
  public function findByEmail($email, $deleted = false)
  {
    $del_flg = self::FLG_OFF;
    if ($deleted) {
      $del_flg = self::FLG_ON;
    }

    return $this->model->where('email', $email)->where('del_flg', $del_flg)->first();
  }
}
