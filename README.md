# Batman PHP

## Php applications framework
## Version 0.1.6-ALPHA-DEV

### Setup
1. Compile <b>app/config/routing.yml</b><br>
<br><i>Apache + Mod Rewrite</i>:<br><br><code>php bin/manager.php router:compile:apache</code><br>
<br><i>Nginx (Engine-X)</i>:<br><br><code>php bin/manager.php router:compile:nginx</code><br><br>
2. For PhpStorm IDE try:<br>
<br><code>php bin/manager.php phpstorm:console:generate</code><br><br>
3. Install PEAR/PHPUnit (recommended for Test Drive Development)

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