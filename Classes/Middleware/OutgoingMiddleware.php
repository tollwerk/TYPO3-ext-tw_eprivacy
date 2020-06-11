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
use Tollwerk\TwEprivacy\Utilities\EprivacyShield;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

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
        $response      = $handler->handle($request);
        $cookieHeaders = array_filter(headers_list(), function(string $header) {
            return !strncasecmp('Set-Cookie:', $header, 11);
        });

        // If there are cookie headers
        if (count($cookieHeaders)) {
            $objectManager  = GeneralUtility::makeInstance(ObjectManager::class);
            $ePrivacyShield = $objectManager->get(EprivacyShield::class);

            // Parse and run through all cookies
            foreach (array_map([$this, 'parseCookieHeader'], $cookieHeaders) as $cookieParams) {
                $cookieName = key($cookieParams);
                if (!$ePrivacyShield->isAllowedName($cookieName)) {
                    setcookie(
                        $cookieName,
                        is_string($cookieParams[$cookieName]) ? $cookieParams[$cookieName] : '',
                        1,
                        $cookieParams['path'] ?? '',
                        $cookieParams['domain'] ?? '',
                        GeneralUtility::getIndpEnv('TYPO3_SSL') && ($cookieParams['secure'] ?? true),
                        $cookieParams['httponly'] ?? true
                    );
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
            list($partName, $partValue) = GeneralUtility::trimExplode('=', $part, true, 2);
            $cookieParams[$partName] = $partValue ?? true;
        }

        return $cookieParams;
    }
}
