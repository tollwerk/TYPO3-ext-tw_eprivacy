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

use Tollwerk\TwEprivacy\Domain\Model\Subject;
use Tollwerk\TwEprivacy\Domain\Model\Type;
use Tollwerk\TwEprivacy\Domain\Repository\ConsentRepository;
use Tollwerk\TwEprivacy\Domain\Repository\SubjectRepository;
use Tollwerk\TwEprivacy\ExpressionLanguage\ConsentConditionProvider;
use Tollwerk\TwEprivacy\Utilities\ConsentUtility;
use Tollwerk\TwEprivacy\Utilities\EprivacyShield;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Object\Exception;
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
     * @param int|null $pid
     * @param array    $addIdentifiers
     *
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function addConsentAction(int $pid = null, array $addIdentifiers = []) {

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
        $this->redirectToUri($this->uriBuilder->setTargetPageUid($pid)->build());
    }

    /**
     * List action
     *
     * @param int $update     Update consent state
     * @param array $subjecst Subjects with consent
     *
     * @throws Exception
     * @throws InvalidConfigurationTypeException
     */
    public function listAction(int $update = 0, array $subjects = [])
    {
        $consent = $this->consentRepository->get();

        // Process updates
        if ($update) {
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
    }

    /**
     * Dialog action
     *
     * @param int|null $update Update consent state
     *
     * @return void
     *
     * @throws Exception
     * @throws InvalidConfigurationTypeException
     */
    public function dialogAction(int $update = null) {
        if ($update !== self::UPDATE_ACCEPT && $update !== self::UPDATE_DENY) {
            return;
        }

        $this->consentUtility->update($update);
    }
}
