<?php

namespace Kaely\PmsHotel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Kaely\PmsHotel\Models\SpecialRequirement;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SpecialRequirementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        Gate::authorize('viewAny', SpecialRequirement::class);

        $query = SpecialRequirement::query();

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
        $specialRequirements = $query->paginate($perPage);

        return response()->json($specialRequirements);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        Gate::authorize('create', SpecialRequirement::class);

        $specialRequirement = SpecialRequirement::create($request->all());

        return response()->json([
            'message' => 'Requerimiento especial creado exitosamente.',
            'data' => $specialRequirement,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(SpecialRequirement $specialRequirement): JsonResponse
    {
        Gate::authorize('view', $specialRequirement);

        return response()->json($specialRequirement);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SpecialRequirement $specialRequirement): JsonResponse
    {
        Gate::authorize('update', $specialRequirement);

        $specialRequirement->update($request->all());

        return response()->json([
            'message' => 'Requerimiento especial actualizado exitosamente.',
            'data' => $specialRequirement,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SpecialRequirement $specialRequirement): JsonResponse
    {
        Gate::authorize('delete', $specialRequirement);

        $specialRequirement->delete();

        return response()->json([
            'message' => 'Requerimiento especial eliminado exitosamente.',
        ]);
    }

    /**
     * Export special requirements to Excel.
     */
    public function export(Request $request): BinaryFileResponse
    {
        Gate::authorize('export', SpecialRequirement::class);

        $query = SpecialRequirement::query();

        // Apply same filters as index
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $sortBy = $request->get('sort_by', 'name');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortBy, $sortDirection);

        $specialRequirements = $query->get();

        $filename = 'special_requirements_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($specialRequirements) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Nombre', 'Descripción', 'Creado', 'Actualizado']);

            foreach ($specialRequirements as $specialRequirement) {
                fputcsv($file, [
                    $specialRequirement->id,
                    $specialRequirement->name,
                    $specialRequirement->description,
                    $specialRequirement->created_at,
                    $specialRequirement->updated_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import special requirements from Excel.
     */
    public function import(Request $request): JsonResponse
    {
        Gate::authorize('import', SpecialRequirement::class);

        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:2048',
        ]);

        return response()->json([
            'message' => 'Requerimientos especiales importados exitosamente.',
            'imported' => 0,
        ]);
    }
}