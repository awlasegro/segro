<?php

namespace App\Providers;

use App\Http\Controllers\AdminChatController;
use App\Models\Funds;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Feeds the navbar "Messages" dropdown on every admin page with real
        // support-chat conversations, instead of AdminLTE's placeholder data.
        View::composer('admin.layout.master', function ($view) {
            $conversations = collect();

            if (Auth::check() && in_array(Auth::user()->user_type, [1, 2])) {
                $conversations = (new AdminChatController())->buildConversationsList();
            }

            $view->with('navChatConversations', $conversations->take(5));
            $view->with('navChatUnreadTotal', $conversations->sum('unread_count'));

            // Feeds the navbar "Notifications" bell with real pending
            // deposit/withdrawal requests, instead of AdminLTE's placeholder data.
            $pendingRequests = collect();

            if (Auth::check() && in_array(Auth::user()->user_type, [1, 2])) {
                $pendingRequests = Funds::with('user')
                    ->whereIn('type', ['deposit', 'withdrawal'])
                    ->where('status', 'pending')
                    ->orderBy('created_at', 'desc')
                    ->get();
            }

            $view->with('navNotifications', $pendingRequests->take(8));
            $view->with('navNotificationsTotal', $pendingRequests->count());
        });
    }
}
