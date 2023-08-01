<?php

namespace Tollwerk\TwEprivacy\Domain\Model;


use Tollwerk\TwEprivacy\Domain\Repository\SubjectRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

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
 * ePrivacy Subject
 */
class Subject extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * Title
     *
     * @var string
     */
    protected $title = '';

    /**
     * Public Name
     *
     * @var string
     */
    protected $name = '';

    /**
     * Unique Identifier
     *
     * @var string
     */
    protected $identifier = '';

    /**
     * Provider
     *
     * @var string
     */
    protected $provider = '';

    /**
     * Purpose
     *
     * @var string
     */
    protected $purpose = '';

    /**
     * Lifetime (seconds)
     *
     * @var int
     */
    protected $lifetime = 0;

    /**
     * Public
     *
     * @var bool
     */
    protected $public = true;

    /**
     * Expires with the session
     *
     * @var bool
     */
    protected $session = false;

    /**
     * Type
     *
     * @var \Tollwerk\TwEprivacy\Domain\Model\Type
     */
    protected $type = null;

    /**
     * Parent Set
     *
     * @var \Tollwerk\TwEprivacy\Domain\Model\Subject
     */
    protected $parentSet = null;

    /**
     * Mode
     *
     * @var int
     */
    protected $mode = self::MODE_COOKIE;

    // Modes
    const MODE_COOKIE = 0;
    const MODE_SET = 1;

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
     * Returns the public name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the public name
     *
     * @param string $name
     *
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Returns the identifier
     *
     * @return string $identifier
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Sets the identifier
     *
     * @param string $identifier
     *
     * @return void
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * Returns the provider
     *
     * @return string $provider
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Sets the provider
     *
     * @param string $provider
     *
     * @return void
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;
    }

    /**
     * Returns the purpose
     *
     * @return string $purpose
     */
    public function getPurpose()
    {
        return $this->purpose;
    }

    /**
     * Sets the purpose
     *
     * @param string $purpose
     *
     * @return void
     */
    public function setPurpose($purpose)
    {
        $this->purpose = $purpose;
    }

    /**
     * Returns the type
     *
     * @return \Tollwerk\TwEprivacy\Domain\Model\Type $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the type
     *
     * @param \Tollwerk\TwEprivacy\Domain\Model\Type $type
     *
     * @return void
     */
    public function setType(\Tollwerk\TwEprivacy\Domain\Model\Type $type)
    {
        $this->type = $type;
    }

    /**
     * Return the lifetime in seconds
     *
     * @return int Lifetime
     */
    public function getLifetime(): int
    {
        // @extensionScannerIgnoreLine
        return $this->lifetime;
    }

    /**
     * Set the lifetime in seconds
     *
     * @param int $lifetime Lifetime
     */
    public function setLifetime(int $lifetime): void
    {
        $this->lifetime = $lifetime;
    }

    /**
     * Return whether the subject is public
     *
     * @return bool Is public
     */
    public function isPublic(): bool
    {
        return $this->public;
    }

    /**
     * Set whether the subject is public
     *
     * @param bool $public Is public
     */
    public function setPublic(bool $public): void
    {
        $this->public = $public;
    }

    /**
     * Return whether the subject expires with the session
     *
     * @return bool Expires with the session
     */
    public function isSession(): bool
    {
        return $this->session;
    }

    /**
     * Set whether the subject expires with the session
     *
     * @param bool $session Expires with the session
     */
    public function setSession(bool $session): void
    {
        $this->session = $session;
    }

    /**
     * Return the mode
     *
     * @return int Mode
     */
    public function getMode(): int
    {
        return $this->mode;
    }

    /**
     * Set the mode
     *
     * @param int $mode Mode
     */
    public function setMode(int $mode): void
    {
        $this->mode = $mode;
    }

    /**
     * Return the parent set
     *
     * @return Subject Parent set
     */
    public function getParentSet(): ?Subject
    {
        return $this->parentSet;
    }

    /**
     * Set the parent set
     *
     * @param Subject $parentSet Parent set
     */
    public function setParentSet(Subject $parentSet): void
    {
        $this->parentSet = $parentSet;
    }

    /**
     * Return all set members
     *
     * @return QueryResultInterface Set members
     */
    public function getSetMembers(): QueryResultInterface
    {
        return GeneralUtility::makeInstance(SubjectRepository::class)->findByParentSet($this);
    }
}
