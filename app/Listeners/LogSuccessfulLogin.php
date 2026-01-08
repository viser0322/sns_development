<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use App\Repositories\UserLogsRepository;
// use App\Repositories\AdminUserLogsRepository;

class LogSuccessfulLogin
{
  private $user_logs_repo;

  private $admin_user_logs_repo;

  /**
   * Create the event listener.
   *
   * @return void
   */
  public function __construct(UserLogsRepository $user_logs_repo)
  {
    $this->user_logs_repo = $user_logs_repo;
    // $this->admin_user_logs_repo = $admin_user_logs_repo;
  }

  /**
   * Handle the event.
   *
   * @param  object  $event
   * @return void
   */
  public function handle($event)
  {
    // ログインログ
    if ($event->guard == 'admin') {
      // $this->admin_user_logs_repo->insert(
      //   Auth::guard('admin')->user()->id,
      //   request()->ip(),
      //   request()->header('User-Agent')
      // );

    } else {
      $this->user_logs_repo->insert(
        Auth::guard('user')->user()->id,
        request()->ip(),
        request()->header('User-Agent')
      );
    }
  }
}
