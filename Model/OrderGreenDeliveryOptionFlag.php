<?php


declare(strict_types=1);

namespace MageWorx\GreenDeliveryBase\Model;

use MageWorx\GreenDeliveryBase\Api\Data\OrderGreenDeliveryOptionFlagInterface;
use Magento\Framework\Model\AbstractModel;

class OrderGreenDeliveryOptionFlag extends AbstractModel implements OrderGreenDeliveryOptionFlagInterface
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(\MageWorx\GreenDeliveryBase\Model\ResourceModel\Order\GreenDeliveryOptionFlag::class);
    }

    /**
     * @return int
     */
    public function getOrderId(): int
    {
        return (int)$this->getData(self::ORDER_ID);
    }

    /**
     * @param int $orderId
     * @return OrderGreenDeliveryOptionFlagInterface
     */
    public function setOrderId(int $orderId): OrderGreenDeliveryOptionFlagInterface
    {
        return $this->setData(self::ORDER_ID, $orderId);
    }

    /**
     * @return bool
     */
    public function getValue(): bool
    {
        return (bool)$this->getData(self::VALUE);
    }

    /**
     * @param bool $value
     * @return OrderGreenDeliveryOptionFlagInterface
     */
    public function setValue(bool $value): OrderGreenDeliveryOptionFlagInterface
    {
        return $this->setData(self::VALUE, $value);
    }
}
