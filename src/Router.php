<?php
/**
 * User    : Seyhan 'sTaRs' YILDIZ
 * Mail    : syhnyldz@gmail.com
 * Company : Digital Panzehir
 * Web     : www.digitalpanzehir.com
 * Date    : 3/12/2018
 * Time    : 11:39 AM
 */

namespace Zehir\System;

use Zehir\Settings\Setup;
use Illuminate\Database\Capsule\Manager as DB;

class Router
{
    public static $controller;
    public static $function;
    public static $path;
    public static $params;
    public static $param;

    public static function getURL()
    {
        return implode('/', self::getParams());
    }

    public static function getParams()
    {
        $thisPage = substr(str_replace($_SERVER['SCRIPT_NAME'], null, $_SERVER['PHP_SELF']), 1);

        App::assign('thisPage', $thisPage);
        App::assign('basePath', str_replace('public/' . basename($_SERVER["SCRIPT_FILENAME"]), '', $_SERVER['SCRIPT_NAME']));
        return explode('/', $thisPage);
    }

    public static function search($key)
    {
        try {
            $query = DB::table('router')->select(['param as id', 'seo', 'controller'])->where('seo', $key);
            foreach (Setup::$search_extend as $k => $v) {
                $query = $query->union(DB::table($k)->select(['id', 'seo', DB::RAW('concat("' . $v . '") as controller')])->where('seo', $key));
            }
            $r = $query->first();
            return $r ? ['controller' => $r->controller, 'param' => $r->id] : false;
        } catch (\PDOException $e) {
            $code = $e->getCode();
            if ($code == '1044') {
                //erişim izini yok;
                $view = 'db-permission';
            } else {
                //yoğunluk
                $view = 'db-down';
            }
            if (Setup::$template_engine) {
                $loader = new \Twig_Loader_Filesystem(base . '/' . Setup::$appDir . '/views');
                $twig = new \Twig_Environment($loader, array(
                    'cache' => base . '/' . Setup::$cacheDir,
                    'auto_reload' => true
                ));
                try {
                    return $twig->render($view . '.phtml', ['ecode' => $code]);
                } catch (\Twig_Error $exception) {
                    return false;
                }
            } else {
                App::json(['Status' => false, 'ErrorCode' => $code]);
            }
            App::processHalt();
        }

    }

    public static function up()
    {
        self::$params = self::getParams();
        $PN = 0;
        if (isset(Setup::$bundles[strtolower(self::$params[$PN])])) {
            self::$path = Setup::$bundles[strtolower(self::$params[$PN])];
            $PN = 1;
        }
        if (isset(self::$params[$PN]) && self::$params[$PN]) {
            self::$controller = self::$params[$PN];
        } else {
            self::$controller = Setup::$mainController;
        }
        if (isset(self::$params[$PN + 1])) self::$function = self::$params[$PN + 1];
        if (isset(self::$params[$PN + 2])) self::$param = self::$params[$PN + 2];


    }
}