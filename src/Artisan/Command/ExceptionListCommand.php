<?php

declare(strict_types=1);

namespace AZakhozhiy\Laravel\Toolkit\Artisan\Command;

use AZakhozhiy\Laravel\Exceptions\Service\ExceptionRepository;
use AZakhozhiy\Laravel\Toolkit\Artisan\Mapping\ArtisanTable;
use AZakhozhiy\Laravel\Toolkit\Helper\ExceptionTableHelper;
use Illuminate\Console\Command;

class ExceptionListCommand extends Command
{
    protected $name = 'az-exceptions:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get exceptions list for core.';

    public static function getTableColumns(): array
    {
        return ['category', 'code', 'name', 'default_message'];
    }

    /**
     * @throws \JsonException
     */
    public function handle(ExceptionRepository $exceptionRepository): void
    {
        $categoryName = $this->choice(
            'Please select category for getting list.',
            $exceptionRepository->getCategoriesSlugs()
        );

        $catExceptions = $exceptionRepository->getExceptionObjectsByCategory($categoryName);

        $artisanTable = new ArtisanTable();
        $artisanTable->addHeaders(static::getTableColumns());
        $artisanTable = ExceptionTableHelper::fillRowsByErrors(
            $artisanTable,
            $categoryName,
            $catExceptions
        );

        $this->table($artisanTable->getHeaders(), $artisanTable->getRows());
    }
}
