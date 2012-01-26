# Batman PHP

## Php applications framework
## Version 0.1-ALPHA-DEV

### Setup
1. Install requirements: <br>
<br><code>php composer.phar install</code><br><br>
2. Compile <b>app/config/routing.yml</b><br>
<br><i>Apache + Mod Rewrite</i>:<br><br><code>php bin/manager.php compile</code><br>
<br><i>Nginx (Engine-X)</i>:<br><br><code>php bin/manager.php compile n</code><br><br>
3. PHPUnit recommended for Test Drive Development

### Directories & files chmod
* app/cache - <b>0777</b>
* app/logs - <b>0777</b>
* bin/manager.php - <b>+x</b>
* composer.phar - <b>+x</b>

### Philosophy
1. Avoid the transmission of the variables in paths, use POST for that
2. The application routing must not depend on specific web-server

### Requirements
+ Require all composer vendors (in json file)
+ Recommended version of php >= <b>5.3.8</b>
+ Source code is distributed under <b>GNU General Public License</b>

### Copyrights
##### Powered by Symfony2 components
Yaml, HttpFoundation, Console, Class-Loader<br>
(c) Symfony Project 

##### This framework uses Twig template engine
(c) Fabien Potencier

##### This framework uses SwiftMailer
(c) Chris Corbyn