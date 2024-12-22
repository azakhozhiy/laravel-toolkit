<?php

declare(strict_types=1);

namespace AZakhozhiy\Laravel\Toolkit\Trait;

use AZakhozhiy\Laravel\Exceptions\Service\ExceptionRepository;
use Illuminate\Contracts\Container\BindingResolutionException;

trait HasExceptionRepository
{
    private ?ExceptionRepository $exceptionRepository = null;

    /**
     * @throws BindingResolutionException
     */
    public function getExceptionRepository(): ExceptionRepository
    {
        if ($this->exceptionRepository === null) {
            $this->exceptionRepository = app()->make(ExceptionRepository::class);
        }

        return $this->exceptionRepository;
    }
}
