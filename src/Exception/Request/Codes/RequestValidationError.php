<?php

declare(strict_types=1);

namespace AZakhozhiy\Laravel\Toolkit\Exception\Request\Codes;

use AZakhozhiy\Laravel\Exceptions\BaseExceptionObject;

class RequestValidationError extends BaseExceptionObject
{
    public static function getErrorCode(): int
    {
        return 1000;
    }

    public static function getErrorName(): string
    {
        return 'VALIDATION_ERROR';
    }

    public static function getErrorDesc(): ?string
    {
        return null;
    }

    public static function getErrorMessage(): array
    {
        return [];
    }

    public static function getHttpCode(): int
    {
        return 422;
    }
}
