<?php

namespace App\Services\Contracts;

use App\Models\Company;

interface CompanyServiceInterface
{
    public function getOrCreateCompany(string $name): Company;
}
