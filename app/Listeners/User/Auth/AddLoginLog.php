<?php

namespace App\Listeners\User\Auth;

use App\Models\UserLoginLog;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AddLoginLog
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $login_log = new UserLoginLog();
        $login_log->user_id = $event->user->id;
        $login_log->ip_address = request()->ip();
        $login_log->login_type = request('login_type');
        $login_log->os_version = request('os_version');
        $login_log->app_version = request('app_version');
        $login_log->login_at = Carbon::Now();
        $login_log->save();
    }
}