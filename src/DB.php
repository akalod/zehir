<?php
/**
 * Created by PhpStorm.
 * User: sTaRs
 * Date: 16/10/19
 * Time: 10:19
 */

namespace Zehir\System;

class DB extends \Illuminate\Database\Capsule\Manager
{

    public static $totalQuery = 0;

    public static function table($table, $connection = null)
    {
        self::$totalQuery++;
        return parent::table($table, $connection);
    }

}