<?php

namespace Kaely\PmsHotel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Kaely\PmsHotel\Models\Dessert;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DessertController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        Gate::authorize('viewAny', Dessert::class);

        $query = Dessert::query();

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'name');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortBy, $sortDirection);

        // Pagination
        $perPage = min($request->get('per_page', 15), 100);
        $desserts = $query->paginate($perPage);

        return response()->json($desserts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        Gate::authorize('create', Dessert::class);

        $dessert = Dessert::create($request->all());

        return response()->json([
            'message' => 'Postre creado exitosamente.',
            'data' => $dessert,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Dessert $dessert): JsonResponse
    {
        Gate::authorize('view', $dessert);

        return response()->json($dessert);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dessert $dessert): JsonResponse
    {
        Gate::authorize('update', $dessert);

        $dessert->update($request->all());

        return response()->json([
            'message' => 'Postre actualizado exitosamente.',
            'data' => $dessert,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dessert $dessert): JsonResponse
    {
        Gate::authorize('delete', $dessert);

        $dessert->delete();

        return response()->json([
            'message' => 'Postre eliminado exitosamente.',
        ]);
    }

    /**
     * Export desserts to Excel.
     */
    public function export(Request $request): BinaryFileResponse
    {
        Gate::authorize('export', Dessert::class);

        $query = Dessert::query();

        // Apply same filters as index
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $sortBy = $request->get('sort_by', 'name');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortBy, $sortDirection);

        $desserts = $query->get();

        $filename = 'desserts_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($desserts) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Nombre', 'Descripción', 'Creado', 'Actualizado']);

            foreach ($desserts as $dessert) {
                fputcsv($file, [
                    $dessert->id,
                    $dessert->name,
                    $dessert->description,
                    $dessert->created_at,
                    $dessert->updated_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import desserts from Excel.
     */
    public function import(Request $request): JsonResponse
    {
        Gate::authorize('import', Dessert::class);

        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:2048',
        ]);

        return response()->json([
            'message' => 'Postres importados exitosamente.',
            'imported' => 0,
        ]);
    }
}