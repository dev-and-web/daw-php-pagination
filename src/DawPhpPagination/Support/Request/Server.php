<?php

namespace DawPhpPagination\Support\Request;

/**
 * @link     https://github.com/dev-and-web/daw-php-pagination
 * @author   Stephen Damian <contact@devandweb.fr>
 * @license  MIT License
 */
class Server
{
    /**
     * Server
     */
    private $request;


    /**
     * Server constructor.
     */
    public function __construct()
    {
        $this->request = new Request();
    }
    
    /**
     * @return string - L'URI qui a été fourni pour accéder à cette page
     */
    public function getRequestUri(): string
    {
        return $this->request->getServer()->get('REQUEST_URI');
    }
}
