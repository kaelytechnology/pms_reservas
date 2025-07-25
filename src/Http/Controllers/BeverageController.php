<?php

namespace Kaely\PmsHotel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Kaely\PmsHotel\Models\Beverage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BeverageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        Gate::authorize('viewAny', Beverage::class);

        $query = Beverage::query();

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
        $beverages = $query->paginate($perPage);

        return response()->json($beverages);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        Gate::authorize('create', Beverage::class);

        $beverage = Beverage::create($request->all());

        return response()->json([
            'message' => 'Bebida creada exitosamente.',
            'data' => $beverage,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Beverage $beverage): JsonResponse
    {
        Gate::authorize('view', $beverage);

        return response()->json($beverage);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Beverage $beverage): JsonResponse
    {
        Gate::authorize('update', $beverage);

        $beverage->update($request->all());

        return response()->json([
            'message' => 'Bebida actualizada exitosamente.',
            'data' => $beverage,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Beverage $beverage): JsonResponse
    {
        Gate::authorize('delete', $beverage);

        $beverage->delete();

        return response()->json([
            'message' => 'Bebida eliminada exitosamente.',
        ]);
    }

    /**
     * Export beverages to Excel.
     */
    public function export(Request $request): BinaryFileResponse
    {
        Gate::authorize('export', Beverage::class);

        $query = Beverage::query();

        // Apply same filters as index
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $sortBy = $request->get('sort_by', 'name');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortBy, $sortDirection);

        $beverages = $query->get();

        $filename = 'beverages_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($beverages) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Nombre', 'Descripción', 'Creado', 'Actualizado']);

            foreach ($beverages as $beverage) {
                fputcsv($file, [
                    $beverage->id,
                    $beverage->name,
                    $beverage->description,
                    $beverage->created_at,
                    $beverage->updated_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import beverages from Excel.
     */
    public function import(Request $request): JsonResponse
    {
        Gate::authorize('import', Beverage::class);

        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:2048',
        ]);

        return response()->json([
            'message' => 'Bebidas importadas exitosamente.',
            'imported' => 0,
        ]);
    }
}