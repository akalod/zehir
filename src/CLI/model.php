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

class model extends generic
{

    protected static $defaultName = 'add:m';

    protected function configure()
    {
        $this
            ->setDescription('Model oluşturma')
            ->setHelp('add:m dosyaAdi')
            ->setDefinition(
                new InputDefinition([
                    new InputOption('folder', 'f', InputOption::VALUE_OPTIONAL),
                    new InputOption('settings', 's', InputOption::VALUE_REQUIRED)
                ])
            )->addArgument('name', InputArgument::REQUIRED, 'DosyaAdı');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        self::createModel($input->getArgument('name'), self::$defaultSettingFolder);
    }


}