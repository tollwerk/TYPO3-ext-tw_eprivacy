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

use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
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
     * Return all subjects that are consented to by default
     *
     * @return QueryResultInterface Default subjects
     */
    public function findDefaultSubjects(): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching($query->equals('type.needsConsent', 0));

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
}
