<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('ui-contact.contact');
    }
    public function send(Request $request)
    {
        $request->validate([
            'name'    => 'required',
            'email'   => 'required|email',
            'message' => 'required',
        ]);

        $toEmail = $request->email; // Gửi đến email người dùng nhập

        $data = [
            'name' => $request->name,
            'content' => $request->message
        ];

        Mail::send('email.email-layout', $data, function($message) use ($toEmail) {
            $message->to($toEmail)
                    ->subject('Phản hồi liên hệ từ TechStore');
        });

        return back()->with('success', 'Tin nhắn đã được gửi tới email của bạn!');
    }

    

}
