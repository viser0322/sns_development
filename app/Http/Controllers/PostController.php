<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Services\PostService;
use App\Services\NoticeService;

class PostController extends BaseController
{
  private $service;

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct(PostService $service, NoticeService $notice_service)
  {
    parent::__construct();

    $this->service = $service;
    $this->notice_service = $notice_service;
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function index($id)
  {
    $noncheck_count = $this->notice_service->getNonCheckNoticeCountByUser(Auth::guard('user')->user()->id);

    $post = $this->service->getPostDetail($id);
    $to_posts = $this->service->getPostListById($id);

    $post_content = $this->convertLinkObject($post);
    $to_post_contents = $this->convertLink($to_posts);

    return view('post', [
      'title' => '投稿',
      'post' => $post,
      'post_content' => $post_content,
      'to_posts' => $to_posts,
      'to_post_contents' => $to_post_contents,
      'noncheck_count' => $noncheck_count,
      'user' => Auth::guard('user')->user(),
    ]);
  }

  public function getDisplay($id)
  {
    $to_posts = $this->service->getPostListById($id);

    return $to_posts;
  }
    
  /**
   * データ削除後一覧画面にリダイレクト
   *
   * @param int $request
   * @return Response
   */
  public function destroy($id)
  {
    $this->service->delete($id);
    $this->service->delete($id);
  }
}
