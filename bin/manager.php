<?php
error_reporting(-1);
ini_set('display_errors', 'On');
define('CONSOLE', true);
if (preg_match('/bin/', getcwd())) chdir('..');
require_once __DIR__ . '/../autoload.php';
use \Symfony\Component\Console\Application;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Input\InputArgument;
use \Symfony\Component\Console\Input\InputOption;
use \Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\Yaml\Yaml;
/**
 * Batman PHP Console Manager
 */
$console = new Application('Batman PHP Console Manager', '1.0.0');
$console
    ->register('framework:optimize')
    ->setDescription('Clean garbage from vendor dir.')
    ->setCode(function (InputInterface $input, OutputInterface $output) {
        $map = array(
            'vendor/swiftmailer/swiftmailer/doc',
            'vendor/swiftmailer/swiftmailer/tests',
            'vendor/swiftmailer/swiftmailer/test-suite',
            'vendor/swiftmailer/swiftmailer/notes',
            'vendor/twig/twig/doc',
            'vendor/twig/twig/test',
            'vendor/twig/twig/bin',
            'vendor/doctrine/common/tests',
            'vendor/doctrine/dbal/tests',
        );
        foreach ($map as $path) {
            $output->writeln("<info>remove $path ...</info>");
            rrmdir($path);
        }
    })
;
$console
    ->register('database:create')
    ->setDefinition(array(
		new InputArgument('database', InputArgument::OPTIONAL, 'Database configuration name', 'database')
	))
    ->setDescription('Create database (drop if exists).')
    ->setCode(function (InputInterface $input, OutputInterface $output) {
        include __DIR__ . '/scripts/create_database.php';
        script($input, $output);
    })
;
$console
    ->register('database:schema:create')
    ->setDefinition(array(
        new InputArgument('schema', InputArgument::REQUIRED, 'Schema name')
    ))
    ->setDescription('Create new schema for migration.')
    ->setCode(function (InputInterface $input, OutputInterface $output) {
        include __DIR__ . '/scripts/create_migration.php';
        script($input, $output);
    })
;
$console
    ->register('database:schema:generate')
    ->setDefinition(array(
        new InputArgument('schema', InputArgument::REQUIRED, 'Schema name'),
        new InputArgument('map', InputArgument::REQUIRED, 'Generator map'),
    ))
    ->setDescription('Generate new schema for migration.')
    ->setCode(function (InputInterface $input, OutputInterface $output) {
        include __DIR__ . '/scripts/generate_migration.php';
        script($input, $output);
    })
;
$console
    ->register('database:schema:migrate')
    ->setDefinition(array(
		new InputArgument('migration', InputArgument::REQUIRED, 'File name in app/migration'),
		new InputArgument('database', InputArgument::OPTIONAL, 'Database configuration name', 'database'),
    ))
    ->setDescription('Create tables in database (drop if exists).')
    ->setCode(function (InputInterface $input, OutputInterface $output) {
        include __DIR__ . '/scripts/migrate.php';
        script($input, $output);
    })
;
$console
    ->register('router:compile:apache')
    ->setDescription('Compile routing for apache + mod rewrite.')
    ->setCode(function (InputInterface $input, OutputInterface $output) {
        include __DIR__ . '/scripts/compile.php';
        script('apache', $output);
    })
;
$console
    ->register('router:compile:nginx')
    ->setDescription('Compile routing for nginx.')
    ->setCode(function (InputInterface $input, OutputInterface $output) {
        include __DIR__ . '/scripts/compile.php';
        script('nginx', $output);
    })
;
$console
    ->register('framework:test')
    ->setDefinition(array(
		new InputArgument('name', InputArgument::REQUIRED, 'Element name'),
        new InputArgument('method', InputArgument::OPTIONAL, 'Test name', 'test'),
    ))
    ->setDescription('Run PHPUnit test for framework element.')
    ->setCode(function (InputInterface $input, OutputInterface $output) {
        include __DIR__ . '/scripts/framework_test.php';
        script($input, $output);
    })
;
$console
    ->register('controller:test')
    ->setDefinition(array(
		new InputArgument('name', InputArgument::REQUIRED, 'Controller name'),
        new InputArgument('method', InputArgument::OPTIONAL, 'Test name', 'test'),
		new InputArgument('bundle', InputArgument::OPTIONAL, 'Bundle (default Main)', 'Main'),
    ))
    ->setDescription('Run PHPUnit test for application controller.')
    ->setCode(function (InputInterface $input, OutputInterface $output) {
        include __DIR__ . '/scripts/test.php';
        script($input, $output, 'controller');
    })
;
$console
    ->register('solution:test')
    ->setDefinition(array(
		new InputArgument('name', InputArgument::REQUIRED, 'Solution name'),
        new InputArgument('method', InputArgument::OPTIONAL, 'Test name', 'test'),
		new InputArgument('bundle', InputArgument::OPTIONAL, 'Bundle (default Main)', 'Main'),
    ))
    ->setDescription('Run PHPUnit test for application solution.')
    ->setCode(function (InputInterface $input, OutputInterface $output) {
        include __DIR__ . '/scripts/test.php';
        script($input, $output, 'solution');
    })
;
$console
    ->register('controller:create')
    ->setDefinition(array(
		new InputArgument('name', InputArgument::REQUIRED, 'Controller name'),
		new InputArgument('bundle', InputArgument::OPTIONAL, 'Bundle (default Main)', 'Main'),
    ))
    ->setDescription('Create new controller.')
    ->setCode(function (InputInterface $input, OutputInterface $output) {
        include __DIR__ . '/scripts/component_create.php';
        script($input, $output, 'controller');
    })
;
$console
    ->register('solution:create')
    ->setDefinition(array(
		new InputArgument('name', InputArgument::REQUIRED, 'Solution name'),
		new InputArgument('bundle', InputArgument::OPTIONAL, 'Bundle (default Main)', 'Main'),
    ))
    ->setDescription('Create new solution.')
    ->setCode(function (InputInterface $input, OutputInterface $output) {
        include __DIR__ . '/scripts/component_create.php';
        script($input, $output, 'solution');
    })
;
$console
    ->register('model:create')
    ->setDefinition(array(
    new InputArgument('name', InputArgument::REQUIRED, 'Model name'),
    new InputArgument('bundle', InputArgument::OPTIONAL, 'Bundle (default Main)', 'Main'),
))
    ->setDescription('Create new model.')
    ->setCode(function (InputInterface $input, OutputInterface $output) {
    include __DIR__ . '/scripts/component_create.php';
    script($input, $output, 'model');
})
;
$console
    ->register('phpstorm:console:generate')
    ->setDescription('Generate Batman-PHP console commands XML-helper for Idea IDE (PhpStorm).')
    ->setCode(function (InputInterface $input, OutputInterface $output) {
        global $console;
        include __DIR__ . '/scripts/phpstorm_console.php';
        script($input, $output, $console);
    })
;

function rrmdir($dir) {
   if (is_dir($dir)) {
     $objects = scandir($dir);
     foreach ($objects as $object) {
       if ($object != "." && $object != "..") {
         if (filetype($dir."/".$object) == "dir")
             rrmdir($dir."/".$object);
         else
             unlink($dir."/".$object);
       }
     }
     reset($objects);
     rmdir($dir);
   }
 }

$console->run();