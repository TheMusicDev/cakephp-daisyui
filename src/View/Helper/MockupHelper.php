<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\View\Helper;

use Cake\View\Helper;
use TheMusicDev\DaisyUi\View\ClassMap;
use function Cake\Core\h;

/**
 * daisyUI mockup components.
 *
 * @extends \Cake\View\Helper<\Cake\View\View>
 * @property \Cake\View\Helper\HtmlHelper $Html
 */
class MockupHelper extends Helper
{
    use ComponentTrait;

    protected array $helpers = ['Html'];

    /**
     * Browser mockup. https://daisyui.com/components/mockup-browser/
     *
     * Display content inside a browser mockup frame.
     *
     * @param string $content Raw HTML content.
     * @param array<string, mixed> $options `url` (string, escaped; renders toolbar with URL),
     *   `toolbar` (string, raw HTML; overrides url), `class`; any other key becomes an HTML attribute.
     * @return string
     */
    public function browser(string $content, array $options = []): string
    {
        $class = $this->componentClass('browser', $options);
        $url = $options['url'] ?? null;
        $toolbar = $options['toolbar'] ?? null;
        unset($options['url'], $options['toolbar']);

        $toolbarContent = '';
        if ($toolbar !== null) {
            $toolbarContent = $toolbar;
        } elseif ($url !== null) {
            $toolbarContent = $this->tag('div', h((string)$url), ['class' => ClassMap::get('input.base')]);
        }

        $inner = $this->tag('div', $toolbarContent, ['class' => ClassMap::get('browser.part.toolbar')])
            . $this->tag('div', $content);

        return $this->tag('div', $inner, ['class' => $class] + $options);
    }

    /**
     * Code mockup. https://daisyui.com/components/mockup-code/
     *
     * Display code lines inside a code mockup frame.
     *
     * @param array<string|array<string, mixed>>|string $lines Lines of code; string is split on newlines.
     *   Each line is a string or array with `text` (escaped), optional `prefix` and `class`.
     * @param array<string, mixed> $options `prefix` (default prefix for all lines), `numbered` (bool; auto-number
     *   lines 1, 2, 3…), `class`; any other key becomes an HTML attribute.
     * @return string
     */
    public function code(array|string $lines, array $options = []): string
    {
        $class = $this->componentClass('code', $options);
        $defaultPrefix = $options['prefix'] ?? null;
        $numbered = $options['numbered'] ?? false;
        unset($options['prefix'], $options['numbered']);

        if (is_string($lines)) {
            $lines = explode("\n", $lines);
        }

        $content = '';
        foreach ($lines as $index => $line) {
            $prefix = null;
            $text = '';
            $lineClass = '';

            if (is_string($line)) {
                $text = $line;
            } else {
                $text = $line['text'] ?? '';
                if (isset($line['prefix'])) {
                    $prefix = $line['prefix'];
                } elseif ($numbered) {
                    $prefix = (string)($index + 1);
                }
                if (isset($line['class'])) {
                    $lineClass = is_array($line['class']) ? implode(' ', $line['class']) : $line['class'];
                }
            }

            if ($prefix === null && !$numbered && $defaultPrefix !== null) {
                $prefix = $defaultPrefix;
            } elseif ($prefix === null && $numbered) {
                $prefix = (string)($index + 1);
            }

            $preAttrs = [];
            if ($prefix !== null) {
                $preAttrs['data-prefix'] = $prefix;
            }
            if ($lineClass) {
                $preAttrs['class'] = $lineClass;
            }

            $content .= $this->tag('pre', $this->tag('code', h((string)$text)), $preAttrs);
        }

        return $this->tag('div', $content, ['class' => $class] + $options);
    }

    /**
     * Phone mockup. https://daisyui.com/components/mockup-phone/
     *
     * Display content inside a phone mockup frame.
     *
     * @param string $content Raw HTML content.
     * @param array<string, mixed> $options `displayClass` (classes on the display div), `class`;
     *   any other key becomes an HTML attribute.
     * @return string
     */
    public function phone(string $content, array $options = []): string
    {
        $class = $this->componentClass('phone', $options);
        $displayClass = $options['displayClass'] ?? '';
        unset($options['displayClass']);

        $displayAttrs = ['class' => ClassMap::get('phone.part.display')];
        if ($displayClass) {
            $displayAttrs['class'] .= ' ' . $displayClass;
        }

        $inner = $this->tag('div', '', ['class' => ClassMap::get('phone.part.camera')])
            . $this->tag('div', $content, $displayAttrs);

        return $this->tag('div', $inner, ['class' => $class] + $options);
    }

    /**
     * Window mockup. https://daisyui.com/components/mockup-window/
     *
     * Display content inside a window mockup frame.
     *
     * @param string $content Raw HTML content.
     * @param array<string, mixed> $options `contentClass` (classes on the inner div), `class`;
     *   any other key becomes an HTML attribute.
     * @return string
     */
    public function window(string $content, array $options = []): string
    {
        $class = $this->componentClass('window', $options);
        $contentClass = $options['contentClass'] ?? '';
        unset($options['contentClass']);

        $contentAttrs = [];
        if ($contentClass) {
            $contentAttrs['class'] = $contentClass;
        }

        $inner = $this->tag('div', $content, $contentAttrs);

        return $this->tag('div', $inner, ['class' => $class] + $options);
    }
}
