<?php

/**
 * ePrivacy
 *
 * @category   Tollwerk
 * @package    Tollwerk\TwEprivacy
 * @subpackage Tollwerk\TwEprivacy\Hooks\ContentObject
 * @author     Klaus Fiedler <klaus@tollwerk.de>
 * @copyright  Copyright © 2024 Joschi Kuphal <joschi@tollwerk.de> / @jkphl
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

/***********************************************************************************
 *  The MIT License (MIT)
 *
 *  Copyright © 2024 Joschi Kuphal <joschi@tollwerk.de> / @jkph
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


namespace Tollwerk\TwEprivacy\Hooks\Frontend;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

/**
 * CreateHashBaseHook
 *
 * @package    Tollwerk\TwEprivacy
 * @subpackage Tollwerk\TwEprivacy\Hooks\ContentObject
 */
class CreateHashBaseHook
{
    /**
     * Modify parameters used to create the page cache identifier
     *
     * @param array                        $_params Parameters for cache identifier
     * @param TypoScriptFrontendController $tsfe    TypoScriptFrontendController
     *
     * @return void
     */
    public function modifyParams(array &$_params, TypoScriptFrontendController $tsfe): void
    {
        // If eprivacy_consent cookie is set, take all cookies with consent into account for page cache identifier.
        if (!empty($_COOKIE['eprivacy_consent'])) {
            $eprivacyConsent =  (array) json_decode($_COOKIE['eprivacy_consent'], 'true')['consent'];
            natsort($eprivacyConsent);
            $_params['hashParameters']['tx_tweprivacy_cookies'] = $eprivacyConsent;
        }
    }
}
