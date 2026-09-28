# cakephp-daisyui

daisyUI 5 view helpers for CakePHP 5.

Built by [TheMusicDev LLC](https://github.com/TheMusicDev), MIT licensed.

- daisyUI **5**, Tailwind CSS **4**, CakePHP **^5.2**, PHP **>= 8.2**
- One helper per daisyUI category, plus daisyUI-styled `Form`, `Paginator`,
  `Flash` and `Breadcrumbs` helpers — so a CakePHP app can go fully daisyUI
  with no other package.

## Install

```sh
composer require themusicdev/cakephp-daisyui
bin/cake plugin load TheMusicDev/DaisyUi
```

## Loading the helpers

Add the trait to your `AppView` and call `loadDaisyUi()`:

```php
// src/View/AppView.php
use TheMusicDev\DaisyUi\View\DaisyUiViewTrait;

class AppView extends View
{
    use DaisyUiViewTrait;

    public function initialize(): void
    {
        $this->loadDaisyUi();
    }
}
```

This registers these twelve helpers:

| Helper | daisyUI category |
|---|---|
| `Actions` | Actions (button, dropdown, fab, modal, swap, theme controller) |
| `DataDisplay` | Data display (badge, card, …) |
| `DataInput` | Data input — redirect stubs; these components belong to `Form` |
| `Feedback` | Feedback (alert, loading, progress, …) |
| `Layout` | Layout (divider, drawer, …) |
| `Mockup` | Mockup (browser, code, phone, window) |
| `Navigation` | Navigation (dock, link, menu, …) |
| `Assets` | Pinned CDN CSS loading (see below) |
| `Form` | daisyUI-styled form controls (replaces the core helper) |
| `Paginator` | daisyUI-styled pagination (replaces the core helper) |
| `Flash` | daisyUI-styled flash messages (replaces the core helper) |
| `Breadcrumbs` | daisyUI-styled breadcrumbs (replaces the core helper) |

To load only some of them instead, register them yourself:

```php
$this->addHelper('TheMusicDev/DaisyUi.Feedback');
```

## The class map

Every daisyUI class the helpers emit comes from one flat map, keyed
`{component}.{group}.{name}`:

```php
// config/class_maps/daisyui.php (excerpt)
'button.base' => 'btn',
'button.color.primary' => 'btn-primary',
'button.size.sm' => 'btn-sm',
```

Each modifier group (`color`, `size`, `appearance`, `modifier`, `direction`,
`placement`, `behavior`, `alignment`) is a method option, so class names are
never concatenated and Tailwind can always see them statically.

**Swap the whole map** (a map replaces the default entirely):

```php
// Put your map in config/class_maps/mycustom.php, then:
Configure::write('DaisyUi.classMap', 'mycustom');
```

The app's `config/class_maps/{name}.php` is preferred over the plugin's copy
of the same name.

**Override single entries:**

```php
Configure::write('DaisyUi.classMapOverrides', ['badge.color.success' => 'badge-success custom']);
```

- A missing key throws `OutOfBoundsException` — that includes invalid option
  values like `['color' => 'nope']`.
- The per-call `class` option is *appended* to the map's classes. To replace
  a map class, override the map entry.
- Class-map **keys** are public API: renaming or removing a key is a major
  release. Changing a default **value** is a minor release.

## CSS loading

`AssetsHelper::css()` emits pinned daisyUI/Tailwind CDN tags (SRI-hashed):

```php
// templates/layout/default.php, inside <head>
<?= $this->Assets->css() ?>
```

**CDN mode is for development and prototyping only.** Tailwind's browser
build compiles CSS in every visitor's browser on every page load — it is not
intended for production. For production, load a compiled stylesheet instead:

```php
Configure::write('DaisyUi.cdn', false);

// in your layout:
<?= $this->Html->css('main') ?>
```

The CDN URLs are overridable via `Configure::write('DaisyUi.cdn', [...])`.
Each entry carries its own URL and integrity hash, so a hash is never
applied to the wrong file:

```php
Configure::write('DaisyUi.cdn', [
    'daisyui' => ['url' => 'https://example.com/daisy.css'],
    'tailwind' => [
        'url' => 'https://example.com/tw.js',
        'integrity' => 'sha384-…',
    ],
]);
```

An entry without `integrity` (or with an empty one) is emitted without the
`integrity`/`crossorigin` attributes; other entries keep their defaults.

## Production CSS: a Tailwind build

Recommended for PHP projects without Node: the **Tailwind standalone CLI**
(single binary) with daisyUI's `daisyui.mjs` plugin file, which daisyUI
officially supports.

Tailwind v4 skips gitignored paths such as `vendor/`, so register the
plugin's sources in your input CSS:

```css
@import "tailwindcss";
@source "../vendor/themusicdev/cakephp-daisyui/src";
@source "../vendor/themusicdev/cakephp-daisyui/config";
@source "../vendor/themusicdev/cakephp-daisyui/templates";
@source "../config";   /* app class-map overrides */
@plugin "./daisyui.mjs";
```

## Content-Security-Policy

Three parts of this plugin use inline script/attributes and need a CSP that
allows them (e.g. `unsafe-inline` or a nonce):

1. the modal's inline `onclick` handler (`showModal()`), and
2. the theme-persistence script emitted by `Assets::css()` (turn it off with
   `DaisyUi.persistTheme => false`), and
3. the `oninvalid`/`oninput` handlers CakePHP's FormHelper adds to required
   fields (turn them off with the helper config `autoSetCustomValidity => false`).
