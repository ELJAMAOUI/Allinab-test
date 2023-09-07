<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Member;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\bcrypt;

use App\Providers\RouteServiceProvider;
class AuthController extends Controller
{
public function register(Request $request)
{
    // Validate the incoming request data
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:members',
        'password' => 'required|string|min:6',
        'phone' => 'required|string|max:20',
        'city' => 'required|string|max:255',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    // Create a new Member
    $member = new Member([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'phone' => $request->phone,
        'city' => $request->city,
    ]);

    $member->save();

    // Generate a Passport token for the Member
    $token = $member->createToken('MemberToken')->accessToken;

    return response()->json(['token' => $token], 201);
}

public function login(Request $request)
{
    // Validate the incoming request data
    $validator = Validator::make($request->all(), [
        'email' => 'required|string|email|max:255',
        'password' => 'required|string|min:6',

    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    // Attempt to authenticate the Member
    if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        // Log successful login
        \Log::info('Login successful for user: ' . Auth::user()->email);

        $member = Auth::user();

        // Generate a Passport token for the Member
        $token = $member->createToken('MemberToken')->accessToken;

        return response()->json(['token' => $token, 'member' => $member], 200);
    } else {
        // Log failed login
        \Log::warning('Login failed for member with email: ' . $request->email);

        return response()->json(['error' => 'Unauthorized'], 401);
    }
}

}
