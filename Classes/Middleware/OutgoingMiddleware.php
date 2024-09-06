<?php

/**
 * ePrivacy
 *
 * @category   Tollwerk
 * @package    Tollwerk\TwEprivacy
 * @subpackage Tollwerk\TwEprivacy\Middleware
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

namespace Tollwerk\TwEprivacy\Middleware;

use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Tollwerk\TwEprivacy\Utilities\CookieUtility;
use Tollwerk\TwEprivacy\Utilities\EprivacyShield;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * ePrivacy Outgoing Middleware
 *
 * @package    Tollwerk\TwEprivacy
 * @subpackage Tollwerk\TwEprivacy\Middleware
 */
class OutgoingMiddleware implements MiddlewareInterface
{
    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Get extension configuration.
        $extensionConfiguration = GeneralUtility::makeInstance(
            ExtensionConfiguration::class
        )->get('tw_eprivacy');
        $extensionConfiguration['alwaysAllowedCookies'] = GeneralUtility::trimExplode(
            ',', $extensionConfiguration['alwaysAllowedCookies']
        );

        // Initialize EprivacyShield
        $ePrivacyShield = GeneralUtility::makeInstance(EprivacyShield::class);

        // Check if there are cookie headers inside the current request.
        $response      = $handler->handle($request);
        $cookieHeaders = array_filter(headers_list(), function(string $header) {
            return !strncasecmp('Set-Cookie:', $header, 11);
        });
        if (count($cookieHeaders)) {
            // Parse and run through all cookies.
            foreach (array_map([$this, 'parseCookieHeader'], $cookieHeaders) as $cookieParams) {
                $cookieName = key($cookieParams);

                // Skip cookies that are always allowed.
                if (in_array($cookieName, $extensionConfiguration['alwaysAllowedCookies'])) {
                    continue;
                }

                // Check and delete cookie if necessary.
                if (!$ePrivacyShield->isAllowedName($cookieName)) {
                    CookieUtility::deleteCookie(
                        $cookieName,
                        $cookieParams['path'] ?? '',
                        $cookieParams['domain'] ?? '',
                        GeneralUtility::getIndpEnv('TYPO3_SSL') && ($cookieParams['secure'] ?? true),
                        $cookieParams['httponly'] ?? true
                    );
                }
            }
        }

        // Check all other cookies because those could be set by JavaScript or any other means.
        if (count($_COOKIE)) {
            foreach($_COOKIE as $cookieName => $cookieValue) {
                // Skip cookies that are always allowed.
                if (in_array($cookieName, $extensionConfiguration['alwaysAllowedCookies'])) {
                    continue;
                }

                // Check and delete cookie if necessary.
                if (!$ePrivacyShield->isAllowedName($cookieName)) {
                    CookieUtility::deleteCookie($cookieName);
                }
            }
        }

        return $response;
    }

    /**
     * Parse a HTTP header string
     *
     * @param string $header HTTP header string
     *
     * @return array Header name / value pair
     * @throws InvalidArgumentException
     */
    protected function parseCookieHeader(string $header): array
    {
        list(, $value) = GeneralUtility::trimExplode(':', $header, true, 2);
        if (!strlen($value)) {
            throw new InvalidArgumentException('Invalid cookie header value', 1581703794);
        }

        $cookieParams = [];
        foreach (GeneralUtility::trimExplode(';', $value, true) as $part) {
            list($partName, $partValue) = array_pad(GeneralUtility::trimExplode('=', $part, true, 2), 2, true);
            $cookieParams[$partName] = $partValue ?? true;
        }

        return $cookieParams;
    }
}
