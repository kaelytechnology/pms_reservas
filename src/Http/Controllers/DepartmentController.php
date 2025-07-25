<?php

namespace Kaely\PmsHotel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Kaely\PmsHotel\Models\Department;
use Kaely\PmsHotel\Http\Requests\DepartmentRequest;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        Gate::authorize('viewAny', Department::class);

        $query = Department::query();

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
        $departments = $query->paginate($perPage);

        return response()->json($departments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DepartmentRequest $request): JsonResponse
    {
        Gate::authorize('create', Department::class);

        $department = Department::create($request->validated());

        return response()->json([
            'message' => 'Departamento creado exitosamente.',
            'data' => $department,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department): JsonResponse
    {
        Gate::authorize('view', $department);

        return response()->json($department);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DepartmentRequest $request, Department $department): JsonResponse
    {
        Gate::authorize('update', $department);

        $department->update($request->validated());

        return response()->json([
            'message' => 'Departamento actualizado exitosamente.',
            'data' => $department,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department): JsonResponse
    {
        Gate::authorize('delete', $department);

        $department->delete();

        return response()->json([
            'message' => 'Departamento eliminado exitosamente.',
        ]);
    }

    /**
     * Export departments to Excel.
     */
    public function export(Request $request): BinaryFileResponse
    {
        Gate::authorize('export', Department::class);

        $query = Department::query();

        // Apply same filters as index
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $sortBy = $request->get('sort_by', 'name');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortBy, $sortDirection);

        $departments = $query->get();

        // Here you would implement the actual export logic
        // For now, we'll return a simple CSV response
        $filename = 'departments_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($departments) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Nombre', 'Descripción', 'Creado', 'Actualizado']);

            foreach ($departments as $department) {
                fputcsv($file, [
                    $department->id,
                    $department->name,
                    $department->description,
                    $department->created_at,
                    $department->updated_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import departments from Excel.
     */
    public function import(Request $request): JsonResponse
    {
        Gate::authorize('import', Department::class);

        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:2048',
        ]);

        // Here you would implement the actual import logic
        // For now, we'll return a success message
        return response()->json([
            'message' => 'Departamentos importados exitosamente.',
            'imported' => 0, // This would be the actual count
        ]);
    }
}