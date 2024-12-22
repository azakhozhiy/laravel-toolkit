<?php

declare(strict_types=1);

namespace AZakhozhiy\Laravel\Toolkit\Trait;

trait HasClassUtils
{
    public static function getShortClassName(): string
    {
        $classNameWithNamespace = static::class;

        return substr($classNameWithNamespace, strrpos($classNameWithNamespace, '\\') + 1);
    }
}
