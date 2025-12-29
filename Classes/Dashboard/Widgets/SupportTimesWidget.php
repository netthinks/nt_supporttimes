<?php

declare(strict_types=1);

namespace Netthinks\NtSupporttimes\Dashboard\Widgets;

use Netthinks\NtSupporttimes\Service\ReleaseService;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Dashboard\Widgets\WidgetConfigurationInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetInterface;
use TYPO3\CMS\Fluid\View\StandaloneView;

class SupportTimesWidget implements WidgetInterface
{
    public function __construct(
        private readonly WidgetConfigurationInterface $configuration,
        private readonly ReleaseService $releaseService,
        private readonly StandaloneView $view,
        private readonly PageRenderer $pageRenderer,
        private readonly array $options = []
    ) {
    }

    public function renderWidgetContent(): string
    {
        $this->view->setTemplatePathAndFilename('EXT:nt_supporttimes/Resources/Private/Templates/Dashboard/SupportTimes.html');
        $this->view->assignMultiple([
            'releaseData' => $this->releaseService->getReleaseData(),
            'options' => $this->options,
            'configuration' => $this->configuration,
        ]);

        return $this->view->render();
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
