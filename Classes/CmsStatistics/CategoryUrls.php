<?php

declare(strict_types=1);

namespace AUBA\CmsCensus\CmsStatistics;

use AUBA\CmsCensus\Domain\Repository\UrlRepository;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * CategoryUrls class
 */
class CategoryUrls
{
    /**
     * urlRepository
     *
     * @var UrlRepository
     */
    protected $urlRepository;

    /**
     * Constructor __construct
     *
     * @param UrlRepository $urlRepository
     */
    public function __construct(UrlRepository $urlRepository)
    {
        $this->urlRepository = $urlRepository;
    }

    /**
     * Count CMS of a Category Urls. Returns an array: ['data' => ..., 'labels' => ... ]
     *
     * @return array
     */
    public function countCmsOfCategoryUrls(int $categoryUid = 0): array
    {
        $query = $this->urlRepository->createQuery();

        if ($categoryUid == 0) {
            $queryResult = $query->statement(
                'SELECT *
                        FROM tx_cmscensus_domain_model_url
                        WHERE is_proposal = 0
                        GROUP BY whatcmstype'
            )->execute();
        } else {
            $queryResult = $query->statement(
                'SELECT *
                        FROM tx_cmscensus_domain_model_url
                            INNER JOIN tx_cmscensus_url_category_mm
                                ON tx_cmscensus_domain_model_url.uid = tx_cmscensus_url_category_mm.uid_local
                        WHERE tx_cmscensus_url_category_mm.uid_foreign = ?
                        AND is_proposal = 0
                        AND deleted = 0
                        AND httpstatus = 200
                        GROUP BY whatcmstype',
                [$categoryUid]
            )->execute();
        }

        $cmsUrlsCountArray = [];
        $cmsSumCmsUrlsCounts = 0;
        $notYetQueriedMessage = LocalizationUtility::translate(
            'tx_cmscensus_category_urls_controller_notYetQueriedMessage',
            'CmsCensus'
        );
        foreach ($queryResult as $row) {
            if ($categoryUid == 0) {
                $whatCmsTypeUrlsCount = $query->statement(
                    'SELECT *
                        FROM tx_cmscensus_domain_model_url
                        WHERE whatcmstype = ?
                        AND is_proposal = 0
                        AND httpstatus = 200
                        AND deleted = 0',
                    [$row->getWhatcmstype()]
                )->execute()->count();
            } else {
                $whatCmsTypeUrlsCount = $query->statement(
                    'SELECT *
                        FROM tx_cmscensus_domain_model_url
                            INNER JOIN tx_cmscensus_url_category_mm
                                ON tx_cmscensus_domain_model_url.uid = tx_cmscensus_url_category_mm.uid_local
                        WHERE tx_cmscensus_url_category_mm.uid_foreign = ?
                        AND whatcmstype = ?
                        AND is_proposal = 0
                        AND httpstatus = 200
                        AND deleted = 0',
                    [$categoryUid, $row->getWhatcmstype()]
                )->execute()->count();
            }

            $cmsName = $row->getWhatcmstype() === '0' ? $notYetQueriedMessage : $row->getWhatcmstype();
            $cmsUrlsCountArray[$cmsName] = $whatCmsTypeUrlsCount;
            $cmsSumCmsUrlsCounts = $cmsSumCmsUrlsCounts + $whatCmsTypeUrlsCount;
        }

        $cmsUrlsCounts = [];
        $cmsLabels = [];
        $cmsColors = [];
        $cmsTable = [];
        $arrayIndex = 0;
        $splitCounter = 255 / (count($cmsUrlsCountArray) == 0 ? 1 : count($cmsUrlsCountArray));
        $rgbSwitcher = 0;
        arsort($cmsUrlsCountArray);
        foreach ($cmsUrlsCountArray as $key => $value) {
            $cmsLabels[] = $key;
            $cmsUrlsCounts[] = $value;
            $cmsColors[] = $this->getRGBString($splitCounter, $rgbSwitcher, $arrayIndex);
            $cmsUrlsPercentages[] = round($value / $cmsSumCmsUrlsCounts * 100, 2, PHP_ROUND_HALF_UP);
            $cmsTable[$arrayIndex] = [
                'cmsLabel' => $key,
                'cmsUrlsCount' => $value,
                'cmsColor' => $cmsColors[$arrayIndex],
                'cmsUrlsPercentage' => $cmsUrlsPercentages[$arrayIndex],
            ];
            $arrayIndex++;
            $rgbSwitcher == 5 ? $rgbSwitcher = 0 : $rgbSwitcher++;
        }

        return [
            'cmsCategoryUid' => $categoryUid,
            'cmsUrlsCounts' => $cmsUrlsCounts,
            'cmsLabels' => $cmsLabels,
            'cmsColors' => $cmsColors,
            'cmsUrlsPercentages' => $cmsUrlsPercentages,
            'cmsTable' => $cmsTable,
            'cmsSumCmsUrlsCounts' => $cmsSumCmsUrlsCounts,
        ];
    }

    protected function getRGBString(float $splitCounter, int $rgbSwitcher, int $arrayIndex): string
    {
        switch ($rgbSwitcher) {
            case 0:
                return 'rgb(' . 0 . ',' . 0 . ',' . (255 - ($splitCounter * ($arrayIndex + 1))) . ')';
            case 1:
                return 'rgb(' . (0 + ($splitCounter * ($arrayIndex + 1))) . ',' . 105 . ',' . 155 . ')';
            case 2:
                return 'rgb(' . 0 . ',' . (255 - ($splitCounter * ($arrayIndex + 1))) . ',' . 0 . ')';
            case 3:
                return 'rgb(' . 230 . ',' . 150 . ',' . (0 + ($splitCounter * ($arrayIndex + 1))) . ')';
            case 4:
                return 'rgb(' . (255 - ($splitCounter * ($arrayIndex + 1))) . ',' . 0 . ',' . 0 . ')';
            case 5:
                return 'rgb(' . 250 . ',' . (0 + ($splitCounter * ($arrayIndex + 1))) . ',' . 150 . ')';
            default:
                return 'rgb(' . 0 . ',' . 0 . ',' . 0 . ')';
        }
    }
}
