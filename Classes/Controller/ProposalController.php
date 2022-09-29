<?php

declare(strict_types=1);

namespace AUBA\CmsCensus\Controller;

use AUBA\CmsCensus\Domain\Model\Category;
use AUBA\CmsCensus\Domain\Model\Url;
use AUBA\CmsCensus\Domain\Repository\CategoryRepository;
use AUBA\CmsCensus\Domain\Repository\UrlRepository;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * This file is part of the "CMS Census Extension" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2021 Alexander Ullrich <alexander.ullrich@digitaler-mittelstand-dresden.de>, Digitaler Mittelstand Dresden GbR
 */

/**
 * ProposalController
 */
class ProposalController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * urlRepository
     *
     * @var UrlRepository
     */
    protected $urlRepository = null;

    /**
     * @param UrlRepository $urlRepository
     */
    public function injectUrlRepository(UrlRepository $urlRepository)
    {
        $this->urlRepository = $urlRepository;
    }

    /**
     * categoryRepository
     *
     * @var CategoryRepository
     */
    protected $categoryRepository = null;

    /**
     * @param CategoryRepository $categoryRepository
     */
    public function injectCategoryRepository(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @var array Extension configuration
     */
    protected $extensionConfiguration = [];

    /**
     * @param ExtensionConfiguration $extensionConfiguration
     */
    public function injectExtensionConfiguration(ExtensionConfiguration $extensionConfiguration)
    {
        $this->extensionConfiguration = $extensionConfiguration->get('cms_census');
    }

    /**
     * action new
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function newAction(): \Psr\Http\Message\ResponseInterface
    {
        return $this->htmlResponse();
    }

    /**
     * Show form to add an new URL Proposal
     *
     * @param Url $url
     */
    public function addUrlFormAction(Url $url = null): void
    {
        // Checking whether the offer with authentication is activated
        // and forwarding if the user is not logged in
        if($this->extensionConfiguration['enableProposalWithAuth'] == 1){
            $context = GeneralUtility::makeInstance(Context::class);
            if (!$context->getPropertyFromAspect('frontend.user', 'isLoggedIn')) {
                $this->redirect(null, null, null, null, $this->extensionConfiguration['loginPID']);
            }
        }

        $this->view->assign('url', $url);
        $this->view->assign('categories', $this->categoryRepository->findAll());
    }

    /**
     * action create new URL
     *
     * @param \AUBA\CmsCensus\Domain\Model\Url $newUrl
     */
    public function createUrlAction(\AUBA\CmsCensus\Domain\Model\Url $newUrl)
    {
        if (filter_var($newUrl->getName(), FILTER_VALIDATE_URL)) {
            $newUrl->setIsProposal(true);
            $this->urlRepository->add($newUrl);
            $this->finishCreation('addUrlForm');
        } else {
            $errorMessageBody = LocalizationUtility::translate(
                'tx_cmscensus_flashmessage_proposal-error.url',
                'CmsCensus'
            );
            $this->addFlashMessage($errorMessageBody . ' => [' . $newUrl->getName() . ']', '', AbstractMessage::ERROR);
            $this->redirect('addUrlForm');
        }
    }

    /**
     * Show form to add an new Category Proposal
     *
     * @param Category $category
     */
    public function addCategoryFormAction(Category $category = null): void
    {
        $this->view->assign('category', $category);
    }

    /**
     * action create new Category
     *
     * @param \AUBA\CmsCensus\Domain\Model\Category $newCategory
     */
    public function createCategoryAction(\AUBA\CmsCensus\Domain\Model\Category $newCategory)
    {
        if ($this->categoryRepository->isCategoryNameExist($newCategory->getName())) {
            $warningMessageBody = LocalizationUtility::translate(
                'tx_cmscensus_flashmessage_proposal-warning-exist.category',
                'CmsCensus'
            );
            $this->addFlashMessage($warningMessageBody . ' => [' . $newCategory->getName() . ']', '',
                AbstractMessage::WARNING);
            $this->redirect('addCategoryForm');
        } else {
            $newCategory->setIsProposal(true);
            $this->categoryRepository->add($newCategory);
            $this->finishCreation('addCategoryForm');
        }
    }

    /**
     * function to complete the creation process
     *
     * @param String $redirectDestination
     */
    protected function finishCreation(string $redirectDestination): void
    {
        $flashMessageHeadline = LocalizationUtility::translate(
            'tx_cmscensus_flashmessage_proposal-created.headline',
            'CmsCensus'
        );
        $flashMessageBody = LocalizationUtility::translate(
            'tx_cmscensus_flashmessage_proposal-created.body',
            'CmsCensus'
        );

        $this->addFlashMessage($flashMessageBody, $flashMessageHeadline, AbstractMessage::OK);
        $this->redirect($redirectDestination);
    }
}
