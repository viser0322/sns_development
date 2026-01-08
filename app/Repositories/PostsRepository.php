<?php

namespace App\Repositories;

use App\Models\Posts;
use DateTime;

class PostsRepository extends AbstractRdbRepository
{
  public function getModelClass(): string
  {
    return Posts::class;
  }

  /**
   * 登録
   */
  public function insert(
    $content,
    $user_id,
    $type,
    $to_posts
  ) {
    if (empty($type)) {
      $type = 0;
    }
    if (empty($to_posts)) {
      $to_posts = null;
    }

    return $this->model->create([
      'content' => $content,
      'user_id' => $user_id,
      'type' => $type,
      'to_posts' => $to_posts,
    ])->id;
  }

  /**
   * 全データを返却
   */
  public function findAll($public_only = false)
  {
    date_default_timezone_set('Asia/Tokyo');
    $obj = $this->model
      ->select(
        'posts.id',
        'posts.content',
        'posts.user_id',
        'posts.type',
        'posts.to_posts',
        'posts.del_flg',
        'posts.deleted_at',
        'posts.created_at',
        'posts.updated_at',
      );
      // if ($public_only) {
      //   $obj = $obj->where('posts.is_public', 1)
      //     ->where('posts.display_start_at', '<=', date('Y-m-d H:i:s'))
      //     ->where(function ($obj) {
      //       $obj->whereNull('posts.display_end_at')
      //         ->orwhere('posts.display_end_at', '>', date('Y-m-d H:i:s'));
      //     });
      // }
      return $obj->where('posts.del_flg', self::FLG_OFF)
        ->orderByRaw('posts.id ASC');
  }


  /**
   * 公開済み全データを返却
   */
  public function findAllAndReturnCollectionObj($public_only = false)
  {
    return $this->model
    ->select(
      'posts.id',
      'posts.content',
      'posts.user_id',
      'posts.type',
      'posts.to_posts',
      'posts.del_flg',
      'posts.deleted_at',
      'posts.created_at',
      'posts.updated_at',
      'users.name as user_name',
      'files.file_name as file_name'
    )
    ->leftJoin('users', 'users.id', '=', 'posts.user_id')
    ->leftJoin('files', 'files.post_id', '=', 'posts.id')
    ->where('posts.del_flg', self::FLG_OFF)
    ->orderByRaw('posts.created_at DESC')
    ->get();
  }

  /**
   * 指定カテゴリIDのデータを返却
   */
  public function findAllWithCategory($id, $public_only = false)
  {
    date_default_timezone_set('Asia/Tokyo');
    $obj = $this->model
      ->select(
        'posts.id',
        'posts.content',
        'posts.user_id',
        'posts.type',
        'posts.to_posts',
        'posts.del_flg',
        'posts.deleted_at',
        'posts.created_at',
        'posts.updated_at',
        'users.name as user_name',
        'files.file_name as file_name'
      )
      ->leftJoin('users', 'users.id', '=', 'posts.user_id')
      ->leftJoin('files', 'files.post_id', '=', 'posts.id');

      if ($public_only) {
        $obj = $obj->where('posts.is_public', 1)
          ->where('posts.display_start_at', '<=', date('Y-m-d H:i:s'))
          ->where(function ($obj) {
            $obj->whereNull('posts.display_end_at')
              ->orwhere('posts.display_end_at', '>', date('Y-m-d H:i:s'));
          });
      }
      return $obj->where('posts.del_flg', self::FLG_OFF)
        ->orderByRaw('posts.created_at DESC');
  }

  /**
   * 指定ユーザーIDのデータを返却
   */
  public function findAllWithUser($id, $public_only = false)
  {
    date_default_timezone_set('Asia/Tokyo');

    $obj = $this->model
      ->select(
        'posts.id',
        'posts.content',
        'posts.user_id',
        'posts.type',
        'posts.to_posts',
        'posts.del_flg',
        'posts.deleted_at',
        'posts.created_at',
        'posts.updated_at',
        'users.name as user_name',
        'files.file_name as file_name'
      )
      ->leftJoin('users', 'users.id', '=', 'posts.user_id')
      ->leftJoin('files', 'files.post_id', '=', 'posts.id')
      ->where('posts.user_id', $id);

      if ($public_only) {
        $obj = $obj->where('posts.is_public', 1);
      }

      return $obj->where('posts.del_flg', self::FLG_OFF)->get();
  }

  /**
   * 指定IDのデータを返却
   */
  public function findByIdWithId($id, $public_only = false)
  {
    date_default_timezone_set('Asia/Tokyo');

    $obj = $this->model
      ->select(
        'posts.id',
        'posts.content',
        'posts.user_id',
        'posts.type',
        'posts.to_posts',
        'posts.del_flg',
        'posts.deleted_at',
        'posts.created_at',
        'posts.updated_at',
        'users.name as user_name',
        'files.file_name as file_name'
      )
      ->leftJoin('users', 'users.id', '=', 'posts.user_id')
      ->leftJoin('files', 'files.post_id', '=', 'posts.id')
      ->where('posts.id', $id);

      if ($public_only) {
        $obj = $obj->where('posts.is_public', 1)
          ->where('posts.display_start_at', '<=', date('Y-m-d H:i:s'))
          ->where(function ($obj) {
            $obj->whereNull('posts.display_end_at')
              ->orwhere('posts.display_end_at', '>', date('Y-m-d H:i:s'));
          });
      }

      return $obj->where('posts.del_flg', self::FLG_OFF)->first();
  }

  /**
   * 指定返信IDのデータを返却
   */
  public function findByIdWithToPostId($id, $public_only = false)
  {
    date_default_timezone_set('Asia/Tokyo');

    $obj = $this->model
      ->select(
        'posts.id',
        'posts.content',
        'posts.user_id',
        'posts.type',
        'posts.to_posts',
        'posts.del_flg',
        'posts.deleted_at',
        'posts.created_at',
        'posts.updated_at',
        'users.name as user_name',
        'files.file_name as file_name'
      )
      ->leftJoin('users', 'users.id', '=', 'posts.user_id')
      ->leftJoin('files', 'files.post_id', '=', 'posts.id')
      ->where('posts.to_posts', $id);

      if ($public_only) {
        $obj = $obj->where('posts.is_public', 1)
          ->where('posts.display_start_at', '<=', date('Y-m-d H:i:s'))
          ->where(function ($obj) {
            $obj->whereNull('posts.display_end_at')
              ->orwhere('posts.display_end_at', '>', date('Y-m-d H:i:s'));
          });
      }

      return $obj->where('posts.del_flg', self::FLG_OFF)->get();
  }

  /**
   * 指定返信IDのデータを返却
   */
  public function findByToPost($id_list, $public_only = false)
  {
    date_default_timezone_set('Asia/Tokyo');

    $obj = $this->model
      ->select(
        'posts.id',
        'posts.content',
        'posts.user_id',
        'posts.type',
        'posts.to_posts',
        'posts.del_flg',
        'posts.deleted_at',
        'posts.created_at',
        'posts.updated_at',
        'users.name as user_name',
        'files.file_name as file_name'
      )
      ->leftJoin('users', 'users.id', '=', 'posts.user_id')
      ->leftJoin('files', 'files.post_id', '=', 'posts.id');

      if (!is_null($id_list)) {
        $count = 0;
        foreach ($id_list as $id) {
          if ($count == 0) {
            $obj = $obj->where('posts.to_posts', $id);
          } else {
            $obj = $obj->orwhere('posts.to_posts', $id);
          }
          $count++;
        }
      }

      if ($public_only) {
        $obj = $obj->where('posts.is_public', 1)
          ->where('posts.display_start_at', '<=', date('Y-m-d H:i:s'))
          ->where(function ($obj) {
            $obj->whereNull('posts.display_end_at')
              ->orwhere('posts.display_end_at', '>', date('Y-m-d H:i:s'));
          });
      }

      return $obj->where('posts.del_flg', self::FLG_OFF)
      ->orderByRaw('posts.created_at DESC')->get();
  }

}
