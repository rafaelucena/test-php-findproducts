<?php

namespace Recruitment\Tests\Services;

use Recruitment\Tests\BaseTest;
use Recruitment\Services\Result;

class ResultTest extends BaseTest
{
    private $result;

    protected function setUp(): void
    {
        parent::setUp();
        $this->result = new Result($this->source['found'], $this->source['matched']);
    }

    public function testMatchedProductsWithoutItselfOnTheList()
    {
        $matchedGrouped = $this->callMethod($this->result, 'groupMatchedProducts', []);
        $oneFoundProduct = $this->source['found'][0];

        $foundProductBeacon = $oneFoundProduct['beacon'];
        $foundProductSymbol = $oneFoundProduct['symbol'];
        $this->assertArrayHasKey($foundProductBeacon, $matchedGrouped);
        $this->assertArrayHasKey($foundProductSymbol, $matchedGrouped[$foundProductBeacon]);

        $this->callMethod($this->result, 'setSymbolSearch', [$foundProductSymbol]);
        $matchedGroupedWithoutSelf = $this->callMethod($this->result, 'getProductsMatchedWithoutSelf', [$matchedGrouped[$foundProductBeacon]]);

        $this->assertArrayNotHasKey($foundProductSymbol, $matchedGroupedWithoutSelf);
    }

    public function testKeysAreNotDuplicatedInNestedArrays()
    {
        $this->result->buildResponse();
        $response = $this->result->getResponse();

        foreach ($response as $key => $item) {
            $this->assertNotContains($key, $item);
        }
    }
}