<?php
/**
 * Copyright © Excellence Pvt Ltd. All rights reserved.
 * See COPYING.txt for license details.
 * https://www.xmagestore.com | support@xmagestore.com
 */

/*
* This will override core file and methods of Form.php block
* This will render our form Block while fetching shipping methods while placing order
* from admin. and apply our custom shipping rate if enabled
*/

namespace Excellence\CustomShippingRate\Block\Adminhtml\Order\Create\Shipping\Method;

use Excellence\CustomShippingRate\Model\Carrier;

class Form extends \Magento\Sales\Block\Adminhtml\Order\Create\Shipping\Method\Form
{
    protected $activeMethodRate;

    /**
     * Grabbing active shipping methods
     *
     * @return string
     */
    public function getActiveCustomShippingRateMethod()
    {
        $rate = $this->getActiveMethodRate();
        return $rate && $rate->getCarrier() == Carrier::CODE ? $rate->getMethod() : '';
    }

    /**
     * Grabbing custom shipping rate
     *
     * @return string
     */
    public function getActiveCustomShippingRatePrice()
    {
        $rate = $this->getActiveMethodRate();
        return $this->getActiveCustomShippingRateMethod() && $rate->getPrice() ? $rate->getPrice() * 1 : '';
    }

    /**
     * Grabbing status of custom shiping method active or not
     *
     * @return boolean
     */
    public function isCustomShippingRateActive()
    {
        if (empty($this->activeMethodRate)) {
            $this->activeMethodRate = $this->getActiveMethodRate();
        }

        return $this->activeMethodRate && $this->activeMethodRate->getCarrier() == Carrier::CODE ? true : false;
    }

    /**
     * Retrieve array of shipping rates groups
     *
     * @return array
     */
    public function getGroupShippingRates()
    {
        $rates = $this->getShippingRates();

        if (array_key_exists(Carrier::CODE, $rates)) {
            if (!$this->isCustomShippingRateActive()) {
                unset($rates[Carrier::CODE]);
            } else {
                $activeRateMethod = $this->getActiveCustomShippingRateMethod();
                foreach ($rates[Carrier::CODE] as $key => $rate) {
                    if ($rate->getMethod() != $activeRateMethod) {
                        unset($rates[Carrier::CODE][$key]);
                    }
                }
            }
        }

        return $rates;
    }
}
