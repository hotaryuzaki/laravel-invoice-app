<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CompanyController extends Controller
{
    // GET /api/companies (index)
    public function index(Request $request)
    {
        $limit = $request->query('limit', 10);
        $offset = $request->query('offset', 0);

        $companies = Company::limit($limit)->offset($offset)->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Companies retrieved successfully',
            'data' => $companies
        ], 200);
    }

    // POST /api/companies (store)
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|min:3',
                'address' => 'required|string|min:3',
                'email' => 'required|email|unique:companies,email',
            ]);

            $company = Company::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Company created successfully',
                'data' => $company
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->errors()
            ], 400);
        }
    }

    // GET /api/companies/{id} (show)
    public function show($id)
    {
        try {
            $company = Company::findOrFail($id);

            return response()->json([
                'status' => 'success',
                'message' => 'Company retrieved successfully',
                'data' => $company
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while retrieving the company',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    // PATCH /api/companies/{id} (update)
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|min:3',
                'address' => 'required|string|min:3',
                'email' => 'required|email|unique:companies,email,' . $id,
            ]);

            try {
                $company = Company::findOrFail($id);
                $company->update($validated);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Company updated successfully',
                    'data' => $company
                ], 200);

            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'An error occurred while updating the company',
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

    // DELETE /api/companies/{id} (destroy)
    public function destroy($id)
    {
        try {
            $company = Company::findOrFail($id);
            $company->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Company deleted successfully'
            ], 204);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting the company',
                'error' => $e->getMessage(),
            ], 404);
        }
    }
}
