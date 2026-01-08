<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Contracts\Auth\Guard;

class AdminUserComposer {
    /**
     * @var String
     */
    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Bind data to the view.
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('admin_user', $this->auth->user());
    }
}
