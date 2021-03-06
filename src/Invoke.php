<?php
namespace Componentary;

/**
 * External Invoke Builder
 *
 * @package Componentary
 * @author  undercloud <lodashes@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 * @link    http://github.com/undercloud/componentary
 */
class Invoke
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $args = [];

    /**
     * @var array
     */
    protected $prototype = [];

    /**
     * @param string $name invoke
     * @param array  $prototype arguments
     */
    public function __construct($name = 'undefinedInvoke', array $prototype = [])
    {
        $this->name = $name;
        $this->prototype = $prototype;
    }

    /**
     * Set invoke name
     *
     * @param string $name key
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get invoke name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set prototype
     *
     * @param array $prototype declaration
     *
     * @return self
     */
    public function setPrototype(array $prototype)
    {
        $this->prototype = $prototype;

        return $this;
    }

    /**
     * Get prototype
     *
     * @return array
     */
    public function getPrototype()
    {
        return $this->prototype;
    }

    /**
     * Check if argument exists
     *
     * @param string $name key
     *
     * @return boolean
     */
    public function hasArg($name)
    {
        return isset($this->args[$name]);
    }

    /**
     * Set argument
     *
     * @param string $name key
     * @param mixed  $val  value
     *
     * @return self
     */
    public function setArg($name, $val)
    {
        $this->args[$name] = $val;

        return $this;
    }

    /**
     * Get argument
     *
     * @param string $name key
     *
     * @return mixed
     */
    public function getArg($name)
    {
        if ($this->hasArg($name)) {
            return $this->args[$name];
        }
    }

    /**
     * Set argument from map
     *
     * @param array   $args   array
     * @param boolean $append save setuped values
     *
     * @return self
     */
    public function setArgs(array $args, $append = true)
    {
        $this->args = array_merge($append ? $this->args : [], $args);

        return $this;
    }

    /**
     * Get arguments
     *
     * @return array
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * Magic __set
     *
     * @param string $name key
     * @param mixed  $val  value
     *
     * @return null;
     */
    public function __set($name, $val)
    {
        $this->setArg($name, $val);
    }

    /**
     * Get argument
     *
     * @param string $name key
     *
     * @return mixed
     */
    public function __get($name)
    {
        return $this->getArg($name);
    }

    /**
     * Stringify arguments
     *
     * @return string
     */
    protected function buildArgs()
    {
        $order = (
            $this->prototype
                ? array_merge(array_flip($this->prototype), $this->args)
                : $this->args
        );

        $mapper = function ($item) {
            switch (gettype($item)) {
                case 'boolean':
                    return $item ? 'true' : 'false';
                case 'string':
                    return "'" . addslashes($item) . "'";
                case 'array':
                case 'object':
                    return Utils::toJson($item);
                case 'integer':
                case 'double':
                    return $item;
                case 'NULL':
                default:
                    return 'null';
            }
        };

        return implode(',', array_map($mapper, $order));
    }

    /**
     * __toString magic
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name . '(' . $this->buildArgs() . ');';
    }
}
