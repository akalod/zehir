<?php

namespace Zehir\Models;

Use Illuminate\Database\Capsule\Manager as DB;

class Pages extends Generic
{
    static $table = 'pages';

    public static function getAll($langId = 0, $column = null, $permission = false)
    {
        if (!$column) {
            $column = ['id', 'seo', 'title', 'langId', 'group'];
        }
        $q = DB::table(self::$table);

        if ($langId) {
            $q->where('langId', $langId);
        }

        if (!$permission) {
            $q->where('status', 1);
        }

        return $q->orderBy('short', 'asc')->get($column);
    }

    public static function getGroupPages($groupId, $langId = 0, $permission = false)
    {
        $q = DB::table(self::$table)
            ->where('group', $groupId);

        if (!$permission) {
            $q->where('status', 1);
        }

        if ($langId) {
            $q->where('langId', $langId);
        }

        return $q->orderBy('short', 'asc')
            ->get(['id', 'seo', 'title']);
    }

}
