<?php
use \Symfony\Component\Console\Input\InputInterface,
    \Symfony\Component\Console\Output\OutputInterface,
    \Framework\Common\Database;

function script(InputInterface $input, OutputInterface $output)
{
    $conn = Database::getInstance($input->getArgument('database'));
    $schema = $input->getArgument('migration');
    $schemas = (strpos($schema, ',') !== false) ? explode(',', $schema) : array($schema);
    $platform = $conn->getDatabasePlatform();
    $pName = $platform->getName();
    $dbName = $conn->getDatabase();
    foreach ($schemas as $schema)
    {
        $callStr = '\\Schema\\' . ucfirst($schema) . 'Migrate';
        /**
         * @var \Framework\Common\Migrate $migrate
         */
        $migrate = new $callStr();
        if (!file_exists("app/migration/SQL.cache/$schema.$pName.create.sql")) {
            $migrate->save($platform, "app/migration/SQL.cache/$schema");
            $output->writeln("<info>SQL cache app/migration/SQL.cache/$schema.$pName.create.sql created</info>");
            $output->writeln("<info>SQL cache app/migration/SQL.cache/$schema.$pName.drop.sql created</info>");
        } else {
            $output->writeln("<info>SQL cache app/migration/SQL.cache/$schema.$pName.create.sql exists, using it</info>");
            $output->writeln("<info>SQL cache app/migration/SQL.cache/$schema.$pName.drop.sql exists, using it</info>");
        }
        $output->writeln("<info>Running SQL operations on $dbName database...</info>");
        $migrate->create($conn, "app/migration/SQL.cache/$schema");
        $output->writeln("<info>Migration schema $callStr migrated successful</info>");
    }
}