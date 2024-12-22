<?php

declare(strict_types=1);

namespace AZakhozhiy\Laravel\Toolkit\Job;

use AZakhozhiy\Laravel\Toolkit\Trait\HasClassUtils;
use AZakhozhiy\ScopedLogger\Component\ScopedLogger;
use AZakhozhiy\ScopedLogger\Contract\ScopedLoggerInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Psr\Log\LoggerInterface;

abstract class BaseJob
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use HasClassUtils;

    protected ?string $loggerScope = null;

    public function __construct()
    {
    }

    public function initLogger(LoggerInterface $logger): ScopedLoggerInterface
    {
        if (!($logger instanceof ScopedLoggerInterface)) {
            if (!$this->loggerScope) {
                $className = static::getShortClassName();
                $this->loggerScope = Str::upper(Str::kebab($className));
            }

            $logger = (new ScopedLogger($logger))->appendScope($this->loggerScope);
        }

        return $logger;
    }

    public function setLoggerScope(string $scope): self
    {
        $this->loggerScope = $scope;

        return $this;
    }
}
