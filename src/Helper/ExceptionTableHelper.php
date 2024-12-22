<?php

declare(strict_types=1);

namespace AZakhozhiy\Laravel\Toolkit\Helper;

use AZakhozhiy\Laravel\Exceptions\BaseExceptionObject;
use AZakhozhiy\Laravel\Toolkit\Artisan\Mapping\ArtisanTable;
use JsonException;

class ExceptionTableHelper
{
    /**
     * @param ArtisanTable $table
     * @param string $catSlug
     * @param BaseExceptionObject[] $exceptions
     * @return ArtisanTable
     * @throws JsonException
     */
    public static function fillRowsByErrors(
        ArtisanTable $table,
        string       $catSlug,
        array        $exceptions
    ): ArtisanTable {
        foreach ($exceptions as $exception) {
            $table->addRow([
                $catSlug,
                $exception::getErrorCode(),
                $exception::getErrorName(),
                is_array($exception::getErrorMessage())
                    ? json_encode($exception::getErrorMessage(), JSON_THROW_ON_ERROR)
                    : $exception::getErrorMessage()
            ]);
        }

        return $table;
    }
}
