<?php

/**
 * TcaUtility
 *
 * @subpackage ${NAMESPACE}
 * @author     2022 Klaus Fiedler <klaus@tollwerk.de>
 * @copyright  Copyright © 2022 2022 Klaus Fiedler <klaus@tollwerk.de>
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

/***********************************************************************************
 *  The MIT License (MIT)
 *
 *  Copyright © 2022 Klaus Fiedler <klaus@tollwerk.de>
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy of
 *  this software and associated documentation files (the "Software"), to deal in
 *  the Software without restriction, including without limitation the rights to
 *  use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 *  the Software, and to permit persons to whom the Software is furnished to do so,
 *  subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 *  FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 *  COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 *  IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 *  CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 ***********************************************************************************/

namespace Tollwerk\TwEprivacy\Utilities;

use Doctrine\DBAL\Driver\Exception;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use UnexpectedValueException;

/**
 * TcaUtility
 *
 * @package    Tollwerk\TwEprivacy
 * @subpackage Tollwerk\TwEprivacy\Utilities
 */
class TcaUtility
{
    /**
     * Returns a list of tx_tweprivacy_domain_model_subject items for populating field "tx_tweprivacy_consent"
     * in tt_content table (called as itemsProcFunc).
     *
     * @param array $configuration Current field configuration
     *
     * @throws UnexpectedValueException
     * @throws Exception
     * @internal
     */
    public function getConsentItems(array &$configuration)
    {
        // Get all tx_tweprivacy_domain_model_subject records
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
                                      ->getQueryBuilderForTable('tx_tweprivacy_domain_model_subject');
        $records      = $queryBuilder
            ->select('subject.uid', 'subject.title', 'subject.name', 'subject.identifier')
            ->from('tx_tweprivacy_domain_model_subject', 'subject')
            ->innerJoin(
                'subject',
                'tx_tweprivacy_domain_model_type',
                'type',
                $queryBuilder->expr()->eq(
                    'type.uid',
                    $queryBuilder->quoteIdentifier('subject.type')
                )

            )
            ->where($queryBuilder->expr()->in('subject.sys_language_uid', [-1, 0]))
            ->andWhere($queryBuilder->expr()->eq('type.needs_consent', 1))
            ->groupBy('subject.uid')
            ->execute()
            ->fetchAllAssociative();

        // Set items
        foreach ($records as $record) {
            $configuration['items'][] = [
                sprintf('%s [%s]',
                    $record['title'],
                    $record['name'],
                ),
                $record['name']
            ];
        }
    }
}
