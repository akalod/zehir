###example index.php (or someone else)
```php
use  Zehir\Settings\Setup as setup;
use  Zehir\System\App;

include "vendor/autoload.php"; 

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

###webconfig rule
```
<rule name="ZehirMVC" stopProcessing="true">
    <match url="^(.*)$" ignoreCase="false" />
    <conditions logicalGrouping="MatchAll">
        <add input="{REQUEST_FILENAME}" matchType="IsFile" ignoreCase="false" negate="true" />
        <add input="{REQUEST_FILENAME}" matchType="IsDirectory" ignoreCase="false" negate="true" />
        <add input="{URL}" matchType="IsDirectory" ignoreCase="false" negate="true" />
    </conditions>
    <action type="Rewrite" url="_app.php/{R:1}" />
</rule>
```
###.htaccess rule
```
<IfModule mod_rewrite.c>
    RewriteEngine on
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{URL} !-d
    RewriteRule ^(.*)$ {{filaname}}.php/$1 [L] 
</IfModule>
```
### you can use "no-database"
Setup::$noDatabase = true; 
### you can disable routing from database
Setup::$routeDB = false;

###Databases settings
```php
Setup::configure([
    'local' => Array(
        'host' => 'localhost',
        'name' => 'databasename',
        'user' => 'username',
        'pass' => 'sifre',
        'port' => 3306,
        'adapter' => 'mysql',
        'redis_server' => 'localhost',
        'redis_port' => 6379,
    ),
    'test' => Array(
        'host' => '91.121.***.***',
        'name' => 'dp_tests',
        'user' => 'dp_tests',
        'pass' => '*****',
        'port' => 3306,
        'adapter' => 'mysql'
    )]);
```

### Enable Multi Languages 
```
Setup::$multiLang = true;
Setup::$enableLanguages[] = ['id' => 1, 'lang' => 'TR'];
Setup::$enableLanguages[] = ['id' => 2, 'lang' => 'EN'];
```
### Install pre set modules
```
Setup::$installParameters = ['news', 'banners', 'pages'];
```
### injection Twig Filter
```
Setup::$TwigFilters = [
    [
        "name" => 'tracker',
        'fn' => function ($string) {
            return $string . '?' . $_SERVER["QUERY_STRING"];
        }
    ]
];
```
### database search routing name by seo column
```
Setup::$search_extend['dabase_table_name'] = 'controller_name';
```
### Add costum value
```
Setup::addCustom('JWT_SECRET', 'T3-5T~=!@\'/W3Eh:[Gb4@~{}_v{?e8}%7HDp');
```
### Get custom value
```
Setup::custom('JWT_SECRET')
```
### Assign value to template engine
```
App::assign('pageTitle',$data->name);
```
