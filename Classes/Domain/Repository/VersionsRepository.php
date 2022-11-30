<?php

declare(strict_types=1);

namespace AUBA\CmsCensus\Domain\Repository;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * This file is part of the "CMS Census Extension" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2021 Alexander Ullrich <alexander.ullrich@digitaler-mittelstand-dresden.de>, Digitaler Mittelstand Dresden GbR
 */

/**
 * The repository for Versions
 */
class VersionsRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    public function checkTokenExist(){
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_cmscensus_domain_model_versions');
        $statement = $queryBuilder
            ->select('*')
            ->from('tx_cmscensus_domain_model_versions')
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter(1, \PDO::PARAM_INT))
            )
            ->execute()->fetch();

        if($statement){
            $currantDate = new \DateTime('now');;
            $expireyDate = new \DateTime($statement['expirey']);
            $expireyDate->setTimezone(new \DateTimeZone(date_default_timezone_get()));
            if($currantDate < $expireyDate){
                return $statement['token'];
            } else {
                //Expired Token
                return 1;
            }
        } else {
            //No Token Saved
            return 0;
        }
        
    }

    public function saveToken($response,$stat = NULL){

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_cmscensus_domain_model_versions');
        if($stat == 1){
            $queryBuilder
            ->update('tx_cmscensus_domain_model_versions')
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter(1, \PDO::PARAM_INT))
            )
            ->set('token', $response->token)
            ->set('expirey', $response->expiry)
            ->execute();
        }elseif($stat == 0) {
            $queryBuilder
                ->insert('tx_cmscensus_domain_model_versions')
                ->values([
                    'uid' => 1,
                    'token' => $response->token,
                    'expirey' => $response->expiry,
                ])
                ->execute();
        }
        return $response->token;
    }
}
