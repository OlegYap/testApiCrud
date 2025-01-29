<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Http\Requests\CompanyRequest;
use App\Services\CompanyService;
use App\DTO\CompanyDTO;
use Illuminate\Http\JsonResponse;
class CompanyController extends Controller
{
    public function __construct(
        private readonly CompanyService $companyService
    ) {}

    public function index(): JsonResponse
    {
        try {
            $companies = $this->companyService->getPaginated();
            return response()->json($companies);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching companies',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(CompanyRequest $request): JsonResponse
    {
        try {
            $dto = CompanyDTO::fromRequest($request);
            $company = $this->companyService->create($dto);
            return response()->json($company, 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating company',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Company $company): JsonResponse
    {
        try {
            return response()->json($company);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching company',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(CompanyRequest $request, Company $company): JsonResponse
    {
        try {
            $dto = CompanyDTO::fromRequest($request);
            $updatedCompany = $this->companyService->update($company, $dto);
            return response()->json($updatedCompany);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating company',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Company $company): JsonResponse
    {
        try {
            $this->companyService->delete($company);
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error deleting company',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getTopCompanies(): JsonResponse
    {
        try {
            $companies = $this->companyService->getTopRated();
            return response()->json($companies);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching top companies',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
