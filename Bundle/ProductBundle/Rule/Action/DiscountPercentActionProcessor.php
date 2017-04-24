<?php

namespace CoreShop\Bundle\ProductBundle\Rule\Action;

use CoreShop\Component\Product\Model\ProductInterface;
use CoreShop\Component\Product\Rule\Action\ProductPriceActionProcessorInterface;
use Webmozart\Assert\Assert;

class DiscountPercentActionProcessor implements ProductPriceActionProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getDiscount($subject, $price, array $configuration)
    {
        Assert::isInstanceOf($subject, ProductInterface::class);

        return ($configuration['percent'] / 100) * $price;
    }

    /**
     * {@inheritdoc}
     */
    public function getPrice($subject, array $configuration)
    {
        return null;
    }
}