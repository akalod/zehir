```php
use  Zehir\Settings\Setup as setup;
use  Zehir\System\App;

include "../vendor/autoload.php"; 

/**
'url-path'=>'MVC/bundle-path'
**/
setup::$bundles=[
    'api'=>'API',
    'stok'=>'STOK',
    'zehir'=>'PANEL' 
];

setup::$target = 'dev'; //for select target database configuration
setup::$webUrl = 'http://localhost/';

App::run();
```