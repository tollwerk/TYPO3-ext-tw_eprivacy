<?php

/**
 * data
 *
 * @category   Tollwerk
 * @package    Tollwerk\TwEprivacy
 * @subpackage Tollwerk\TwEprivacy\Domain\Model
 * @author     Joschi Kuphal <joschi@tollwerk.de> / @jkphl
 * @copyright  Copyright © 2020 Joschi Kuphal <joschi@tollwerk.de> / @jkphl
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

/***********************************************************************************
 *  The MIT License (MIT)
 *
 *  Copyright © 2020 Joschi Kuphal <joschi@tollwerk.de>
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy of
 *  this software and associated documentation files (the "Software"), to deal in
 *  the Software without restriction, including without limitation the rights to
 *  use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 *  the Software, and to permit persons to whom the Software is furnished to do so,
 *  subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 *  FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 *  COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 *  IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 *  CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 ***********************************************************************************/

namespace Tollwerk\TwEprivacy\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Consent
 *
 * @package    Tollwerk\TwEprivacy
 * @subpackage Tollwerk\TwEprivacy\Domain\Model
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
    public function getSubjects(): array
    {
        return $this->subjects;
    }

    /**
     * Set the subject consent states
     *
     * @param bool[] $subjects Subject consent states
     */
    public function setSubjects(array $subjects): void
    {
        $this->subjects = $subjects;
    }

    /**
     * Return the last modification date and time
     *
     * @return \DateTimeInterface Last modification date and time
     */
    public function getLastmod(): \DateTimeInterface
    {
        return $this->lastmod;
    }

    /**
     * Set the last modification date and time
     *
     * @param \DateTimeInterface $lastmod Last modification date and time
     */
    public function setLastmod(\DateTimeInterface $lastmod): void
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
    public function allowsSubject(string $subject): bool
    {
        return strlen($subject) && in_array($subject, $this->subjects);
    }
}
