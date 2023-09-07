<?php

namespace App\Http\Controllers;




use Illuminate\Http\Request;
use App\Member;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\bcrypt;

use App\Providers\RouteServiceProvider;
class MembersController extends Controller
{
    // Create a new member
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:members',
            'password' => 'required|string|min:6',
            'phone' => 'required|string',
            'city' => 'required|string',
        ]);

        $member = new Member([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'phone' => $request->input('phone'),
            'city' => $request->input('city'),
        ]);

        $member->save();

        return response()->json(['message' => 'Member created successfully'], 201);
    }
   // Get all members
    public function index()
    {
        $members = Member::all();

        return response()->json(['members' => $members], 200);
    }

    // Get a specific member by ID
    public function show($id)
    {
        $member = Member::find($id);

        if (!$member) {
            return response()->json(['message' => 'Member not found'], 404);
        }

        return response()->json(['member' => $member], 200);
    }

    // Update a member by ID
    public function update(Request $request, $id)
    {
        $member = Member::find($id);

        if (!$member) {
            return response()->json(['message' => 'Member not found'], 404);
        }

        $request->validate([
            'name' => 'string|max:255',
            'email' => 'string|email|max:255|unique:members,email,' . $id,
            'password' => 'string|min:6',
            'phone' => 'string',
            'city' => 'string',
        ]);

        if ($request->has('name')) {
            $member->name = $request->input('name');
        }

        if ($request->has('email')) {
            $member->email = $request->input('email');
        }

        if ($request->has('password')) {
            $member->password = bcrypt($request->input('password'));
        }

        if ($request->has('phone')) {
            $member->phone = $request->input('phone');
        }

        if ($request->has('city')) {
            $member->city = $request->input('city');
        }

        $member->save();

        return response()->json(['message' => 'Member updated successfully'], 200);
    }

    // Delete a member by ID
    public function destroy($id)
    {
        $member = Member::find($id);

        if (!$member) {
            return response()->json(['message' => 'Member not found'], 404);
        }

        $member->delete();

        return response()->json(['message' => 'Member deleted successfully'], 200);
    }
}
