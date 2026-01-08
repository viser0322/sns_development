<?php

namespace App\Services;

use App\Repositories\NoticesRepository;

class NoticeService
{
  private $notices_repo;

  public function __construct(NoticesRepository $notices_repo)
  {
    $this->notices_repo = $notices_repo;
  }

  /**
   * 通知情報一覧を返却
   *
   * @return array
   */
  public function getNoticeListByUser($id)
  {
    return $this->notices_repo->findAllWithUser($id);
  }

  /**
   * 確認していない通知情報一覧を返却
   *
   * @return array
   */
  public function getNonCheckNoticeCountByUser($id)
  {
    return $this->notices_repo->findNonCheckCountWithUser($id);
  }

  /**
   * 通知情報を登録
   *
   * @param string $emoji_id
   * @param int $emoji_skin
   * @param int $post_id
   * @param int $user_id
   * @return bool
   */
  public function regist(
    $to_id,
    $from_id,
    $to_user_id,
    $from_user_id,
    $type
  ) {
    try {
      $this->notices_repo->insert(
        $to_id,
        $from_id,
        $to_user_id,
        $from_user_id,
        $type
      );

    } catch(\Exception $e) {
      \Log::error('reaction regist: '.$e->getMessage());
      return false;
    }

    return true;
  }

  /**
   * 通知情報を既読にする
   *
   * @param int $user_id
   * @return bool
   */
  public function check($to_user_id) {
    try {
      $this->notices_repo->checkWithUser($to_user_id);

    } catch(\Exception $e) {
      \Log::error('reaction regist: '.$e->getMessage());
      return false;
    }

    return true;
  }
}
