<?php

namespace Tollwerk\TwEprivacy\ViewHelpers\MissingConsent;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Create "t3://" typolink to the page with the tw_eprivacy plugin
 *
 * @package    Tollwerk\TwEprivacy
 * @subpackage Tollwerk\TwEprivacy\ViewHelpers
 */
class SettingsT3LinkViewHelper extends AbstractViewHelper
{
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

        return 't3://page?uid=' . $settings['pluginPid'];
    }
}
