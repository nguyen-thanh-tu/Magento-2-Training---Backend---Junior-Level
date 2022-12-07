<?php

namespace TUTJunior\CancelOrder\Api\Data;

interface OrderDetailReponseInterface
{
    /**
     * Get value
     *
     * @return \TUTJunior\CancelOrder\Api\DataArrayInterface[]
     */
    public function getOrderDetai();

    /**
     * @param array $orderDetai
     * @return $this
     */
    public function setOrderDetai(array $orderDetai);
}
