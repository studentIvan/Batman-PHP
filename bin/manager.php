<?php
error_reporting(-1);
ini_set('display_errors', 'On');
if (preg_match('/bin/', getcwd())) chdir('..');
require_once 'autoload.php';
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
    ->register('compile')
    ->setDefinition(array(
		new InputArgument('server', InputArgument::OPTIONAL, 'Webserver (a - apache (default), n - nginx)', 'a'),
    ))
    ->setDescription('Compile routing for specific web server.')
    ->setCode(function (InputInterface $input, OutputInterface $output) {
        if ($input->getArgument('server') == 'a') {
			$rules = file_get_contents('app/config/htaccess.txt');
			$rules .= "\n\nRewriteEngine On\nRewriteBase /";
			foreach (Yaml::parse('app/config/routing.yml') as $rule) {
				$output->writeln('routing ' . $rule['pattern']);
				$rule['pattern'] = ltrim($rule['pattern'], '/');
				$q = "\nRewriteRule ^{$rule['pattern']}$ /index.php?{$rule['route']}";
				$output->writeln(trim($q)); $rules .= $q;
				$output->writeln('============================================================');
			}
			file_put_contents('app/root/.htaccess', trim($rules));
			$output->writeln('Complete!');
		} else {
			$rules = '';
			foreach (Yaml::parse('app/config/routing.yml') as $rule) {
				$output->writeln('routing ' . $rule['pattern']);
				$q = "\nrewrite ^{$rule['pattern']}$ /index.php?{$rule['route']} last;";
				$output->writeln(trim($q)); $rules .= $q;
				$output->writeln('============================================================');
			}
			file_put_contents('nginx.inc', trim($rules));
			$output->writeln('Complete!');
		}
    })
;
$console
    ->register('ftest')
    ->setDefinition(array(
		new InputArgument('name', InputArgument::REQUIRED, 'Element name'),
        new InputArgument('method', InputArgument::OPTIONAL, 'Test name', 'test'),
    ))
    ->setDescription('Run PHPUnit test for framework element.')
    ->setCode(function (InputInterface $input, OutputInterface $output) {
        $name = ucfirst($input->getArgument('name'));
        $method = $input->getArgument('method');
		
		$location = '\\Framework\\Tests\\' . $name . 'Test';
		$output->writeln(sprintf('Running <info>%s</info>...', "$location()->$method()"));
		$request = new $location();
		$stop = false;
		try 
		{
			$request->$method();
		}
		catch (\Exception $e) 
		{
			$output->writeln($e->getMessage());
			$stop = true;
		}
		if (!$stop) {
			$output->writeln('Test complete!');
		}
    })
;
$console
    ->register('test')
    ->setDefinition(array(
		new InputArgument('name', InputArgument::REQUIRED, 'Component name'),
        new InputArgument('method', InputArgument::OPTIONAL, 'Test name', 'test'),
        new InputArgument('type', InputArgument::OPTIONAL, 'Component type (c - controller/s - solution (default))', 's'),
		new InputArgument('bundle', InputArgument::OPTIONAL, 'Bundle (default Main)', 'Main'),
    ))
    ->setDescription('Run PHPUnit test for application component.')
    ->setCode(function (InputInterface $input, OutputInterface $output) {
        $type = ($input->getArgument('type') == 'c') ? 'Controllers' : 'Solutions';
        $name = ucfirst($input->getArgument('name'));
        $method = $input->getArgument('method');
        $bundle = $input->getArgument('bundle');
		
		$location = '\\' . $bundle . 'Tests\\' . $type . '\\' . $name . 'Test';
		$output->writeln(sprintf('Running <info>%s</info>...', "$location()->$method()"));
		$request = new $location();
		$stop = false;
		try 
		{
			$request->$method();
		}
		catch (\Exception $e) 
		{
			$output->writeln($e->getMessage());
			$stop = true;
		}
		if (!$stop) {
			$output->writeln('Test complete!');
		}
    })
;
$console
    ->register('create')
    ->setDefinition(array(
		new InputArgument('name', InputArgument::REQUIRED, 'Component name'),
        new InputArgument('type', InputArgument::OPTIONAL, 'Component type (c - controller (default)/s - solution)', 'c'),
		new InputArgument('bundle', InputArgument::OPTIONAL, 'Bundle (default Main)', 'Main'),
    ))
    ->setDescription('Create new component.')
    ->setCode(function (InputInterface $input, OutputInterface $output) {
		$name = ucfirst(strtolower($input->getArgument('name')));
        $bundle = ucfirst(strtolower($input->getArgument('bundle')));
		$bundleLocation = getcwd() . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, "app/logic/$bundle");
		$bundleTestsLocation = getcwd() . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, "app/tests/{$bundle}Tests");
		clearstatcache();
        switch ($input->getArgument('type')) {
			case 'c':
			//create controller
			$targetLocation = $bundleLocation . DIRECTORY_SEPARATOR . 'Controllers';
			$targetTestsLocation = $bundleTestsLocation . DIRECTORY_SEPARATOR . 'Controllers';
			if (!is_dir($targetLocation)) mkdir($targetLocation, '0755', true);
			if (!is_dir($targetTestsLocation)) mkdir($targetTestsLocation, '0755', true);
			$targetFile = $targetLocation . DIRECTORY_SEPARATOR . "{$name}.php";
			$targetTestFile = $targetTestsLocation . DIRECTORY_SEPARATOR . "{$name}Test.php";
			$tpl = file_get_contents('bin' . DIRECTORY_SEPARATOR . 'controller.data');
			$aslData = \Framework\Core\Config::get('application', 'autoload_solutions');
			$autoloadedSolutions = '* ';
			if ($aslData)
			{
				$autoloadedSolutions .= "\n * Autoloaded solutions: ";
				foreach ($aslData as $solution) 
				{
					$solution = strtolower($solution);
					$callString = '\\Main\\Solutions\\' . ucfirst($solution);
					$autoloadedSolutions .= "\n * @property $callString \${$solution}";
				}
				$autoloadedSolutions .= "\n * ";
			}
			file_put_contents($targetFile, str_replace(
				array('{%=Bundle=%}', '{%=Controller=%}', '{%=AutoloadedSolutions=%}'), 
				array($bundle, $name, $autoloadedSolutions), $tpl
			));
			$tpl = file_get_contents('bin' . DIRECTORY_SEPARATOR . 'ctest.data');
			file_put_contents($targetTestFile, str_replace(
				array('{%=Bundle=%}', '{%=Controller=%}'), array($bundle, $name), $tpl
			));
			$output->writeln(str_replace(getcwd(), '', sprintf('Controller <info>%s</info> created succesfull!', $targetFile)));
			$output->writeln(str_replace(getcwd(), '', sprintf('Test <info>%s</info> created succesfull!', $targetTestFile)));
			break;
			
			case 's':
			//create solution
			$targetLocation = $bundleLocation . DIRECTORY_SEPARATOR . 'Solutions';
			$targetTestsLocation = $bundleTestsLocation . DIRECTORY_SEPARATOR . 'Solutions';
			if (!is_dir($targetLocation)) mkdir($targetLocation, '0755', true);
			if (!is_dir($targetTestsLocation)) mkdir($targetTestsLocation, '0755', true);
			$targetFile = $targetLocation . DIRECTORY_SEPARATOR . "{$name}.php";
			$targetTestFile = $targetTestsLocation . DIRECTORY_SEPARATOR . "{$name}Test.php";
			$tpl = file_get_contents('bin' . DIRECTORY_SEPARATOR . 'solution.data');
			file_put_contents($targetFile, str_replace(
				array('{%=Bundle=%}', '{%=Solution=%}'), array($bundle, $name), $tpl
			));
			$tpl = file_get_contents('bin' . DIRECTORY_SEPARATOR . 'stest.data');
			file_put_contents($targetTestFile, str_replace(
				array('{%=Bundle=%}', '{%=Solution=%}'), array($bundle, $name), $tpl
			));
			$output->writeln(str_replace(getcwd(), '', sprintf('Solution <info>%s</info> created succesfull!', $targetFile)));
			$output->writeln(str_replace(getcwd(), '', sprintf('Test <info>%s</info> created succesfull!', $targetTestFile)));
			break;
		}
    })
;
$console->run();
