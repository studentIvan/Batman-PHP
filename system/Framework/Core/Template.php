<?php
namespace Framework\Core;
use \Framework\Core\Config;
 
class Template {

    protected $matches = array();
    protected $bundle;
    protected $engine;
    protected $runner = false;

    /**
     * @param string $bundle
     */
    public function __construct($bundle = 'Bootstrap') {
        $engine = Config::get('application', 'template_engine');
        $this->engine = ($engine) ? $engine : 'Native';
        $this->bundle = $bundle;
    }

    /**
     * @param string $bundle
     * @return \Twig_Environment
     */
    protected function _init_Twig($bundle = 'Main') {
        $loader = new \Twig_Loader_Filesystem(realpath("app/logic/$bundle/Views"));
        $object = new \Twig_Environment($loader, Config::get('twig'));
        foreach (Config::get('twig', 'extensions') as $extName) {
            $loadStr = "\\Framework\\Common\\Twig\\{$extName}Extension";
            $object->addExtension(new $loadStr());
        }
        return $object;
    }

    /**
     * Match template variables
     *
     * @example $this->tpl->match('name', 'Ivan');
     * @example $this->tpl->match(array('name' => 'Ivan'));
     * @example $this->tpl->match(array('lorem' => 'Ispum', 'dolor' => 'Sit Amet'));
     * @param string|array $data Name of template-var or associative array with keys and values
     * @param string|bool $_data Value of variable (for template-var set)
     * @return \Framework\Core\Template
     */
    public function match($data, $_data = false)
    {
        if (is_array($data))
        {
            /**
             * associative array set
             */
            $this->matches = (count($this->matches) > 0)
                    ? array_replace_recursive($this->matches, $data) : $data;
        }
        else
        {
            /**
             * template-var set
             */
            $this->matches[$data] = $_data;
        }

        return $this;
    }

    /**
     * @param string $template
     * @return string
     */
    protected function _render_Twig($template)
    {
        if (!$this->runner) $this->runner = $this->_init_Twig($this->bundle);
        return $this->runner->render($template . '.html.twig', $this->matches);
    }

    /**
     * @param string $template
     * @return string
     */
    protected function _render_Native($template)
    {
        $this->runner = $template; unset($template);
        ob_start(); extract($this->matches, EXTR_SKIP);
        include "app/logic/{$this->bundle}/Views/{$this->runner}.phtml";
        return ob_get_clean();
    }

    /**
     * Render template data and return as string
     *
     * @param string $template Name of template-file (without extension)
     * @param bool|string $engine Specific template engine
     * @return string
     */
    public function render($template, $engine = false) {
        $callStr = '_render_' . (($engine) ? ucfirst($engine) : $this->engine);
        return $this->$callStr($template);
    }
}
