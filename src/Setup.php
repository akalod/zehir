<?php
/**
 * User    : Seyhan 'sTaRs' YILDIZ
 * Mail    : syhnyldz@gmail.com
 * Company : Digital Panzehir
 * Web     : www.digitalpanzehir.com
 * Date    : 10/2/2018
 * Time    : 11:50
 */

namespace Zehir\Settings;


class Setup
{
    public static $multiLang = false;
    public static $redis = false;
    public static $noSQL = false;
    public static $useSession = true;
    public static $isResponsiveDesign = true;
    public static $cacheTime = 500;
    public static $target = 'test';
    public static $panelLink = 'zehir/';
    public static $webUrl = 'http://dev.localhost/';
    public static $CORS = false;
    public static $template_engine = true;
    public static $bundles = [];
    public static $prefix;
    public static $session_key = "pTH7nUV4EqxU";
    public static $appDir = '_app';
    public static $cacheDir = '_cache';
    public static $langDir = '_lang';
    public static $dataDir = '_data';
    public static $mobileDir = 'mobile';
    public static $enableLanguages = [['id' => 1, 'lang' => 'TR']];
    public static $langId = 1;
    public static $mainController = 'main';
    public static $routeDB = true;
    public static $TwigFilters = null;
    public static $disableServiceArea = false;
    public static $disablePlannedDelivery = false;
    public static $installParameters = ['pages'];
    /**
     * $routeDB açık ise search_extend devreye girecektir.
     * @var array [db.table.seo] => [controller.file]
     */
    public static $search_extend = [
        'pages' => 'page'
    ];

    public static $clawler_list = Array(
        'YandexBot',
        'Googlebot',
        'Yahoo'
    );

    public static $mobile_list = Array(
        'Android',
        'PlayBook',
        'Kindle',
        'Phone',
        'iPad'
    );

    public static $SMTP = [
        'Host' => 'mail.localhost',
        'SMTPAuth' => true,
        'Username' => 'info@mail.localhost',
        'Password' => 'cRxtTdIkxk',
        'Port' => 587,
        'DevMail' => 'seyhan@digitalpanzehir.com'
    ];

    private static $databaseSettings = [];
    private static $customSettings = [];

    private static function setGetTarget()
    {
        if (!isset(self::$databaseSettings[self::$target])) {
            self::$target = 'test';
        }
        return self::$target;
    }

    public static function getDBsettings($ENV)
    {
        self::$target = $ENV;
        self::setGetTarget();
        return self::$databaseSettings[self::$target];
    }

    public static function getConnectionsSettings()
    {
        return self::$databaseSettings[self::$target];
    }

    public static function addCustom($key, $val)
    {
        self::$customSettings[$key] = $val;
    }

    public static function custom($key)
    {
        if (isset(self::$customSettings[$key])) return self::$customSettings[$key];
        return false;
    }

    public static function database()
    {
        self::setGetTarget();

        if (!isset(self::$databaseSettings[self::$target])) {
            die('"' . self::$target . '" database settings doesn\'t set');
        }

        define('DB_ADAPTER', self::$databaseSettings[self::$target]['adapter']);
        if (isset(self::$databaseSettings[self::$target]['host']))
            define('DB_HOST', self::$databaseSettings[self::$target]['host']);
        if (isset(self::$databaseSettings[self::$target]['name']))
            define('DB_NAME', self::$databaseSettings[self::$target]['name']);
        if (isset(self::$databaseSettings[self::$target]['user']))
            define('DB_USER', self::$databaseSettings[self::$target]['user']);
        if (isset(self::$databaseSettings[self::$target]['pass']))
            define('DB_PASSWORD', self::$databaseSettings[self::$target]['pass']);
        if (isset(self::$databaseSettings[self::$target]['port']))
            define('DB_PORT', self::$databaseSettings[self::$target]['port']);
    }

    public static function configure($data)
    {
        self::$databaseSettings = $data;
    }
}
