<?php

/***************************************************************
 * Extension Manager/Repository config file for ext: "tw_eprivacy"
 *
 *
 * Manual updates:
 * Only the data in the array - anything else is removed by next write.
 * "version" and "dependencies" must not be touched!
 *
 * @category  Tollwerk
 * @package   Tollwerk
 * @author    jolanta <jolanta@tollwerk.de>
 * @copyright 2023 jolanta <jolanta@tollwerk.de>
 * @license   MIT https://opensource.org/licenses/MIT
 * @link      https://tollwerk.de
 */

$EM_CONF['tw_eprivacy'] = [
    'title'            => 'tollwerk ePrivacy Consent Manager',
    'description'      => 'ePrivacy Consent Manager',
    'category'         => 'plugin',
    'author'           => 'Jolanta Dworczyk',
    'author_email'     => 'joschi@tollwerk.de',
    'state'            => 'alpha',
    'uploadfolder'     => 0,
    'createDirs'       => '',
    'clearCacheOnLoad' => 0,
    'version'          => '1.0.0',
    'constraints'      => [
        'depends'   => [
            'typo3'   => '10.0.0-11.5.99',
        ],
        'conflicts' => [],
        'suggests'  => [],
    ],
];
