<?php

namespace App\Services;

use App\Repositories\Contracts\CompanyRepositoryInterface;
use App\Services\Contracts\CompanyServiceInterface;
use App\Models\Company;

class CompanyService implements CompanyServiceInterface
{
    protected CompanyRepositoryInterface $companyRepository;

    public function __construct(CompanyRepositoryInterface $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function getOrCreateCompany(string $companyName): Company
    {
        // Şirketi adıyla bul
        $company = $this->companyRepository->findByName($companyName);

        // Şirket bulunamazsa, yeni şirket oluştur
        if (!$company) {
            $company = $this->companyRepository->create(['name' => $companyName]);
        }

        return $company;
    }
}
