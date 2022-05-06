<?php

declare(strict_types=1);

namespace HPT;

use HPT\Enity\Product;

interface Output
{
    public function addProduct(string $code, ?Product $product): void;

    public function getJson(): string;
}
