<?php

declare(strict_types=1);

namespace AUBA\CmsCensus\Controller;

use AUBA\CmsCensus\CmsStatistics\CategoryUrls;
use AUBA\CmsCensus\Domain\Repository\CategoryRepository;
use AUBA\CmsCensus\Domain\Repository\UrlRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Pagination\ArrayPaginator;
use TYPO3\CMS\Core\Pagination\SimplePagination;

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

    public function searchAction(): void
    {
        $currentPage = 1;
        $searchData = GeneralUtility::_GP('tx_cmscensus_chartcmscensus');
        if($searchData['domain']) {
            $GLOBALS['_GET']['tx_cmscensus_chartcmscensus']['sortby'] = isset($GLOBALS['_GET']['tx_cmscensus_chartcmscensus']['sortby']) ? $GLOBALS['_GET']['tx_cmscensus_chartcmscensus']['sortby'] : null;
            $GLOBALS['_GET']['tx_cmscensus_chartcmscensus']['formate'] = isset($GLOBALS['_GET']['tx_cmscensus_chartcmscensus']['formate']) ? $GLOBALS['_GET']['tx_cmscensus_chartcmscensus']['formate'] : null;
            $searchResult = $this->urlRepository->fetchSearchResult($searchData, $GLOBALS['_GET']['tx_cmscensus_chartcmscensus']['sortby'],$GLOBALS['_GET']['tx_cmscensus_chartcmscensus']['formate']);
        }
        debug($GLOBALS['_GET']['tx_cmscensus_chartcmscensus']);
        if(!empty($GLOBALS['_GET']['tx_cmscensus_chartcmscensus']['currentPage']))
        {
            $currentPage = (int)$GLOBALS['_GET']['tx_cmscensus_chartcmscensus']['currentPage'];
        }
        $arrayPaginator = new ArrayPaginator($searchResult, $currentPage, 10);
        $pagination = new SimplePagination($arrayPaginator);
        debug($pagination);
        $this->view->assignMultiple(
            [
                'searchData' => $searchData,
                'sortby' => $GLOBALS['_GET']['tx_cmscensus_chartcmscensus']['sortby'],
                'formate' => $GLOBALS['_GET']['tx_cmscensus_chartcmscensus']['formate'],
                'paginatedItems' => $arrayPaginator->getPaginatedItems(),
                'pages' => $pagination->getLastPageNumber(),
                'searchResult' => $searchResult,
                'paginator' => $arrayPaginator,
                'pagination' => $pagination,
            ]
        );
    }
}
