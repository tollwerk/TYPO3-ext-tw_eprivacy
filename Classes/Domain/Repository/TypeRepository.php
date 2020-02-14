<?php
namespace Tollwerk\TwEprivacy\Domain\Repository;


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
 * The repository for Types
 */
class TypeRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * @var array
     */
    protected $defaultOrderings = [
    'sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING
];
}
