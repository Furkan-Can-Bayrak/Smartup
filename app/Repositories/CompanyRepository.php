<?php

namespace App\Repositories;

use App\Repositories\Contracts\CompanyRepositoryInterface;
use App\Models\Company;

class CompanyRepository extends BaseRepository implements CompanyRepositoryInterface
{
    public function __construct(Company $model)
    {
        parent::__construct($model);
    }

    public function findByName(string $name): ?Company
    {
        return $this->model->where('name', $name)->first();
    }
}
