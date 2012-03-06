<?php
use \Symfony\Component\Console\Input\InputInterface,
    \Symfony\Component\Console\Output\OutputInterface,
    \Framework\Common\Database,
    \Framework\Core\Config;

function script(InputInterface $input, OutputInterface $output)
{
    $dbConfig = Config::get($input->getArgument('database'));
    $name = isset($dbConfig['path']) ? $dbConfig['path'] : $dbConfig['dbname'];
    $tmpConnection = Database::newFreeInstance($input->getArgument('database'));
    $dbMigrateConfig = Config::get('manager.database');
    $created = false;
    $mysqlPlatformCollate = "
            ALTER DATABASE $name
            DEFAULT CHARACTER SET {$dbMigrateConfig['charset']}
            COLLATE {$dbMigrateConfig['collate']};";

    try
    {
        $tmpConnection->getSchemaManager()->createDatabase($name);
        $created = true;
        $output->writeln(sprintf('<info>Created database for connection named %s</info>', $name));
    }
    catch (\Exception $e)
    {
        try
        {
            $tmpConnection->getSchemaManager()->dropDatabase($name);
            $tmpConnection->getSchemaManager()->createDatabase($name);
            $created = true;
            $output->writeln(sprintf('<info>Existed database %s was dropped</info>', $name));
            $output->writeln(sprintf('<info>Created database named %s</info>', $name));
        }
        catch (\Exception $e)
        {
            $output->writeln(sprintf('<error>Could not create database named %s</error>', $name));
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
        }
    }

    if ($created)
    {
        if ($tmpConnection->getDatabasePlatform()->getName() == 'mysql')
        {
            $tmpConnection->query($mysqlPlatformCollate);
            $output->writeln(
                sprintf('<info>Convert created MySQL database charset to %s</info>', $dbMigrateConfig['charset'])
            );
        }
    }

    $tmpConnection->close();
}