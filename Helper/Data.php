<?php
/**
 * Copyright © Excellence Pvt Ltd. All rights reserved.
 * See COPYING.txt for license details.
 * https://www.xmagestore.com | support@xmagestore.com
 */

namespace Excellence\CustomShippingRate\Helper;

use Magento\Store\Model\ScopeInterface;
use Excellence\CustomShippingRate\Model\Carrier;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var array
     */
    protected $shippingType;

    /**
     * @var array
     */
    protected $codes = [
        
        'code' => [
            'label' => 'Code',
            'class' => 'validate-no-empty validate-data',
            'default' => ''
        ],
        'title' => [
            'label' => 'Title',
            'class' => 'validate-no-empty',
            'default' => ''
        ],
        'price' => [
            'label' => 'Price',
            'class' => 'validate-no-empty greater-than-equals-to-0',
            'default' => ''
        ],
        'sort_order' => [
            'label' => 'Admin Sort',
            'class' => 'validate-no-empty greater-than-equals-to-0',
            'default' => 99
        ]
    ];

    /**
     * @var array
     */
    protected $headerTemplate;

    /**
     * @return array|mixed
     */
    public function getShippingType()
    {
        if (!$this->shippingType) {
            $arrayValues = [];
            $configData = $this->getConfigData('shipping_type');

            if (is_string($configData) && !empty($configData) && $configData !== '[]') {
                if ($this->isJson($configData)) {
                    $arrayValues = (array) json_decode($configData, true);
                } else {
                    $arrayValues = (array) array_values(unserialize($configData));
                }
            }

            $arrayValues = $this->shippingArrayObject($arrayValues);

            usort($arrayValues, function ($a, $b) {
                if (array_key_exists('sort_order', $a)) {
                    return $a['sort_order'] - $b['sort_order'];
                } else {
                    return 0;
                }
            });

            $this->shippingType = $arrayValues;
        }

        return $this->shippingType;
    }

    /**
     * input {code}_{method}
     * return method
     * @param $method_code
     * @return string
     */
    public function getShippingCodeFromMethod($method_code)
    {
        foreach ($this->getShippingType() as $shippingType) {
            if (Carrier::CODE . '_' . $shippingType['code'] == $method_code) {
                return $shippingType['code'];
                break;
            }
        }

        return '';
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->scopeConfig->isSetFlag('carriers/' . Carrier::CODE . '/active');
    }

    /**
     * Retrieve information from carrier configuration
     *
     * @param   string $field
     * @return  string
     */
    public function getConfigData($field)
    {
        return $this->scopeConfig->getValue(
            'carriers/' . Carrier::CODE . '/' . $field,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Magento 2.2 return json instead of serialize array
     *
     * @param   string $string
     * @return  bool
     */
    public function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * @return array
     */
    public function getHeaderTemplate()
    {
        if (!$this->headerTemplate) {
            $this->headerTemplate = [];

            foreach ($this->getHeaderColumns() as $key => $column) {
                $this->headerTemplate[$key] = $column['default'];
            }
        }

        return $this->headerTemplate;
    }

    /**
     * @return array
     */
    public function getHeaderColumns()
    {
        return $this->codes;
    }

    /**
     * @param $values
     * @return mixed
     */
    public function shippingArrayObject($values)
    {
        //fix existing options
        $requiredFields = $this->getHeaderTemplate();

        if (is_array($values)) {
            foreach ($values as &$row) {
                $row = array_merge($requiredFields, $row);
            }
        }

        return $values;
    }
}
