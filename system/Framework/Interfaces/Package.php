<?php
namespace Framework\Interfaces;

interface Package
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
