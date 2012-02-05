<?php
namespace Framework\Common;

abstract class Model
{
    /**
     * Object to Array (Alias)
     *
     * @return array
     */
    public function __invoke() {
        return $this->toArray();
    }

    /**
     * Object to Array
     *
     * @return array
     */
    public function toArray() {
        return get_object_vars($this);
    }

    /**
     * Object Functions
     *
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments) {
        $cmd = substr($name, 0, 3);
        $var = strtolower(str_replace($cmd, '', $name));
        if ($cmd == 'set') {
            if (isset($arguments[0]) && isset($var)) {
                $this->$var = $arguments[0];
                return $this;
            }
        } elseif ($cmd == 'get') {
            return (isset($this->$var)) ? $this->$var : null;
        }
    }
}
