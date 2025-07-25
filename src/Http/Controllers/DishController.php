<?php

namespace Kaely\PmsHotel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Kaely\PmsHotel\Models\Dish;
use Kaely\PmsHotel\Http\Requests\DishRequest;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DishController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        Gate::authorize('viewAny', Dish::class);

        $query = Dish::query();

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
        $dishes = $query->paginate($perPage);

        return response()->json($dishes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DishRequest $request): JsonResponse
    {
        Gate::authorize('create', Dish::class);

        $dish = Dish::create($request->validated());

        return response()->json([
            'message' => 'Platillo creado exitosamente.',
            'data' => $dish,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Dish $dish): JsonResponse
    {
        Gate::authorize('view', $dish);

        return response()->json($dish);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DishRequest $request, Dish $dish): JsonResponse
    {
        Gate::authorize('update', $dish);

        $dish->update($request->validated());

        return response()->json([
            'message' => 'Platillo actualizado exitosamente.',
            'data' => $dish,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dish $dish): JsonResponse
    {
        Gate::authorize('delete', $dish);

        $dish->delete();

        return response()->json([
            'message' => 'Platillo eliminado exitosamente.',
        ]);
    }

    /**
     * Export dishes to Excel.
     */
    public function export(Request $request): BinaryFileResponse
    {
        Gate::authorize('export', Dish::class);

        $query = Dish::query();

        // Apply same filters as index
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $sortBy = $request->get('sort_by', 'name');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortBy, $sortDirection);

        $dishes = $query->get();

        $filename = 'dishes_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($dishes) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Nombre', 'Descripción', 'Creado', 'Actualizado']);

            foreach ($dishes as $dish) {
                fputcsv($file, [
                    $dish->id,
                    $dish->name,
                    $dish->description,
                    $dish->created_at,
                    $dish->updated_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import dishes from Excel.
     */
    public function import(Request $request): JsonResponse
    {
        Gate::authorize('import', Dish::class);

        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:2048',
        ]);

        return response()->json([
            'message' => 'Platillos importados exitosamente.',
            'imported' => 0,
        ]);
    }
}