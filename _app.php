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

Setup::$noDatabase = false;
Setup::configure([
    'test' => Array(
        'host' => 'demo.digitalpanzehir.com',
        'name' => 'dp_eruslu',
        'user' => 'dp_eruslu',
        'pass' => 'lvIeF0AoiI',
        'port' => 3306,
        'adapter' => 'mysql'
    )]);
Setup::$enableLanguages[]=['id'=>2,'lang'=>'EN'];
App::run();