<?php
/**
 * Copyright © Excellence Pvt Ltd. All rights reserved.
 * See COPYING.txt for license details.
 * https://www.xmagestore.com | support@xmagestore.com
 */

namespace Excellence\CustomShippingRate\Block\Adminhtml\System\Config\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

/**
 * Class Locations Backend system config array field renderer
 */
class ShippingList extends AbstractFieldArray
{
    protected $helper;

    /**
     * ShippingList constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Excellence   \CustomShippingRate\Helper\Data $helperData
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Excellence\CustomShippingRate\Helper\Data $helperData,
        array $data = []
    ) {
        $this->helper = $helperData;
        parent::__construct($context, $data);
    }

    /**
     * Initialise columns for 'Store Locations'
     * Label is name of field
     * Class is storefront validation action for field
     *
     * @return void
     */
    protected function _construct()
    {
        foreach ($this->helper->getHeaderColumns() as $key => $column) {
            $this->addColumn(
                $key,
                [
                    'label' => __($column['label']),
                    'class' => $column['class']
                ]
            );
        }

        $this->_addAfter = false;
        parent::_construct();
    }
}