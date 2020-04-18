<?php

namespace Recruitment\Tests;

use Recruitment\Run;
use Recruitment\Tests\BaseTest;

class RunTest extends BaseTest
{
    /**
     * @test
     */
    public function mapInputsEchoErrorMessage()
    {
        $run = new Run();
        $this->expectOutputString('This script must have exactly 2 parameters, 1 given' . "\n");
        $run->prepare(['file.php', 'path/to/file/products.json']);
    }
}