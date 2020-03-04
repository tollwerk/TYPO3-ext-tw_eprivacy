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
use TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Object\Exception;

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
//        print_r($_COOKIE);
        $consent = $this->consentRepository->get();

        // Process updates
        if ($update) {
            switch ($update) {
                case self::UPDATE_ACCEPT:
                    $subjects = array_map(
                        function(Subject $subject) {
                            return $subject->getIdentifier();
                        },
                        $this->subjectRepository->findByPublic(true)->toArray()
                    );;
                    break;
                case self::UPDATE_DENY:
                    $subjects = [];
                case self::UPDATE_UPDATE:
                    $defaultSubjectIdentifiers = array_map(
                        function(Subject $subject) {
                            return $subject->getIdentifier();
                        },
                        $this->subjectRepository->findDefaultSubjects()->toArray()
                    );
                    $subjects                  = array_unique(array_merge($subjects, $defaultSubjectIdentifiers));
                    break;
            }
            $consent->setSubjects($subjects);
            $this->consentRepository->update($consent);
        }

        $types          = [];
        $subjectsByType = [];

        /** @var Subject $subject */
        foreach ($this->subjectRepository->findByPublic(true) as $subject) {
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

//        setcookie('test', 'value', time() + 86400, '/');
    }
}
