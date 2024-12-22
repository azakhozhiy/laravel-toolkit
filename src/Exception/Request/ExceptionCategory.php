<?php

declare(strict_types=1);

namespace AZakhozhiy\Laravel\Toolkit\Exception\Request;

use AZakhozhiy\Laravel\Exceptions\BaseExceptionCategory;

class ExceptionCategory extends BaseExceptionCategory
{
    public static function getName(): string
    {
        return 'Request';
    }

    public static function getSlug(): string
    {
        return 'request';
    }
}
