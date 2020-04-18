<?php

namespace Recruitment\Services;

use Recruitment\Helpers\RulesHelper;

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

    /** @var array */
    private $intersectedParameters = [];

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
        $this->setName($properties[RulesHelper::PROPERTY_NAME]);
        $this->setFindProductsRules($properties[RulesHelper::PROPERTY_FIND]);
        $this->setMatchProductsRules($properties[RulesHelper::PROPERTY_MATCH]);
        $this->setIntersectedParameters();
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
                if ($rule[RulesHelper::SUBPROPERTY_PARAMETER] === 'Kategoria') {
                    $this->basicCategoryRule = $this->getReflectionRule($rule[RulesHelper::SUBPROPERTY_PARAMETER]);
                }
                $parameter = $rule[RulesHelper::SUBPROPERTY_PARAMETER];
                $matchProductsRules[$key][RulesHelper::SUBPROPERTY_EQUALS] = $this->getReflectionRule($parameter);
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
        return $rule[RulesHelper::SUBPROPERTY_EQUALS] === RulesHelper::RESERVED_KEYWORD_THIS;
    }

    /**
     * @param string $parameter
     * @return string
     */
    private function getReflectionRule(string $parameter): string
    {
        foreach ($this->findProductsRules as $rule) {
            if ($rule[RulesHelper::SUBPROPERTY_PARAMETER] === $parameter) {
                return $rule[RulesHelper::SUBPROPERTY_EQUALS];
            }
        }

        return '';
    }

    /**
     * @return void
     */
    private function setIntersectedParameters(): void
    {
        $findRules = $this->getArrayColumnRules($this->findProductsRules);
        $matchRules = $this->getArrayColumnRules($this->matchProductsRules);

        $intersectedResult = array_intersect($findRules, $matchRules);
        foreach ($intersectedResult as $key => $value) {
            // Check and remove array key when is empty is set
            if ($value === RulesHelper::RESERVED_KEYWORD_IS_EMPTY) {
                unset($intersectedResult[$key]);
            }
        }

        // In here we save only the keys of the intersected array for using later
        $this->intersectedParameters = array_keys($intersectedResult);
    }

    /**
     * @param array $input
     * @return array
     */
    private function getArrayColumnRules(array $input): array
    {
        return array_column($input, RulesHelper::SUBPROPERTY_EQUALS, RulesHelper::SUBPROPERTY_PARAMETER);
    }

    /**
     * @return string
     */
    public function getBasicCategoryRule(): string
    {
        return $this->basicCategoryRule;
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

    /**
     * @return array
     */
    public function getIntersectedParameters(): array
    {
        return $this->intersectedParameters;
    }
}
