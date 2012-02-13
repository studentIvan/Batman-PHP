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
                $resource = "<script src=\"$resource\" type=\"text/javascript\"></script>";
                $compiler->write("#rjs:$resource:endrjs\n");
                break;
            case 'css':
                $resource = "<link rel=\"stylesheet\" type=\"text/css\" href=\"$resource\">";
                $compiler->write("#rcss:$resource:endrcss\n");
                break;
            default:
                $compiler->write("echo '$resource\n';\n");
        }
    }
}
