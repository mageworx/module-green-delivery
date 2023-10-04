<?php


declare(strict_types=1);

namespace MageWorx\GreenDeliveryBase\Model\Source;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Option\ArrayInterface;
use Magento\Shipping\Model\Config as ShippingConfig;

class ShippingMethods implements ArrayInterface
{
    /**
     * @var ShippingConfig
     */
    protected $shippingConfig;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param ShippingConfig $shippingConfig
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ShippingConfig $shippingConfig, ScopeConfigInterface $scopeConfig)
    {
        $this->shippingConfig = $shippingConfig;
        $this->scopeConfig    = $scopeConfig;
    }

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        $carriers = $this->shippingConfig->getAllCarriers();

        foreach ($carriers as $carrierCode => $carrierModel) {
            $carrierMethods = $carrierModel->getAllowedMethods();

            if (!$carrierMethods) {
                continue;
            }

            $carrierTitle          = $this->scopeConfig->getValue(
                'carriers/' . $carrierCode . '/title',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
            $methods[$carrierCode] = ['label' => $carrierTitle, 'value' => []];

            foreach ($carrierMethods as $methodCode => $methodTitle) {
                if (is_array($methodTitle)) {
                    continue;
                }

                $methods[$carrierCode]['value'][] = [
                    'value' => $carrierCode . '_' . $methodCode,
                    'label' => '[' . $carrierCode . '] ' . ($methodTitle ? $methodTitle : $methodCode),
                ];
            }
        }

        if (empty($methods)) {
            $methods = ['label' => [], 'value' => []];
        }

        return $methods;
    }
}
