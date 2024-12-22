<?php

declare(strict_types=1);

namespace AZakhozhiy\Laravel\Toolkit\Helper;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use InvalidArgumentException;
use RuntimeException;

class ModelHelper
{
    public static function generateUniqColumnValue(
        string $modelClass,
        string $column,
        string $slug,
        ?int   $whereNotKey = null,
        int    $deepLevel = 1,
        int    $maxLevel = 5
    ): string {
        if (class_exists($modelClass) === false) {
            throw new InvalidArgumentException("Model class doesn't exist.");
        }

        /** @var Model $model */
        $model = new $modelClass();

        if (!($model instanceof Model)) {
            throw new RuntimeException("Model class should be eloquent model.");
        }

        $query = $model::query()->where($column, $slug);

        if ($whereNotKey) {
            $query = $query->whereKeyNot($whereNotKey);
        }

        $exists = $query->exists();

        if ($exists === true) {
            if ($deepLevel + 1 === $maxLevel) {
                throw new RuntimeException("Too many attempts to create a unique slug for the $model model.");
            }

            $slug = Str::slug($slug . '-' . $deepLevel + 1);

            return static::generateUniqColumnValue($modelClass, $column, $slug, $whereNotKey, $deepLevel + 1);
        }

        return $slug;
    }
}
