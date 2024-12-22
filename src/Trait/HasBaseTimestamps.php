<?php

declare(strict_types=1);

namespace AZakhozhiy\Laravel\Toolkit\Trait;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 */
trait HasBaseTimestamps
{
    public function getCreatedAt(): ?Carbon
    {
        if ($this->timestamps) {
            return Carbon::parse($this->created_at);
        }

        return null;
    }

    public function getUpdatedAt(): ?Carbon
    {
        if ($this->timestamps) {
            return $this->updated_at
                ? Carbon::parse($this->updated_at)
                : null;
        }

        return null;
    }
}
