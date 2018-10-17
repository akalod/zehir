<?php
/**
 * Created by PhpStorm.
 * User: stars
 * Date: 16.10.2018
 * Time: 22:22
 */

namespace Zehir\Controllers;


use Zehir\System\App;
use Zehir\Models\News as N;

class News
{
    public $data;

    function __construct()
    {
        if (App::$param) {
            $this->data = N::get(App::$param);
            App::assign('data', $this->data);
            if (isset($this->data->page_title)) {
                App::assign('pageTitle', $this->data->page_title);
            }
            if (isset($this->data->meta_keywords)) {
                App::assign('metaKeywords', $this->data->meta_keywords);
            }
            if (isset($this->data->meta_description)) {
                App::assign('metaDescription', $this->data->meta_description);
            }
        }
    }
}