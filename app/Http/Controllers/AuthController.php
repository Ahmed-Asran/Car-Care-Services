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
    public function register( Request $request){
        $fields=$request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|string|email|unique:users',
            'phone'      => 'required|string|max:20|unique:users',
            'password'   => 'required|string|confirmed|min:6',
        ]);
        $user=User::create([
            'first_name' => $fields['first_name'],
            'last_name'  => $fields['last_name'],
            'email'      => $fields['email'],
            'phone'      => $fields['phone'],
            'password'   => Hash::make($fields['password']),
            'salt'        => bin2hex(random_bytes(16))
        ]);
        $token=$user->createToken($user->last_name);
        $response=Response(["user"=>$user,"token"=>$token],201);
        return Response()->json($response,201);
    }
    public function registerProvider(Request $request){
        // check the user is exist first 
        $user=$request->user();
        if(!$user)
        {
        return Response()->json(['error' => 'User not authenticated'],401);
        }
        //check if the user is already a provider
        if($user->role==='provider'){
            return Response()->json(['message' => 'User is already a provider'],400);
        }

        $fields=$request->validate([   
        // provider details, image  execluded 
        'street'    => 'nullable|string|max:255',
        'city'      => 'nullable|string|max:100',
        'state'     => 'nullable|string|max:100',
        'country'   => 'nullable|string|max:100',
        'latitude'  => 'required|numeric|between:-90,90',
        'longitude' => 'required|numeric|between:-180,180',
        'national_id_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);
           
            
        DB::beginTransaction(); //begin the creation of all field in the entities
        try {
            if ($user->role !== 'provider') {
            $user->role = 'provider';
            $user->save();
            }
            $image=null;
            if($request->hasFile('national_id_image'))
            {
            $file=$request->file('national_id_image');
            $path=$file->store('images', 'public');
            
            $image = \App\Models\Image::create([
            'name' => $file->getClientOriginalName(),
            'mime' => $file->getClientMimeType(),
            'path' => $path,

            ]);
            }
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
            'national_id_image_id'=>$image->id??null,
            ]);
            $token = $user->createToken('provider_token')->plainTextToken;
            DB::commit();
            $response=['message' => "registered successfully as provider",'user'=> $user,'token'   => $token];
            $response['provider'] = $provider;
            $response['location'] = $location;
            $response['image']=$image;
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
