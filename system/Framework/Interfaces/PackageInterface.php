<?php
namespace Framework\Interfaces;

interface PackageInterface
{
    /**
     * Return package information
     *
     * @abstract
     * @return array
     */
    public function getPackageInfo();

    /**
     * Package constructor
     */
    public function __construct();
}
