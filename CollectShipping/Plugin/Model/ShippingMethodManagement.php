<?php

namespace TUTJunior\CollectShipping\Plugin\Model;

use Magento\Quote\Api\Data\AddressInterface;

class ShippingMethodManagement
{
    public function __construct
    (
        \Magento\Framework\Webapi\Rest\Request $request,
        \Magento\Customer\Api\AddressRepositoryInterface $addressRepository
    )
    {
        $this->addressRepository = $addressRepository;
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
            $output = $this->freeFlatrate($output);
        }
        return $output;
    }

    public function afterEstimateByAddressId
    (
        \Magento\Quote\Model\ShippingMethodManagement $shippingMethodManagement,
        $output,
        $cartId,
        $addressId
    )
    {
        $addressObject = $this->addressRepository->getById($addressId);
        if(!empty($addressObject->getCustomAttribute('account_number')))
        {
            $output = $this->freeFlatrate($output);
        }
        return $output;
    }

    private function freeFlatrate($output)
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
        return $output;
    }
}
