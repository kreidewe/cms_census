<?php

declare(strict_types=1);

namespace AUBA\CmsCensus\Step;

use Cobweb\ExternalImport\Step\AbstractStep;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class for customize storePID parameter.
 *
 * @package AUBA\CmsCensus\Step
 */
class CustomizeStoragePIDStep extends AbstractStep
{
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
        $this->setStoragePID();
    }

    /**
     * Set pid to StoragePID if StoragePID is not 0.
     *
     * @return void
     */
    protected function setStoragePID(): void
    {
        $generalConfiguration = $this->getImporter()->getExternalConfiguration()->getGeneralConfiguration();
        if((int)$this->extensionConfiguration['storagePID'] != 0){
            $generalConfiguration['pid'] = (int)$this->extensionConfiguration['storagePID'];
            $this->getImporter()->getExternalConfiguration()->setGeneralConfiguration($generalConfiguration);
        }
    }
}