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

namespace Tollwerk\TwEprivacy\Domain\Repository;

use DateTimeImmutable;
use Tollwerk\TwEprivacy\Domain\Model\Consent;
use Tollwerk\TwEprivacy\Domain\Model\Subject;
use Tollwerk\TwEprivacy\Utilities\EprivacyShield;
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
    public function get()
    {
        if (self::$consent === null) {
            self::$consent = new Consent();
            if (!empty($_COOKIE[self::COOKIE_NAME])) {
                $value = (array)@json_decode($_COOKIE[self::COOKIE_NAME]);
                if (!empty($value) && !empty($value['consent']) && is_array($value['consent'])) {
                    self::$consent->setSubjects($value['consent']);
                    self::$consent->setLastmod(new DateTimeImmutable(empty($value['lastmod']) ? 'now' : $value['lastmod']));
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
     * @param Consent $consent Consent
     *
     * @return bool Success
     *
     * @throws InvalidConfigurationTypeException
     * @throws Exception
     */
    public function update(Consent $consent)
    {
        // Reset the ePrivacy shield
        EprivacyShield::reset();

        self::$consent        = $consent;
        $objectManager        = GeneralUtility::makeInstance(ObjectManager::class);
        $configurationManager = $objectManager->get(ConfigurationManager::class);
        $settings             = $configurationManager->getConfiguration(
            ConfigurationManager::CONFIGURATION_TYPE_FULL_TYPOSCRIPT,
            'TwEprivacy'
        );
        $cookieSettings       = empty($settings['plugin.']['tx_tweprivacy_eprivacy.']['settings.']) ? [] : $settings['plugin.']['tx_tweprivacy_eprivacy.']['settings.'];
        $lifetime             = intval(empty($cookieSettings['lifetime']) ? 2629800 : $cookieSettings['lifetime']);
        $path                 = trim(empty($cookieSettings['path']) ? '/' : $cookieSettings['path']);
        $domain               = trim(empty($cookieSettings['domain']) ? '' : $cookieSettings['domain']);
        $secure               = GeneralUtility::getIndpEnv('TYPO3_SSL') &&
                                boolval(empty($cookieSettings['secure']) ? true : $cookieSettings['secure']);
        $secure               = $secure && GeneralUtility::getIndpEnv('TYPO3_SSL');
        $httpOnly             = boolval(empty($cookieSettings['httponly']) ? true : $cookieSettings['httponly']);

        // Set the consent cookie
        $consentSuccess = setcookie(
            self::COOKIE_NAME,
            strval($consent),
            time() + $lifetime,
            $path,
            $domain,
            $secure,
            $httpOnly
        );

        // If the consent could be updated: Kill all unmatched cookies
        if ($consentSuccess) {
            $allSubjects = array_map(
                function(Subject $subject) {
                    return $subject->getIdentifier();
                },
                $objectManager->get(SubjectRepository::class)->findByPublic(true)->toArray()
            );
            foreach (array_diff($allSubjects, $consent->getSubjects()) as $denySubject) {
                if (!setcookie(
                    $denySubject,
                    '',
                    1,
                    $path,
                    $domain,
                    $secure,
                    $httpOnly
                )) {
                    return false;
                }
            }
        }

        return $consentSuccess;
    }
}
