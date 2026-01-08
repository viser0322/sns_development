<?php

namespace App\Services;

use App\Repositories\UsersRepository;
use App\Repositories\UserLogsRepository;

class UserService
{
  private const FLG_ON = 1;

  private const FLG_OFF = 0;

  private $users_repo;

  private $user_logs_repo;

  public function __construct(UsersRepository $users_repo, UserLogsRepository $user_logs_repo)
  {
    $this->users_repo = $users_repo;
    $this->user_logs_repo = $user_logs_repo;
  }

  /**
   * 管理者一覧を返却
   *
   * @return array
   */
  public function getUserList()
  {
    return $this->users_repo->findAll();
  }

  /**
   * 管理者詳細を返却
   *
   * @param int $id
   * @return array
   */
  public function getUserDetail($id)
  {
    return $this->users_repo->findById($id);
  }

  /**
   * 管理者ログ一覧を返却
   *
   * @param int $id
   * @return array
   */
  public function getUserLogs($id)
  {
    return $this->user_logs_repo->findByUserId($id);
  }

  /**
   * 管理者情報を登録
   *
   * @param string $name
   * @param string $email
   * @return bool
   */
  public function regist($name, $email)
  {
    $deleted_user = $this->users_repo->findByEmail($email, true);

    try {
      // 過去削除済みの場合は復元する
      if (isset($deleted_user)) {
        $data = [
          'name' => $name,
          'email' => $email,
          'del_flg' => self::FLG_OFF,
          'deleted_at' => null,
        ];
        $this->users_repo->update($deleted_user['id'], $data);

      } else {
        $this->users_repo->insert($name, $email);
      }

    } catch(\Exception $e) {
      \Log::error('-user regist: '.$e->getMessage());
      return false;
    }

    return true;
  }

  /**
   * 管理者情報を更新
   *
   * @param int $id
   * @param string $name
   * @param string $email
   * @return bool
   */
  public function update(
    $name, 
    $email, 
    $icon, 
    $detail, 
    $department, 
    $birthday, 
    $hire_date
  ) {
    $user = $this->users_repo->findByEmail($email);

    $data = [
      'name' => $name,
      'email' => $email,
      'detail' => $detail,
      'department' => $department,
      'birthday' => $birthday,
      'hire_date' => $hire_date
    ];

    if (isset($icon)) {
      $data = array_merge($data, array('icon' => $icon,));
    }

    try {
      $this->users_repo->update($user['id'], $data);

    } catch (\Exception $e) {
      \Log::error('-user update: '.$e->getMessage());
      return false;
    }

    return true;
  }

  /**
   * 管理者情報を削除
   *
   * @param int
   */
  public function delete($id)
  {
    $this->users_repo->deleteById($id);
  }

  /**
   * 画像アップロード後ファイル名を返却
   *
   * @param UploadedFile $file
   * @return string
   */
  public function uploadImage($file)
  {
    $image_path = $file->store('public/content/');
    $file_name = basename($image_path);

    return $file_name;
  }

  /**
   * 指定メールアドレスのデータを返却
   */
  public function findByEmail($email, $deleted = false)
  {
    return $this->users_repo->findByEmail($email, $deleted = false);
  }
}
