<?php

namespace App\Http\Controllers;

use App\Exports\SuppliersExport;
use App\Models\Supplier;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Http\Resources\SupplierResource;
use App\Imports\SupplierImport;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class SupplierController extends Controller
{

    // function to return the view of the proveedor module

    public function index()
    {
        Gate::authorize('viewAny', Supplier::class);
        return Inertia::render('panel/supplier/indexSupplier');
    }

    public function listarProveedor(Request $request)
    {
        // authorization so you can access the method

        Gate::authorize('viewAny', Supplier::class);

        try {
            $name = $request->get('name');
            $suppliers = Supplier::when($name, function ($query, $name) {
                return $query->whereLike('name', "%$name%");
            })->orderBy('id','asc')->paginate(12);
            return response()->json([
                'suppliers'=> SupplierResource::collection($suppliers),
                'pagination' => [
                    'total' => $suppliers->total(),
                    'current_page' => $suppliers->currentPage(),
                    'per_page' => $suppliers->perPage(),
                    'last_page' => $suppliers->lastPage(),
                    'from' => $suppliers->firstItem(),
                    'to' => $suppliers->lastItem(),
                ],
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error al cargar los datos del proveedor',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function create()
    {
        return Inertia::render('panel/supplier/components/formSupplier');
    }
    public function store(StoreSupplierRequest $request)
    {
        Gate::authorize('create', Supplier::class);
        $validated = $request->validated();
        $validated = $request->safe()->except(['state']);
        $supplier = Supplier::create(Arr::except($validated, ['state']));
        return redirect()->route('panel.suppliers.index')->with('message', 'Proveedor creado correctamente');   
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        Gate::authorize('view', $supplier);
        return response()->json([
            'state' => true,
            'message' => 'Proveedor encontrado',
            'supplier' => new SupplierResource($supplier),
        ], 200);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        Gate::authorize('update', $supplier);
        $validated = $request->validated();
        $validated['state'] = ($validated['state'] ?? 'inactivo') === 'activo';
        $supplier->update($validated);
        return response()->json([
            'state' => true,
            'message' => 'Proveedor actualizado de manera correcta',
            'supplier' => new SupplierResource($supplier->refresh()),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        Gate::authorize('delete', $supplier);
        $supplier->delete();
        return response()->json([
            'state' => true,
            'message' => 'Proveedor eliminado de manera correcta',
        ]);
    }

    // EXPORTAR A EXCEL
    public function exportExcel()
    {
        return Excel::download(new SuppliersExport, 'proveedores.xlsx');
    }

    // IMPORTAR EXCEL
    public function importExcel(Request $request)
    {
        $request->validate([
            'archivo' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new SupplierImport, $request->file('archivo'));
        return response()->json([
            'message' => 'Importación de proveedores realizado correctamente',
        ]);
    }
}
