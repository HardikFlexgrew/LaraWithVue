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
                'city' => 'required',
                'state' => 'required',
                'country_id' => 'required',
            ],[
               'address' => 'The address is required', 
               'city' => 'The city is required', 
               'state' => 'The state is required', 
               'country_id' => 'The country is required', 
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'country_id' => $request->country_id
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

    public function getCountry(){
        $countries = Country::select('id','country_name')->get();
        return response()->json([
            'success' => true,
            'countries' => $countries
        ]);
    }
}
