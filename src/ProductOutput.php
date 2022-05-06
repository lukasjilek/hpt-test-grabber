<?php

namespace HPT;

use HPT\Enity\Product;

class ProductOutput implements Output
{
    /** @array */
    private $products;

    public function __construct()
    {
        $this->products = [];
    }

    public function addProduct(string $code, ?Product $product): void
    {
        $this->products[$code] = $product;
    }

    public function getJson(): string
    {
        return json_encode((object)$this->products);
    }
}