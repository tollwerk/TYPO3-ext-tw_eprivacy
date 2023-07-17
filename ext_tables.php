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

/***********************************************************************************
 *  Copyright © 2023 Jolanta Dworczyk <jolanta@tollwerk.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***********************************************************************************/

defined('TYPO3') || die();

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

(function () {

    ExtensionManagementUtility::addLLrefForTCAdescr(
        'tx_tweprivacy_domain_model_type',
        'EXT:tw_eprivacy/Resources/Private/Language/locallang_csh_tx_tweprivacy_domain_model_type.xlf'
    );
    ExtensionManagementUtility::allowTableOnStandardPages('tx_tweprivacy_domain_model_type');

    ExtensionManagementUtility::addLLrefForTCAdescr(
        'tx_tweprivacy_domain_model_subject',
        'EXT:tw_eprivacy/Resources/Private/Language/locallang_csh_tx_tweprivacy_domain_model_subject.xlf'
    );
    ExtensionManagementUtility::allowTableOnStandardPages('tx_tweprivacy_domain_model_subject');
})();
