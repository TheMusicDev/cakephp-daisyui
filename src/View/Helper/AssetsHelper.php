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
        $out = '';
        if (Configure::read('DaisyUi.cdn') !== false) {
            $entries = array_replace($this->getConfig('cdn'), (array)Configure::read('DaisyUi.cdn', []));
            $out .= $this->tag('css', 'daisyui', $entries['daisyui']);
            // daisyui.css ships light and dark only; other themes need themes.css.
            if (array_diff(self::themes(), ['light', 'dark'])) {
                $out .= $this->tag('css', 'themes', $entries['themes']);
            }
            $out .= $this->tag('script', 'tailwind', $entries['tailwind']);
        }
        if (Configure::read('DaisyUi.persistTheme', true)) {
            $out .= $this->themeScript();
        }

        return $out;
    }

    /**
     * The configured themes (`DaisyUi.themes`, default light + dark), or the given
     * list, validated: names go into inline JavaScript and attributes.
     *
     * @param array<string>|null $themes Theme names, or null for the configured list.
     * @return list<string>
     * @throws \InvalidArgumentException On an empty list or an invalid theme name.
     */
    public static function themes(?array $themes = null): array
    {
        $themes = array_values($themes ?? (array)Configure::read('DaisyUi.themes', ['light', 'dark']));
        if ($themes === []) {
            throw new InvalidArgumentException('DaisyUi.themes must list at least one theme.');
        }
        foreach ($themes as $theme) {
            if (!is_string($theme) || !preg_match('/^[a-z0-9][a-z0-9-]*$/', $theme)) {
                throw new InvalidArgumentException(sprintf(
                    'Invalid daisyUI theme name %s: use lowercase letters, digits and "-".',
                    var_export($theme, true),
                ));
            }
        }

        return $themes;
    }

    /**
     * Inline script (spec §5.9): before first paint, sets `data-theme` on `<html>`
     * from localStorage — or, for a two-theme toggle, from the OS dark-mode setting,
     * else the first theme — then keeps every theme controller in sync and saves changes.
     *
     * @return string
     */
    private function themeScript(): string
    {
        $themes = json_encode(
            self::themes(),
            JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_THROW_ON_ERROR,
        );
        $js = <<<JS
(function(){
var k='daisyui-theme',t={$themes},d=document.documentElement,s=null;
try{s=localStorage.getItem(k)}catch(e){}
var dark=t.length===2&&window.matchMedia&&matchMedia('(prefers-color-scheme: dark)').matches;
var v=t.indexOf(s)>-1?s:(dark?t[1]:t[0]);
d.setAttribute('data-theme',v);
function sync(){document.querySelectorAll('input[data-theme-controller]').forEach(function(i){i.checked=i.value===v;});}
document.addEventListener('DOMContentLoaded',function(){
sync();
document.querySelectorAll('input[data-theme-controller]').forEach(function(i){
i.addEventListener('change',function(){
v=i.type==='checkbox'&&!i.checked?t[0]:i.value;
d.setAttribute('data-theme',v);
try{localStorage.setItem(k,v)}catch(e){}
sync();
});});});})();
JS;

        return (string)$this->Html->scriptBlock($js);
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
