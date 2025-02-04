<?php

namespace App\Http\Controllers\Api;

use App\DTO\CompanyUpdateDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyUpdateRequest;
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
        $companies = $this->companyService->getPaginated();
        return response()->json($companies);
    }

    public function store(CompanyRequest $request): JsonResponse
    {
        $dto = CompanyDTO::fromRequest($request);
        $company = $this->companyService->create($dto);
        return response()->json($company, 201);
    }

    public function show(Company $company): JsonResponse
    {
        return response()->json($company);
    }

    public function update(CompanyUpdateRequest $request, Company $company): JsonResponse
    {
        $dto = CompanyUpdateDTO::fromRequest($request);
        $updatedCompany = $this->companyService->update($company, $dto);
        return response()->json($updatedCompany);
    }

    public function destroy(Company $company): JsonResponse
    {
        $this->companyService->delete($company);
        return response()->json(null, 204);
    }

    public function getTopCompanies(Company $company): JsonResponse
    {
        $companies = $this->companyService->getTopRated($company);
        return response()->json($companies);
    }

    public function getComments(Company $company): JsonResponse
    {
        $comments = $this->companyService->getCompanyComments($company);
        return response()->json($comments);
    }

    public function getAverageRating(Company $company): JsonResponse
    {
        $averageRating = $this->companyService->getCompanyAverageRating($company);

        return response()->json([
            'company_id'    => $company->id,
            'averageRating' => $averageRating,
        ]);
    }
}
