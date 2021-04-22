<?php

namespace DawPhpPagination\Support\Facades;

/**
 * Classe parent de toute les Façades
 *
 * @link     https://github.com/dev-and-web/daw-php-pagination
 * @author   Stephen Damian <contact@devandweb.fr>
 * @license  MIT License
 */
abstract class Facade
{
    /**
     * @return string
     */
    abstract protected static function getFacadeAccessor();

    /**
     * @param string $method - Nom de la method à appeler
     * @param array $arguments - Paramètres dans method
     * @return mixed
     */
    final public static function __callStatic(string $method, array $arguments)
    {
        if (static::$instance === null) {            
            static::$instance = self::getFacadeInstace();
        }

        return static::$instance->$method(...$arguments);
    }

    /**
     * @return mixed
     */
    private static function getFacadeInstace()
    {
        $class = static::getFacadeAccessor();
        
        return new $class();
    }
}
