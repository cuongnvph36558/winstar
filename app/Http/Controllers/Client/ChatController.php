<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $messages = Message::latest()->take(50)->get()->reverse();
        return view('client.chat.index', compact('messages'));
    }

    public function send(Request $request)
    {
        $request->validate(['content' => 'required|string|max:1000']);

        $message = Message::create([
            'user_id' => Auth::id(),
            'content' => $request->content
        ]);

        if ($request->ajax()) {
            return response()->json(['message' => $message]);
        }

        return redirect()->back();
    }
}