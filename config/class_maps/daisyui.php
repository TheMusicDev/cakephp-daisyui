<?php
declare(strict_types=1);

// Almost every value is a daisyUI class. A few plugin-owned keys also hold Tailwind
// utilities (control.*, table.part.wrapper, themeController.part.menu, flash.part.*):
// see README "Tailwind utility classes the plugin emits" before overriding them.
return [
    // Button — https://daisyui.com/components/button/
    'button.base' => 'btn',
    'button.color.neutral' => 'btn-neutral',
    'button.color.primary' => 'btn-primary',
    'button.color.secondary' => 'btn-secondary',
    'button.color.accent' => 'btn-accent',
    'button.color.info' => 'btn-info',
    'button.color.success' => 'btn-success',
    'button.color.warning' => 'btn-warning',
    'button.color.error' => 'btn-error',
    'button.appearance.outline' => 'btn-outline',
    'button.appearance.dash' => 'btn-dash',
    'button.appearance.soft' => 'btn-soft',
    'button.appearance.ghost' => 'btn-ghost',
    'button.appearance.link' => 'btn-link',
    'button.behavior.active' => 'btn-active',
    'button.behavior.disabled' => 'btn-disabled',
    'button.size.xs' => 'btn-xs',
    'button.size.sm' => 'btn-sm',
    'button.size.md' => 'btn-md',
    'button.size.lg' => 'btn-lg',
    'button.size.xl' => 'btn-xl',
    'button.modifier.wide' => 'btn-wide',
    'button.modifier.block' => 'btn-block',
    'button.modifier.square' => 'btn-square',
    'button.modifier.circle' => 'btn-circle',

    // Badge — https://daisyui.com/components/badge/
    'badge.base' => 'badge',
    'badge.color.neutral' => 'badge-neutral',
    'badge.color.primary' => 'badge-primary',
    'badge.color.secondary' => 'badge-secondary',
    'badge.color.accent' => 'badge-accent',
    'badge.color.info' => 'badge-info',
    'badge.color.success' => 'badge-success',
    'badge.color.warning' => 'badge-warning',
    'badge.color.error' => 'badge-error',
    'badge.appearance.outline' => 'badge-outline',
    'badge.appearance.dash' => 'badge-dash',
    'badge.appearance.soft' => 'badge-soft',
    'badge.appearance.ghost' => 'badge-ghost',
    'badge.size.xs' => 'badge-xs',
    'badge.size.sm' => 'badge-sm',
    'badge.size.md' => 'badge-md',
    'badge.size.lg' => 'badge-lg',
    'badge.size.xl' => 'badge-xl',

    // Alert — https://daisyui.com/components/alert/
    'alert.base' => 'alert',
    'alert.color.info' => 'alert-info',
    'alert.color.success' => 'alert-success',
    'alert.color.warning' => 'alert-warning',
    'alert.color.error' => 'alert-error',
    'alert.appearance.outline' => 'alert-outline',
    'alert.appearance.dash' => 'alert-dash',
    'alert.appearance.soft' => 'alert-soft',
    'alert.direction.vertical' => 'alert-vertical',
    'alert.direction.horizontal' => 'alert-horizontal',

    // Card — https://daisyui.com/components/card/
    'card.base' => 'card',
    'card.part.title' => 'card-title',
    'card.part.body' => 'card-body',
    'card.part.actions' => 'card-actions',
    'card.appearance.border' => 'card-border',
    'card.appearance.dash' => 'card-dash',
    'card.modifier.side' => 'card-side',
    'card.modifier.image-full' => 'image-full',
    'card.size.xs' => 'card-xs',
    'card.size.sm' => 'card-sm',
    'card.size.md' => 'card-md',
    'card.size.lg' => 'card-lg',
    'card.size.xl' => 'card-xl',

    // Avatar — https://daisyui.com/components/avatar/
    'avatar.base' => 'avatar',
    'avatar.modifier.online' => 'avatar-online',
    'avatar.modifier.offline' => 'avatar-offline',
    'avatar.modifier.placeholder' => 'avatar-placeholder',
    'avatarGroup.base' => 'avatar-group',

    // Kbd — https://daisyui.com/components/kbd/
    'kbd.base' => 'kbd',
    'kbd.size.xs' => 'kbd-xs',
    'kbd.size.sm' => 'kbd-sm',
    'kbd.size.md' => 'kbd-md',
    'kbd.size.lg' => 'kbd-lg',
    'kbd.size.xl' => 'kbd-xl',

    // Status — https://daisyui.com/components/status/
    'status.base' => 'status',
    'status.color.neutral' => 'status-neutral',
    'status.color.primary' => 'status-primary',
    'status.color.secondary' => 'status-secondary',
    'status.color.accent' => 'status-accent',
    'status.color.info' => 'status-info',
    'status.color.success' => 'status-success',
    'status.color.warning' => 'status-warning',
    'status.color.error' => 'status-error',
    'status.size.xs' => 'status-xs',
    'status.size.sm' => 'status-sm',
    'status.size.md' => 'status-md',
    'status.size.lg' => 'status-lg',
    'status.size.xl' => 'status-xl',

    // Loading — https://daisyui.com/components/loading/
    'loading.base' => 'loading',
    'loading.appearance.spinner' => 'loading-spinner',
    'loading.appearance.dots' => 'loading-dots',
    'loading.appearance.ring' => 'loading-ring',
    'loading.appearance.ball' => 'loading-ball',
    'loading.appearance.bars' => 'loading-bars',
    'loading.appearance.infinity' => 'loading-infinity',
    'loading.size.xs' => 'loading-xs',
    'loading.size.sm' => 'loading-sm',
    'loading.size.md' => 'loading-md',
    'loading.size.lg' => 'loading-lg',
    'loading.size.xl' => 'loading-xl',

    // Progress — https://daisyui.com/components/progress/
    'progress.base' => 'progress',
    'progress.color.neutral' => 'progress-neutral',
    'progress.color.primary' => 'progress-primary',
    'progress.color.secondary' => 'progress-secondary',
    'progress.color.accent' => 'progress-accent',
    'progress.color.info' => 'progress-info',
    'progress.color.success' => 'progress-success',
    'progress.color.warning' => 'progress-warning',
    'progress.color.error' => 'progress-error',

    // Radial progress — https://daisyui.com/components/radial-progress/
    'radialProgress.base' => 'radial-progress',

    // Tooltip — https://daisyui.com/components/tooltip/
    'tooltip.base' => 'tooltip',
    'tooltip.part.content' => 'tooltip-content',
    'tooltip.modifier.open' => 'tooltip-open',
    'tooltip.placement.top' => 'tooltip-top',
    'tooltip.placement.bottom' => 'tooltip-bottom',
    'tooltip.placement.left' => 'tooltip-left',
    'tooltip.placement.right' => 'tooltip-right',
    'tooltip.alignment.start' => 'tooltip-start',
    'tooltip.alignment.center' => 'tooltip-center',
    'tooltip.alignment.end' => 'tooltip-end',
    'tooltip.color.primary' => 'tooltip-primary',
    'tooltip.color.secondary' => 'tooltip-secondary',
    'tooltip.color.accent' => 'tooltip-accent',
    'tooltip.color.info' => 'tooltip-info',
    'tooltip.color.success' => 'tooltip-success',
    'tooltip.color.warning' => 'tooltip-warning',
    'tooltip.color.error' => 'tooltip-error',

    // Join — https://daisyui.com/components/join/
    'join.base' => 'join',
    'join.direction.vertical' => 'join-vertical',
    'join.direction.horizontal' => 'join-horizontal',
    'joinItem.base' => 'join-item',

    // Breadcrumbs — https://daisyui.com/components/breadcrumbs/
    'breadcrumbs.base' => 'breadcrumbs',

    // Fieldset — https://daisyui.com/components/fieldset/ (used by FormHelper::control())
    'fieldset.base' => 'fieldset',
    'fieldset.part.legend' => 'fieldset-legend',
    // Label — https://daisyui.com/components/label/
    'label.base' => 'label',
    'floatingLabel.base' => 'floating-label',
    // FormHelper::control() help line and validation error (plugin keys, not daisyUI classes)
    'control.part.help' => 'label',
    'control.part.error' => 'label text-error',
    // FormHelper horizontal layout (create(..., ['align' => 'horizontal'])): plugin keys, Tailwind grid utilities
    'control.layout.horizontal' => 'grid-cols-[10rem_1fr] items-center gap-x-4',
    'control.layout.offset' => 'col-start-2',

    // Input — https://daisyui.com/components/input/
    'input.base' => 'input',
    'input.appearance.ghost' => 'input-ghost',
    'input.color.neutral' => 'input-neutral',
    'input.color.primary' => 'input-primary',
    'input.color.secondary' => 'input-secondary',
    'input.color.accent' => 'input-accent',
    'input.color.info' => 'input-info',
    'input.color.success' => 'input-success',
    'input.color.warning' => 'input-warning',
    'input.size.xs' => 'input-xs',
    'input.size.sm' => 'input-sm',
    'input.size.md' => 'input-md',
    'input.size.lg' => 'input-lg',
    'input.size.xl' => 'input-xl',
    'input.color.error' => 'input-error',

    // Textarea — https://daisyui.com/components/textarea/
    'textarea.base' => 'textarea',
    'textarea.appearance.ghost' => 'textarea-ghost',
    'textarea.color.neutral' => 'textarea-neutral',
    'textarea.color.primary' => 'textarea-primary',
    'textarea.color.secondary' => 'textarea-secondary',
    'textarea.color.accent' => 'textarea-accent',
    'textarea.color.info' => 'textarea-info',
    'textarea.color.success' => 'textarea-success',
    'textarea.color.warning' => 'textarea-warning',
    'textarea.color.error' => 'textarea-error',
    'textarea.size.xs' => 'textarea-xs',
    'textarea.size.sm' => 'textarea-sm',
    'textarea.size.md' => 'textarea-md',
    'textarea.size.lg' => 'textarea-lg',
    'textarea.size.xl' => 'textarea-xl',

    // Select — https://daisyui.com/components/select/
    'select.base' => 'select',
    'select.appearance.ghost' => 'select-ghost',
    'select.color.neutral' => 'select-neutral',
    'select.color.primary' => 'select-primary',
    'select.color.secondary' => 'select-secondary',
    'select.color.accent' => 'select-accent',
    'select.color.info' => 'select-info',
    'select.color.success' => 'select-success',
    'select.color.warning' => 'select-warning',
    'select.color.error' => 'select-error',
    'select.size.xs' => 'select-xs',
    'select.size.sm' => 'select-sm',
    'select.size.md' => 'select-md',
    'select.size.lg' => 'select-lg',
    'select.size.xl' => 'select-xl',

    // Checkbox — https://daisyui.com/components/checkbox/
    'checkbox.base' => 'checkbox',
    'checkbox.color.primary' => 'checkbox-primary',
    'checkbox.color.secondary' => 'checkbox-secondary',
    'checkbox.color.accent' => 'checkbox-accent',
    'checkbox.color.neutral' => 'checkbox-neutral',
    'checkbox.color.success' => 'checkbox-success',
    'checkbox.color.warning' => 'checkbox-warning',
    'checkbox.color.info' => 'checkbox-info',
    'checkbox.color.error' => 'checkbox-error',
    'checkbox.size.xs' => 'checkbox-xs',
    'checkbox.size.sm' => 'checkbox-sm',
    'checkbox.size.md' => 'checkbox-md',
    'checkbox.size.lg' => 'checkbox-lg',
    'checkbox.size.xl' => 'checkbox-xl',

    // Radio — https://daisyui.com/components/radio/
    'radio.base' => 'radio',
    'radio.color.neutral' => 'radio-neutral',
    'radio.color.primary' => 'radio-primary',
    'radio.color.secondary' => 'radio-secondary',
    'radio.color.accent' => 'radio-accent',
    'radio.color.success' => 'radio-success',
    'radio.color.warning' => 'radio-warning',
    'radio.color.info' => 'radio-info',
    'radio.color.error' => 'radio-error',
    'radio.size.xs' => 'radio-xs',
    'radio.size.sm' => 'radio-sm',
    'radio.size.md' => 'radio-md',
    'radio.size.lg' => 'radio-lg',
    'radio.size.xl' => 'radio-xl',

    // Toggle — https://daisyui.com/components/toggle/
    'toggle.base' => 'toggle',
    'toggle.color.primary' => 'toggle-primary',
    'toggle.color.secondary' => 'toggle-secondary',
    'toggle.color.accent' => 'toggle-accent',
    'toggle.color.neutral' => 'toggle-neutral',
    'toggle.color.success' => 'toggle-success',
    'toggle.color.warning' => 'toggle-warning',
    'toggle.color.info' => 'toggle-info',
    'toggle.color.error' => 'toggle-error',
    'toggle.size.xs' => 'toggle-xs',
    'toggle.size.sm' => 'toggle-sm',
    'toggle.size.md' => 'toggle-md',
    'toggle.size.lg' => 'toggle-lg',
    'toggle.size.xl' => 'toggle-xl',

    // File input — https://daisyui.com/components/file-input/
    'fileInput.base' => 'file-input',
    'fileInput.appearance.ghost' => 'file-input-ghost',
    'fileInput.color.neutral' => 'file-input-neutral',
    'fileInput.color.primary' => 'file-input-primary',
    'fileInput.color.secondary' => 'file-input-secondary',
    'fileInput.color.accent' => 'file-input-accent',
    'fileInput.color.info' => 'file-input-info',
    'fileInput.color.success' => 'file-input-success',
    'fileInput.color.warning' => 'file-input-warning',
    'fileInput.color.error' => 'file-input-error',
    'fileInput.size.xs' => 'file-input-xs',
    'fileInput.size.sm' => 'file-input-sm',
    'fileInput.size.md' => 'file-input-md',
    'fileInput.size.lg' => 'file-input-lg',
    'fileInput.size.xl' => 'file-input-xl',

    // Range — https://daisyui.com/components/range/
    'range.base' => 'range',
    'range.color.neutral' => 'range-neutral',
    'range.color.primary' => 'range-primary',
    'range.color.secondary' => 'range-secondary',
    'range.color.accent' => 'range-accent',
    'range.color.success' => 'range-success',
    'range.color.warning' => 'range-warning',
    'range.color.info' => 'range-info',
    'range.color.error' => 'range-error',
    'range.size.xs' => 'range-xs',
    'range.size.sm' => 'range-sm',
    'range.size.md' => 'range-md',
    'range.size.lg' => 'range-lg',
    'range.size.xl' => 'range-xl',
    'range.direction.vertical' => 'range-vertical',

    // Rating — https://daisyui.com/components/rating/
    'rating.base' => 'rating',
    'rating.modifier.half' => 'rating-half',
    'rating.size.xs' => 'rating-xs',
    'rating.size.sm' => 'rating-sm',
    'rating.size.md' => 'rating-md',
    'rating.size.lg' => 'rating-lg',
    'rating.size.xl' => 'rating-xl',
    // rating-hidden goes on the first (clear) radio, not the wrapper, so it's a part
    'rating.part.clear' => 'rating-hidden',
    // plugin keys: the star inputs (daisyUI mask classes)
    'rating.part.item' => 'mask mask-star-2',
    'rating.part.halfStart' => 'mask-half-1',
    'rating.part.halfEnd' => 'mask-half-2',

    // Validator — https://daisyui.com/components/validator/
    'validator.base' => 'validator',
    'validator.part.hint' => 'validator-hint',

    // List — https://daisyui.com/components/list/
    'list.base' => 'list',
    'list.part.row' => 'list-row',
    'list.part.colGrow' => 'list-col-grow',
    'list.part.colWrap' => 'list-col-wrap',

    // Stat — https://daisyui.com/components/stat/
    'stats.base' => 'stats',
    'stats.direction.horizontal' => 'stats-horizontal',
    'stats.direction.vertical' => 'stats-vertical',
    'stats.part.stat' => 'stat',
    'stats.part.title' => 'stat-title',
    'stats.part.value' => 'stat-value',
    'stats.part.desc' => 'stat-desc',
    'stats.part.figure' => 'stat-figure',
    'stats.part.actions' => 'stat-actions',

    // Steps — https://daisyui.com/components/steps/
    'steps.base' => 'steps',
    'steps.direction.vertical' => 'steps-vertical',
    'steps.direction.horizontal' => 'steps-horizontal',
    'step.base' => 'step',
    'step.color.neutral' => 'step-neutral',
    'step.color.primary' => 'step-primary',
    'step.color.secondary' => 'step-secondary',
    'step.color.accent' => 'step-accent',
    'step.color.info' => 'step-info',
    'step.color.success' => 'step-success',
    'step.color.warning' => 'step-warning',
    'step.color.error' => 'step-error',
    'step.part.icon' => 'step-icon',

    // Tab — https://daisyui.com/components/tab/
    'tabs.base' => 'tabs',
    'tabs.appearance.box' => 'tabs-box',
    'tabs.appearance.border' => 'tabs-border',
    'tabs.appearance.lift' => 'tabs-lift',
    'tabs.size.xs' => 'tabs-xs',
    'tabs.size.sm' => 'tabs-sm',
    'tabs.size.md' => 'tabs-md',
    'tabs.size.lg' => 'tabs-lg',
    'tabs.size.xl' => 'tabs-xl',
    'tabs.placement.top' => 'tabs-top',
    'tabs.placement.bottom' => 'tabs-bottom',
    'tab.base' => 'tab',
    'tab.modifier.active' => 'tab-active',
    'tab.modifier.disabled' => 'tab-disabled',
    'tab.part.content' => 'tab-content',

    // Collapse — https://daisyui.com/components/collapse/
    'collapse.base' => 'collapse',
    'collapse.part.title' => 'collapse-title',
    'collapse.part.content' => 'collapse-content',
    'collapse.modifier.arrow' => 'collapse-arrow',
    'collapse.modifier.plus' => 'collapse-plus',
    'collapse.modifier.open' => 'collapse-open',
    'collapse.modifier.close' => 'collapse-close',

    // Timeline — https://daisyui.com/components/timeline/
    'timeline.base' => 'timeline',
    'timeline.part.start' => 'timeline-start',
    'timeline.part.middle' => 'timeline-middle',
    'timeline.part.end' => 'timeline-end',
    'timeline.part.box' => 'timeline-box',
    'timeline.modifier.snap-icon' => 'timeline-snap-icon',
    'timeline.modifier.compact' => 'timeline-compact',
    'timeline.direction.vertical' => 'timeline-vertical',
    'timeline.direction.horizontal' => 'timeline-horizontal',

    // Table — https://daisyui.com/components/table/
    'table.base' => 'table',
    'table.modifier.zebra' => 'table-zebra',
    'table.modifier.pin-rows' => 'table-pin-rows',
    'table.modifier.pin-cols' => 'table-pin-cols',
    'table.size.xs' => 'table-xs',
    'table.size.sm' => 'table-sm',
    'table.size.md' => 'table-md',
    'table.size.lg' => 'table-lg',
    'table.size.xl' => 'table-xl',
    // plugin key: the scroll wrapper the reference recommends
    'table.part.wrapper' => 'overflow-x-auto',

    // Swap — https://daisyui.com/components/swap/
    'swap.base' => 'swap',
    'swap.part.on' => 'swap-on',
    'swap.part.off' => 'swap-off',
    'swap.part.indeterminate' => 'swap-indeterminate',
    'swap.modifier.active' => 'swap-active',
    'swap.appearance.rotate' => 'swap-rotate',
    'swap.appearance.flip' => 'swap-flip',

    // Dropdown — https://daisyui.com/components/dropdown/
    'dropdown.base' => 'dropdown',
    'dropdown.part.content' => 'dropdown-content',
    'dropdown.placement.start' => 'dropdown-start',
    'dropdown.placement.center' => 'dropdown-center',
    'dropdown.placement.end' => 'dropdown-end',
    'dropdown.placement.top' => 'dropdown-top',
    'dropdown.placement.bottom' => 'dropdown-bottom',
    'dropdown.placement.left' => 'dropdown-left',
    'dropdown.placement.right' => 'dropdown-right',
    'dropdown.modifier.hover' => 'dropdown-hover',
    'dropdown.modifier.open' => 'dropdown-open',
    'dropdown.modifier.close' => 'dropdown-close',

    // Toast — https://daisyui.com/components/toast/
    'toast.base' => 'toast',
    'toast.placement.start' => 'toast-start',
    'toast.placement.center' => 'toast-center',
    'toast.placement.end' => 'toast-end',
    'toast.placement.top' => 'toast-top',
    'toast.placement.middle' => 'toast-middle',
    'toast.placement.bottom' => 'toast-bottom',

    // Modal — https://daisyui.com/components/modal/
    'modal.base' => 'modal',
    'modal.part.box' => 'modal-box',
    'modal.part.action' => 'modal-action',
    'modal.part.backdrop' => 'modal-backdrop',
    'modal.part.toggle' => 'modal-toggle',
    'modal.modifier.open' => 'modal-open',
    'modal.placement.top' => 'modal-top',
    'modal.placement.middle' => 'modal-middle',
    'modal.placement.bottom' => 'modal-bottom',
    'modal.placement.start' => 'modal-start',
    'modal.placement.end' => 'modal-end',

    // Theme controller — https://daisyui.com/components/theme-controller/
    'themeController.base' => 'theme-controller',
    // plugin key: the dropdown panel of a 3+ theme controller
    'themeController.part.menu' => 'menu bg-base-200 rounded-box w-52 p-2 shadow-sm',

    // Link — https://daisyui.com/components/link/
    'link.base' => 'link',
    'link.appearance.hover' => 'link-hover',
    'link.color.neutral' => 'link-neutral',
    'link.color.primary' => 'link-primary',
    'link.color.secondary' => 'link-secondary',
    'link.color.accent' => 'link-accent',
    'link.color.success' => 'link-success',
    'link.color.info' => 'link-info',
    'link.color.warning' => 'link-warning',
    'link.color.error' => 'link-error',

    // Menu — https://daisyui.com/components/menu/
    'menu.base' => 'menu',
    'menu.size.xs' => 'menu-xs',
    'menu.size.sm' => 'menu-sm',
    'menu.size.md' => 'menu-md',
    'menu.size.lg' => 'menu-lg',
    'menu.size.xl' => 'menu-xl',
    'menu.direction.vertical' => 'menu-vertical',
    'menu.direction.horizontal' => 'menu-horizontal',
    'menu.modifier.paged' => 'menu-paged',
    'menu.part.title' => 'menu-title',
    'menu.part.dropdown' => 'menu-dropdown',
    'menu.part.dropdownToggle' => 'menu-dropdown-toggle',
    'menu.modifier.dropdownShow' => 'menu-dropdown-show',
    'menuItem.modifier.active' => 'menu-active',
    'menuItem.modifier.disabled' => 'menu-disabled',
    'menuItem.modifier.focus' => 'menu-focus',

    // Navbar — https://daisyui.com/components/navbar/
    'navbar.base' => 'navbar',
    'navbar.part.start' => 'navbar-start',
    'navbar.part.center' => 'navbar-center',
    'navbar.part.end' => 'navbar-end',

    // Drawer — https://daisyui.com/components/drawer/
    'drawer.base' => 'drawer',
    'drawer.part.toggle' => 'drawer-toggle',
    'drawer.part.content' => 'drawer-content',
    'drawer.part.side' => 'drawer-side',
    'drawer.part.overlay' => 'drawer-overlay',
    'drawer.part.button' => 'drawer-button',
    'drawer.placement.end' => 'drawer-end',
    'drawer.modifier.open' => 'drawer-open',

    // Divider — https://daisyui.com/components/divider/
    'divider.base' => 'divider',
    'divider.color.neutral' => 'divider-neutral',
    'divider.color.primary' => 'divider-primary',
    'divider.color.secondary' => 'divider-secondary',
    'divider.color.accent' => 'divider-accent',
    'divider.color.success' => 'divider-success',
    'divider.color.warning' => 'divider-warning',
    'divider.color.info' => 'divider-info',
    'divider.color.error' => 'divider-error',
    'divider.direction.vertical' => 'divider-vertical',
    'divider.direction.horizontal' => 'divider-horizontal',
    'divider.placement.start' => 'divider-start',
    'divider.placement.end' => 'divider-end',

    // Footer — https://daisyui.com/components/footer/
    'footer.base' => 'footer',
    'footer.part.title' => 'footer-title',
    'footer.placement.center' => 'footer-center',
    'footer.direction.horizontal' => 'footer-horizontal',
    'footer.direction.vertical' => 'footer-vertical',

    // Dock — https://daisyui.com/components/dock/
    'dock.base' => 'dock',
    'dock.part.label' => 'dock-label',
    'dock.size.xs' => 'dock-xs',
    'dock.size.sm' => 'dock-sm',
    'dock.size.md' => 'dock-md',
    'dock.size.lg' => 'dock-lg',
    'dock.size.xl' => 'dock-xl',
    'dockItem.modifier.active' => 'dock-active',

    // Megamenu — https://daisyui.com/components/megamenu/
    'megamenu.base' => 'megamenu',
    'megamenu.part.active' => 'megamenu-active',
    'megamenu.modifier.wide' => 'megamenu-wide',
    'megamenu.modifier.full' => 'megamenu-full',
    'megamenu.direction.vertical' => 'megamenu-vertical',
    'megamenu.size.xs' => 'megamenu-xs',
    'megamenu.size.sm' => 'megamenu-sm',
    'megamenu.size.md' => 'megamenu-md',
    'megamenu.size.lg' => 'megamenu-lg',
    'megamenu.size.xl' => 'megamenu-xl',

    // Hero — https://daisyui.com/components/hero/
    'hero.base' => 'hero',
    'hero.part.content' => 'hero-content',
    'hero.part.overlay' => 'hero-overlay',

    // Indicator — https://daisyui.com/components/indicator/
    'indicator.base' => 'indicator',
    'indicatorItem.base' => 'indicator-item',
    'indicatorItem.placement.start' => 'indicator-start',
    'indicatorItem.placement.center' => 'indicator-center',
    'indicatorItem.placement.end' => 'indicator-end',
    'indicatorItem.placement.top' => 'indicator-top',
    'indicatorItem.placement.middle' => 'indicator-middle',
    'indicatorItem.placement.bottom' => 'indicator-bottom',

    // Mask — https://daisyui.com/components/mask/
    'mask.base' => 'mask',
    'mask.appearance.squircle' => 'mask-squircle',
    'mask.appearance.heart' => 'mask-heart',
    'mask.appearance.hexagon' => 'mask-hexagon',
    'mask.appearance.hexagon-2' => 'mask-hexagon-2',
    'mask.appearance.decagon' => 'mask-decagon',
    'mask.appearance.pentagon' => 'mask-pentagon',
    'mask.appearance.diamond' => 'mask-diamond',
    'mask.appearance.circle' => 'mask-circle',
    'mask.appearance.star' => 'mask-star',
    'mask.appearance.star-2' => 'mask-star-2',
    'mask.appearance.triangle' => 'mask-triangle',
    'mask.appearance.triangle-2' => 'mask-triangle-2',
    'mask.appearance.triangle-3' => 'mask-triangle-3',
    'mask.appearance.triangle-4' => 'mask-triangle-4',
    'mask.modifier.half-1' => 'mask-half-1',
    'mask.modifier.half-2' => 'mask-half-2',

    // Stack — https://daisyui.com/components/stack/
    'stack.base' => 'stack',
    'stack.modifier.top' => 'stack-top',
    'stack.modifier.bottom' => 'stack-bottom',
    'stack.modifier.start' => 'stack-start',
    'stack.modifier.end' => 'stack-end',

    // Skeleton — https://daisyui.com/components/skeleton/
    'skeleton.base' => 'skeleton',
    'skeleton.modifier.text' => 'skeleton-text',

    // Carousel — https://daisyui.com/components/carousel/
    'carousel.base' => 'carousel',
    'carousel.part.item' => 'carousel-item',
    'carousel.modifier.start' => 'carousel-start',
    'carousel.modifier.center' => 'carousel-center',
    'carousel.modifier.end' => 'carousel-end',
    'carousel.direction.horizontal' => 'carousel-horizontal',
    'carousel.direction.vertical' => 'carousel-vertical',

    // Chat — https://daisyui.com/components/chat/
    'chat.base' => 'chat',
    'chat.placement.start' => 'chat-start',
    'chat.placement.end' => 'chat-end',
    'chat.part.image' => 'chat-image',
    'chat.part.header' => 'chat-header',
    'chat.part.footer' => 'chat-footer',
    'chatBubble.base' => 'chat-bubble',
    'chatBubble.color.neutral' => 'chat-bubble-neutral',
    'chatBubble.color.primary' => 'chat-bubble-primary',
    'chatBubble.color.secondary' => 'chat-bubble-secondary',
    'chatBubble.color.accent' => 'chat-bubble-accent',
    'chatBubble.color.info' => 'chat-bubble-info',
    'chatBubble.color.success' => 'chat-bubble-success',
    'chatBubble.color.warning' => 'chat-bubble-warning',
    'chatBubble.color.error' => 'chat-bubble-error',

    // Countdown — https://daisyui.com/components/countdown/
    'countdown.base' => 'countdown',

    // Diff — https://daisyui.com/components/diff/
    'diff.base' => 'diff',
    'diff.part.item1' => 'diff-item-1',
    'diff.part.item2' => 'diff-item-2',
    'diff.part.resizer' => 'diff-resizer',

    // Hover 3D — https://daisyui.com/components/hover-3d/
    'hover3d.base' => 'hover-3d',

    // Hover gallery — https://daisyui.com/components/hover-gallery/
    'hoverGallery.base' => 'hover-gallery',

    // Text rotate — https://daisyui.com/components/text-rotate/
    'textRotate.base' => 'text-rotate',

    // Aura — https://daisyui.com/components/aura/
    'aura.base' => 'aura',
    'aura.appearance.dual' => 'aura-dual',
    'aura.appearance.rainbow' => 'aura-rainbow',
    'aura.appearance.holo' => 'aura-holo',
    'aura.appearance.gold' => 'aura-gold',
    'aura.appearance.silver' => 'aura-silver',
    'aura.appearance.glow' => 'aura-glow',
    'aura.size.xs' => 'aura-xs',
    'aura.size.sm' => 'aura-sm',
    'aura.size.md' => 'aura-md',
    'aura.size.lg' => 'aura-lg',
    'aura.size.xl' => 'aura-xl',

    // FAB — https://daisyui.com/components/fab/
    'fab.base' => 'fab',
    'fab.part.close' => 'fab-close',
    'fab.part.mainAction' => 'fab-main-action',
    'fab.modifier.flower' => 'fab-flower',

    // Browser mockup — https://daisyui.com/components/mockup-browser/
    'browser.base' => 'mockup-browser',
    'browser.part.toolbar' => 'mockup-browser-toolbar',

    // Code mockup — https://daisyui.com/components/mockup-code/
    'code.base' => 'mockup-code',

    // Phone mockup — https://daisyui.com/components/mockup-phone/
    'phone.base' => 'mockup-phone',
    'phone.part.camera' => 'mockup-phone-camera',
    'phone.part.display' => 'mockup-phone-display',

    // Window mockup — https://daisyui.com/components/mockup-window/
    'window.base' => 'mockup-window',

    // Filter — https://daisyui.com/components/filter/
    'filter.base' => 'filter',
    'filter.part.reset' => 'filter-reset',

    // OTP — https://daisyui.com/components/otp/
    'otp.base' => 'otp',
    'otp.modifier.joined' => 'otp-joined',
    'otp.size.xs' => 'otp-xs',
    'otp.size.sm' => 'otp-sm',
    'otp.size.md' => 'otp-md',
    'otp.size.lg' => 'otp-lg',
    'otp.size.xl' => 'otp-xl',
    'otp.color.neutral' => 'otp-neutral',
    'otp.color.primary' => 'otp-primary',
    'otp.color.secondary' => 'otp-secondary',
    'otp.color.accent' => 'otp-accent',
    'otp.color.info' => 'otp-info',
    'otp.color.success' => 'otp-success',
    'otp.color.warning' => 'otp-warning',
    'otp.color.error' => 'otp-error',

    // Flash messages (plugin keys): the dismiss toggle and the alert it hides (Tailwind peer utilities)
    'flash.part.dismissToggle' => 'peer sr-only',
    'flash.part.dismissible' => 'peer-checked:hidden peer-focus-visible:outline-2',
];
