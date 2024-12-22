<?php

declare(strict_types=1);

namespace AZakhozhiy\Laravel\Toolkit\Tests;

use AZakhozhiy\Laravel\Toolkit\Tests\Provider\TestExceptionServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app): array
    {
        return [
            TestExceptionServiceProvider::class
        ];
    }
}
