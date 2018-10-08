<?php
/**
 * User    : Seyhan 'sTaRs' YILDIZ
 * Mail    : syhnyldz@gmail.com
 * Company : Digital Panzehir
 * Web     : www.digitalpanzehir.com
 * Date    : 3/12/2018
 * Time    : 11:59 AM
 */

namespace Zehir\System;

use Zehir\Settings\Setup;
use Zehir\Filters;
use Illuminate\Database\Capsule\Manager as DB;

class App
{
    public static $db;

    public static $param = null;

    public static $json, $isBot, $isMobile, $isAjax, $post, $get, $files, $view;

    public static $havePost = false;

    public static $controller;
    private static $activeDir;

    private static $data = [];

    public static $message;


    private static function check_ajax()
    {
        if (@$_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            return true;
        }
        return false;
    }

    public static function returnData()
    {
        return self::$data;
    }

    public static function redirect($link)
    {
        header('location:' . self::getData('basePath') . $link);
        self::processHalt();
    }


    public static function assign($key, $value)
    {
        self::$data[$key] = $value;
    }

    private static function check_bot()
    {
        return self::check_array_in_uagent(Setup::$clawler_list);
    }

    private static function check_mobile()
    {
        return self::check_array_in_uagent(Setup::$mobile_list);
    }

    private static function setupCORS()
    {
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:GET,PUT,POST,DELETE,OPTIONS');
        header('Access-Control-Allow-Headers:Origin, X-Requested-With, Content-Range,Content-Disposition, Content-Type, Authorization');
        header('Access-Control-Allow-Credentials:true');
    }

    public static function loadController()
    {
        $params = func_get_args();
        if (!isset($params[0]))
            return false;
        $controller = $params[0];
        array_shift($params);
        include self::$activeDir . $controller . '.php';
        $object = new $controller($params);
        return $object;
    }

    private static function connect()
    {
        if (!self::$db) {
            self::$db = new DB;
            self::$db->addConnection([
                'driver' => DB_ADAPTER,
                'host' => DB_HOST,
                'database' => DB_NAME,
                'username' => DB_USER,
                'password' => DB_PASSWORD,
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix' => Setup::$prefix,
            ], 'default');
            self::$db->setAsGlobal();
            //self::$db->setFetchMode(PDO::FETCH_ASSOC);
            self::$db->bootEloquent();
        }
    }

    private static function check_array_in_uagent($array)
    {
        $agent = null;

        if (isset ($_SERVER ['HTTP_USER_AGENT'])) {
            $agent = $_SERVER ['HTTP_USER_AGENT'];
        }

        str_replace($array, NULL, $agent, $count);
        if ($count > 0) {
            return TRUE;
        }
        return FALSE;
    }

    private static function trade($type = 'GET', $array)
    {
        if (!is_array((array)$array))
            return;

        $key = array_keys($array);
        $size = sizeof($key);


        if ($type == 'GET') {
            self::$get = new self ();
            for ($i = 0; $size > $i; $i++) {
                $nKey = $key [$i];
                self::$get->$nKey = $array [$key [$i]];
            }
        } else {
            self::$post = new self ();

            if ($size > 0)
                self::$havePost = true;

            for ($i = 0; $size > $i; $i++) {
                $nKey = $key[$i];
                self::$post->$nKey = $array [$key [$i]];
            }

        }
    }

    public static function setSession($key, $val)
    {
        $_SESSION[Setup::$session_key][$key] = $val;
    }

    public static function getSession($key)
    {
        return isset($_SESSION[Setup::$session_key][$key]) ? $_SESSION[Setup::$session_key][$key] : false;
    }

    public static function checkCSRF()
    {
        if (!@$_SESSION[Setup::$session_key]['CSRF']) {
            self::updateCSRF();
        }
        self::assign('__csrf', $_SESSION[Setup::$session_key]['CSRF']);
        if (@$_SESSION[Setup::$session_key]['CSRF'] != self::getParam('__csrf')) {
            return false;
        }
        return true;
    }

    public static function updateCSRF()
    {
        $csrf = self::generateCSRF();
        $_SESSION[Setup::$session_key]['CSRF'] = $csrf;
        self::assign('__csrf', $csrf);
    }

    private static function generateCSRF()
    {
        return 'z' . chr(rand(65, 90)) . rand(0, 9) . chr(rand(65, 90)) . chr(rand(65, 90)) . rand(0, 9) . rand(0, 9) . rand(0, 9) . chr(rand(65, 90)) . rand(0, 9) . rand(0, 9) . chr(rand(65, 90));
    }

    public static function getData($key)
    {
        return isset(self::$data[$key]) ? self::$data[$key] : false;
    }

    public static function getParam($name)
    {
        if (isset(app::$post->$name) && app::$post->$name)
            return app::$post->$name;

        if (isset(app::$json->$name) && app::$json->$name)
            return app::$json->$name;

        return null;
    }

    private static function setupCheck()
    {
        session_start();

        self::$isAjax = self::check_ajax();
        self::$isBot = self::check_bot();
        self::$isMobile = self::check_mobile();

        self::$json = json_decode(file_get_contents('php://input'));

        if (self::$json) {
            self::$havePost = true;
        }


        // aktarmalar
        self::trade('GET', $_GET);
        self::trade('POST', $_POST);
        self::$files = $_FILES;
        self::checkCSRF();
        // silmeler
        unset ($_FILES);
        unset ($_POST);
        unset ($_GET);
    }

    /**
     * @param $data [Array]
     */
    public static function json($data)
    {
        echo json_encode($data, JSON_NUMERIC_CHECK);
        self::processHalt();
    }

    public static function processHalt()
    {
        if (Cache::$client) {
            Cache::$client->quit();
        }
        exit();
    }

    public static function getIP()
    {
        if (getenv("HTTP_CLIENT_IP")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } elseif (getenv("HTTP_X_FORWARDED_FOR")) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
            if (strstr($ip, ',')) {
                $tmp = explode(',', $ip);
                $ip = trim($tmp[0]);
            }
        } else {
            $ip = getenv("REMOTE_ADDR");
        }
        return $ip;
    }

    public static function loadHMVC()
    {
        $realPath = base . '/' . Setup::$appDir . '/';
        if (Router::$path) {
            $realPath .= Router::$path . '/';
        }

        //modelleri yükle
        $files = glob(base . '/' . Setup::$appDir . '/models/*.php');
        $c = count($files);

        for ($i = 0; $i < $c; $i++) {
            include $files[$i];
        }

        self::$activeDir = $realPath . 'controllers/';

        //yüklenecek controller dosyasının tanımlanması
        $controllerFile = $realPath . 'controllers/' . Router::$controller . '.php';

        self::assign('controller', Router::$controller);

        self::$view = Router::$controller;

        //ilgili bootstrap dosyasının çalıştırılması
        if (file_exists($realPath . 'start.php')) {
            include $realPath . 'start.php';
        }

        if (Setup::$CORS)
            self::setupCORS();


        // ilgili controller dosyasının yüklenmesi
        if (!file_exists($controllerFile)) {

            $mainController = $realPath . 'controllers/main.php';

            if (file_exists($mainController)) {
                self::$view = 'main';
                include $mainController;
                self::$controller = new  \main();
            } else {
                self::$message = 'Controller Not Found:' . $controllerFile;
                return false;
            }

        } else {

            include $controllerFile;
            self::$controller = new  Router::$controller();
        }

        // nesne yüklenip sonuclandıktan sonra yüklenen
        if (file_exists($realPath . 'end.php')) {
            include $realPath . 'end.php';
        }
        //template engine in karar verilip yüklenmesi
        if (Setup::$template_engine) {
            $loader = new \Twig_Loader_Filesystem($realPath . 'views');
            $twig = new \Twig_Environment($loader, array(
                'cache' => base . '/' . Setup::$cacheDir,
                'auto_reload' => true
            ));
            $twig->addFilter(
                new \Twig_SimpleFilter('seo', function ($string) {
                    return Filters::seo($string);
                })
            );
            $twig->addFilter(
                new \Twig_SimpleFilter('markdown', function ($string) {
                    $markDown = new \Parsedown();
                    $markDown->setSafeMode(true);
                    return $markDown->parse($string);
                },[ 'is_safe' => ['all']])
            );
            try {
                return $twig->render(self::$view . '.phtml', self::$data);
            } catch (\Twig_Error $exception) {
                self::$message = $exception->getMessage();
                return false;
            }
        }

    }

    public static function run($params = null)
    {
        // charset
        header('Content-Type: text/html; charset=UTF-8');

        //veritabanı ayarlarını yükle
        Setup::database();

        //ORM i ayarla
        self::connect();

        //checks
        self::setupCheck();

        //router'dan parametreleri al
        Router::up();

        if (Setup::$routeDB) {
            $dbRouter = Router::search(Router::$controller);
            if ($dbRouter) {
                App::$param = $dbRouter['param'];
                Router::$controller = $dbRouter['controller'];
            }
        }

        $result = self::loadHMVC();
        if ($result) {
            echo $result;
        }
        self::processHalt();

    }

    public static function layout($layout, $data = [])
    {

        $layoutDir = base . '/' . Setup::$appDir . '/views/';

        $loader = new \Twig_Loader_Filesystem($layoutDir);
        $twig = new \Twig_Environment($loader, array(
            'cache' => false,
            'auto_reload' => true
        ));
        $twig->addFilter(
            new \Twig_SimpleFilter('seo', function ($string) {
                return Filters::seo($string);
            }));

        try {
            return $twig->render($layout . '.phtml', $data);
        } catch (\Twig_Error $e) {
            return false;
        }


    }
}