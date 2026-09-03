<?php

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

namespace Tollwerk\TwEprivacy\Controller;

use Psr\Http\Message\ResponseInterface;
use Tollwerk\TwEprivacy\Domain\Model\Consent;
use Tollwerk\TwEprivacy\Domain\Model\Subject;
use Tollwerk\TwEprivacy\Domain\Model\Type;
use Tollwerk\TwEprivacy\Domain\Repository\ConsentRepository;
use Tollwerk\TwEprivacy\Domain\Repository\SubjectRepository;
use Tollwerk\TwEprivacy\Utilities\ConsentUtility;
use Tollwerk\TwEprivacy\Utilities\EprivacyShield;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use \Exception;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;


/**
 * SubjectController
 */
class SubjectController extends ActionController
{
    // Update actions
    const UPDATE_UPDATE = 1;
    const UPDATE_ACCEPT = 2;
    const UPDATE_DENY = 3;

    /**
     * Subject Repository
     *
     * @var SubjectRepository
     */
    protected $subjectRepository = null;
    /**
     * Consent repository
     *
     * @var ConsentRepository
     */
    protected $consentRepository = null;

    /**
     * Consent utility
     *
     * @var ConsentUtility
     */
    protected $consentUtility = null;

    /**
     * Inject the subject repository
     *
     * @param SubjectRepository $subjectRepository Subject repository
     */
    public function injectSubjectRepository(SubjectRepository $subjectRepository): void
    {
        $this->subjectRepository = $subjectRepository;
    }

    /**
     * Inject the consent repository
     *
     * @param ConsentRepository $consentRepository Consent repository
     */
    public function injectConsentRepository(ConsentRepository $consentRepository): void
    {
        $this->consentRepository = $consentRepository;
    }

    /**
     * Inject the consent utility
     *
     * @param ConsentUtility $consentUtility Consent utility
     */
    public function injectConsentUtility(ConsentUtility $consentUtility): void
    {
        $this->consentUtility = $consentUtility;
    }

    /**
     * AddConsentAction
     *
     * @param int|null $pid         PID
     * @param array $addIdentifiers Add identifiers
     *
     * @return ResponseInterface
     */
    public function addConsentAction(?int $pid = null, array $addIdentifiers = []): ResponseInterface {
        // Get all subjects
        $allSubjects = array_map(
            function(Subject $subject) {
                return $subject->getIdentifier();
            },
            $this->subjectRepository->findByPublic(true)->toArray()
        );

        // Remove all subjects currently not allowed by user
        $eprivacyShield = GeneralUtility::makeInstance(EprivacyShield::class);
        for($i = 0, $length = count($allSubjects); $i < $length; $i++) {
            if(!$eprivacyShield->isAllowedIdentifier($allSubjects[$i])) {
                unset($allSubjects[$i]);
            }
        }

        // Now add the new identifiers the users wants to allow.
        $subjects = array_merge($allSubjects, $addIdentifiers);
        $consent = $this->consentRepository->get();
        $consent->setSubjects($subjects);
        $this->consentRepository->update($consent);

        // Remove all subjects currently not allowed by user
        return $this->redirectToUri($this->uriBuilder->setTargetPageUid($pid)->build());
    }

    /**
     * Clear cookies by given subject UIDs
     *
     * @param string $subjectUidString The subject UIDs as comma separated string.
     *                                 The UIDs are a single string to better pass them on when redirecting.
     *
     * @return ResponseInterface
     */
    public function clearAction(string $subjectUidString = ''): ResponseInterface
    {
        // If no UIDs given, just redirect to list action.
        if (empty($subjectUidString)) {
            return $this->redirect('list', 'Subject', 'TwEprivacy');
        }

        // Get Eprivacy TypoScript settings and derived values.
        $configurationManager = GeneralUtility::makeInstance(ConfigurationManager::class);
        $settings = $configurationManager->getConfiguration(
            ConfigurationManager::CONFIGURATION_TYPE_FULL_TYPOSCRIPT,
            'TwEprivacy'
        );
        $cookieSettings = $settings['plugin.']['tx_tweprivacy_eprivacy.']['settings.'] ?? [];
        $lifetime             = intval($cookieSettings['lifetime'] ?? 2629800);
        $secure               = GeneralUtility::getIndpEnv('TYPO3_SSL');

        // Delete the first 10 of all given subjects/cookies,
        // then redirect to clearAction with the remaining subject UIDs.
        // Continue until no cookies remain.
        $subjectRepository = GeneralUtility::makeInstance(SubjectRepository::class);
        $subjectUids = GeneralUtility::trimExplode(',', $subjectUidString);
        for($i = 0; $i < 10; $i++) {
            $subjectUid = array_shift($subjectUids);
            $subjectTitle = $subjectRepository->getTitleByUid($subjectUid);
            setcookie(
                $subjectTitle,
                '',
                1,
                trim($cookieSettings['path'] ?? '/'),
                trim($cookieSettings['domain'] ?? ''),
                $secure && boolval($cookieSettings['secure'] ?? true),
                boolval($cookieSettings['httponly'] ?? true)
            );
            if (array_key_exists($subjectTitle, $_COOKIE)) {
                unset($_COOKIE);
            }
        }
        return $this->redirect('clear', 'Subject', 'TwEprivacy', ['subjectUids' => implode(',', $subjectUids)]);
    }

    /**
     * List action
     *
     * @param int   $update   Update consent state
     * @param array $subjects Subjects with consent
     *
     * @throws Exception
     */
    public function listAction(int $update = 0, array $subjects = []): ResponseInterface
    {
        $consent = $this->consentRepository->get();

        // Process updates
        if ($update) {
            // If UPDATE_DENY, start special treatment for deleting all cookies.
            if ($update == self::UPDATE_DENY) {
                // Update the consent information, but without modifying the cookies.
                // We have to do that by redirecting to clearAction because of possible HTTP Header buffer overflows
                // when there is a high number of cookies involved.
                $this->consentUtility->update($update, $subjects, $consent, false);

                // Get all registered cookies and redirect them to clearAction.
                $allSubjects = array_map(
                    function(Subject $subject) {
                        return $subject->getUid();
                    },
                    GeneralUtility::makeInstance(SubjectRepository::class)->findByPublic(true)->toArray()
                );
                $allSubjectUids = implode(',', $allSubjects);
                return $this->redirect('clear', 'Subject', 'TwEprivacy', ['subjectUids' => $allSubjectUids]);
            }

            // If not UPDATE_DENY, start normal treatment.
            $this->consentUtility->update($update, $subjects, $consent);
        }

        // Get everything for showing the cookie consent manager.
        $types          = [];
        $subjectsByType = [];

        /** @var Subject $subject */
        foreach ($this->subjectRepository->findByPublicTopLevel() as $subject) {
            $type   = $subject->getType();
            $typeId = $type->getUid();
            if (empty($types[$typeId])) {
                $types[$typeId]          = $type;
                $subjectsByType[$typeId] = [];
            }
            $subjectsByType[$typeId][] = $subject;
        }

        // Sort the type list
        uasort($types, function(Type $a, Type $b) {
            return ($a->getSorting() > $b->getSorting()) ? 1 : -1;
        });

        // Sort the subjects by type list
        uksort($subjectsByType, function(int $a, int $b) use ($types) {
            return ($types[$a]->getSorting() > $types[$b]->getSorting()) ? 1 : -1;
        });

        $this->view->assignMultiple([
            'subjects' => $subjectsByType,
            'types'    => $types,
            'consent'  => $consent,
            'now'      => new \DateTime()
        ]);

        return $this->htmlResponse();
    }

    /**
     * Dialog action
     *
     * @param int|null    $update      Update consent state
     * @param string|null $redirectUrl URL for redirecting after dialog form submit
     *
     * @return ResponseInterface
     *
     * @throws Exception
     */
    public function dialogAction(?int $update = null, ?string $redirectUrl = null): ResponseInterface {
        // Do nothing if update value is not valid.
        if ($update !== self::UPDATE_ACCEPT && $update !== self::UPDATE_DENY) {
            $this->view->assign('redirectUrl', GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'));
            return $this->htmlResponse();
        }

        // Update the consent.
        $this->consentUtility->update($update);

        // Perform a redirect so that updated cookies take effect.
        if ($redirectUrl) {
            header('Location: ' . $redirectUrl);
            die();
        }
        return $this->redirect('dialog', 'Subject', 'TwEprivacy');
    }
}
