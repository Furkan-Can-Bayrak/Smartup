<?php

namespace App\Repositories;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function update(int $id, array $data): bool
    {
        $record = $this->findById($id);
        return $record ? $record->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $record = $this->findById($id);
        return $record ? $record->delete() : false;
    }

    public function searchUsers(array $filters)
    {
        $search = User::query();

        foreach ($filters as $key => $value) {
            if ($key === 'companyName' && $value) {
                $search->whereHas('company', function ($query) use ($value) {
                    $query->where('name', 'like', "%{$value}%");
                });
            } elseif ($value) {
                $search->where($key, 'like', "%{$value}%");
            }
        }
        return $search->get();
    }
}
