<?php

/**
 * data
 *
 * @category   Tollwerk
 * @package    Tollwerk\TwEprivacy
 * @subpackage Tollwerk\TwEprivacy\Domain\Repository
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

namespace Tollwerk\TwEprivacy\Domain\Repository;

use DateTimeImmutable;
use Tollwerk\TwEprivacy\Domain\Model\Consent;
use Tollwerk\TwEprivacy\Domain\Model\Subject;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException;
use TYPO3\CMS\Extbase\Object\Exception;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Consent Repository
 *
 * @package    Tollwerk\TwEprivacy
 * @subpackage Tollwerk\TwEprivacy\Domain\Repository
 */
class ConsentRepository implements SingletonInterface
{
    /**
     * Consent Cookie Name
     *
     * @var string
     */
    const COOKIE_NAME = 'eprivacy_consent';
    /**
     * Current consent state
     *
     * @var Consent
     */
    protected static $consent = null;

    /**
     * Return the current users consent state
     *
     * @return Consent Consent
     * @throws \Exception
     */
    public function get(): Consent
    {
        if (self::$consent === null) {
            self::$consent = new Consent();
            if (!empty($_COOKIE[self::COOKIE_NAME])) {
                $value = (array)@json_decode($_COOKIE[self::COOKIE_NAME]);
                if (is_object($value) && !empty($value['consent']) && is_array($value['consent'])) {
                    self::$consent->setSubjects($value['consent']);
                    self::$consent->setLastmod(new DateTimeImmutable($value['lastmod'] ?? 'now'));
                }
            }

            // If there are no subjects enabled by cookie: Register the default subjects
            if (!count(self::$consent->getSubjects())) {
                $objectManager     = GeneralUtility::makeInstance(ObjectManager::class);
                $subjectRepository = $objectManager->get(SubjectRepository::class);
                self::$consent->setSubjects(
                    array_map(
                        function(Subject $subject) {
                            return $subject->getIdentifier();
                        },
                        $subjectRepository->findDefaultSubjects()->toArray()
                    )
                );
            }
        }

        return self::$consent;
    }

    /**
     * Set the current users consent state
     *
     * @param Consent $consent Constent
     *
     * @throws InvalidConfigurationTypeException
     * @throws Exception
     */
    public function update(Consent $consent): void
    {
        $objectManager        = GeneralUtility::makeInstance(ObjectManager::class);
        $configurationManager = $objectManager->get(ConfigurationManager::class);
        $settings             = $configurationManager->getConfiguration(
            ConfigurationManager::CONFIGURATION_TYPE_FULL_TYPOSCRIPT,
            'TwEprivacy'
        );
        $cookieSettings       = $settings['plugin.']['tx_tweprivacy_eprivacy.']['settings.'] ?? [];
        $lifetime             = intval($cookieSettings['lifetime'] ?? 2629800);

        // Set the consent cookie
        setcookie(
            self::COOKIE_NAME,
            strval($consent),
            time() + $lifetime,
            trim($cookieSettings['path'] ?? '/'),
            trim($cookieSettings['domain'] ?? ''),
            boolval($cookieSettings['secure'] ?? true),
            boolval($cookieSettings['httponly'] ?? true)
        );
    }
}
