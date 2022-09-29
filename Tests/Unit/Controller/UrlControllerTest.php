<?php
declare(strict_types=1);

namespace AUBA\CmsCensus\Tests\Unit\Controller;

use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3Fluid\Fluid\View\ViewInterface;

/**
 * Test case
 *
 * @author Alexander Ullrich <alexander.ullrich@digitaler-mittelstand-dresden.de>
 */
class UrlControllerTest extends UnitTestCase
{
    /**
     * @var \AUBA\CmsCensus\Controller\UrlController|MockObject|AccessibleObjectInterface
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder($this->buildAccessibleProxy(\AUBA\CmsCensus\Controller\UrlController::class))
            ->onlyMethods(['redirect', 'forward', 'addFlashMessage'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function listActionFetchesAllUrlsFromRepositoryAndAssignsThemToView(): void
    {
        $allUrls = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $urlRepository = $this->getMockBuilder(\AUBA\CmsCensus\Domain\Repository\UrlRepository::class)
            ->onlyMethods(['findAll'])
            ->disableOriginalConstructor()
            ->getMock();
        $urlRepository->expects(self::once())->method('findAll')->will(self::returnValue($allUrls));
        $this->subject->_set('urlRepository', $urlRepository);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assign')->with('urls', $allUrls);
        $this->subject->_set('view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenUrlToView(): void
    {
        $url = new \AUBA\CmsCensus\Domain\Model\Url();

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $this->subject->_set('view', $view);
        $view->expects(self::once())->method('assign')->with('url', $url);

        $this->subject->showAction($url);
    }

    /**
     * @test
     */
    public function createActionAddsTheGivenUrlToUrlRepository(): void
    {
        $url = new \AUBA\CmsCensus\Domain\Model\Url();

        $urlRepository = $this->getMockBuilder(\AUBA\CmsCensus\Domain\Repository\UrlRepository::class)
            ->onlyMethods(['add'])
            ->disableOriginalConstructor()
            ->getMock();

        $urlRepository->expects(self::once())->method('add')->with($url);
        $this->subject->_set('urlRepository', $urlRepository);

        $this->subject->createAction($url);
    }

    /**
     * @test
     */
    public function editActionAssignsTheGivenUrlToView(): void
    {
        $url = new \AUBA\CmsCensus\Domain\Model\Url();

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $this->subject->_set('view', $view);
        $view->expects(self::once())->method('assign')->with('url', $url);

        $this->subject->editAction($url);
    }

    /**
     * @test
     */
    public function updateActionUpdatesTheGivenUrlInUrlRepository(): void
    {
        $url = new \AUBA\CmsCensus\Domain\Model\Url();

        $urlRepository = $this->getMockBuilder(\AUBA\CmsCensus\Domain\Repository\UrlRepository::class)
            ->onlyMethods(['update'])
            ->disableOriginalConstructor()
            ->getMock();

        $urlRepository->expects(self::once())->method('update')->with($url);
        $this->subject->_set('urlRepository', $urlRepository);

        $this->subject->updateAction($url);
    }

    /**
     * @test
     */
    public function deleteActionRemovesTheGivenUrlFromUrlRepository(): void
    {
        $url = new \AUBA\CmsCensus\Domain\Model\Url();

        $urlRepository = $this->getMockBuilder(\AUBA\CmsCensus\Domain\Repository\UrlRepository::class)
            ->onlyMethods(['remove'])
            ->disableOriginalConstructor()
            ->getMock();

        $urlRepository->expects(self::once())->method('remove')->with($url);
        $this->subject->_set('urlRepository', $urlRepository);

        $this->subject->deleteAction($url);
    }
}
