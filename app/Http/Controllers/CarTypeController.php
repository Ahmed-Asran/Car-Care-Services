<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarType;

class CarTypeController extends Controller
{

    public function index()
    {
        return CarType::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'manufacturer' => 'required|string',
            'model' => 'required|string',
        ]);

        $carType = CarType::create($request->only('manufacturer', 'model'));

        return response()->json($carType, 201);
    }

    public function show(CarType $carType)
    {
        return response()->json($carType);
    }

    public function update(Request $request, CarType $carType)
    {
        $carType->update($request->only('manufacturer', 'model'));
        return response()->json($carType);
    }

    public function destroy(CarType $carType)
    {
        $carType->delete();
        return response()->json(null, 204);
    }
}
