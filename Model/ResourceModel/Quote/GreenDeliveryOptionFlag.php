<?php


declare(strict_types=1);

namespace MageWorx\GreenDeliveryBase\Model\ResourceModel\Quote;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use MageWorx\GreenDeliveryBase\Api\Data\QuoteGreenDeliveryOptionFlagInterface;

class GreenDeliveryOptionFlag extends AbstractDb
{
    protected $_eventPrefix = 'mageworx_greendelivery_option_flag_quote';
    protected $_eventObject = 'mageworx_greendelivery_option_flag_quote';

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init('mageworx_greendelivery_option_quote', QuoteGreenDeliveryOptionFlagInterface::ENTITY_ID);
    }
}

