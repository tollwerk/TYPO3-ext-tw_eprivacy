<?php

namespace Tollwerk\TwEprivacy\ViewHelpers\MissingConsent;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Create "t3://" typolink for accepting missing cookies
 *
 * @package    Tollwerk\TwEprivacy
 * @subpackage Tollwerk\TwEprivacy\ViewHelpers
 */
class AcceptT3LinkViewHelper extends AbstractViewHelper
{
    /**
     * Initialize all arguments. You need to override this method and call
     * $this->registerArgument(...) inside this method, to register all your arguments.
     *
     * @return void
     * @api
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('identifiers', 'array', 'Cookie identifiers', true);
        $this->registerArgument('contentUid', 'int', 'UID of the tt_content element', true);
    }

    /**
     * Default implementation of static rendering; useful API method if your ViewHelper
     * when compiled is able to render itself statically to increase performance. This
     * default implementation will simply delegate to the ViewHelperInvoker.
     *
     * @param array                     $arguments
     * @param \Closure                  $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     *
     * @return string
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): string {
        // Get TypoScript settings for tw_eprivacy.
        $typoscript = GeneralUtility::makeInstance(
            ConfigurationManager::class
        )->getConfiguration(
            ConfigurationManager::CONFIGURATION_TYPE_FULL_TYPOSCRIPT,
            'TwEprivacy'
        );
        $settings = $typoscript['plugin.']['tx_tweprivacy_eprivacy.']['settings.'];

        // Create array with all link parts.
        $t3linkParts = [
            't3://page?uid=' . $settings['pluginPid'] . '?',
            'tx_tweprivacy_eprivacy[action]=addConsent',
            'tx_tweprivacy_eprivacy[controller]=Subject',
            'tx_tweprivacy_eprivacy[pluginName]=Eprivacy',
            'tx_tweprivacy_eprivacy[pid]=' . $GLOBALS['TSFE']->id,
        ];
        foreach($arguments['identifiers'] as $identifier) {
            $t3linkParts[] = 'tx_tweprivacy_eprivacy[addIdentifiers][]=' . $identifier;
        }
        $t3linkParts[] = '#' . $arguments['contentUid'];

        // Return concatenated t3link string.
        return implode('&', $t3linkParts);
    }
}
