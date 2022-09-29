<?php

declare(strict_types=1);

namespace AUBA\CmsCensus\Controller;

use AUBA\CmsCensus\CmsStatistics\CategoryUrls;
use AUBA\CmsCensus\Domain\Repository\CategoryRepository;
use AUBA\CmsCensus\Domain\Repository\UrlRepository;
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
 * ChartController
 */
class ChartController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * categoryUrls
     *
     * @var CategoryUrls
     */
    protected $categoryUrls = null;

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
     *
     */
    public function showAction(): void
    {
        $categoryUid = (int)$this->settings['categoryUid'];
        $category = $this->categoryRepository->findByUid($categoryUid);

        $this->categoryUrls = GeneralUtility::makeInstance(CategoryUrls::class);
        $countCmsOfCategoryUrls = $this->categoryUrls->countCmsOfCategoryUrls($categoryUid);

        $this->view->assign('category', $category);
        $this->view->assign('tableCmsCountOfCategoryUrls', $countCmsOfCategoryUrls['cmsTable']);
        $this->view->assign('totalCountOfCMSUrls', $countCmsOfCategoryUrls['cmsSumCmsUrlsCounts']);
    }
}
