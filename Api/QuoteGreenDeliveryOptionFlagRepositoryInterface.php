<?php


declare(strict_types=1);

namespace MageWorx\GreenDeliveryBase\Api;

use MageWorx\GreenDeliveryBase\Api\Data\QuoteGreenDeliveryOptionFlagInterface;

interface QuoteGreenDeliveryOptionFlagRepositoryInterface
{
    /**
     * @param \MageWorx\GreenDeliveryBase\Api\Data\QuoteGreenDeliveryOptionFlagInterface $greenDeliveryOptionFlag
     * @return \MageWorx\GreenDeliveryBase\Api\Data\QuoteGreenDeliveryOptionFlagInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(
        QuoteGreenDeliveryOptionFlagInterface $greenDeliveryOptionFlag
    ): QuoteGreenDeliveryOptionFlagInterface;

    /**
     * @param int $quoteId
     * @return \MageWorx\GreenDeliveryBase\Api\Data\QuoteGreenDeliveryOptionFlagInterface
     */
    public function getByQuoteId(int $quoteId): QuoteGreenDeliveryOptionFlagInterface;

    /**
     * @param int $quoteId
     * @param bool $value
     * @return \MageWorx\GreenDeliveryBase\Api\Data\QuoteGreenDeliveryOptionFlagInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function saveData(int $quoteId, bool $value): QuoteGreenDeliveryOptionFlagInterface;
}
