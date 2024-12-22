<?php

declare(strict_types=1);

namespace AZakhozhiy\Laravel\Toolkit\Model;

use AZakhozhiy\Laravel\Eloquent\Tools\FilterableModel;
use AZakhozhiy\Laravel\Toolkit\Trait\HasBaseTimestamps;

abstract class BaseModel extends FilterableModel
{
    use HasBaseTimestamps;
}
