<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Requests\RegisterUserRequest;
use App\Models\message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MessageController extends Controller
{

    public function register(RegisterUserRequest $request)
    {
        $validatedData = $request->validated();
        $verificationCode = mt_rand(100000, 999999);
        $user = User::create($validatedData);
        $token = $user->createToken('token')->plainTextToken;
        return response()->json([
            'message' => 'Registration Successful',
            'token' => $token,
            'user' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required'],
            'password' => ['required'],
        ]);

        $find = User::where('email', $request->email)->first();

        if ($find) {
            if (Hash::check($request->password, $find->password)) {
                $token = $find->createToken('token')->plainTextToken;
                return response()->json([
                    'User' => 'login successfuly',
                    'token' => $token,
                ]);
            } else {
                return response()->json([
                    'message' => 'Wrong password',
                ]);
            }
        } else {
            return response()->json([
                'message' => 'Wrong email',
            ]);
        }
    }



    public function index()
    {
        return message::all();
    }

    public function store(Request $request)
    {
        $message = message::create([
            'user_id' => $request->user()->id,
            'message' => $request->message,
        ]);
        broadcast(new MessageSent($message))->toOthers();
        return response()->json(['status' => 'Message Sent!']);
    }
}
