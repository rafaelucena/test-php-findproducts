<?php

namespace Recruitment\Services;

use Recruitment\Traits\Performance;

class Result
{
    use Performance;

    private $productsFound;

    private $productsMatched;

    private $symbolSearch;

    private $response;

    public function __construct($productsFound, $productsMatched)
    {
        $this->setProductsFound($productsFound);
        $this->setProductsMatched($productsMatched);
    }

    private function setProductsFound($productsFound)
    {
        $this->productsFound = $productsFound;
    }

    private function setProductsMatched($productsMatched)
    {
        $this->productsMatched = $productsMatched;
    }

    public function buildResponse()
    {
        $response = [];

        $prepareMatch = [];
        $startResponse = round(microtime(true) * 1000);
        foreach ($this->productsMatched as $matchedProduct) {
            $prepareMatch[$matchedProduct['beacon']][$matchedProduct['symbol']] = $matchedProduct['symbol'];
        }

        foreach ($this->productsFound as $foundProduct) {
            $this->symbolSearch = $foundProduct['symbol'];

            $response[$this->symbolSearch] = [];
            $beacon = $foundProduct['beacon'];
            if (isset($prepareMatch[$beacon])) {
                $located = array_filter($prepareMatch[$beacon], function ($arrayKey) {
                    return $arrayKey !== $this->symbolSearch;
                }, ARRAY_FILTER_USE_KEY);
                $response[$this->symbolSearch] = array_values($located);
            }
        }
        $endResponse = round(microtime(true) * 1000);
        $this->setExecutionTime($endResponse - $startResponse);
        $this->response = $response;
    }

    /**
     * @return string
     */
    public function getResponseJson(): string
    {
        return json_encode($this->response, JSON_PRETTY_PRINT);
    }
}
