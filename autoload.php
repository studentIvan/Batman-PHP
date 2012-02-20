<?php
/**
 * Batman PHP Autoloader
 */
if (!defined('CONSOLE')) chdir('../..');
require_once 'vendors/symfony/Symfony/Component/ClassLoader/UniversalClassLoader.php';
$loader = new Symfony\Component\ClassLoader\UniversalClassLoader();
$loader->registerNamespaces(array(
/** Application Architecture */
    'Main'                                  => 'app/logic',
    'Bootstrap'                             => 'app/logic',
    'Admin'                                 => 'app/logic',
    'AdminTests'                            => 'app/tests',
    'MainTests'                             => 'app/tests',
    'Schema'                                => 'app/migration',

/** Vendors */
    'Symfony'                               => 'vendors/symfony',
    'Doctrine'                              => 'vendors/doctrine',
    'Assetic'                               => 'vendors/assetic',
    'Zend'                                  => 'vendors/zend',

/** Framework Data */
    'Exceptions'                            => 'system',
    'Framework'                             => 'system',
));
$loader->registerPrefixes(array(
    'Twig_'                                 => 'vendors/twig',
    'Swift_'                                => 'vendors/swiftmailer/classes',
));
$loader->register();
Framework\Core\Config::init();