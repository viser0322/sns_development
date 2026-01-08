<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Repositories\AdminUsersRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;

class AdminLoginController extends Controller
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

  private $admin_users_repo;

  /**
   * Where to redirect users after login.
   *
   * @var string
   */
  protected $redirectTo = RouteServiceProvider::ADMIN_HOME;

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct(AdminUsersRepository $admin_users_repo)
  {
    $this->middleware('guest:admin')->except('logout');

    $this->admin_users_repo = $admin_users_repo;
  }

  public function index()
  {
    return view('admin.login');
  }

  public function redirectToGoogle()
  {
    return Socialite::driver('google')->redirect();
  }

  public function handleGoogleCallback(Request $request)
  {
    $gUser = Socialite::driver('google')->stateless()->user();
    $user = $this->admin_users_repo->findByEmail($gUser->getEmail());

    if ($user) {
      $this->guard('admin')->login($user, true);
    }

    return redirect('/admin/login')->with('error', 'アカウントが登録されていません');
  }

  public function logout()
  {
    $this->guard('admin')->logout();
    return redirect('/admin/login');
  }

  protected function guard()
  {
    return Auth::guard('admin');
  }
}
