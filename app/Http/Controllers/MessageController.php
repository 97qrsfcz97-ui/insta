<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function inbox()
    {
        $following_ids = Auth::user()->following->pluck('following_id');

        $following_users = User::whereIn('id', $following_ids)->get();
        
        return view('messages.inbox', compact('following_users'));
    }
    public function index($id)
    {
        $receiver = User::findOrFail($id);
        $user_id = Auth::id(); 

        $messages = Message::where(function($query) use ($user_id, $id) 
        {
            $query->where('sender_id', $user_id)->where('receiver_id', $id);
        })->orWhere(function($query) use ($user_id, $id)
        {
            $query->where('sender_id', $id)->where('receiver_id', $user_id);
        })->orderBy('created_at', 'asc')->get();

        return view('messages.index', compact('messages', 'receiver'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'body' => 'nullable|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1048',
        ]);

        if (!$request->body && !$request->hasFile('image')) {
            return redirect()->back();
        }

        $image_base64 = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_base64 = 'data:image/' . $image->extension() . ';base64,' . base64_encode(file_get_contents($image));
        }

        Message::create([
            'sender_id' => Auth::id(), 
            'receiver_id' => $id,    
            'body' => $request->body, 
            'image' => $image_base64, 
        ]);

        return redirect()->back();
    }
    }
