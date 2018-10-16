<?php
namespace Zehir\Controllers;

use Zehir\System\Router;
use Zehir\System\App;
use Zehir\Settings\Setup;

/**
 * Created by PhpStorm.
 * User: stars
 * Date: 16.10.2018
 * Time: 21:55
 */
class REST
{
    public $method;
    public $status = true;

    private function setParam()
    {
        $id = Router::$function;
        if ($id)
            $this->param = $id;
    }

    public function PUT()
    {
    }

    public function GET()
    {
    }

    public function POST()
    {

    }

    public function PATCH()
    {

    }

    public function DELETE()
    {

    }

    public function output()
    {
        App::json($this);
    }

    function __construct()
    {
        Setup::$template_engine = false;
        $this->method = strtoupper($_SERVER['REQUEST_METHOD']);
        $this->setParam();
        switch ($this->method) {
            case 'PUT':
                $this->PUT();
                break;
            case 'POST':
                $this->POST();
                break;
            case 'DELETE':
                $this->DELETE();
                break;
            case 'PATCH':
                $this->PATCH();
                break;
            default:
                $this->GET();
        }

    }

    function __destruct()
    {
        $this->output();
    }
}