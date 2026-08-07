<?php

namespace App\Http\Controllers;

use App\Http\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class UsersController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Mock a paginated response to demonstrate collection metadata contract
        $page = (int) $request->input('page', 1);
        $perPage = (int) $request->input('per_page', 5);
        $total = 12;

        $items = [];
        for ($i = ($page - 1) * $perPage + 1; $i <= min($page * $perPage, $total); $i++) {
            $items[] = [
                'id' => $i,
                'name' => 'Item '.$i,
                'description' => 'Description for item '.$i,
                'created_at' => now()->toIso8601String(),
                'updated_at' => now()->toIso8601String(),
            ];
        }

        $paginator = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => $request->url()]
        );

        return $this->successResponse($paginator, 'Items retrieved successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        if ($id == 999) {
            // Mock a conflict/not-found scenario if required, or throw model not found
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException('Resource not found.');
        }

        return $this->successResponse([
            'id' => (int) $id,
            'name' => 'Item '.$id,
            'description' => 'Description for item '.$id,
            'created_at' => now()->toIso8601String(),
            'updated_at' => now()->toIso8601String(),
        ], 'Item retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        return $this->successResponse([
            'id' => rand(100, 999),
            'name' => $validated['name'],
            'description' => $validated['description'] ?? 'Item description',
            'created_at' => now()->toIso8601String(),
        ], 'Item created successfully', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        return $this->successResponse([
            'id' => (int) $id,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? 'Updated description',
            'updated_at' => now()->toIso8601String(),
        ], 'Item updated successfully');
    }

    /**
     * Partially update the specified resource in storage.
     */
    public function patch(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
        ]);

        return $this->successResponse([
            'id' => (int) $id,
            'name' => $validated['name'] ?? 'Partially Updated Item',
            'description' => $validated['description'] ?? 'Partially updated description',
            'updated_at' => now()->toIso8601String(),
        ], 'Item partially updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        return $this->successResponse([
            'id' => (int) $id,
        ], 'Item deleted successfully');
    }
}

