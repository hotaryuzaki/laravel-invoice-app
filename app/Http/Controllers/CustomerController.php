<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
    // Index
    public function index(Request $request)
    {
        $limit = $request->query('limit', 10);
        $offset = $request->query('offset', 0);

        $customers = Customer::limit($limit)->offset($offset)->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Customers retrieved successfully',
            'data' => $customers
        ], 200);
    }

    // Store
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|min:3',
                'address' => 'required|string|min:3',
                'email' => 'required|email|unique:customers,email',
            ]);

            $customer = Customer::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Customer created successfully',
                'data' => $customer
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->errors()
            ], 400);
        }
    }

    // Show
    public function show($id)
    {
        try {
            $customer = Customer::findOrFail($id);

            return response()->json([
                'status' => 'success',
                'message' => 'Customer retrieved successfully',
                'data' => $customer
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while retrieving the customer',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    // Update
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|min:3',
                'address' => 'required|string|min:3',
                'email' => 'required|email|unique:customers,email,' . $id,
            ]);

            try {
                $customer = Customer::findOrFail($id);
                $customer->update($validated);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Customer updated successfully',
                    'data' => $customer
                ], 200);

            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'An error occurred while updating the customer',
                    'error' => $e->getMessage(),
                ], 404);
            }

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->errors()
            ], 400);
        }
    }

    // Destroy
    public function destroy($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $customer->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Customer deleted successfully'
            ], 204);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting the customer',
                'error' => $e->getMessage(),
            ], 404);
        }
    }
}
