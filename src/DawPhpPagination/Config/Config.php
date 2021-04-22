<?php

namespace DawPhpPagination\Config;

use DawPhpPagination\Contracts\Config\ConfigInterface;

/**
 * Pour require fichier(s) qui sont dans le dossier de config
 *
 * @link     https://github.com/dev-and-web/daw-php-pagination
 * @author   Stephen Damian <contact@devandweb.fr>
 * @license  MIT License
 */
final class Config extends SingletonConfig implements ConfigInterface
{
    /**
     * @var Config
     */
    protected static ?self $instance = null;
    
    /**
     * @var array
     */
    private static array $config = [];
    
    /**
     * @var array
     */
    private static array $defaultConfig = [];

    /**
     * @param array $config
     */
    public static function set(array $config): void
    {
        // éventuellement écraser la config par défaut avec config(s) passé(s) en param
        self::$config = array_merge(self::$defaultConfig, $config);
    }

    /**
     * @return array|string
     */
    public static function get(string $param = null)
    {
        if (self::$defaultConfig === []) {
            self::$defaultConfig = require_once dirname(dirname(dirname(__FILE__))).'/config/config.php';
        }

        // s'il n'y a pas eu de conf modifiée (avec function set), il faut assigner la config par défaut à la config à retourner
        if (self::$config === []) {
            self::$config = self::$defaultConfig;
        }

        return self::$config[$param] ?? self::$config;
    }
}
