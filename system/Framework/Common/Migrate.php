<?php
namespace Framework\Common;
use \Doctrine\DBAL\Connection;
use \Doctrine\DBAL\Platforms\AbstractPlatform;
use \Doctrine\DBAL\Schema\Schema;

abstract class Migrate
{
    /**
     * Schema data-types:
     */
    public $string = 'string';
    public $integer = 'integer';
    public $datetime = 'datetime';
    public $time = 'time';
    public $bigint = 'bigint';
    public $smallint = 'smallint';
    public $boolean = 'boolean';
    public $date = 'date';
    public $decimal = 'decimal';
    public $object = 'object';
    public $text = 'text';

    /**
     * @var \Doctrine\DBAL\Schema\Schema
     */
    protected $schema;

    public function __construct() {
        $this->schema = new Schema();
        $this();
    }

    public function __invoke() {

    }

    public function save(AbstractPlatform $platform, $path) {
        $create = $this->schema->toSql($platform);
        $drop = $this->schema->toDropSql($platform);
        $name = $platform->getName();
        file_put_contents("$path.$name.create.sql", join("\n", $create));
        file_put_contents("$path.$name.drop.sql", join("\n", $drop));
    }

    public function create(AbstractPlatform $platform, Connection $conn) {
        $create = $this->schema->toSql($platform);
        $drop = $this->schema->toDropSql($platform);
        try {
            foreach ($create as $sql) $conn->query($sql);
        } catch (\PDOException $e) {
            foreach ($drop as $sql) $conn->query($sql);
            foreach ($create as $sql) $conn->query($sql);
        }
        $conn->close();
    }
}
