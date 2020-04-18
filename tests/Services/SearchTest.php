<?php

namespace Recruitment\Tests\Service;

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
        $products = array_values($this->source['products']);
        $firstParameters = $products[0]['parameters'];

        $result = $this->callMethod($this->search, 'getFilteredParametersWithIntersection', [$firstParameters]);
        var_dump($result);
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
}