<?php

declare(strict_types=1);

namespace AZakhozhiy\Laravel\Toolkit\Trait;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 */
trait HasUuidPrimaryKey
{
    use HasUuidColumn;

    protected $keyType = 'string';
    protected $primaryKey = 'uuid';

    public function getKeyType()
    {
        return 'string';
    }

    public function getKeyName()
    {
        return static::UUID;
    }
}
