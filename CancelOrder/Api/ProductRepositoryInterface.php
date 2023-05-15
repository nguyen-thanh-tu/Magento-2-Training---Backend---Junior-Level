<?php

namespace TUTJunior\CancelOrder\Api;

interface ProductRepositoryInterface
{
    /**
     * Return a filtered product.
     *
     * @param int $id
     * @return \TUTJunior\CancelOrder\Api\ResponseItemInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getItem(int $id);

    /**
     * Set descriptions for the products.
     *
     * @param \TUTJunior\CancelOrder\Api\RequestItemInterface[] $products
     * @return void
     */
    public function setDescription(array $products);
}
