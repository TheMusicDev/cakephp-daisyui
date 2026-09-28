<?php
declare(strict_types=1);

namespace TheMusicDev\DaisyUi\View\Helper;

use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Utility\Inflector;
use Cake\View\Helper\FormHelper as CoreFormHelper;
use InvalidArgumentException;
use TheMusicDev\DaisyUi\View\ClassMap;
use function Cake\Core\h;
use function Cake\I18n\__;
use function Cake\I18n\__n;

/**
 * daisyUI-styled Form helper. https://daisyui.com/components/fieldset/
 *
 * `control()` renders each field as
 * `<div class="fieldset"><label class="fieldset-legend">…</label><input class="input">…</div>`,
 * with an optional help line (`help` option) and the validation error below it.
 * The field's daisyUI classes come from the class map and its `color`, `size`
 * and `appearance` options; a field with a validation error gets the error color.
 *
 * Templates you pass in the helper config win over these defaults. As in core,
 * required fields get inline `oninvalid`/`oninput` handlers for custom validity
 * messages (`autoSetCustomValidity`); under a strict Content-Security-Policy set
 * it to `false` in the helper config.
 */
class FormHelper extends CoreFormHelper
{
    use ComponentTrait;

    /**
     * `control()` types rendered as a daisyUI `input`.
     */
    private const INPUT_TYPES = [
        'text', 'email', 'password', 'number', 'tel', 'url', 'search',
        'date', 'time', 'datetime', 'datetime-local', 'month', 'week',
    ];

    /**
     * Whether the current form was created with `'align' => 'horizontal'`.
     */
    private bool $horizontal = false;

    /**
     * Whether the control being rendered uses the two-column grid (horizontal
     * form, and not a group/rating/filter, which stay stacked).
     */
    private bool $gridControl = false;

    /**
     * @param array<string, mixed> $config Helper config.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $fieldset = h(ClassMap::get('fieldset.base'));
        $templates = [
            'inputContainer' => '<div class="' . $fieldset . '">{{content}}{{help}}</div>',
            'inputContainerError' => '<div class="' . $fieldset . '">{{content}}{{help}}{{error}}</div>',
            'error' => '<p class="' . h(ClassMap::get('control.part.error')) . '" id="{{id}}">{{content}}</p>',
            // Core wraps each multi-checkbox option in `<div class="checkbox">`, which daisyUI would style as a checkbox.
            'checkboxWrapper' => '{{label}}',
        ];
        $this->setTemplates(array_diff_key($templates, $this->templatesArray($config['templates'] ?? [])));
    }

    /**
     * Same as the core method, plus `'align' => 'horizontal'`: controls render
     * with the label in a first column and the field, help and error in a
     * second (radio/checkbox groups and ratings stay stacked).
     *
     * @param mixed $context The form context (entity, array, null, …).
     * @param array<string, mixed> $options Core create() options plus `align`.
     * @return string
     */
    public function create(mixed $context = null, array $options = []): string
    {
        $this->horizontal = ($options['align'] ?? null) === 'horizontal';
        unset($options['align']);

        return parent::create($context, $options);
    }

    /**
     * @param array<string, mixed> $secureAttributes Core end() argument.
     * @return string
     */
    public function end(array $secureAttributes = []): string
    {
        $this->horizontal = false;

        return parent::end($secureAttributes);
    }

    /**
     * Same as the core method, plus daisyUI classes and these options:
     *
     * - `help` (string): plain text (escaped) shown below the field as
     *   `<p class="label">`, linked with `aria-describedby`.
     * - `floating` (bool): daisyUI floating label — the label text sits inside
     *   the field and moves above it on focus (text-like inputs only). The
     *   label text doubles as the placeholder unless you pass one.
     * - `color`, `size`, `appearance`: daisyUI modifiers for the field
     *   (class-map groups of its component, e.g. `input.size.sm`).
     * - `class`: appended to the field's daisyUI classes.
     *
     * @param string $fieldName Field name, e.g. `email` or `Articles.title`.
     * @param array<string, mixed> $options Core control() options plus the above.
     * @return string
     */
    public function control(string $fieldName, array $options = []): string
    {
        $type = $options['type'] ?? $this->_inputType($fieldName, $options);
        $component = $this->componentFor((string)$type, $options);
        if ($component === null) {
            return parent::control($fieldName, $options);
        }
        // Core also accepts a template file name; load it now so our per-call templates can merge with it.
        $options['templates'] = $this->templatesArray($options['templates'] ?? []);
        // A daisyUI toggle is a checkbox with the `toggle` class.
        $options['type'] = $type === 'toggle' ? 'checkbox' : $type;
        $stacked = in_array($type, ['radio', 'rating', 'filter'], true)
            || ($component === 'checkbox' && $type === 'select');
        $this->gridControl = $this->horizontal && !$stacked;
        if ($type === 'rating') {
            return parent::control($fieldName, $this->help($fieldName, $this->rating($fieldName, $options)));
        }
        if ($type === 'filter') {
            return parent::control($fieldName, $this->help($fieldName, $this->filter($fieldName, $options)));
        }
        if ($type === 'otp') {
            $options = $this->otp($fieldName, $options);
            if ($this->gridControl) {
                $options = $this->horizontalLayout('otp', $options);
            }

            return parent::control($fieldName, $this->help($fieldName, $options));
        }
        if ($type === 'range') {
            // daisyUI rule: a range must have min and max.
            $options += ['min' => 0, 'max' => 100];
        }
        if ($this->isFieldError($fieldName)) {
            // The error color replaces any color the caller chose.
            unset($options['color']);
        }

        $validator = !empty($options['validator']);
        $options['class'] = $this->componentClass($component, $options);
        if ($validator) {
            $options['class'] = trim($options['class'] . ' ' . ClassMap::get('validator.base'));
        }
        // Core adds `templates.errorClass` to the field when it has an error.
        if (ClassMap::has($component . '.color.error')) {
            $options['templates'] = (array)($options['templates'] ?? [])
                + ['errorClass' => ClassMap::get($component . '.color.error')];
        }
        if (!empty($options['floating']) && $component === 'input') {
            $options = $this->floatingLabel($fieldName, $options);
        } elseif ($type === 'checkbox' || $type === 'toggle') {
            // A single checkbox sits inside its label: `<label class="label"><input class="checkbox">Text</label>`.
            $options = $this->labelClass($options, 'label.base');
        } else {
            $options = $this->labelClass($options, 'fieldset.part.legend');
        }
        if (($component === 'checkbox' && $type === 'select') || $type === 'radio') {
            $options = $this->group($fieldName, $options);
        }
        unset($options['floating']);
        if ($component === 'input' && (isset($options['prepend']) || isset($options['append']))) {
            $options = $this->inputGroup($fieldName, $options);
        }
        if ($this->gridControl) {
            $options = $this->horizontalLayout($type, $options);
        }

        return parent::control($fieldName, $this->validator($fieldName, $component, $this->help($fieldName, $options)));
    }

    /**
     * A `templates` option as an array: a string is a template file name,
     * read the same way core's `StringTemplate::load()` does.
     *
     * @param mixed $templates Array of templates, file name, or nothing.
     * @return array<string, string>
     */
    private function templatesArray(mixed $templates): array
    {
        if (is_string($templates) && $templates !== '') {
            return (new PhpConfig())->read($templates);
        }

        return is_array($templates) ? $templates : [];
    }

    /**
     * The class-map component for a control type, or null to leave the control to core.
     *
     * @param string $type Resolved control type.
     * @param array<string, mixed> $options Control options.
     * @return string|null
     */
    private function componentFor(string $type, array $options): ?string
    {
        return match (true) {
            in_array($type, self::INPUT_TYPES, true) => 'input',
            $type === 'textarea' => 'textarea',
            $type === 'select' && ($options['multiple'] ?? null) === 'checkbox' => 'checkbox',
            // Core renders `year` as a <select> of years.
            $type === 'select', $type === 'year' => 'select',
            $type === 'checkbox' => 'checkbox',
            $type === 'toggle' => 'toggle',
            $type === 'file' => 'fileInput',
            $type === 'range' => 'range',
            $type === 'rating' => 'rating',
            $type === 'filter' => 'filter',
            $type === 'otp' => 'otp',
            $type === 'radio' => 'radio',
            default => null,
        };
    }

    /**
     * Adds a class-map class to the control's label.
     *
     * @param array<string, mixed> $options Control options.
     * @param string $classKey Class-map key, e.g. `fieldset.part.legend`.
     * @return array<string, mixed>
     */
    private function labelClass(array $options, string $classKey): array
    {
        $label = $options['label'] ?? null;
        if ($label === false) {
            return $options;
        }
        if (!is_array($label)) {
            $label = $label === null ? [] : ['text' => $label];
        }
        $options['label'] = $this->addClass($label, ClassMap::get($classKey));

        return $options;
    }

    /**
     * Wraps the input and its label text in `<label class="floating-label">`.
     *
     * @param string $fieldName Field name.
     * @param array<string, mixed> $options Control options.
     * @return array<string, mixed>
     */
    private function floatingLabel(string $fieldName, array $options): array
    {
        $text = $this->labelText($fieldName, $options);

        $options['label'] = false;
        // Core would otherwise use the placeholder as the accessible name.
        $options += ['placeholder' => $text, 'aria-label' => $text];
        $options['templates'] = (array)($options['templates'] ?? []) + [
            'formGroup' => '<label class="' . h(ClassMap::get('floatingLabel.base')) . '">'
                . '{{input}}<span>{{floatingText}}</span></label>',
        ];
        $options['templateVars']['floatingText'] = h($text);

        return $options;
    }

    /**
     * The control's label text: the `label` option, else the humanized field name (as core does).
     *
     * @param string $fieldName Field name.
     * @param array<string, mixed> $options Control options.
     * @return string
     */
    private function labelText(string $fieldName, array $options): string
    {
        $label = $options['label'] ?? null;
        $text = is_array($label) ? ($label['text'] ?? null) : $label;
        if ($text === null || $text === false) {
            $name = array_slice(explode('.', $fieldName), -1)[0];
            $text = __(Inflector::humanize(Inflector::underscore(preg_replace('/_id$/', '', $name) ?? $name)));
        }

        return (string)$text;
    }

    /**
     * Renders a group of options (multi-checkbox, radio) as a real
     * `<fieldset class="fieldset">` with `<legend class="fieldset-legend">`,
     * each option inside its own `<label class="label">`.
     *
     * @param string $fieldName Field name.
     * @param array<string, mixed> $options Control options.
     * @return array<string, mixed>
     */
    private function group(string $fieldName, array $options): array
    {
        $legend = '';
        if (($options['label'] ?? null) !== false) {
            $legend = $this->Html->tag('legend', h($this->labelText($fieldName, $options)), [
                'class' => ClassMap::get('fieldset.part.legend'),
            ]);
        }
        $fieldset = h(ClassMap::get('fieldset.base'));
        $options['label'] = false;
        $options['templates'] = (array)($options['templates'] ?? []) + [
            'inputContainer' => '<fieldset class="' . $fieldset . '">{{legend}}{{content}}{{help}}</fieldset>',
            'inputContainerError' => '<fieldset class="' . $fieldset . '">'
                . '{{legend}}{{content}}{{help}}{{error}}</fieldset>',
        ];
        $options['templateVars']['legend'] = $legend;
        $options['labelOptions'] = $this->addClass(
            is_array($options['labelOptions'] ?? null) ? $options['labelOptions'] : [],
            ClassMap::get('label.base'),
        );

        return $options;
    }

    /**
     * `type => filter`: daisyUI filter — radio buttons styled as buttons in
     * `<div class="filter">` (the div variant: a nested `<form>` would be invalid
     * inside a CakePHP form), preceded by a reset radio (value `''`, `filter-reset`).
     * Option labels are shown through each radio's `aria-label` (daisyUI renders it).
     * Rendered inside the fieldset/legend group, through core `radio()` so
     * FormProtection still sees the field.
     *
     * @param string $fieldName Field name.
     * @param array<string, mixed> $options Control options (`options` = value => label,
     *   `reset` = reset button text, default '×'; `class` on the filter div).
     * @return array<string, mixed>
     */
    private function filter(string $fieldName, array $options): array
    {
        $callerTemplates = (array)($options['templates'] ?? []);
        $reset = (string)($options['reset'] ?? '×');
        unset($options['reset']);
        $filterClass = $this->componentClass('filter', $options);
        $button = ClassMap::get('button.base');

        $radios = [['value' => '', 'text' => $reset, 'aria-label' => $reset,
            'class' => $button . ' ' . ClassMap::get('filter.part.reset')]];
        foreach ((array)($options['options'] ?? []) as $value => $label) {
            $radios[] = ['value' => (string)$value, 'text' => (string)$label, 'aria-label' => (string)$label,
                'class' => $button];
        }

        $options['type'] = 'radio';
        $options['options'] = $radios;
        $options['hiddenField'] = false;
        $options = $this->group($fieldName, $options);
        $options['labelOptions'] = false;
        $filter = '<div class="' . h($filterClass) . '">{{content}}</div>';
        $fieldset = h(ClassMap::get('fieldset.base'));
        $options['templates'] = [
            'inputContainer' => '<fieldset class="' . $fieldset . '">{{legend}}' . $filter . '{{help}}</fieldset>',
            'inputContainerError' => '<fieldset class="' . $fieldset . '">{{legend}}' . $filter
                . '{{help}}{{error}}</fieldset>',
        ] + (array)$options['templates'];
        // The caller's own per-call templates still win.
        $options['templates'] = $callerTemplates + $options['templates'];

        return $options;
    }

    /**
     * `type => otp`: a one-time-code field — `<label class="otp …">` with one empty
     * `<span>` per digit and a text input (`autocomplete="one-time-code"`,
     * `inputmode="numeric"`, matching `maxlength`/`pattern`). Options: `length`
     * (4–6, default 6), `size`, `color`, `modifier` (joined), `class` (on the label).
     *
     * @param string $fieldName Field name.
     * @param array<string, mixed> $options Control options.
     * @return array<string, mixed>
     * @throws \InvalidArgumentException When length is not 4–6.
     */
    private function otp(string $fieldName, array $options): array
    {
        $length = (int)($options['length'] ?? 6);
        unset($options['length']);
        if ($length < 4 || $length > 6) {
            throw new InvalidArgumentException(sprintf(
                'OTP length must be 4 to 6 digits, got %d (field "%s").',
                $length,
                $fieldName,
            ));
        }
        if ($this->isFieldError($fieldName)) {
            $options['color'] = 'error';
        }
        $otpClass = $this->componentClass('otp', $options);
        $options = $this->labelClass($options, 'fieldset.part.legend');

        $options['type'] = 'text';
        $options += [
            'autocomplete' => 'one-time-code',
            'inputmode' => 'numeric',
            'maxlength' => $length,
            'pattern' => '[0-9]{' . $length . '}',
        ];
        $options['templates'] = [
            'input' => '<label class="' . h($otpClass) . '">' . str_repeat('<span></span>', $length)
                . '<input type="{{type}}" name="{{name}}"{{attrs}}></label>',
            'errorClass' => '',
        ] + (array)$options['templates'];

        return $options;
    }

    /**
     * `type => rating`: star-shaped radios in `<div class="rating">`, inside a
     * fieldset/legend group. Options: `max` (int, default 5), `size`,
     * `modifier => 'half'` (half stars, values in 0.5 steps), `class` (on the
     * rating div). Unless `required`, a hidden first radio lets the user clear
     * the rating (daisyUI `rating-hidden`).
     *
     * @param string $fieldName Field name.
     * @param array<string, mixed> $options Control options.
     * @return array<string, mixed>
     */
    private function rating(string $fieldName, array $options): array
    {
        $callerTemplates = (array)($options['templates'] ?? []);
        if (!empty($options['validator'])) {
            throw new InvalidArgumentException(sprintf(
                'The daisyUI validator works with input, select and textarea fields, not "rating" (field "%s").',
                $fieldName,
            ));
        }
        unset($options['validator'], $options['hint']);
        $max = (int)($options['max'] ?? 5);
        unset($options['max']);
        $half = in_array('half', (array)($options['modifier'] ?? []), true);
        $ratingClass = $this->componentClass('rating', $options);

        $radios = [];
        if (empty($options['required'])) {
            $radios[] = ['value' => '', 'text' => '', 'class' => ClassMap::get('rating.part.clear'),
                'aria-label' => __('Clear rating')];
        }
        $step = $half ? 0.5 : 1;
        for ($i = 1; $i <= $max / $step; $i++) {
            $value = $i * $step;
            $class = ClassMap::get('rating.part.item');
            if ($half) {
                $class .= ' ' . ClassMap::get($i % 2 ? 'rating.part.halfStart' : 'rating.part.halfEnd');
            }
            $radios[] = ['value' => (string)$value, 'text' => (string)$value, 'class' => $class,
                'aria-label' => __n('{0} star', '{0} stars', (int)ceil($value), $value)];
        }

        $options['type'] = 'radio';
        $options['options'] = $radios;
        $options = $this->group($fieldName, $options);
        $options['labelOptions'] = false;
        $rating = '<div class="' . h($ratingClass) . '">{{content}}</div>';
        $fieldset = h(ClassMap::get('fieldset.base'));
        $options['templates'] = [
            'inputContainer' => '<fieldset class="' . $fieldset . '">{{legend}}' . $rating . '{{help}}</fieldset>',
            'inputContainerError' => '<fieldset class="' . $fieldset . '">{{legend}}' . $rating
                . '{{help}}{{error}}</fieldset>',
        ] + (array)$options['templates'];
        // The caller's own per-call templates still win.
        $options['templates'] = $callerTemplates + $options['templates'];

        return $options;
    }

    /**
     * Two-column container for a control in a horizontal form.
     *
     * @param string $type Resolved control type.
     * @param array<string, mixed> $options Control options.
     * @return array<string, mixed>
     */
    private function horizontalLayout(string $type, array $options): array
    {
        $container = h(ClassMap::classes('fieldset.base', 'control.layout.horizontal'));
        $offset = ClassMap::get('control.layout.offset');
        $options['templates'] = (array)($options['templates'] ?? []) + [
            'inputContainer' => '<div class="' . $container . '">{{content}}{{help}}</div>',
            'inputContainerError' => '<div class="' . $container . '">{{content}}{{help}}{{error}}</div>',
            'error' => '<p class="' . h(ClassMap::classes('control.part.error', 'control.layout.offset'))
                . '" id="{{id}}">{{content}}</p>',
        ];
        if (($type === 'checkbox' || $type === 'toggle') && is_array($options['label'] ?? null)) {
            // No separate label column: the checkbox + its label go in the field column.
            $options['label'] = $this->addClass($options['label'], $offset);
        }

        return $options;
    }

    /**
     * `prepend` / `append`: daisyUI puts the `input` class on a parent label
     * when the field has other content — `<label class="input">$ <input> USD</label>`.
     * The field's classes (and error color) move to that wrapper. Each side is
     * plain text (escaped), or `['text' => '<svg…>', 'escape' => false]` for markup.
     *
     * @param string $fieldName Field name.
     * @param array<string, mixed> $options Control options.
     * @return array<string, mixed>
     */
    private function inputGroup(string $fieldName, array $options): array
    {
        $wrapperClass = (string)$options['class'];
        if ($this->isFieldError($fieldName)) {
            $wrapperClass .= ' ' . ClassMap::get('input.color.error');
        }
        $side = static function (mixed $part): string {
            if (is_array($part)) {
                $text = (string)($part['text'] ?? '');

                return ($part['escape'] ?? true) === false ? $text : h($text);
            }

            return h((string)$part);
        };
        $options['templateVars']['prepend'] = $side($options['prepend'] ?? '');
        $options['templateVars']['append'] = $side($options['append'] ?? '');
        unset($options['prepend'], $options['append'], $options['class']);
        $options['templates'] = [
            'input' => '<label class="' . h($wrapperClass) . '">{{prepend}}'
                . '<input type="{{type}}" name="{{name}}"{{attrs}}>{{append}}</label>',
            // The error color is on the wrapper; keep the inner input clean.
            'errorClass' => '',
        ] + (array)($options['templates'] ?? []);

        return $options;
    }

    /**
     * `validator => true` (input, select, textarea only — daisyUI rule): native
     * validation colors the field; `hint` (plain text, escaped) renders as
     * `<p class="validator-hint">` after the help line and describes the field.
     *
     * @param string $fieldName Field name.
     * @param string $component Class-map component of the field.
     * @param array<string, mixed> $options Control options.
     * @return array<string, mixed>
     * @throws \InvalidArgumentException When used on another kind of field.
     */
    private function validator(string $fieldName, string $component, array $options): array
    {
        $validator = !empty($options['validator']);
        $hint = $options['hint'] ?? null;
        unset($options['validator'], $options['hint']);
        if (!$validator) {
            return $options;
        }
        if (!in_array($component, ['input', 'select', 'textarea'], true)) {
            throw new InvalidArgumentException(sprintf(
                'The daisyUI validator works with input, select and textarea fields, not "%s" (field "%s").',
                $component,
                $fieldName,
            ));
        }
        if ($hint === null || $hint === '') {
            return $options;
        }

        $id = $this->_domId($fieldName) . '-hint';
        $options['templateVars']['help'] = ($options['templateVars']['help'] ?? '')
            . $this->Html->tag('p', h((string)$hint), [
                'class' => $this->gridControl
                    ? ClassMap::classes('validator.part.hint', 'control.layout.offset')
                    : ClassMap::get('validator.part.hint'),
                'id' => $id,
            ]);
        $options['aria-describedby'] = trim(($options['aria-describedby'] ?? '') . ' ' . $id);

        return $options;
    }

    /**
     * Turns the `help` option into a `{{help}}` template variable plus `aria-describedby`.
     *
     * @param string $fieldName Field name.
     * @param array<string, mixed> $options Control options.
     * @return array<string, mixed>
     */
    private function help(string $fieldName, array $options): array
    {
        $help = $options['help'] ?? null;
        unset($options['help']);
        if ($help === null || $help === '') {
            return $options;
        }

        $id = $this->_domId($fieldName) . '-help';
        $options['templateVars']['help'] = $this->Html->tag('p', h((string)$help), [
            'class' => $this->gridControl
                ? ClassMap::classes('control.part.help', 'control.layout.offset')
                : ClassMap::get('control.part.help'),
            'id' => $id,
        ]);
        $describedBy = [$id];
        if ($this->isFieldError($fieldName)) {
            $describedBy[] = $this->_domId($fieldName) . '-error';
        }
        $options += ['aria-describedby' => implode(' ', $describedBy)];

        return $options;
    }
}
