<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\View\Helper;

use Cake\View\Helper\BreadcrumbsHelper as CoreBreadcrumbsHelper;
use TheMusicDev\DaisyUi\View\ClassMap;
use function Cake\Core\h;

/**
 * daisyUI-styled Breadcrumbs helper. https://daisyui.com/components/breadcrumbs/
 *
 * Renders `<nav class="breadcrumbs" aria-label="…"><ul>…</ul></nav>`.
 * Unlike the core helper, crumb titles are **escaped by default**; pass
 * `'escape' => false` in a crumb's options to output raw HTML (e.g. an icon).
 * Helper config `label` sets the nav's aria-label (default 'Breadcrumb').
 * Templates you pass in the helper config win over these defaults.
 */
class BreadcrumbsHelper extends CoreBreadcrumbsHelper
{
    /**
     * @param array<string, mixed> $config Helper config.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $userTemplates = $config['templates'] ?? [];
        if (is_array($userTemplates) && isset($userTemplates['wrapper'])) {
            return;
        }
        $this->setTemplates([
            'wrapper' => '<nav class="' . h(ClassMap::get('breadcrumbs.base')) . '" aria-label="'
                . h((string)($config['label'] ?? 'Breadcrumb')) . '"><ul{{attrs}}>{{content}}</ul></nav>',
        ]);
    }

    /**
     * Same as the core method, but titles are escaped unless the crumb's
     * options say `'escape' => false`.
     *
     * @param array<string, mixed> $attributes Attributes for the `<ul>`.
     * @param array<string, mixed> $separator Separator config (see core).
     * @return string
     */
    public function render(array $attributes = [], array $separator = []): string
    {
        $original = $this->crumbs;
        $this->crumbs = array_map(static function (array $crumb): array {
            $escape = ($crumb['options']['escape'] ?? true) !== false;
            // Never hand `escape` to formatAttributes(): false there disables attribute escaping.
            unset($crumb['options']['escape']);
            if ($escape) {
                $crumb['title'] = h($crumb['title']);
            }

            return $crumb;
        }, $original);

        try {
            return parent::render($attributes, $separator);
        } finally {
            $this->crumbs = $original;
        }
    }
}
