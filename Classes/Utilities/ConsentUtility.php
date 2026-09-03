<?php

namespace Tollwerk\TwEprivacy\Utilities;

use Tollwerk\TwEprivacy\Controller\SubjectController;
use Tollwerk\TwEprivacy\Domain\Model\Consent;
use Tollwerk\TwEprivacy\Domain\Model\Subject;
use Tollwerk\TwEprivacy\Domain\Repository\ConsentRepository;
use Tollwerk\TwEprivacy\Domain\Repository\SubjectRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class ConsentUtility
{
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
     * Update the consent
     *
     * @param int          $update               See SubjectController::UPDATE_ACCEPT etc.
     * @param array        $subjects             Subjects with consent
     * @param Consent|null $consent              Consent. Will be retrieved from ConsentRepository if not given.
     *
     * @return Consent
     *
     * TODO: Deprecated: Tollwerk\TwEprivacy\Utilities\ConsentUtility::update(): Implicitly marking parameter $consent as nullable is deprecated, the explicit nullable type must be used instead in /var/www/local_packages/tw-eprivacy/Classes/Utilities/ConsentUtility.php
     */
    public function update(int $update = SubjectController::UPDATE_UPDATE, array $subjects = [], ?Consent $consent = null): Consent {
        $consent = $consent ?: $this->consentRepository->get();
        $defaultSubjectIdentifiers = array_map(
            function(Subject $subject) {
                return $subject->getIdentifier();
            },
            $this->subjectRepository->findDefaultSubjects()->toArray()
        );

        // Process updates
        $allSubjects = $this->subjectRepository->findByPublic(true)->toArray();
        switch ($update) {
            case SubjectController::UPDATE_ACCEPT:
                $subjects = array_map(
                    function(Subject $subject) {
                        return $subject->getIdentifier();
                    },
                    $allSubjects
                );
                break;
            case SubjectController::UPDATE_DENY:
                $subjects = $defaultSubjectIdentifiers;
                break;
            case SubjectController::UPDATE_UPDATE:
                $subjects                  = array_unique(array_merge($subjects, $defaultSubjectIdentifiers));
                break;
        }

        // For each given subject that is a set, add it's children subjects.
        if (count($subjects)) {
            // Get parent sets.
            $parentSetsByIdentifier = [];
            foreach($allSubjects as $subject) {
                if ($subject->getMode() === Subject::MODE_SET) {
                    $parentSetsByIdentifier[$subject->getIdentifier()] = $subject;
                }
            }

            // Get and add children subjects.
            foreach($subjects as $subject) {
                if (array_key_exists($subject, $parentSetsByIdentifier)) {
                  foreach($this->subjectRepository->findByParentSet($parentSetsByIdentifier[$subject]) as $subjectFromSet) {
                      if (!array_key_exists($subjectFromSet->getIdentifier(), $subjects)) {
                          $subjects[] = $subjectFromSet->getIdentifier();
                      }
                  };
                }
            }
        }

        // Update the consent
        $consent->setSubjects($subjects);
        $this->consentRepository->update($consent);

        return $consent;
    }

    /**
     * Get subjects that dont have consent
     *
     * @param Consent $consent
     * @return array
     */
    public function getSubjectsWithoutConsent(Consent $consent): array
    {
        $subjectRepository =  GeneralUtility::makeInstance(SubjectRepository::class);
        $allSubjects = [];
        foreach($subjectRepository->findByPublic(true)->toArray() as $subject) {
            $allSubjects[$subject->getUid()] = $subject->getIdentifier();
        }
        natsort($allSubjects);
        return array_diff($allSubjects, $consent->getSubjects());
    }
}
