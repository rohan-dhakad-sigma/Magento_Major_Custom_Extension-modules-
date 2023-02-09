<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Task\UpdateProductCommentGraphQl\Model\Resolver;

use Magento\CatalogGraphQl\Model\Resolver\Products\Query\ProductQueryInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\CatalogGraphQl\DataProvider\Product\SearchCriteriaBuilder;

/**
 * Products field resolver, used for GraphQL request processing.
 */
class Products implements ResolverInterface
{
    /**
     * @var ProductQueryInterface
     */
    private $searchQuery;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchApiCriteriaBuilder;

    /**
     * @param ProductQueryInterface $searchQuery
     * @param SearchCriteriaBuilder|null $searchApiCriteriaBuilder
     */
    public function __construct(
        ProductQueryInterface $searchQuery,
        SearchCriteriaBuilder $searchApiCriteriaBuilder = null
    ) {
        $this->searchQuery = $searchQuery;
        $this->searchApiCriteriaBuilder = $searchApiCriteriaBuilder ??
            \Magento\Framework\App\ObjectManager::getInstance()->get(SearchCriteriaBuilder::class);
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/temp.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);

        $orderId = $args['order_id'];
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $order = $objectManager->create('\Magento\Sales\Model\OrderRepository')->get($orderId);

        $objectManagerQuote = \Magento\Framework\App\ObjectManager::getInstance();
        $quote = $objectManagerQuote->create('\Magento\Quote\Model\Quote')->load($order->getQuoteId());

        $Allitems = $quote->getAllItems();
        $j = 0;
        $product['result'] = "product comments are updated";
        foreach($Allitems as $item)
        {
            if($item->getSku() ==  $args['filter']['sku']['in'][$j])
            {
                $item->setProductComments($args['filter']['product_comment']['in'][$j]); 
                $j++;
                $item->save();   
            }
        }

        // get product_comment field from quote id & sku
        // $logger->info(var_dump($args['filter']['sku']['in'][0])."\n".var_dump($args['filter']['product_comment']['in'][0]) );


//        if (isset($args['filter']['sku'])) {
//            $data['categories'] = $args['filter']['sku']['eq'] ?? $args['filter']['sku']['in'];
//            $data['categories'] = is_array($data['categories']) ? $data['categories'] : [$data['categories']];
//        }

        return $product;
    }

}
