<?php

declare(strict_types=1);

namespace Netthinks\NtSupporttimes\Service;

use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\FrontendInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ReleaseService
{
    private const API_URL_RELEASES = 'https://get.typo3.org/json';
    private const API_URL_METADATA = 'https://get.typo3.org/api/v1/major/';
    private const CACHE_IDENTIFIER = 'typo3_release_data_v2';

    private FrontendInterface $cache;

    public function __construct(
        private readonly RequestFactory $requestFactory,
        private readonly ExtensionConfiguration $extensionConfiguration,
        CacheManager $cacheManager
    ) {
        $this->cache = $cacheManager->getCache('nt_supporttimes_cache');
    }

    public function getReleaseData(array $allowedVersions = []): array
    {
        $cacheIdentifier = self::CACHE_IDENTIFIER . ($allowedVersions ? '_' . md5(json_encode($allowedVersions)) : '');

        $cacheEntry = $this->cache->get($cacheIdentifier);
        if ($cacheEntry !== false) {
            return $cacheEntry;
        }

        try {
            $metadata = $this->fetchJson(self::API_URL_METADATA);
            $releases = $this->fetchJson(self::API_URL_RELEASES);

            $processedData = $this->processData($metadata, $releases, $allowedVersions); // Pass allowedVersions

            $lifetime = (int)$this->getExtensionConfiguration('cacheLifetime', 86400);
            $this->cache->set($cacheIdentifier, $processedData, [], $lifetime);

            return $processedData;
        } catch (\Throwable $e) {
            return [];
        }
    }

    private function fetchJson(string $url): array
    {
        $response = $this->requestFactory->request($url, 'GET', ['verify' => false]);
        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException('Failed to fetch data from ' . $url);
        }
        return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
    }

    private function processData(array $metadata, array $releases, array $allowedVersions = []): array
    {
        if (empty($allowedVersions)) {
             $supportedVersionsConfig = (string)$this->getExtensionConfiguration('supportedVersions', '9,10,11,12,13,14');
             $supportedVersions = GeneralUtility::intExplode(',', $supportedVersionsConfig, true);
        } else {
             $supportedVersions = array_map('intval', $allowedVersions);
        }
        $showElts = (bool)$this->getExtensionConfiguration('showElts', 1);

        $result = [];

        // Metadata is a list of objects, key is numeric index
        foreach ($metadata as $meta) {
            $majorVersion = (int)$meta['version'];
            
            if (!in_array($majorVersion, $supportedVersions, true)) {
                continue;
            }

            // Find matching release info
            // API Keys in releases json are strings like "12"
            $releaseKey = (string)$majorVersion;
            $releaseInfo = $releases[$releaseKey] ?? [];

            $item = [
                'version' => $majorVersion,
                'latest' => $releaseInfo['latest'] ?? '',
                'latestDate' => $releaseInfo['releases'][$releaseInfo['latest'] ?? '']['date'] ?? null,
                'releaseUrl' => 'https://get.typo3.org/release/' . ($releaseInfo['latest'] ?? ''),
                'releases' => $releaseInfo['releases'] ?? [],
                'lts' => $meta['lts'] ?? null, // LTS minor version (e.g., 11.5, 12.4)
                'support' => [
                    'release_date' => $meta['release_date'] ?? null,
                    'official' => $meta['maintained_until'] ?? null,
                ],
                'elts' => $showElts ? ($meta['elts_until'] ?? null) : null,
                'status' => 'active',
                'statusLabel' => 'Active',
                'statusClass' => 'success',
            ];

            // Determine status
            $now = new \DateTimeImmutable();
            $officialEnd = isset($item['support']['official']) ? new \DateTimeImmutable($item['support']['official']) : null;
            $eltsEnd = isset($item['elts']) ? new \DateTimeImmutable($item['elts']) : null;

            if ($officialEnd && $now > $officialEnd) {
                if ($showElts && $eltsEnd && $now <= $eltsEnd) {
                    $item['status'] = 'elts';
                    $item['statusLabel'] = 'ELTS';
                    $item['statusClass'] = 'warning';
                } elseif ($officialEnd) {
                    $item['status'] = 'expired';
                    $item['statusLabel'] = 'Expired';
                    $item['statusClass'] = 'danger';
                }
            } elseif ($officialEnd) {
                 // Check "expiring soon" (< 6 months)
                 $interval = $now->diff($officialEnd);
                 // If not inverted (future) and years is 0 and months < 6
                 if ($interval->invert === 0 && $interval->y === 0 && $interval->m < 6) {
                     $item['status'] = 'expiring';
                     $item['statusLabel'] = 'Expiring soon';
                     $item['statusClass'] = 'info';
                 }
            }

            $result[] = $item;
        }

        // Sort by version descending
        usort($result, fn($a, $b) => $b['version'] <=> $a['version']);

        return $result;
    }

    private function getExtensionConfiguration(string $key, mixed $default = null): mixed
    {
        try {
            $config = $this->extensionConfiguration->get('nt_supporttimes');
            $value = $config[$key] ?? $default;
            
            if (is_array($value)) {
                return implode(',', $value);
            }
            
            return $value;
        } catch (\Exception $e) {
            return $default;
        }
    }
}
