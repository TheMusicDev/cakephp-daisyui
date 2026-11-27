<?php
declare(strict_types=1);

/**
 * Updates the CDN pins in `AssetsHelper` (and the test expectations that quote
 * them) to the latest npm releases of daisyUI and @tailwindcss/browser, and
 * recomputes their SRI integrity hashes. Run on a schedule in CI (see
 * .github/workflows/cdn-pins.yml); any diff turns into a "CDN pin bump"
 * pull request.
 *
 * Usage: php bin/check-cdn-pins.php [--dry]
 *
 * Exits 0 when nothing changed, 1 when the pins were updated (or would be,
 * with --dry).
 */

$dry = in_array('--dry', $argv, true);

/**
 * @return string
 * @throws \RuntimeException
 */
function latestNpmVersion(string $package): string
{
    $json = @file_get_contents('https://registry.npmjs.org/' . $package . '/latest');
    if ($json === false) {
        throw new RuntimeException('npm registry unreachable for ' . $package);
    }
    $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    if (!is_string($data['version'] ?? null)) {
        throw new RuntimeException('no version in registry response for ' . $package);
    }

    return $data['version'];
}

/**
 * @return string
 * @throws \RuntimeException
 */
function integrityFor(string $url): string
{
    $body = @file_get_contents($url);
    if ($body === false) {
        throw new RuntimeException('could not fetch ' . $url);
    }

    return 'sha384-' . base64_encode(hash('sha384', $body, true));
}

// cdn key in AssetsHelper => npm package pinned by that key's URLs.
$packages = [
    'daisyui' => 'daisyui',
    'themes' => 'daisyui',
    'tailwind' => '@tailwindcss/browser',
];

$assetsHelperPath = __DIR__ . '/../src/View/Helper/AssetsHelper.php';
$files = array_merge(
    glob(__DIR__ . '/../src/View/Helper/*.php') ?: [],
    glob(__DIR__ . '/../tests/TestCase/View/Helper/Assets/*.php') ?: [],
);

$changed = 0;
foreach ($packages as $key => $package) {
    $latest = latestNpmVersion($package);

    $source = file_get_contents($assetsHelperPath);
    if ($source === false) {
        throw new RuntimeException('could not read AssetsHelper.php');
    }
    if (!preg_match("/'" . $key . "' => \[\s*'url' => '([^']+)'/", $source, $m)) {
        throw new RuntimeException("no pin found for '{$key}' in AssetsHelper.php");
    }
    $oldUrl = $m[1];
    // `daisyui@5.7.46` in the daisyUI URLs, `browser@4.3.3` in the Tailwind one.
    $newUrl = preg_replace('/(daisyui@|browser@)[\d.]+/', '${1}' . $latest, $oldUrl, 1);
    if ($newUrl === $oldUrl) {
        continue;
    }

    $oldIntegrity = integrityFor($oldUrl);
    $newIntegrity = integrityFor($newUrl);

    foreach ($files as $file) {
        $source = file_get_contents($file);
        if ($source === false) {
            continue;
        }
        $updated = str_replace([$oldUrl, $oldIntegrity], [$newUrl, $newIntegrity], $source);
        if ($updated === $source) {
            continue;
        }
        echo 'updated ' . substr((string)realpath($file), strlen((string)getcwd()) + 1) . PHP_EOL;
        $changed++;
        if (!$dry) {
            file_put_contents($file, $updated);
        }
    }

    echo $key . ': ' . $package . ' pin ' . $oldUrl . ' → ' . $newUrl . PHP_EOL;
}

exit($changed === 0 ? 0 : 1);