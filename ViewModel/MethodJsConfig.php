<?php


declare(strict_types=1);

namespace MageWorx\GreenDeliveryBase\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use MageWorx\GreenDeliveryBase\Model\GreenDeliveryMethodConfigReader;

class MethodJsConfig implements ArgumentInterface
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
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'description' => $this->configReader->getDescription(),
            'image_src'   => $this->configReader->getIconUrl(),
            'color'       => $this->configReader->getFontColor(),
            'name'        => $this->configReader->getName()
        ];
    }
}
