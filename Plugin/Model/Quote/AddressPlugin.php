<?php
/**
 * Copyright © Excellence Pvt Ltd. All rights reserved.
 * See COPYING.txt for license details.
 * https://www.xmagestore.com | support@xmagestore.com
 */

/*
* This plugin set our shipping method with their corresponding rate
*/
namespace Excellence\CustomShippingRate\Plugin\Model\Quote;

class AddressPlugin
{

    /**
     * Around plugin to collect out custom shipping details with custom shiping Address
     * @param \Magento\Quote\Api\Data\AddressInterface $subject
     * @param callable $proceed
     * @return mixed
     */
    public function aroundCollectShippingRates(\Magento\Quote\Api\Data\AddressInterface $subject, callable $proceed)
    {
        $price = null;
        $description = null;

        //get custom shipping rate set by admin
        foreach ($subject->getAllShippingRates() as $rate) {
            if ($rate->getCode() == $subject->getShippingMethod()) {
                $price = $rate->getPrice();
                $description = $rate->getMethodTitle();
                break;
            }
        }

        $return = $proceed();

        if ($price !== null) {
            //reset custom shipping rate
            foreach ($subject->getAllShippingRates() as $rate) {
                if ($rate->getCode() == $subject->getShippingMethod()) {
                    $rate->setPrice($price);
                    $rate->setCost($price);
                    $rate->setMethodTitle($description);
                    break;
                }
            }
        }

        return $return;
    }
}