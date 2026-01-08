<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ReactionService;
use App\Services\NoticeService;
use Illuminate\Support\Facades\Auth;

class ReactionController extends BaseController
{
  private $service;

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct(ReactionService $service, NoticeService $notice_service)
  {
    parent::__construct();

    $this->service = $service;
    $this->notice_service = $notice_service;
  }

  public function regist(Request $request)
  {
    $data = [];

    if (isset($request)) {
      $check = $this->service->check(
        $request->emoji_id,
        $request->emoji_skin,
        $request->post_id,
        Auth::guard('user')->user()->id
      );
      $data['count'] = count($check);

      if (count($check) > 0) {
      // すでにリアクション情報があるかどうかを確認
        if ($check[0]['del_flg'] == 0){
        // リアクションしていれば取り消し
          $this->service->delete($check[0]['id']);
          $data['type'] = "delete";
          return $data;
        }
        else if ($check[0]['del_flg'] == 1){
        // リアクションが取り消されていれば復活
          $this->service->restore($check[0]['id']);
          $data['type'] = "restore";
          return $data;
        }
      } else {
        $success = $this->service->regist(
          $request->emoji_id,
          $request->emoji_skin,
          $request->post_id,
          Auth::guard('user')->user()->id
        );

        if (isset($success)) {
            // 通知を追加
            $id = $success;
            $reaction = $this->service->getReactionDetail($id);

            $success = $this->notice_service->regist(
              $request->post_id,
              $id,
              $reaction['to_user_id'],
              Auth::guard('user')->user()->id,
              2
            );

          //   return redirect('/home')->with('post', $request->post);;
          // } else {
          //   return back()->with('error_msg', '投稿に失敗しました。もう一度やり直してください。');
        }

        $data['type'] = "regist";
        return $data;
      }
    } else {
      $data['type'] = "failure";
      return $data;
    }
  }

  public function get()
  {
    $reactions = [];
    $reactions = $this->service->getReactionList();

    if ($reactions) {
      return $reactions;
    } else {
      return back()->with('error_msg', '投稿に失敗しました。もう一度やり直してください。');
    }
  }

  public function count()
  {
    $reaction_counts = [];
    $reaction_counts = $this->service->getReactionCount();

    if ($reaction_counts) {
      return $reaction_counts;
    } else {
      return back()->with('error_msg', '投稿に失敗しました。もう一度やり直してください。');
    }
  }
}
