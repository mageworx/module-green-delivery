<?php


declare(strict_types=1);

namespace MageWorx\GreenDeliveryBase\Api\Data;

interface OrderGreenDeliveryOptionFlagInterface
{
    const ENTITY_ID = 'entity_id';
    const ORDER_ID  = 'order_id';
    const VALUE     = 'value';

    /**
     * @return int
     */
    public function getEntityId();

    /**
     * @param int $entityId
     * @return OrderGreenDeliveryOptionFlagInterface
     */
    public function setEntityId($entityId);

    /**
     * @return int
     */
    public function getOrderId(): int;

    /**
     * @param int $orderId
     * @return OrderGreenDeliveryOptionFlagInterface
     */
    public function setOrderId(int $orderId): OrderGreenDeliveryOptionFlagInterface;

    /**
     * @return bool
     */
    public function getValue(): bool;

    /**
     * @param bool $value
     * @return OrderGreenDeliveryOptionFlagInterface
     */
    public function setValue(bool $value): OrderGreenDeliveryOptionFlagInterface;
}
