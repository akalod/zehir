<?php

namespace Zehir\CLI;


use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputDefinition;

class view extends generic
{

    protected static $defaultName = 'add:v';

    protected function configure()
    {
        $this
            ->setDescription('View oluşturma')
            ->setHelp('view dosyaAdi')->setDefinition(
                new InputDefinition([
                    new InputOption('folder', 'f', InputOption::VALUE_OPTIONAL),
                    new InputOption('settings', 's', InputOption::VALUE_REQUIRED)
                ])
            )
            ->addArgument('name', InputArgument::REQUIRED, 'DosyaAdı');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        self::createView($input->getArgument('name'), self::$defaultSettingFolder);
    }


}