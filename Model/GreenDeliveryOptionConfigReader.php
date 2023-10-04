<?php


declare(strict_types=1);

namespace MageWorx\GreenDeliveryBase\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class GreenDeliveryOptionConfigReader
{
    const XML_PATH_ENABLE           = 'mageworx_greendelivery/option/enable';
    const XML_PATH_TITLE            = 'mageworx_greendelivery/option/title';
    const XML_PATH_DESCRIPTION      = 'mageworx_greendelivery/option/description';
    const XML_PATH_SHIPPING_METHODS = 'mageworx_greendelivery/option/shipping_methods';
    const XML_PATH_COST             = 'mageworx_greendelivery/option/cost';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param int|null $storeId
     * @return bool
     */
    public function isEnabled(?int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int|null $storeId
     * @return string
     */
    public function getTitle(?int $storeId = null): string
    {
        return (string)$this->scopeConfig->getValue(self::XML_PATH_TITLE, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param int|null $storeId
     * @return string
     */
    public function getDescription(?int $storeId = null): string
    {
        return (string)$this->scopeConfig->getValue(self::XML_PATH_DESCRIPTION, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param int|null $storeId
     * @return array
     */
    public function getShippingMethods(?int $storeId = null): array
    {
        $result = (string)$this->scopeConfig->getValue(
            self::XML_PATH_SHIPPING_METHODS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        return array_filter(explode(',', strtolower($result)));
    }

    /**
     * @param int|null $websiteId
     * @return float
     */
    public function getCost(?int $websiteId = null): float
    {
        return (float)$this->scopeConfig->getValue(self::XML_PATH_COST, ScopeInterface::SCOPE_WEBSITE, $websiteId);
    }
}
