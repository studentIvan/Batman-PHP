<?php
/**
 * Batman PHP Autoloader
 */
require_once 'vendor/symfony/class-loader/Symfony/Component/ClassLoader/UniversalClassLoader.php';
$loader = new Symfony\Component\ClassLoader\UniversalClassLoader();
$loader->registerNamespaces(array(
/** Application Architecture */
    'Main'                                  => 'app/logic',
    'Main\Controllers'                      => 'app/logic',
    'Main\Solutions'                        => 'app/logic',
    'Bootstrap'                             => 'app/logic',

/** Test Drive Development */
    'MainTests'                             => 'app/tests',
    'MainTests\Controllers'                 => 'app/tests',
    'MainTests\Solutions'                   => 'app/tests',

/** Symfony Components */
    'Symfony\Component\HttpFoundation'      => 'vendor/symfony/http-foundation',
    'Symfony\Component\Console'             => 'vendor/symfony/console',
    'Symfony\Component\Yaml'                => 'vendor/symfony/yaml',

/** Framework Data */
    'Exceptions'                            => 'system',
    'Framework\Core'                        => 'system',
    'Framework\Tests'                       => 'system',
    'Framework\Common'                      => 'system',
    'Framework\Packages'                    => 'system',
    'Framework\Interfaces'                  => 'system',
));
$loader->registerPrefixes(array(
    'Twig_'                                 => 'vendor/twig/twig/lib',
    'Swift_'                                => 'vendor/swiftmailer/swiftmailer/lib/classes',
));
$loader->register();
Framework\Core\Config::init();