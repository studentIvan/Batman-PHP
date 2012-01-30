<?php
namespace Framework\Interfaces;
use \Doctrine\DBAL\Platforms\AbstractPlatform;
use \Doctrine\DBAL\Connection;

interface SchemaInterface
{
    public function __invoke();
    public function create(AbstractPlatform $platform, Connection $conn);
    public function save(AbstractPlatform $platform, $path);
    public function __construct();
}
