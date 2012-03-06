<?php
namespace Framework\Common\Twig;
use Assetic\Factory\AssetFactory;

class AsseticExtension extends \Assetic\Extension\Twig\AsseticExtension
{
    public function __construct()
    {
        $factory = new AssetFactory('app/root/');
        parent::__construct($factory);
    }
}
