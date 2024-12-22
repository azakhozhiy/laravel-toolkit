<?php

declare(strict_types=1);

namespace AZakhozhiy\Laravel\Toolkit\Trait;

trait HasEnumName
{
    abstract public function getName(): string;
}
