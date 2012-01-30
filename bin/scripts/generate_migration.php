<?php
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;

function script(InputInterface $input, OutputInterface $output)
{
    $schema = ucfirst($input->getArgument('schema'));
    $tableName = strtolower($schema);
    $map = strtolower($input->getArgument('map'));
    $targetFile = "app/migration/Schema/{$schema}Migrate.php";
    $tpl = file_get_contents('bin/templates/migration_generator.data');
    $gMap = "\$table = \$this->schema->createTable('$tableName');";
    $pKey = $data = array();
    $datetimeExists = false;
    foreach (explode(' ', $map) as $element) {
        list($column, $type) = explode(':', $element);
        $data[$column] = array('type' => $type);
        if ($type == 'integer' && preg_match('/^(?:id(?:.+)?|(?:.+)?id)$/', $column)) {
            $pKey[] = $column;
        }
        if (preg_match('/date/', $type)) {
            $datetimeExists = true;
        }
    }
    /**
     * Intellect
     */
    $oldData = $data;
    $data = array();
    if (count($pKey) == 0 && !isset($oldData['id'])) {
        $data['id'] = array('type' => 'integer', 'unsigned' => true, 'autoincrement' => true);
        $pKey[] = 'id';
    }
    $data = array_replace_recursive($data, $oldData);
    if (!$datetimeExists) {
        $data['created'] = array('type' => 'datetime');
    }
    foreach ($data as $_column => $_params) {
        $_type = $_params['type'];
        switch ($_column) {
            case 'username':
            case 'user_name':
            case 'login':
                if ($_type == 'string') {
                    $data[$_column]['length'] = '32';
                }
                break;
            case 'password':
            case 'pass':
            case 'userpass':
            case 'user_password':
                if ($_type == 'string') {
                    $data[$_column]['length'] = '32';
                }
                break;
            case 'user_email':
            case 'email':
            case 'mail':
            case 'user_skype':
            case 'user_jabber':
            case 'skype':
            case 'skype_id':
            case 'jabber':
            case 'jabber_id':
                if ($_type == 'string') {
                    $data[$_column]['length'] = '45';
                }
                break;
            case 'message':
            case 'message_text':
            case 'post_message':
            case 'comment':
            case 'text':
            case 'msg':
            case 'user_agent':
            case 'useragent':
            case 'session_data':
            case 'data':
                if ($_type == 'string') {
                    $data[$_column]['length'] = '150';
                }
                break;
            case 'hash':
            case 'key':
                if ($_type == 'string') {
                    $data[$_column]['length'] = '32';
                }
                break;
            default:
                if ($_type == 'string') {
                    $data[$_column]['length'] = '50';
                }
        }
        $tmp = $data[$_column];
        unset($tmp['type']);
        $joined = '';
        if (count($tmp) > 0) {
            foreach ($tmp as $key => $value) {
                if ($value === true)
                    $value = 'true';
                if ($value === false)
                    $value = 'false';
                if ($value != 'true' and $value != 'false')
                    $value = "'$value'";
                $joined .= "'$key' => $value,";
            }
            $joined = rtrim($joined, ',');
            $joined = ", array($joined)";
        }
        $gMap .= "\n        \$table->addColumn('{$_column}', \$this->{$_type}{$joined});";
    }
    $gMap .= "\n        \$table->setPrimaryKey(array('{$pKey[0]}'));";
    file_put_contents($targetFile, str_replace(array('{%=Schema=%}', '{%=Map=%}'), array($schema, $gMap), $tpl));
    $output->writeln(sprintf('<info>Migration schema %s generated successful</info>', $targetFile));
}