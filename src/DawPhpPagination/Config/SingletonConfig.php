<?php

namespace DawPhpPagination\Config;

/**
 * Classe parent des classes de config
 *
 * @link     https://github.com/dev-and-web/daw-php-pagination
 * @author   Stephen Damian <contact@devandweb.fr>
 * @license  MIT License
 */
abstract class SingletonConfig
{
    /**
     * Singleton
     *
     * @return mixed
     */
    final public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * SingletonConfig constructor.
     * private - car n'est pas autorisé à etre appelée de l'extérieur
     */
    private function __construct()
    {

    }

    /**
     * private - empêcher l'occurrence d'être cloné
     */
    private function __clone()
    {

    }
}
