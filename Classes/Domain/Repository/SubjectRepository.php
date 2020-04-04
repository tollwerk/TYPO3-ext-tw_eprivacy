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

namespace Tollwerk\TwEprivacy\Domain\Repository;

use Tollwerk\TwEprivacy\Domain\Model\Subject;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * The repository for Subjects
 */
class SubjectRepository extends Repository
{
    /**
     * Default ordering
     *
     * @var array
     */
    protected $defaultOrderings = [
        'sorting' => QueryInterface::ORDER_ASCENDING
    ];

    /**
     * Make queries storage PID independent
     */
    public function initializeObject()
    {
        $currentLanguageUid = $this->objectManager->get(Context::class)->getPropertyFromAspect('language', 'id');
        $querySettings      = $this->objectManager->get(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);
        $querySettings->setLanguageUid($currentLanguageUid);
        $this->setDefaultQuerySettings($querySettings);
    }

    /**
     * Return all subjects that are consented to by default
     *
     * @param bool $public Return only public ones
     *
     * @return QueryResultInterface Default subjects
     */
    public function findDefaultSubjects(bool $public = true): QueryResultInterface
    {
        $query       = $this->createQuery();
        $constraints = [$query->equals('type.needsConsent', 0)];

        // If only public subjects should be returned
        if ($public) {
            $constraints[] = $query->equals('public', true);
        }

        $query->matching($query->logicalAnd($constraints));

        return $query->execute();
    }

    /**
     * Find subjects by identifier
     *
     * @param array $subjectIdentifiers Subject identifiers
     *
     * @return QueryResultInterface Subjects
     * @throws InvalidQueryException
     */
    public function findBySubjectIdentifiers(array $subjectIdentifiers): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching($query->in('identifier', $subjectIdentifiers));

        return $query->execute();
    }

    /**
     * Protected find public top level subjects
     *
     * @return QueryResultInterface Public top level subjects
     */
    public function findByPublicTopLevel(): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching(
            $query->logicalAnd(
                $query->equals('public', true),
                $query->equals('parentSet', 0)
            )
        );

        return $query->execute();
    }

    /**
     * Find subjects by parent set
     *
     * @param Subject $parentSet Parent set
     *
     * @return QueryResultInterface Subjects
     */
    public function findByParentSet(Subject $parentSet): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching($query->equals('parentSet', $parentSet))
              ->setOrderings(['sorting' => QueryInterface::ORDER_ASCENDING]);

        return $query->execute();
    }
}
