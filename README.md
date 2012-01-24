# Batman PHP
# Php applications framework

1. Install requirements: 
* php composer.phar install
2. Compile app/config/routing.yml
* Apache + Mod Rewrite: php bin/manager.php compile
* Nginx: php bin/manager.php compile n
3. PHPUnit recommended for TDD

## Directories & files chmod

app/cache - 0777
app/logs - 0777
bin/manager.php - +x
composer.phar - +x

## Philosophy

1. Avoid the transmission of the variables in paths, use POST for that
2. The application routing must not depend on specific web-server

## Requirements

Require all composer vendors (in json file)
Recommended version of php >= 5.3.8
Source code is distributed under GNU General Public License