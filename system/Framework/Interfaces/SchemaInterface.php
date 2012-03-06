<?php
namespace Framework\Interfaces;

use \Doctrine\DBAL\Platforms\AbstractPlatform,
    \Doctrine\DBAL\Connection;

interface SchemaInterface
{
    public function __invoke();
    public function create(Connection $conn, $path);
    public function save(AbstractPlatform $platform, $path);
    public function __construct();
}
