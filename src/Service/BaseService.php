<?php

declare(strict_types=1);

namespace AZakhozhiy\Laravel\Toolkit\Service;

use AZakhozhiy\Laravel\Toolkit\Trait\HasExceptionRepository;
use AZakhozhiy\ScopedLogger\Component\ScopedLogger;
use AZakhozhiy\ScopedLogger\Contract\ScopedLoggerInterface;
use Illuminate\Support\Str;
use Psr\Log\LoggerInterface;

class BaseService
{
    use HasExceptionRepository;

    protected ScopedLoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        if ($logger instanceof ScopedLoggerInterface) {
            $this->logger = $logger;
        } else {
            $classNameWithNamespace = get_class($this);
            $className = substr($classNameWithNamespace, strrpos($classNameWithNamespace, '\\') + 1);
            $classKebab = Str::upper(Str::kebab($className));

            $this->logger = (new ScopedLogger($logger))->appendScope("<$classKebab>");
        }
    }

    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    public function setLogger(LoggerInterface $logger): self
    {
        $this->logger->setLogger($logger);

        return $this;
    }
}
