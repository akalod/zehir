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
    private static $table;

    private static function banners()
    {
        DB::schema()->create(self::$table, function ($table) {
            $table->increments('id');
            $table->string('src');
            $table->string('text')->nullable();
            $table->string('link')->nullable();
            $table->string('langId')->default(0);
            $table->string('btn_text')->nullable();
            $table->enum('type', ['img', 'iframe', 'video'])->default('img');
            $table->enum('viewable', ['responsive', 'mobile', 'desktop'])->default('responsive');
            $table->enum('target', ['_self', '_blank', '_parent', '_top'])->default('_self');
            $table->integer('short')->default(0);
        });
    }

    private static function news()
    {
        DB::schema()->create(self::$table, function ($table) {
            $table->increments('id');
            $table->string('seo')->unique();
            $table->string('image')->nullable();
            $table->string('title');
            $table->string('page_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->text('summary')->nullable();
            $table->longText('content')->nullable();
            $table->text('js_body')->nullable();
            $table->timestamp('date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->tinyInteger('status')->default(0);
            $table->smallInteger('langId')->default(0);
        });
    }

    private static function pages()
    {
        DB::schema()->create(self::$table, function ($table) {
            $table->increments('id');
            $table->string('seo')->unique();
            $table->string('title');
            $table->string('page_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->longText('content')->nullable();
            $table->text('js_body')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->smallInteger('langId')->default(0);
            $table->smallInteger('group')->default(0);
            $table->integer('short')->default(0);
        });
    }

    private static function router()
    {
        $table = 'router';
        if (!DB::Schema()->hasTable($table)) {
            DB::schema()->create($table, function ($table) {
                $table->increments('id');
                $table->string('seo')->unique();
                $table->string('controller');
                $table->string('param')->nullable();
                $table->smallInteger('langId')->default(0);
            });
        }
    }

    public static function create()
    {
        echo '<pre>';
        foreach (Setup::$installParameters as $k) {
            self::$table = $k;
            if (method_exists(__CLASS__, $k) && !DB::Schema()->hasTable(self::$table)) {
                self::$k();
                echo "\n$k";
            }
        }
        self::router();
        echo "\nDONE;</pre>";
    }

}