<?php


declare(strict_types=1);

namespace MageWorx\GreenDeliveryBase\Plugin\Api;

use Magento\Quote\Api\CartManagementInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Sales\Api\Data\OrderExtensionInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use MageWorx\GreenDeliveryBase\Api\OrderGreenDeliveryOptionFlagRepositoryInterface;
use MageWorx\GreenDeliveryBase\Api\QuoteGreenDeliveryOptionFlagRepositoryInterface;
use MageWorx\GreenDeliveryBase\Model\GreenDeliveryOptionConfigReader;

class AddGreenDeliveryOptionToNewOrder
{
    /**
     * @var OrderExtensionFactory
     */
    private $orderExtensionFactory;

    /**
     * @var OrderGreenDeliveryOptionFlagRepositoryInterface
     */
    private $orderGreenDeliveryOptionFlagRepository;

    /**
     * @var QuoteGreenDeliveryOptionFlagRepositoryInterface
     */
    private $quoteGreenDeliveryOptionFlagRepository;

    /**
     * @var GreenDeliveryOptionConfigReader
     */
    private $configReader;

    /**
     * @param OrderExtensionFactory $orderExtensionFactory
     * @param QuoteGreenDeliveryOptionFlagRepositoryInterface $quoteGreenDeliveryOptionFlagRepository
     * @param OrderGreenDeliveryOptionFlagRepositoryInterface $orderGreenDeliveryOptionFlagRepository
     * @param GreenDeliveryOptionConfigReader $configReader
     */
    public function __construct(
        OrderExtensionFactory $orderExtensionFactory,
        QuoteGreenDeliveryOptionFlagRepositoryInterface $quoteGreenDeliveryOptionFlagRepository,
        OrderGreenDeliveryOptionFlagRepositoryInterface $orderGreenDeliveryOptionFlagRepository,
        GreenDeliveryOptionConfigReader $configReader
    ) {
        $this->orderExtensionFactory                  = $orderExtensionFactory;
        $this->quoteGreenDeliveryOptionFlagRepository = $quoteGreenDeliveryOptionFlagRepository;
        $this->orderGreenDeliveryOptionFlagRepository = $orderGreenDeliveryOptionFlagRepository;
        $this->configReader                           = $configReader;
    }

    /**
     * @param CartManagementInterface $subject
     * @param OrderInterface $order
     * @param CartInterface $cart
     * @return OrderInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterSubmit(
        CartManagementInterface $subject,
        OrderInterface $order,
        CartInterface $cart
    ): OrderInterface {
        $quoteFlag = $this->quoteGreenDeliveryOptionFlagRepository->getByQuoteId((int)$cart->getId());

        if ($quoteFlag->getValue() && $this->validate($cart)) {
            /** @var OrderExtensionInterface $extensionAttributes */
            $extensionAttributes = $order->getExtensionAttributes();

            if ($extensionAttributes === null) {
                $extensionAttributes = $this->orderExtensionFactory->create();
            }

            $extensionAttributes->setMageWorxGreenDeliveryOptionFlag($quoteFlag->getValue());
            $order->setExtensionAttributes($extensionAttributes);

            $this->orderGreenDeliveryOptionFlagRepository->saveData((int)$order->getEntityId(), $quoteFlag->getValue());
        }

        return $order;
    }

    /**
     * @param CartInterface $cart
     * @return bool
     */
    private function validate(CartInterface $cart): bool
    {
        $shippingMethod = $cart->getShippingAddress()->getShippingMethod();

        if (!empty($this->configReader->getShippingMethods())
            && array_search($shippingMethod, $this->configReader->getShippingMethods()) === false
        ) {
            return false;
        }

        return true;
    }
}
