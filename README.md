# goo.bd Admin Experience for YOURLS

A configurable, responsive admin and login experience for YOURLS. The plugin preserves native YOURLS behavior while improving link management, statistics, sharing, tools, plugin management, mobile layouts, and branding controls.

Current version: **1.25.5**

## Features

- Responsive YOURLS Dashboard and login interface
- Mobile-friendly URL cards and compact action menus
- Improved filtering, pagination, sharing, statistics, tools, and plugin pages
- Optional 30-day Remember Me login
- Configurable header identity, tagline, site name, and footer
- Safe inline HTML support for branding fields
- Optional gettext translations
- No YOURLS core-file modifications

## Requirements

- YOURLS 1.10.4 or newer
- PHP 8.1 or newer
- A private YOURLS admin installation is strongly recommended

## Installation

1. Download or clone this repository.
2. Place the plugin directory at:

   ```text
   user/plugins/goobd-admin-experience/
   ```

3. Sign in to the YOURLS admin area.
4. Open **Manage Plugins**.
5. Activate **goo.bd Admin Experience for YOURLS**.
6. Open **Manage Plugins → Admin Experience Settings** to configure branding.
7. Hard-refresh the browser after an update so the versioned stylesheet reloads.

## Production deployment

The runtime plugin requires:

```text
goobd-admin-experience/
├── plugin.php
├── assets/
│   └── admin.css
└── languages/        # Only required when translations are installed
```

Files such as `tests/`, `.gitignore`, and repository documentation are useful on GitHub but are not required on the production server.

## Branding settings

The settings page provides:

- **Site name:** required plain text used in titles, metadata, and accessibility labels
- **Header identity HTML:** required safe inline HTML displayed in the main brand link
- **Header tagline HTML:** optional safe inline HTML
- **Footer HTML:** optional safe inline HTML with restricted links

Supported dynamic placeholders:

- `{year}`
- `{site_url}`
- `{admin_url}`

Resetting the settings restores the default `goo.bd by CORNQ` identity.

## HTML security

Header and tagline fields allow these inline elements:

```html
<span> <strong> <em> <small> <br>
```

The footer additionally allows restricted `<a>` elements. The sanitizer removes scripts, embedded content, forms, inline styles, event handlers, and unsafe URL protocols. Links using `target="_blank"` receive `noopener noreferrer` protection.

## Translations

The gettext text domain is:

```text
goobd-admin-experience
```

Place compiled translations in `languages/` using this filename pattern:

```text
goobd-admin-experience-LOCALE.mo
```

For example:

```text
goobd-admin-experience-bn_BD.mo
```

The `languages/` directory is optional when no translation is installed.

## Testing

Do not upload `tests/` to the production server. Run tests locally, on staging, or in CI:

```bash
php -l plugin.php
php tests/branding-sanitizer-test.php
```

Before deployment, verify the Dashboard, filtering, pagination, row actions, statistics, sharing, tools, plugin management, login, header, and footer on desktop and at 412px, 390px, and 360px mobile widths.

## Updating

1. Back up the existing plugin directory.
2. Replace the plugin files with the new version.
3. Hard-refresh the browser.
4. Verify the main Dashboard and statistics/share pages on desktop and mobile.

Saved branding settings remain in the YOURLS options table when the plugin is deactivated.

## Compatibility and safety

This plugin uses YOURLS hooks and progressive enhancement. It does not intentionally modify YOURLS core files, short-link data, or database tables. Native actions, nonces, filtering, sorting, pagination, AJAX editing, deletion, sharing, and statistics remain in place.

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for release history.

## License

Released under the [MIT License](LICENSE).

Developed by [CORNQ](https://cornq.com/).
