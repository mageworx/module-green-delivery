<?php


declare(strict_types=1);

namespace MageWorx\GreenDeliveryBase\Plugin\Order\Grid;

use Magento\Framework\Data\Collection;
use Magento\Framework\View\Element\UiComponent\DataProvider\CollectionFactory;

class AddGreenDeliveryOptionFlagPlugin
{
    /**
     * @param CollectionFactory $subject
     * @param Collection $collection
     * @param $requestName
     * @return Collection
     */
    public function afterGetReport(
        CollectionFactory $subject,
        Collection $collection,
        $requestName
    ): Collection {
        if ($requestName == 'sales_order_grid_data_source') {
            if ($collection instanceof \Magento\Sales\Model\ResourceModel\Order\Grid\Collection) {
                $collection->getSelect()->joinLeft(
                    ['mw_gdo_o' => $collection->getResource()->getTable('mageworx_greendelivery_option_order')],
                    'mw_gdo_o.order_id = main_table.entity_id',
                    ['green_delivery_option_flag' => new \Zend_Db_Expr('IFNULL(mw_gdo_o.value, 0)')]
                );

                $collection->addFilterToMap('green_delivery_option_flag', new \Zend_Db_Expr('IFNULL(mw_gdo_o.value, 0)'));
            }
        }

        return $collection;
    }
}
