<?php

namespace HPT;

use HPT\Enity;
use HPT\Enity\Product;
use Nette\Utils\Arrays;
use Nette\Utils\Strings;

class CZCGrabber implements Grabber
{
    private static function formatSearchUrl(string $productId): string
    {
        return "https://www.czc.cz/{$productId}/hledat";
    }

    private static function formatUrlSuffix(string $suffix): string
    {
        return "https://www.czc.cz/{$suffix}";
    }


    public function getProduct(string $productId): ?Product
    {
        $searchUrl = self::formatSearchUrl($productId);

        $searchDocument = self::getDocument($searchUrl);
        if ($searchDocument) {
            $productUrl = $this->processSearch($searchDocument);
            if ($productUrl) {
                $productDoc = self::getDocument($productUrl);
                if ($productDoc) {
                    $product = $this->processProduct($productDoc);

                    return $product;
                }
            } else return null;
        } else return null;

        return null;
    }

    /**
     * @param \DOMDocument $doc
     * @return string|null
     */
    private function processSearch(\DOMDocument $doc): ?string
    {
        $path = new \DOMXPath($doc);

        $found = $path->query('//*[@id="tiles"]/div/div[2]/div[1]/h5/a');
        $node = $found->item($found->length !== 1 ? 1 : 0);
        $href = $node ? $node->attributes->getNamedItem('href') : null;

        return $href ? self::formatUrlSuffix($href->value) : null;
    }

    /**
     * @param \DOMDocument $doc
     * @return Product|null
     */
    private function processProduct(\DOMDocument $doc): ?Enity\Product
    {
        $product = self::createProduct($doc);
        if ($product) {
            $product->setPrice(self::grabPrice($doc));
            $product->setRating(self::grabRating($doc));
        }

        return $product;
    }


    /**
     * @param string $url
     * @return \DOMDocument|null
     */
    private static function getDocument(string $url): ?\DOMDocument
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $html = curl_exec($ch);

        if (!empty($html)) {
            $doc = new \DOMDocument();
            libxml_use_internal_errors(true);
            $doc->loadHTML(str_replace("&nbsp;", "", $html));
            libxml_use_internal_errors(false);
            curl_close($ch);
            return $doc;
        }

        curl_close($ch);

        return null;
    }

    /**
     * @param \DOMDocument $doc
     * @return Product|null
     */
    private static function createProduct(\DOMDocument $doc): ?Product
    {
        $path = new \DOMXPath($doc);

        $codeEl = $path->query('//div[@class="pd-next-in-category__item"]');

        if ($codeEl) {
            $product = null;
            /** @var \DOMNode $el */
            foreach ($codeEl as $el) {
                $str = Strings::toAscii($el->textContent);
                if (preg_match('/Kod vyrobce:\s+([^\s]+)/m', $str, $matches)) {
                    if (isset($matches[1])) {
                        $product = new Enity\Product($matches[1]);
                    }
                }
            }

            return $product;
        } else return null;
    }

    public static function grabPrice(\DOMDocument $doc): ?float
    {
        $path = new \DOMXPath($doc);

        $priceEl = $path->query('//span[@class="price alone"]/span[@class="price-vatin"]');
        if ($priceEl->length === 0) {
            $priceEl = $path->query('//span[@class="price action"]/span[@class="price-vatin"]');
        }

        if ($priceEl->length !== 0) {
            $price = trim($priceEl->item(0)->textContent);
            $price = Strings::replace($price, '/[^0-9,.]/', '');

            return floatval($price);
        } else return null;
    }


    public static function grabRating(\DOMDocument $doc): ?float
    {
        $path = new \DOMXPath($doc);
        $ratingEl = $path->query('//span[@class="rating"]/span[@class="rating__label"]');

        if ($ratingEl->length !== 0) {
            $rating = trim($ratingEl->item(0)->textContent);
            $rating = Strings::replace($rating, '/[^0-9,.]/', '');

            return floatval($rating) / 100;
        } else return null;
    }
}