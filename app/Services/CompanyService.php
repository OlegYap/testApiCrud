<?php

namespace App\Services;
use App\Models\Company;
use App\DTO\CompanyDTO;
use App\Models\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class CompanyService
{
    public function getPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Company::paginate($perPage);
    }

    public function create(CompanyDTO $dto): Company
    {
        try {
            if (!File::find($dto->logo_id)) {
                throw ValidationException::withMessages([
                    'logo_id' => ['The selected logo file does not exist.']
                ]);
            }
            return Company::create($dto->toArray());
        } catch (\Exception $e) {
            Log::error('Error creating company: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update(Company $company, CompanyDTO $dto): Company
    {
        try {
            $companyData = $dto->toArray();

            if (isset($companyData['logo_id']) && $companyData['logo_id'] !== $company->logo_id) {
                if (!File::find($companyData['logo_id'])) {
                    throw ValidationException::withMessages([
                        'logo_id' => ['The selected logo file does not exist.']
                    ]);
                }
            }

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

    public function getTopRated(int $limit = 10): Collection
    {
        return Company::withAvg('comments', 'rating')
            ->orderByDesc('comments_avg_rating')
            ->take($limit)
            ->get();
    }
}
