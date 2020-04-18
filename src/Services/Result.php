<?php

namespace Recruitment\Services;

use Recruitment\Helpers\ProductHelper;
use Recruitment\Traits\Performance;

class Result
{
    use Performance;

    /** @var array */
    private $productsFound;

    /** @var array */
    private $productsMatched;

    /** @var string */
    private $symbolSearch;

    /** @var array */
    private $response;

    /**
     * @param array $productsFound
     * @param array $productsMatched
     */
    public function __construct(array $productsFound, array $productsMatched)
    {
        $this->setProductsFound($productsFound);
        $this->setProductsMatched($productsMatched);
    }

    /**
     * @param array $productsFound
     * @return void
     */
    private function setProductsFound(array $productsFound): void
    {
        $this->productsFound = $productsFound;
    }

    /**
     * @param array $productsMatched
     * @return void
     */
    private function setProductsMatched(array $productsMatched): void
    {
        $this->productsMatched = $productsMatched;
    }

    /**
     * @return void
     */
    public function buildResponse(): void
    {
        $response = [];

        $startResponse = round(microtime(true) * 1000);

        // first grouping matched products by the beacon
        $matchedProductsGrouped = $this->groupMatchedProducts();
        // then assigning the groups into each found product preventing duplicates
        $response = $this->tieProductsMatchedWithFound($matchedProductsGrouped);

        $endResponse = round(microtime(true) * 1000);
        $this->setExecutionTime($endResponse - $startResponse);
        $this->response = $response;
    }

    /**
     * @return array
     */
    private function groupMatchedProducts(): array
    {
        $prepareMatched = [];
        foreach ($this->productsMatched as $productMatched) {
            $beacon = $productMatched[ProductHelper::PROPERTY_BEACON];
            $symbol = $productMatched[ProductHelper::PROPERTY_SYMBOL];
            $prepareMatched[$beacon][$symbol] = $symbol;
        }

        return $prepareMatched;
    }

    /**
     * @param array $groupedMatchedProducts
     * @return array
     */
    private function tieProductsMatchedWithFound(array $groupedMatchedProducts): array
    {
        $tiedProducts = [];
        foreach ($this->productsFound as $productFound) {
            $this->setSymbolSearch($productFound[ProductHelper::PROPERTY_SYMBOL]);

            $tiedProducts[$this->symbolSearch] = [];
            $beacon = $productFound[ProductHelper::PROPERTY_BEACON];
            if (isset($groupedMatchedProducts[$beacon])) {
                $arrayFiltered = $this->getProductsMatchedWithoutSelf($groupedMatchedProducts[$beacon]);
                $tiedProducts[$this->symbolSearch] = array_values($arrayFiltered);
            }
        }

        return $tiedProducts;
    }

    /**
     * @param string $symbolSearch
     * @return void
     */
    private function setSymbolSearch(string $symbolSearch): void
    {
        $this->symbolSearch = $symbolSearch;
    }

    /**
     * @param array $sliceOfProductsMatched
     * @return array
     */
    private function getProductsMatchedWithoutSelf(array $sliceOfProductsMatched): array
    {
        $callback = function ($arrayKey) {
            return $arrayKey !== $this->symbolSearch;
        };
        return array_filter($sliceOfProductsMatched, $callback, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @return array
     */
    public function getResponse(): array
    {
        return $this->response;
    }

    /**
     * @return string
     */
    public function getResponseJson(): string
    {
        return json_encode($this->response, JSON_PRETTY_PRINT);
    }
}
