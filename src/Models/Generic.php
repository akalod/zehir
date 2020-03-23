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

Use Zehir\System\DB;

class Generic
{
    static $table = 'pages';

    public static function update($id, $data)
    {
        return DB::table(static::$table)
            ->where('id', $id)
            ->update($data);
    }

    public static function delete($pageId)
    {
        return DB::table(static::$table)
            ->delete($pageId);
    }

    public static function get($id, $permission = false)
    {
        $q = DB::table(static::$table);

        if (!$permission) {
            $q->where('status', 1);
        }

        return $q->where('id', $id)
            ->first();
    }

    public static function insert($data){
        return DB::table(static::$table)->insertGetId($data);
    }


}