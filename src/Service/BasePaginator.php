<?php

declare(strict_types=1);

namespace AZakhozhiy\Laravel\Toolkit\Service;

use AZakhozhiy\Laravel\Eloquent\Tools\FilterableModel;
use EloquentFilter\ModelFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BasePaginator extends BaseService
{
    /** @var class-string<ModelFilter>|null */
    protected ?string $filterClass = null;

    /**
     * @return class-string<FilterableModel>
     */
    abstract protected function getModelClass(): string;

    /**
     * @return class-string<FilterableModel>
     */
    abstract protected function getBaseFilter(): string;

    public function paginate(
        array $data,
        int $perPage = 20
    ): LengthAwarePaginator {
        return $this->baseFilterQuery($data)->paginateFilter($perPage);
    }

    public function baseFilterQuery(array $data): Builder
    {
        return $this->filter($data)->orderBy('id', 'desc');
    }

    protected function filter(array $data): Builder
    {
        return $this->getModelClass()::filter($data, $this->getFilterClass());
    }

    protected function getFilterClass(): string
    {
        return $this->filterClass ?? $this->getBaseFilter();
    }

    /**
     * @param  class-string<ModelFilter>  $filterClass
     * @return $this
     */
    public function setFilterClass(string $filterClass): static
    {
        $this->filterClass = $filterClass;

        return $this;
    }
}
