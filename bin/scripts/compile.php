<?php
use \Symfony\Component\Console\Input\InputInterface,
    \Symfony\Component\Console\Output\OutputInterface,
    \Symfony\Component\Yaml\Yaml;

function script($server, OutputInterface $output)
{
    if ($server == 'apache')
    {
        $rules = file_get_contents('app/config/htaccess.txt') .
            "\n\nRewriteEngine On\nRewriteBase /";
        $template = "\nRewriteRule ^%p%$ /index.php?%r% [L]";
    }
    elseif ($server == 'nginx')
    {
        $rules = file_get_contents('app/config/nginx.inc.txt');
        $template = "\nrewrite ^/%p%$ /index.php?%r% last;";
    }
    else
    {
        throw new \Exception('<error>WTF?</error>');
    }

    foreach (Yaml::parse('app/config/routing.yml') as $rule)
    {
        $rule['pattern'] = preg_replace(
            '/\{([^}]+):([^}]+)\}/e', '\Framework\Core\Config::get("$1", "$2")', $rule['pattern']
        );

        $rule['route'] = preg_replace(
            '/\{([^}]+):([^}]+)\}/e', '\Framework\Core\Config::get("$1", "$2")', $rule['route']
        );

        $output->writeln('routing ' . $rule['pattern']);

        preg_match_all('/<([^<]+)>/', $rule['pattern'], $overloadVars);

        if (isset($overloadVars[1]))
        {
            $varId = 0;
            foreach ($overloadVars[1] as $var) {
                $rule['route'] = str_replace('$' . $var, '$' . (++$varId), $rule['route']);
            }
        }

        $rule['pattern'] = preg_replace('/<[^<]+>/', '([^/]+)', $rule['pattern']);
        if (isset($rule['backslash']) && $rule['backslash']) {
            $rule['pattern'] .= '(?:/)?';
        }

        $rule['pattern'] = ltrim($rule['pattern'], '/');
        $q = str_replace(array('%p%', '%r%'), array($rule['pattern'], $rule['route']), $template);
        $output->writeln('<info>' . trim($q) . '</info>');
        $rules .= $q;
        $output->writeln('============================================================');
    }

    $target =  ($server == 'apache') ? 'app/root/.htaccess' : 'nginx.inc';
    file_put_contents($target, trim($rules));
    $output->writeln('Complete!');
}