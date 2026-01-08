<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
  /*
  |--------------------------------------------------------------------------
  | Login Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles authenticating users for the application and
  | redirecting them to your home screen. The controller uses a trait
  | to conveniently provide its functionality to your applications.
  |
  */

  use AuthenticatesUsers;

  private $service;

  /**
   * Where to redirect users after login.
   *
   * @var string
   */
  protected $redirectTo = RouteServiceProvider::HOME;

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct(UserService $service)
  {
    $this->middleware('guest:user')->except('logout');

    $this->service = $service;
  }

  public function index()
  {
    return view('login')->with('title', 'ログイン');
  }

  public function redirectToGoogle()
  {
    return Socialite::driver('google')->redirect();
  }

  public function handleGoogleCallback(Request $request)
  {
    $gUser = Socialite::driver('google')->stateless()->user();
    $email = $gUser->getEmail();
    $name = $gUser->getName();
    $user = $this->service->findByEmail($email);

    $data = [
      'email' => $email,
      'name' => $name,
    ];

    if ($user) {
      $this->guard('user')->login($user, true);
      return redirect('/home');
    }
    else if (str_contains($email, '@apie.jp')) {
      return redirect('/regist')->with('data', $data);
    }

    return redirect('/login')->with('error', 'アカウントが登録されていません');
  }

  public function logout()
  {
    $this->guard('user')->logout();
    return redirect('/login');
  }

  protected function guard()
  {
    return Auth::guard('user');
  }
}
