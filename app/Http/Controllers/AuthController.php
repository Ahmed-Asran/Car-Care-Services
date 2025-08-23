<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function customerRegister(Request $request){
         $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|string|email|unique:users',
            'phone'      => 'required|string|max:20|unique:users',
            'password'   => 'required|string|min:6|confirmed',
        ]);
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'phone'      => $request->phone,
            'password'   => Hash::make($request->password),
            'salt' => $request->salt// this is must be here and in fillable or drop this coulmn 
        ]);

        $token=$user->createToken($user->first_name);
        return response()->json([
            'message' => 'User registered successfully',
            'user'    => $user,
            'token'   => $token,
        ]);
        
    }
    public function providerRegister(Request $request){
        $fields=$request->validate([// user details 
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|string|email|unique:users',
            'phone'      => 'required|string|max:20|unique:users',
            'password'   => 'required|string|min:6',
            
        ],[
            // provider details, image not execluded 
        'street'    => 'nullable|string|max:255',
        'city'      => 'required|string|max:100',
        'state'     => 'nullable|string|max:100',
        'country'   => 'required|string|max:100',
        'latitude'  => 'nullable|numeric|between:-90,90',
        'longitude' => 'nullable|numeric|between:-180,180',
        ]);
        DB::beginTransaction();

    try {
        $user =User::create([
        'first_name' => $fields['first_name'],
        'last_name'  => $fields['last_name'],
        'email'      => $fields['email'],
        'phone'      => $fields['phone'],
        'password'   => Hash::make($fields['password']),
        'salt'        => $request->salt,
        'role'       => 'provider',
        ]);
        $location =\App\Models\Location::create([
        'street'    => $fields['street'] ?? null,
        'city'      => $fields['city'] ?? null,
        'state'     => $fields['state'] ?? null,
        'country'   => $fields['country']??null,
        'latitude'  => $fields['latitude'] ?? null,
        'longitude' => $fields['longitude'] ?? null,
        ]);
        $provider = \App\Models\Provider::create([
        'user_id' => $user->id,
        'verification_status' => 'pending',
        'location_id' => $location->id,
    ]);

     $token = $user->createToken('provider_token')->plainTextToken;
     DB::commit();
       return response()->json([
        'message'  => 'Provider registered successfully',
        'user'     => $user,
        'provider' => $provider,
        'location' => $location,
        'token'    => $token,
       ]);
    }catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => $e->getMessage()], 500);
    }
    
     
    }
     public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The credentials are incorrect.'],
            ]);
        }

        // create token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user'    => $user,
            'token'   => $token
        ]);
    }
    public function logout(Request $request){
        $request->user()->tokens()->delete();
       return response()->json([
            'message' => 'Logged out successfully',
        ]);
    } 

}
