<?php


declare(strict_types=1);

namespace MageWorx\GreenDeliveryBase\Model;

class ShippingDescriptionConverter
{
    /**
     * @var GreenDeliveryOptionConfigReader
     */
    private $configReader;

    /**
     * @param GreenDeliveryOptionConfigReader $configReader
     */
    public function __construct(GreenDeliveryOptionConfigReader $configReader)
    {
        $this->configReader = $configReader;
    }

    /**
     * @param string $description
     * @return string
     */
    public function convert(string $description): string
    {
        return $description . $this->getSuffix();
    }

    /**
     * @param string $description
     * @return string
     */
    public function clear(string $description): string
    {
        return str_replace($this->getSuffix(), '', $description);
    }

    /**
     * @return string
     */
    private function getSuffix(): string
    {
        return ' (' . $this->configReader->getTitle() . ')';
    }
}
