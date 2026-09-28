<?php

/**
 * Tables configuration
 *
 * @category Tollwerk
 * @package  Tollwerk\TwEprivacy
 * @author   tollwerk GmbH <info@tollwerk.de>
 * @license  http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link     https://tollwerk.de
 */

/***
 *
 * This file is part of the "Tollwerk E-Privacy" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020 Joschi Kuphal <joschi@tollwerk.de>, tollwerk GmbH
 *
 ***/

defined('TYPO3') or die();

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

call_user_func(function () {
    ExtensionManagementUtility::addStaticFile(
        'tw_eprivacy',
        'Configuration/TypoScript',
        'tollwerk ePrivacy Consent Manager'
    );
});
