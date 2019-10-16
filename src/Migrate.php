<?php

namespace Zehir\Migrations;


Use Zehir\System\DB as Capsule;
use Phinx\Migration\AbstractMigration;
use Zehir\Settings\Setup as setup;


class Migrate extends AbstractMigration
{
    /** @var \Illuminate\Database\Capsule\Manager $capsule */
    public $capsule;
    /** @var \Illuminate\Database\Schema\Builder $capsule */
    public $schema;

    public function init()
    {

        $selectedSetting = setup::getDBsettings($this->getInput()->getOption('environment'));
        $newSetting = [
            'driver' => $selectedSetting['adapter'],
            'host' => $selectedSetting['host'],
            'port' => $selectedSetting['port'],
            'database' => $selectedSetting['name'],
            'username' => $selectedSetting['user'],
            'password' => $selectedSetting['pass'],
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ];

        $this->capsule = new Capsule;
        $this->capsule->addConnection($newSetting);

        $this->capsule->bootEloquent();
        $this->capsule->setAsGlobal();
        $this->schema = $this->capsule->schema();
    }
}