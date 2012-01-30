<?php
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use \Framework\Common\Database;

function script(InputInterface $input, OutputInterface $output) {
    $schema = $input->getArgument('migration');
    $conn = Database::newInstance($input->getArgument('database'));
    $platform = $conn->getDatabasePlatform();
    $pName = $platform->getName();
    $dbName = $conn->getDatabase();
    $callStr = '\\Schema\\' . ucfirst($schema) . 'Migrate';
    /**
     * @var \Framework\Common\Migrate $migrate
     */
    $migrate = new $callStr();
    $migrate->save($platform, "app/migration/SQL.cache/$schema");
    $output->writeln("<info>SQL cache app/migration/SQL.cache/$schema.$pName.create.sql created</info>");
    $output->writeln("<info>SQL cache app/migration/SQL.cache/$schema.$pName.drop.sql created</info>");
    $output->writeln("<info>Running SQL operations on $dbName database...</info>");
    $migrate->create($platform, $conn);
    $output->writeln("<info>Migration schema $callStr migrated successful</info>");
}