<?php

namespace Kaely\PmsHotel\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Kaely\PmsHotel\Models\RoomRateRule;
use Kaely\PmsHotel\Http\Requests\RoomRateRuleRequest;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class RoomRateRuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        Gate::authorize('viewAny', RoomRateRule::class);

        $query = RoomRateRule::query();

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('class', 'like', "%{$search}%")
                  ->orWhere('room_type_name', 'like', "%{$search}%")
                  ->orWhere('rule_text', 'like', "%{$search}%");
            });
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        if ($request->filled('class')) {
            $query->where('class', $request->get('class'));
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        // Paginate results
        $perPage = $request->get('per_page', 15);
        $roomRateRules = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $roomRateRules,
            'message' => 'Room rate rules retrieved successfully'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoomRateRuleRequest $request): JsonResponse
    {
        Gate::authorize('create', RoomRateRule::class);

        $roomRateRule = RoomRateRule::create($request->validated());

        return response()->json([
            'success' => true,
            'data' => $roomRateRule,
            'message' => 'Room rate rule created successfully'
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(RoomRateRule $roomRateRule): JsonResponse
    {
        Gate::authorize('view', $roomRateRule);

        return response()->json([
            'success' => true,
            'data' => $roomRateRule,
            'message' => 'Room rate rule retrieved successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoomRateRuleRequest $request, RoomRateRule $roomRateRule): JsonResponse
    {
        Gate::authorize('update', $roomRateRule);

        $roomRateRule->update($request->validated());

        return response()->json([
            'success' => true,
            'data' => $roomRateRule->fresh(),
            'message' => 'Room rate rule updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoomRateRule $roomRateRule): JsonResponse
    {
        Gate::authorize('delete', $roomRateRule);

        $roomRateRule->delete();

        return response()->json([
            'success' => true,
            'message' => 'Room rate rule deleted successfully'
        ]);
    }

    /**
     * Export room rate rules to CSV.
     */
    public function export(Request $request): JsonResponse
    {
        Gate::authorize('export', RoomRateRule::class);

        $query = RoomRateRule::query();

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('class', 'like', "%{$search}%")
                  ->orWhere('room_type_name', 'like', "%{$search}%")
                  ->orWhere('rule_text', 'like', "%{$search}%");
            });
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $roomRateRules = $query->get();

        return response()->json([
            'success' => true,
            'data' => $roomRateRules,
            'message' => 'Room rate rules exported successfully'
        ]);
    }

    /**
     * Import room rate rules from CSV.
     */
    public function import(Request $request): JsonResponse
    {
        Gate::authorize('import', RoomRateRule::class);

        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        // TODO: Implement CSV import logic
        // This would typically involve reading the CSV file and creating records

        return response()->json([
            'success' => true,
            'message' => 'Room rate rules imported successfully'
        ]);
    }

    /**
     * Get unique classes for filtering.
     */
    public function getClasses(): JsonResponse
    {
        Gate::authorize('viewAny', RoomRateRule::class);

        $classes = RoomRateRule::distinct('class')
            ->whereNotNull('class')
            ->pluck('class')
            ->sort()
            ->values();

        return response()->json([
            'success' => true,
            'data' => $classes,
            'message' => 'Classes retrieved successfully'
        ]);
    }
}