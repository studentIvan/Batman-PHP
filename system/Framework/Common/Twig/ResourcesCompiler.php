<?php
namespace Framework\Common\Twig;
use Assetic\Asset\AssetCollection;
use Assetic\Asset\FileAsset;
use Assetic\Filter\LessFilter;

class ResourcesCompiler extends \Twig_Compiler
{
    public function compile(\Twig_NodeInterface $node, $indentation = 0)
    {
        parent::compile($node, $indentation);
        $this->source = preg_replace_callback(
            '/(?:\#rcss\:([^:]+)\:endrcss)(?:(?:\s+?\/\/\sline\s\d+\s+?)?echo\s\"\s+?\";\s+?\#rcss\:([^:]+)\:endrcss)+/s',
            array($this, '_cssAssetic'), $this->source
        );
        $this->source = preg_replace_callback(
            '/(?:\#rjs\:([^:]+)\:endrjs)(?:(?:\s+?\/\/\sline\s\d+\s+?)?echo\s\"\s+?\";\s+?\#rjs\:([^:]+)\:endrjs)+/s',
            array($this, '_jsAssetic'), $this->source
        );
        $this->source = preg_replace('/\#r(?:css|js)\:([^:]+)\:endr(?:css|js)/', "echo '$1\n';", $this->source);
        return $this;
    }

    protected function _cssAssetic($matches)
    {
        preg_match_all('/#rcss:.+?href="(.+?)"\>:endrcss/', $matches[0], $styles);
        $assetCache = 'app/root/styles/asset/' . md5(join('', $styles[1])) . '.css';
        $assets = array();
        foreach($styles[1] as $style) {
            $assets[] = (strrpos($style, '.less') === false) ?
                new FileAsset("app/root/$style") :
                new FileAsset("app/root/$style", array(new LessFilter()));
        }
        $css = new AssetCollection($assets, array());
        file_put_contents($assetCache, $css->dump());
        $assetHref = str_replace('app/root', '', $assetCache);
        return "echo '<link rel=\"stylesheet\" type=\"text/css\" href=\"$assetHref\">\n';";
    }

    protected function _jsAssetic($matches)
    {
        preg_match_all('/#rjs:.+?href="(.+?)"\>:endrjs/', $matches[0], $scripts);
        $assetCache = 'app/root/javascripts/asset/' . md5(join('', $scripts[1])) . '.js';
        $assets = array();
        foreach($scripts[1] as $script) {
            $assets[] = new FileAsset("app/root/$script");
        }
        $js = new AssetCollection($assets, array());
        file_put_contents($assetCache, $js->dump());
        $assetHref = str_replace('app/root', '', $assetCache);
        return "echo '<script src=\"$assetHref\" type=\"text/javascript\"></script>';";
    }
}
