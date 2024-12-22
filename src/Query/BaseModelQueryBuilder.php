<?php

declare(strict_types=1);

namespace AZakhozhiy\Laravel\Toolkit\Query;

use BadMethodCallException;
use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin Builder
 */
abstract class BaseModelQueryBuilder
{
    protected Builder $builder;

    public function __construct()
    {
        $this->builder = $this->baseQuery();
    }

    abstract public function baseQuery(): Builder;

    public function getBuilder(): Builder
    {
        return $this->builder;
    }

    public function setBuilder(Builder $builder): static
    {
        $this->builder = $builder;

        return $this;
    }

    public function addWith(string $relation): static
    {
        $this->builder->with($relation);

        return $this;
    }

    public function addWithout(string $relation): static
    {
        $this->builder->without($relation);

        return $this;
    }

    public function __call(string $name, array $arguments)
    {
        if (method_exists($this, $name)) {
            return $this->$name(...$arguments);
        }

        if (method_exists($this->builder, $name)) {
            return $this->builder->$name(...$arguments);
        }

        throw new BadMethodCallException("Method {$name} does not exist.");
    }
}
