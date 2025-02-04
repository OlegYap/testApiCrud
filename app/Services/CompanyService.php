<?php

namespace App\Services;
use App\DTO\CompanyUpdateDTO;
use App\Models\Company;
use App\DTO\CompanyDTO;
use App\Models\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CompanyService
{
    public function getPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Company::paginate($perPage);
    }

    public function create(CompanyDTO $dto): Company
    {
        try {
            return Company::create($dto->toArray());
        } catch (\Exception $e) {
            Log::error('Error creating company: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update(Company $company, CompanyUpdateDTO $dto): Company
    {
        try {
            $companyData = $dto->toArray();
            $company->update($companyData);
            return $company;
        } catch (\Exception $e) {
            Log::error('Error updating company: ' . $e->getMessage());
            throw $e;
        }
    }

    public function delete(Company $company): void
    {
        try {
            $file = File::find($company->logo_id);
            $company->delete();

            if ($file && !Company::where('logo_id', $file->id)->exists()) {
                $file->delete();
            }
        } catch (\Exception $e) {
            Log::error('Error deleting company: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getTopRated(Company $company): Collection
    {
        return $company->getTopRated();
    }

    public function getCompanyComments(Company $company): Collection
    {
        return $company->getCompanyComments();
    }

    public function getCompanyAverageRating(Company $company): float
    {
        return $company->getAverageRating();
    }
}
