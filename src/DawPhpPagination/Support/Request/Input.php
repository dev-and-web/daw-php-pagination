<?php

namespace DawPhpPagination\Support\Request;

/**
 * @link     https://github.com/dev-and-web/daw-php-pagination
 * @author   Stephen Damian <contact@devandweb.fr>
 * @license  MIT License
 */
class Input
{
    /**
     * Request
     */
    private $request;

    /**
     * Input constructor.
     */
    public function __construct()
    {
        $this->request = new Request();
    }

    /**
     * Verifier si donnée envoyé en GET existe
     *
     * @param string $name
     * @return bool
     */
    public function hasGet(string $name): bool
    {
        return $this->request->getGet()->has($name);;
    }


    /**
     * @param string $name
     * @return mixed - Donnée envoyée en GET
     */
    public function get(string $name)
    {
        return $this->request->getGet()->get($name);
    }
}
