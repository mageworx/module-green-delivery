<?php


declare(strict_types=1);

namespace MageWorx\GreenDeliveryBase\ViewModel\PayPal;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use MageWorx\GreenDeliveryBase\Model\GreenDeliveryMethodConfigReader;

class GreenDeliveryDescription implements ArgumentInterface
{
    /**
     * @var GreenDeliveryMethodConfigReader
     */
    protected $configReader;

    /**
     * @param GreenDeliveryMethodConfigReader $configReader
     */
    public function __construct(GreenDeliveryMethodConfigReader $configReader)
    {
        $this->configReader = $configReader;
    }

    /**
     * @return bool
     */
    public function isDisplayed(): bool
    {
        return $this->configReader->isActive() && $this->configReader->getDescription();
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->configReader->getDescription();
    }
}
