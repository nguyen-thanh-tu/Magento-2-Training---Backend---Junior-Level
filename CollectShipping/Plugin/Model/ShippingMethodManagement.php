<?php

namespace TUTJunior\CollectShipping\Plugin\Model;

use Magento\Quote\Api\Data\AddressInterface;

class ShippingMethodManagement
{
    public function __construct
    (
        \Magento\Framework\Webapi\Rest\Request $request
    )
    {
        $this->_request = $request;
    }

    public function afterEstimateByExtendedAddress
    (
        \Magento\Quote\Model\ShippingMethodManagement $shippingMethodManagement,
        $output,
        $cartId,
        AddressInterface $address
    )
    {
        return $this->filterOutput($output, $address);
    }

    private function filterOutput($output, AddressInterface $address)
    {
        $params = $this->_request->getBodyParams();

        if(!empty($params['address']['custom_attributes']))
        foreach($params['address']['custom_attributes'] as $customAttribute)
        {
            if(!empty($customAttribute['attribute_code'] === 'account_number') && strlen($customAttribute['value']) <= 6)
            {
                $address->setAccountNumber($customAttribute['value']);
            }
        }

        if(!empty($address->getAccountNumber()))
        {
            $free = [];
            foreach ($output as $shippingMethod) {
                if ($shippingMethod->getCarrierCode() === 'flatrate' && $shippingMethod->getMethodCode() === 'flatrate') {
                    $free[] = $shippingMethod
                        ->setPriceInclTax(0)
                        ->setPriceExclTax(0)
                        ->setBaseAmount(0)
                        ->setAmount(0);
                    break;
                }
            }
            if ($free) {
                return $free;
            }
        }
        return $output;
    }
}
