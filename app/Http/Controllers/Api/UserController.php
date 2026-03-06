<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function updateProfile(Request $request){
        $user = $request->user();

        if($request->hasFile('avatar')){
            $path = $request->file('avatar')->store('avatars', 'public');

            if($user->avatar){
                Storage::disk('public')->delete($user->avatar);
            }

            $user->update(['avatar' => $path]);

            return response()->json([
                'message' => "Avatar updated successfully",
                'avatar_url' => asset('storage/'. $path)
            ]);
        }else{
            return response()->json([
                'message' => "The avatar file is required"
            ], 400);
        }
    }
}
