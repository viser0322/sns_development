<?php

namespace App\Repositories;

use App\Models\Notices;
use DateTime;
use Carbon\Carbon;

class NoticesRepository extends AbstractRdbRepository
{
  public function getModelClass(): string
  {
    return Notices::class;
  }

  /**
   * 登録
   */
  public function insert(
    $to_id,
    $from_id,
    $to_user_id,
    $from_user_id,
    $type
  ) {

    return $this->model->create([
      'to_id' => $to_id,
      'from_id' => $from_id,
      'to_user_id' => $to_user_id,
      'from_user_id' => $from_user_id,
      'type' => $type,
    ]);
  }

  /**
   * 指定ユーザーIDのデータを返却
   */
  public function findAllWithUser($id)
  {
    date_default_timezone_set('Asia/Tokyo');

    $obj = $this->model
      ->select(
        'notices.id',
        'notices.to_id',
        'notices.from_id',
        'notices.to_user_id',
        'notices.from_user_id',
        'notices.type',
        'notices.del_flg',
        'notices.deleted_at',
        'notices.created_at',
        'notices.updated_at',
        'users.name as user_name',
        'posts.content as content',
        'files.file_name as file_name',
        'reactions.emoji_id as emoji_id',
        'reactions.emoji_skin as emoji_skin',
      )
      ->leftJoin('posts', 'posts.id', '=', 'notices.from_id')
      ->leftJoin('reactions', 'reactions.id', '=', 'notices.from_id')
      ->leftJoin('users', 'users.id', '=', 'notices.from_user_id')
      ->leftJoin('files', 'files.post_id', '=', 'posts.id')

      ->where('notices.to_user_id', $id);

      $obj->where(function($obj) {
        $obj->where('posts.del_flg', self::FLG_OFF)
          ->orwhere('reactions.del_flg', self::FLG_OFF);
      });

      return $obj->orderByRaw('notices.created_at DESC')->get();
  }

  /**
   * 確認していない指定ユーザーIDのデータ数を返却
   */
  public function findNonCheckCountWithUser($id)
  {
    date_default_timezone_set('Asia/Tokyo');

    return $this->model
      ->where('notices.to_user_id', $id)
      ->where('notices.del_flg', self::FLG_OFF)->count();
    }

  /**
   * 指定ユーザーIDのデータを削除（既読）
   */
  public function checkWithUser($id)
  {
    $now = new Carbon('now');
    $this->model->where('to_user_id', $id)->where('created_at', '<', $now)->update([
      'del_flg' => self::FLG_ON,
      'deleted_at' => $now,
    ]);
  }
}
