<?php
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;

function script(InputInterface $input, OutputInterface $output, $type)
{
    $name = ucfirst(strtolower($input->getArgument('name')));
    $bundle = ucfirst(strtolower($input->getArgument('bundle')));
    $bundleLocation = getcwd() . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, "app/logic/$bundle");
    $bundleTestsLocation = getcwd() . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, "app/tests/{$bundle}Tests");
    clearstatcache();

    switch ($type)
    {
        case 'controller':
            //create controller
            $targetLocation = $bundleLocation . DIRECTORY_SEPARATOR . 'Controllers';
            $targetTestsLocation = $bundleTestsLocation . DIRECTORY_SEPARATOR . 'Controllers';
            if (!is_dir($targetLocation)) mkdir($targetLocation, '0755', true);
            if (!is_dir($targetTestsLocation)) mkdir($targetTestsLocation, '0755', true);
            $targetFile = $targetLocation . DIRECTORY_SEPARATOR . "{$name}.php";
            $targetTestFile = $targetTestsLocation . DIRECTORY_SEPARATOR . "{$name}Test.php";
            $tpl = file_get_contents('bin/templates/controller.data');
            file_put_contents($targetFile, str_replace(
                array('{%=Bundle=%}', '{%=Controller=%}'), array($bundle, $name), $tpl
            ));
            $tpl = file_get_contents('bin/templates/ctest.data');
            file_put_contents($targetTestFile, str_replace(
                array('{%=Bundle=%}', '{%=Controller=%}'), array($bundle, $name), $tpl
            ));
            $output->writeln(str_replace(getcwd(), '', sprintf('Controller <info>%s</info> created successful!', $targetFile)));
            $output->writeln(str_replace(getcwd(), '', sprintf('Test <info>%s</info> created successful!', $targetTestFile)));
            break;

        case 'solution':
            //create solution
            $targetLocation = $bundleLocation . DIRECTORY_SEPARATOR . 'Solutions';
            $targetTestsLocation = $bundleTestsLocation . DIRECTORY_SEPARATOR . 'Solutions';
            if (!is_dir($targetLocation)) mkdir($targetLocation, '0755', true);
            if (!is_dir($targetTestsLocation)) mkdir($targetTestsLocation, '0755', true);
            $targetFile = $targetLocation . DIRECTORY_SEPARATOR . "{$name}.php";
            $targetTestFile = $targetTestsLocation . DIRECTORY_SEPARATOR . "{$name}Test.php";
            $tpl = file_get_contents('bin/templates/solution.data');
            file_put_contents($targetFile, str_replace(
                array('{%=Bundle=%}', '{%=Solution=%}'), array($bundle, $name), $tpl
            ));
            $tpl = file_get_contents('bin/templates/stest.data');
            file_put_contents($targetTestFile, str_replace(
                array('{%=Bundle=%}', '{%=Solution=%}'), array($bundle, $name), $tpl
            ));
            $output->writeln(str_replace(getcwd(), '', sprintf('Solution <info>%s</info> created successful!', $targetFile)));
            $output->writeln(str_replace(getcwd(), '', sprintf('Test <info>%s</info> created successful!', $targetTestFile)));
            break;

        case 'model':
            //create model
            $targetLocation = $bundleLocation . DIRECTORY_SEPARATOR . 'Models';
            if (!is_dir($targetLocation)) mkdir($targetLocation, '0755', true);
            $targetFile = $targetLocation . DIRECTORY_SEPARATOR . "{$name}.php";
            $tpl = file_get_contents('bin/templates/model.data');
            file_put_contents($targetFile, str_replace(
                array('{%=Bundle=%}', '{%=Model=%}'), array($bundle, $name), $tpl
            ));
            $output->writeln(str_replace(getcwd(), '', sprintf('Model <info>%s</info> created successful!', $targetFile)));
            break;
    }
}