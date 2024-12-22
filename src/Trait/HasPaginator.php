<?php

declare(strict_types=1);

namespace AZakhozhiy\Laravel\Toolkit\Trait;

use Illuminate\Pagination\LengthAwarePaginator;

trait HasPaginator
{
    protected array $paginatorExceptKeys = [];

    public function getPaginatorExceptKeys(): array
    {
        return $this->paginatorExceptKeys;
    }

    public function addPaginatorExceptKeys(array $keys): static
    {
        $this->paginatorExceptKeys = array_merge($this->paginatorExceptKeys, $keys);

        return $this;
    }

    public function paginatorToArray(LengthAwarePaginator $paginator, array $except = []): array
    {
        $result = $paginator->toArray();

        $unsetKeys = array_merge($except, $this->getPaginatorExceptKeys());

        foreach ($unsetKeys as $key) {
            unset($result[$key]);
        }

        return $result;
    }
}
