<?php
/**
 * Created by PhpStorm.
 * User: sTaRs
 * Date: 16/10/19
 * Time: 15:10
 */

namespace Zehir\CLI;


use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputDefinition;

class controller extends generic
{

    protected static $defaultName = 'add:c';

    protected function configure()
    {
        $this
            ->setDescription('Controller oluşturma')
            ->setHelp('Controller dosyaAdi')
            ->setDefinition(
                new InputDefinition([
                    new InputOption('folder', 'f', InputOption::VALUE_OPTIONAL),
                    new InputOption('settings', 's', InputOption::VALUE_REQUIRED)
                ])
            )
            ->addArgument('name', InputArgument::REQUIRED, 'DosyaAdı')
            ->addArgument('output', InputArgument::OPTIONAL, 'Çontroller Tipi');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        self::createController($input->getArgument('name'), self::$defaultSettingFolder, $input->getArgument('output'));
    }


}