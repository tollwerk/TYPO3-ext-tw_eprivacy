<?php

/**
 * ePrivacy
 *
 * @category   Tollwerk
 * @package    Tollwerk\TwEprivacy
 * @subpackage Tollwerk\TwEprivacy\Hooks\ContentObject
 * @author     Klaus Fiedler <klaus@tollwerk.de>
 * @copyright  Copyright © 2023 Joschi Kuphal <joschi@tollwerk.de> / @jkphl
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

/***********************************************************************************
 *  The MIT License (MIT)
 *
 *  Copyright © 2023 Joschi Kuphal <joschi@tollwerk.de> / @jkph
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

use Tollwerk\TwEprivacy\Controller\SubjectController;
use Tollwerk\TwEprivacy\Utilities\EprivacyShield;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectPostInitHookInterface;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectStdWrapHookInterface;
use Exception;

/**
 * PostInitHook
 *
 * Hook for $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['postInit']
 * for manipulating the rendering of content elements.
 *
 * @package    Tollwerk\TwEprivacy
 * @subpackage Tollwerk\TwEprivacy\Hooks\ContentObject
 */
class PostInitHook implements ContentObjectPostInitHookInterface
{
    /**
     * Check if a content element can be rendered
     * based on the necessary cookies set in field "tx_tweprivacy_consent".
     *
     * @param ContentObjectRenderer $parentObject         ParentObject
     * @param array                 $consentItems Required cookie consent names
     *
     * @return bool
     *
     * @throws Exception
     */
    protected function canRender(ContentObjectRenderer $parentObject, $consentItems = []): bool
    {
        // Get required cookie consent names from record.
        // If there are none, the content element can be rendered.
        if (!count($consentItems)) {
            return true;
        }

        // If there is required cookie consent, check if consent was given.
        // If any of required consent is missing, the content element can not be rendered.
        /** @var EprivacyShield $eprivacyShield */
        $eprivacyShield = GeneralUtility::makeInstance(EprivacyShield::class);
        $canRender = true;
        foreach ($consentItems as $consentItem) {
            if (!$eprivacyShield->isAllowedName($consentItem)) {
                $canRender = false;
            }
        }

        // Content can be rendered.
        return $canRender;
    }

    /**
     * Manipulate content elements after initialization
     *
     * @param ContentObjectRenderer $parentObject The content object renderer
     *
     * @return void
     *
     * @throws Exception
     */
    public function postProcessContentObjectInitialization(ContentObjectRenderer &$parentObject)
    {
        return;

        // Check if content elements can be rendered according to field tx_tweprivacy_consent.
        if ($parentObject->getCurrentTable() == 'tt_content' && !empty($parentObject->data['tx_tweprivacy_consent'])) {

            // Get required consent items.
            $consentItems = array_filter(
                GeneralUtility::trimExplode(
                    ',',
                    $parentObject->data['tx_tweprivacy_consent']
                )
            );


            // If content element can not be rendered, show a corresponding message instead.
            if (!$this->canRender($parentObject, $consentItems)) {
                // Get TypoScript settings for tw_eprivacy.
                $typoscript = GeneralUtility::makeInstance(
                    ConfigurationManager::class
                )->getConfiguration(
                    ConfigurationManager::CONFIGURATION_TYPE_FULL_TYPOSCRIPT,
                    'TwEprivacy'
                );
                $eprivacySettings = $typoscript['plugin.']['tx_tweprivacy_eprivacy.']['settings.'];

                // Change rendering of content element to show 'consent missing' message instead.
                $parentObject->data['CType'] = 'text';
                $parentObject->data['header_layout'] = 100;
                $templatePath = GeneralUtility::getFileAbsFileName('EXT:tw_eprivacy/Resources/Private/Templates/Content/MissingConsent.html');
                $view = GeneralUtility::makeInstance(StandaloneView::class);
                $view->setTemplatePathAndFilename($templatePath);
                $view->assignMultiple([
                    'consentItems' => $consentItems,
                    'data'         => $parentObject->data,
                    'settings'     => $eprivacySettings,
                    'pid'          => $GLOBALS['TSFE']->id,
                ]);
                $parentObject->data['bodytext'] = $view->render();
            }
        }
    }
}
