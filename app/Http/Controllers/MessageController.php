<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::with('user')->orderBy('created_at', 'asc')->get();
        return view('messages.index', compact('messages'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:255',
        ]);

        Message::create([
            'message' => $request->message,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('messages.index');
    }
}
