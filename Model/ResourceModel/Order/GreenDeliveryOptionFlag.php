<?php


declare(strict_types=1);

namespace MageWorx\GreenDeliveryBase\Model\ResourceModel\Order;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use MageWorx\GreenDeliveryBase\Api\Data\OrderGreenDeliveryOptionFlagInterface;

class GreenDeliveryOptionFlag extends AbstractDb
{
    protected $_eventPrefix = 'mageworx_greendelivery_option_flag_order';
    protected $_eventObject = 'mageworx_greendelivery_option_flag_order';

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init('mageworx_greendelivery_option_order', OrderGreenDeliveryOptionFlagInterface::ENTITY_ID);
    }
}
