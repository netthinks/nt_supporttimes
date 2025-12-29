<?php

declare(strict_types=1);

namespace Netthinks\NtSupporttimes\Dashboard\Widgets;

use Netthinks\NtSupporttimes\Service\ReleaseService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Dashboard\Widgets\WidgetConfigurationInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetInterface;
use TYPO3Fluid\Fluid\View\TemplateView;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContext;

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
        $templatePathAndFilename = GeneralUtility::getFileAbsFileName(
            'EXT:nt_supporttimes/Resources/Private/Templates/Dashboard/SupportTimes.html'
        );

        $renderingContext = new RenderingContext();
        $renderingContext->getTemplatePaths()->setTemplatePathAndFilename($templatePathAndFilename);
        
        $view = new TemplateView($renderingContext);
        $view->assignMultiple([
            'releaseData' => $this->releaseService->getReleaseData(),
            'options' => $this->options,
            'configuration' => $this->configuration,
        ]);

        return $view->render();
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
