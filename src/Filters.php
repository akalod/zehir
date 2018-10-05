<?php
/**
 * User    : Seyhan 'sTaRs' YILDIZ
 * Mail    : syhnyldz@gmail.com
 * Company : Digital Panzehir
 * Web     : www.digitalpanzehir.com
 * Date    : 3/12/2018
 * Time    : 3:35 PM
 */

namespace Zehir;


class Filters
{
    public static function seo($link)
    {
        // seo link hazırlamak için tr karakter encode
        $array = Array(
            'Ü' => 'u',
            'İ' => 'i',
            'Ş' => 's',
            'Ğ' => 'g',
            'Ö' => 'o',
            'Ç' => 'c',
            'ü' => 'u',
            'ı' => 'i',
            'ş' => 's',
            'ğ' => 'g',
            'ö' => 'o',
            'ç' => 'c',
            'Ãœ' => '',
            ' ' => '-',
            '?' => '',
            '_' => '-',
            '---' => '-',
            '--' => '-',
            '\\' => null,
            '=' => null,
            '&' => null,
            '+' => null
        );
        $keys = array_keys($array);
        $c = count($array);

        for ($i = 0; $c > $i; $i++) {
            $link = str_replace($keys [$i], $array [$keys [$i]], $link);
        }


        $link = strtolower($link);

        return $link;
    }

    public static function calcCurrency($d = 0)
    {
        return number_format(floor($d * 100) / 100, 2, '.', '');
    }
}