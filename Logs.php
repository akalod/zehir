<?php
/**
 * User    : Seyhan 'sTaRs' YILDIZ
 * Mail    : syhnyldz@gmail.com
 * Company : Digital Panzehir
 * Web     : www.digitalpanzehir.com
 * Date    : 10/3/2018
 * Time    : 15:14
 */

namespace Zehir\System;

use Illuminate\Database\Capsule\Manager as DB;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;


class Logs
{

    const LOGIN = 'LOGIN';
    const ADD_CATEGORY = 'CATEGORY_ADD';
    const EDIT_CATEGORY = 'CATEGORY_EDIT';
    const DEL_CATEGORY = 'CATEGORY_REMOVE';
    const CHANGE_PASSWORD = 'PASSWORD_SET';


    /**
     * @param $do
     * @param null $what
     * @param null $level
     * @param null $who
     * @param null $storage
     * @return bool
     */
    public static function manuel($do, $what = null, $level = null, $who = null, $storage = null)
    {
        $ipx = App::getIP();
        $data = [
            'what' => $what,
            'do' => $do,
            'level' => $level ? $level : 0,
            'who' => $who ? $who : 0,
            'storage' => $storage,
            'ipx' => $ipx
        ];

        try {
            DB::table('logs_system')
                ->insert($data);
        } catch (\PDOException $e) {
            try {
                $handler = new StreamHandler(base . '/_logs/system.txt');
            } catch (\Exception $e) {
                return false;
            }
            if (isset($handler) && $handler) {
                $log = new Logger('SYSTEM');
                $log->pushHandler($handler);
                $log->addError(json_encode($data));
            }
        }
    }

    public static function login($me, $level = -1)
    {
        self::manuel(self::LOGIN, null, $level, $me);
    }

    public static function addCategory($catID, $me, $level = -1)
    {
        self::manuel(self::ADD_CATEGORY, $catID, $level, $me);
    }

    public static function removeCategory($catID, $me, $level = -1, $storage = null)
    {
        self::manuel(self::DEL_CATEGORY, $catID, $level, $me, $storage);
    }

    public static function editCategory($catID, $categoryName, $me, $level = -1)
    {
        self::manuel(self::EDIT_CATEGORY, $catID, $level, $me, $categoryName);
    }

    public static function changePassword($me, $oldPassword, $level = -1, $who = null, $actionLevel = null)
    {
        if (!$who) {
            $who = $me;
            $actionLevel = $level;
        }
        $what = json_encode([
            'myself' => ($me == $who && $level == $actionLevel) ? true : false,
            'editedID' => $who,
            'level' => $actionLevel
        ]);
        self::manuel(self::CHANGE_PASSWORD, $what, $level, $me, $oldPassword);
    }

}