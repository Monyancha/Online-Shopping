# CoreShop Product Price Calculation

CoreShop uses multiple Price Calculators to determine the correct price for a product. Per default following Calculators are used

 - [Price Rule Calculator which uses Prices from Catalog Price Rules and Specific Price Rules](https://github.com/coreshop/CoreShop/blob/master/src/CoreShop/Bundle/ProductBundle/Calculator/PriceRuleCalculator.php)
 - [Property Price Calculator](https://github.com/coreshop/CoreShop/blob/master/src/CoreShop/Bundle/ProductBundle/Calculator/PropertyPriceCalculator.php)

These two are only the default implementations, if you need a custom Calculator, you need to implement the Interface
```CoreShop\Component\Product\Calculator\ProductPriceCalculatorInterface``` and register your service with the tag
```coreshop.product.price_calculator```, a ```type``` attribute and a ```priority```

## Calculator Service
If you want to calculate the Price for a Product, you need to use a special service to do that. There are two options:


**1**: ```coreshop.product.price_calculator``` which calculates prices without any tax regards.

**2**: ```coreshop.product.taxed_price_calculator``` which calculates prices with tax or without. (recommended one to use)

### Templating
If you want to calculate the price within a Template, you can do so by using the filter ```coreshop_product_price```

<div class="code-section">

```php
<?php
echo $this->coreshop_product_price($product);

?>
```

```twig
{{ (product|coreshop_product_price(true)) }}
```

</div>

## Custom Price Calculator Example

Our Example Service will take the Property "price" - 1 as Product Price and -1 as Discount, therefore the price stays the same.
This example is only a show-case of how to add a new calculator.

```php
<?php

namespace AppBundle\CoreShop\Product;

use CoreShop\Component\Product\Calculator\ProductPriceCalculatorInterface;
use CoreShop\Component\Product\Model\ProductInterface;

final class CustomPriceCalculator implements ProductPriceCalculatorInterface
{
    public function getPrice(ProductInterface $subject)
    {
        return $subject->getPrice() - 1;
    }

    public function getDiscount(ProductInterface $subject, $price)
    {
        return -1;
    }
}
```

Now we need to register our service to the container and add the calculator tag:

```yaml
app.coreshop.product.price_calculator.custom:
    class: AppBundle\CoreShop\Product\CustomPriceCalculator
    tags:
      - { name: coreshop.product.price_calculator, type: custom, priority: 1 }
```

CoreShop now uses our service for all Product Price Calculations.
