<?php

namespace DawPhpPagination\Support\Request\Bags;

/**
 * @link     https://github.com/dev-and-web/daw-php-pagination
 * @author   Stephen Damian <contact@devandweb.fr>
 * @license  MIT License
 */
class ParameterBag
{
    /**
     * Parameter storage.
     *
     * @var array
     */
    private array $parameters;

    /**
     * ParameterBag Constructor.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }
    
    /**
     * @param string $key - Le name
     * @return bool - True si le paramètre existe
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->parameters);
    }
    
    /**
     * Retourne un paramètre par son nom
     *
     * @param string $key - Le name
     * @param mixed $default - La valeur par défault si le paramètre n'existe pas
     * @return mixed
     */
    public function get(string $key, $default = '')
    {
        return $this->has($key) ? $this->parameters[$key] : $default;
    }
}
