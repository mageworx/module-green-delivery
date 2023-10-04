<?php


declare(strict_types=1);

namespace MageWorx\GreenDeliveryBase\Plugin\Api\Quote;

use Magento\Quote\Api\Data\CartSearchResultsInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartExtensionFactory;
use Magento\Quote\Api\Data\CartExtensionInterface;
use Magento\Quote\Api\Data\CartInterface;
use MageWorx\GreenDeliveryBase\Api\Data\QuoteGreenDeliveryOptionFlagInterface;
use MageWorx\GreenDeliveryBase\Api\QuoteGreenDeliveryOptionFlagRepositoryInterface;

class AddGreenDeliveryOptionAttributePlugin
{
    /**
     * @var CartExtensionFactory
     */
    private $cartExtensionFactory;

    /**
     * @var QuoteGreenDeliveryOptionFlagRepositoryInterface
     */
    private $greenDeliveryOptionFlagRepository;

    /**
     * @param CartExtensionFactory $cartExtensionFactory
     * @param QuoteGreenDeliveryOptionFlagRepositoryInterface $greenDeliveryOptionFlagRepository
     */
    public function __construct(
        CartExtensionFactory $cartExtensionFactory,
        QuoteGreenDeliveryOptionFlagRepositoryInterface $greenDeliveryOptionFlagRepository
    ) {
        $this->cartExtensionFactory = $cartExtensionFactory;
        $this->greenDeliveryOptionFlagRepository = $greenDeliveryOptionFlagRepository;
    }

    /**
     * @param CartRepositoryInterface $subject
     * @param CartInterface $cart
     * @return CartInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGet(CartRepositoryInterface $subject, CartInterface $cart): CartInterface
    {
        /** @var CartExtensionInterface $extensionAttributes */
        $extensionAttributes = $cart->getExtensionAttributes();

        if ($extensionAttributes === null) {
            $extensionAttributes = $this->cartExtensionFactory->create();
        }

        /** @var QuoteGreenDeliveryOptionFlagInterface $greenDeliveryOptionFlag */
        $greenDeliveryOptionFlag = $this->greenDeliveryOptionFlagRepository->getByQuoteId((int)$cart->getEntityId());

        $extensionAttributes->setMageWorxGreenDeliveryOptionFlag($greenDeliveryOptionFlag->getValue());
        $cart->setExtensionAttributes($extensionAttributes);

        return $cart;
    }

    /**
     * @param CartRepositoryInterface $subject
     * @param CartSearchResultsInterface $cartSearchResult
     * @return CartSearchResultsInterface
     */
    public function afterGetList(
        CartRepositoryInterface $subject,
        CartSearchResultsInterface $cartSearchResult
    ): CartSearchResultsInterface {
        /** @var CartInterface $cart */
        foreach ($cartSearchResult->getItems() as $cart) {
            $this->afterGet($subject, $cart);
        }

        return $cartSearchResult;
    }
}
