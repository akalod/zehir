<?php
/**
 * User    : Seyhan 'sTaRs' YILDIZ
 * Mail    : syhnyldz@gmail.com
 * Company : Digital Panzehir
 * Web     : www.digitalpanzehir.com
 * Date    : 10/17/2018
 * Time    : 11:46
 */

namespace Zehir\Models;

Use Illuminate\Database\Capsule\Manager as DB;

class Generic
{
    private static $table = 'pages';

    public static function update($id, $data)
    {
        return DB::table(self::$table)
            ->where('id', $id)
            ->update($data);
    }

    public static function delete($pageId)
    {
        return DB::table(self::$table)
            ->delete($pageId);
    }

    public static function get($id, $permission = false)
    {
        $q = DB::table(self::$table);

        if (!$permission) {
            $q->where('status', 1);
        }

        return $q->where('id', $id)
            ->first();
    }


}