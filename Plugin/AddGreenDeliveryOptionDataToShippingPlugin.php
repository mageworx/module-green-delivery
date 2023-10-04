<?php


declare(strict_types=1);

namespace MageWorx\GreenDeliveryBase\Plugin;

use Magento\Framework\Pricing\PriceCurrencyInterface;
use MageWorx\GreenDeliveryBase\Model\GreenDeliveryOptionConfigReader;
use Magento\Quote\Model\Quote\Address\Total\Shipping;
use Magento\Quote\Model\Quote;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote\Address\Total;
use MageWorx\GreenDeliveryBase\Model\ShippingDescriptionConverter;

class AddGreenDeliveryOptionDataToShippingPlugin
{
    /**
     * @var GreenDeliveryOptionConfigReader
     */
    private $configReader;

    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @var ShippingDescriptionConverter
     */
    private $descriptionConverter;

    /**
     * @param GreenDeliveryOptionConfigReader $configReader
     * @param PriceCurrencyInterface $priceCurrency
     * @param ShippingDescriptionConverter $descriptionConverter
     */
    public function __construct(
        GreenDeliveryOptionConfigReader $configReader,
        PriceCurrencyInterface $priceCurrency,
        ShippingDescriptionConverter $descriptionConverter
    ) {
        $this->configReader         = $configReader;
        $this->priceCurrency        = $priceCurrency;
        $this->descriptionConverter = $descriptionConverter;
    }

    /**
     * @param Shipping $subject
     * @param Shipping $result
     * @param Quote $quote
     * @param ShippingAssignmentInterface $shippingAssignment
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return Shipping
     */
    public function afterCollect(
        Shipping $subject,
        Shipping $result,
        Quote $quote,
        ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    ): Shipping {
        if (!count($shippingAssignment->getItems())) {
            return $result;
        }

        $extensionAttributes = $quote->getExtensionAttributes();

        if ($extensionAttributes === null
            || !$extensionAttributes->getMageWorxGreenDeliveryOptionFlag()
        ) {
            return $result;
        }

        $method = $shippingAssignment->getShipping()->getMethod();

        if (!empty($this->configReader->getShippingMethods())
            && array_search($method, $this->configReader->getShippingMethods()) === false
        ) {
            return $result;
        }

        $store      = $quote->getStore();
        $cost       = $this->configReader->getCost();
        $amountCost = $this->priceCurrency->convert($cost, $store);

        $total->setTotalAmount($subject->getCode(), $total->getTotalAmount($subject->getCode()) + $amountCost);
        $total->setBaseTotalAmount($subject->getCode(), $total->getBaseTotalAmount($subject->getCode()) + $cost);
        $total->setShippingDescription($this->descriptionConverter->convert((string)$total->getShippingDescription()));

        $quote->setMwGreenDeliveryCollectedFlag(true);

        return $result;
    }

    /**
     * @param Shipping $subject
     * @param Quote $quote
     * @param Total $total
     * @return null
     */
    public function beforeFetch(Shipping $subject, Quote $quote, Total $total)
    {
        if ($quote->getIsVirtual() || $quote->getMwGreenDeliveryCollectedFlag()) {
            return null;
        }

        $extensionAttributes = $quote->getExtensionAttributes();

        if ($extensionAttributes === null
            || !$extensionAttributes->getMageWorxGreenDeliveryOptionFlag()
        ) {
            return null;
        }

        $method = $quote->getShippingAddress()->getShippingMethod();

        if (!empty($this->configReader->getShippingMethods())
            && array_search($method, $this->configReader->getShippingMethods()) === false
        ) {
            return null;
        }

        $quote->collectTotals();

        return null;
    }
}
