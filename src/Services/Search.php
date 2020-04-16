<?php

namespace Recruitment\Services;

use Recruitment\Services\Rules;

class Search
{
    /** @var Rules */
    private $rules;

    /** @var array */
    private $productsFound = [];

    /** @var array */
    private $productsMatched = [];

    /**
     * @param array $rulesDecoded
     * @param array $productsDecoded
     */
    public function __construct(array $rulesDecoded, array $productsDecoded)
    {
        $this->setRules($rulesDecoded);
        $this->searchProducts($productsDecoded);
    }

    /**
     * @param array $rulesDecoded
     * @return void
     */
    private function setRules(array $rulesDecoded): void
    {
        $this->rules = new Rules($rulesDecoded);
    }

    /**
     * @param array $produtsDecoded
     * @return void
     */
    private function searchProducts(array $produtsDecoded): void
    {
        foreach ($produtsDecoded as $product) {
            if ($this->passBasicCategoryRule($product) === false) {
                continue;
            }
            if ($this->passMainProductsRules($product, 'find') === true) {
                $this->productsFound[] = $product;
            }
            if ($this->passMainProductsRules($product, 'match') === true) {
                $this->productsMatched[] = $product;
            }
        }

        // $this->rules->setProducts($produtsDecoded);
        echo "Found products: " . count($this->getProductsFound()) . "\n";
        echo "Matched products: " . count($this->getProductsMatched()) . "\n";
        // $this->rules->getProductsMatched();

        // $this->rules->getAnyProductMatched();
    }

    /**
     * @param array $product
     * @return boolean
     */
    private function passBasicCategoryRule(array $product): bool
    {
        if (empty($this->rules->basicCategoryRule) === true) {
            return true;
        }

        if ($product['id_category'] === $this->basicCategoryRule) {
            return true;
        }

        return false;
    }

    /**
     * @param array $product
     * @param string $type
     * @return boolean
     */
    private function passMainProductsRules(array $product, string $type): bool
    {
        $rules = [];
        if ($type === 'find') {
            $rules = $this->rules->getFindProductsRules();
        } elseif ($type === 'match') {
            $rules = $this->rules->getMatchProductsRules();
        }

        foreach ($rules as $rule) {
            if ($this->detectedReservedKeyword($rule['equals'])) {
                if ($this->validReservedCheck($product['parameters'], $rule) === false) {
                    return false;
                }
            } elseif ($this->validCommonCheck($product['parameters'], $rule) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $keyword
     * @return boolean
     */
    private function detectedReservedKeyword(string $keyword): bool
    {
        return in_array($keyword, Rules::RESERVED_KEYWORDS);
    }

    /**
     * @param array $parameters
     * @param array $rule
     * @return boolean
     */
    private function validReservedCheck(array $parameters, array $rule): bool
    {
        $equals = $rule['equals'];
        $key = $rule['parameter'];
        if ($equals === 'any' && isset($parameters[$key])) {
            return true;
        } elseif ($equals === 'is empty' && isset($parameters[$key]) === false) {
            return true;
        }

        return false;
    }

    /**
     * @param array $parameters
     * @param array $rule
     * @return boolean
     */
    private function validCommonCheck(array $parameters, array $rule): bool
    {
        if (isset($parameters[$rule['parameter']]) === false) {
            return false;
        }
        if ($parameters[$rule['parameter']] !== $rule['equals']) {
            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    public function getProductsFound(): array
    {
        return $this->productsFound;
    }

    /**
     * @return array
     */
    public function getProductsMatched(): array
    {
        return $this->productsMatched;
    }

    public function getAnyProductMatched()
    {
        $total = count($this->productsMatched);
        $randomElement = random_int(0, $total);

        $count = 0;
        foreach ($this->productsMatched as $product) {
            if ($count === $randomElement) {
                var_dump($product);
                die;
            }
            $count++;
        }
    }
}
