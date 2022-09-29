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
class WhatCmsTypeTest extends UnitTestCase
{
    /**
     * @var \AUBA\CmsCensus\Domain\Model\WhatCmsType|MockObject|AccessibleObjectInterface
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = $this->getAccessibleMock(
            \AUBA\CmsCensus\Domain\Model\WhatCmsType::class,
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
    public function getIdReturnsInitialValueForInt(): void
    {
        self::assertSame(
            0,
            $this->subject->getId()
        );
    }

    /**
     * @test
     */
    public function setIdForIntSetsId(): void
    {
        $this->subject->setId(12);

        self::assertEquals(12, $this->subject->_get('id'));
    }

    /**
     * @test
     */
    public function getLabelReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getLabel()
        );
    }

    /**
     * @test
     */
    public function setLabelForStringSetsLabel(): void
    {
        $this->subject->setLabel('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('label'));
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
}
