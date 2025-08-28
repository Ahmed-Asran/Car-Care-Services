<?php

namespace App\Http\Controllers;

use App\Models\RequestService;
use App\Models\RequestStatusChange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class RequestStatusChangeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            // Admin can see all requests
            $requests = RequestService::with('statusChanges')->get();
        } else {
            // Customer can only see his own requests
            $requests = RequestService::whereHas('customerCar', function ($q) use ($user) {
                $q->where('customer_id', $user->id);
            })->with('statusChanges')->get();
        }

        return response()->json($requests);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = Auth::user();

        $requestService = RequestService::with('statusChanges')->findOrFail($id);

        // Admin can view any request
        if ($user->role === 'admin') {
            return response()->json([
                'request_id' => $requestService->id,
                'current_status' => $requestService->status,
                'history' => $requestService->statusChanges
            ]);
        }

        // Customer can only view their own request
        if ($requestService->customerCar->customer_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'request_id' => $requestService->id,
            'current_status' => $requestService->status,
            'history' => $requestService->statusChanges
        ]);
    }
}
