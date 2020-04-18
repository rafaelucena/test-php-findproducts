<?php

namespace Recruitment\Tests\Services;

use Recruitment\Services\Rules;
use Recruitment\Tests\BaseTest;

class RulesTest extends BaseTest
{
    /** @var Rules */
    private $rules;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rules = new Rules($this->source['rules']);
    }

    public function testGetArrayColumnNames()
    {
        $matchRules = $this->rules->getMatchProductsRules();
        $matchRulesColumns = array_values(array_column($matchRules, 'parameter'));
        $arrayColumns = $this->callMethod($this->rules, 'getArrayColumnRules', [$matchRules]);
        $this->assertEquals($matchRulesColumns, array_keys($arrayColumns ?? []));
    }

    public function testGetReflectionRule()
    {
        $parameter = 'Rodzaj konektora';
        $equals = '';
        $findRules = $this->rules->getFindProductsRules();
        foreach ($findRules as $findRule) {
            if ($findRule['parameter'] === $parameter) {
                $equals = $findRule['equals'];
            }
        }
        $reflectionRuleEquals = $this->callMethod($this->rules, 'getReflectionRule', [$parameter]);
        $this->assertEquals($equals, $reflectionRuleEquals);
    }

    public function testIntersectedRulesInterpolate()
    {
        $findRulesColumns = array_values(array_column($this->rules->getFindProductsRules(), 'parameter'));
        $matchRulesColumns = array_values(array_column($this->rules->getMatchProductsRules(), 'parameter'));
        $intersectedColumns = array_intersect($findRulesColumns, $matchRulesColumns);

        $intersectedColumnsReturned = $this->rules->getIntersectedParameters();
        foreach ($intersectedColumnsReturned as $intersectedColumnReturned) {
            $this->assertArrayHasKey($intersectedColumnReturned, array_flip($intersectedColumns));
        }
        $this->assertArrayNotHasKey('Typ akcesoriów do złącz', array_flip($intersectedColumnsReturned));
    }
}