<?php

namespace HPT\Enity;


class Product implements \JsonSerializable
{

    /** @var string */
    private $code;

    /** @var float|null */
    private $price;

    /** @var float|null */
    private $rating;

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
     * @return float|null
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

    /**
     * @return float|null
     */
    public function getRating(): ?float
    {
        return $this->rating;
    }

    /**
     * @param float|null $rating
     * @return Product
     */
    public function setRating(?float $rating): Product
    {
        $this->rating = $rating;
        return $this;
    }




    public function jsonSerialize()
    {
        return (object)[
            'price' => $this->getPrice(),
            'rating' => $this->getRating(),
        ];
    }
}