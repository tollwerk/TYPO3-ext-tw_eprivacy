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
     * @param int          $update   See SubjectController::UPDATE_ACCEPT etc.
     * @param array        $subjects Subjects with consent
     * @param Consent|null $consent  Consent. Will be retrieved from ConsentRepository if not given.
     *
     * @return Consent
     *
     * @throws \TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException
     * @throws \TYPO3\CMS\Extbase\Object\Exception
     */
    public function update(int $update = SubjectController::UPDATE_UPDATE, array $subjects = [], Consent $consent = null): Consent {
        $consent = $consent ?: $this->consentRepository->get();
        $defaultSubjectIdentifiers = array_map(
            function(Subject $subject) {
                return $subject->getIdentifier();
            },
            $this->subjectRepository->findDefaultSubjects()->toArray()
        );

        // Process updates
        switch ($update) {
            case SubjectController::UPDATE_ACCEPT:
                $subjects = array_map(
                    function(Subject $subject) {
                        return $subject->getIdentifier();
                    },
                    $this->subjectRepository->findByPublic(true)->toArray()
                );
                break;
            case SubjectController::UPDATE_DENY:
                $subjects = $defaultSubjectIdentifiers;
                break;
            case SubjectController::UPDATE_UPDATE:
                $subjects                  = array_unique(array_merge($subjects, $defaultSubjectIdentifiers));
                break;
        }

        // Update the consent
        $consent->setSubjects($subjects);
        $this->consentRepository->update($consent);

        return $consent;
    }
}
