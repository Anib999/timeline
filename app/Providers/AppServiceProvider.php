<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\AdminToEmployeeRequestForCheckInOut;
use Auth;
use App\Notifications;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider {

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        Schema::defaultStringLength(191);

        view()->composer('*', function($view) {
            if (auth()->check()) {
                /*$unreadNotifications = AdminToEmployeeRequestForCheckInOut::where('user_id', Auth::user()->id)
                        ->whereIn('status', array(1, 2))
                        ->get();*/

                Notifications::where('user_id', Auth::user()->id)
                                ->where('deliveredOn',null)
                                ->where('isAdminMessage',0)
                                ->update([
                                    'deliveredOn' => Carbon::now('GMT+5:45')
                                ]);

                $notifications = Notifications::where('user_id', Auth::user()->id)
                                ->where('isAdminMessage',0)
                                ->where('viewStatus',0)
                                ->where('deliveredOn','<>',null)
                                ->where('status','<>',null)
                                ->get();
//dd($notification); exit;
                $view->with([/*'unreadNotifications'=> $unreadNotifications,*/'notifications' => $notifications]);
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }

}
