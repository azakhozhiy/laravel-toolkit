<?php

namespace AZakhozhiy\Laravel\Toolkit\Tests\Provider;

use AZakhozhiy\Laravel\Toolkit\Provider\ExceptionServiceProvider;

class TestExceptionServiceProvider extends ExceptionServiceProvider
{
    protected function getAdditionalRegistrars(): array
    {
        return [];
    }
}