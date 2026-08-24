# Changelog

## v1.24.1

### Delete notification refinement

- Replaced the decorative green success pill with a compact neutral notification.
- Removed the prominent checkmark, tinted background, large radius, and heavy shadow.
- Shortened the message and display duration while retaining accessible live feedback.

## v1.24.0

### Dashboard navigation and feedback

- Renamed the native `Admin interface` menu label to `Dashboard` while preserving its URL, menu key, and active state.
- Fixed the Search button hover state with a light background and dark readable label.
- Added a responsive success toast after the native AJAX delete flow removes a URL row.
- Avoided success feedback for canceled or failed deletions.

## v1.23.2

### Tools API overview layout

- Reworked the signature area into a balanced two-column desktop overview.
- Grouped the native API introduction, current token, and usage note on the left.
- Positioned the reset controls on the right to use previously empty space.
- Kept the detailed API examples full width and restored a single-column layout below 900px.

## v1.23.1

### Signature reset login fix

- Invalidated the active config file in PHP OPcache immediately after rotating `YOURLS_COOKIEKEY`.
- Prevented a stale-key login nonce from producing an `Unauthorized action or expired link` error after reset.
- Retained the clean logout, re-login, and Tools-page success flow.

## v1.23.0

### Tools API signature reset

- Added a Tools-page control for rotating the secret API signature token without editing YOURLS core.
- Protected the reset with current-user authentication, a YOURLS nonce, and explicit impact confirmation.
- Replaced exactly one literal `YOURLS_COOKIEKEY` using a cryptographically secure random value and atomic config-file swap.
- Invalidated old API signatures, admin sessions, and nonces, then required a fresh login before showing the new token.
- Added responsive reset, success, permission-error, and login-notice states without exposing secret values.

## v1.22.0

### Tools page

- Grouped the native Tools content into responsive Bookmarklets, Prefix-n-Shorten, and API sections.
- Preserved all draggable bookmarklet links and their original javascript destinations.
- Reworked the bookmarklet comparison table into labeled mobile cards.
- Improved social actions, guidance notes, token presentation, and API code examples.
- Added controlled overflow for long values and scoped every style to `body.tools`.

## v1.21.1

### Mobile statistics navigation

- Added a full-width section dropdown for mobile statistics pages.
- Connected each dropdown option to its original native tab anchor.
- Synchronized dropdown state with tab clicks and the initially selected section.
- Preserved the desktop tab bar and no-JavaScript fallback.

## v1.21.0

### Statistics page

- Added a responsive overview card for the title, short URL, and destination URL.
- Reworked the native statistics tabs into a branded, mobile-scrollable navigation row.
- Converted statistics, location, and sources tables into balanced desktop panels and stacked mobile cards.
- Improved range controls, historical totals, detail lists, referrers, countries, charts, and maps.
- Restyled the Share tab while preserving its native fields and social actions.
- Scoped every change to `body.infos` and retained all native IDs, hashes, chart containers, and click handlers.

## v1.20.0

### Sticky footer

- Anchored the footer to the viewport bottom on short admin and statistics pages.
- Used a flex page shell instead of fixed positioning, preventing content overlap.
- Kept long dashboard, plugins, tools, login, and statistics content in normal document flow.
- Added dynamic mobile viewport support.

## v1.19.2

### Delete detail panel

- Removed the decorative red accent border from the URL detail panel.
- Added a neutral border and subtle separators between detail rows.
- Reserved danger emphasis for the destructive Delete button.

## v1.19.1

### Delete dialog height

- Fixed the delete dialog stretching to nearly the full viewport height.
- Switched the container to content-sized height with an explicit zero minimum.
- Added reliable vertical centering and dynamic viewport limits on desktop and mobile.

## v1.19.0

### Delete confirmation dialog

- Replaced the native fixed-size delete dialog with a responsive auto-height panel.
- Removed unnecessary scrollbars while retaining bounded scrolling for exceptionally long content.
- Improved the title, URL detail hierarchy, danger action, Cancel action, and backdrop.
- Added mobile-safe dimensions, wrapping, and touch-friendly controls.
- Preserved native YOURLS confirmation and cancellation handlers.

## v1.18.1

### Plugin actions

- Restyled Activate and Deactivate links as clear, high-contrast buttons.
- Moved the mobile Action label to the left and its button to the right.
- Separated Author and Action into distinct full-width mobile rows.

## v1.18.0

### Plugins page

- Added a responsive summary toolbar with installed/activated totals on the left.
- Replaced the native icon toggle with an All, Activated, and Deactivated dropdown on the right.
- Fixed desktop description overflow with explicit column sizing and safe text wrapping.
- Rebuilt the mobile plugin listing as scoped, full-width cards with clear content hierarchy.
- Improved activation actions and the recovery/more-plugins information panels.
- Kept all styles under `body.plugins` so the URL-management table is not affected.

## v1.17.0

### Dashboard summary

- Combined the displayed URL range and overall totals into one compact summary bar.
- Positioned range information on the left and link/click totals on the right.
- Preserved native counter elements, filtered click totals, and AJAX increment classes.
- Added a compact two-column mobile presentation.

## v1.16.1

### Mobile navigation listing

- Replaced the mobile navigation grid with sequential full-width menu rows.
- Displayed plugin dropdown destinations as full-width stacked rows.
- Kept active, account, hamburger, and touch-target behavior intact.

## v1.16.0

### Mobile navigation

- Added a hamburger Menu toggle at 700px and below.
- Added accessible expanded state, outside-click, Escape, and resize behavior.
- Preserved the Manage Plugins dropdown and all native/plugin navigation links.
- Kept the uncollapsed native menu as the no-JavaScript fallback.

## v1.15.0

### Plugin navigation dropdown

- Converted Manage Plugins into an accessible dropdown trigger.
- Added All Plugins using the original native plugins.php destination.
- Retained dynamically registered plugin submenu items such as 2FA Setup.
- Added outside-click, Escape, keyboard, desktop popover, and mobile expansion behavior.

## v1.14.1

### Active navigation

- Fixed the active menu label disappearing against its Deep Navy background.
- Locked active link text rendering across normal, visited, hover, and focus states.

## v1.14.0

### Admin navigation

- Restyled the native YOURLS menu as a compact CORNQ navigation card.
- Added clear active, hover, focus, submenu, and logout treatments.
- Added a responsive mobile layout with a separate account row.
- Preserved 2FA, plugin submenu, Help, and hook-added navigation links.

## v1.13.0

### Copy action

- Added a Copy action for each short URL row on desktop and mobile.
- Copies the row's current short URL with visible success feedback.
- Added a legacy clipboard fallback for browsers without Clipboard API access.
- Rebalanced desktop table columns to keep all five actions inside the table.
- Preserved native Stats, Share, Edit, and Delete behavior.

## v1.12.0

### Desktop filters

- Moved the native Filter Links form above the URL table.
- Hid the empty table footer after the filter and pagination nodes are relocated.
- Added a balanced desktop grid aligned with the mobile filter hierarchy.
- Preserved all native controls, values, query strings, Search, and Clear behavior.

## v1.11.0

### Mobile filters

- Preserved the native Search and Clear controls during filter enhancement.
- Improved Search, Sort, Rows, Clicks, and Created field grouping on mobile.
- Increased mobile filter controls to a 44px touch target.
- Removed legacy table-footer framing around the mobile filter card.
- Added a clean single-column layout for viewports at 420px and below.

## v1.10.2

### Pagination

- Moved the native pagination block below the URL table.
- Preserved the original YOURLS pagination links and query-string behavior.
- Added responsive bottom alignment for desktop and mobile layouts.

## v1.10.1

### Share interface

- Constrained the native Twitter and Facebook SVG backgrounds to 16px.
- Aligned both social icons consistently inside the themed share buttons.
- Preserved the native YOURLS social-sharing links and behavior.

## v1.10.0

### Share experience

- Added automatic scrolling to the dashboard Share section after it opens.
- Restyled the dashboard Share section with scoped CORNQ components.
- Kept statistics and information-page share layouts isolated.

## v1.9.1

### Mobile actions

- Prevented the three-dot action menu from being clipped by URL cards.
