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

use Tollwerk\TwEprivacy\Domain\Repository\SubjectRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * SubjectController
 */
class SubjectController extends ActionController
{

    /**
     * subjectRepository
     *
     * @var SubjectRepository
     */
    protected $subjectRepository = null;

    /**
     * Inject the subject repositorz
     *
     * @param SubjectRepository $subjectRepository
     */
    public function injectSubjectRepository(SubjectRepository $subjectRepository): void
    {
        $this->subjectRepository = $subjectRepository;
    }

    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {
        $subjects = $this->subjectRepository->findAll();
        $this->view->assign('subjects', $subjects);
    }
}
