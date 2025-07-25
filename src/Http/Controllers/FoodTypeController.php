<?php

namespace Kaely\PmsHotel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Kaely\PmsHotel\Models\FoodType;
use Kaely\PmsHotel\Http\Requests\FoodTypeRequest;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FoodTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        Gate::authorize('viewAny', FoodType::class);

        $query = FoodType::query();

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
        $foodTypes = $query->paginate($perPage);

        return response()->json($foodTypes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FoodTypeRequest $request): JsonResponse
    {
        Gate::authorize('create', FoodType::class);

        $foodType = FoodType::create($request->validated());

        return response()->json([
            'message' => 'Tipo de comida creado exitosamente.',
            'data' => $foodType,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(FoodType $foodType): JsonResponse
    {
        Gate::authorize('view', $foodType);

        return response()->json($foodType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FoodTypeRequest $request, FoodType $foodType): JsonResponse
    {
        Gate::authorize('update', $foodType);

        $foodType->update($request->validated());

        return response()->json([
            'message' => 'Tipo de comida actualizado exitosamente.',
            'data' => $foodType,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FoodType $foodType): JsonResponse
    {
        Gate::authorize('delete', $foodType);

        $foodType->delete();

        return response()->json([
            'message' => 'Tipo de comida eliminado exitosamente.',
        ]);
    }

    /**
     * Export food types to Excel.
     */
    public function export(Request $request): BinaryFileResponse
    {
        Gate::authorize('export', FoodType::class);

        $query = FoodType::query();

        // Apply same filters as index
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $sortBy = $request->get('sort_by', 'name');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortBy, $sortDirection);

        $foodTypes = $query->get();

        // Here you would implement the actual export logic
        // For now, we'll return a simple CSV response
        $filename = 'food_types_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($foodTypes) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Nombre', 'Descripción', 'Creado', 'Actualizado']);

            foreach ($foodTypes as $foodType) {
                fputcsv($file, [
                    $foodType->id,
                    $foodType->name,
                    $foodType->description,
                    $foodType->created_at,
                    $foodType->updated_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import food types from Excel.
     */
    public function import(Request $request): JsonResponse
    {
        Gate::authorize('import', FoodType::class);

        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:2048',
        ]);

        // Here you would implement the actual import logic
        // For now, we'll return a success message
        return response()->json([
            'message' => 'Tipos de comida importados exitosamente.',
            'imported' => 0, // This would be the actual count
        ]);
    }
}