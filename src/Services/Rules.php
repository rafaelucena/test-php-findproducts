<?php

namespace Recruitment\Services;

class Rules
{
    /** @var string */
    private $name = '';

    /** @var string */
    private $basicCategoryRule = '';

    /** @var array */
    private $findProductsRules = [];

    /** @var array */
    private $matchProductsRules = [];

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
        $this->setMatchProductsRules($properties['matchProducts']);
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
     * @param array $matchProductsRules
     * @return void
     */
    private function setMatchProductsRules(array $matchProductsRules): void
    {
        foreach ($matchProductsRules as $key => $rule) {
            if ($this->isMirrorRule($rule)) {
                if ($rule['parameter'] === 'Kategoria') {
                    $this->basicCategoryRule = $this->getReflectionRule($rule['parameter']);
                }
                $matchProductsRules[$key]['equals'] = $this->getReflectionRule($rule['parameter']);
            }
        }

        $this->matchProductsRules = $matchProductsRules;
    }

    /**
     * @param array $rule
     * @return boolean
     */
    private function isMirrorRule(array $rule): bool
    {
        return $rule['equals'] === 'this';
    }

    /**
     * @return string
     */
    private function getReflectionRule(string $parameter): string
    {
        foreach ($this->findProductsRules as $rule) {
            if ($rule['parameter'] === $parameter) {
                return $rule['equals'];
            }
        }

        return '';
    }

    /**
     * @return array
     */
    public function getFindProductsRules(): array
    {
        return $this->findProductsRules;
    }

    /**
     * @return array
     */
    public function getMatchProductsRules(): array
    {
        return $this->matchProductsRules;
    }
}
