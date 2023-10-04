<?php


declare(strict_types=1);

namespace MageWorx\GreenDeliveryBase\Plugin\Api\Order;

use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderExtensionInterface;
use MageWorx\GreenDeliveryBase\Api\OrderGreenDeliveryOptionFlagRepositoryInterface;

class SaveGreenDeliveryOptionAttributePlugin
{
    /**
     * @var OrderGreenDeliveryOptionFlagRepositoryInterface
     */
    private $greenDeliveryOptionFlagRepository;

    /**
     * @param OrderGreenDeliveryOptionFlagRepositoryInterface $greenDeliveryOptionFlagRepository
     */
    public function __construct(OrderGreenDeliveryOptionFlagRepositoryInterface $greenDeliveryOptionFlagRepository)
    {
        $this->greenDeliveryOptionFlagRepository = $greenDeliveryOptionFlagRepository;
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $resultOrder
     * @return OrderInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterSave(OrderRepositoryInterface $subject, OrderInterface $resultOrder): OrderInterface
    {
        /** @var OrderExtensionInterface $extensionAttributes */
        $extensionAttributes = $resultOrder->getExtensionAttributes();

        if (null !== $extensionAttributes &&
            null !== $extensionAttributes->getMageWorxGreenDeliveryOptionFlag()
        ) {
            try {
                $this->greenDeliveryOptionFlagRepository->saveData(
                    (int)$resultOrder->getEntityId(),
                    $extensionAttributes->getMageWorxGreenDeliveryOptionFlag()
                );
            } catch (\Exception $e) {
                throw new \Magento\Framework\Exception\CouldNotSaveException(
                    __('Could not add attribute to order: "%1"', $e->getMessage()),
                    $e
                );
            }
        }

        return $resultOrder;
    }
}
