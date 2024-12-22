<?php

declare(strict_types=1);

namespace AZakhozhiy\Laravel\Toolkit\Tests\Unit;

use AZakhozhiy\Laravel\Exceptions\BaseExceptionCategory;
use AZakhozhiy\Laravel\Exceptions\BaseExceptionObject;
use AZakhozhiy\Laravel\Exceptions\Service\ExceptionRepository;
use AZakhozhiy\Laravel\Toolkit\Artisan\Command\ExceptionListCommand;
use AZakhozhiy\Laravel\Toolkit\Artisan\Mapping\ArtisanTable;
use AZakhozhiy\Laravel\Toolkit\Exception\Request\Codes\RequestValidationError;
use AZakhozhiy\Laravel\Toolkit\Exception\Request\ExceptionCategory as RequestExceptionCategory;
use AZakhozhiy\Laravel\Toolkit\Exception\System\Codes\SystemUnknownError;
use AZakhozhiy\Laravel\Toolkit\Exception\System\ExceptionCategory as SystemExceptionCategory;
use AZakhozhiy\Laravel\Toolkit\Helper\ExceptionTableHelper;
use AZakhozhiy\Laravel\Toolkit\Tests\TestCase;

class ExceptionRepoTest extends TestCase
{
    public function test_exception_repo(): void
    {
        $repo = $this->app->make(ExceptionRepository::class);
        $objects = $repo->getExceptionObjectsByCategory(RequestExceptionCategory::getSlug());

        self::assertCount(1, $objects);
    }

    /**
     * @throws \JsonException
     */
    public function test_command(): void
    {
        $category = RequestExceptionCategory::getSlug();

        $repo = $this->app->make(ExceptionRepository::class);

        $artisanTable = (new ArtisanTable());
        $exceptions = $repo->getExceptionObjectsByCategory($category);

        // Fill headers & rows
        $artisanTable = ExceptionTableHelper::fillRowsByErrors($artisanTable, $category, $exceptions);
        $artisanTable->addHeaders(ExceptionListCommand::getTableColumns());

        $this
            ->artisan('az-exceptions:list')
            ->expectsQuestion('Please select category for getting list.', $category)
            ->expectsTable(
                $artisanTable->getHeaders(),
                $artisanTable->getRows()
            );
    }

    public static function exceptions(): array
    {
        return [
            RequestValidationError::getErrorName() => [
                'cat' => RequestExceptionCategory::class,
                'error' => RequestValidationError::class
            ],
            SystemUnknownError::getErrorName() => [
                'cat' => SystemExceptionCategory::class,
                'error' => SystemUnknownError::class
            ]
        ];
    }

    /**
     * @param class-string<BaseExceptionCategory> $cat
     * @param class-string<BaseExceptionObject> $error
     * @dataProvider exceptions
     * @return void
     */
    public function test_build_exception(string $cat, string $error): void
    {
        $repo = $this->app->make(ExceptionRepository::class);

        $exceptionObj = $repo->buildException(
            $cat::getSlug(),
            $error::getErrorCode()
        );

        self::assertEquals(
            $exceptionObj->getExceptionObject()->getErrorCode(),
            $error::getErrorCode()
        );

        self::assertEquals(
            $exceptionObj->getExceptionObject()->getHttpCode(),
            $error::getHttpCode()
        );
    }
}
