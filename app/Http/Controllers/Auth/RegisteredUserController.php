<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;
use App\Models\Country;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        try{
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'address' => 'required|string',
                'country' => 'required',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'address' => $request->address,
            ]);
        
            event(new Registered($user));

            Auth::login($user);

            $user->assignRole('User');
            
            return response()->json([
                'success' => true,
                'role' => Auth::user()->getRoleNames()->first(),
                'user' => Auth::user(),
                'message' => 'Successfully! User Registered',
            ]);
        } catch(queryException $e){
            return response()->json([
                'success' => false,
                'message' => $e
            ]);
        }   
    }

    public function getCountry($country){
        if($country){
            $countries = Country::all();
            return response()->josn([
                'countries' => $countries
            ]);
        }
    }
}
