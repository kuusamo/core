<?php

declare(strict_types=1);

namespace Kuusamo\Vle\Helper;

use ArrayAccess;

class Collection implements ArrayAccess
{
    private $data;

    /**
     * Constructor.
     *
     * @param array $data Data.
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * isset($collection['key'])
     *
     * @param mixed $offset Key.
     * @return boolean
     */
    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }

    /**
     * $collection['key']
     *
     * @param mixed $offset Key.
     * @return mixed
     */
    public function offsetGet($offset): mixed
    {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    /**
     * $collection['key'] = 'value'
     *
     * @param mixed $offset Key.
     * @param mixed $value  Value.
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        $this->data[$offset] = $value;
    }

    /**
     * unset($collection['key'])
     *
     * @param mixed $offset Key.
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);
    }
}
