<?php

declare(strict_types=1);

namespace AUBA\CmsCensus\UserFunction;

use Cobweb\ExternalImport\ImporterAwareInterface;
use Cobweb\ExternalImport\ImporterAwareTrait;

/**
 * user functions for the 'cms_census' extension
 *
 * @author Alexander Ullrich
 */
class Transformation implements ImporterAwareInterface
{
    use ImporterAwareTrait;

    /**
     * Takes a Array structure and transforms it into a comma separated string structure.
     *
     * @param array $record The full record that is being transformed
     * @param string $index The index of the field to transform
     * @param array $params Additional parameters from the TCA
     * @return string HTML structure
     */
    public function getCommaSeparatedValuesFromArray(array $record, string $index, array $params): string
    {
        $resultString = '';

        foreach ($record[$index] as $key => $value) {
            $resultString .= $value;
            if (($key + 1) != count($record[$index])) {
                $resultString .= ', ';
            }
        }

        return $resultString;
    }
}
