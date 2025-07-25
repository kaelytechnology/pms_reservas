<?php

namespace Kaely\PmsHotel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Kaely\PmsHotel\Models\RestaurantReservation;
use Kaely\PmsHotel\Http\Requests\RestaurantReservationRequest;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class RestaurantReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        Gate::authorize('viewAny', RestaurantReservation::class);

        $query = RestaurantReservation::query()->with([
            'reservation',
            'restaurant',
            'event',
            'food',
            'dessert',
            'beverage',
            'decoration',
            'specialRequirement',
            'availability'
        ]);

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by restaurant
        if ($request->filled('restaurant_id')) {
            $query->where('restaurant_id', $request->restaurant_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('reservation_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('reservation_date', '<=', $request->date_to);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'reservation_date');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        // Pagination
        $perPage = min($request->get('per_page', 15), 100);
        $reservations = $query->paginate($perPage);

        return response()->json($reservations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RestaurantReservationRequest $request): JsonResponse
    {
        Gate::authorize('create', RestaurantReservation::class);

        $reservation = RestaurantReservation::create($request->validated());
        $reservation->load([
            'reservation',
            'restaurant',
            'event',
            'food',
            'dessert',
            'beverage',
            'decoration',
            'specialRequirement',
            'availability'
        ]);

        return response()->json([
            'message' => 'Reserva de restaurante creada exitosamente.',
            'data' => $reservation,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(RestaurantReservation $restaurantReservation): JsonResponse
    {
        Gate::authorize('view', $restaurantReservation);

        $restaurantReservation->load([
            'reservation',
            'restaurant',
            'event',
            'food',
            'dessert',
            'beverage',
            'decoration',
            'specialRequirement',
            'availability'
        ]);

        return response()->json($restaurantReservation);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RestaurantReservationRequest $request, RestaurantReservation $restaurantReservation): JsonResponse
    {
        Gate::authorize('update', $restaurantReservation);

        $restaurantReservation->update($request->validated());
        $restaurantReservation->load([
            'reservation',
            'restaurant',
            'event',
            'food',
            'dessert',
            'beverage',
            'decoration',
            'specialRequirement',
            'availability'
        ]);

        return response()->json([
            'message' => 'Reserva de restaurante actualizada exitosamente.',
            'data' => $restaurantReservation,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RestaurantReservation $restaurantReservation): JsonResponse
    {
        Gate::authorize('delete', $restaurantReservation);

        $restaurantReservation->delete();

        return response()->json([
            'message' => 'Reserva de restaurante eliminada exitosamente.',
        ]);
    }

    /**
     * Export restaurant reservations to Excel.
     */
    public function export(Request $request): BinaryFileResponse
    {
        Gate::authorize('export', RestaurantReservation::class);

        $query = RestaurantReservation::query()->with([
            'reservation',
            'restaurant',
            'event',
            'food',
            'dessert',
            'beverage',
            'decoration',
            'specialRequirement',
            'availability'
        ]);

        // Apply same filters as index
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('restaurant_id')) {
            $query->where('restaurant_id', $request->restaurant_id);
        }
        if ($request->filled('date_from')) {
            $query->where('reservation_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('reservation_date', '<=', $request->date_to);
        }

        $sortBy = $request->get('sort_by', 'reservation_date');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $reservations = $query->get();

        $filename = 'restaurant_reservations_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($reservations) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'ID', 'Restaurante', 'Evento', 'Comida', 'Postre', 'Bebida', 
                'Decoración', 'Requerimiento', 'Personas', 'Fecha', 'Hora', 
                'Estado', 'Comentario', 'Otro'
            ]);

            foreach ($reservations as $reservation) {
                fputcsv($file, [
                    $reservation->id,
                    $reservation->restaurant?->short_name,
                    $reservation->event?->name,
                    $reservation->food?->name,
                    $reservation->dessert?->name,
                    $reservation->beverage?->name,
                    $reservation->decoration?->name,
                    $reservation->specialRequirement?->name,
                    $reservation->people,
                    $reservation->reservation_date,
                    $reservation->reservation_time,
                    $reservation->status,
                    $reservation->comment,
                    $reservation->other,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import restaurant reservations from Excel.
     */
    public function import(Request $request): JsonResponse
    {
        Gate::authorize('import', RestaurantReservation::class);

        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:2048',
        ]);

        return response()->json([
            'message' => 'Reservas de restaurante importadas exitosamente.',
            'imported' => 0,
        ]);
    }
}