<?php
/**
 * User    : Seyhan 'sTaRs' YILDIZ
 * Mail    : syhnyldz@gmail.com
 * Company : Digital Panzehir
 * Web     : www.digitalpanzehir.com
 * Date    : 10/11/2018
 * Time    : 10:05
 */
use  Zehir\Settings\Setup;
use  Zehir\System\App;

include "vendor/autoload.php";

Setup::$noSQL = false;
Setup::configure([
    'test' => Array(
        'host' => 'localhost',
        'name' => 'dumb_table',
        'user' => 'root',
        'pass' => 'root',
        'port' => 3306,
        'adapter' => 'mysql',
        'mq'=>[
            'user'=>'panzehir',
            'pass'=>'panzehir'
        ]
    )]);
print_r(Setup::getConnectionsSettings());
Setup::$enableLanguages[]=['id'=>2,'lang'=>'EN'];
Setup::$enableLanguages[]=['id'=>3,'lang'=>'AR'];
Setup::$installParameters=['news','banners'];
App::run();