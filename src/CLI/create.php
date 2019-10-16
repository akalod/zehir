<?php
/**
 * Created by PhpStorm.
 * User: sTaRs
 * Date: 16/10/19
 * Time: 11:50
 */

namespace Zehir\CLI;


use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputDefinition;

class create extends generic
{

    protected static $defaultName = 'create';

    protected function configure()
    {
        $this
            ->setDescription('Create folder structure')
            ->setDefinition(
                new InputDefinition([
                    new InputOption('folder', 'f', InputOption::VALUE_OPTIONAL),
                    new InputOption('settings', 's', InputOption::VALUE_REQUIRED)
                ])
            )
            ->setHelp('Dosya yapısını oluşturur');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        mkdir(self::$defaultSettingFolder);
        $dirList = ['views', 'controllers', 'models'];

        foreach ($dirList as $dir) {
            mkdir(self::$defaultSettingFolder . '/' . $dir);
        }

        self::createController('main', self::$defaultSettingFolder);
        self::createModel('main', self::$defaultSettingFolder);
        self::createView('main', self::$defaultSettingFolder);
    }


}