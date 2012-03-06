<?php
use \Symfony\Component\Console\Input\InputInterface,
    \Symfony\Component\Console\Output\OutputInterface;

function script(InputInterface $input, OutputInterface $output)
{
    $m = number_format(memory_get_usage() / 1024 / 1024, 3);
    $output->writeln("memory usage: {$m}MB");
    $name = ucfirst($input->getArgument('name'));
    $method = $input->getArgument('method');
    $location = '\\Framework\\Tests\\' . $name . 'Test';
    $output->writeln(sprintf('Running <info>%s</info>...', "$location()->$method()"));
    $time_start = microtime(true);
    $request = new $location();
    $stop = false;

    try
    {
        $request->$method();
    }
    catch (\Exception $e)
    {
        $output->writeln('Test failed with exception: ' . $e->getMessage());
        $stop = true;
    }

    $time_end = microtime(true);
    $time = $time_end - $time_start;
    $ml = number_format(memory_get_usage() / 1024 / 1024, 3);
    $output->writeln('memory usage after: ' . $ml . 'MB (+' . ($ml * 1 - $m * 1) . 'MB)');
    $output->writeln('time: ' . number_format($time, 4) . ' sec');

    if (!$stop) {
        $output->writeln('Test complete!');
    }
}