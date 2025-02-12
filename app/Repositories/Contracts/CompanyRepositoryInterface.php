<?php

namespace App\Repositories\Contracts;

use App\Models\Company;
use Illuminate\Database\Eloquent\Collection;

interface CompanyRepositoryInterface
{
    public function create(array $data);
    public function findById(int $id);
    public function findByName(string $name): ?Company;
}
