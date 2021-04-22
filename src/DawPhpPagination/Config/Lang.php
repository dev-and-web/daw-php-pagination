<?php

namespace DawPhpPagination\Config;

use DawPhpPagination\Exception\PaginationException;

/**
 * Pour require les fichiers qui sont dans le dossier lang des resources
 *
 * @link     https://github.com/dev-and-web/daw-php-pagination
 * @author   Stephen Damian <contact@devandweb.fr>
 * @license  MIT License
 */
final class Lang extends SingletonConfig
{
    /**
     * @var Lang
     */
    protected static ?self $instance = null;

    /**
     * @var array
     */
    private static array $require = [];
    
    /**
     * Pour charger fichier(s) de lang
     *
     * @param string $method
     * @param array $arguments
     * @return mixed
     * @throws PaginationException
     */
    public function __call(string $method, array $arguments)
    {
        if (!isset(self::$require[$method])) {
            $path = dirname(dirname(dirname(__FILE__))).'/resources/lang/'.$this->getLang().'/'.$method.'.php';
            
            if (file_exists($path)) {
                self::$require[$method] = require_once $path;
            } else {
                throw new PaginationException('Lang file "'.$this->getLang().'" not found.');
            }
        }

        return self::$require[$method];
    }

    /**
     * @return string - Langue choisie dans config
     */
    private function getLang(): string
    {
        static $lang;

        if ($lang === null) {
            $lang = Config::getInstance()->get()['lang'];
        }

        return $lang;
    }
}
