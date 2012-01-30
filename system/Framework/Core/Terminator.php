<?php
namespace Framework\Core;
use \Exceptions\NotFoundException;
use \ErrorException;
 
class Terminator {

    /**
     * @static
     * @throws \ErrorException
     * @param int $errno
     * @param string $errstr
     * @param string $errfile
     * @param int $errline
     * @return void
     */
    public static function terminate($errno, $errstr, $errfile, $errline) {
        if ($errno == 4096 and (strpos($errstr, 'WebRequest, string given') !== false)) {
            throw new NotFoundException();
        } else {
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        }
    }
}