<?php

namespace App\Http\Controllers;
use App\Models\ServiceForCarType;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ServiceForCarTypeController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prices = ServiceforCarType::with(['service', 'carType'])->get();
        return response()->json($prices);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'car_type_id' => 'required|exists:car_types,id',
            'price' => 'required|numeric|min:0',
        ]);
        $this->authorize('create',ServiceForCarType::class);
        $servicePrice = ServiceForCarType::create($validated);

        return response()->json($servicePrice, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $servicePrice = ServiceForCarType::findOrFail($id);
        $this->authorize('view',$servicePrice);
        $servicePrice=ServiceForCarType::with(['service', 'carType'])->findOrFail($id);
         return response()->json($servicePrice);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $servicePrice = ServiceForCarType::findOrFail($id);
         $this->authorize('update',$servicePrice);
        $validated = $request->validate([
            'price' => 'sometimes|numeric|min:0',
        ]);

         $servicePrice->update([
        'price' => $validated['price']
            ]);

        return response()->json($servicePrice);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
          $servicePrice = ServiceForCarType::findOrFail($id);
        $this->authorize('delete',$servicePrice);
        $servicePrice->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
