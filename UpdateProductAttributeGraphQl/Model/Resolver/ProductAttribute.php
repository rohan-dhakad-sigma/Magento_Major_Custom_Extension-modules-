<?php

namespace Task\UpdateProductAttributeGraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\Validator\EmailAddress as EmailValidator;
use MageDigest\DemoGraphQl\Model\Customer\Order as CustomerOrder;
use Magento\Framework\Exception\NoSuchEntityException;

class ProductAttribute implements ResolverInterface
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
        $product_id = $args['product_id'];
        $productUnit = $args['product_unit'];
        if($args['product_unit'] == "Litre" || $args['product_unit'] == "Kg" || $args['product_unit'] == "mL"
        || $args['product_unit'] == "Packets") {
            // Creating product object
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $product = $objectManager->create('Magento\Catalog\Model\Product')->load($product_id);
            $product->setData('product_unit', $productUnit);
            $product->save();
            $productAttribute['product_unit'] = $args['product_unit'];
            return $productAttribute;
        }
        else {
            throw new GraphQlInputException(__('Given Unit must be correct'));
        }

    }
}