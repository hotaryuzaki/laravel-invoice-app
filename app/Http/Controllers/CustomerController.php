<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // Index
    public function index(Request $request)
    {
        $limit = $request->query('limit', 10);
        $offset = $request->query('offset', 0);

        $customers = Customer::limit($limit)->offset($offset)->get();

        return response()->json($customers, 200);
    }

    // Store
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3',
            'address' => 'required|string|min:3',
            'email' => 'required|email|unique:customers,email',
        ]);

        $customer = Customer::create($validated);

        return response()->json($customer, 201);
    }

    // Show
    public function show($id)
    {
        $customer = Customer::findOrFail($id);

        return response()->json($customer, 200);
    }

    // Update
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3',
            'address' => 'required|string|min:3',
            'email' => 'required|email|unique:customers,email,' . $id,
        ]);

        $customer = Customer::findOrFail($id);
        $customer->update($validated);

        return response()->json($customer, 200);
    }

    // Destroy
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return response()->json(null, 204);
    }
}
