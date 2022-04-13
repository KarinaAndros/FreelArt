<?php

namespace App\Console;

use App\Models\Account;
use App\Models\AccountUser;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $accounts = Account::query()->where('title', '=','PRO аккаунт');
            $account_users = AccountUser::query()->where('account_id', '=', $accounts->id)->where('deleted_at', '!=', '')->get();
            $now = Carbon::now();
            if ($account_users->end_action == $now){
                $account_users->delete();
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
