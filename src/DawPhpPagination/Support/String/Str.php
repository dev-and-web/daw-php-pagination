<?php

namespace DawPhpPagination\Support\String;

use DawPhpPagination\Support\Facades\Input;

/**
 * @link     https://github.com/dev-and-web/daw-php-pagination
 * @author   Stephen Damian <contact@devandweb.fr>
 * @license  MIT License
 */
class Str
{
    /**
     * @param array|string $gets
     * @param $operatorParam|null
     * @return string
     */
    public static function andIfHasQueryParams($gets, $operatorParam = null): string
    {
        $var = '';

        if (is_array($gets)) {
            $i = 0;
            foreach ($gets as $get) {
                if (Input::hasGet($get)) {
                    $operator = ($i == 0 && $operatorParam != null) ? $operatorParam : '&amp;';
                    $var .= $operator.$get.'='. Input::get($get);
                    $i++;
                }
            }
        } else {
            if (Input::hasGet($gets)) {
                $operator = ($operatorParam != null) ? $operatorParam : '&amp;';
                $var .= $operator.$gets.'='. Input::get($gets);
            }
        }

        return $var;
    }

    /**
     * @param array|string $gets
     * @return string
     */
    public static function inputHiddenIfHasQueryParams($gets): string
    {
        $var = '';

        if (is_array($gets)) {
            foreach ($gets as $get) {
                if (Input::hasGet($get)) {
                    $var .= '<input type="hidden" name="'.$get.'" value="'.Input::get($get).'">';
                }
            }
        } else {
            if (Input::hasGet($gets)) {
                $var .= '<input type="hidden" name="'.$gets.'" value="'.Input::get($gets).'">';
            }
        }

        return $var;
    }
}
