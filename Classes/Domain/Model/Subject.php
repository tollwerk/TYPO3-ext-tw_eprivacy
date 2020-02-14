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
     * Type
     *
     * @var \Tollwerk\TwEprivacy\Domain\Model\Type
     */
    protected $type = null;

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
}
