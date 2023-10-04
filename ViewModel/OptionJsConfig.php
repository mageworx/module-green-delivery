<?php


declare(strict_types=1);

namespace MageWorx\GreenDeliveryBase\ViewModel;

use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use MageWorx\GreenDeliveryBase\Model\GreenDeliveryOptionConfigReader;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Checkout\Model\Session;
use Magento\Quote\Model\Quote;

class OptionJsConfig implements ArgumentInterface
{
    /**
     * @var GreenDeliveryOptionConfigReader
     */
    protected $configReader;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var Session
     */
    protected $checkoutSession;

    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;

    /**
     * @param GreenDeliveryOptionConfigReader $configReader
     * @param StoreManagerInterface $storeManager
     * @param Session $checkoutSession
     * @param PriceCurrencyInterface $priceCurrency
     */
    public function __construct(
        GreenDeliveryOptionConfigReader $configReader,
        StoreManagerInterface $storeManager,
        Session $checkoutSession,
        PriceCurrencyInterface $priceCurrency
    ) {
        $this->configReader    = $configReader;
        $this->storeManager    = $storeManager;
        $this->checkoutSession = $checkoutSession;
        $this->priceCurrency   = $priceCurrency;
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        $currency = $this->storeManager->getStore()->getBaseCurrency();
        $quote    = $this->checkoutSession->getQuote();

        /** @var CartExtensionInterface $extensionAttributes */
        $extensionAttributes = $quote->getExtensionAttributes();

        if (null !== $extensionAttributes &&
            null !== $extensionAttributes->getMageWorxGreenDeliveryOptionFlag()
        ) {
            $value = (int)$extensionAttributes->getMageWorxGreenDeliveryOptionFlag();
        } else {
            $value = 0;
        }

        return [
            'enabled'          => $this->configReader->isEnabled(),
            'title'            => $this->configReader->getTitle(),
            'description'      => $this->configReader->getDescription(),
            'shipping_methods' => $this->configReader->getShippingMethods(),
            'cost'             => $currency->convert($this->configReader->getCost(), $quote->getQuoteCurrencyCode()),
            'formatedCost'     => $this->formatCost($this->configReader->getCost(), $quote),
            'value'            => (string)$value,
            'checked'          => (bool)$value
        ];
    }

    /**
     * @param float $cost
     * @param Quote $quote
     * @return string
     */
    protected function formatCost(float $cost, Quote $quote): string
    {
        return $this->priceCurrency->convertAndFormat(
            $cost,
            false,
            PriceCurrencyInterface::DEFAULT_PRECISION,
            $quote->getStore()
        );
    }
}
