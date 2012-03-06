<?php
use \Symfony\Component\Console\Input\InputInterface,
    \Symfony\Component\Console\Output\OutputInterface;

function script(InputInterface $input, OutputInterface $output)
{
    $schema = ucfirst($input->getArgument('schema'));
    $targetFile = "app/migration/Schema/{$schema}Migrate.php";
    $tpl = file_get_contents('bin/templates/migration.data');
    file_put_contents($targetFile, str_replace('{%=Schema=%}', $schema, $tpl));
    $output->writeln(sprintf('<info>Migration schema %s created successful</info>', $targetFile));
}