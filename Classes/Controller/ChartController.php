<?php

declare(strict_types=1);

namespace AUBA\CmsCensus\Controller;

use AUBA\CmsCensus\CmsStatistics\CategoryUrls;
use AUBA\CmsCensus\Domain\Repository\CategoryRepository;
use AUBA\CmsCensus\Domain\Repository\UrlRepository;
use TYPO3\CMS\Core\Pagination\ArrayPaginator;
use TYPO3\CMS\Core\Pagination\SimplePagination;
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
    protected $categoryUrls;

    /**
     * urlRepository
     *
     * @var UrlRepository
     */
    protected $urlRepository;

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
    protected $categoryRepository;

    /**
     * @param CategoryRepository $categoryRepository
     */
    public function injectCategoryRepository(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

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

    public function searchAction(): void
    {
        $currentPage = 1;
        $searchData = GeneralUtility::_GP('tx_cmscensus_chartcmscensus');
        $sortBy = GeneralUtility::_GP('sortby');
        $sort = GeneralUtility::_GP('formate');
        if ($searchData['domain']) {
            $sortBy=='null' ? $sortBy = null : $sortBy;
            $sort=='null' ? $sort = null : $sortBy;
            $searchResult = $this->urlRepository->fetchSearchResult($searchData, $sortBy, $sort);
        }

        if (!empty($GLOBALS['_GET']['tx_cmscensus_chartcmscensus']['currentPage'])) {
            $currentPage = (int)$GLOBALS['_GET']['tx_cmscensus_chartcmscensus']['currentPage'];
        }
        $arrayPaginator = new ArrayPaginator($searchResult, $currentPage, 10);
        $pagination = new SimplePagination($arrayPaginator);
        $this->view->assignMultiple(
            [
                'searchData' => $searchData,
                'sortby' => $sortBy,
                'formate' => $sort,
                'paginatedItems' => $arrayPaginator->getPaginatedItems(),
                'pages' => $pagination->getLastPageNumber(),
                'searchResult' => $searchResult,
                'paginator' => $arrayPaginator,
                'pagination' => $pagination,
            ]
        );
    }
}
