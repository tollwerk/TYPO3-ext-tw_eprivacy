<?php

/**
 * ePrivacy
 *
 * @category   Tollwerk
 * @package    Tollwerk\TwEprivacy
 * @subpackage Tollwerk\TwEprivacy\Hooks\ContentObject
 * @author     Klaus Fiedler <klaus@tollwerk.de>
 * @copyright  Copyright © 2020 Joschi Kuphal <joschi@tollwerk.de> / @jkphl
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

/***********************************************************************************
 *  The MIT License (MIT)
 *
 *  Copyright © 2022 Joschi Kuphal <joschi@tollwerk.de> / @jkph
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

namespace Tollwerk\TwEprivacy\Hooks\ContentObject;

use Tollwerk\TwEprivacy\Utilities\EprivacyShield;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectStdWrapHookInterface;
use Exception;

/**
 * StdWrapHook
 *
 * @package    Tollwerk\TwEprivacy
 * @subpackage Tollwerk\TwEprivacy\Hooks\ContentObject
 */
class StdWrapHook implements ContentObjectStdWrapHookInterface
{
    /**
     * Hook for modifying $content before core's stdWrap does anything
     *
     * @param string                $content       Input value undergoing processing in this function. Possibly
     *                                             substituted by other values fetched from another source.
     * @param array                 $configuration TypoScript stdWrap properties
     * @param ContentObjectRenderer $parentObject  Parent content object
     *
     * @return string|null Further processed $content
     * @throws Exception
     */
    public function stdWrapPreProcess($content, array $configuration, ContentObjectRenderer &$parentObject): ?string
    {
        return $content;
    }

    /**
     * Hook for modifying $content after core's stdWrap has processed setContentToCurrent, setCurrent, lang, data,
     * field, current, cObject, numRows, filelist and/or preUserFunc
     *
     * @param string                $content       Input value undergoing processing in this function. Possibly
     *                                             substituted by other values fetched from another source.
     * @param array                 $configuration TypoScript stdWrap properties
     * @param ContentObjectRenderer $parentObject  Parent content object
     *
     * @return string|null Further processed $content
     */
    public function stdWrapOverride($content, array $configuration, ContentObjectRenderer &$parentObject): ?string
    {
        return $content;
    }

    /**
     * Hook for modifying $content after core's stdWrap has processed override, preIfEmptyListNum, ifEmpty, ifBlank,
     * listNum, trim and/or more (nested) stdWraps
     *
     * @param string                $content       Input value undergoing processing in this function. Possibly
     *                                             substituted by other values fetched from another source.
     * @param array                 $configuration TypoScript "stdWrap properties".
     * @param ContentObjectRenderer $parentObject  Parent content object
     *
     * @return string|null Further processed $content
     */
    public function stdWrapProcess($content, array $configuration, ContentObjectRenderer &$parentObject): ?string
    {
        return $content;
    }

    /**
     * Hook for modifying $content after core's stdWrap has processed anything but debug
     *
     * @param string                $content       Input value undergoing processing in this function. Possibly
     *                                             substituted by other values fetched from another source.
     * @param array                 $configuration TypoScript stdWrap properties
     * @param ContentObjectRenderer $parentObject  Parent content object
     *
     * @return string|null Further processed $content
     */
    public function stdWrapPostProcess($content, array $configuration, ContentObjectRenderer &$parentObject): ?string
    {
        // Check if cookie consent for one or more cookies is required. Do not render element if consent is missing.
        if ($parentObject->getCurrentTable() == 'tt_content' && !empty($parentObject->data['tx_tweprivacy_consent'])) {
            // Get required consent names from record
            $consentItems = array_filter(
                GeneralUtility::trimExplode(
                    ',',
                    $parentObject->data['tx_tweprivacy_consent']
                )
            );
            if (!count($consentItems)) {
                return $content;
            }

            // Check if all items are allowed. Return empty string on the first missing consent.
            $eprivacyShield = GeneralUtility::makeInstance(EprivacyShield::class);
            foreach ($consentItems as $consentItem) {
                if (!$eprivacyShield->isAllowedName($consentItem)) {

                    $configurationManager = GeneralUtility::makeInstance(ConfigurationManager::class);
                    $typoscript           = $configurationManager->getConfiguration(ConfigurationManager::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
                    $settings             = $typoscript['plugin.']['tx_tweprivacy_eprivacy.']['settings.'];

                    /** @var StandaloneView $standaloneView */
                    $standaloneView = GeneralUtility::makeInstance(StandaloneView::class);
                    $standaloneView->setLayoutRootPaths([GeneralUtility::getFileAbsFileName('EXT:tw_eprivacy/Resources/Private/Layouts')]);
                    $standaloneView->setPartialRootPaths([GeneralUtility::getFileAbsFileName('EXT:tw_eprivacy/Resources/Private/Partials')]);
                    $standaloneView->setTemplateRootPaths([GeneralUtility::getFileAbsFileName('EXT:tw_eprivacy/Resources/Private/Templates')]);
                    $standaloneView->getRequest()
                                   ->setControllerExtensionName(GeneralUtility::underscoredToUpperCamelCase('tw_eprivacy'));
                    $standaloneView->setTemplate('Content/NeedsConsent');
                    $standaloneView->assign('consentItems', $consentItems);
                    $standaloneView->assignMultiple([
                        'consentItems' => $consentItems,
                        'data'         => $parentObject->data,
                        'settings'     => $settings,
                    ]);

                    return $standaloneView->render();
                }
            }
        }

        return $content;
    }
}
