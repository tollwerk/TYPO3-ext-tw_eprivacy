<?php

namespace Tollwerk\TwEprivacy\Utilities;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class CookieUtility
{
    /**
     * Delete a cookie
     *
     * @param string $name     Name
     * @param string $path     Path
     * @param string $domain   Domain
     * @param bool   $secure   Secure
     * @param bool   $httpOnly HttpOnly
     *
     * @return void
     */
    public static function deleteCookie(
        string $name,
        string $path = '',
        string $domain = '',
        bool   $secure = true,
        bool   $httpOnly = true
    )
    {
        setcookie($name, '', 1, $path, $domain, $secure, $httpOnly);
    }
}
