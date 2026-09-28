<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\View\Helper;

use Cake\Core\Configure;
use Cake\View\Helper;

/**
 * Emits the pinned daisyUI CDN assets (spec §5.7).
 *
 * CDN mode is for development and prototyping; production apps load a
 * compiled stylesheet with `$this->Html->css()`.
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
            'daisyui' => 'https://cdn.jsdelivr.net/npm/daisyui@5.7.46/daisyui.css',
            'themes' => 'https://cdn.jsdelivr.net/npm/daisyui@5.7.46/themes.css',
            'tailwind' => 'https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4.3.3',
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
        $cdn = Configure::read('DaisyUi.cdn');
        if ($cdn === null) {
            $cdn = $this->getConfig('cdn');
        }
        if ($cdn === false || !is_array($cdn)) {
            return '';
        }

        $options = ['crossorigin' => 'anonymous'];

        return $this->Html->css((string)$cdn['daisyui'], ['integrity' => self::SRI_DAISYUI] + $options)
            . $this->Html->script((string)$cdn['tailwind'], ['integrity' => self::SRI_TAILWIND] + $options);
    }

    private const SRI_DAISYUI = 'sha384-bbGkD3MAh/9AO9eBt/6ReKyGTu78VjNCrlo1uLqxHFOrFr8lRuS4H0sC04ucGill';

    private const SRI_TAILWIND = 'sha384-aJ9rL4k6lF+91guGvUFVSkpIcge7Zd9EiI4TQDLoK9kFaFJgKHgjEXVvG/qA5COj';
}
