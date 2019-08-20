<?php
/**
 * Copyright © Dxvn, Inc. All rights reserved.
 * @author  Tran Ngoc Duc <caothu91@gmail.com>
 */

namespace Diepxuan\GroupedProductMaxPrice\Pricing\Price;

use Magento\Catalog\Model\Product;

/**
 * Final price model
 */
class FinalPrice extends \Magento\GroupedProduct\Pricing\Price\FinalPrice {
    /**
     * Price type final
     */
    const PRICE_CODE = 'final_price';

    /**
     * @var Product maximum
     */
    protected $maxProduct;

    /**
     * Return maximal product price
     *
     * @return float
     */
    public function getMaxValue() {
        $maxProduct = $this->getMaxProduct();

        return $maxProduct ?
            $maxProduct->getPriceInfo()->getPrice( FinalPrice::PRICE_CODE )->getValue() :
            0.00;
    }

    /**
     * Returns product with maximal price
     *
     * @return Product
     */
    public function getMaxProduct() {
        if ( null === $this->maxProduct ) {
            $products = $this->product->getTypeInstance()->getAssociatedProducts( $this->product );
            $maxPrice = null;
            foreach ( $products as $item ) {
                $product = clone $item;
                $product->setQty( \Magento\Framework\Pricing\PriceInfoInterface::PRODUCT_QUANTITY_DEFAULT );
                $price = $product->getPriceInfo()
                                 ->getPrice( FinalPrice::PRICE_CODE )
                                 ->getValue();
                if ( ( $price !== false ) && ( $price >= ( $maxPrice === null ? $price : $maxPrice ) ) ) {
                    $this->maxProduct = $product;
                    $maxPrice         = $price;
                }
            }
        }

        return $this->maxProduct;
    }
}
