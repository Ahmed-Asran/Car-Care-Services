<?php

namespace App\Http\Controllers;
use App\Models\Setting ;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class SettingController extends Controller
{
        use AuthorizesRequests; // 👈 لازم تضيف ده

    function index(){
        return response()->json(Setting::all());
    }
    function show(Setting $setting){
        return response()->json($setting);
    }
    function store(Request $request){
        $this->authorize('create', Setting::class);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'about_description' => 'nullable|string',
            'price_per_km' => 'nullable|numeric',
            'terms_and_conditions' => 'nullable|string',
            'facebook_url' => 'nullable|string',
            'whatsapp_number' => 'nullable|string',
            'primary_phone_number' => 'nullable|string',
            'secondary_phone_number' => 'nullable|string',
            'logo_id' => 'nullable|integer',
            'about_image_id' => 'nullable|integer',
        ]);
        $setting = Setting::create($data);
        return response()->json($setting, 201);
}
    function update(Request $request, Setting $setting){
        $this->authorize('update', $setting);
        $setting->update($request->all());
        return response()->json($setting);
    }
    function destroy(Setting $setting){
        $this->authorize('delete', $setting);
        $setting->delete();
        return response()->json(null, 204);
    }


}
