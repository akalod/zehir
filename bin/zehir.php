<?php

$autoloader = require __DIR__ . '/../src/composer_autoloader.php';


use Symfony\Component\Console\Application;

if (!$autoloader()) {
    die(
        'You need to set up the project dependencies using the following commands:' . PHP_EOL .
        'curl -sS https://getcomposer.org/installer | php' . PHP_EOL .
        'php composer.phar install' . PHP_EOL
    );
}

$application = new Application();
$application->setName('Zehir CLI');

$application->add(new \Zehir\CLI\create());
$application->add(new \Zehir\CLI\model());
$application->add(new \Zehir\CLI\view());
$application->add(new \Zehir\CLI\controller());

try {
    $application->run();
} catch (Exception $e) {
    echo $e->getMessage();
}