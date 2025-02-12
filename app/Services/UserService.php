<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Services\Contracts\CompanyServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use App\Models\User;

class UserService implements UserServiceInterface
{
    protected UserRepositoryInterface $userRepository;
    protected CompanyServiceInterface $companyService;

    public function __construct(UserRepositoryInterface $userRepository, CompanyServiceInterface $companyService)
    {
        $this->userRepository = $userRepository;
        $this->companyService = $companyService;
    }

    public function getAllUsers(): Collection
    {
        return $this->userRepository->all();
    }


    public function createUser(array $data): User
    {
        // Şirketi getir veya oluştur
        $company = $this->companyService->getOrCreateCompany($data['companyName']);

        // Kullanıcıyı oluştur
        return $this->userRepository->create([
            'name'       => $data['name'],
            'surname'    => $data['surname'],
            'email'    => $data['email'],
            'phoneNumber'     => $data['phoneNumber'],
            'company_id' => $company->id,
        ]);
    }


    public function findUser(int $id): ?User
    {
        return $this->userRepository->findById($id);
    }

    public function updateUser(int $id, array $data): bool
    {
        if (isset($data['companyName'])) {
            $company = $this->companyService->getOrCreateCompany($data['companyName']);
            $data['company_id'] = $company->id;
            unset($data['companyName']);
        }

        return $this->userRepository->update($id, $data);
    }

    public function deleteUser(int $id): bool
    {
        return $this->userRepository->delete($id);
    }

    public function searchUsers(array $filters)
    {
        return $this->userRepository->searchUsers($filters);
    }
}
