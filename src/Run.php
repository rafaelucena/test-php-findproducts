<?php

namespace Recruitment;

class Run
{
    private $productsDecoded;

    private $rulesDecoded;

    public function __construct(array $inputs)
    {
        $this->mapInputs($inputs);
    }

    private function mapInputs($inputs)
    {
        if (count($inputs) !== 3) {
            echo "This script must have exactly two parameters\n";
            return;
        }

        $this->setProductsDecoded($inputs[1]);
        $this->setRulesDecoded($inputs[2]);
    }

    private function setProductsDecoded(string $productsPath): void
    {
        // $this->productsDecoded = json_decode(file_get_contents(__DIR__ . '/../data/products_basic.json'), true);
        // $this->productsDecoded = json_decode(file_get_contents(__DIR__ . '/../data/products.json'), true);
        $this->productsDecoded = json_decode(file_get_contents(__DIR__ . '/../data/products_big.json'), true);
    }

    private function setRulesDecoded(string $rulesPath): void
    {
        // $this->rulesDecoded = json_decode(file_get_contents(__DIR__ . '/../data/rule.json'), true);
        // $this->rulesDecoded = json_decode(file_get_contents(__DIR__ . '/../data/rule_big.json'), true);
        $this->rulesDecoded = json_decode(file_get_contents(__DIR__ . '/../data/rule_male_female.json'), true);
    }

    public function forest()
    {
        $test = 'what now';
    }
}
