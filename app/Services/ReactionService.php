<?php

namespace App\Services;

use App\Repositories\ReactionsRepository;

class ReactionService
{
  private $reactions_repo;

  public function __construct(ReactionsRepository $reactions_repo)
  {
    $this->reactions_repo = $reactions_repo;
  }

  /**
   * 反応一覧を返却
   *
   * @return array
   */
  public function getReactionList()
  {
    return $this->reactions_repo->findAllAndReturnCollectionObj();
  }

  /**
   * 投稿情報詳細を返却
   *
   * @param int
   * @return array
   */
  public function getReactionCount()
  {
    return $this->reactions_repo->findAllCount();
  }

  /**
   * 投稿情報詳細を返却
   *
   * @param int
   * @return array
   */
  public function getReactionDetail($id)
  {
    return $this->reactions_repo->findByIdWithId($id);
  }

  /**
   * リアクション情報詳細を返却
   *
   * @param array
   * @return array
   */
  public function getReactionListByPost($id_list)
  {
    return $this->reactions_repo->findByPost($id_list);
  }

  /**
   * リアクション情報を登録
   *
   * @param string $emoji_id
   * @param int $emoji_skin
   * @param int $post_id
   * @param int $user_id
   * @return bool
   */
  public function regist(
    $emoji_id,
    $emoji_skin,
    $post_id,
    $user_id
  ) {
    try {
      $reaction_id = $this->reactions_repo->insert(
        $emoji_id,
        $emoji_skin,
        $post_id,
        $user_id
      );

    } catch(\Exception $e) {
      \Log::error('reaction regist: '.$e->getMessage());
      return false;
    }

    return $reaction_id;
  }

  /**
   * リアクション情報の有無を確認
   *
   * @param string $emoji_id
   * @param int $emoji_skin
   * @param int $post_id
   * @param int $user_id
   * @return bool
   */
  public function check(
    $emoji_id,
    $emoji_skin,
    $post_id,
    $user_id
  ) {
    return $this->reactions_repo->findByIdWithEmoji(
      $emoji_id,
      $emoji_skin,
      $post_id,
      $user_id
    );
  }

  // /**
  //  * コンテンツ情報を更新
  //  *
  //  * @param string $title
  //  * @param string $description
  //  * @param int $category_id
  //  * @param string $display_start_at
  //  * @param string $display_end_at
  //  * @param int $is_public
  //  * @return bool
  //  */
  // public function update(
  //   $id,
  //   $title,
  //   $description,
  //   $category_id,
  //   $display_start_at,
  //   $display_end_at,
  //   $is_public
  // ) {
  //   $data = [
  //     'title' => $title,
  //     'description' => $description,
  //     'category_id' => $category_id,
  //     'display_start_at' => $display_start_at,
  //     'display_end_at' => $display_end_at,
  //     'is_public' => $is_public
  //   ];

  //   try {
  //     $this->posts_repo->update($id, $data);

  //   } catch(\Exception $e) {
  //     \Log::error('post update: '.$e->getMessage());
  //     return false;
  //   }

  //   return true;
  // }

  /**
   * 投稿情報を削除
   *
   * @param int
   */
  public function delete($id)
  {
    $this->reactions_repo->deleteById($id);
  }

  /**
   * 投稿情報を削除
   *
   * @param int
   */
  public function restore($id)
  {
    $this->reactions_repo->restoreById($id);
  }
}
