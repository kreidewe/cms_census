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
class CategoryControllerTest extends UnitTestCase
{
    /**
     * @var \AUBA\CmsCensus\Controller\CategoryController|MockObject|AccessibleObjectInterface
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder($this->buildAccessibleProxy(\AUBA\CmsCensus\Controller\CategoryController::class))
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
    public function listActionFetchesAllCategoriesFromRepositoryAndAssignsThemToView(): void
    {
        $allCategories = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $categoryRepository = $this->getMockBuilder(\AUBA\CmsCensus\Domain\Repository\CategoryRepository::class)
            ->onlyMethods(['findAll'])
            ->disableOriginalConstructor()
            ->getMock();
        $categoryRepository->expects(self::once())->method('findAll')->will(self::returnValue($allCategories));
        $this->subject->_set('categoryRepository', $categoryRepository);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assign')->with('categories', $allCategories);
        $this->subject->_set('view', $view);

        $this->subject->listAction();
    }
}
