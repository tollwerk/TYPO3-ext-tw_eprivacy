<?php

declare(strict_types=1);

namespace Tollwerk\TwEprivacy\EventListener;

use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Frontend\Event\BeforePageCacheIdentifierIsHashedEvent;

/**
 * Add user cookie consent to cache identifiers because they have direct influence
 * on what contents are getting rendered or not, based on the consent given by the user.
 */
#[AsEventListener(
    identifier: 'tw-eprivacy/before-page-cache-identifier-is-hashed-listener'
)]
final readonly class BeforePageCacheIdentifierIsHashedListener
{
    /**
     * Invoke
     *
     * @param BeforePageCacheIdentifierIsHashedEvent $event PSR-14 event
     *
     * @return void
     */
    public function __invoke(BeforePageCacheIdentifierIsHashedEvent $event): void
    {
        // If eprivacy_consent cookie is set, take all cookies with consent into account for page cache identifier.
        if (!empty($_COOKIE['eprivacy_consent'])) {
            // Get given consent and sort it by cookie names/identifiers.
            // The sorting is necessary so prevent multiple different cache versions of the same page,
            // just because users gave consent to the exactly same cookies, but in different order.
            $eprivacyConsent =  (array) json_decode($_COOKIE['eprivacy_consent'], true)['consent'];
            natsort($eprivacyConsent);

            // Add consent to page cache identifiers.
            $pageCacheIdentifierParameters = $event->getPageCacheIdentifierParameters();
            $pageCacheIdentifierParameters['tx_tweprivacy_cookies'] = $eprivacyConsent;
            $event->setPageCacheIdentifierParameters($pageCacheIdentifierParameters);
        }
    }
}
