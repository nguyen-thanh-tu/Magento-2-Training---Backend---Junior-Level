<?php

namespace TUTJunior\HandlePageRedirection\Observer;

use Magento\Framework\Event\ObserverInterface;

class Productsaveafter implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $_product = $observer->getProduct();
        $price = $_product->getPrice();
        $specialPrice = $_product->getSpecialPrice();
        if($specialPrice >= $price){
            $_product->setIsSaleoff(0);
            $_product->setUrlKey($this->slugify($_product->getName()));
        } else {
            $_product->setIsSaleoff(1);
            $_product->setUrlKey('sale/'.$_product->getSku());
        }// you will get product object
    }

    public function slugify($text, string $divider = '-'): string
    {
        // replace non letter or digits by divider
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, $divider);

        // remove duplicate divider
        $text = preg_replace('~-+~', $divider, $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}

