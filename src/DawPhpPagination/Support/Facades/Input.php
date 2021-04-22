<?php

namespace DawPhpPagination\Support\Facades;

/**
 * Facade pour la classe Request
 *
 * @link     https://github.com/dev-and-web/daw-php-pagination
 * @author   Stephen Damian <contact@devandweb.fr>
 * @license  MIT License
 */
final class Input extends Facade
{
    /**
     * @var DawPhpPagination\Support\Request\Input
     */
    protected static $instance;

    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'DawPhpPagination\Support\Request\Input';
    }
}
