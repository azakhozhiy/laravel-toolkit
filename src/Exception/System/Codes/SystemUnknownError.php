<?php

declare(strict_types=1);

namespace AZakhozhiy\Laravel\Toolkit\Exception\System\Codes;

use AZakhozhiy\Laravel\Exceptions\BaseExceptionObject;

class SystemUnknownError extends BaseExceptionObject
{
    public static function getErrorCode(): int
    {
        return 1000;
    }

    public static function getErrorName(): string
    {
        return 'UNKNOWN_ERROR';
    }

    public static function getErrorDesc(): ?string
    {
        return null;
    }

    public static function getErrorMessage(): string
    {
        return 'Unknown error.';
    }

    public static function getHttpCode(): int
    {
        return 500;
    }
}
