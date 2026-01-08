<?php

namespace App\Repositories;

use App\Models\Reactions;
use DateTime;

class ReactionsRepository extends AbstractRdbRepository
{
  public function getModelClass(): string
  {
    return Reactions::class;
  }

  /**
   * 登録
   */
  public function insert(
    $id,
    $skin,
    $post_id,
    $user_id
  ) {
    if (empty($skin)) {
      $skin = null;
    }

    return $this->model->create([
      'emoji_id' => $id,
      'emoji_skin' => $skin,
      'post_id' => $post_id,
      'user_id' => $user_id,
    ])->id;
  }

  // /**
  //  * 全データを返却
  //  */
  // public function findAll($public_only = false)
  // {
  //   date_default_timezone_set('Asia/Tokyo');
  //   $obj = $this->model
  //     ->select(
  //       'posts.id',
  //       'posts.content',
  //       'posts.user_id',
  //       'posts.type',
  //       'posts.to_posts',
  //       'posts.del_flg',
  //       'posts.deleted_at',
  //       'posts.created_at',
  //       'posts.updated_at',
  //     );
  //     // if ($public_only) {
  //     //   $obj = $obj->where('posts.is_public', 1)
  //     //     ->where('posts.display_start_at', '<=', date('Y-m-d H:i:s'))
  //     //     ->where(function ($obj) {
  //     //       $obj->whereNull('posts.display_end_at')
  //     //         ->orwhere('posts.display_end_at', '>', date('Y-m-d H:i:s'));
  //     //     });
  //     // }
  //     return $obj->where('posts.del_flg', self::FLG_OFF)
  //       ->orderByRaw('posts.id ASC');
  // }


  /**
   * 公開済み全データを返却
   */
  public function findAllAndReturnCollectionObj($public_only = false)
  {
    return $this->model
    ->select(
      'reactions.id',
      'reactions.emoji_id',
      'reactions.emoji_skin',
      'reactions.post_id',
      'reactions.user_id',
      'reactions.del_flg',
      'reactions.deleted_at',
      'reactions.created_at',
      'reactions.updated_at'
    )
    ->where('reactions.del_flg', self::FLG_OFF)
    ->orderByRaw('reactions.created_at DESC')
    ->get();
  }

  /**
   * 公開済み全データを返却
   */
  public function findAllCount($public_only = false)
  {
    return $this->model
    ->select(
      'reactions.emoji_id',
      'reactions.emoji_skin',
      'reactions.post_id',
      'posts.user_id',      
    )
    ->selectRaw('COUNT(post_id) as reaction_count')
    ->leftJoin('posts', 'posts.id', '=', 'reactions.post_id')
    ->where('reactions.del_flg', self::FLG_OFF)
    ->groupBy('emoji_id')
    ->groupBy('emoji_skin')
    ->groupBy('post_id')
    ->get();
  }

  /**
   * 指定カテゴリIDのデータを返却
   */
  public function findByIdWithEmoji(
    $emoji_id,
    $emoji_skin,
    $post_id,
    $user_id,
    $public_only = false)
  {
    date_default_timezone_set('Asia/Tokyo');
    return $this->model
      ->select(
        'reactions.id',
        'reactions.del_flg',
      )
      ->where('emoji_id', $emoji_id)
      ->where('emoji_skin', $emoji_skin)
      ->where('post_id', $post_id)
      ->where('user_id', $user_id)
      ->get();
  }

  /**
   * 指定ポストIDのデータを返却
   */
  public function findByPost($id_list, $public_only = false)
  {
    date_default_timezone_set('Asia/Tokyo');

    $obj = $this->model
      ->select(
        'reactions.id',
        'reactions.emoji_id',
        'reactions.emoji_skin',
        'reactions.post_id',
        'reactions.user_id',
        'reactions.del_flg',
        'reactions.deleted_at',
        'reactions.created_at',
        'reactions.updated_at',
        'users.name as user_name',
      )
      ->leftJoin('users', 'users.id', '=', 'reactions.user_id');

      if (!empty($id_list)) {
        $obj = $obj->whereIn('reactions.id', $id_list);
      }

      return $obj->where('reactions.del_flg', self::FLG_OFF)
      ->orderByRaw('reactions.created_at DESC')->get();
  }

  /**
   * 指定IDのデータを返却
   */
  public function findByIdWithId($id)
  {
    date_default_timezone_set('Asia/Tokyo');

    $obj = $this->model
      ->select(
        'reactions.id',
        'reactions.emoji_id',
        'reactions.emoji_skin',
        'reactions.post_id',
        'reactions.user_id',
        'reactions.del_flg',
        'reactions.deleted_at',
        'reactions.created_at',
        'reactions.updated_at',
        'users.name as user_name',
        'posts.user_id as to_user_id',
      )
      ->leftJoin('users', 'users.id', '=', 'reactions.user_id')
      ->leftJoin('posts', 'posts.id', '=', 'reactions.post_id')
      ->where('reactions.id', $id);

      return $obj->where('reactions.del_flg', self::FLG_OFF)->first();
  }
}
