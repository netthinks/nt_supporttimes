<?php

declare(strict_types=1);

namespace Netthinks\NtSupporttimes\Backend\EventListener;

use Netthinks\NtSupporttimes\Service\ReleaseService;
use TYPO3\CMS\Backend\Backend\Event\SystemInformationToolbarCollectorEvent;
use TYPO3\CMS\Backend\Toolbar\InformationStatus;
use TYPO3\CMS\Core\Attribute\AsEventListener;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Localization\LanguageServiceFactory;

/**
 * Reichert die System-Informations-Anzeige (bei der TYPO3-Versionsanzeige) um eine
 * "Update verfügbar"-Meldung an, sobald für den installierten Major-Zweig eine neuere
 * Patch-Version veröffentlicht wurde.
 */
#[AsEventListener('nt-supporttimes/backend/update-notification')]
final readonly class UpdateNotificationListener
{
    private const LL = 'LLL:EXT:nt_supporttimes/Resources/Private/Language/locallang.xlf:';

    public function __construct(
        private ReleaseService $releaseService,
        private Typo3Version $typo3Version,
        private LanguageServiceFactory $languageServiceFactory,
    ) {}

    public function __invoke(SystemInformationToolbarCollectorEvent $event): void
    {
        $installedVersion = $this->typo3Version->getVersion();
        $currentMajor = $this->typo3Version->getMajorVersion();

        $latestVersion = $this->getLatestPatchForMajor($currentMajor);
        if ($latestVersion === null) {
            return;
        }

        if (version_compare($installedVersion, $latestVersion, '>=')) {
            return;
        }

        $lang = $this->getLanguageService();
        $releaseUrl = 'https://get.typo3.org/release/' . rawurlencode($latestVersion);

        $text = sprintf(
            '<strong>%s</strong>: <a href="%s" target="_blank" rel="noreferrer">%s</a> (%s: %s)',
            htmlspecialchars($lang->sL(self::LL . 'systeminformation.updateAvailable')),
            htmlspecialchars($releaseUrl),
            htmlspecialchars($latestVersion),
            htmlspecialchars($lang->sL(self::LL . 'systeminformation.installed')),
            htmlspecialchars($installedVersion),
        );

        $event->getToolbarItem()->addSystemMessage(
            $text,
            InformationStatus::WARNING,
            1,
        );
    }

    private function getLatestPatchForMajor(int $major): ?string
    {
        $releaseData = $this->releaseService->getReleaseData([$major]);

        foreach ($releaseData as $item) {
            if ((int)($item['version'] ?? 0) === $major) {
                $latest = (string)($item['latest'] ?? '');
                return $latest !== '' ? $latest : null;
            }
        }

        return null;
    }

    private function getLanguageService(): LanguageService
    {
        if (isset($GLOBALS['LANG']) && $GLOBALS['LANG'] instanceof LanguageService) {
            return $GLOBALS['LANG'];
        }

        return $this->languageServiceFactory->createFromUserPreferences($GLOBALS['BE_USER'] ?? null);
    }
}
