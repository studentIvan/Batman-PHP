<?php
/**
 * Batman PHP Autoloader
 */
if (!defined('CONSOLE')) chdir(FRAMEWORK_PATH);
require_once 'vendors/symfony/Symfony/Component/ClassLoader/UniversalClassLoader.php';
$loader = new Symfony\Component\ClassLoader\UniversalClassLoader();
$loader->registerNamespaces(array(
/** Application Architecture */
    'Main'                                  => APPLICATION_PATH . 'logic',
    'Authmasha'                             => APPLICATION_PATH . 'logic',
    'Bootstrap'                             => APPLICATION_PATH . 'logic',
    'Admin'                                 => APPLICATION_PATH . 'logic',
    'AdminTests'                            => APPLICATION_PATH . 'tests',
    'MainTests'                             => APPLICATION_PATH . 'tests',
    'Schema'                                => APPLICATION_PATH . 'migration',

/** Vendors */
    'Symfony'                               => 'vendors/symfony',
    'Doctrine'                              => 'vendors/doctrine',
    'Assetic'                               => 'vendors/assetics',
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