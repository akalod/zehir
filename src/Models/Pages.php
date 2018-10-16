<?php
namespace Zehir\Models;

Use Illuminate\Database\Capsule\Manager as DB;

class Pages
{

    private static $table = 'pages';

    public static function getPage($id)
    {
        return DB::table(self::$table)
            ->where('status', 1)
            ->where('id', $id)
            ->first();
    }

    public static function deletePage($pageId)
    {
        return DB::table(self::$table)
            ->delete($pageId);
    }

    public static function updatePage($pageId, $data)
    {
        return DB::table(self::$table)
            ->where('id', $pageId)
            ->update($data);
    }

    public static function getGroupPages($groupId, $langId = 1)
    {
        return DB::table(self::$table)
            ->where('status', 1)
            ->where('group', $groupId)
            ->where('langId', $langId)
            ->orderBy('short', 'asc')
            ->get(['id', 'seo', 'title']);
    }

}
