<?php
/**
 * User    : Seyhan 'sTaRs' YILDIZ
 * Mail    : syhnyldz@gmail.com
 * Company : Digital Panzehir
 * Web     : www.digitalpanzehir.com
 * Date    : 10/1/2018
 * Time    : 11:17
 */

namespace Zehir\System;

use Zehir\Settings\Setup;
use Predis;

class Cache
{
    private static $cacheFolder = '/shared/';
    const SHARED = 0; //local
    const UNIQUE = 1; //redis-server

    public static $client = null;

    public static function check($name, $type = self::SHARED)
    {
        if ($type == self::SHARED) {
            $fn = base . '/' . Setup::$cacheDir . self::$cacheFolder . $name;
            if (!file_exists($fn))
                return false;

            if (filemtime($fn) + Setup::$cacheTime < (int)date('U'))
                return false;
        }
        if ($type == self::UNIQUE) {
            self::redisCheckAndSet();
            if (!self::$client->get($name)) {
                return false;
            }
            return json_decode(self::$client->get($name));
        }
        return true;

    }

    public static function load($name, $type = self::SHARED)
    {
        if ($type == self::SHARED)
            return json_decode(file_get_contents(base . '/' . Setup::$cacheDir . self::$cacheFolder . $name));
        if ($type == self::UNIQUE) {
            self::redisCheckAndSet();
            return self::check($name, $type);
        }
    }

    public static function redisCheckAndSet()
    {
        if (!self::$client) {
            Predis\Autoloader::register();
            $values = Setup::getConnectionsSettings();
            self::$client = new Predis\Client(array(
                "scheme" => "tcp",
                "host" => $values['redis_server'] ? $values['redis_server'] : 'localhost',
                "port" => $values['redis_port'] ? $values['redis_port'] : 6379
            ));
            if (isset($values['redis_auth'])) {
                self::$client->auth($values['redis_auth']);
            }
        }
    }

    public static function make($name, $data, $type = self::SHARED, $time = null)
    {
        if (!$time) {
            $time = Setup::$cacheTime;
        }
        if ($type == self::SHARED)
            file_put_contents(base . '/' . Setup::$cacheDir . self::$cacheFolder . $name, json_encode($data));
        if ($type == self::UNIQUE) {
            self::redisCheckAndSet();
            self::$client->set($name, json_encode($data));
            self::$client->expire($name, $time);
        }
    }

}

