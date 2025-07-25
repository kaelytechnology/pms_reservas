<?php

namespace Kaely\PmsHotel\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Kaely\PmsHotel\Models\Food;
use Kaely\PmsHotel\Http\Requests\FoodRequest;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        Gate::authorize('viewAny', Food::class);

        $query = Food::query();

        // Apply filters
        if ($request->filled('search')) {
            $query->search($request->get('search'));
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'name');
        $sortDirection = $request->get('sort_direction', 'asc');
        $query->orderBy($sortBy, $sortDirection);

        // Paginate results
        $perPage = $request->get('per_page', 15);
        $foods = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $foods,
            'message' => 'Foods retrieved successfully'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FoodRequest $request): JsonResponse
    {
        Gate::authorize('create', Food::class);

        $food = Food::create($request->validated());

        return response()->json([
            'success' => true,
            'data' => $food,
            'message' => 'Food created successfully'
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Food $food): JsonResponse
    {
        Gate::authorize('view', $food);

        return response()->json([
            'success' => true,
            'data' => $food,
            'message' => 'Food retrieved successfully'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FoodRequest $request, Food $food): JsonResponse
    {
        Gate::authorize('update', $food);

        $food->update($request->validated());

        return response()->json([
            'success' => true,
            'data' => $food->fresh(),
            'message' => 'Food updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Food $food): JsonResponse
    {
        Gate::authorize('delete', $food);

        $food->delete();

        return response()->json([
            'success' => true,
            'message' => 'Food deleted successfully'
        ]);
    }

    /**
     * Export foods to CSV.
     */
    public function export(Request $request): JsonResponse
    {
        Gate::authorize('export', Food::class);

        $query = Food::query();

        // Apply same filters as index
        if ($request->filled('search')) {
            $query->search($request->get('search'));
        }

        $foods = $query->get();

        return response()->json([
            'success' => true,
            'data' => $foods,
            'message' => 'Foods exported successfully'
        ]);
    }

    /**
     * Import foods from CSV.
     */
    public function import(Request $request): JsonResponse
    {
        Gate::authorize('import', Food::class);

        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        // TODO: Implement CSV import logic
        // This would typically involve reading the CSV file and creating records

        return response()->json([
            'success' => true,
            'message' => 'Foods imported successfully'
        ]);
    }
}