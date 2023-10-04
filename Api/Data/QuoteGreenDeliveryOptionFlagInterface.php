<?php


declare(strict_types=1);

namespace MageWorx\GreenDeliveryBase\Api\Data;

interface QuoteGreenDeliveryOptionFlagInterface
{
    const ENTITY_ID = 'entity_id';
    const QUOTE_ID  = 'quote_id';
    const VALUE     = 'value';

    /**
     * @return int
     */
    public function getEntityId();

    /**
     * @param int $entityId
     * @return QuoteGreenDeliveryOptionFlagInterface
     */
    public function setEntityId($entityId);

    /**
     * @return int
     */
    public function getQuoteId(): int;

    /**
     * @param int $quoteId
     * @return QuoteGreenDeliveryOptionFlagInterface
     */
    public function setQuoteId(int $quoteId): QuoteGreenDeliveryOptionFlagInterface;

    /**
     * @return bool
     */
    public function getValue(): bool;

    /**
     * @param bool $value
     * @return QuoteGreenDeliveryOptionFlagInterface
     */
    public function setValue(bool $value): QuoteGreenDeliveryOptionFlagInterface;
}
