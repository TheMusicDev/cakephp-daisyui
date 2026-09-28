<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\View\Helper;

use Cake\Core\Configure;
use Cake\View\Helper;
use InvalidArgumentException;

/**
 * Emits the pinned daisyUI CDN assets (spec §5.7).
 *
 * CDN mode is for development and prototyping; production apps load a
 * compiled stylesheet with `$this->Html->css()`.
 *
 * Each CDN entry carries its own URL and integrity hash, so a hash is
 * never applied to a user-supplied URL.
 *
 * @extends \Cake\View\Helper<\Cake\View\View>
 * @property \Cake\View\Helper\HtmlHelper $Html
 */
class AssetsHelper extends Helper
{
    protected array $helpers = ['Html'];

    /**
     * @var array<string, mixed>
     */
    protected array $_defaultConfig = [
        'cdn' => [
            'daisyui' => [
                'url' => 'https://cdn.jsdelivr.net/npm/daisyui@5.7.46/daisyui.css',
                'integrity' => 'sha384-bbGkD3MAh/9AO9eBt/6ReKyGTu78VjNCrlo1uLqxHFOrFr8lRuS4H0sC04ucGill',
            ],
            'themes' => [
                'url' => 'https://cdn.jsdelivr.net/npm/daisyui@5.7.46/themes.css',
                'integrity' => 'sha384-c36/WtFSy9L5usv/sVucIkudH6aZA2jUVg6yyWX8TzPxBC9XoLMaa2WO9kaM5pKc',
            ],
            'tailwind' => [
                'url' => 'https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4.3.3',
                'integrity' => 'sha384-aJ9rL4k6lF+91guGvUFVSkpIcge7Zd9EiI4TQDLoK9kFaFJgKHgjEXVvG/qA5COj',
            ],
        ],
    ];

    /**
     * Emits the pinned daisyUI stylesheet and the Tailwind browser script,
     * with SRI hashes.
     *
     * `'DaisyUi.cdn' => false` in Configure disables the tags; the user then
     * loads their compiled stylesheet with `$this->Html->css()`.
     *
     * @return string The `<link>` and `<script>` tags, or '' when disabled.
     */
    public function css(): string
    {
        if (Configure::read('DaisyUi.cdn') === false) {
            return '';
        }

        $entries = array_replace($this->getConfig('cdn'), (array)Configure::read('DaisyUi.cdn', []));

        return $this->tag('css', 'daisyui', $entries['daisyui'])
            . $this->tag('script', 'tailwind', $entries['tailwind']);
    }

    /**
     * Emits one asset tag from a CDN entry.
     *
     * @param string $type Either 'css' or 'script'.
     * @param string $name Entry key under `DaisyUi.cdn`, for error messages.
     * @param array<string, mixed> $entry The entry, with 'url' and an
     *   optional non-empty 'integrity'.
     * @return string
     * @throws \InvalidArgumentException When the entry has no non-empty 'url'.
     */
    private function tag(string $type, string $name, array $entry): string
    {
        $url = $entry['url'] ?? null;
        if (!is_string($url) || $url === '') {
            throw new InvalidArgumentException(sprintf(
                'DaisyUi.cdn.%s needs a non-empty \'url\', e.g. [\'url\' => \'https://...\'].',
                $name,
            ));
        }
        $options = [];
        $integrity = $entry['integrity'] ?? null;
        if (is_string($integrity) && $integrity !== '') {
            $options['integrity'] = $integrity;
            $options['crossorigin'] = 'anonymous';
        }

        return (string)($type === 'css'
            ? $this->Html->css($url, $options)
            : $this->Html->script($url, $options));
    }
}
