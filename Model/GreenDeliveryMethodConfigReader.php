<?php


declare(strict_types=1);

namespace MageWorx\GreenDeliveryBase\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class GreenDeliveryMethodConfigReader
{
    const IMAGE_URL_PATH       = '/mageworx/greendelivery_method/icon/';
    const XML_PATH_ACTIVE      = 'carriers/mageworxgreendelivery/active';
    const XML_PATH_TITLE       = 'carriers/mageworxgreendelivery/title';
    const XML_PATH_NAME        = 'carriers/mageworxgreendelivery/name';
    const XML_PATH_DESCRIPTION = 'carriers/mageworxgreendelivery/description';
    const XML_PATH_COST        = 'carriers/mageworxgreendelivery/cost';
    const XML_PATH_FONT_COLOR  = 'carriers/mageworxgreendelivery/font_color';
    const XML_PATH_ICON        = 'carriers/mageworxgreendelivery/icon';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param ScopeConfigInterface $storeManager
     */
    public function __construct(ScopeConfigInterface $scopeConfig, StoreManagerInterface $storeManager)
    {
        $this->scopeConfig  = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    /**
     * @param int|null $storeId
     * @return bool
     */
    public function isActive(?int $websiteId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ACTIVE,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
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
    public function getName(?int $storeId = null): string
    {
        return (string)$this->scopeConfig->getValue(self::XML_PATH_NAME, ScopeInterface::SCOPE_STORE, $storeId);
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
     * @param int|null $websiteId
     * @return float
     */
    public function getCost(?int $websiteId = null): float
    {
        return (float)$this->scopeConfig->getValue(self::XML_PATH_COST, ScopeInterface::SCOPE_WEBSITE, $websiteId);
    }

    /**
     * @param int|null $storeId
     * @return string
     */
    public function getFontColor(?int $storeId = null): string
    {
        return (string)$this->scopeConfig->getValue(self::XML_PATH_FONT_COLOR, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param int|null $storeId
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getIconUrl(?int $storeId = null): string
    {
        $value = (string)$this->scopeConfig->getValue(self::XML_PATH_ICON, ScopeInterface::SCOPE_STORE, $storeId);

        if ($value) {
            return $this->getBaseImagePath($storeId) . $value;
        }

        return '';
    }

    /**
     * @param int|null $storeId
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function getBaseImagePath(int $storeId = null): string
    {
        $store        = $this->storeManager->getStore($storeId);
        $baseMediaUrl = $store->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);

        return $baseMediaUrl . static::IMAGE_URL_PATH;
    }
}
