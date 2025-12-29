<?php
declare(strict_types=1);

namespace Netthinks\NtSupporttimes\Controller;

use Netthinks\NtSupporttimes\Service\ReleaseService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class SupportTimesController extends ActionController
{
    public function __construct(
        private readonly ReleaseService $releaseService
    ) {}

    public function roadmapAction(): \Psr\Http\Message\ResponseInterface
    {
        $selectedVersions = [];
        if (!empty($this->settings['selectedVersions'])) {
            $selectedVersions = GeneralUtility::intExplode(',', $this->settings['selectedVersions'], true);
        }

        $rawReleaseData = $this->releaseService->getReleaseData($selectedVersions);
        $chartData = $this->prepareChartData($rawReleaseData);

        $this->view->assign('chartData', json_encode($chartData));
        $this->view->assign('rawReleaseData', $rawReleaseData);
        
        return $this->htmlResponse();
    }

    private function prepareChartData(array $releases): array
    {
        $series = [];

        foreach ($releases as $release) {
            $version = 'TYPO3 v' . $release['version']; // "TYPO3 v14"
            $majorVersion = $release['version']; // 14
            $startDate = $release['support']['release_date'] ?? null;
            $maintainedUntil = $release['support']['official'] ?? null;
            $eltsUntil = $release['elts'] ?? null;
            $individualReleases = $release['releases'] ?? [];
            $ltsVersion = $release['lts'] ?? null; // e.g., 11.5, 12.4

            if (!$startDate || !$maintainedUntil) {
                continue;
            }

            // Determine the actual LTS start date
            // If an LTS version is specified (e.g., 11.5), use that release's date
            // Otherwise, use the release_date from metadata
            $ltsStartDate = $startDate;
            if ($ltsVersion !== null) {
                // The lts field contains the full version (e.g., 11.5, 12.4)
                // We need to find the .0 release of that version (e.g., "11.5.0", "12.4.0")
                $ltsReleaseKey = $ltsVersion . '.0';
                
                if (isset($individualReleases[$ltsReleaseKey]['date'])) {
                    $ltsStartDate = $individualReleases[$ltsReleaseKey]['date'];
                }
            }

            // Calculations
            $start = new \DateTimeImmutable($ltsStartDate);
            $endMaintained = new \DateTimeImmutable($maintainedUntil);
            
            // Regular Maintenance is ~1.5 years from LTS release
            $endRegular = $start->modify('+18 months');
            
            if ($endRegular > $endMaintained) {
                $endRegular = $endMaintained;
            }

            // --- 0. Sprint Releases ---
            // Filter and sort releases for this major version that occur before the LTS start date
            // Only count minor version releases (x.y.0), not patches (x.y.z where z > 0)
            // Note: The API only provides release dates, not development phase start dates
            // So we show the period from each sprint release to the next
            
            // Planned TYPO3 14 sprint releases (use actual data when available)
            $plannedReleases = [];
            if ($majorVersion === 14) {
                $plannedReleases = [
                    '14.0.0' => '2025-11-25T00:00:00+01:00',
                    '14.1.0' => '2026-01-20T00:00:00+01:00',
                    '14.2.0' => '2026-03-31T00:00:00+02:00',
                    '14.3.0' => '2026-04-21T00:00:00+02:00', // LTS
                ];
            }
            
            $sprintReleases = [];
            foreach ($individualReleases as $vStr => $rData) {
                // Check if it belongs to this major version
                if (str_starts_with((string)$vStr, (string)$majorVersion . '.')) {
                    if (isset($rData['date'])) {
                        $releaseDate = new \DateTimeImmutable($rData['date']);
                        // Sprint releases are those released BEFORE the official LTS start date
                        if ($releaseDate < $start) {
                            // Only include minor releases (x.y.0), not patches
                            $parts = explode('.', (string)$vStr);
                            if (isset($parts[2]) && (int)$parts[2] === 0) {
                                $sprintReleases[] = [
                                    'version' => $vStr,
                                    'date' => $releaseDate,
                                    'type' => 'sprint',
                                    'actual' => true
                                ];
                                // Remove from planned if it exists (actual data takes precedence)
                                unset($plannedReleases[$vStr]);
                            }
                        }
                    }
                }
            }
            
            // Add planned releases that haven't been released yet
            foreach ($plannedReleases as $vStr => $dateStr) {
                $releaseDate = new \DateTimeImmutable($dateStr);
                if ($releaseDate < $start) {
                    $sprintReleases[] = [
                        'version' => $vStr,
                        'date' => $releaseDate,
                        'type' => 'sprint',
                        'actual' => false
                    ];
                }
            }
            
            // Sort by date ascending
            usort($sprintReleases, fn($a, $b) => $a['date'] <=> $b['date']);

            // Create entries for sprint releases
            // Each sprint period runs from its release date to the next sprint release (or LTS)
            foreach ($sprintReleases as $index => $data) {
                $sprintStart = $data['date'];
                
                // Determine end of this sprint period
                if (isset($sprintReleases[$index + 1])) {
                    $sprintEnd = $sprintReleases[$index + 1]['date'];
                } else {
                    // Last sprint goes until the LTS release date
                    $sprintEnd = $start; 
                }

                // Add the sprint release block
                if ($sprintEnd > $sprintStart) {
                    // Alternate gray tones for visual distinction
                    $sprintColor = ($index % 2 === 0) ? '#e0e0e0' : '#d0d0d0';
                    
                    $series[] = [
                        'x' => $version,
                        'y' => [
                            $sprintStart->getTimestamp() * 1000,
                            $sprintEnd->getTimestamp() * 1000
                        ],
                        'fillColor' => $sprintColor,
                        'meta' => $this->getLabel('chart.phase.sprint') . ' ' . $data['version']
                    ];
                }
            }


            // --- 1. Regular Maintenance (Green) ---
            // Build the label with LTS version if available
            $regularMaintenanceLabel = $this->getLabel('chart.phase.regular');
            if ($ltsVersion !== null) {
                $regularMaintenanceLabel .= ' (' . $ltsVersion . ' LTS)';
            }
            
            $series[] = [
                'x' => $version,
                'y' => [
                    $start->getTimestamp() * 1000,
                    $endRegular->getTimestamp() * 1000
                ],
                'fillColor' => '#61a656', // TYPO3 Official Green
                'meta' => $regularMaintenanceLabel
            ];

            // --- 2. Priority/Security Support (Orange) ---
            if ($endRegular < $endMaintained) {
                $series[] = [
                    'x' => $version,
                    'y' => [
                        $endRegular->getTimestamp() * 1000,
                        $endMaintained->getTimestamp() * 1000
                    ],
                    'fillColor' => '#ff8700', // TYPO3 Official Orange
                    'meta' => $this->getLabel('chart.phase.priority')
                ];
            }

            // --- 3. ELTS (Pale Orange/Peach) ---
            $endElts = null;
            if ($eltsUntil) {
                $endElts = new \DateTimeImmutable($eltsUntil);
                if ($endElts > $endMaintained) {
                    $series[] = [
                        'x' => $version,
                        'y' => [
                            $endMaintained->getTimestamp() * 1000,
                            $endElts->getTimestamp() * 1000
                        ],
                        'fillColor' => '#ffbd6c', // Lighter Orange for ELTS
                        'meta' => $this->getLabel('chart.phase.elts')
                    ];
                }
            }

            // --- 4. Extended Partner Support (Beige) ---
            // 2 Years after ELTS or Official logic
            $baseForEps = $endElts ?? $endMaintained;
            if ($baseForEps) {
                $endEps = $baseForEps->modify('+2 years');
                $series[] = [
                    'x' => $version,
                    'y' => [
                        $baseForEps->getTimestamp() * 1000,
                        $endEps->getTimestamp() * 1000
                    ],
                    'fillColor' => '#ffe6a5', // Beige/Yellow for EPS
                    'meta' => $this->getLabel('chart.phase.extended')
                ];
            }
        }

        return [
            [
                'name' => 'Support Phases',
                'data' => $series
            ]
        ];
    }
    private function getLabel(string $key): string
    {
        return \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate(
            $key,
            'nt_supporttimes'
        ) ?? $key;
    }
}
