<?php

namespace DawPhpPagination\Support\Request;

use DawPhpPagination\Contracts\Support\Request\RequestInterface;
use DawPhpPagination\Support\Request\Bags\ParameterBag;

/**
 * @link     https://github.com/dev-and-web/daw-php-pagination
 * @author   Stephen Damian <contact@devandweb.fr>
 * @license  MIT License
 */
class Request implements RequestInterface
{
    /**
     * @var ParameterBag - $_GET
     */
    private ParameterBag $get;

    /**
     * @var ParameterBag - $_SERVER
     */
    private ParameterBag $server;

    /**
     * Request Constructor.
     */
    public function __construct()
    {
        $this->get = new ParameterBag($_GET);
        $this->server = new ParameterBag($_SERVER);
    }

    /**
     * @return ParameterBag
     */
    public function getGet(): ParameterBag
    {
        return $this->get;
    }

    /**
     * @return ParameterBag
     */
    public function getServer(): ParameterBag
    {
        return $this->server;
    }

    /**
     * Verifier si Ajax
     *
     * @return bool
     */
    public function isAjax(): bool
    {
        return $this->server->has('HTTP_X_REQUESTED_WITH') && $this->server->get('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest';
    }
}
