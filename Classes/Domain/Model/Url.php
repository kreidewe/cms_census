<?php

declare(strict_types=1);

namespace AUBA\CmsCensus\Domain\Model;


/**
 * This file is part of the "CMS Census Extension" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2021 Alexander Ullrich <alexander.ullrich@digitaler-mittelstand-dresden.de>, Digitaler Mittelstand Dresden GbR
 */

/**
 * URLs
 */
class Url extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * name
     *
     * @var string
     */
    protected $name = '';

    /**
     * description
     *
     * @var string
     */
    protected $description = '';

    /**
     * isProposal
     *
     * @var bool
     */
    protected $isProposal = false;

    /**
     * onlyNextAutoUpdate
     *
     * @var bool
     */
    protected $onlyNextAutoUpdate = false;

    /**
     * everyAutoUpdate
     *
     * @var bool
     */
    protected $everyAutoUpdate = false;

    /**
     * isAutoUpdatePlanned
     *
     * @var bool
     */
    protected $isAutoUpdatePlanned = false;

    /**
     * URL Categories
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\AUBA\CmsCensus\Domain\Model\Category>
     */
    protected $categories = null;

    /**
     * URL WhatCms.org Type
     *
     * @var string
     */
    protected $whatcmstype = '';

    /**
     * tstamp
     *
     * @var int
     */
    protected $tstamp;
    
    /**
     * @return int $tstamp
     */
    public function getTstamp()
    {
        return $this->tstamp;
    }

     /**
     * Sets the tstamp
     *
     * @param string $tstamp
     * @return void
     */
    public function setTstamp(string $tstamp)
    {
        $this->tstamp = $tstamp;
    }

    /**
     * __construct
     */
    public function __construct()
    {

        // Do not remove the next line: It would break the functionality
        $this->initializeObject();
    }

    /**
     * Initializes all ObjectStorage properties when model is reconstructed from DB (where __construct is not called)
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    public function initializeObject()
    {
        $this->categories = $this->categories ?: new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Returns the name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name
     *
     * @param string $name
     * @return void
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Returns the description
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the description
     *
     * @param string $description
     * @return void
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * Returns the isProposal
     *
     * @return bool $isProposal
     */
    public function getIsProposal()
    {
        return $this->isProposal;
    }

    /**
     * Sets the isProposal
     *
     * @param bool $isProposal
     * @return void
     */
    public function setIsProposal(bool $isProposal)
    {
        $this->isProposal = $isProposal;
    }

    /**
     * Returns the boolean state of isProposal
     *
     * @return bool
     */
    public function isIsProposal()
    {
        return $this->isProposal;
    }

    /**
     * Returns the onlyNextAutoUpdate
     *
     * @return bool $onlyNextAutoUpdate
     */
    public function getOnlyNextAutoUpdate()
    {
        return $this->onlyNextAutoUpdate;
    }

    /**
     * Sets the onlyNextAutoUpdate
     *
     * @param bool $onlyNextAutoUpdate
     * @return void
     */
    public function setOnlyNextAutoUpdate(bool $onlyNextAutoUpdate)
    {
        $this->onlyNextAutoUpdate = $onlyNextAutoUpdate;
    }

    /**
     * Returns the boolean state of onlyNextAutoUpdate
     *
     * @return bool
     */
    public function isOnlyNextAutoUpdate()
    {
        return $this->onlyNextAutoUpdate;
    }

    /**
     * Returns the everyAutoUpdate
     *
     * @return bool $everyAutoUpdate
     */
    public function getEveryAutoUpdate()
    {
        return $this->everyAutoUpdate;
    }

    /**
     * Sets the everyAutoUpdate
     *
     * @param bool $everyAutoUpdate
     * @return void
     */
    public function setEveryAutoUpdate(bool $everyAutoUpdate)
    {
        $this->everyAutoUpdate = $everyAutoUpdate;
    }

    /**
     * Returns the boolean state of everyAutoUpdate
     *
     * @return bool
     */
    public function isEveryAutoUpdate()
    {
        return $this->everyAutoUpdate;
    }

    /**
     * Sets the isAutoUpdatePlanned
     *
     * @param bool $isAutoUpdatePlanned
     * @return void
     */
    public function setIsAutoUpdatePlanned(bool $isAutoUpdatePlanned)
    {
        $this->isAutoUpdatePlanned = $isAutoUpdatePlanned;
    }

    /**
     * Returns the boolean state of isAutoUpdatePlanned
     *
     * @return bool
     */
    public function isAutoUpdatePlanned()
    {
        return $this->isAutoUpdatePlanned;
    }

    /**
     * Adds a Category
     *
     * @param \AUBA\CmsCensus\Domain\Model\Category $category
     * @return void
     */
    public function addCategory(\AUBA\CmsCensus\Domain\Model\Category $category)
    {
        $this->categories->attach($category);
    }

    /**
     * Removes a Category
     *
     * @param \AUBA\CmsCensus\Domain\Model\Category $categoryToRemove The Category to be removed
     * @return void
     */
    public function removeCategory(\AUBA\CmsCensus\Domain\Model\Category $categoryToRemove)
    {
        $this->categories->detach($categoryToRemove);
    }

    /**
     * Returns the categories
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\AUBA\CmsCensus\Domain\Model\Category> $categories
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Sets the categories
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\AUBA\CmsCensus\Domain\Model\Category> $categories
     * @return void
     */
    public function setCategories(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $categories)
    {
        $this->categories = $categories;
    }

    /**
     * Returns the whatcmstype
     *
     * @return string whatcmstype
     */
    public function getWhatcmstype()
    {
        return $this->whatcmstype;
    }

    /**
     * Sets the whatcmstype
     *
     * @param string $whatcmstype
     * @return void
     */
    public function setWhatcmstype(string $whatcmstype)
    {
        $this->whatcmstype = $whatcmstype;
    }
}
