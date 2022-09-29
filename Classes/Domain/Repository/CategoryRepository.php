<?php

declare(strict_types=1);

namespace AUBA\CmsCensus\Domain\Repository;

/**
 * This file is part of the "CMS Census Extension" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2021 Alexander Ullrich <alexander.ullrich@digitaler-mittelstand-dresden.de>, Digitaler Mittelstand Dresden GbR
 */

/**
 * The repository for Categories
 */
class CategoryRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    /**
     * @param String $categoryName
     * @return bool $categoryExist
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function isCategoryNameExist($categoryName): bool
    {
        $query = $this->createQuery();
        $query->matching(
            $query->like('name', $categoryName)
        );
        $result = $query->execute();

        if($result->count() > 0){
            return true;
        }else{
            return false;
        }
    }
}
