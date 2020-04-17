<?php

namespace Recruitment;

use Recruitment\Services\Search;
use Recruitment\Services\Result;

class Run
{
    /** @var array */
    private $productsDecoded;

    /** @var array */
    private $rulesDecoded;

    /** @var Search */
    private $search;

    /**
     * @param array $inputs
     */
    public function __construct(array $inputs)
    {
        $this->mapInputs($inputs);
        $this->setSearch();
    }

    /**
     * @param array $inputs
     * @return void
     */
    private function mapInputs(array $inputs): void
    {
        if (count($inputs) !== 3) {
            echo sprintf('This script must have exactly 2 parameters, %d given' . "\n", count($inputs));
            exit;
        }

        foreach ($inputs as $key => $input) {
            if ($key === 0) {
                continue;
            }
            if (file_exists($input) === false) {
                echo sprintf('File: \'%s\' does not exist, please fix the path or add the file.' . "\n", $input);
                exit;
            }
        }

        $this->setProductsDecoded($inputs[1]);
        $this->setRulesDecoded($inputs[2]);
    }

    /**
     * @param string $productsPath
     * @return void
     */
    private function setProductsDecoded(string $productsPath): void
    {
        $this->productsDecoded = json_decode(file_get_contents($productsPath), true);
    }

    /**
     * @param string $rulesPath
     * @return void
     */
    private function setRulesDecoded(string $rulesPath): void
    {
        $this->rulesDecoded = json_decode(file_get_contents($rulesPath), true);
    }

    /**
     * @return void
     */
    private function setSearch(): void
    {
        $this->search = new Search($this->rulesDecoded, $this->productsDecoded);
    }

    /**
     * @return void
     */
    public function forest(): void
    {
        $result = new Result($this->search->getProductsFound(), $this->search->getProductsMatched());
        $result->buildResponse();
        echo $result->getResponseJson() . "\n";
        // echo "Took to search: " . $this->search->getExecutionTime() . "\n";
        // echo "Took to group: " . $result->getExecutionTime() . "\n";
    }
}
