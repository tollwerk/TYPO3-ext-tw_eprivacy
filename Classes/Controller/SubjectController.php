<?php
namespace Tollwerk\TwEprivacy\Controller;


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
/**
 * SubjectController
 */
class SubjectController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * subjectRepository
     * 
     * @var \Tollwerk\TwEprivacy\Domain\Repository\SubjectRepository
     * @inject
     */
    protected $subjectRepository = null;

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
