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

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\ParameterType;
use Tollwerk\TwEprivacy\Domain\Model\Subject;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Database\Query\Restriction\FrontendRestrictionContainer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

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

        $query->matching($query->logicalAnd(...$constraints));

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

        return $query->matching($query->in('identifier', $subjectIdentifiers))->execute();
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
        $query->matching($query->equals('parentSet', $parentSet->getUid()))
              ->setOrderings(['sorting' => QueryInterface::ORDER_ASCENDING]);

        return $query->execute();
    }

    /**
     * Find public subjects
     *
     * @param bool $public Public
     *
     * @return QueryResultInterface Public subjects
     */
    public function findByPublic(bool $public): QueryResultInterface
    {
        $query = $this->createQuery();

        return $query->matching($query->equals('public', $public))->execute();
    }

    /**
     * Returns a query for objects of this repository
     *
     * @return QueryInterface
     */
    public function createQuery()
    {
        $query = parent::createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        return $query;
    }

    public function getIdentifierByUid(int $uid): ?string
    {
        $queryBuilder = GeneralUtility::makeInstance(
            ConnectionPool::class
        )->getQueryBuilderForTable('tx_tweprivacy_domain_model_subject');
        $title = $queryBuilder
            ->select('identifier')
            ->from('tx_tweprivacy_domain_model_subject')
            ->where(
                $queryBuilder->expr()->eq(
                    'uid',
                    $queryBuilder->createNamedParameter($uid, ParameterType::INTEGER)
                )
            )
            ->executeQuery()
            ->fetchOne();
        if ($title === false) {
            return null;
        }

        return $title;
    }

    /**
     * Return the identifiers of all subjects that are consented to by default
     *
     * Doesn't use Extbase persistence because it's also called by the OutgoingMiddleware for fully cached pages,
     * where TYPO3 doesn't calculate the full TypoScript setup that Extbase's query factory depends on.
     *
     * @param bool $public Return only public ones
     *
     * @return string[] Default subject identifiers
     */
    public function findDefaultSubjectIdentifiers(bool $public = true): array
    {
        $queryBuilder = $this->getFrontendQueryBuilder();
        $constraints  = [
            // Name, identifier, type and public are excluded from translation, so default language records suffice
            $queryBuilder->expr()->in(
                'subject.sys_language_uid',
                $queryBuilder->createNamedParameter([0, -1], ArrayParameterType::INTEGER)
            ),
            $queryBuilder->expr()->eq(
                'type.needs_consent',
                $queryBuilder->createNamedParameter(0, ParameterType::INTEGER)
            ),
        ];

        // If only public subjects should be returned
        if ($public) {
            $constraints[] = $queryBuilder->expr()->eq(
                'subject.public',
                $queryBuilder->createNamedParameter(1, ParameterType::INTEGER)
            );
        }

        return $queryBuilder
            ->select('subject.identifier')
            ->from('tx_tweprivacy_domain_model_subject', 'subject')
            ->innerJoin(
                'subject',
                'tx_tweprivacy_domain_model_type',
                'type',
                $queryBuilder->expr()->eq('type.uid', $queryBuilder->quoteIdentifier('subject.type'))
            )
            ->where(...$constraints)
            ->orderBy('subject.sorting')
            ->executeQuery()
            ->fetchFirstColumn();
    }

    /**
     * Return the names of the subjects with the given identifiers
     *
     * Doesn't use Extbase persistence for the same reason as findDefaultSubjectIdentifiers().
     *
     * @param array $subjectIdentifiers Subject identifiers
     *
     * @return string[] Subject names
     */
    public function findNamesBySubjectIdentifiers(array $subjectIdentifiers): array
    {
        if (!count($subjectIdentifiers)) {
            return [];
        }

        $queryBuilder = $this->getFrontendQueryBuilder();

        return $queryBuilder
            ->select('name')
            ->from('tx_tweprivacy_domain_model_subject')
            ->where(
                $queryBuilder->expr()->in(
                    'sys_language_uid',
                    $queryBuilder->createNamedParameter([0, -1], ArrayParameterType::INTEGER)
                ),
                $queryBuilder->expr()->in(
                    'identifier',
                    $queryBuilder->createNamedParameter(array_values($subjectIdentifiers), ArrayParameterType::STRING)
                )
            )
            ->executeQuery()
            ->fetchFirstColumn();
    }

    /**
     * Return a query builder for the subject table with the same visibility restrictions
     * (hidden, deleted, workspaces etc.) that Extbase applies in the frontend
     *
     * @return QueryBuilder Query builder
     */
    protected function getFrontendQueryBuilder(): QueryBuilder
    {
        $queryBuilder = GeneralUtility::makeInstance(
            ConnectionPool::class
        )->getQueryBuilderForTable('tx_tweprivacy_domain_model_subject');
        $queryBuilder->setRestrictions(GeneralUtility::makeInstance(FrontendRestrictionContainer::class));

        return $queryBuilder;
    }
}
