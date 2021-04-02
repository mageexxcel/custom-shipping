<?php
/**
 * Copyright © Excellence Pvt Ltd. All rights reserved.
 * See COPYING.txt for license details.
 * https://www.xmagestore.com | support@xmagestore.com
 */

/*
* This file is to serialize the scope config data into one single config data to store in
*/
namespace Excellence\CustomShippingRate\Config\Backend\Serialized;

class ArraySerialized extends \Magento\Config\Model\Config\Backend\Serialized\ArraySerialized
{
    protected $helper;

    /**
     * ArraySerialized constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $config
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param \Excellence\CustomShippingRate\Helper\Data $helperData
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        \Excellence\CustomShippingRate\Helper\Data $helperData,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $config,
            $cacheTypeList,
            $resource,
            $resourceCollection,
            $data
        );

        $this->helper = $helperData;
    }

    /**
     * @return void
     */
    protected function _afterLoad()
    {
        parent::_afterLoad();

        $this->setValue($this->helper->shippingArrayObject($this->getValue()));
    }
}
