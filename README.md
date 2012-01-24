# Batman PHP

## Php applications framework

### Setup
1. Install requirements: <br>
<br><code>php composer.phar install</code><br><br>
2. Compile app/config/routing.yml<br>
<br><i>Apache + Mod Rewrite</i>:<br><code>php bin/manager.php compile</code><br>
<br><i>Nginx</i>:<br><code>php bin/manager.php compile n</code><br><br>
3. PHPUnit recommended for TDD

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