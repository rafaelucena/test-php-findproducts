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
    }

    /**
     * @param array $rulesDecoded
     * @return void
     */
    private function setRules(array $rulesDecoded): void
    {
        $this->rules = new Rules($rulesDecoded);
    }
}
