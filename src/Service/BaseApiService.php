<?php

declare(strict_types=1);

namespace AZakhozhiy\Laravel\Toolkit\Service;

use Exception;
use Psr\Log\LoggerInterface;
use Throwable;

class BaseApiService extends BaseService
{
    /**
     * @throws Exception
     */
    public function makeRequest(int $times, callable $action, ?callable $customerWhenRetry = null): mixed
    {
        return retry(
            $times,
            $action,
            $this->getSleepMilliseconds(),
            fn (Throwable $e) => $customerWhenRetry
                ? $customerWhenRetry($e)
                : $this->whenRetry($e)
        );
    }

    public function getSleepMilliseconds(): int
    {
        return 500;
    }

    public function whenRetry(Throwable $e): bool
    {
        return true;
    }

    public function cloneLogger(?string $appendPrefix = null): LoggerInterface
    {
        $logger = clone $this->logger;

        if ($appendPrefix) {
            $logger->appendScope($appendPrefix);
        }

        return $logger;
    }
}
