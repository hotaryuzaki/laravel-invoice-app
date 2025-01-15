<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ItemController extends Controller
{
    // List items with pagination
    public function index(Request $request)
    {
        $limit = $request->query('limit', 10);
        $offset = $request->query('offset', 0);
        $search = $request->query('search', '');

        $query = Item::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('type', 'like', '%' . $search . '%');
            });
        }

        $totalRecords = $query->count();

        $items = $query->limit($limit)->offset($offset)->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Items retrieved successfully',
            'data' => $items,
            'total_datas' => $totalRecords
        ], 200);
    }

    // Store a new item
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|min:3',
                'type' => 'required|in:service,hardware',
            ]);

            $item = Item::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Item created successfully',
                'data' => $item
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->errors()
            ], 400);
        }
    }

    // Show a specific item
    public function show($id)
    {
        try {
            $item = Item::findOrFail($id);

            return response()->json([
                'status' => 'success',
                'message' => 'Item retrieved successfully',
                'data' => $item
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while retrieving the item',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    // Update an item
    public function update(Request $request, $id)
    {
        try {
            $item = Item::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|min:3',
                'type' => 'required|in:service,hardware',
            ]);

            $item->update($validated);

            return response()->json($item, 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->errors()
            ], 400);
        }
    }

    // Delete an item
    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return response()->noContent();
    }   
}
