<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    // GET /api/companies (index)
    public function index(Request $request)
    {
        $limit = $request->query('limit', 10);
        $offset = $request->query('offset', 0);

        $companies = Company::query()->limit($limit)->offset($offset)->get();

        return response()->json($companies, 200);
    }

    // POST /api/companies (store)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3',
            'address' => 'required|string|min:3',
            'email' => 'required|email|unique:companies,email',
        ]);

        $company = Company::create($validated);

        return response()->json($company, 201);
    }

    // GET /api/companies/{id} (show)
    public function show($id)
    {
        $company = Company::findOrFail($id);

        return response()->json($company, 200);
    }

    // PATCH /api/companies/{id} (update)
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3',
            'address' => 'required|string|min:3',
            'email' => 'required|email|unique:companies,email',
        ]);

        $company = Company::findOrFail($id);
        $company->update($validated);

        return response()->json($company, 200);
    }

    // DELETE /api/companies/{id} (destroy)
    public function destroy($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();

        return response()->noContent();
    }
}
