<?php
/**
 * Created by PhpStorm.
 * User: stars
 * Date: 16.10.2018
 * Time: 22:22
 */

namespace Zehir\Controllers;


use Zehir\System\App;
use Zehir\Models\Pages;

class Page
{
    public $data;

    function __construct()
    {
        if (App::$param) {
            $this->data = Pages::getPage(App::$param);
            App::assign('data', $this->data);
            if (isset($this->data->pageTitle)) {
                App::assign('pageTitle', $this->data->pageTitle);
            }
        }
    }
}