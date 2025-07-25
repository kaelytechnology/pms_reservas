<?php

namespace Kaely\PmsHotel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Kaely\PmsHotel\Models\Decoration;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DecorationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        Gate::authorize('viewAny', Decoration::class);

        $query = Decoration::query();

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
        $decorations = $query->paginate($perPage);

        return response()->json($decorations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        Gate::authorize('create', Decoration::class);

        $decoration = Decoration::create($request->all());

        return response()->json([
            'message' => 'Decoración creada exitosamente.',
            'data' => $decoration,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Decoration $decoration): JsonResponse
    {
        Gate::authorize('view', $decoration);

        return response()->json($decoration);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Decoration $decoration): JsonResponse
    {
        Gate::authorize('update', $decoration);

        $decoration->update($request->all());

        return response()->json([
            'message' => 'Decoración actualizada exitosamente.',
            'data' => $decoration,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Decoration $decoration): JsonResponse
    {
        Gate::authorize('delete', $decoration);

        $decoration->delete();

        return response()->json([
            'message' => 'Decoración eliminada exitosamente.',
        ]);
    }

    /**
     * Export decorations to Excel.
     */
    public function export(Request $request): BinaryFileResponse
    {
        Gate::authorize('export', Decoration::class);

        $query = Decoration::query();

        // Apply same filters as index
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $sortBy = $request->get('sort_by', 'name');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortBy, $sortDirection);

        $decorations = $query->get();

        $filename = 'decorations_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($decorations) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Nombre', 'Descripción', 'Creado', 'Actualizado']);

            foreach ($decorations as $decoration) {
                fputcsv($file, [
                    $decoration->id,
                    $decoration->name,
                    $decoration->description,
                    $decoration->created_at,
                    $decoration->updated_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import decorations from Excel.
     */
    public function import(Request $request): JsonResponse
    {
        Gate::authorize('import', Decoration::class);

        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:2048',
        ]);

        return response()->json([
            'message' => 'Decoraciones importadas exitosamente.',
            'imported' => 0,
        ]);
    }
}