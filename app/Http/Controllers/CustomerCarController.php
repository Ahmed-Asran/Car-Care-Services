<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerCar;
use App\Models\CarType;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
class CustomerCarController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
       // authorize using policy
    $this->authorize('viewAny', CustomerCar::class);

    $user = auth()->user();

    // fetch only this user's cars with their car type
    $cars = CustomerCar::with('carType')
        ->where('customer_id', $user->id)
        ->get();

    return response()->json($cars);
    }

    // add new car 
    public function store(Request $request)
    {
     $this->authorize('create', CustomerCar::class);
            $validated = $request->validate([
            'manufacturer' => 'required|string|max:255',
            'model'        => 'required|string|max:255',
            'car_license'  => 'required|string|unique:customer_cars,car_license',
        ]);

        // Find or create CarType
        $carType = CarType::firstOrCreate([
            'manufacturer' => $validated['manufacturer'],
            'model'        => $validated['model'],
        ]);

        // Create customer car
        $car = CustomerCar::create([
            'customer_id' => auth()->id(),
            'car_type_id' => $carType->id,
            'car_license' => $validated['car_license'],
        ]);

        return response()->json($car->load('carType'), 201);
    }

    // get a specific car
    public function show($id)
    {

        // Find car with carType
        $car = CustomerCar::with('carType')->find($id);

        if (!$car) {
            return response()->json(['message' => 'Car not found'], 404);
        }
        // authorize before returning
        $this->authorize('view',$car );
        return response()->json($car);
    }

    // update specific car
    public function update(Request $request, $id)
    {
            $fields = $request->validate([
        'manufacturer' => 'sometimes|string|max:255',
        'model'        => 'sometimes|string|max:255',
        'car_license'  => 'sometimes|string|unique:customer_cars,car_license,' . $id, 
    ]);

    $car = CustomerCar::find($id);

    if (!$car) 
        {
        return response()->json(['message' => 'Car not found'], 404);
        }

    $this->authorize('update', $car);

    // 🔹 If car_type fields are provided
    if ($request->has('manufacturer') || $request->has('model')) {
        $carType = CarType::where('manufacturer', $request->manufacturer ?? $car->carType->manufacturer)
                          ->where('model', $request->model ?? $car->carType->model)
                          ->first();

        // If the car type does not exist, create it
        if (!$carType) {
            $carType = CarType::create([
                'manufacturer' => $request->manufacturer ?? $car->carType->manufacturer,
                'model'        => $request->model ?? $car->carType->model,
            ]);
        }

        // Update the relation
        $car->car_type_id = $carType->id;
    }

    // 🔹 Update license if provided
    if ($request->has('car_license')) {
        $car->car_license = $request->car_license;
    }

    $car->save();

    return response()->json($car->load('carType'));
    }

    //delete a specific car 
    public function destroy($id)
    {
        $car = CustomerCar::find($id);
        if (!$car) return response()->json(['message' => 'Car not found'], 404);
        $this->authorize('delete',$car);
        $car->delete();
        return response()->json(null, 204);
    }
}
