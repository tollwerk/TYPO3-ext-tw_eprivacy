<?php

/**
 * ePrivacy
 *
 * @category   Tollwerk
 * @package    Tollwerk\TwEprivacy
 * @subpackage Tollwerk\TwEprivacy\ViewHelpers
 * @author     Klaus Fiedler <klaus@tollwerk.de>
 * @copyright  Copyright © 2020 Joschi Kuphal <joschi@tollwerk.de> / @jkphl
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

/***********************************************************************************
 *  The MIT License (MIT)
 *
 * Copyright © 2020 Joschi Kuphal <joschi@tollwerk.de> / @jkphl
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

namespace Tollwerk\TwEprivacy\ViewHelpers;

use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * FindSubjectsByNameViewHelper
 *
 * Find subjects by their name and return them with their identifier as key.
 *
 * @package    Tollwerk\TwEprivacy
 * @subpackage Tollwerk\TwEprivacy\ViewHelpers
 */
class FindSubjectsByNameViewHelper extends AbstractViewHelper
{
    /**
     * Initialize all arguments. You need to override this method and call
     * $this->registerArgument(...) inside this method, to register all your arguments.
     *
     * @return void
     * @api
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('names', 'array', 'Subject names', false, []);
    }

    /**
     * Default implementation of static rendering; useful API method if your ViewHelper
     * when compiled is able to render itself statically to increase performance. This
     * default implementation will simply delegate to the ViewHelperInvoker.
     *
     * @param array                     $arguments
     * @param \Closure                  $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     *
     * @return mixed
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {

        // Get current frontend language for query
        $context = GeneralUtility::makeInstance(Context::class);
        $sysLanguageUid = $context->getPropertyFromAspect('language', 'id');

        // Prepare $subjects for query
        $subjects = $arguments['names'];
        array_walk($subjects, function(&$value){ $value = '"' . $value . '"';});

        // Create query
        $query = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_tweprivacy_domain_model_subject');
        $subjects = $query->select('uid', 'name', 'title', 'identifier', 'name', 'purpose', 'purpose_short as purposeShort', 'is_third_party_cookie as isThirdPartyCookie', 'third_party_host as thirdPartyHost')
                              ->from('tx_tweprivacy_domain_model_subject')
                              ->where($query->expr()->eq('sys_language_uid', $sysLanguageUid))
                              ->andWhere($query->expr()->in('name', $subjects))
                              ->execute()
                              ->fetchAllAssociative();

        $subjectsByIdentifier = [
            'byIdentifier' => [],
            'identifiers' => [],
        ];
        foreach($subjects as $subject) {
            $subjectsByIdentifier['identifiers'][] = $subject['identifier'];
            $subjectsByIdentifier['byIdentifier'][$subject['identifier']] = $subject;
        }

        return $subjectsByIdentifier;
    }
}
