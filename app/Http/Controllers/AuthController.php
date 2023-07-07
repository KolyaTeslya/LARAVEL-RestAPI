<?php

use Illuminate\Support\Facades\Auth;

public function login(Request $request)
{
$credentials = $request->only('email', 'password');

if (Auth::attempt($credentials)) {
$user = Auth::user();
$token = $user->createToken('API Token')->accessToken;
return response()->json(['token' => $token]);
} else {
return response()->json(['error' => 'Invalid credentials'], 401);
}
}
