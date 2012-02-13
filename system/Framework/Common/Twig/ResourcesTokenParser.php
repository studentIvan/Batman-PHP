<?php
namespace Framework\Common\Twig;

class ResourcesTokenParser extends \Twig_TokenParser
{
    function parse(\Twig_Token $token)
    {
        $lineno = $token->getLine();
        $type = $this->parser->getStream()->expect(\Twig_Token::NAME_TYPE)->getValue();
        $this->parser->getStream()->expect(\Twig_Token::PUNCTUATION_TYPE, '.');
        $name = $this->parser->getStream()->expect(\Twig_Token::NAME_TYPE)->getValue();
        $this->parser->getStream()->expect(\Twig_Token::BLOCK_END_TYPE);
        return new ResourcesSetNode($type, $name, $lineno, $this->getTag());
    }

    function getTag()
    {
        return 'resource';
    }
}
