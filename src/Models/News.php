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
    static $table = 'news';

    public static function getLast($langId = 0, $limit = 10)
    {
        return self::getAll($langId, null, $limit);
    }

    public static function getAll($langId = 0, $column = null, $limit = null, $permission = false)
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
        if ($limit) {
            $q->limit($limit);
        }

        return $q->orderBy('date', 'desc')->get($column);
    }
}