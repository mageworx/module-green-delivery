<?php


declare(strict_types=1);

namespace MageWorx\GreenDeliveryBase\Plugin\Api\Quote;

use Magento\Quote\Api\Data\CartSearchResultsInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartExtensionInterface;
use Magento\Quote\Api\Data\CartInterface;
use MageWorx\GreenDeliveryBase\Api\QuoteGreenDeliveryOptionFlagRepositoryInterface;

class SaveGreenDeliveryOptionAttributePlugin
{
    /**
     * @var QuoteGreenDeliveryOptionFlagRepositoryInterface
     */
    private $greenDeliveryOptionFlagRepository;

    /**
     * @param QuoteGreenDeliveryOptionFlagRepositoryInterface $greenDeliveryOptionFlagRepository
     */
    public function __construct(QuoteGreenDeliveryOptionFlagRepositoryInterface $greenDeliveryOptionFlagRepository)
    {
        $this->greenDeliveryOptionFlagRepository = $greenDeliveryOptionFlagRepository;
    }

    /**
     * @param CartRepositoryInterface $subject
     * @param CartInterface $resultCart
     * @return CartInterface[]
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeSave(CartRepositoryInterface $subject, CartInterface $resultCart): array
    {
        /** @var CartExtensionInterface $extensionAttributes */
        $extensionAttributes = $resultCart->getExtensionAttributes();

        if (null !== $extensionAttributes &&
            null !== $extensionAttributes->getMageWorxGreenDeliveryOptionFlag()
        ) {
            try {
                $this->greenDeliveryOptionFlagRepository->saveData(
                    (int)$resultCart->getId(),
                    $extensionAttributes->getMageWorxGreenDeliveryOptionFlag()
                );
            } catch (\Exception $e) {
                throw new \Magento\Framework\Exception\CouldNotSaveException(
                    __('Could not add attribute to cart: "%1"', $e->getMessage()),
                    $e
                );
            }
        }

        return [$resultCart];
    }
}
