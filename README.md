# Batman PHP

## Php applications framework
## Version 0.1.7-ALPHA-DEV

### Setup
1. Compile <b>app/config/routing.yml</b><br>
<br><i>Apache + Mod Rewrite</i>:<br><br><code>php bin/manager.php router:compile:apache</code><br>
<br><i>Nginx (Engine-X)</i>:<br><br><code>php bin/manager.php router:compile:nginx</code><br><br>
2. For PhpStorm IDE try:<br>
<br><code>php bin/manager.php phpstorm:console:generate</code><br><br>
3. Install PEAR/PHPUnit (recommended for Test Drive Development)

##### Nginx configuration prototype:
```nginx
server {
    server_name mysite.com;

    root /srv/www/mysite.com/app/root;
    include /srv/www/mysite.com/nginx.inc;

    location / {
        index index.php index.html;
    }

    location ^~ /(images|styles|javascripts)/ {
        expires 30d;
        access_log off;
    }

    location ~ \.php$ {
        fastcgi_pass   127.0.0.1:9000;
        fastcgi_index  index.php;
        fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
        include        fastcgi_params;
    }
}
```

##### Apache configuration prototype:
```apache
<VirtualHost *>
    ServerName mysite.com
    DocumentRoot "/srv/www/mysite.com/app/root"

    <Directory /srv/www/mysite.com/app/root>
        DirectoryIndex index.php index.html
        AllowOverride All
        Order allow,deny
        Allow from all
    </Directory>
</VirtualHost>
```

### Directories & files chmod
* app/cache - <b>0777</b>
* app/logs - <b>0777</b>
* bin/manager.php - <b>+x</b>

### Philosophy
1. Avoid the transmission of the variables in paths, use POST for that
2. The application routing must not depend on specific web-server

### Info
+ Recommended version of php >= <b>5.3.8</b>
+ Source code is distributed under <b>BSD License</b> (changed 24.02.2012)
+ Included some vendors
+ Процесс разработки и отладки фреймворка выполняется под Windows 7 x64, Apache 2, PHP 5.3
+ Фреймворк находится в стадии разработки, не рекомендуется использовать его в своих проектах до стабильных релизов!

### Available console commands
<pre>
controller
  controller:create           Create new controller.
  controller:test             Run PHPUnit test for application controller.
database
  database:create             Create database (drop if exists).
  database:schema:create      Create new schema for migration.
  database:schema:generate    Generate new schema for migration.
  database:schema:migrate     Create tables in database (drop if exists).
framework
  framework:test              Run PHPUnit test for framework element.
model
  model:create                Create new model.
phpstorm
  phpstorm:console:generate   Generate Batman-PHP console commands XML-helper for Idea IDE (PhpStorm).
router
  router:compile:apache       Compile routing for apache + mod rewrite.
  router:compile:nginx        Compile routing for nginx.
solution
  solution:create             Create new solution.
  solution:test               Run PHPUnit test for application solution.
</pre>

### Copyrights
##### Powered by Symfony2 components
Yaml, HttpFoundation, Console, Class-Loader, Process<br>
(c) Symfony Project 

##### This framework uses Twig template engine
(c) Fabien Potencier

##### This framework uses SwiftMailer
(c) Chris Corbyn

##### This framework uses Doctrine 2 Database Abstraction Layer
(c) Doctrine Project

##### This framework uses Assetic
(c) Kris Wallsmith

##### This framework uses Zend Framework 2 components
Registry<br>
(c) Zend Technologies USA Inc.