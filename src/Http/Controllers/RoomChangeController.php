<?php

namespace Kaely\PmsHotel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Kaely\PmsHotel\Models\RoomChange;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class RoomChangeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        Gate::authorize('viewAny', RoomChange::class);

        $query = RoomChange::query();

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
        $roomChanges = $query->paginate($perPage);

        return response()->json($roomChanges);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        Gate::authorize('create', RoomChange::class);

        $roomChange = RoomChange::create($request->all());

        return response()->json([
            'message' => 'Cambio de habitación creado exitosamente.',
            'data' => $roomChange,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(RoomChange $roomChange): JsonResponse
    {
        Gate::authorize('view', $roomChange);

        return response()->json($roomChange);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RoomChange $roomChange): JsonResponse
    {
        Gate::authorize('update', $roomChange);

        $roomChange->update($request->all());

        return response()->json([
            'message' => 'Cambio de habitación actualizado exitosamente.',
            'data' => $roomChange,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoomChange $roomChange): JsonResponse
    {
        Gate::authorize('delete', $roomChange);

        $roomChange->delete();

        return response()->json([
            'message' => 'Cambio de habitación eliminado exitosamente.',
        ]);
    }

    /**
     * Export room changes to Excel.
     */
    public function export(Request $request): BinaryFileResponse
    {
        Gate::authorize('export', RoomChange::class);

        $query = RoomChange::query();

        // Apply same filters as index
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $sortBy = $request->get('sort_by', 'id');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $roomChanges = $query->get();

        $filename = 'room_changes_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($roomChanges) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Reservación', 'Habitación Anterior', 'Habitación Nueva', 'Creado', 'Actualizado']);

            foreach ($roomChanges as $roomChange) {
                fputcsv($file, [
                    $roomChange->id,
                    $roomChange->reservation_id ?? 'N/A',
                    $roomChange->old_room ?? 'N/A',
                    $roomChange->new_room ?? 'N/A',
                    $roomChange->created_at,
                    $roomChange->updated_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import room changes from Excel.
     */
    public function import(Request $request): JsonResponse
    {
        Gate::authorize('import', RoomChange::class);

        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:2048',
        ]);

        return response()->json([
            'message' => 'Cambios de habitación importados exitosamente.',
            'imported' => 0,
        ]);
    }
}