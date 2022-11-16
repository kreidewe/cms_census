<?php

declare(strict_types=1);

namespace AUBA\CmsCensus\Domain\Repository;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;

/**
 * This file is part of the "CMS Census Extension" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2021 Alexander Ullrich <alexander.ullrich@digitaler-mittelstand-dresden.de>, Digitaler Mittelstand Dresden GbR
 */

/**
 * The repository for Urls
 */
class UrlRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    /**
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function fetchSearchResult($searchData,$sort,$formate){
        $query = $this->createQuery();
        if($searchData['domain']){
            $sort = isset($sort) ? $sort : 'uid';
            $formate = isset($formate) ? $formate : 'ASC';
            $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_cmscensus_domain_model_url');
            $queryBuilder
            ->select('*')
            ->from('tx_cmscensus_domain_model_url')
            ->where(
                $queryBuilder->expr()->like(
                    'name',
                    $queryBuilder->createNamedParameter($queryBuilder->escapeLikeWildcards($searchData['domain']) . '%')
                )
            )
            ->addOrderBy((string)$sort, $formate);
        } 
        $out = $queryBuilder->execute();
        $result = [];
        while ($row = $out->fetchAssociative()) {
            $result[] = $row;
        }
        return $result;
    }
}
