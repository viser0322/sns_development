<?php

namespace App\Services;

use App\Repositories\PostsRepository;
use App\Repositories\FilesRepository;

class PostService
{
  private $posts_repo;

  public function __construct(PostsRepository $posts_repo, FilesRepository $files_repo)
  {
    $this->posts_repo = $posts_repo;
    $this->files_repo = $files_repo;
  }

  /**
   * 投稿情報一覧を返却
   *
   * @return array
   */
  public function getPostList()
  {
    return $this->posts_repo->findAllAndReturnCollectionObj();
  }

  /**
   * 投稿情報一覧を返却
   *
   * @return array
   */
  public function getPostListByUser($id)
  {
    return $this->posts_repo->findAllWithUser($id);
  }

  /**
   * 投稿情報詳細を返却
   *
   * @param int
   * @return array
   */
  public function getPostDetail($id)
  {
    return $this->posts_repo->findByIdWithId($id);
  }

  /**
   * 投稿情報詳細を返却
   *
   * @param int
   * @return array
   */
  public function getPostListById($id)
  {
    return $this->posts_repo->findByIdWithToPostId($id);
  }

  /**
   * 返信情報詳細を返却
   *
   * @param array
   * @return array
   */
  public function getToPostList($id_list)
  {
    return $this->posts_repo->findByToPost($id_list);
  }

  /**
   * 投稿情報を登録
   *
   * @param string $content
   * @param int $user_id
   * @param int $type
   * @param int $to_posts
   * @return bool
   */
  public function regist(
    $content,
    $user_id,
    $type,
    $to_posts,
    $file_name
  ) {
    try {
      $post_id = $this->posts_repo->insert(
        $content,
        $user_id,
        $type,
        $to_posts
      );

      $this->files_repo->insert(
        $post_id,
        $file_name
      );

    } catch(\Exception $e) {
      \Log::error('post regist: '.$e->getMessage());
      return false;
    }

    return $post_id;
  }

  /**
   * 投稿情報を削除
   *
   * @param int
   */
  public function delete($id)
  {
    $this->posts_repo->deleteById($id);
    $this->files_repo->deleteById($id);
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
}
