<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // List items with pagination
    public function index(Request $request)
    {
        $limit = $request->get('limit', 10);
        $offset = $request->get('offset', 0);

        $items = Item::query()->offset($offset)->limit($limit)->get();

        return response()->json($items, 200);
    }

    // Store a new item
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3',
            'type' => 'required|in:service,hardware',
        ]);

        $item = Item::create($validated);

        return response()->json($item, 201);
    }

    // Show a specific item
    public function show($id)
    {
        $item = Item::findOrFail($id);

        return response()->json($item, 200);
    }

    // Update an item
    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|min:3',
            'type' => 'required|in:service,hardware',
        ]);

        $item->update($validated);

        return response()->json($item, 200);
    }

    // Delete an item
    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return response()->noContent();
    }
}
