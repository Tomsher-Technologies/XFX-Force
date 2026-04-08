<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class NotificationController extends Controller
{
    public function index() {
        $notifications = auth()->user()->notifications()->paginate(15);
        
        auth()->user()->unreadNotifications->markAsRead();
        
        return view('backend.notification.index', compact('notifications'));
        
    }

    public function customerNotifications() {
        $user = auth('frontend')->user();
        if (!$user) abort(403, 'Unauthorized');

        $notifications = $user->notifications()->paginate(15);
        $user->unreadNotifications->markAsRead();

        return view('frontend.notification.index', compact('notifications'));
    }
}
