<?php

namespace HPT\Enity;


class Product implements \JsonSerializable
{

    /** @var string */
    private $code;

    /** @var float|null */
    private $price;

    /**
     * @param string $code
     */
    public function __construct(string $code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }


    /**
     * @return float
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return Product
     */
    public function setPrice(?float $price): Product
    {
        $this->price = $price;
        return $this;
    }


    public function jsonSerialize()
    {
        return (object)[
            'price' => $this->getPrice(),
        ];
    }
}