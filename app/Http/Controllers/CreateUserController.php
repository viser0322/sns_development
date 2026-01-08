<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Services\UserService;

class CreateUserController extends BaseController
{
  private $service;

  public function __construct(UserService $service)
  {
    $this->service = $service;
  }

  /**
   * 新規登録表示
   *
   * @return View
   */
  public function create()
  {
    // loginページからGoogle認証のデータを受け取る
    $data = session('data');
    $name = null;
    $email = null;

    if (!isset($data)) {
      return redirect('/login');
    }

    if (isset($data)) {
      $name = $data['name'];
      $email = $data['email'];  
    }

    $user = $this->service->regist($name, $email);

    return view('/regist', [
      'title' => '新規登録',
      'name' => $name,
      'email' => $email,
    ]);
  }

  /**
   * データ登録後ホーム画面にリダイレクト
   *
   * @param Request $request
   * @return Response
   */
  public function store(UserRequest $request)
  {
    $file_name = null;
    if (isset($request->icon)) {
      $file_name = $this->service->uploadImage($request->icon);
    }

    $success = $this->service->update(
      $request->name, 
      $request->email, 
      $file_name, 
      $request->detail, 
      $request->department, 
      $request->birthday, 
      $request->hire_date
    );

    $user = $this->service->findByEmail($request->email);

    if ($success) {
      return redirect('/home');
    } else {
      return redirect('/regist')->with('error_msg', '登録に失敗しました。もう一度やり直してください。');
    }
  }

}
