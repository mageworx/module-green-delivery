<?php


declare(strict_types=1);

namespace MageWorx\GreenDeliveryBase\Model;

use MageWorx\GreenDeliveryBase\Api\Data\QuoteGreenDeliveryOptionFlagInterface;
use Magento\Framework\Model\AbstractModel;

class QuoteGreenDeliveryOptionFlag extends AbstractModel implements QuoteGreenDeliveryOptionFlagInterface
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(\MageWorx\GreenDeliveryBase\Model\ResourceModel\Quote\GreenDeliveryOptionFlag::class);
    }

    /**
     * @return int
     */
    public function getQuoteId(): int
    {
        return (int)$this->getData(self::QUOTE_ID);
    }

    /**
     * @param int $quoteId
     * @return QuoteGreenDeliveryOptionFlagInterface
     */
    public function setQuoteId(int $quoteId): QuoteGreenDeliveryOptionFlagInterface
    {
        return $this->setData(self::QUOTE_ID, $quoteId);
    }

    /**
     * @return bool
     */
    public function getValue(): bool
    {
        return (bool)$this->getData(self::VALUE);
    }

    /**
     * @param bool $value
     * @return QuoteGreenDeliveryOptionFlagInterface
     */
    public function setValue(bool $value): QuoteGreenDeliveryOptionFlagInterface
    {
        return $this->setData(self::VALUE, $value);
    }
}
