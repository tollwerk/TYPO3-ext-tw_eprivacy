<?php

/**
 * tollwerk ePrivacy Consent Manager
 *
 * @category   Tollwerk
 * @package    Tollwerk\TwEprivacy
 * @author     Joschi Kuphal <joschi@tollwerk.de> / @jkphl
 * @copyright  Copyright © 2020 Joschi Kuphal <joschi@tollwerk.de> / @jkphl
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU GPLv2
 */

/***************************************************************
 * Copyright © 2020 Joschi Kuphal <joschi@tollwerk.de>
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301, USA.
 ***************************************************************/

namespace Tollwerk\TwEprivacy\Domain\Model;

use Tollwerk\TwEprivacy\Domain\Repository\SubjectRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

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
    public function getLifetime()
    {
        return $this->lifetime;
    }

    /**
     * Set the lifetime in seconds
     *
     * @param int $lifetime Lifetime
     */
    public function setLifetime(int $lifetime)
    {
        $this->lifetime = $lifetime;
    }

    /**
     * Return whether the subject is public
     *
     * @return bool Is public
     */
    public function isPublic()
    {
        return $this->public;
    }

    /**
     * Set whether the subject is public
     *
     * @param bool $public Is public
     */
    public function setPublic(bool $public)
    {
        $this->public = $public;
    }

    /**
     * Return whether the subject expires with the session
     *
     * @return bool Expires with the session
     */
    public function isSession()
    {
        return $this->session;
    }

    /**
     * Set whether the subject expires with the session
     *
     * @param bool $session Expires with the session
     */
    public function setSession(bool $session)
    {
        $this->session = $session;
    }

    /**
     * Return the mode
     *
     * @return int Mode
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Set the mode
     *
     * @param int $mode Mode
     */
    public function setMode(int $mode)
    {
        $this->mode = $mode;
    }

    /**
     * Return the parent set
     *
     * @return Subject Parent set
     */
    public function getParentSet()
    {
        return $this->parentSet;
    }

    /**
     * Set the parent set
     *
     * @param Subject $parentSet Parent set
     */
    public function setParentSet(Subject $parentSet)
    {
        $this->parentSet = $parentSet;
    }

    /**
     * Return all set members
     *
     * @return QueryResultInterface Set members
     */
    public function getSetMembers()
    {
        return GeneralUtility::makeInstance(SubjectRepository::class)->findByParentSet($this);
    }
}
