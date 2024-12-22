<?php

declare(strict_types=1);

namespace AZakhozhiy\Laravel\Toolkit\Exception\System;

use AZakhozhiy\Laravel\Exceptions\BaseExceptionCategory;

class ExceptionCategory extends BaseExceptionCategory
{
    public static function getName(): string
    {
        return 'System';
    }

    public static function getSlug(): string
    {
        return 'system';
    }
}
