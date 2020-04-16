<?php

namespace Recruitment\Services;

class Rules
{
    /** @var string */
    private $name;

    /** @var array */
    private $findProductsRules = [];

    /**
     * @param array $properties
     */
    public function __construct(array $properties)
    {
        $this->fromArray($properties);
    }

    /**
     * @param array $properties
     * @return void
     */
    public function fromArray(array $properties): void
    {
        $this->setName($properties['name']);
        $this->setFindProductsRules($properties['findProducts']);
    }

    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param array $findProductsRules
     * @return void
     */
    private function setFindProductsRules(array $findProductsRules): void
    {
        $this->findProductsRules = $findProductsRules;
    }

    /**
     * @return array
     */
    public function getFindProductsRules(): array
    {
        return $this->findProductsRules;
    }
}
