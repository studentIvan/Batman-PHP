<?php
use \Symfony\Component\Console\Input\InputInterface,
    \Symfony\Component\Console\Output\OutputInterface,
    \Symfony\Component\Console\Application,
    \Framework\Common\Database,
    \Framework\Core\Config;

function script(InputInterface $input, OutputInterface $output, Application $console)
{
    $schemaName = 'frameworkDescriptionVersion1.1.xsd';
    $document = new DOMDocument('1.0', 'UTF-8');
    preg_match_all('/<info>(\S+\:\S+)(?:\s+?)<\/info>(?:\s?)(.+)/', $console->asText(), $out);
    $framework = $document->createElement('framework');
    $framework->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
    $framework->setAttribute('xsi:noNamespaceSchemaLocation', 'schemas/' . $schemaName);
    $framework->setAttribute('name', 'Batman PHP');
    $framework->setAttribute('invoke', 'php "bin/manager.php"');
    $framework->setAttribute('alias', 'manager');
    $framework->setAttribute('enabled', 'true');
    $framework->setAttribute('version', '1');

    for ($i = 0; $i < count($out[1]); $i++)
    {
        $command = $document->createElement('command');
        $name = $document->createElement('name', $out[1][$i]);
        $command->appendChild($name);
        $help = $document->createElement('help');
        $cdata = $document->createCDATASection($out[2][$i]);
        $help->appendChild($cdata);
        $command->appendChild($help);
        $commandObject = $console->find($out[1][$i]);
        $qArguments = false;

        if ($commandObject)
        {
            $qArguments = '';
            $arguments = $commandObject->getDefinition()->getArguments();

            if ($arguments && count($arguments) > 0)
            {
                foreach ($arguments as $argumentName => $argumentData)
                {
                    /** @var \Symfony\Component\Console\Input\InputArgument $argumentData */
                    $default = $argumentData->getDefault();
                    $tmp = $argumentName;

                    if ($default) {
                        $tmp .= '[=' . strval($default) . ']';
                    }

                    $qArguments .= " $tmp";
                }
                $qArguments = (trim($qArguments) == 'command') ? false : trim($qArguments);
            }
        }

        if ($qArguments)
        {
            $params = $document->createElement('params', $qArguments);
            $command->appendChild($params);
        }

        $framework->appendChild($command);
    }

    $document->appendChild($framework);
    $document->formatOutput = true;
    $cwd = getcwd();

    if (!is_dir('.idea/commandlinetools')) {
        mkdir('.idea/commandlinetools', 0777, true);
        $output->writeln("<info>Directory $cwd/.idea/commandlinetools created</info>");
    }

    if (!is_dir('.idea/commandlinetools/schemas')) {
        mkdir('.idea/commandlinetools/schemas', 0777, true);
        $output->writeln("<info>Directory $cwd/.idea/commandlinetools/schemas created</info>");
    }

    $schema = 'bin/idehelper/' . $schemaName;
    $schemaTarget = '.idea/commandlinetools/schemas/';

    if (!file_exists($schemaTarget . $schemaName)) {
        copy($schema, $schemaTarget . $schemaName);
        $output->writeln("<info>File $cwd/{$schemaTarget}{$schemaName} created</info>");
    }

    file_put_contents('.idea/commandlinetools/Batman_PHP.xml', $document->saveXML());
    $output->writeln("<info>File $cwd/.idea/commandlinetools/Batman_PHP.xml created</info>");
}