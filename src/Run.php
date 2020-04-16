<?php

namespace Recruitment;

class Run
{
    /** @var array */
    private $productsDecoded;

    /** @var array */
    private $rulesDecoded;

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

    public function forest()
    {
        $test = 'what now';
    }
}
