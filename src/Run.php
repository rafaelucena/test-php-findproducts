<?php

namespace Recruitment;

use Recruitment\Services\Search;

class Run
{
    /** @var array */
    private $productsDecoded;

    /** @var array */
    private $rulesDecoded;

    /** @var Search */
    private $search;

    /**
     * @param array $inputs
     */
    public function __construct(array $inputs)
    {
        $this->mapInputs($inputs);
    }

    /**
     * @param array $inputs
     * @return void
     */
    private function mapInputs(array $inputs): void
    {
        if (count($inputs) !== 3) {
            echo "This script must have exactly two parameters\n";
            return;
        }

        $this->setProductsDecoded($inputs[1]);
        $this->setRulesDecoded($inputs[2]);
    }

    /**
     * @param string $productsPath
     * @return self
     */
    private function setProductsDecoded(string $productsPath): self
    {
        // $this->productsDecoded = json_decode(file_get_contents(__DIR__ . '/../data/products_basic.json'), true);
        // $this->productsDecoded = json_decode(file_get_contents(__DIR__ . '/../data/products.json'), true);
        $this->productsDecoded = json_decode(file_get_contents(__DIR__ . '/../data/products_big.json'), true);

        return $this;
    }

    /**
     * @param string $rulesPath
     * @return self
     */
    private function setRulesDecoded(string $rulesPath): self
    {
        // $this->rulesDecoded = json_decode(file_get_contents(__DIR__ . '/../data/rule.json'), true);
        // $this->rulesDecoded = json_decode(file_get_contents(__DIR__ . '/../data/rule_big.json'), true);
        $this->rulesDecoded = json_decode(file_get_contents(__DIR__ . '/../data/rule_male_female.json'), true);

        return $this;
    }

    /**
     * @return void
     */
    private function setSearch(): void
    {
        $startDecode = round(microtime(true) * 1000);
        $this->search = new Search($this->rulesDecoded, $this->productsDecoded);
        $endDecode = round(microtime(true) * 1000);

        echo "Took to decode: " . (string)($endDecode - $startDecode) . "\n";
    }

    /**
     * @return void
     */
    public function forest(): void
    {
        $this->setSearch();
    }
}
