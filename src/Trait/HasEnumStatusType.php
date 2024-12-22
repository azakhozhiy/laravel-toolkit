<?php

declare(strict_types=1);

namespace AZakhozhiy\Laravel\Toolkit\Trait;

use AZakhozhiy\Laravel\Toolkit\Enum\StatusTypeEnum;

trait HasEnumStatusType
{
    abstract public function getStatusTypeEnum(): StatusTypeEnum;

    public function getStatusType(): string
    {
        return $this->getStatusTypeEnum()->value;
    }
}
