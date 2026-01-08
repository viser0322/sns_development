<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Services\PostService;
use App\Services\ReactionService;
use App\Services\NoticeService;

class HomeController extends BaseController
{
  private $service;

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct(PostService $service, ReactionService $reaction_service, NoticeService $notice_service)
  {
    parent::__construct();

    $this->service = $service;
    $this->reaction_service = $reaction_service;
    $this->notice_service = $notice_service;
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function index()
  {
    $noncheck_count = $this->notice_service->getNonCheckNoticeCountByUser(Auth::guard('user')->user()->id);

    $posts = [];
    $posts = $this->service->getPostList();
    $post_contents = $this->convertLink($posts);

    $reactions = [];
    $reactions = $this->reaction_service->getReactionList();

    $notices = [];
    $notices = $this->notice_service->getNoticeListByUser(Auth::guard('user')->user()->id);

    return view('home', [
      'title' => 'ホーム',
      'posts' => $posts,
      'post_contents' => $post_contents,
      'reactions' => $reactions,
      'notices' => $notices,
      'noncheck_count' => $noncheck_count,
      'user' => Auth::guard('user')->user(),
    ]);
  }

  public function post(Request $request)
  {
    if (isset($request->post) || isset($request->file)) {
      $file_name = null;
      if (isset($request->file)) {
        $file_name = $this->service->uploadImage($request->file);
      }

      $success = $this->service->regist(
        $request->post,
        Auth::guard('user')->user()->id,
        $request->type,
        $request->to_posts,
        $file_name,
      );

      if (isset($success)) {
        // 返信の場合は通知を追加
        if(isset($request->to_posts)) {
          $id = $success;
          $post = $this->service->getPostDetail($request->to_posts);
          $success = $this->notice_service->regist(
            $request->to_posts,
            $id,
            $post['user_id'],
            Auth::guard('user')->user()->id,
            1
          );  
        }

        return redirect('/home')->with('post', $request->post);;
      } else {
        return back()->with('error_msg', '投稿に失敗しました。もう一度やり直してください。');
      }

    } else {
      return back();
    }
  }

  public function notice()
  {
    $post_id_list = [];
    $reaction_id_list = [];

    $notices = [];
    $notices = $this->notice_service->getNoticeListByUser(Auth::guard('user')->user()->id);

    $this->notice_service->check(Auth::guard('user')->user()->id);
    $noncheck_count = $this->notice_service->getNonCheckNoticeCountByUser(Auth::guard('user')->user()->id);

    return view('notice', [
      'title' => 'ホーム',
      'notices' => json_decode($notices),
      'noncheck_count' => $noncheck_count,
      'user' => Auth::guard('user')->user(),
    ]);
  }
}
