<?php

namespace Task\UpdateOrderCommentGraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Validator\EmailAddress as EmailValidator;
use MageDigest\DemoGraphQl\Model\Customer\Order as CustomerOrder;
use Magento\Framework\Exception\NoSuchEntityException;

class UpdateOrder implements ResolverInterface
{

    /**
     * @param Field $field
     * @param \Magento\Framework\GraphQl\Query\Resolver\ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        // Creating order object
        $orderId = $args['order_id'];
        $orderComments = $args['order_comment'];    
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $order = $objectManager->create('\Magento\Sales\Model\OrderRepository')->get($orderId);
        $order->setData('task_order_comments', $orderComments);
        $order->save();

        // Creating quote object
        $objectManagerQuote = \Magento\Framework\App\ObjectManager::getInstance();
        $quote = $objectManagerQuote->create('\Magento\Quote\Model\Quote')->load($order->getQuoteId());
//        var_dump($order->getQuoteId());
        $quote->setData('task_order_comments', $orderComments);
        $quote->save();

        $orderAttribute['order_comment'] = $args['order_comment'];

        return $orderAttribute;

    }
}