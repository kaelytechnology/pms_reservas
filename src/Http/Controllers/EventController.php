<?php

namespace Kaely\PmsHotel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Kaely\PmsHotel\Models\Event;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        Gate::authorize('viewAny', Event::class);

        $query = Event::query();

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
        $events = $query->paginate($perPage);

        return response()->json($events);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        Gate::authorize('create', Event::class);

        $event = Event::create($request->all());

        return response()->json([
            'message' => 'Evento creado exitosamente.',
            'data' => $event,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event): JsonResponse
    {
        Gate::authorize('view', $event);

        return response()->json($event);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event): JsonResponse
    {
        Gate::authorize('update', $event);

        $event->update($request->all());

        return response()->json([
            'message' => 'Evento actualizado exitosamente.',
            'data' => $event,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event): JsonResponse
    {
        Gate::authorize('delete', $event);

        $event->delete();

        return response()->json([
            'message' => 'Evento eliminado exitosamente.',
        ]);
    }

    /**
     * Export events to Excel.
     */
    public function export(Request $request): BinaryFileResponse
    {
        Gate::authorize('export', Event::class);

        $query = Event::query();

        // Apply same filters as index
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $sortBy = $request->get('sort_by', 'name');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortBy, $sortDirection);

        $events = $query->get();

        $filename = 'events_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($events) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Nombre', 'Descripción', 'Creado', 'Actualizado']);

            foreach ($events as $event) {
                fputcsv($file, [
                    $event->id,
                    $event->name,
                    $event->description,
                    $event->created_at,
                    $event->updated_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import events from Excel.
     */
    public function import(Request $request): JsonResponse
    {
        Gate::authorize('import', Event::class);

        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:2048',
        ]);

        return response()->json([
            'message' => 'Eventos importados exitosamente.',
            'imported' => 0,
        ]);
    }
}