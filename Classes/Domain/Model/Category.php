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
 * Categories
 */
class Category extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
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
}
