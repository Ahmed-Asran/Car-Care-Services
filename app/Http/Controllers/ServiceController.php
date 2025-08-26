<?php

namespace App\Http\Controllers;

use App\Models\Service;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
class ServiceController extends Controller
{
     use AuthorizesRequests;
      public function index()
    {
        $this->authorize('viewAny', Service::class);

        $services = Service::all();
        return response()->json($services, 200);
    }
    public function store(Request $request){
        $fields=$request->validate(["name"=>'required|string|unique:services,name']);
          $this->authorize('create',Service::class);
        $service=Service::create($fields);
        return Response()->json($fields,201);
    }
    public function update(Request $request, Service $service)
    {
        $fields = $request->validate([
            "name" => 'required|string|unique:services,name,' . $service->id
        ]);

        $this->authorize('update', $service);

        $service->update($fields);

        return response()->json($service, 200);
    }
     /**
     * Show a specific service
     */
    public function show(Service $service)
    {
        $this->authorize('view', $service);

        return response()->json($service, 200);
    }
    public function destroy(Service $service)
    {
        $this->authorize('delete', $service);

        $service->delete();

        return response()->json(["message" => "Service deleted successfully"], 200);
    }
  
}
