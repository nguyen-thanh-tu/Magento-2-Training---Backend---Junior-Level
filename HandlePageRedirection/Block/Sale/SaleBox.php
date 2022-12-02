<?php

namespace TUTJunior\HandlePageRedirection\Block\Sale;

use Magento\Framework\View\Element\Template;

class SaleBox extends Template
{
    public function __construct
    (
        Template\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory  $productCollectionFactory,
        \Magento\Catalog\Block\Product\ListProduct $listProduct,
        array $data = []
    )
    {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->listProduct = $listProduct;
        parent::__construct($context, $data);
    }

    public function getProductSaleOffJsonData()
    {
        $data = [];
        $productCollection = $this->productCollectionFactory->create()->addAttributeToSelect('*')->addAttributeToFilter('is_saleoff',  1);
        foreach ($productCollection as $product)
        {
            $productImage = $this->listProduct->getImage($product, 'product_thumbnail_image');
            $data[] = ['url' => $product->getProductUrl(), 'image' => $productImage->getImageUrl()];
        }
        return json_encode($data);
    }
}
