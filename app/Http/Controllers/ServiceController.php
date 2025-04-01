<?php
namespace App\Http\Controllers;

use App\Models\Service;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Http\Resources\ServiceResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class ServiceController extends Controller
{
    /**
     * Display a listing of the services.
     */
    public function index()
    {
        Gate::authorize('viewAny', Service::class);
        return Inertia::render('panel/service/indexService');
    }

    public function listarServices(Request $request)
    {
        Gate::authorize('viewAny', Service::class);
        try {
            $name = $request->get('name');
            $services = Service::when($name, function ($query, $name) {
                return $query->where('name', 'like', "%$name%");
            })->orderBy('id')->paginate(15);

            return response()->json([
                'services' => ServiceResource::collection($services),
                'pagination' => [
                    'total' => $services->total(),
                    'current_page' => $services->currentPage(),
                    'per_page' => $services->perPage(),
                    'last_page' => $services->lastPage(),
                    'from' => $services->firstItem(),
                    'to' => $services->lastItem()
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error al listar los servicios',
                'error' => $th->getMessage()
            ], 500);
        }
    }


    public function create()
    {
        Gate::authorize('create', Service::class);
        return Inertia::render('panel/service/components/formService');
    }

    /**
     * Store a newly created service.
     */
    public function store(StoreServiceRequest $request)
    {
        Gate::authorize('create', Service::class);
        try {
            $validatedData = $request->validated();
            $service = Service::create($validatedData);
            return response()->json(new ServiceResource($service), 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error al crear el servicio',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified service.
     */
    public function show(Service $service)
    {
        Gate::authorize('view', $service);
        return response()->json(new ServiceResource($service));
    }

    /**
     * Update the specified service.
     */
    public function update(UpdateServiceRequest $request, Service $service)
    {
        Gate::authorize('update', $service);
        try {
            $validatedData = $request->validated();
            $service->update($validatedData);
            return response()->json(new ServiceResource($service));
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error al actualizar el servicio',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified service from storage.
     */
    public function destroy(Service $service)
    {
        Gate::authorize('delete', $service);
        try {
            $service->delete();
            return response()->json(null, 204);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error al eliminar el servicio',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}