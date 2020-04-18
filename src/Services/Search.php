<?php

namespace Recruitment\Services;

use Recruitment\Helpers\ProductHelper;
use Recruitment\Helpers\RulesHelper;
use Recruitment\Services\Rules;
use Recruitment\Traits\Performance;

class Search
{
    use Performance;

    /** @var Rules */
    private $rules;

    /** @var array */
    private $productsFound = [];

    /** @var array */
    private $productsMatched = [];

    /**
     * @param array $rulesDecoded
     */
    public function __construct(array $rulesDecoded)
    {
        $this->setRules($rulesDecoded);
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
    public function searchProducts(array $produtsDecoded): void
    {
        $startSearch = round(microtime(true) * 1000);
        foreach ($produtsDecoded as $product) {
            if ($this->passBasicCategoryRule($product) === false) {
                continue;
            }
            if ($this->passMainProductsRules($product, 'find') === true) {
                $parameters = $product[ProductHelper::PROPERTY_PARAMETERS];
                $product[ProductHelper::PROPERTY_BEACON] = $this->setBeacon($parameters);
                $this->productsFound[] = $product;
            }
            if ($this->passMainProductsRules($product, 'match') === true) {
                if (isset($product[ProductHelper::PROPERTY_BEACON]) === false) {
                    $parameters = $product[ProductHelper::PROPERTY_PARAMETERS];
                    $product[ProductHelper::PROPERTY_BEACON] = $this->setBeacon($parameters);
                }
                $this->productsMatched[] = $product;
            }
        }
        $endSearch = round(microtime(true) * 1000);
        $this->setExecutionTime($endSearch - $startSearch);
    }

    /**
     * @param array $parameters
     * @return string
     */
    private function setBeacon(array $parameters): string
    {
        $callback = function ($arrayKey) {
            return in_array($arrayKey, $this->rules->getIntersectedParameters());
        };
        $arrayFiltered = array_filter($parameters, $callback, ARRAY_FILTER_USE_KEY);

        return md5(json_encode($arrayFiltered));
    }

    /**
     * @param array $product
     * @return boolean
     */
    private function passBasicCategoryRule(array $product): bool
    {
        if (empty($this->rules->getBasicCategoryRule()) === true) {
            return true;
        }

        if ($product[ProductHelper::PROPERTY_ID_CATEGORY] === $this->rules->getBasicCategoryRule()) {
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
            if ($this->detectedReservedKeyword($rule[RulesHelper::SUBPROPERTY_EQUALS])) {
                if ($this->validReservedCheck($product[ProductHelper::PROPERTY_PARAMETERS], $rule) === false) {
                    return false;
                }
            } elseif ($this->validCommonCheck($product[ProductHelper::PROPERTY_PARAMETERS], $rule) === false) {
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
        return in_array($keyword, RulesHelper::RESERVED_KEYWORDS);
    }

    /**
     * @param array $parameters
     * @param array $rule
     * @return boolean
     */
    private function validReservedCheck(array $parameters, array $rule): bool
    {
        $equals = $rule[RulesHelper::SUBPROPERTY_EQUALS];
        $key = $rule[RulesHelper::SUBPROPERTY_PARAMETER];
        if ($equals === RulesHelper::RESERVED_KEYWORD_ANY && isset($parameters[$key])) {
            return true;
        } elseif ($equals === RulesHelper::RESERVED_KEYWORD_IS_EMPTY && isset($parameters[$key]) === false) {
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
        if (isset($parameters[$rule[RulesHelper::SUBPROPERTY_PARAMETER]]) === false) {
            return false;
        }
        if ($parameters[$rule[RulesHelper::SUBPROPERTY_PARAMETER]] !== $rule[RulesHelper::SUBPROPERTY_EQUALS]) {
            return false;
        }

        return true;
    }

    /**
     * @return Rules
     */
    public function getRules(): Rules
    {
        return $this->rules;
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
}
