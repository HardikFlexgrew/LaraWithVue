<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
// use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {
        return redirect()->back();
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        if($request->authenticate()){                 

            $request->session()->regenerate();
        
            return response()->json([
                'success' => true,
                'role' => Auth::user()->getRoleNames()->first(),
                'user' => Auth::user(),
                'message' => 'Logged in successfully'
            ]);
        } 
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json([
            "success" => true,
        ]);
    }

    public function user(){
        return Auth::user();
    }
}
