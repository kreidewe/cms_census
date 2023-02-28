<?php

declare(strict_types=1);

namespace AUBA\CmsCensus\Controller;

use AUBA\CmsCensus\Domain\Model\Url;
use AUBA\CmsCensus\Domain\Repository\UrlRepository;
use AUBA\CmsCensus\Domain\Repository\VersionsRepository;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Http\RequestFactory;
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
 * VersionController
 */
class VersionController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
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
     * versionsRepository
     *
     * @var VersionsRepository
     */
    protected $versionsRepository;

    /**
     * @param VersionsRepository $versionsRepository
     */
    public function injectVersionsRepository(VersionsRepository $versionsRepository)
    {
        $this->versionsRepository = $versionsRepository;
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

    public function showAction(): void
    {
        //Token Validation
        $token = $this->versionsRepository->checkTokenExist();
        $username = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_cmscensus_versionscmscensus.']['username'];
        $password = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_cmscensus_versionscmscensus.']['password'];

        if ($token == 1 || $token == 0) {
            if ($username && $password) {
                $url = 'https://www.t3versions.com/api/auth/login/';
                $additionalOptions = [
                    'form_params' => [
                       'username' => $username,
                       'password' => $password,
                    ],
                ];
                $apiRequest = GeneralUtility::makeInstance(RequestFactory::class);
                $apiResponse = $apiRequest->request($url, 'POST', $additionalOptions);
                if ($apiResponse->getStatusCode() === 200) {
                    $response = json_decode($apiResponse->getBody()->getContents());
                    $token = $this->versionsRepository->saveToken($response, $token);
                }
            } else {
                echo 'Please add Id and password in Constant editor only then graph/Chart will show';
                die;
            }
        }

        // Static Chart Data
        if ($this->settings['enableStaticChart']) {
            $result = $this->fetchChartData($token);
            $this->view->assign('label', $result['label']);
            $this->view->assign('dataset', $result['dataset']);
        }

        // URL List
        if ($this->settings['enableStaticList']) {
            $response = $this->staticListById($token);
            $this->view->assign('listResult', $response->results);
        }

        //Chart Data By Id
        $searchData = GeneralUtility::_GP('tx_cmscensus_versionscmscensus');
        if (isset($searchData['listId'])) {
            $result = $this->fetchChartDataById($token, $searchData);
            $this->view->assign('label', $result['label']);
            $this->view->assign('dataset', $result['dataset']);
        }

        $this->view->assign('settings', $this->settings);
    }

    public function fetchChartData($token)
    {
        $url = 'https://www.t3versions.com/api/v1/statistics-latest/';
        $apiRequest = GeneralUtility::makeInstance(RequestFactory::class);
        $apiResponse = $apiRequest->request(
            $url,
            'GET',
            [
            'headers' => [
                'Cache-Control' => 'no-cache',
                'Authorization' => 'Token ' . $token,
                'allow_redirects' => false,
            ]]
        );
        if ($apiResponse->getStatusCode() === 200) {
            $response = json_decode($apiResponse->getBody()->getContents());
        }
        $result = [];

        $string = $response->results[0]->versions_line_chart_data;
        $label = trim($string, "{'labels': ");
        $result['label'] = substr($label, 0, strpos($label, ", 'datasets'"));
        $pos = strpos($string, "[{'data'");
        $dataset = substr($string, $pos);
        $oldstr = substr($dataset, 0, -1);
        $result['dataset'] = substr_replace($oldstr, "'label': 'Static-Latest',", 2, 0);

        return $result;
    }

    public function staticListById($token)
    {
        $url = 'https://www.t3versions.com/api/v1/statistics/';
        $apiRequest = GeneralUtility::makeInstance(RequestFactory::class);
        $apiResponse = $apiRequest->request(
            $url,
            'GET',
            [
            'headers' => [
                'Cache-Control' => 'no-cache',
                'Authorization' => 'Token ' . $token,
                'allow_redirects' => false,
                //'cookies' => true
            ]]
        );
        if ($apiResponse->getStatusCode() === 200) {
            $response = json_decode($apiResponse->getBody()->getContents());
        }
        return $response;
    }

    public function fetchChartDataById($token, $searchData)
    {
        $url = 'https://www.t3versions.com/api/v1/statistics/' . $searchData['listId'];
        $apiRequest = GeneralUtility::makeInstance(RequestFactory::class);
        $apiResponse = $apiRequest->request(
            $url,
            'GET',
            [
            'headers' => [
                'Cache-Control' => 'no-cache',
                'Authorization' => 'Token ' . $token,
                'allow_redirects' => false,
                //'cookies' => true
            ]]
        );
        if ($apiResponse->getStatusCode() === 200) {
            $response = json_decode($apiResponse->getBody()->getContents());
        }
        $result = [];
        $string = $response->results[0]->versions_line_chart_data;
        $label = trim($string, "{'labels': ");
        $result['label'] = substr($label, 0, strpos($label, ", 'datasets'"));
        $pos = strpos($string, "[{'data'");
        $dataset = substr($string, $pos);
        $oldstr = substr($dataset, 0, -1);
        $result['dataset'] = substr_replace($oldstr, "'label': 'TYPO3 versions',", 2, 0);
        return $result;
    }
}
