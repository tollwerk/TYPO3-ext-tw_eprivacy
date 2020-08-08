<?php

/**
 * ePrivacy
 *
 * @category   Tollwerk
 * @package    Tollwerk\TwEprivacy
 * @subpackage Tollwerk\TwEprivacy\ViewHelpers
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

use Tollwerk\TwEprivacy\Domain\Model\Subject;
use Tollwerk\TwEprivacy\Domain\Repository\SubjectRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * User function for checking for ePrivacy consent
 *
 * @package Tollwerk\TwEprivacy\ViewHelpers
 */
class ConsentUserFunc
{
    /**
     * Content object
     *
     * @var ContentObjectRenderer
     */
    public $cObj;

    /**
     *
     * @return bool
     * @var array $configuration
     *
     * @var string $content
     */
    public function hasConsent($content, array $configuration = null)
    {
        $needConsent = $this->cObj->getFieldVal('tx_tweprivacy_required_consent');
        $needConsent = GeneralUtility::trimExplode(',', $needConsent, true);
        if (empty($needConsent)) {
            return '';
        }

        $objectManager     = GeneralUtility::makeInstance(ObjectManager::class);
        $subjectRepository = $objectManager->get(SubjectRepository::class);
        $ePrivacyShield    = GeneralUtility::makeInstance(EprivacyShield::class);

        // Run through all subjects that need to be allowed
        /** @var Subject $subject */
        foreach ($subjectRepository->findByIdentifiers($needConsent) as $subject) {
            $GLOBALS['TSFE']->set_no_cache('Content depends on ePrivacy settings');
            if (!$ePrivacyShield->isAllowedIdentifier($subject->getIdentifier())) {
                return $this->getAlternativeText();
            }
        }

        return '';
    }

    /**
     * Create and return the alternative text
     *
     * @return string Alternative text
     */
    protected function getAlternativeText()
    {
        $alternativeText = trim($this->cObj->getFieldVal('tx_tweprivacy_alt_text'));
        if (!strlen($alternativeText)) {
            $alternativeText = LocalizationUtility::translate(
                'LLL:EXT:tw_eprivacy/Resources/Private/Language/locallang.xlf:eprivacy.alt',
                'TwEprivacy'
            );
        }
        $alternativeText = '<p class="Eprivacy__alternative">'.
                           $this->cObj->parseFunc($alternativeText, '', '< lib.parseFunc').
                           '</p>';

        return $alternativeText;
    }
}
