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

namespace Tollwerk\TwEprivacy\Domain\Repository;

use Tollwerk\TwEprivacy\Domain\Model\Subject;
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
     * @param bool $public Return only public ones
     *
     * @return QueryResultInterface Default subjects
     */
    public function findDefaultSubjects($public = true)
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
    public function findBySubjectIdentifiers(array $subjectIdentifiers)
    {
        $query = $this->createQuery();

        return $query->matching($query->in('identifier', $subjectIdentifiers))->execute();
    }

    /**
     * Protected find public top level subjects
     *
     * @return QueryResultInterface Public top level subjects
     */
    public function findByPublicTopLevel()
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
    public function findByParentSet(Subject $parentSet)
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
    public function findByPublic($public)
    {
        $query = $this->createQuery();

        return $query->matching($query->equals('public', boolval($public)))->execute();
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
}
