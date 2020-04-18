<?php

namespace Recruitment\Services;

use Recruitment\Helpers\ProductHelper;
use Recruitment\Helpers\RulesHelper;

class Validate
{
    /**
     * @param array $productset
     * @return boolean
     */
    public static function validateProduct($productset): bool
    {
        $products = [];
        $products[] = reset($productset);
        $products[] = next($productset);
        $products[] = end($productset);

        foreach ($products as $product) {
            foreach ($product as $key => $property) {
                if (in_array($key, ProductHelper::PROPERTIES) === false) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param array $ruleset
     * @return boolean
     */
    public static function validateRule(array $ruleset): bool
    {
        foreach ($ruleset as $key => $property) {
            if (in_array($key, RulesHelper::PROPERTIES) === false) {
                return false;
            } elseif (is_array($property) && self::isValidSubproperties($property) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $subproperties
     * @return boolean
     */
    private function isValidSubproperties(array $subproperties): bool
    {
        foreach ($subproperties as $rule) {
            foreach ($rule as $subproperty => $value) {
                if (in_array($subproperty, RulesHelper::SUBPROPERTIES) === false) {
                    return false;
                }
            }
        }

        return true;
    }
}
