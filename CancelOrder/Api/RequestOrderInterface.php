<?php

namespace TUTJunior\CancelOrder\Api;

interface RequestOrderInterface
{
    const DATA_ID = 'id';

    /**
     * @return string
     */
    public function getId();

    /**
     * @param string $id
     * @return $this
     */
    public function setId(int $id);
}
