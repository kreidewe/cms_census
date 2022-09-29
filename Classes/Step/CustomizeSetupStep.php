<?php

declare(strict_types=1);

namespace AUBA\CmsCensus\Step;

use Cobweb\ExternalImport\Step\AbstractStep;
use Doctrine\DBAL\Query\QueryBuilder;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class for adapting the connector URI parameter.
 *
 * @package AUBA\CmsCensus\Step
 */
class CustomizeSetupStep extends AbstractStep
{
    /**
     * queryBuilder
     *
     * @var QueryBuilder
     */
    protected $queryBuilder = null;

    /**
     * @var array Extension configuration
     */
    protected $extensionConfiguration = [];

    /**
     * CustomizeSetupStep constructor.
     *
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException
     */
    public function __construct()
    {
        $this->queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_cmscensus_domain_model_url');

        $this->extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class)
            ->get('cms_census');
    }

    /**
     * Performs the actual tasks of the step.
     *
     * @return void
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    public function run(): void
    {
        $this->setUriParameter();
    }

    /**
     * Set uri parameter.
     *
     * @return void
     */
    protected function setUriParameter(): void
    {
        $generalConfiguration = $this->getImporter()->getExternalConfiguration()->getGeneralConfiguration();
        $parameters = $generalConfiguration['parameters'];

        $requestUrl = $this->getRequestUrl();

        foreach ($parameters as $key => $value) {
            if($requestUrl != ''){
                // Update the "whatCmsApiKey" and "requestUrl" string from the "uri" parameter, if it exists
                if ($key === 'uri' && strpos($value, 'whatCmsApiKey') !== false) {
                    $parameters[$key] = str_replace('whatCmsApiKey', $this->extensionConfiguration['whatCmsApiKey'], $value);
                    $parameters[$key] = str_replace('requestUrl', $this->getRequestUrl(), $parameters[$key]);
                }
            } else {
                // Abort because no url was found
                $this->setAbortFlag(true);
                $this->getImporter()->addMessage('No URL was found for the import query!',
                    FlashMessage::WARNING
                );
            }
        }

        $generalConfiguration['parameters'] = $parameters;
        $this->getImporter()->getExternalConfiguration()->setGeneralConfiguration($generalConfiguration);
    }

    /**
     * Supplies the URL to be queried.
     *
     * @return string
     */
    protected function getRequestUrl(): string
    {
        // query all entries where is_auto_update_planned = 1
        $resultArray = $this->queryBuilder
            ->select('name')
            ->from('tx_cmscensus_domain_model_url')
            ->where(
                $this->queryBuilder->expr()->eq('is_auto_update_planned', '1'),
            )
            ->execute()
            ->fetchFirstColumn();

        // remove http:// or https://
        $requestUrl = str_replace('http://', '', $resultArray[0]);
        $requestUrl = str_replace('https://', '', $requestUrl);

        return $requestUrl;
    }
}