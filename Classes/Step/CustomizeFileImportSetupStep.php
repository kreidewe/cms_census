<?php

declare(strict_types=1);

namespace AUBA\CmsCensus\Step;

use AUBA\CmsCensus\Domain\Model\Category;
use AUBA\CmsCensus\Domain\Repository\CategoryRepository;
use Cobweb\ExternalImport\Step\AbstractStep;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

/**
 * Class for adapting the connector URI parameter.
 *
 * @package AUBA\CmsCensus\Step
 */
class CustomizeFileImportSetupStep extends AbstractStep
{

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     */
    protected $persistenceManager;

    /**
     * @var array Extension configuration
     */
    protected $extensionConfiguration = [];

    /**
     * categoryRepository
     *
     * @var CategoryRepository
     */
    protected $categoryRepository = null;

    /**
     * CustomizeSetupStep constructor.
     *
     * @param CategoryRepository $categoryRepository
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException
     * @throws \TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class)
            ->get('cms_census');

        $this->persistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);

        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Performs the actual tasks of the step.
     *
     * @return void
     */
    public function run(): void
    {
        $this->setUriParameter();
        $this->setCategory();
    }

    /**
     * Set uri parameter.
     *
     * @return void
     */
    protected function setUriParameter(): void
    {
        $destinationAbsolutePath = GeneralUtility::getFileAbsFileName($GLOBALS['TYPO3_CONF_VARS']['BE']['fileadminDir']);
        $generalConfiguration = $this->getImporter()->getExternalConfiguration()->getGeneralConfiguration();
        $parameters = $generalConfiguration['parameters'];

        foreach ($parameters as $key => $value) {
            // Update the "whatCmsJsonFilePath" string from the "uri" parameter, if it exists
            if ($key === 'uri' && strpos($value, 'fileadminImportFolderPath') !== false) {
                $parameters[$key] = str_replace('fileadminImportFolderPath',
                    $destinationAbsolutePath . $this->extensionConfiguration['fileadminImportFolderPath'], $value);
                $parameters[$key] = str_replace('importFileName', $this->extensionConfiguration['importFileName'],
                    $parameters[$key]);
                $parameters[$key] = str_replace('//', '/', $parameters[$key]);
            }
        }

        $generalConfiguration['parameters'] = $parameters;
        $this->getImporter()->getExternalConfiguration()->setGeneralConfiguration($generalConfiguration);
    }

    /**
     * Set categories column configuration
     *
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     */
    protected function setCategory(): void
    {
        $columnConfiguration = $this->getImporter()->getExternalConfiguration()->getColumnConfiguration();

        if($this->extensionConfiguration['importCategoryName'] != '') {
            $query = $this->categoryRepository->createQuery();
            $queryResult = $query->statement(
                'SELECT * 
                        FROM tx_cmscensus_domain_model_category
                        WHERE name = ?
                        AND deleted = 0',
                [$this->extensionConfiguration['importCategoryName']]
            )->execute();

            if(count($queryResult) == 0){
                $newCategory = GeneralUtility::makeInstance(Category::class);
                $newCategory->setName($this->extensionConfiguration['importCategoryName']);
                $newCategory->setPid((int)$this->extensionConfiguration['storagePID']);
                $this->categoryRepository->add($newCategory);
                $this->persistenceManager->persistAll();

                $categoriesTransformations[10] = array('value' => $newCategory->getUid());
            }else{
                $categoriesTransformations[10] = array('value' => $queryResult[0]->getUid());
            }

            $categories['transformations'] = $categoriesTransformations;
            $columnConfiguration['categories'] = $categories;
            $this->getImporter()->getExternalConfiguration()->setColumnConfiguration($columnConfiguration);
        }
    }
}