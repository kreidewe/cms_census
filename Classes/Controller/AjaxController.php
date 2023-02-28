<?php

declare(strict_types=1);

namespace AUBA\CmsCensus\Controller;

use AUBA\CmsCensus\CmsStatistics\CategoryUrls;
use AUBA\CmsCensus\Domain\Model\Category;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class AjaxController extends ActionController
{
    /**
     * categoryUrls
     *
     * @var CategoryUrls
     */
    protected $categoryUrls;

    /**
     * @return ResponseInterface
     */
    public function cmsPerCategoryUrlsAction(Category $category = null): ResponseInterface
    {
        // Do all Urls, if catergory is empty
        if ($category == null || $category->getName() == '') {
            $categoryUid = 0;
        } else {
            $categoryUid = $category->getUid();
        }

        $this->categoryUrls = GeneralUtility::makeInstance(CategoryUrls::class);

        $data = [
            'cmsPerCategoryUrls' => $this->categoryUrls->countCmsOfCategoryUrls($categoryUid),
        ];

        return (new JsonResponse())->setPayload($data);
    }
}
