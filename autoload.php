<?php
/**
 * Batman PHP Autoloader
 */
if (!defined('CONSOLE')) chdir('../..');
require_once 'vendor/symfony/class-loader/Symfony/Component/ClassLoader/UniversalClassLoader.php';
$loader = new Symfony\Component\ClassLoader\UniversalClassLoader();
$loader->registerNamespaces(array(
/** Application Architecture */
    'Main'                                  => 'app/logic',
    'Bootstrap'                             => 'app/logic',
    'MainTests'                             => 'app/tests',

/** Symfony Components */
    'Symfony\Component\HttpFoundation'      => 'vendor/symfony/http-foundation',
    'Symfony\Component\Console'             => 'vendor/symfony/console',
    'Symfony\Component\Yaml'                => 'vendor/symfony/yaml',

/** Doctrine Project */
    'Doctrine\Common'                       => 'vendor/doctrine/common/lib',
    'Doctrine\DBAL'                         => 'vendor/doctrine/dbal/lib',
    'Schema'                                => 'app/migration',

/** Framework Data */
    'Exceptions'                            => 'system',
    'Framework'                             => 'system',
));
$loader->registerPrefixes(array(
    'Twig_'                                 => 'vendor/twig/twig/lib',
    'Swift_'                                => 'vendor/swiftmailer/swiftmailer/lib/classes',
));
$loader->register();
Framework\Core\Config::init();