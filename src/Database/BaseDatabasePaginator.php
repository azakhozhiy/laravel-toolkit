<?php

declare(strict_types=1);

namespace AZakhozhiy\Laravel\Toolkit\Database;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

abstract class BaseDatabasePaginator
{
    public function getItems(int $offset, int $perPage): Collection
    {
        return $this->getQuery()
            ->offset($offset)
            ->limit($perPage)
            ->get();
    }

    public function getTotal(Builder $q): int
    {
        return DB::table(DB::raw("({$q->toSql()}) as sub"))
            ->mergeBindings($q)
            ->count();
    }

    public function paginate(
        Request $request,
        int $perPage = 30,
        int $page = 1,
        bool $asEloquent = false
    ): LengthAwarePaginator {
        $offset = ($page - 1) * $perPage;
        $query = $this->getQuery();
        $total = $this->getTotal($query);
        $items = $query
            ->offset($offset)
            ->limit($perPage)
            ->get();

        if ($asEloquent) {
            $items = $this->transformToEloquent($items);
        }

        $path = static::getOriginalRequestUri() ?: $request->path();

        $options = [
            'path' => $path,
            'query' => $request->query(),
        ];

        return new LengthAwarePaginator($items, $total, $perPage, $page, $options);
    }

    abstract public static function getOriginalRequestUri(): ?string;

    protected function transformToEloquent(Collection $items): Collection
    {
        return $items->map(function ($item) {
            $model = new ($this->getModel());
            $model->forceFill((array)$item);
            $model->exists = true;

            return $model;
        });
    }

    abstract public function getQuery(): Builder;

    abstract public function getModel(): string;
}
