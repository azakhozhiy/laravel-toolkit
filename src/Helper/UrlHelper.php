<?php

declare(strict_types=1);

namespace AZakhozhiy\Laravel\Toolkit\Helper;

use Illuminate\Support\Facades\URL;

class UrlHelper
{
    public static function joinUrl(...$parts): string
    {
        $firstPart = array_shift($parts);

        if (preg_match('/^https?:\/\//', $firstPart)) {
            $url = rtrim($firstPart, '/');
        } else {
            $url = trim($firstPart, '/');
        }

        $url .= '/' . implode('/', array_map(static fn ($part) => trim($part, '/'), $parts));

        return $url;
    }

    public static function getOriginalRequestUri(bool $withQuery = true): ?string
    {
        if (isset($_SERVER['HTTP_X_ORIGINAL_URI'])) {
            $uri = $_SERVER['HTTP_X_ORIGINAL_URI'];

            if (!$withQuery) {
                $uri = explode('?', $uri)[0];
            }

            $host = $_SERVER['HTTP_HOST'] ?? parse_url(config('app.url'), PHP_URL_HOST);

            return static::joinUrl(
                URL::formatScheme(true),
                $host,
                $uri
            );
        }

        return null;
    }
}
