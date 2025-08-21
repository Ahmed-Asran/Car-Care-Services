<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerCar;

class CustomerCarController extends Controller
{
    public function index()
    {
        return CustomerCar::all();
    }

    // إضافة سيارة جديدة
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|integer',
            'car_type_id' => 'required|integer',
            'car_license' => 'required|string',
        ]);

        $car = CustomerCar::create($request->all());
        return response()->json($car, 201);
    }

    // عرض سيارة واحدة
    public function show($id)
    {
        $car = CustomerCar::find($id);
        if (!$car) return response()->json(['message' => 'Car not found'], 404);
        return response()->json($car);
    }

    // تعديل سيارة
    public function update(Request $request, $id)
    {
        $car = CustomerCar::find($id);
        if (!$car) return response()->json(['message' => 'Car not found'], 404);

        $car->update($request->only('customer_id','car_type_id','car_license'));
        return response()->json($car);
    }

    // حذف سيارة
    public function destroy($id)
    {
        $car = CustomerCar::find($id);
        if (!$car) return response()->json(['message' => 'Car not found'], 404);

        $car->delete();
        return response()->json(null, 204);
    }
}
