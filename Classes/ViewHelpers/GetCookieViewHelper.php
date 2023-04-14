<?php

namespace Tollwerk\TwEprivacy\ViewHelpers;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * GetCookieViewHelper
 *
 * Get a cookie by its name
 *
 * @package    Tollwerk\TwEprivacy
 * @subpackage Tollwerk\TwEprivacy\ViewHelpers
 */
class GetCookieViewHelper extends AbstractViewHelper
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
        $this->registerArgument('name', 'string', 'The cookie name', true);
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
        if(isset($_COOKIE[$arguments['name']])){
            return json_decode($_COOKIE[$arguments['name']], true);
        }

        return null;
    }
}
