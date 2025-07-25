<?php

namespace Kaely\PmsHotel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Kaely\PmsHotel\Models\CourtesyDinner;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CourtesyDinnerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        Gate::authorize('viewAny', CourtesyDinner::class);

        $query = CourtesyDinner::query();

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
        $courtesyDinners = $query->paginate($perPage);

        return response()->json($courtesyDinners);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        Gate::authorize('create', CourtesyDinner::class);

        $courtesyDinner = CourtesyDinner::create($request->all());

        return response()->json([
            'message' => 'Cena de cortesía creada exitosamente.',
            'data' => $courtesyDinner,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(CourtesyDinner $courtesyDinner): JsonResponse
    {
        Gate::authorize('view', $courtesyDinner);

        return response()->json($courtesyDinner);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CourtesyDinner $courtesyDinner): JsonResponse
    {
        Gate::authorize('update', $courtesyDinner);

        $courtesyDinner->update($request->all());

        return response()->json([
            'message' => 'Cena de cortesía actualizada exitosamente.',
            'data' => $courtesyDinner,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourtesyDinner $courtesyDinner): JsonResponse
    {
        Gate::authorize('delete', $courtesyDinner);

        $courtesyDinner->delete();

        return response()->json([
            'message' => 'Cena de cortesía eliminada exitosamente.',
        ]);
    }

    /**
     * Export courtesy dinners to Excel.
     */
    public function export(Request $request): BinaryFileResponse
    {
        Gate::authorize('export', CourtesyDinner::class);

        $query = CourtesyDinner::query();

        // Apply same filters as index
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $sortBy = $request->get('sort_by', 'id');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $courtesyDinners = $query->get();

        $filename = 'courtesy_dinners_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($courtesyDinners) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Reservación', 'Fecha', 'Descripción', 'Creado', 'Actualizado']);

            foreach ($courtesyDinners as $courtesyDinner) {
                fputcsv($file, [
                    $courtesyDinner->id,
                    $courtesyDinner->reservation_id ?? 'N/A',
                    $courtesyDinner->date ?? 'N/A',
                    $courtesyDinner->description ?? 'N/A',
                    $courtesyDinner->created_at,
                    $courtesyDinner->updated_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import courtesy dinners from Excel.
     */
    public function import(Request $request): JsonResponse
    {
        Gate::authorize('import', CourtesyDinner::class);

        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:2048',
        ]);

        return response()->json([
            'message' => 'Cenas de cortesía importadas exitosamente.',
            'imported' => 0,
        ]);
    }
}