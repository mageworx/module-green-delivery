<?php


declare(strict_types=1);

namespace MageWorx\GreenDeliveryBase\Model;

use MageWorx\GreenDeliveryBase\Api\QuoteGreenDeliveryOptionFlagRepositoryInterface;
use MageWorx\GreenDeliveryBase\Api\Data\QuoteGreenDeliveryOptionFlagInterfaceFactory;
use MageWorx\GreenDeliveryBase\Api\Data\QuoteGreenDeliveryOptionFlagInterface;
use MageWorx\GreenDeliveryBase\Model\ResourceModel\Quote\GreenDeliveryOptionFlag as ResourceModel;

class QuoteGreenDeliveryOptionFlagRepository implements QuoteGreenDeliveryOptionFlagRepositoryInterface
{
    /**
     * @var ResourceModel
     */
    protected $resource;

    /**
     * @var QuoteGreenDeliveryOptionFlagInterfaceFactory
     */
    protected $greenDeliveryOptionFlagFactory;

    /**
     * @param ResourceModel $resource
     * @param QuoteGreenDeliveryOptionFlagInterfaceFactory $greenDeliveryOptionFlagFactory
     */
    public function __construct(
        ResourceModel $resource,
        QuoteGreenDeliveryOptionFlagInterfaceFactory $greenDeliveryOptionFlagFactory
    ) {
        $this->resource                       = $resource;
        $this->greenDeliveryOptionFlagFactory = $greenDeliveryOptionFlagFactory;
    }

    /**
     * @param QuoteGreenDeliveryOptionFlagInterface $greenDeliveryOptionFlag
     * @return QuoteGreenDeliveryOptionFlagInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(
        QuoteGreenDeliveryOptionFlagInterface $greenDeliveryOptionFlag
    ): QuoteGreenDeliveryOptionFlagInterface {
        try {
            $this->resource->save($greenDeliveryOptionFlag);
        } catch (\Exception $exception) {
            throw new  \Magento\Framework\Exception\CouldNotSaveException(
                __(
                    'Could not save the Green Delivery Option Flag: %1',
                    $exception->getMessage()
                )
            );
        }

        return $greenDeliveryOptionFlag;
    }

    /**
     * @param int $quoteId
     * @return QuoteGreenDeliveryOptionFlagInterface
     */
    public function getByQuoteId(int $quoteId): QuoteGreenDeliveryOptionFlagInterface
    {
        /** @var QuoteGreenDeliveryOptionFlagInterface $greenDeliveryOptionFlag */
        $greenDeliveryOptionFlag = $this->greenDeliveryOptionFlagFactory->create();

        $this->resource->load($greenDeliveryOptionFlag, $quoteId, QuoteGreenDeliveryOptionFlagInterface::QUOTE_ID);

        return $greenDeliveryOptionFlag;
    }

    /**
     * @param int $quoteId
     * @param bool $value
     * @return QuoteGreenDeliveryOptionFlagInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function saveData(int $quoteId, bool $value): QuoteGreenDeliveryOptionFlagInterface
    {
        $greenDeliveryOptionFlag = $this->getByQuoteId($quoteId);
        $greenDeliveryOptionFlag->setQuoteId($quoteId);
        $greenDeliveryOptionFlag->setValue($value);

        return $this->save($greenDeliveryOptionFlag);
    }
}
