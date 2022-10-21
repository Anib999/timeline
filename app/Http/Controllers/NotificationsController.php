<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications;
use Auth;
use Carbon\Carbon;

class NotificationsController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function setNotification(){

    }

    public function changeNotificationStatus(Request $request){
        $notificationID = $request->get('notificationID');
        $notificationStatus = $request->get('viewStatus');
        $userId = Auth::user()->id;
        $currentDate = Carbon::now('GMT+5:45');

        $updateStatus = Notifications::where('id',$notificationID)
                        ->update([
                            'viewStatus' => $notificationStatus,
                            'readBy' => $userId,
                            'readOn' => $currentDate
                        ]);
        
        if($updateStatus == 0){
            return response()->json(['updateStatus' => 0]);
        }else{
            return response()->json(['updateStatus' => 1]);
        }
    }

    public function viewAllNotification(){
        $userId = Auth::user()->id;

        $notifications = Notifications::where('user_id',$userId)
                        ->orderBy('viewStatus','asc')
                        ->get();
        
        return view('pages.viewAllNotifications',['notifications_v' => $notifications]);
    }


}
