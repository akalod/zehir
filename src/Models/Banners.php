<?php
/**
 * User    : Seyhan 'sTaRs' YILDIZ
 * Mail    : syhnyldz@gmail.com
 * Company : Digital Panzehir
 * Web     : www.digitalpanzehir.com
 * Date    : 10/17/2018
 * Time    : 11:51
 */

namespace Zehir\Models;

Use Illuminate\Database\Capsule\Manager as DB;

class Banners extends Generic
{
    private static $table = 'banners';

    const RESPONSIVE = 1;
    const DESKTOP = 2;
    const MOBILE = 3;

    public static function getAll($viewable = self::RESPONSIVE, $langId = 0, $permission = false)
    {

        $q = DB::table(self::$table);

        if ($langId) {
            $q->where('langId', $langId);
        }

        if ($viewable == self::MOBILE) {
            $q->whereIn('viewable', ['responsive', 'mobile']);
        } else if ($viewable == self::DESKTOP) {
            $q->whereIn('viewable', ['responsive', 'desktop']);
        } else if ($viewable == self::RESPONSIVE) {
            $q->whereIn('viewable', ['responsive']);
        }

        if (!$permission) {
            $q->where('status', 1);
        }

        return $q->orderBy('short', 'asc')->get();
    }

}