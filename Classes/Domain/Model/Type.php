<?php

namespace Tollwerk\TwEprivacy\Domain\Model;


/***
 *
 * This file is part of the "Tollwerk E-Privacy" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020 Joschi Kuphal <joschi@tollwerk.de>, tollwerk GmbH
 *
 ***/

/**
 * ePrivacy Subject Type
 */
class Type extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * Title
     *
     * @var string
     */
    protected $title = '';

    /**
     * Needs Consent
     *
     * @var bool
     */
    protected $needsConsent = true;

    /**
     * Description
     *
     * @var string
     */
    protected $description = '';

    /**
     * Returns the title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * @param string $title
     *
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Return whether this type needs user consent
     *
     * @return bool Needs user consent
     */
    public function isNeedsConsent(): bool
    {
        return $this->needsConsent;
    }

    /**
     * Sets whether this type needs user consent
     *
     * @param bool $needsConsent Needs user consent
     */
    public function setNeedsConsent(bool $needsConsent): void
    {
        $this->needsConsent = $needsConsent;
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
     *
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
}
