<?php

declare(strict_types=1);

namespace Netthinks\NtSupporttimes\Dashboard\Widgets;

use Netthinks\NtSupporttimes\Service\ReleaseService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Dashboard\Widgets\WidgetConfigurationInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetInterface;
use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextFactory;

class SupportTimesWidget implements WidgetInterface
{
    public function __construct(
        private readonly WidgetConfigurationInterface $configuration,
        private readonly ReleaseService $releaseService,
        private readonly array $options = []
    ) {
    }

    public function renderWidgetContent(): string
    {
        $renderingContextFactory = GeneralUtility::makeInstance(RenderingContextFactory::class);
        $renderingContext = $renderingContextFactory->create();
        
        $templatePathAndFilename = GeneralUtility::getFileAbsFileName(
            'EXT:nt_supporttimes/Resources/Private/Templates/Dashboard/SupportTimes.html'
        );
        
        $renderingContext->getTemplatePaths()->setTemplatePathAndFilename($templatePathAndFilename);
        $renderingContext->getViewHelperResolver()->addNamespace('f', 'TYPO3\\CMS\\Fluid\\ViewHelpers');
        
        // Use add() instead of setAll()
        $variableProvider = $renderingContext->getVariableProvider();
        $variableProvider->add('releaseData', $this->releaseService->getReleaseData());
        $variableProvider->add('options', $this->options);
        $variableProvider->add('configuration', $this->configuration);

        $view = GeneralUtility::makeInstance(\TYPO3Fluid\Fluid\View\TemplateView::class, $renderingContext);
        
        return $view->render();
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
