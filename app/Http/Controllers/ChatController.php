<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function fetch()
    {
        $messages = ChatMessage::where('user_id', Auth::id())->orderBy('id')->get();

        ChatMessage::where('user_id', Auth::id())
            ->where('sender', 'admin')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json($messages);
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required_without:image|nullable|string|max:2000',
            'image' => 'required_without:message|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'user_id' => Auth::id(),
            'sender' => 'user',
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
}
