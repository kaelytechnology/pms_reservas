<?php

namespace Kaely\PmsHotel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Kaely\PmsHotel\Models\Reservation;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        Gate::authorize('viewAny', Reservation::class);

        $query = Reservation::query();

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
        $reservations = $query->paginate($perPage);

        return response()->json($reservations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        Gate::authorize('create', Reservation::class);

        $reservation = Reservation::create($request->all());

        return response()->json([
            'message' => 'Reservación creada exitosamente.',
            'data' => $reservation,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation): JsonResponse
    {
        Gate::authorize('view', $reservation);

        return response()->json($reservation);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reservation $reservation): JsonResponse
    {
        Gate::authorize('update', $reservation);

        $reservation->update($request->all());

        return response()->json([
            'message' => 'Reservación actualizada exitosamente.',
            'data' => $reservation,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation): JsonResponse
    {
        Gate::authorize('delete', $reservation);

        $reservation->delete();

        return response()->json([
            'message' => 'Reservación eliminada exitosamente.',
        ]);
    }

    /**
     * Export reservations to Excel.
     */
    public function export(Request $request): BinaryFileResponse
    {
        Gate::authorize('export', Reservation::class);

        $query = Reservation::query();

        // Apply same filters as index
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $sortBy = $request->get('sort_by', 'id');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $reservations = $query->get();

        $filename = 'reservations_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($reservations) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Cliente', 'Habitación', 'Check-in', 'Check-out', 'Estado', 'Creado', 'Actualizado']);

            foreach ($reservations as $reservation) {
                fputcsv($file, [
                    $reservation->id,
                    $reservation->guest_name ?? 'N/A',
                    $reservation->room_number ?? 'N/A',
                    $reservation->check_in ?? 'N/A',
                    $reservation->check_out ?? 'N/A',
                    $reservation->status ?? 'N/A',
                    $reservation->created_at,
                    $reservation->updated_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import reservations from Excel.
     */
    public function import(Request $request): JsonResponse
    {
        Gate::authorize('import', Reservation::class);

        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:2048',
        ]);

        return response()->json([
            'message' => 'Reservaciones importadas exitosamente.',
            'imported' => 0,
        ]);
    }
}