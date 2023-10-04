<?php


declare(strict_types=1);

namespace MageWorx\GreenDeliveryBase\Plugin\Api\Order;

use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderExtensionInterface;
use MageWorx\GreenDeliveryBase\Model\ShippingDescriptionConverter;

class ChangeShippingDescriptionPlugin
{
    /**
     * @var ShippingDescriptionConverter
     */
    private $descriptionConverter;

    /**
     * @param ShippingDescriptionConverter $descriptionConverter
     */
    public function __construct(ShippingDescriptionConverter $descriptionConverter)
    {
        $this->descriptionConverter = $descriptionConverter;
    }

    /**
     * @param OrderRepositoryInterface $entity
     * @param OrderInterface $order
     * @return OrderInterface[]
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeSave(OrderRepositoryInterface $entity, OrderInterface $order): array
    {
        /** @var OrderExtensionInterface $extensionAttributes */
        $extensionAttributes = $order->getExtensionAttributes();

        if (null !== $extensionAttributes
            && $extensionAttributes->getMageWorxGreenDeliveryOptionFlag()
        ) {
            $description = $this->descriptionConverter->clear((string)$order->getShippingDescription());

            $order->setShippingDescription($this->descriptionConverter->convert($description));
        }

        return [$order];
    }
}
