<?php

declare(strict_types=1);

namespace AZakhozhiy\Laravel\Toolkit\Repository;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModelRepository
{
    abstract public function query(): Builder;

    public function getByIds(array $ids): Collection
    {
        return $this->query()
            ->whereIn('id', $ids)
            ->get();
    }

    public function findById(int $id): Model
    {
        return $this->query()
            ->where('id', $id)
            ->firstOrFail();
    }

    public function checkExists(string $field, mixed $value): bool
    {
        return $this->query()
            ->where($field, $value)
            ->exists();
    }
}
