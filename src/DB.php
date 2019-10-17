<?php
/**
 * Created by PhpStorm.
 * User: sTaRs
 * Date: 16/10/19
 * Time: 10:19
 */

namespace Zehir\System;

use Illuminate\Database\Query\Builder;

class DB extends \Illuminate\Database\Capsule\Manager
{

    public static $totalQuery = 0;

    public static function table($table, $connection = null)
    {
        self::$totalQuery++;
        return parent::table($table, $connection);
    }

    /**
     * @param Builder $db
     * @param $column --nerede
     * @param $object --objecnin ementi
     * @param $cond -- koşul
     * @param null $val --değer
     * --- değer yoksa koşul eşit olup değer koşuldaki olarak çalışır (kısa kullanım)
     * @return Builder
     */
    public static function jsonWhere(Builder &$db, $column, $object, $cond, $val = null)
    {
        if (!$val && $cond) {
            $val = $cond;
            $cond = '=';
        }
        if (!is_numeric($val)) {
            $val = "'" . str_replace("'", "\'", $val) . "'";
        }
        return $db->whereRaw("json_extract($column, '$.$object') $cond $val");
    }

    /**
     * karakter katarlarını temizleyerek geri döndürür
     * JSON objectin sonucunu direk alabilmek için  $.test.name yerine
     * test.name yapılabilir yazım methodu basitleştirmesi
     * @param $column
     * @param $select
     * @return mixed
     */
    public static function jsonSelect($column,$select)
    {
        return DB::RAW('JSON_UNQUOTE(json_extract('.$column.',\'$.' . $select . '\'))');
    }

    public static function jsonWhereInArray(Builder &$db, $column, $array, $val)
    {
        if (!is_numeric($val)) {
            $val = "'" . str_replace("'", "\'", $val) . "'";
        }
        return $db->whereRaw("
        JSON_SEARCH(
            json_extract($column, '$.$array')
            ,'all',$val)
        ");
    }

}