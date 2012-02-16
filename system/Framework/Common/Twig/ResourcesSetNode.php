<?php
namespace Framework\Common\Twig;
use \Framework\Core\Config;

class ResourcesSetNode extends \Twig_Node
{
    protected $type, $name;

    public function __construct($type, $name, $lineno, $tag)
    {
        parent::__construct(array(), array(), $lineno, $tag);
        $this->type = $type;
        $this->name = $name;
    }

    public function compile(\Twig_Compiler $compiler)
    {
        Config::loadResources();

        if (!$resource = Config::get('resources.' . $this->type, $this->name))
        {
            throw new \Exception(
                'Resource resources.' . $this->type . ':' . $this->name . ' not found in app/config/resources.yml'
            );
        }

        switch ($this->type) {
            case 'js':
                $compiler->write("echo '<script src=\"$resource\" type=\"text/javascript\"></script>\n';\n");
                break;
            case 'css':
                $compiler->write("echo '<link rel=\"stylesheet\" type=\"text/css\" href=\"$resource\">\n';\n");
                break;
            default:
                $compiler->write("echo '$resource\n';\n");
        }
    }
}
