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
    public function fetchUrls($argumentPerCron,$sysfolderID){
        
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_cmscensus_domain_model_url');
        $flag = $queryBuilder->select('uid')
            ->from('tx_cmscensus_domain_model_url')
            ->where(
                $queryBuilder->expr()->eq('checkflag', $queryBuilder->createNamedParameter(1, \PDO::PARAM_INT))
            )
            ->execute()->fetchAssociative();

        if(!$flag){
            $flag = $queryBuilder->select('uid')
            ->from('tx_cmscensus_domain_model_url')
            ->where(
                $queryBuilder->expr()->eq('checkflag', $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT))
            )
            ->execute()->fetchAssociative();
            $flag['uid']--;
        }

        $out = $queryBuilder->select('*')
            ->from('tx_cmscensus_domain_model_url')
            ->where(
                $queryBuilder->expr()->gt('uid', $queryBuilder->createNamedParameter((int)$flag['uid'], \PDO::PARAM_INT))
            )
            ->andWhere(
                $queryBuilder->expr()->eq('pid', $queryBuilder->createNamedParameter((int)$sysfolderID, \PDO::PARAM_INT))
            )
            ->setMaxResults((int)$argumentPerCron)
            ->execute();
        
        $result = [];
        while ($row = $out->fetchAssociative()) {
            $result[] = $row;
        }

        return $result;
    }

    public function updateStatus($uid,$status){
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_cmscensus_domain_model_url');
        $queryBuilder
            ->update('tx_cmscensus_domain_model_url')
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, \PDO::PARAM_INT))
            )
            ->set('httpstatus', $status)
            ->execute();
        return;
    }

    public function updateFlag($uid,$sysfolderID){
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_cmscensus_domain_model_url');
        
        $lastUid = $queryBuilder->select('uid')
            ->from('tx_cmscensus_domain_model_url')
            ->where(
                $queryBuilder->expr()->eq('pid', $queryBuilder->createNamedParameter((int)$sysfolderID, \PDO::PARAM_INT))
            )
            ->addOrderBy('uid', 'DESC')
            ->execute()
            ->fetchOne();
        if((int)$lastUid != (int)$uid){
            $queryBuilder
            ->update('tx_cmscensus_domain_model_url')
            ->where(
                $queryBuilder->expr()->eq('checkflag', $queryBuilder->createNamedParameter(1, \PDO::PARAM_INT))
            )
            ->set('checkflag', 0)
            ->execute();

            $queryBuilder
                ->update('tx_cmscensus_domain_model_url')
                ->where(
                    $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, \PDO::PARAM_INT))
                )
                ->set('checkflag', 1)
                ->execute();
        } else {
            $queryBuilder
            ->update('tx_cmscensus_domain_model_url')
            ->where(
                $queryBuilder->expr()->eq('checkflag', $queryBuilder->createNamedParameter(1, \PDO::PARAM_INT))
            )
            ->set('checkflag', 0)
            ->execute();
        }
        return;        
    }
    /**
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function fetchSearchResult($searchData,$sort,$formate){

        if($searchData['domain']){
            $sort = $sort ? $sort : 'uid';
            $formate = $formate ? $formate : 'ASC';
            $order = $sort."\n".$formate;
            $query = $this->createQuery();
            $result = $query->statement(
                'SELECT *
                    FROM tx_cmscensus_domain_model_url 
                        INNER JOIN tx_cmscensus_url_category_mm 
                            ON tx_cmscensus_domain_model_url.uid = tx_cmscensus_url_category_mm.uid_local 
                    WHERE tx_cmscensus_url_category_mm.uid_foreign = ? 
                    AND `name` LIKE ?
                    AND is_proposal = 0
                    AND httpstatus = 200
                    AND deleted = 0
                    ORDER BY '.$order.'; ',
                [$searchData['catId'],'%'.$searchData['domain'].'%']
            )->execute();        
        } 
        return $result;
    }
}