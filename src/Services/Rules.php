<?php

namespace Recruitment\Services;

class Rules
{
    private const KEYWORD_ANY = 'any';
    private const KEYWORD_THIS = 'this';
    private const KEYWORD_IS_EMPTY = 'is empty';

    public const RESERVED_KEYWORDS = [
        self::KEYWORD_ANY,
        self::KEYWORD_THIS,
        self::KEYWORD_IS_EMPTY,
    ];

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
        $this->setName($properties['name']);
        $this->setFindProductsRules($properties['findProducts']);
        $this->setMatchProductsRules($properties['matchProducts']);
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
     * @return void
     */
    private function setIntersectedParameters(): void
    {
        $findRules = array_column($this->findProductsRules, 'equals', 'parameter');
        $matchRules = array_column($this->matchProductsRules, 'equals', 'parameter');

        $intersectedResult = array_intersect($findRules, $matchRules);
        foreach ($intersectedResult as $key => $value) {
            if ($value === 'is empty') {
                unset($intersectedResult[$key]);
            }
        }

        // In here we save only the keys of the intersected array for using later
        $this->intersectedParameters = array_keys($intersectedResult);
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
