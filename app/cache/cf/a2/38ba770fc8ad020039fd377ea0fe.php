<?php

/* hello.html.twig */
class __TwigTemplate_cfa238ba770fc8ad020039fd377ea0fe extends Twig_Template
{
    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "Hello ";
        if (isset($context["name"])) { $_name_ = $context["name"]; } else { $_name_ = null; }
        echo twig_escape_filter($this->env, $_name_, "html", null, true);
        echo "!";
    }

    public function getTemplateName()
    {
        return "hello.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }
}
