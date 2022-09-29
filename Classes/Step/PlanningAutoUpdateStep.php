<?php

declare(strict_types=1);

namespace AUBA\CmsCensus\Step;

use Cobweb\ExternalImport\Step\AbstractStep;
use Doctrine\DBAL\Query\QueryBuilder;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class for scheduling automatic updates.
 *
 * @package AUBA\CmsCensus\Step
 */
class PlanningAutoUpdateStep extends AbstractStep
{
    /**
     * queryBuilder
     *
     * @var QueryBuilder
     */
    protected $queryBuilder = null;

    /**
     * PlanningAutoUpdateStep constructor.
     *
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    public function __construct()
    {
        $this->queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_cmscensus_domain_model_url');
    }

    /**
     * Performs the actual tasks of the step.
     *
     * @return void
     */
    public function run(): void
    {
        if (!$this->isAutoUpdatePlanned()) {
            $this->planningAutoUpdate();
        }
    }

    /**
     * Check is auto update planned
     *
     * @return bool
     */
    protected function isAutoUpdatePlanned(): bool
    {
        $resultArray = $this->queryBuilder
            ->count('*')
            ->from('tx_cmscensus_domain_model_url')
            ->where(
                $this->queryBuilder->expr()->eq(
                    'is_auto_update_planned',
                    '1'
                )
            )
            ->execute()
            ->fetchFirstColumn();

        return $resultArray[0] > 0;
    }

    /**
     * Planning all entries to auto update
     *
     * @return void
     */
    protected function planningAutoUpdate(): void
    {
        // query all entries where:
        // only_next_auto_update = 1 or every_auto_update = 1 and is_proposal = 0
        $query = $this->queryBuilder
            ->select('uid')
            ->from('tx_cmscensus_domain_model_url')
            ->where(
                $this->queryBuilder->expr()->eq('only_next_auto_update', '1'),
            )
            ->orWhere(
                $this->queryBuilder->expr()->eq('every_auto_update', '1'),
            )
            ->andWhere(
                $this->queryBuilder->expr()->eq('is_proposal', '0')
            )
            ->execute();

        // set is_auto_update_planned to 1 on all query entries
        while ($row = $query->fetch()) {
            $this->queryBuilder
                ->update('tx_cmscensus_domain_model_url')
                ->where(
                    $this->queryBuilder->expr()->eq('uid', $row['uid'])
                )
                ->set('is_auto_update_planned', '1')
                ->set('only_next_auto_update', '0')
                ->execute();
        }
    }
}