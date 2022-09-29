<?php
declare(strict_types=1);

namespace AUBA\CmsCensus\Tests\Unit\Domain\Model;

use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 *
 * @author Alexander Ullrich <alexander.ullrich@digitaler-mittelstand-dresden.de>
 */
class UrlTest extends UnitTestCase
{
    /**
     * @var \AUBA\CmsCensus\Domain\Model\Url|MockObject|AccessibleObjectInterface
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = $this->getAccessibleMock(
            \AUBA\CmsCensus\Domain\Model\Url::class,
            ['dummy']
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getNameReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getName()
        );
    }

    /**
     * @test
     */
    public function setNameForStringSetsName(): void
    {
        $this->subject->setName('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('name'));
    }

    /**
     * @test
     */
    public function getDescriptionReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getDescription()
        );
    }

    /**
     * @test
     */
    public function setDescriptionForStringSetsDescription(): void
    {
        $this->subject->setDescription('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('description'));
    }

    /**
     * @test
     */
    public function getIsProposalReturnsInitialValueForBool(): void
    {
        self::assertFalse($this->subject->getIsProposal());
    }

    /**
     * @test
     */
    public function setIsProposalForBoolSetsIsProposal(): void
    {
        $this->subject->setIsProposal(true);

        self::assertEquals(true, $this->subject->_get('isProposal'));
    }

    /**
     * @test
     */
    public function getOnlyNextAutoUpdateReturnsInitialValueForBool(): void
    {
        self::assertFalse($this->subject->getOnlyNextAutoUpdate());
    }

    /**
     * @test
     */
    public function setOnlyNextAutoUpdateForBoolSetsOnlyNextAutoUpdate(): void
    {
        $this->subject->setOnlyNextAutoUpdate(true);

        self::assertEquals(true, $this->subject->_get('onlyNextAutoUpdate'));
    }

    /**
     * @test
     */
    public function getEveryAutoUpdateReturnsInitialValueForBool(): void
    {
        self::assertFalse($this->subject->getEveryAutoUpdate());
    }

    /**
     * @test
     */
    public function setEveryAutoUpdateForBoolSetsEveryAutoUpdate(): void
    {
        $this->subject->setEveryAutoUpdate(true);

        self::assertEquals(true, $this->subject->_get('everyAutoUpdate'));
    }

    /**
     * @test
     */
    public function getCategorysReturnsInitialValueForCategory(): void
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getCategorys()
        );
    }

    /**
     * @test
     */
    public function setCategorysForObjectStorageContainingCategorySetsCategorys(): void
    {
        $category = new \AUBA\CmsCensus\Domain\Model\Category();
        $objectStorageHoldingExactlyOneCategorys = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneCategorys->attach($category);
        $this->subject->setCategorys($objectStorageHoldingExactlyOneCategorys);

        self::assertEquals($objectStorageHoldingExactlyOneCategorys, $this->subject->_get('categories'));
    }

    /**
     * @test
     */
    public function addCategoryToObjectStorageHoldingCategorys(): void
    {
        $category = new \AUBA\CmsCensus\Domain\Model\Category();
        $categorysObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->onlyMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $categorysObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($category));
        $this->subject->_set('categories', $categorysObjectStorageMock);

        $this->subject->addCategory($category);
    }

    /**
     * @test
     */
    public function removeCategoryFromObjectStorageHoldingCategorys(): void
    {
        $category = new \AUBA\CmsCensus\Domain\Model\Category();
        $categorysObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->onlyMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $categorysObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($category));
        $this->subject->_set('categories', $categorysObjectStorageMock);

        $this->subject->removeCategory($category);
    }

    /**
     * @test
     */
    public function getWhatcmstypeReturnsInitialValueForWhatCmsType(): void
    {
        self::assertEquals(
            null,
            $this->subject->getWhatcmstype()
        );
    }

    /**
     * @test
     */
    public function setWhatcmstypeForWhatCmsTypeSetsWhatcmstype(): void
    {
        $whatcmstypeFixture = new \AUBA\CmsCensus\Domain\Model\WhatCmsType();
        $this->subject->setWhatcmstype($whatcmstypeFixture);

        self::assertEquals($whatcmstypeFixture, $this->subject->_get('whatcmstype'));
    }
}
