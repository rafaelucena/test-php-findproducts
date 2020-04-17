<?php

namespace Recruitment\Services;

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

        $prepareMatch = [];
        $startResponse = round(microtime(true) * 1000);
        // first grouping matched products by the beacon
        foreach ($this->productsMatched as $productMatched) {
            $prepareMatch[$productMatched['beacon']][$productMatched['symbol']] = $productMatched['symbol'];
        }

        // then assigning the groups into each found product preventing duplicates
        foreach ($this->productsFound as $productFound) {
            $this->symbolSearch = $productFound['symbol'];

            $response[$this->symbolSearch] = [];
            $beacon = $productFound['beacon'];
            if (isset($prepareMatch[$beacon])) {
                $arrayFiltered = $this->getProductsMatchedWithoutSelf($prepareMatch[$beacon]);
                $response[$this->symbolSearch] = array_values($arrayFiltered);
            }
        }
        $endResponse = round(microtime(true) * 1000);
        $this->setExecutionTime($endResponse - $startResponse);
        $this->response = $response;
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
     * @return string
     */
    public function getResponseJson(): string
    {
        return json_encode($this->response, JSON_PRETTY_PRINT);
    }
}
