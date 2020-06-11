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
     * Sorting
     *
     * @var int
     */
    protected $sorting = 0;

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
    public function isNeedsConsent()
    {
        return $this->needsConsent;
    }

    /**
     * Sets whether this type needs user consent
     *
     * @param bool $needsConsent Needs user consent
     */
    public function setNeedsConsent(bool $needsConsent)
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

    /**
     * Return the sorting position
     *
     * @return int Sorting position
     */
    public function getSorting()
    {
        return $this->sorting;
    }

    /**
     * Set the sorting position
     *
     * @param int $sorting Sorting position
     */
    public function setSorting(int $sorting)
    {
        $this->sorting = $sorting;
    }
}
