<?php

/**
 * tollwerk ePrivacy Consent Manager
 *
 * @category   Tollwerk
 * @package    Tollwerk\TwEprivacy
 * @author     Joschi Kuphal <joschi@tollwerk.de> / @jkphl
 * @copyright  Copyright © 2020 Joschi Kuphal <joschi@tollwerk.de> / @jkphl
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU GPLv2
 */

/***************************************************************
 * Copyright © 2020 Joschi Kuphal <joschi@tollwerk.de>
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301, USA.
 ***************************************************************/

namespace Tollwerk\TwEprivacy\Controller;

use DateTime;
use Tollwerk\TwEprivacy\Domain\Model\Consent;
use Tollwerk\TwEprivacy\Domain\Model\Subject;
use Tollwerk\TwEprivacy\Domain\Model\Type;
use Tollwerk\TwEprivacy\Domain\Repository\ConsentRepository;
use Tollwerk\TwEprivacy\Domain\Repository\SubjectRepository;
use TYPO3\CMS\Extbase\Configuration\Exception\InvalidConfigurationTypeException;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;
use TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException;
use TYPO3\CMS\Extbase\Object\Exception;

/**
 * Subject Controller
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
    public function injectSubjectRepository(SubjectRepository $subjectRepository)
    {
        $this->subjectRepository = $subjectRepository;
    }

    /**
     * Inject the consent repository
     *
     * @param ConsentRepository $consentRepository Consent repository
     */
    public function injectConsentRepository(ConsentRepository $consentRepository)
    {
        $this->consentRepository = $consentRepository;
    }

    /**
     * List action
     *
     * @param int $update     Update consent state
     * @param array $subjects Subjects with consent
     *
     * @throws Exception
     * @throws InvalidConfigurationTypeException
     */
    public function listAction($update = 0, array $subjects = [])
    {
        $consent = $this->updateConsent($update, $subjects, 'list');

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
        uksort($subjectsByType, function($a, $b) use ($types) {
            return ($types[$a]->getSorting() > $types[$b]->getSorting()) ? 1 : -1;
        });

        $this->view->assignMultiple([
            'subjects' => $subjectsByType,
            'types'    => $types,
            'consent'  => $consent,
            'now'      => new DateTime()
        ]);
    }

    /**
     * Update consent
     *
     * @param int $update            Update consent state
     * @param array $subjects        Subjects with consent
     * @param string $redirectAction Redirect to action
     *
     * @return Consent|null
     * @throws Exception
     * @throws InvalidConfigurationTypeException
     * @throws StopActionException
     * @throws UnsupportedRequestTypeException
     */
    protected function updateConsent($update, array $subjects = [], $redirectAction = null)
    {
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
                    );
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

            // Redirect
            if ($redirectAction) {
                $this->redirect($redirectAction);
            }
        }

        return $consent;
    }

    /**
     * Show a cookie banner
     */
    public function bannerAction()
    {
        // Skip if cookie consent is already given
        if (!empty($_COOKIE[ConsentRepository::COOKIE_NAME])) {
            return '';
        }
    }

    /**
     * Update the privacy settings from the cookie banner
     *
     * @param int $update Update mode
     *
     * @throws StopActionException
     */
    public function updateAction($update = 0)
    {
        $this->updateConsent($update, [], 'banner');
    }
}
