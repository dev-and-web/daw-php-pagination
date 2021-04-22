<?php

namespace DawPhpPagination\Contracts\Support\Request;

use DawPhpPagination\Support\Request\Bags\ParameterBag;

/**
 * @link     https://github.com/dev-and-web/daw-php-pagination
 * @author   Stephen Damian <contact@devandweb.fr>
 * @license  MIT License
 */
Interface RequestInterface
{
    /**
     * Request Constructor.
     */
    public function __construct();

    /**
     * @return ParameterBag
     */
    public function getGet(): ParameterBag;

    /**
     * @return ParameterBag
     */
    public function getServer(): ParameterBag;

    /**
     * Verifier si Ajax
     *
     * @return bool
     */
    public function isAjax(): bool;
}
