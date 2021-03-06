<?php
namespace VerteXVaaR\Logs\ViewHelpers\Format;

use Closure;
use DateTime;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use function strpos;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
class MicrotimeViewHelper extends AbstractViewHelper
{
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('microTime', 'float', 'Value returned by microtime(true)', true);
        $this->registerArgument('format', 'string', 'Resulting format', false, 'Y-m-d H:i:s.u');
    }

    public function render(): string
    {
        return static::renderStatic(
            [
                'microTime' => $this->arguments['microTime'],
                'format' => $this->arguments['format'],
            ],
            $this->buildRenderChildrenClosure(),
            $this->renderingContext
        );
    }

    public static function renderStatic(
        array $arguments,
        Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): string {
        $microTime = $arguments['microTime'];
        $format = $arguments['format'];

        if (false !== strpos($microTime, '.')) {
            $dateTime = DateTime::createFromFormat('U.u', $microTime);
        } elseif (false !== strpos(' ', $microTime)) {
            $dateTime = DateTime::createFromFormat('u U', $microTime);
        } else {
            $dateTime = DateTime::createFromFormat('U', (int)$microTime);
        }

        return $dateTime->format($format);
    }
}
