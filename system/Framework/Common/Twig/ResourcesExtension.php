<?php
namespace Framework\Common\Twig;

class ResourcesExtension extends \Twig_Extension
{
    public function getName() {
        return 'resources';
    }

    public function getTokenParsers() {
        return array(new ResourcesTokenParser());
    }
}
