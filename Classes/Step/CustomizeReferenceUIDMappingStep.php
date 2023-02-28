<?php

declare(strict_types=1);

namespace AUBA\CmsCensus\Step;

use AUBA\CmsCensus\Domain\Repository\UrlRepository;
use Cobweb\ExternalImport\Step\AbstractStep;
use Doctrine\DBAL\Query\QueryBuilder;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class for prepare the referenceUid mapping.
 */
class CustomizeReferenceUIDMappingStep extends AbstractStep
{
    /**
     * queryBuilder
     *
     * @var QueryBuilder
     */
    protected $queryBuilder;

    /**
     * urlRepository
     *
     * @var UrlRepository
     */
    protected $urlRepository;

    /**
     * CustomizeSetupStep constructor.
     *
     * @param UrlRepository $urlRepository
     *
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException
     */
    public function __construct(UrlRepository $urlRepository)
    {
        $this->queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_cmscensus_domain_model_url');

        $this->urlRepository = $urlRepository;
    }

    /**
     * Performs the actual tasks of the step.
     *
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    public function run(): void
    {
        $this->addRequestUrlToRawData();
    }

    /**
     * Add requestUrl to rawData Array.
     */
    protected function addRequestUrlToRawData(): void
    {
        $rawData = $this->getData()->getRawData();
        $rawData['result'] = $rawData['result'] + ['requestUrl' => $this->getRequestUrl()];
        $this->getData()->setRawData($rawData);

        // prepare the record to match name with requestUrl when record not empty
        $records = $this->getData()->getRecords();
        if (!empty($records)) {
            $records[0] = $records[0] + ['name' => $this->getRequestUrl()];
            $this->getData()->setRecords($records);
        } else {
            $this->removeUrlFromPlanning();
        }
    }

    /**
     * Supplies the URL to be store referenceUid.
     *
     * @return string
     */
    protected function getRequestUrl(): string
    {
        // query all entries where is_auto_update_planned = 1
        $query = $this->urlRepository->createQuery();
        $queryResult = $query->statement(
            'SELECT name
                        FROM tx_cmscensus_domain_model_url
                        WHERE is_auto_update_planned = ?',
            [1]
        )->execute();

        return $queryResult[0]->getName();
    }

    /**
     * Supplies the URL UID.
     *
     * @return int
     */
    protected function getRequestUrlUid(): int
    {
        // query all entries where is_auto_update_planned = 1
        $query = $this->urlRepository->createQuery();
        $queryResult = $query->statement(
            'SELECT uid
                        FROM tx_cmscensus_domain_model_url
                        WHERE is_auto_update_planned = ?',
            [1]
        )->execute();

        return $queryResult[0]->getUid();
    }

    /**
     * Remove the URL from planning, delete every auto update and set whatcmstype to unknown.
     */
    protected function removeUrlFromPlanning(): void
    {
        // update is_auto_update_planned with 0 where name is requestUrl
        $requestUrlUid = $this->getRequestUrlUid();

        /** TODO: Refactoring
        $query = $this->urlRepository->createQuery();
        $queryResult = $query->statement(
            'UPDATE tx_cmscensus_domain_model_url
                        SET is_auto_update_planned = ?,
                        SET every_auto_update = ?,
                        SET whatcmstype = ?
                        WHERE uid = ?', [0,0,'CMS Not Detected', $requestUrlUid]
        )->execute();
        **/
        $this->queryBuilder
            ->update('tx_cmscensus_domain_model_url')
            ->where(
                $this->queryBuilder->expr()->eq('uid', $requestUrlUid)
            )
            ->set('is_auto_update_planned', '0')
            ->set('every_auto_update', '0')
            ->set('whatcmstype', 'CMS Not Detected')
            ->execute();
    }
}
