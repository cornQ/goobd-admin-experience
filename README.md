# goobd Admin Experience for YOURLS

goobd Admin Experience transforms the native [YOURLS](https://github.com/YOURLS/YOURLS)  admin panel into a modern, responsive interface for desktop and mobile while preserving the core YOURLS workflow and functionality.

Current version: **1.25.5**

## Features

- **Fully Redesigned Admin Panel**
  - Modernized YOURLS admin experience while preserving native functionality.

- **Responsive Desktop & Mobile Experience**
  - Responsive Dashboard, Login, Statistics, Plugins, and Tools pages.
  - Optimized layouts and controls for both desktop and mobile devices.

- **Responsive Link & Plugin Management**
  - Improved link management table for desktop.
  - Mobile-friendly link cards and actions.
  - Responsive plugin management interface.
  - Direct short URL copy action.

- **30-Day Remember Me**
  - Optional 30-day persistent login support directly from the YOURLS login page.

- **Built-in API Secret Reset**
  - Reset the secret signature token used for passwordless API requests directly from the Tools page.
  - Securely rotates the underlying secret and invalidates previous signatures.

- **Configurable Branding**
  - Customize the site name, header identity, tagline, and footer.
  - Sanitized HTML support for safe branding customization.
  - Localization and translation support.

- **Redesigned Navigation**
  - Modernized admin navigation while preserving native and plugin-added menu items.
  - Responsive hamburger navigation for mobile devices.

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

3. Sign in to your YOURLS admin area.
4. Open **Manage Plugins**.
5. Activate **goobd Admin Experience for YOURLS**.
6. Open **Manage Plugins → Admin Experience Settings** to configure branding.
7. Hard-refresh your browser after installation or an update to ensure the latest versioned stylesheet is loaded.

## Branding Settings

The plugin includes a native YOURLS settings page for customizing the admin interface without modifying the plugin files.

Available settings include:

- **Site name** — Plain text used in page titles, metadata, and accessibility labels.
- **Header identity HTML** — Safe inline HTML displayed as the main header identity.
- **Header tagline HTML** — Optional inline HTML displayed below the header identity.
- **Footer HTML** — Optional footer content with restricted link support.

### Dynamic Placeholders

The following placeholders can be used in supported branding fields:

```text
{year}
{site_url}
{admin_url}
```

Resetting the branding settings restores the default `goobd by CORNQ` identity.

## API Secret Reset

The YOURLS Tools page includes a built-in option for rotating the secret used for passwordless API signatures.

This allows an administrator to reset the API secret without manually editing the YOURLS configuration file.

The reset process is protected by authentication, nonce verification, and explicit confirmation.

> **Important:** Resetting the API secret invalidates existing passwordless API signatures and active admin sessions. You will be required to sign in again after the reset.

## HTML Security

Configurable branding fields use a restricted HTML allowlist.

Header identity and tagline fields support:

```html
<span> <strong> <em> <small> <br>
```

The footer additionally supports restricted `<a>` elements.

The sanitizer removes or rejects potentially unsafe content including:

- Scripts and embedded content
- Forms
- Inline styles
- Event-handler attributes
- Unsafe URL protocols

Links using `target="_blank"` automatically receive `noopener noreferrer` protection.

## Translations

The plugin supports optional gettext translations.

Text domain:

```text
goobd-admin-experience
```

Place compiled translation files inside the `languages/` directory using the following filename format:

```text
goobd-admin-experience-LOCALE.mo
```

For example:

```text
goobd-admin-experience-bn_BD.mo
```

The `languages/` directory is optional when no translation is installed.

## Testing

Basic regression tests are included for contributors and maintainers.

Run the following commands from the plugin directory:

```bash
php -l plugin.php
php tests/branding-sanitizer-test.php
```

For interface testing, the Dashboard, filtering, pagination, link actions, Statistics, sharing, Tools, plugin management, login interface, header, and footer should be verified on both desktop and mobile viewports.

## Updating

1. Back up the existing plugin directory.
2. Replace the plugin files with the new version.
3. Hard-refresh your browser.
4. Verify the main Dashboard and other customized admin pages.

Saved branding settings remain in the YOURLS options table when the plugin is deactivated.

## Compatibility

goobd Admin Experience enhances the existing YOURLS interface through hooks and progressive enhancement rather than replacing the underlying YOURLS functionality.

The plugin does not intentionally modify YOURLS core files, short-link data, or database tables.

Native YOURLS functionality including filtering, sorting, pagination, AJAX editing, deletion, sharing, statistics, nonces, and plugin-added navigation is preserved.

## Support

For bug reports, compatibility issues, or feature requests, please open an issue on GitHub:

[Open an Issue](https://github.com/cornQ/goobd-admin-experience/issues)

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for the complete release history.

## License

Released under the [MIT License](LICENSE).

Developed by [CORNQ](https://cornq.com/).