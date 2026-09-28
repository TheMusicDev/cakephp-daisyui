# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Unreleased

## 1.0.0 - 2026-09-28

First release: all 68 daisyUI 5 components plus daisyUI-styled Form, Paginator,
Flash and Breadcrumbs helpers.

### Added

- `ActionsHelper::button()` with `color`, `appearance`, `behavior`, `size`,
  `modifier`, `url`, `class`, `escape` options and the `button` class map.
- `DataDisplayHelper::badge()` with `color`, `size`, `appearance`, `class`,
  `escape` options and the `badge` class map.
- `FeedbackHelper::alert()` with `color`, `appearance`, `direction`, `role`,
  `class`, `escape` options and the `alert` class map.
- `DataDisplayHelper::card()` with `title`, `actions`, `image`, `imageAlt`,
  `size`, `modifier`, `appearance`, `class`, `escape` options and the
  `card` class map.
- `DataDisplayHelper::avatar()` and `avatarGroup()` with `alt`, `placeholder`,
  `innerClass`, `modifier`, `class` options and the `avatar` and `avatarGroup`
  class maps.
- `DataDisplayHelper::kbd()` with `size`, `class`, `escape` options and the
  `kbd` class map.
- `DataDisplayHelper::status()` with `color`, `size`, `role`, `aria-hidden`,
  `aria-label`, `class` options and the `status` class map.
- `FeedbackHelper::loading()` with `appearance`, `size`, `role`, `aria-label`,
  `class` options and the `loading` class map.
- `FeedbackHelper::progress()` with `max`, `color`, `class` options and the
  `progress` class map.
- `FeedbackHelper::radialProgress()` with `text`, `diameter`, `thickness`, `role`,
  `aria-valuenow`, `aria-valuemin`, `aria-valuemax`, `style`, `class` options
  and the `radialProgress` class map.
- `FeedbackHelper::tooltip()` with `placement`, `alignment`, `color`, `modifier`,
  `class`, `escape` options and the `tooltip` class map.
- `ActionsHelper::swap()` with `label`, `checked`, `indeterminate`, `appearance`,
  `modifier`, `class` options and the `swap` class map.
- `ActionsHelper::dropdown()` with `buttonClass`, `open`, `placement`, `modifier`,
  `class`, `escape` options and the `dropdown` class map.
- `FeedbackHelper::toast()` with `placement`, `class` options and the `toast`
  class map.
- `FlashHelper::render()` renders the standard flash types (default, success,
  error, warning, info) as daisyUI alerts via plugin elements; `params.class`
  and `params.alert` customise them; app overrides go in
  `templates/plugin/TheMusicDev/DaisyUi/element/flash/`.
- `LayoutHelper::join()` and `joinItemClass()` with `direction`, `class` options
  and the `join` and `joinItem` class maps.
- `PaginatorHelper`: numbers, prev/next and first/last render as daisyUI
  `join-item btn` links (active page `btn-active` + `aria-current`, disabled
  items follow the Button disabled rule); new `links()` wraps prev + numbers +
  next in a `join` inside a `<nav>`.
- `BreadcrumbsHelper` renders `<nav class="breadcrumbs">` and escapes crumb
  titles by default (the core helper does not); `'escape' => false` per crumb
  for raw HTML.
- `FormHelper::control()` renders daisyUI fieldset controls: `div.fieldset`
  container, `label.fieldset-legend`, `help` line and error text (both linked
  via `aria-describedby`), the field's error color on validation errors, and
  `floating => true` floating labels.
- Form inputs: `color`, `size` and `appearance` (ghost) on text-like controls;
  a validation error's color replaces the caller's `color`.
- Form textareas render with the daisyUI `textarea` class and its `color`,
  `size` and `appearance` (ghost) modifiers.
- Form selects render with the daisyUI `select` class and its modifiers (`size`
  here is the daisyUI size, not the HTML attribute).
- Form checkboxes: `<label class="label">` wrapping `input.checkbox` (with
  modifiers); multi-checkbox selects render as a `<fieldset>` + `<legend>` group
  (core's `<div class="checkbox">` wrapper removed — daisyUI would style it).
- Form radio groups render as a `<fieldset>` + `<legend>` with each
  `input.radio` inside its own `label`, with modifiers and error color.
- `type => toggle` form controls: a checkbox with the daisyUI `toggle` class
  and its modifiers.
- Form file inputs (`file-input` + modifiers) and range sliders (`range` +
  modifiers incl. `direction => vertical`; `min`/`max` default to 0/100).
- `type => rating` form controls: star radios in `div.rating` inside a
  fieldset/legend, `max` (default 5), `size`, `modifier => half`, a clear option
  unless `required`. `ClassMap::has()`.
- `validator => true` on input/select/textarea controls adds the daisyUI
  `validator` class; `hint` renders a `validator-hint` linked via
  `aria-describedby`.
- Input groups: `prepend` / `append` on text-like controls render
  `<label class="input">prefix<input>suffix</label>` (classes and error color on
  the wrapper; sides escaped unless `[text, escape => false]`).
- `create(..., ['align' => 'horizontal'])`: label column + field column layout
  (class-map keys `control.layout.*`); groups stay stacked.
- `DataDisplayHelper::list()` and `listColumnClass()` with `class` option and
  the `list` class map.
- `DataDisplayHelper::stats()` with `direction`, `class` options and the
  `stats` and `stat` class maps.
- `NavigationHelper::steps()` with `direction`, `class` options and the
  `steps` and `step` class maps.
- `NavigationHelper::tabs()` with `appearance`, `size`, `placement`, `name`, `class`
  options and the `tabs` and `tab` class maps; link mode and content mode with
  auto-generated radio-group names.
- `DataDisplayHelper::collapse()` with `open`, `modifier`, `class` options and
  the `collapse` class map; uses native HTML5 `<details>` and `<summary>`.
- `DataDisplayHelper::accordion()` with `name`, `modifier`, `class` options and
  the `collapse` class map; radio-based with auto-generated group names.
- `DataDisplayHelper::timeline()` with `modifier`, `direction`, `connect`, `class`
  options and the `timeline` class map; per-item escape control.
- `DataDisplayHelper::table()`: rows as arrays or entities (cells via
  `Hash::get()` paths), column labels/`format` callables/classes, `size`,
  `modifier` (zebra, pin-rows, pin-cols), `caption`, `empty`, `rowHeader`, and a
  scroll wrapper (`wrap`).
- `ActionsHelper::modal()` (native `<dialog>`, escaped `title` with
  `aria-labelledby`, raw content/`actions`, close button in `form method=dialog`,
  `backdropClose`, placement/modifier) and `modalTrigger()` (button with
  `onclick="document.getElementById(…).showModal()"`); ids are validated.
- `ActionsHelper::themeController()`: toggle for two themes, dropdown of radios
  for more; `AssetsHelper::css()` adds `themes.css` for non light/dark themes and
  a persistence script (`DaisyUi.persistTheme`, default true) that applies the
  saved or OS-preferred theme before first paint. `AssetsHelper::themes()`.
- `NavigationHelper::link()` with `color`, `appearance`, `class`, `escape`
  options and the `link` class map.
- `NavigationHelper::menu()` with `size`, `direction`, `modifier`, `class`
  options and the `menu`, `menuItem` class maps; items support `text`, `url`,
  `icon`, `active`, `disabled`, `children` (submenu), `content` (raw), and `title`.
- `NavigationHelper::navbar()` with `start`, `center`, `end` sections and `class`
  options and the `navbar` class map.
- `LayoutHelper::drawer()` with `id`, `content`, `side`, `overlayLabel`,
  `placement`, `modifier`, `class` options and the `drawer` class map; `drawerButton()`
  helper renders a toggle label inheriting button styling.
- `LayoutHelper::divider()` with `text`, `color`, `direction`, `placement`, `class`,
  `escape` options and the `divider` class map; adds `role="separator"` by default.
- `LayoutHelper::footer()`: raw content or sections (`title`, `links`,
  `content`) rendered as `<nav>` columns with `footer-title` headings.
- `NavigationHelper::dock()` with `size`, `class` options and the `dock` and
  `dockItem` class maps; items support `icon` (raw), `label` (escaped), `url`,
  and `active`.
- `NavigationHelper::megamenu()` with `id`, items (`label`, `content`), `modifier`
  (wide, full), `size`, `direction`, `class` options and the `megamenu` class map
  (max 10 items); `megamenuButton()` renders a button that opens the megamenu.
- `LayoutHelper::hero()` with `content` (raw), `overlay` (bool), `contentClass`,
  `class` options and the `hero` class map.
- `LayoutHelper::indicator()` with `content` (raw), `item` (raw), `placement`,
  `itemClass`, `class` options and the `indicator` and `indicatorItem` class maps.
- `LayoutHelper::mask()` with `image`, `alt`, `appearance`, `modifier`, `class`
  options and the `mask` class map (requires appearance or modifier).
- `LayoutHelper::stack()` with `content` (raw), `modifier`, `class` options and
  the `stack` class map.
- `FeedbackHelper::skeleton()` with `text` (escaped), `class` options and the
  `skeleton` class map; adds `aria-hidden="true"` by default unless text is given.
- `DataDisplayHelper::carousel()` with `modifier` (start, center, end), `direction`
  (horizontal, vertical), `itemClass`, `class` options and the `carousel` class map;
  items are raw HTML strings or arrays with `content`, `class`, `id` keys.
- `DataDisplayHelper::chat()` with `placement` (start, end), `color` (neutral, primary,
  secondary, accent, info, success, warning, error), `image` (raw), `header`, `footer`,
  `class`, `escape` options and the `chat` and `chatBubble` class maps.
- `DataDisplayHelper::countdown()` with `value` (0–999), `class` options and the `countdown`
  class map; throws \InvalidArgumentException if value outside range.
- `DataDisplayHelper::diff()` with `item1`, `item2` (raw HTML), `class` options and the `diff`
  class map; tabindex is overridable.
- `DataDisplayHelper::hover3d()` with `content` (raw), `url` (optional; renders as `<a>`),
  `class` options and the `hover3d` class map.
- `DataDisplayHelper::hoverGallery()` with `images` (string/array with src, alt), `class`
  options and the `hoverGallery` class map; throws if more than 10 images.
- `DataDisplayHelper::textRotate()` with `lines` (2–6; string or array with text, class),
  `innerClass`, `class` options and the `textRotate` class map; throws if line count out of range.
- `DataDisplayHelper::aura()` with `content` (raw), `appearance` (dual, rainbow, holo, gold,
  silver, glow), `size` (xs–xl), `class` options and the `aura` class map.
- `ActionsHelper::fab()` with `icon` (raw), `actions` (icon, label, url, color),
  `label` (trigger aria-label), `modifier` (flower), `class` options and the `fab` class map;
  throws if action lacks label.
- `MockupHelper::browser()` with `content` (raw), `url` (escaped), `toolbar` (raw),
  `class` options and the `browser` class map.
- `MockupHelper::code()` with `lines` (string split on newlines or array with text, prefix, class),
  `prefix` (default), `numbered` (bool), `class` options and the `code` class map.
- `MockupHelper::phone()` with `content` (raw), `displayClass`, `class` options and the
  `phone` class map.
- `MockupHelper::window()` with `content` (raw), `contentClass`, `class` options and the
  `window` class map.
- Form `type => filter` (daisyUI filter: button-styled radios with a reset,
  div variant) and `type => otp` (one-time code with digit boxes, `length` 4–6,
  size/color/joined).
- Flash messages are dismissible by default (a ✕ that hides the alert, no
  JavaScript); `params.dismiss => false` turns it off.
