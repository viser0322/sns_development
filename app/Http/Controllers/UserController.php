<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserEditRequest;
use App\Services\UserService;
use App\Services\NoticeService;

class UserController extends BaseController
{
  private $service;

  public function __construct(UserService $service, NoticeService $notice_service)
  {
    parent::__construct();

    $this->service = $service;
    $this->notice_service = $notice_service;
  }

  /**
   * 一覧表示
   *
   * @return View
   */
  public function index()
  {
    // $list = $this->service->getUserList();

    // return view('/user/list', [
    //   'title' => '管理者一覧',
    //   'user_list' => $list,
    // ]);
  }

  /**
   * 詳細表示
   *
   * @param int $id
   * @return View
   */
  public function show($id)
  {
    $noncheck_count = $this->notice_service->getNonCheckNoticeCountByUser(Auth::guard('user')->user()->id);

    $user = $this->service->getUserDetail($id);

    if (!$user) {
      return redirect('/login');
    }

    return view('/profile', [
      'title' => '詳細',
      'noncheck_count' => $noncheck_count,
      'user' => $user,
    ]);
  }

  /**
   * 編集表示
   *
   * @param int $id
   * @return View
   */
  public function edit($id)
  {
    $noncheck_count = $this->notice_service->getNonCheckNoticeCountByUser(Auth::guard('user')->user()->id);

    $user = $this->service->getUserDetail($id);

    if (!$user) {
      return redirect('/login');
    }

    return view('/edit', [
      'title' => '編集',
      'noncheck_count' => $noncheck_count,
      'user' => $user,
    ]);
  }

  /**
   * データ更新後詳細画面に戻る
   *
   * @param Request $request
   * @return Response
   */
  public function update(UserEditRequest $request)
  {
    $success = $this->service->update($request->id, $request->name, $request->email, $request->password);

    if ($success) {
      return redirect('/user/detail/'.$request->id);
    } else {
      return redirect('/user/detail/'.$request->id.'/edit')->with('error_msg', '更新に失敗しました。もう一度やり直してください。');
    }
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

    return redirect('/user/list');
  }
}
