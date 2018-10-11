<?php
/**
 * User    : Seyhan 'sTaRs' YILDIZ
 * Mail    : syhnyldz@gmail.com
 * Company : Digital Panzehir
 * Web     : www.digitalpanzehir.com
 * Date    : 10/11/2018
 * Time    : 11:20
 */

namespace Zehir\Migrations;

use Illuminate\Database\Capsule\Manager as DB;
use Zehir\Settings\Setup;


class Structure
{
    private static function pages(){
        DB::schema()->create('pages', function ($table) {
            $table->increments('id');
            $table->string('seo')->unique();
            $table->string('title');
            $table->string('pageTitle')->nullable();
            $table->string('metaDescription')->nullable();
            $table->string('metaKeywords')->nullable();
            $table->longText('content')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->smallInteger('langId')->default(0);
        });
    }

    private static function router()
    {
        DB::schema()->create('router', function ($table) {
            $table->increments('id');
            $table->string('seo')->unique();
            $table->string('controller');
            $table->string('param')->nullable();
            $table->smallInteger('langId')->default(0);
        });
    }

    public static function create()
    {
        foreach (Setup::$installParameters as $k) {
            if (method_exists(__CLASS__, $k))
                self::$k();
        }
        self::router();
    }

}