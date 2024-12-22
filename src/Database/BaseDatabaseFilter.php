<?php

declare(strict_types=1);

namespace AZakhozhiy\Laravel\Toolkit\Database;

use EloquentFilter\ModelFilter;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * @mixin Builder
 */
abstract class BaseDatabaseFilter
{
    protected bool $allowedEmptyFilters = false;
    protected bool $camelCasedMethods = true;
    protected ?Builder $query = null;
    protected array $blacklist = [];
    protected bool $dropId = true;
    protected array $input = [];
    protected array $filterMethodOrder = [];

    public function __construct(array $input = [])
    {
        $this->input = $this->allowedEmptyFilters ? $input : $this->removeEmptyInput($input);
    }

    public function getSelectFields(): array
    {
        return ['*'];
    }

    public function getFilterMethodOrder(): array
    {
        return [];
    }

    protected function includeFilterInput(string $key, $value): bool
    {
        return $value !== '' && $value !== null && !(is_array($value) && empty($value));
    }

    protected function removeEmptyInput(array $input): array
    {
        $filterableInput = [];

        foreach ($input as $key => $val) {
            if ($this->includeFilterInput($key, $val)) {
                $filterableInput[$key] = $val;
            }
        }

        return $filterableInput;
    }

    protected function input($key = null, $default = null)
    {
        if ($key === null) {
            return $this->input;
        }

        return array_key_exists($key, $this->input) ? $this->input[$key] : $default;
    }

    protected function filterInput(): void
    {
        $filterFn = function (string $key, mixed $val): void {
            $method = $this->getFilterMethod($key);

            if ($this->methodIsCallable($method)) {
                $this->{$method}($val);
            }
        };

        $filterMethodValues = [];
        $unsorted = [];

        $isFilterMethodOrderSet = !empty($this->filterMethodOrder);

        foreach ($this->input as $key => $val) {
            $method = $this->getFilterMethod($key);

            if ($this->methodIsCallable($method)) {
                if (!$isFilterMethodOrderSet) {
                    $filterFn($key, $val);
                    continue;
                }

                $methodOrderKey = array_search($method, $this->filterMethodOrder, true);
                if ($methodOrderKey !== false) {
                    $filterMethodValues[$methodOrderKey] = [$method, $val];
                } else {
                    $unsorted[] = [$method, $val];
                }
            }
        }

        if ($isFilterMethodOrderSet) {
            ksort($filterMethodValues);
            $filterMethodValues = array_merge(array_values($filterMethodValues), $unsorted);
        }

        foreach ($filterMethodValues as [$method, $val]) {
            if ($this->methodIsCallable($method)) {
                $this->{$method}($val);
            }
        }
    }

    protected function methodIsBlacklisted(string $method): bool
    {
        return in_array($method, $this->blacklist, true);
    }

    protected function methodIsCallable(string $method): bool
    {
        return !$this->methodIsBlacklisted($method) &&
            method_exists($this, $method) &&
            !method_exists(ModelFilter::class, $method);
    }

    protected function getFilterMethod($key)
    {
        $pattern = $this->dropId ? preg_replace('/^(.*)_id$/', '$1', $key) : $key;

        $methodName = str_replace('.', '', $pattern);

        return $this->camelCasedMethods ? Str::camel($methodName) : $methodName;
    }

    final public function handle(): ?Builder
    {
        if ($this->query === null) {
            throw new RuntimeException(
                "The query is null. Please use the setQuery(Builder \$query)"
                ." method to set a valid query before proceeding."
            );
        }

        // Filter global methods
        if (method_exists($this, 'setup')) {
            $this->setup();
        }

        // Run input filters
        $this->filterInput();

        // Grouping, limit, offset
        $this->endQuery();

        return $this->query;
    }

    public function endQuery(): void
    {
        return;
    }

    public function setQuery(Builder $query): static
    {
        $this->query = $query;

        return $this;
    }

    public function __call($method, $args)
    {
        $resp = call_user_func_array([$this->query, $method], $args);

        // Only return $this if query builder is returned
        // We don't want to make actions to the builder unreachable
        return $resp instanceof QueryBuilder ? $this : $resp;
    }
}
