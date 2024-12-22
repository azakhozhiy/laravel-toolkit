<?php

declare(strict_types=1);

namespace AZakhozhiy\Laravel\Toolkit\Trait;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property string $uuid
 * @property-read int $id
 *
 * @method Builder|static findByTokenOrFail(string $token)
 *
 * @mixin Model
 */
trait HasUuidColumn
{
    public const string UUID = 'uuid';

    protected static function bootHasUuidColumn(): void
    {
        static::creating(static function (self $model): void {
            if (!$model->{$model->getUuidColumnKey()}) {
                $model->{$model->getUuidColumnKey()} = Str::orderedUuid()->toString();
            }
        });
    }

    public function getUuidColumnKey(): string
    {
        return $this->{static::UUID};
    }

    public function getUuid(): string
    {
        return $this->{$this->getUuidColumnKey()};
    }
}
