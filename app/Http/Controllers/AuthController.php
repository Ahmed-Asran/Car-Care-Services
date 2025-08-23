<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mockery\Expectation;

class AuthController extends Controller
{
    public function Register(Request $request){
        // If role is sent as empty string, normalize it to null
    if ($request->has('role') && $request->role === '') {
        $request->merge(['role' => null]);
    }
            $fields=$request->validate([// user details 
                'first_name' => 'required|string|max:255',
                'last_name'  => 'required|string|max:255',
                'email'      => 'required|string|email|unique:users',
                'phone'      => 'required|string|max:20|unique:users',
                'password'   => 'required|string|confirmed|min:6',
                'role' => 'nullable|in:customer,provider',
                
            // provider details, image not execluded 
            'street'    => 'nullable|string|max:255',
            'city'      => 'required_if:role,provider|string|max:100',
            'state'     => 'required_if:role,provider|string|max:100',
            'country'   => 'required_if:role,provider|string|max:100',
            'latitude'  => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            ]);
           
            // Default role to customer if not provided
            $role = $request->input('role', 'customer'); 
            
            DB::beginTransaction(); //begin the creation of all field in the entities
        try {
            // Default role to customer if it is empty
            if (empty($request['role'])) 
                {
                $request['role'] = 'customer';
                $role='customer';
                }
            $user =User::create([
            'first_name' => $fields['first_name'],
            'last_name'  => $fields['last_name'],
            'email'      => $fields['email'],
            'phone'      => $fields['phone'],
            'password'   => Hash::make($fields['password']),
            'salt'        => bin2hex(random_bytes(16)),
            'role'       => $role
            ]);
            if($role=="provider")
            {
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
            }
            $token = $user->createToken("{$role}_token")->plainTextToken;
            DB::commit();
            $response=['message' => "{$role} registered successfully",'user'=> $user,'token'   => $token,];
            if ($request->role === 'provider') {
                $response['provider'] = $provider;
                $response['location'] = $location;
            }
            return response()->json($response,201);
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
        ],200);
    }
    public function logout(Request $request){
        $request->user()->tokens()->delete();
       return response()->json([
            'message' => 'Logged out successfully',
        ]);
    } 

}
