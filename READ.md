```php
use  Zehir\Settings\Setup as setup;
use  Zehir\System\App;

include "../vendor/autoload.php"; 

setup::$bundles=[
    'api'=>'API',
    'stok'=>'STOK',
    'zehir'=>'PANEL'
];

setup::$target = 'dev';
setup::$webUrl = 'http://marketsenin.localhost/';

App::run();
```