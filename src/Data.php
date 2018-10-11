<?php
/**
 * User    : Seyhan 'sTaRs' YILDIZ
 * Mail    : syhnyldz@gmail.com
 * Company : Digital Panzehir
 * Web     : www.digitalpanzehir.com
 * Date    : 10/11/2018
 * Time    : 14:00
 */

namespace Zehir\System;

use Phalcon\Mvc\Model\Transaction\Exception;
Use Symfony\Component\Yaml\Yaml;
use Zehir\Settings\Setup;

class Data
{
    public static $data;
    public static $hasError;

    public function __construct()
    {
        $files = glob(base . '/' . Setup::$dataDir . '/*.yml');
        foreach ($files as $f) {
            $name = str_replace([base . '/' . Setup::$dataDir . '/', '.yml'], '', $f);
            $this->$name = (object)Yaml::parseFile($f);
        }
    }

    /**
     * @param $file
     * @param $data
     * @return string
     */
    public static function get($file, $data)
    {
        if (isset(self::$data->$file)) {
            $r = isset(self::$data->$file->$data) ? self::$data->$file->$data : null;
            if ($r) return $r;
            self::$hasError = "Data Layout($file) içerisinde Pointer($data) bulunamadı";
            return "";
        }
        if (Setup::$target != 'prod')
            self::$hasError = "Data Layout($file) bulunamadı.";
        return "";
    }

    /**
     * @param $file
     * @param $data
     * @return string
     */
    public static function worked($file, $data)
    {
        $args = func_get_args();
        array_shift($args);
        array_shift($args);

        try {
            $result = vsprintf(self::get($file, $data), $args);
        } catch (\Exception $e) {
            if (Setup::$target != 'prod') {
                throw new $e;
            }
            return "";
        }
        return $result;
    }

    /**
     * startta başlatılmıştı bu aşağıdaki arkadaş bunsuz diğer valuelar staticten erişlemez olup constructer ile tekrar oluşması gerekir
     */
    public static function setup()
    {
        self::$data = new self();
    }

    public static function getLangById($langId)
    {
        foreach (Setup::$enableLanguages as $l) {
            if ($l['id'] == $langId) {
                return $l['lang'];
                break;
            }
        }
        return false;
    }

    public static function loadLang($langId = null)
    {

        if (!$langId)
            $langId = Setup::$langId;

        $lang = self::getLangById($langId);
        $langData = null;
        try {
            $langData = Yaml::parseFile(base . '/' . Setup::$langDir . '/' . strtolower($lang) . '.yml');
        } catch (\Symfony\Component\Yaml\Exception\ParseException $e) {
            if (Setup::$target != 'prod') {
                die($e->getMessage());
            }
        }
        App::assign('lang', (object)$langData);

    }
}