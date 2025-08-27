<?php

namespace App\Http\Controllers;
use App\Models\ServiceForCarType;
use App\Models\CustomerCar;
use Illuminate\Support\Facades\Auth;
use App\Models\RequestService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
class RequestServiceController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', RequestService::class);
         return RequestService::with(['customerCar', 'service', 'provider'])->get();
    }

     public function estimateCost(Request $request)
    {
        $this->authorize('create', RequestService::class);
         $validated = $request->validate([
            'customer_car_id' => 'required|exists:customer_cars,id',
            'service_id' => 'required|exists:services,id',
            'location_latitude' => 'required|numeric',
            'location_longitude' => 'required|numeric',
            'provider_id' => 'nullable|exists:providers,id',
        ]);

       

        // Ensure the customer owns the car
        $customerCar = CustomerCar::findOrFail($validated['customer_car_id']);
        if ($customerCar->customer_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        //get the service price 
        $servicePrice = ServiceForCarType::where('car_type_id', $customerCar->car_type_id)
            ->where('service_id', $validated['service_id'])
            ->value('price');

        if (!$servicePrice) {
            return response()->json(['message' => 'Service not available for this car type'], 400);
        }

        // Calculate distance ( should implement actual distance calculation)
        $distance =5;

        $distancePrice = $distance * 10; // 10 per km
        $totalPrice = $servicePrice + $distancePrice;

        return response()->json([
            'service_price' => $servicePrice,
            'distance' => $distance,
            'distance_price' => $distancePrice,
            'total_price' => $totalPrice,
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $this->authorize('create', RequestService::class);
         $validated = $request->validate([
            'customer_car_id' => 'required|exists:customer_cars,id',
            'service_id' => 'required|exists:services,id',
            'location_latitude' => 'required|numeric',
            'location_longitude' => 'required|numeric',
            'provider_id' => 'nullable|exists:providers,id',
        ]);

       

        // Ensure the customer owns the car
        $customerCar = CustomerCar::findOrFail($validated['customer_car_id']);
        if ($customerCar->customer_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        //get the service price 
        $servicePrice = ServiceForCarType::where('car_type_id', $customerCar->car_type_id)
            ->where('service_id', $validated['service_id'])
            ->value('price');

        if (!$servicePrice) {
            return response()->json(['message' => 'Service not available for this car type'], 400);
        }

        // Calculate distance ( should implement actual distance calculation)
        $distance =5;

        $distancePrice = $distance * 10; // 10 per km
        $totalPrice = $servicePrice + $distancePrice;
        $serviceRequest = RequestService::create([
            'customer_car_id' => $validated['customer_car_id'],
            'provider_id' => $validated['provider_id'] ?? null,
            'service_id' => $validated['service_id'],
            'location_latitude' => $validated['location_latitude'],
            'location_longitude' => $validated['location_longitude'],
            'distance' => $distance,
            'service_price' => $servicePrice,
            'distance_price' => $distancePrice,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Service request created successfully.',
            'data' => $serviceRequest->load(['customerCar', 'service', 'provider'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
     public function show($id)
    {
        $serviceRequest = RequestService::with(['customerCar.customer', 'service', 'provider.user'])
            ->findOrFail($id);
        
        $this->authorize('view', $serviceRequest);

        return response()->json($serviceRequest);
    }
    /**
     * Update request status
     * Provider: can update assigned requests
     * Admin: can update all requests
     */
    public function updateStatus($id, Request $request)
    {
        $serviceRequest = RequestService::findOrFail($id);
        $this->authorize('update', $serviceRequest);

        $validated = $request->validate([
            'status' => 'required|in:initiated,accepted,in_progress,completed,canceled'
        ]);

        $serviceRequest->update(['status' => $validated['status']]);

        return response()->json([
            'message' => 'Status updated successfully',
            'data' => $serviceRequest
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RequestService $requestService)
    {
        //
    }
    public function accept($id)
    {
        $serviceRequest = Requestservice::findOrFail($id);
        $this->authorize('accept', $serviceRequest);

        if ($serviceRequest->provider_id !== null) {
            return response()->json(['message' => 'Request already assigned'], 400);
        }

        if ($serviceRequest->status !== 'pending') {
            return response()->json(['message' => 'Request cannot be accepted'], 400);
        }

        $serviceRequest->update([
            'provider_id' => Auth::user()->provider->id,
            'status' => 'accepted'
        ]);

        return response()->json([
            'message' => 'Request accepted successfully',
            'data' => $serviceRequest->load(['customerCar.customer', 'service'])
        ]);
    }
    /**
     * Get incoming nearby requests for providers
     * Provider only
     */
    public function incomingRequests()
    {
        $this->authorize('viewIncoming', RequestService::class);

        $provider = Auth::user()->provider;
        if (!$provider) {
            return response()->json(['message' => 'Provider profile not found'], 404);
        }

        // For simplicity, fetching all pending requests without location filtering

        $requests = RequestService::with(['customerCar.customer', 'service'])
            ->where('provider_id', null)
            ->where('status', 'pending')
            ->get()->values();

        return response()->json($requests);
    }
    public function completedRequests()
    {
        $this->authorize('viewAssigned', RequestService::class);

        $provider = Auth::user()->provider;
        if (!$provider) {
            return response()->json(['message' => 'Provider profile not found'], 404);
        }

        $requests = RequestService::with(['customerCar.customer', 'service'])
            ->where('provider_id', $provider->id)
            ->where('status', 'completed')
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json($requests);
    }
    public function filterRequests(Request $request)
{
    $this->authorize('viewAny', RequestService::class);

    $validated = $request->validate([
        'provider_id' => 'sometimes|exists:providers,id',
        'status' => 'sometimes|in:pending,initiated,accepted,in_progress,completed,canceled',
    ]);

    $query = RequestService::with(['customerCar.customer', 'service', 'provider.user']);

    // Apply filters only if they exist
    if (isset($validated['provider_id'])) {
        $query->where('provider_id', $validated['provider_id']);
    }

    if (isset($validated['status'])) {
        $query->where('status', $validated['status']);
    }

    $requests = $query->orderBy('updated_at', 'desc')->get();

    return response()->json($requests);
}

}
