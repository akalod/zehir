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

        $runningPath = explode('/', $_SERVER['SCRIPT_NAME']);
        array_pop($runningPath);

        $runningPath = implode('/', $runningPath);

        $thisPage = substr(str_replace($runningPath, '', $_SERVER['REQUEST_URI']), 1);


        App::assign('thisPage', $thisPage);
        App::assign('basePath', $runningPath);
        return explode('/', $thisPage);
    }

    public static function search($key)
    {
        try {
            $select = ['param as id', 'seo', 'controller'];
            if (Setup::$multiLang)
                $select[] = 'lang_id';

            $query = DB::table('router')->select($select)->where('seo', $key);
            foreach (Setup::$search_extend as $k => $v) {
                $select = ['id', 'seo', DB::RAW('concat("' . $v . '") as controller')];
                if (Setup::$multiLang)
                    $select[] = 'lang_id';

                $query = $query->union(DB::table($k)->select($select)->where('seo', $key));
            }
            $r = $query->first();
            return $r ? [
                'controller' => $r->controller,
                'param' => $r->id,
                'lang_id' => Setup::$multiLang ? $r->lang_id : 0]
                : false;

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
