<?php

namespace App\Services;
use App\Models\Company;
use App\DTO\CompanyDTO;
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
            if (!$dto->logo instanceof UploadedFile) {
                throw ValidationException::withMessages([
                    'logo' => ['The logo file is required.']
                ]);
            }

            $logoPath = $this->uploadLogo($dto->logo);

            if (!$logoPath) {
                throw ValidationException::withMessages([
                    'logo' => ['Failed to upload logo.']
                ]);
            }

            $companyData = $dto->toArray();
            $companyData['logo'] = $logoPath;
            return Company::create($companyData);
        } catch (\Exception $e) {
            Log::error('Error creating company: ' . $e->getMessage());

            // Если файл был загружен, но создание компании не удалось,
            // удаляем загруженный файл
            if (isset($logoPath)) {
                Storage::disk('public')->delete($logoPath);
            }

            throw $e;
        }
    }

    public function update(Company $company, CompanyDTO $dto): Company
    {
        try {
            $companyData = $dto->toArray();

            if ($dto->logo instanceof UploadedFile) {
                $logoPath = $this->uploadLogo($dto->logo);

                if (!$logoPath) {
                    throw ValidationException::withMessages([
                        'logo' => ['Failed to upload logo.']
                    ]);
                }

                $this->deleteOldLogo($company);
                $companyData['logo'] = $logoPath;
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
            $this->deleteOldLogo($company);
            $company->delete();
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

    private function uploadLogo(UploadedFile $file): ?string
    {
        try {
            return $file->store('logos', 'public');
        } catch (\Exception $e) {
            Log::error('Error uploading logo: ' . $e->getMessage());
            return null;
        }
    }

    private function deleteOldLogo(Company $company): void
    {
        if ($company->logo) {
            Storage::disk('public')->delete($company->logo);
        }
    }
}
