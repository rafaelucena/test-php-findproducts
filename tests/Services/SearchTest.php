<?php

namespace Recruitment\Tests\Services;

use Recruitment\Services\Search;
use Recruitment\Tests\BaseTest;

class SearchTest extends BaseTest
{
    /** @var Search */
    private $search;

    protected function setUp(): void
    {
        parent::setUp();
        $this->search = new Search($this->source['rules']);
    }

    public function testFilteredParametersWithIntersection()
    {
        $firstParameters = $this->source['products'][42]['parameters'];

        $result = $this->callMethod($this->search, 'getFilteredParametersWithIntersection', [$firstParameters]);
        $this->assertCount(3, $result);
    }

    public function testIsBeaconUniqueWhateverOrderOfParameters()
    {
        $products = array_values($this->source['products']);
        $parameters = $products[0]['parameters'];

        $beacon = $this->callMethod($this->search, 'setBeacon', [$parameters]);
        $this->assertEquals('b2d23bd10bf680d0b21a751f7d41c4be', $beacon);

        $beacon = $this->callMethod($this->search, 'setBeacon', [array_reverse($parameters)]);
        $this->assertEquals('b2d23bd10bf680d0b21a751f7d41c4be', $beacon);
    }

    public function testPassBasicCategoryRule()
    {
        $passProduct = $this->source['products'][42];
        $notPassProduct = $this->source['products'][99];
        $this->assertTrue($this->callMethod($this->search, 'passBasicCategoryRule', [$passProduct]));
        $this->assertFalse($this->callMethod($this->search, 'passBasicCategoryRule', [$notPassProduct]));
    }

    public function testPassMainProductsRules()
    {
        $passProductFind = $this->source['products'][42];
        $notPassProductMatch = $this->source['products'][50];
        $this->assertTrue($this->callMethod($this->search, 'passMainProductsRules', [$passProductFind, 'find']));
        $this->assertFalse($this->callMethod($this->search, 'passMainProductsRules', [$notPassProductMatch, 'match']));
    }

    public function testReservedKeywords()
    {
        $passWords = ['is empty', 'this', 'any'];
        $notPassWords = ['is_empty', 'IS EMPTY', 'THIS', 'that', 'ANY', 'and'];

        foreach ($passWords as $passWord) {
            $this->assertTrue($this->callMethod($this->search, 'detectedReservedKeyword', [$passWord]));
        }
        foreach ($notPassWords as $notPassWord) {
            $this->assertFalse($this->callMethod($this->search, 'detectedReservedKeyword', [$notPassWord]));
        }
    }

    public function testPassReservedKeywordsRules()
    {
        $rule = ['parameter' => 'Szerokość', 'equals' => 'is empty'];
        $productNotPass = $this->source['products'][42];
        $this->assertFalse($this->callMethod($this->search, 'validReservedCheck', [$productNotPass['parameters'], $rule]));
        $productPass = $this->source['products'][50];
        $this->assertTrue($this->callMethod($this->search, 'validReservedCheck', [$productPass['parameters'], $rule]));
    }

    public function testPassCommonRules()
    {
        $rule = ['parameter' => 'Szerokość', 'equals' => '23.2345'];
        $productPass = $this->source['products'][42];
        $this->assertTrue($this->callMethod($this->search, 'validCommonCheck', [$productPass['parameters'], $rule]));
        $productNotPass = $this->source['products'][50];
        $this->assertFalse($this->callMethod($this->search, 'validCommonCheck', [$productNotPass['parameters'], $rule]));
    }
}