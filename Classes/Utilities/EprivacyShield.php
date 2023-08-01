<?php

/**
 * data
 *
 * @subpackage ${NAMESPACE}
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

namespace Tollwerk\TwEprivacy\Utilities;

use Exception;
use Tollwerk\TwEprivacy\Domain\Model\Subject;
use Tollwerk\TwEprivacy\Domain\Repository\ConsentRepository;
use Tollwerk\TwEprivacy\Domain\Repository\SubjectRepository;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * ePrivacy Shield
 *
 * @package    Tollwerk\TwEprivacy
 * @subpackage Tollwerk\TwEprivacy\Utilities
 */
class EprivacyShield implements SingletonInterface
{
    /**
     * Subject name cache
     *
     * @var array
     */
    protected static $subjectNames = null;
    /**
     * Consent repository
     *
     * @var ConsentRepository
     */
    protected $consentRepository;
    /**
     * Subject repository
     *
     * @var SubjectRepository
     */
    protected $subjectRepository;

    /**
     * Reset the cached subject names
     */
    public static function reset(): void
    {
        self::$subjectNames = null;
    }

    /**
     * Return whether a particular subject identifier is allowed
     *
     * @param string $subjectIdentifier Subject identifier
     *
     * @return bool Subject identifier is allowed
     * @throws Exception
     */
    public function isAllowedIdentifier(string $subjectIdentifier): bool
    {
        $consent = $this->getConsentRepository()->get();

        return $consent->allowsSubject($subjectIdentifier);
    }

    /**
     * Return whether a particular subject name is allowed
     *
     * @param string $subjectName Subject name
     *
     * @return bool Subject identifier is allowed
     * @throws Exception
     */
    public function isAllowedName(string $subjectName): bool
    {
        if (self::$subjectNames === null) {
            self::$subjectNames = [];
            $consent            = $this->getConsentRepository()->get();
            $subjectIdentifiers = $consent->getSubjects();
            if (count($subjectIdentifiers)) {
                /** @var Subject $subject */
                foreach ($this->getSubjectRepository()->findBySubjectIdentifiers($subjectIdentifiers) as $subject) {
                    self::$subjectNames[] = $subject->getName();
                }
            }
        }

        return in_array($subjectName, self::$subjectNames);
    }

    /**
     * Instantiate and return the consent repository
     *
     * @return ConsentRepository Consent Repository
     *
     * @throws Exception
     */
    protected function getConsentRepository(): ConsentRepository
    {
        if ($this->consentRepository === null) {
            $this->consentRepository = GeneralUtility::makeInstance(ConsentRepository::class);
        }

        return $this->consentRepository;
    }

    /**
     * Instantiate and return the subject repository
     *
     * @return SubjectRepository Subject Repository
     *
     * @throws Exception
     */
    protected function getSubjectRepository(): SubjectRepository
    {
        if ($this->subjectRepository === null) {
            $this->subjectRepository = GeneralUtility::makeInstance(SubjectRepository::class);
        }

        return $this->subjectRepository;
    }
}
