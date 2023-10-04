<?php


declare(strict_types=1);

namespace MageWorx\GreenDeliveryBase\Api;

use MageWorx\GreenDeliveryBase\Api\Data\OrderGreenDeliveryOptionFlagInterface;

interface OrderGreenDeliveryOptionFlagRepositoryInterface
{
    /**
     * @param \MageWorx\GreenDeliveryBase\Api\Data\OrderGreenDeliveryOptionFlagInterface $orderGreenDeliveryOptionFlag
     * @return \MageWorx\GreenDeliveryBase\Api\Data\OrderGreenDeliveryOptionFlagInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(
        OrderGreenDeliveryOptionFlagInterface $orderGreenDeliveryOptionFlag
    ): OrderGreenDeliveryOptionFlagInterface;

    /**
     * @param int $orderId
     * @return \MageWorx\GreenDeliveryBase\Api\Data\OrderGreenDeliveryOptionFlagInterface
     */
    public function getByOrderId(int $orderId): OrderGreenDeliveryOptionFlagInterface;

    /**
     * @param int $orderId
     * @param bool $value
     * @return \MageWorx\GreenDeliveryBase\Api\Data\OrderGreenDeliveryOptionFlagInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function saveData(int $orderId, bool $value): OrderGreenDeliveryOptionFlagInterface;
}
