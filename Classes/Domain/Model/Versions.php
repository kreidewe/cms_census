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
 * whatcms.org type
 */
class Versions extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * token
     *
     * @var string
     */
    protected $token = '';

    /**
     * expirey
     *
     * @var string
     */
    protected $expirey = '';


    /**
     * Returns the token
     *
     * @return string $token
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Sets the token
     *
     * @param string $token
     * @return void
     */
    public function setToken(string $token)
    {
        $this->token = $token;
    }

    /**
     * Returns the expirey
     *
     * @return string $expirey
     */
    public function getExpirey()
    {
        return $this->expirey;
    }

    /**
     * Sets the expirey
     *
     * @param string $expirey
     * @return void
     */
    public function setExpirey(string $expirey)
    {
        $this->expirey = $expirey;
    }
}
