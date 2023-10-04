<?php


declare(strict_types=1);

namespace MageWorx\GreenDeliveryBase\Plugin\Api\Order;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderExtensionInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use MageWorx\GreenDeliveryBase\Api\Data\OrderGreenDeliveryOptionFlagInterface;
use MageWorx\GreenDeliveryBase\Api\OrderGreenDeliveryOptionFlagRepositoryInterface;
use MageWorx\GreenDeliveryBase\Model\ShippingDescriptionConverter;

class AddGreenDeliveryOptionAttributePlugin
{
    /**
     * @var OrderExtensionFactory
     */
    private $orderExtensionFactory;

    /**
     * @var OrderGreenDeliveryOptionFlagRepositoryInterface
     */
    private $greenDeliveryOptionFlagRepository;

    /**
     * @var ShippingDescriptionConverter
     */
    private $descriptionConverter;

    /**
     * @param OrderExtensionFactory $orderExtensionFactory
     * @param OrderGreenDeliveryOptionFlagRepositoryInterface $greenDeliveryOptionFlagRepository
     * @param ShippingDescriptionConverter $descriptionConverter
     */
    public function __construct(
        OrderExtensionFactory $orderExtensionFactory,
        OrderGreenDeliveryOptionFlagRepositoryInterface $greenDeliveryOptionFlagRepository,
        ShippingDescriptionConverter $descriptionConverter
    ) {
        $this->orderExtensionFactory             = $orderExtensionFactory;
        $this->greenDeliveryOptionFlagRepository = $greenDeliveryOptionFlagRepository;
        $this->descriptionConverter              = $descriptionConverter;
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $order
     * @return OrderInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGet(OrderRepositoryInterface $subject, OrderInterface $order): OrderInterface
    {
        /** @var OrderExtensionInterface $extensionAttributes */
        $extensionAttributes = $order->getExtensionAttributes();

        if ($extensionAttributes === null) {
            $extensionAttributes = $this->orderExtensionFactory->create();
        }

        /** @var OrderGreenDeliveryOptionFlagInterface $greenDeliveryOptionFlag */
        $greenDeliveryOptionFlag = $this->greenDeliveryOptionFlagRepository->getByOrderId((int)$order->getEntityId());

        $extensionAttributes->setMageWorxGreenDeliveryOptionFlag($greenDeliveryOptionFlag->getValue());
        $order->setExtensionAttributes($extensionAttributes);

        $description = $this->descriptionConverter->clear((string)$order->getShippingDescription());

        if ($greenDeliveryOptionFlag->getValue()) {
            $description = $this->descriptionConverter->convert($description);
        }

        $order->setShippingDescription($description);

        return $order;
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param OrderSearchResultInterface $orderSearchResult
     * @return OrderSearchResultInterface
     */
    public function afterGetList(
        OrderRepositoryInterface $subject,
        OrderSearchResultInterface $orderSearchResult
    ): OrderSearchResultInterface {
        /** @var OrderInterface $order */
        foreach ($orderSearchResult->getItems() as $order) {
            $this->afterGet($subject, $order);
        }

        return $orderSearchResult;
    }
}
