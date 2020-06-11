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

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Consent
 *
 * @package Tollwerk\TwEprivacy
 */
class Consent extends AbstractEntity
{
    /**
     * Subject consent states
     *
     * @var bool[]
     */
    protected $subjects = [];
    /**
     * Last modification date and time
     *
     * @var \DateTimeInterface
     */
    protected $lastmod;

    /**
     * Consent constructor
     */
    public function __construct()
    {
        $this->lastmod = new \DateTimeImmutable('now');
    }

    /**
     * Return the subject consent states
     *
     * @return bool[] Subject consent states
     */
    public function getSubjects()
    {
        return $this->subjects;
    }

    /**
     * Set the subject consent states
     *
     * @param bool[] $subjects Subject consent states
     */
    public function setSubjects(array $subjects)
    {
        $this->subjects = $subjects;
    }

    /**
     * Return the last modification date and time
     *
     * @return \DateTimeInterface Last modification date and time
     */
    public function getLastmod()
    {
        return $this->lastmod;
    }

    /**
     * Set the last modification date and time
     *
     * @param \DateTimeInterface $lastmod Last modification date and time
     */
    public function setLastmod(\DateTimeInterface $lastmod)
    {
        $this->lastmod = $lastmod;
    }

    /**
     * String serialization
     *
     * @return string Serialized content
     */
    public function __toString()
    {
        $values = [
            'lastmod' => $this->lastmod->format('c'),
            'consent' => $this->subjects,
        ];

        return json_encode($values);
    }

    /**
     * Return whether a particular subject identifier is allowed
     *
     * @param string $subject Subject identifier
     *
     * @return bool Subject is allowed
     */
    public function allowsSubject($subject)
    {
        return strlen($subject) && in_array($subject, $this->subjects);
    }
}
