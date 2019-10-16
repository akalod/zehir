<?php

namespace Zehir\CLI;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Zehir\Settings\Setup;

class generic extends Command
{

    const TYPE_CONTROLLER = 'controllers';
    const TYPE_MODEL = 'models';
    const TYPE_VIEW = 'views';
    const TYPE_PAGE = 'DEFAULT';
    const TYPE_API = 'REST';

    public static $defaultSettingFile = '_app.php';
    public static $defaultSettingFolder = '_app';

    protected static $io;

    public static function camelCaseToUnderscore($input)
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }

    public static function createFile($appDir, $name, $data, $type = self::TYPE_CONTROLLER)
    {
        $ext = self::TYPE_VIEW == $type ? '.phtml' : '.php';
        $file = $appDir . '/' . $type . '/' . $name . $ext;
        if (file_exists($file)) {
            $e = self::$io->confirm('Bu dosya mevcut üzerine yazmak ister misiniz?', true);
        }
        if (!isset($e) || $e) {
            file_put_contents($file, $data);
        }
    }

    public static function createController($name, $appDir = '_app', $type = self::TYPE_PAGE)
    {
        $name = self::camelCaseToUnderscore($name);
        $extend = '';
        $parent = '';
        if (strtoupper($type) == self::TYPE_API) {
            $extend = ' extends Zehir\Controllers\REST ';
            $parent = 'parent::__construct();';
        }
        $data = <<<DATA
<?php
Use Zehir\System\App;
Use Zehir\Settings\Setup;

class $name $extend
{
    function __construct()
    {
        $parent
    }
}
DATA;

        self::createFile($appDir, $name, $data);
    }

    public static function createModel($name, $appDir = '_app')
    {

        $name = self::camelCaseToUnderscore($name);
        $data = <<<DATA
<?php
namespace Models;

use Zehir\System\DB;


class $name
{
    function __construct()
    {
        
    }
}
DATA;
        self::createFile($appDir, $name, $data, self::TYPE_MODEL);
    }

    public static function createView($name, $appDir = '_app')
    {
        $data = <<<DATA
<html>
<head>
<title>{{title}}</title>
</head>
<body>
{{content}}
<h2>TWIG TE</h2>
</body>
</html>
DATA;
        self::createFile($appDir, $name, $data, self::TYPE_VIEW);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        self::$io = new SymfonyStyle($input, $output);

        if ($input->getOption('settings') && self::$defaultSettingFile != $input->getOption('settings')) {
            if (!file_exists($input->getOption('settings')))
                self::$io->error('Ayar Dosyası Bulunamadı!!');
            else {
                self::$defaultSettingFile = $input->getOption('settings');
                include self::$defaultSettingFile;
                self::$defaultSettingFolder = Setup::$appDir;
            }
        }
        if ($input->getOption('folder'))
            self::$defaultSettingFolder = $input->getOption('folder');


    }
}