<?php

namespace TUTJunior\CancelOrder\Api;

interface DataArrayInterface
{
    /**
     * @return mixed
     */
    public function getName();

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name);

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @param mixed $value
     * @return $this
     */
    public function setValue($value);
}
