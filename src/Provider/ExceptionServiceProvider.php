<?php

declare(strict_types=1);

namespace AZakhozhiy\Laravel\Toolkit\Provider;

use AZakhozhiy\Laravel\Exceptions\Contract\RegisterExceptionsInterface;
use AZakhozhiy\Laravel\Exceptions\Service\ExceptionRepository;
use AZakhozhiy\Laravel\Toolkit\Artisan\Command\ExceptionListCommand;
use AZakhozhiy\Laravel\Toolkit\Exception\SystemExceptions;
use Illuminate\Support\ServiceProvider;

abstract class ExceptionServiceProvider extends ServiceProvider
{
    abstract protected function getAdditionalRegistrars(): array;

    protected function getRegistrars(): array
    {
        return array_merge([
            SystemExceptions::class,
        ], $this->getAdditionalRegistrars());
    }

    public function register(): void
    {
        $this->app->singleton(ExceptionRepository::class, function () {
            $repo = new ExceptionRepository();

            /** @var class-string<RegisterExceptionsInterface> $registrarClass */
            foreach ($this->getRegistrars() as $registrarClass) {
                $repo = $registrarClass::register($repo);
            }

            return $repo;
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ExceptionListCommand::class
            ]);
        }
    }
}
