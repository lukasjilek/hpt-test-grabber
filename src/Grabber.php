<?php

declare(strict_types=1);

namespace HPT;

use HPT\Enity\Product;

interface Grabber
{
    /**
     * Get product (if exists)
     * @param string $productId product ID
     * @return Product|null
     */
    public function getProduct(string $productId): ?Product;
}
