<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminChatController extends Controller
{
    /**
     * Console home: full conversation list, most recent conversation auto-selected.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $conversations = $this->buildConversationsList();
        $selectedUser = $conversations->first();
        $messages = collect();
        $stats = null;

        if ($selectedUser) {
            $details = $this->conversationDetails($selectedUser);
            $messages = $details['messages'];
            $stats = $details['stats'];
        }

        return view('admin.chats', compact('conversations', 'selectedUser', 'messages', 'stats'));
    }

    /**
     * Console with one specific conversation pre-selected (bookmarkable URL).
     *
     * @return \Illuminate\Http\Response
     */
    public function show($userId)
    {
        $conversations = $this->buildConversationsList();
        $selectedUser = User::findOrFail($userId);
        $details = $this->conversationDetails($selectedUser);
        $messages = $details['messages'];
        $stats = $details['stats'];

        return view('admin.chats', compact('conversations', 'selectedUser', 'messages', 'stats'));
    }

    /**
     * AJAX: full thread + client stats for one user, used to switch the
     * console's center/right columns without a page reload.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function panel($userId)
    {
        $user = User::findOrFail($userId);
        $details = $this->conversationDetails($user);

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'initial' => strtoupper(substr($user->name, 0, 1)),
            ],
            'messages' => $details['messages'],
            'stats' => $details['stats'],
        ]);
    }

    /**
     * Polled by the open conversation for new messages.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetch($userId)
    {
        $messages = ChatMessage::where('user_id', $userId)->orderBy('id')->get();

        ChatMessage::where('user_id', $userId)
            ->where('sender', 'user')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json($messages);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(Request $request, $userId)
    {
        $request->validate([
            'message' => 'required_without:image|nullable|string|max:2000',
            'image' => 'required_without:message|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'user_id' => $userId,
            'admin_id' => Auth::id(),
            'sender' => 'admin',
            'message' => $request->message,
        ];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('ChatImages'), $imageName);
            $data['image'] = $imageName;
        }

        $message = ChatMessage::create($data);

        return response()->json($message);
    }

    /**
     * Every user with at least one message, latest activity first.
     *
     * @return \Illuminate\Support\Collection
     */
    private function buildConversationsList()
    {
        $userIds = ChatMessage::select('user_id')->distinct()->pluck('user_id');

        return User::whereIn('id', $userIds)->get()->map(function ($user) {
            $user->latest_message = ChatMessage::where('user_id', $user->id)->latest('id')->first();
            $user->unread_count = ChatMessage::where('user_id', $user->id)
                ->where('sender', 'user')
                ->whereNull('read_at')
                ->count();

            return $user;
        })->sortByDesc(function ($user) {
            return optional($user->latest_message)->created_at;
        })->values();
    }

    /**
     * Full thread + real account stats for one user, marking their unread
     * messages as read as a side effect (same as opening the conversation).
     *
     * @param  \App\Models\User  $user
     * @return array
     */
    private function conversationDetails(User $user)
    {
        $messages = ChatMessage::where('user_id', $user->id)->orderBy('id')->get();

        ChatMessage::where('user_id', $user->id)
            ->where('sender', 'user')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // Same balance formula used across the app: deposits + commission - withdrawals.
        $totalDeposits = $user->funds()->where('type', 'deposit')->whereIn('status', ['active', 'deactive'])->sum('amount');
        $totalWithdrawals = $user->funds()->where('type', 'withdrawal')->whereIn('status', ['active', 'deactive'])->sum('amount');
        $totalCommission = $user->funds()->where('type', 'commission')->whereIn('status', ['active', 'deactive'])->sum('amount');
        $totalFunds = $totalDeposits + $totalCommission - $totalWithdrawals;

        // Same "completed orders" query used on the user dashboard (ProfileController::dashboard).
        $completedOrdersCount = $user->orders()->where('type', 'Complete')->where('status', 'active')->count();

        return [
            'messages' => $messages,
            'stats' => [
                'total_funds' => $totalFunds,
                'completed_orders' => $completedOrdersCount,
                'membership' => optional($user->membershipLevel)->level_name,
                'credibility' => $user->credibility,
                'status' => $user->status,
                'location' => $user->last_location,
                'member_since' => optional($user->created_at)->format('M Y'),
                'email' => $user->email,
                'username' => $user->username,
            ],
        ];
    }
}
