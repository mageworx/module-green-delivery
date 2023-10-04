<?php


declare(strict_types=1);

namespace MageWorx\GreenDeliveryBase\Model;

use MageWorx\GreenDeliveryBase\Api\OrderGreenDeliveryOptionFlagRepositoryInterface;
use MageWorx\GreenDeliveryBase\Api\Data\OrderGreenDeliveryOptionFlagInterfaceFactory;
use MageWorx\GreenDeliveryBase\Api\Data\OrderGreenDeliveryOptionFlagInterface;
use MageWorx\GreenDeliveryBase\Model\ResourceModel\Order\GreenDeliveryOptionFlag as ResourceModel;

class OrderGreenDeliveryOptionFlagRepository implements OrderGreenDeliveryOptionFlagRepositoryInterface
{
    /**
     * @var ResourceModel
     */
    protected $resource;

    /**
     * @var OrderGreenDeliveryOptionFlagInterfaceFactory
     */
    protected $greenDeliveryOptionFlagFactory;

    /**
     * @param ResourceModel $resource
     * @param OrderGreenDeliveryOptionFlagInterfaceFactory $greenDeliveryOptionFlagFactory
     */
    public function __construct(
        ResourceModel $resource,
        OrderGreenDeliveryOptionFlagInterfaceFactory $greenDeliveryOptionFlagFactory
    ) {
        $this->resource                       = $resource;
        $this->greenDeliveryOptionFlagFactory = $greenDeliveryOptionFlagFactory;
    }

    /**
     * @param OrderGreenDeliveryOptionFlagInterface $greenDeliveryOptionFlag
     * @return OrderGreenDeliveryOptionFlagInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(
        OrderGreenDeliveryOptionFlagInterface $greenDeliveryOptionFlag
    ): OrderGreenDeliveryOptionFlagInterface {
        try {
            $this->resource->save($greenDeliveryOptionFlag);
        } catch (\Exception $exception) {
            throw new  \Magento\Framework\Exception\CouldNotSaveException(
                __(
                    'Could not save the Green Delivery Option Flag: %1',
                    $exception->getMessage()
                )
            );
        }

        return $greenDeliveryOptionFlag;
    }

    /**
     * @param int $orderId
     * @return OrderGreenDeliveryOptionFlagInterface
     */
    public function getByOrderId(int $orderId): OrderGreenDeliveryOptionFlagInterface
    {
        /** @var OrderGreenDeliveryOptionFlagInterface $greenDeliveryOptionFlag */
        $greenDeliveryOptionFlag = $this->greenDeliveryOptionFlagFactory->create();

        $this->resource->load($greenDeliveryOptionFlag, $orderId, OrderGreenDeliveryOptionFlagInterface::ORDER_ID);

        return $greenDeliveryOptionFlag;
    }

    /**
     * @param int $orderId
     * @param bool $value
     * @return OrderGreenDeliveryOptionFlagInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function saveData(int $orderId, bool $value): OrderGreenDeliveryOptionFlagInterface
    {
        $greenDeliveryOptionFlag = $this->getByOrderId($orderId);
        $greenDeliveryOptionFlag->setOrderId($orderId);
        $greenDeliveryOptionFlag->setValue($value);

        return $this->save($greenDeliveryOptionFlag);
    }
}
