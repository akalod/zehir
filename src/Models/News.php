<?php
/**
 * User    : Seyhan 'sTaRs' YILDIZ
 * Mail    : syhnyldz@gmail.com
 * Company : Digital Panzehir
 * Web     : www.digitalpanzehir.com
 * Date    : 10/17/2018
 * Time    : 11:08
 */

namespace Zehir\Models;

Use Illuminate\Database\Capsule\Manager as DB;

class News extends Generic
{
    private static $table = 'news';

    public static function getAll($langId = 0, $column = null, $permission = false)
    {
        if (!$column) {
            $column = ['id', 'seo', 'image', 'summary', 'title', 'date'];
        }
        $q = DB::table(self::$table);

        if ($langId) {
            $q->where('langId', $langId);
        }

        if (!$permission) {
            $q->where('status', 1);
        }

        return $q->orderBy('date', 'desc')->get($column);
    }
}