<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    //
    public function login(Request $request){
        try{ 
            $user = User::where('email',$request->email)->first();

            if($user && Hash::check($request->password,$user->password)){
                Auth::login($user);

                $request->session()->regenerate();

                return response()->json([
                    'success' => true,
                    'messsage' => 'LoggedIn Successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'messsage' => 'Invalid Credentials'
                ]);
            }
        } catch(err){
            return repsonse()->josn([
                "success" => false,
                'message' => err
            ]);
        }
    }
}
