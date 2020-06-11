<?php

/**
 * ePrivacy
 *
 * @category   Tollwerk
 * @package    Tollwerk\TwEprivacy
 * @subpackage Tollwerk\TwEprivacy\ViewHelpers
 * @author     Joschi Kuphal <joschi@tollwerk.de> / @jkphl
 * @copyright  Copyright © 2020 Joschi Kuphal <joschi@tollwerk.de> / @jkphl
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

/***********************************************************************************
 *  The MIT License (MIT)
 *
 *  Copyright © 2020 Joschi Kuphal <joschi@tollwerk.de>
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

namespace Tollwerk\TwEprivacy\ViewHelpers\Format;

use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Format a duration
 *
 * @package Tollwerk\TwEprivacy\ViewHelpers\Format
 */
class DurationViewHelper extends AbstractViewHelper
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
        $this->registerArgument('seconds', 'int', 'Duration in seconds');
    }

    /**
     * Format a duration
     *
     * @return string
     */
    public function render()
    {
        $seconds = intval($this->arguments['seconds']);

        if ($seconds <= 0) {
            return LocalizationUtility::translate('age.right_now', 'TwEprivacy');
        }

        // Return seconds
        if ($seconds < 60) {
            return LocalizationUtility::translate((($seconds > 1) ? 'age.seconds' : 'age.second').'.duration',
                'TwEprivacy',
                [$seconds]);
        }

        // Return minutes
        $minutes = floor($seconds / 60);
        if ($minutes < 60) {
            return LocalizationUtility::translate((($minutes > 1) ? 'age.minutes' : 'age.minute').'.duration',
                'TwEprivacy',
                [$minutes]);
        }

        // Return hours
        $hours = floor($minutes / 60);
        if ($hours < 24) {
            return LocalizationUtility::translate((($hours > 1) ? 'age.hours' : 'age.hour').'.duration', 'TwEprivacy',
                [$hours]);
        }

        // Return days
        $days = floor($hours / 24);
        if ($days < 7) {
            return LocalizationUtility::translate((($days > 1) ? 'age.days' : 'age.day').'.duration', 'TwEprivacy',
                [$days]);
        }

        // Return weeks
        $weeks = floor($days / 7);
        if ($weeks < 4) {
            return LocalizationUtility::translate((($weeks > 1) ? 'age.weeks' : 'age.week').'.duration', 'TwEprivacy',
                [$weeks]);
        }

        // Return months
        $months = floor($weeks / 4);
        if ($months < 12) {
            return LocalizationUtility::translate((($months > 1) ? 'age.months' : 'age.month').'.duration',
                'TwEprivacy',
                [$months]);
        }

        // Return years
        $years = floor($months / 12);

        return LocalizationUtility::translate((($years > 1) ? 'age.years' : 'age.year').'.duration', 'TwEprivacy',
            [$years]);
    }
}
