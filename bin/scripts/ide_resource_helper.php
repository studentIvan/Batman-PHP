<?php
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use \Framework\Core\Config;

function script(InputInterface $input, OutputInterface $output)
{
    $res = $input->getArgument('resource');
    $type = substr($res, strrpos($res, '.')+1);
    if ($type && ($type == 'js' || $type == 'css'))
    {
        Config::loadResources();
        $path = Config::get('resources.' . $type, substr($res, 0, strrpos($res, '.')));
        $path = str_replace(array('//', 'http:////'), array('http://', 'http://'), $path);
        if ($path) {
            $name = basename($path);
            file_put_contents("bin/idehelper/$name", file_get_contents($path));
            $output->writeln("<info>File $name created</info>");
        }
    }
}