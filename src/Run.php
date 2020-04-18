<?php

namespace Recruitment;

use Recruitment\Services\Search;
use Recruitment\Services\Result;
use Recruitment\Services\Validate;

class Run
{
    /** @var array */
    private $productsDecoded;

    /** @var array */
    private $rulesDecoded;

    /** @var Search */
    private $search;

    /** @var Result */
    private $result;

    /**
     * @param array $inputs
     * @return bool
     */
    public function prepare(array $inputs): bool
    {
        if (count($inputs) !== 3) {
            echo sprintf('This script must have exactly 2 parameters, %d given' . "\n", (count($inputs) - 1));
            return false;
        }

        foreach ($inputs as $key => $input) {
            if ($key === 0) {
                continue;
            }
            if (file_exists($input) === false) {
                echo sprintf('File: \'%s\' does not exist, please fix the path or add the file.' . "\n", $input);
                return false;
            }
        }

        $this->setRulesDecoded($inputs[2]);
        if (Validate::validateRule($this->rulesDecoded) === false) {
            echo 'Invalid rules are set' . "\n";
            return false;
        }
        $this->setProductsDecoded($inputs[1]);
        if (Validate::validateProduct($this->productsDecoded) === false) {
            echo 'Invalid products are set' . "\n";
            return false;
        }
        return true;
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
    private function doSearch(): void
    {
        $this->search = new Search($this->rulesDecoded);
        $this->search->searchProducts($this->productsDecoded);
    }

    /**
     * @return void
     */
    private function doResult(): void
    {
        $this->result = new Result($this->search->getProductsFound(), $this->search->getProductsMatched());
        $this->result->buildResponse();
    }

    /**
     * @return void
     */
    public function execute(): void
    {
        $this->doSearch();
        $this->doResult();
        echo $this->result->getResponseJson() . "\n";
        // echo "Took to search: " . $this->search->getExecutionTime() . "\n";
        // echo "Took to group: " . $result->getExecutionTime() . "\n";
    }
}
