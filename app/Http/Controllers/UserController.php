<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    public function show(User $user)
    {
        $this->authorize('view', $user);
        return response()->json($user->load('provider.location', 'provider.nationalIdImage'));
    }

    public function update(Request $request, User $user)
    {
        
        $this->authorize('update', $user);

        // Basic user validation rules
        $rules = [
            'first_name' => 'sometimes|string|max:255',
            'last_name'  => 'sometimes|string|max:255',
            'email'      => 'sometimes|email|unique:users,email,' . $user->id,
            'phone'      => 'sometimes|string|max:20|unique:users,phone,' . $user->id,
        ];

        // If provider, merge provider-specific rules
        if ($user->role === "provider") {
            $rules = array_merge($rules, [
                'street'    => 'sometimes|nullable|string|max:255',
                'city'      => 'sometimes|nullable|string|max:100',
                'state'     => 'sometimes|nullable|string|max:100',
                'country'   => 'sometimes|nullable|string|max:100',
                'latitude'  => 'sometimes|nullable|numeric|between:-90,90',
                'longitude' => 'sometimes|nullable|numeric|between:-180,180',
                'national_id_image' => 'sometimes|nullable|image|mimes:jpg,jpeg,png|max:2048'
            ]);
        }

        $validated = $request->validate($rules);

        // Update user base fields
        $user->update($validated);

        // Update provider-related data
        if ($user->role === "provider" && $user->provider) {
            $provider = $user->provider;

            // Update location if exists
            if ($provider->location) {
                $provider->location->update([
                    'street'    => $validated['street'] ?? $provider->location->street,
                    'city'      => $validated['city'] ?? $provider->location->city,
                    'state'     => $validated['state'] ?? $provider->location->state,
                    'country'   => $validated['country'] ?? $provider->location->country,
                    'latitude'  => $validated['latitude'] ?? $provider->location->latitude,
                    'longitude' => $validated['longitude'] ?? $provider->location->longitude,
                ]);
            }

            // Update image if uploaded =====not tested===== 
        if ($request->hasFile('national_id_image')) {
            $file = $request->file('national_id_image');
            $path = $file->store('images', 'public');

            // Ensure provider exists
            if ($user->provider) {
                $provider = $user->provider;

                // Delete old image if exists
                if ($provider->nationalIdImage && Storage::disk('public')->exists($provider->nationalIdImage->path)) {
                    Storage::disk('public')->delete($provider->nationalIdImage->path);
                }

        // Update or create image
                if ($provider->nationalIdImage) {
                    $provider->nationalIdImage->update([
                        'name' => $file->getClientOriginalName(),
                        'mime' => $file->getClientMimeType(),
                        'path' => $path,
                    ]);
                } else {
                    $image=\App\Models\Image::create([
                        'name' => $file->getClientOriginalName(),
                        'mime' => $file->getClientMimeType(),
                        'path' => $path,
                    ]);
                    $provider->update([
                    'national_id_image_id' => $image->id,
                ]);
                }
            }
        }
    }

        return response()->json([
            'message' => 'Profile updated successfully',
            'user'    => $user->load('provider.location', 'provider.nationalIdImage'),
        ]);
    }
    public function forgetPass(Request $request){
          $request->validate(['email' => 'required|email']);

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json(['message' => 'If your email exists, you will receive reset instructions.']);
            }
            $token = Str::random(64);
           DB::table('password_reset_tokens')->updateOrInsert(['email'=>$request->email],['token'=>
           Hash::make($token),'created_at' => Carbon::now()]);
           //try to send email 
           Mail::raw("Your reset token is: $token", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Reset Your Password');
    });
           return response()->json([
            'message' => 'Password reset token generated',
            'token'   => $token
        ]);

    }
    public function reset(Request $request){
        
            $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);
        //check the email is exist 
        $reset = DB::table('password_reset_tokens')
        ->where('email', $request->email)
        ->first();
        if(!$reset){
                return response()->json(['message' => 'Invalid token or email'], 400);
        }
        //check the token 
        if (!Hash::check($request->token, $reset->token)) {
        return response()->json(['message' => 'Invalid token'], 400);
        }
         // Check if token expired (1 hour)
        if (Carbon::parse($reset->created_at)->addHour()->isPast()) {
            return response()->json(['message' => 'Token expired'], 400);
        }
        //chreck the user 
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
         $user->update([
            'password' => Hash::make($request->password)
        ]);
        //delete the tocken
         DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json(['message' => 'Password reset successfully']);
    }
}
