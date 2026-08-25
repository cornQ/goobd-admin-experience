# Changelog

## v1.25.5

### Added

- Added a native **Admin Experience Settings** page under Manage Plugins.
- Added configurable Site name, Header identity, Header tagline, and Footer settings.
- Added dynamic branding placeholders:
  - `{year}`
  - `{site_url}`
  - `{admin_url}`
- Added safe inline HTML support with restricted tags and protected links.
- Added a sanitized branding preview and reset-to-default functionality.
- Added optional gettext translation support using the `goobd-admin-experience` text domain.
- Added regression tests for unsafe protocols, event attributes, malformed markup, protected links, and optional empty fields.
- Added public installation, configuration, translation, update, and compatibility documentation.
- Added MIT licensing and public repository metadata.

### Changed

- Finalized the public plugin identity as `goobd Admin Experience for YOURLS`.
- Applied configurable branding across the shared header, tagline, footer, page titles, metadata, and accessibility labels.
- Improved branding settings persistence with database read-back verification.
- Improved handling and wrapping of long custom header and tagline content.
- Improved desktop login layout to keep the header, login area, and footer within the viewport when possible.
- Improved short-height and mobile-keyboard behavior while preserving natural scrolling when required.
- Preserved the default `goo.bd by CORNQ` identity when no custom branding is configured.

### Fixed

- Fixed unnecessary vertical scrolling on login pages when the complete interface fits within the viewport.
- Fixed unnecessary page-level horizontal scrolling across login and admin pages.
- Fixed legacy YOURLS body spacing that could extend the login page beyond the viewport.
- Fixed malformed allowed HTML rendering by balancing supported inline tags.
- Fixed settings success notices so they are only shown after the desired values are successfully persisted.
- Fixed long branding content potentially widening desktop or mobile layouts.

---

## v1.24.0

### Added

- Added a responsive **Back to links** control to Statistics pages.
- Added preservation of the previous Dashboard search, sort, filter, and pagination state when returning from Statistics.
- Added a calendar affordance to the native Created date filter.
- Added responsive success feedback after successful URL deletion.

### Changed

- Renamed the native `Admin interface` navigation label to `Dashboard` while preserving its original destination and behavior.
- Improved Filter Links and mobile Statistics dropdown presentation.
- Added consistent dropdown chevrons while preserving native select behavior.
- Improved mobile Search and Clear actions with equal-width, touch-friendly controls.
- Improved the URL creation panel presentation.
- Improved Search button hover readability.
- Refined URL deletion feedback into a compact, accessible notification.

### Fixed

- Fixed mobile Filter Links and Statistics dropdowns extending beyond their containers.
- Fixed delete-success feedback appearing incorrectly for canceled or failed deletions.
- Fixed Dashboard return navigation so restored locations remain restricted to the same origin.
- Fixed mobile filter action spacing and containment.

---

## v1.23.0

### Added

- Added a built-in Tools-page control for rotating the secret used by YOURLS passwordless API signatures.
- Added current-user authentication and YOURLS nonce verification for secret rotation.
- Added explicit confirmation before changing the API secret.
- Added cryptographically secure generation of a new `YOURLS_COOKIEKEY`.
- Added atomic configuration-file replacement.
- Added automatic invalidation of previous passwordless API signatures, active admin sessions, and nonces.
- Added responsive success, error, permission, and login-notice states.

### Changed

- Redesigned the native Tools page into responsive **Bookmarklets**, **Prefix-n-Shorten**, and **API** sections.
- Reworked the API signature area into a responsive overview with token information and reset controls.
- Improved bookmarklet presentation on mobile.
- Improved API examples, token presentation, social actions, and usage guidance.
- Improved handling of long API values and code examples.

### Fixed

- Fixed stale configuration data after API secret rotation by invalidating the active PHP OPcache entry.
- Fixed stale-key login nonces causing `Unauthorized action or expired link` errors after secret reset.
- Fixed Tools-page layouts on smaller screens while preserving native functionality.

---

## v1.21.0

### Added

- Added a responsive overview card for the link title, short URL, and destination URL.
- Added a full-width Statistics section selector for mobile devices.
- Added synchronization between the mobile selector and native Statistics tabs.
- Added responsive layouts for Statistics, Location, and Sources content.
- Added dynamic mobile viewport support.

### Changed

- Redesigned the native Statistics interface for desktop and mobile.
- Reworked Statistics tabs into a responsive navigation experience.
- Improved range controls, historical totals, detail lists, referrers, countries, charts, and maps.
- Restyled the Statistics Share interface while preserving native fields and social actions.
- Improved footer behavior on short admin and Statistics pages.
- Converted suitable Statistics content into stacked mobile-friendly layouts.

### Fixed

- Fixed footer positioning on short pages without using fixed positioning.
- Fixed potential content overlap caused by footer placement.
- Preserved native IDs, hashes, chart containers, click handlers, and no-JavaScript fallback behavior.

---

## v1.18.0

### Added

- Added installed and activated plugin totals to the Plugins page.
- Added an **All**, **Activated**, and **Deactivated** plugin filter.
- Added responsive mobile plugin cards.
- Added a responsive URL deletion confirmation interface.

### Changed

- Redesigned the Manage Plugins page for desktop and mobile.
- Improved plugin description wrapping and column sizing.
- Restyled Activate and Deactivate actions as clearer buttons.
- Improved Author and Action presentation on mobile.
- Improved plugin recovery and additional-plugin information panels.
- Redesigned the URL deletion dialog with content-sized dimensions.
- Improved URL detail hierarchy, Delete and Cancel actions, backdrop, wrapping, and touch targets.
- Reserved danger styling specifically for destructive actions.

### Fixed

- Fixed plugin descriptions overflowing desktop layouts.
- Fixed the delete dialog stretching unnecessarily toward the full viewport height.
- Fixed unnecessary scrolling inside the delete confirmation dialog.
- Fixed long URL and title handling inside deletion details.
- Preserved native YOURLS confirmation, cancellation, activation, and deletion handlers.

---

## v1.14.0

### Added

- Added responsive mobile navigation with a hamburger menu.
- Added accessible expanded-state handling.
- Added outside-click, Escape-key, keyboard, and resize behavior.
- Added an accessible Manage Plugins navigation dropdown.
- Added an **All Plugins** destination while retaining dynamically registered plugin submenu items.
- Added a compact Dashboard summary for displayed URLs, total links, and total clicks.

### Changed

- Redesigned the native YOURLS admin navigation.
- Improved active, hover, focus, submenu, account, and logout states.
- Rebuilt mobile navigation as sequential full-width menu rows.
- Improved plugin submenu presentation on mobile.
- Improved Dashboard summary presentation for desktop and mobile.

### Fixed

- Fixed active navigation labels becoming unreadable against the active background.
- Fixed mobile navigation layout and touch-target consistency.
- Preserved 2FA, Help, plugin submenu, and other hook-added navigation links.
- Preserved native Dashboard counters and AJAX increment behavior.

---

## v1.10.0

### Added

- Added a direct **Copy** action for each short URL on desktop and mobile.
- Added visible copy-success feedback.
- Added a legacy clipboard fallback for browsers without Clipboard API support.
- Added improved mobile Search, Sort, Rows, Clicks, and Created filters.
- Added automatic scrolling to the Dashboard Share section when opened.

### Changed

- Redesigned link management for desktop and mobile.
- Improved mobile URL cards and action presentation.
- Moved the native Filter Links form above the URL table.
- Moved native pagination below the URL table.
- Improved filter layouts and touch targets on mobile.
- Rebalanced desktop link-management columns to keep all actions accessible.
- Redesigned the Dashboard Share interface.
- Improved social-sharing icon sizing and alignment.

### Fixed

- Fixed mobile three-dot action menus being clipped by URL cards.
- Fixed unnecessary table-footer framing after filters and pagination were relocated.
- Fixed mobile filter layout at narrow viewport widths.
- Preserved native filter values, query strings, Search, Clear, and pagination behavior.
- Preserved native Stats, Share, Edit, Delete, and social-sharing functionality.

---

## v1.0.0

### Added

- Initial release of **goobd Admin Experience for YOURLS**.
- Added the foundation for a redesigned YOURLS administration experience.
- Added responsive styling for desktop and mobile environments.
- Added the initial responsive Dashboard and link-management experience.
- Added the foundation for responsive Login, Statistics, Plugins, and Tools interfaces.
- Built the plugin around native YOURLS hooks and progressive enhancement.

### Changed

- Modernized the native YOURLS admin presentation while preserving the underlying workflow.

### Compatibility

- Designed to enhance YOURLS without modifying core files.
- Preserved native YOURLS functionality as the foundation for subsequent releases.