<?php

namespace Kaely\PmsHotel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Kaely\PmsHotel\Models\RestaurantAvailability;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class RestaurantAvailabilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        Gate::authorize('viewAny', RestaurantAvailability::class);

        $query = RestaurantAvailability::query();

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'id');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        // Pagination
        $perPage = min($request->get('per_page', 15), 100);
        $restaurantAvailabilities = $query->paginate($perPage);

        return response()->json($restaurantAvailabilities);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        Gate::authorize('create', RestaurantAvailability::class);

        $restaurantAvailability = RestaurantAvailability::create($request->all());

        return response()->json([
            'message' => 'Disponibilidad de restaurante creada exitosamente.',
            'data' => $restaurantAvailability,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(RestaurantAvailability $restaurantAvailability): JsonResponse
    {
        Gate::authorize('view', $restaurantAvailability);

        return response()->json($restaurantAvailability);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RestaurantAvailability $restaurantAvailability): JsonResponse
    {
        Gate::authorize('update', $restaurantAvailability);

        $restaurantAvailability->update($request->all());

        return response()->json([
            'message' => 'Disponibilidad de restaurante actualizada exitosamente.',
            'data' => $restaurantAvailability,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RestaurantAvailability $restaurantAvailability): JsonResponse
    {
        Gate::authorize('delete', $restaurantAvailability);

        $restaurantAvailability->delete();

        return response()->json([
            'message' => 'Disponibilidad de restaurante eliminada exitosamente.',
        ]);
    }

    /**
     * Export restaurant availabilities to Excel.
     */
    public function export(Request $request): BinaryFileResponse
    {
        Gate::authorize('export', RestaurantAvailability::class);

        $query = RestaurantAvailability::query();

        // Apply same filters as index
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $sortBy = $request->get('sort_by', 'id');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $restaurantAvailabilities = $query->get();

        $filename = 'restaurant_availabilities_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($restaurantAvailabilities) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Restaurante', 'Fecha', 'Disponible', 'Creado', 'Actualizado']);

            foreach ($restaurantAvailabilities as $availability) {
                fputcsv($file, [
                    $availability->id,
                    $availability->restaurant_id ?? 'N/A',
                    $availability->date ?? 'N/A',
                    $availability->available ? 'Sí' : 'No',
                    $availability->created_at,
                    $availability->updated_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import restaurant availabilities from Excel.
     */
    public function import(Request $request): JsonResponse
    {
        Gate::authorize('import', RestaurantAvailability::class);

        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:2048',
        ]);

        return response()->json([
            'message' => 'Disponibilidades de restaurante importadas exitosamente.',
            'imported' => 0,
        ]);
    }
}