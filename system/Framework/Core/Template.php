<?php
namespace Framework\Core;
use \Framework\Core\Config;
 
class Template {

    protected $matches = array();
    protected $bundle;
    protected $engine;

    /**
     * @param string $bundle
     */
    public function __construct($bundle) {
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
        $auto_reload = Config::get('twig', 'auto_reload');
        $use_cache = Config::get('twig', 'use_cache') ? 'app/cache' : false;
        $cfg = array('cache' => $use_cache, 'auto_reload' => $auto_reload);
        $object = new \Twig_Environment($loader, $cfg);
        return $object;
    }

    /**
     * @param string $bundle
     * @return void
     */
    protected function _init_Native($bundle = 'Main') {
        
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
    protected function _send_Twig($template)
    {
        $tpl = $this->_init_Twig($this->bundle);
        return $tpl->render($template . '.html.twig', $this->matches);
    }

    /**
     * @param string $template
     * @return string
     */
    protected function _send_Native($template) {
        return 'not complete';
    }

    /**
     * Send template data to user
     *
     * @param string $template Name of template-file (without extension)
     * @return void
     */
    public function send($template) {
        $callStr = '_send_' . $this->engine;
        return $this->$callStr($template);
    }
}
