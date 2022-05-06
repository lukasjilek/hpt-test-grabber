<?php

declare(strict_types=1);

namespace HPT;

class Dispatcher
{
    private $input;

    /** @var Grabber */
    private $grabber;

    /** @var Output */
    private $output;

    public function __construct($input, Grabber $grabber, Output $output)
    {
        $this->input = $input;
        $this->grabber = $grabber;
        $this->output = $output;
    }

    /**
     * @return string JSON
     */
    public function run(): string
    {
        while (($productId = fgets($this->input)) !== false) {
            $productId = trim(preg_replace('/\s+/', ' ', $productId));

            $this->output->addProduct($productId, $this->grabber->getProduct($productId));
        }

        fclose($this->input);

        return $this->output->getJson();
    }
}
