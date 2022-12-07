<?php

namespace TUTJunior\CancelOrder\Api\Data;

interface ItemArrayReponseInterface
{
    /**
     * @return \TUTJunior\CancelOrder\Api\DataArrayInterface[]
     */
    public function getItemArray();

    /**
     * @param array $item
     * @return $this
     */
    public function setItemArray(array $item);
}
