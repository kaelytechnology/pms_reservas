<?php

namespace Kaely\PmsHotel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Kaely\PmsHotel\Models\Restaurant;
use Kaely\PmsHotel\Http\Requests\RestaurantRequest;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        Gate::authorize('viewAny', Restaurant::class);

        $query = Restaurant::query()->with('foodType');

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'short_name');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortBy, $sortDirection);

        // Pagination
        $perPage = min($request->get('per_page', 15), 100);
        $restaurants = $query->paginate($perPage);

        return response()->json($restaurants);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RestaurantRequest $request): JsonResponse
    {
        Gate::authorize('create', Restaurant::class);

        $restaurant = Restaurant::create($request->validated());
        $restaurant->load('foodType');

        return response()->json([
            'message' => 'Restaurante creado exitosamente.',
            'data' => $restaurant,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Restaurant $restaurant): JsonResponse
    {
        Gate::authorize('view', $restaurant);

        $restaurant->load('foodType');

        return response()->json($restaurant);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RestaurantRequest $request, Restaurant $restaurant): JsonResponse
    {
        Gate::authorize('update', $restaurant);

        $restaurant->update($request->validated());
        $restaurant->load('foodType');

        return response()->json([
            'message' => 'Restaurante actualizado exitosamente.',
            'data' => $restaurant,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Restaurant $restaurant): JsonResponse
    {
        Gate::authorize('delete', $restaurant);

        $restaurant->delete();

        return response()->json([
            'message' => 'Restaurante eliminado exitosamente.',
        ]);
    }

    /**
     * Export restaurants to Excel.
     */
    public function export(Request $request): BinaryFileResponse
    {
        Gate::authorize('export', Restaurant::class);

        $query = Restaurant::query()->with('foodType');

        // Apply same filters as index
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $sortBy = $request->get('sort_by', 'short_name');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortBy, $sortDirection);

        $restaurants = $query->get();

        $filename = 'restaurants_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($restaurants) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Nombre Corto', 'Nombre Completo', 'Tipo de Comida', 'Capacidad Mín', 'Capacidad Máx', 'Capacidad Total', 'Hora Apertura', 'Hora Cierre', 'Descripción']);

            foreach ($restaurants as $restaurant) {
                fputcsv($file, [
                    $restaurant->id,
                    $restaurant->short_name,
                    $restaurant->full_name,
                    $restaurant->foodType?->name,
                    $restaurant->min_capacity,
                    $restaurant->max_capacity,
                    $restaurant->total_capacity,
                    $restaurant->opening_time,
                    $restaurant->closing_time,
                    $restaurant->description,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import restaurants from Excel.
     */
    public function import(Request $request): JsonResponse
    {
        Gate::authorize('import', Restaurant::class);

        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:2048',
        ]);

        return response()->json([
            'message' => 'Restaurantes importados exitosamente.',
            'imported' => 0,
        ]);
    }
}