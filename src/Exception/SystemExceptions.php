<?php

declare(strict_types=1);

namespace AZakhozhiy\Laravel\Toolkit\Exception;

use AZakhozhiy\Laravel\Exceptions\Contract\RegisterExceptionsInterface;
use AZakhozhiy\Laravel\Exceptions\Service\ExceptionRepository;

class SystemExceptions implements RegisterExceptionsInterface
{
    public static function register(ExceptionRepository $repo): ExceptionRepository
    {
        $repo = static::registerRequestErrors($repo);

        return static::registerSystemErrors($repo);
    }

    private static function registerSystemErrors(ExceptionRepository $repo): ExceptionRepository
    {
        $cat = System\ExceptionCategory::class;

        $repo->registerExceptionCategory($cat);

        return $repo
            ->registerException($cat, System\Codes\SystemUnknownError::class);
    }

    private static function registerRequestErrors(ExceptionRepository $repo): ExceptionRepository
    {
        $cat = Request\ExceptionCategory::class;

        $repo->registerExceptionCategory($cat);

        return $repo
            ->registerException($cat, Request\Codes\RequestValidationError::class);
    }
}
