<?php

declare(strict_types=1);

namespace AZakhozhiy\Laravel\Toolkit\Artisan\Mapping;

class ArtisanTable
{
    private array $rows = [];
    private array $headers = [];

    public function addHeader(string $name): static
    {
        $this->headers[] = $name;

        return $this;
    }

    public function addHeaders(array $headers): static
    {
        $this->headers = array_merge($this->headers, $headers);

        return $this;
    }

    public function addRow(array $row): static
    {
        $this->rows[] = $row;

        return $this;
    }

    public function getRows(): array
    {
        return $this->rows;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
}
