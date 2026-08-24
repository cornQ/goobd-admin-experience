goo.bd Admin Experience for YOURLS

Current version: 1.25.5

Overview
--------

A configurable, responsive admin and login experience for YOURLS. It keeps native YOURLS behavior intact while adding mobile-friendly link management, statistics and tools layouts, optional Remember Me support, and safe branding controls.

Requirements
------------

- YOURLS 1.10.4 or newer
- PHP 8.1 or newer, matching the YOURLS 1.10.4 requirement
- A private YOURLS admin installation is strongly recommended

Clean installation
------------------

1. Copy this repository directory to `user/plugins/goobd-admin-experience/` inside YOURLS.
2. Sign in to YOURLS and open Manage Plugins.
3. Activate `goo.bd Admin Experience for YOURLS`.
4. Open Manage Plugins > Admin Experience Settings to configure the branding.
5. Hard-refresh the browser after an update so the versioned stylesheet is reloaded.

Branding configuration
----------------------

- Site name is plain text and is required.
- Header identity supports safe inline HTML and is required.
- Header tagline supports safe inline HTML and may be left empty.
- Footer supports safe inline HTML and safe links, and may be left empty.
- Available placeholders are `{year}`, `{site_url}`, and `{admin_url}`.
- Reset defaults restores the original `goo.bd by CORNQ` identity.

HTML security
-------------

Allowed header and tagline tags are `span`, `strong`, `em`, `small`, and `br`. The footer additionally supports `a` with restricted attributes and safe protocols. Event handlers, inline styles, embedded content, forms, and unsafe link protocols are removed. Allowed tags are balanced before rendering.

Translations
------------

The branding settings interface uses the `goobd-admin-experience` text domain. Place compiled translations in `languages/` using the filename `goobd-admin-experience-LOCALE.mo`, for example `goobd-admin-experience-bn_BD.mo`.

Updates and deactivation
------------------------

Back up the current plugin directory before updating, replace the plugin files, and hard-refresh the browser. Deactivating the plugin leaves saved branding settings in the YOURLS options table so a later reactivation restores them.

License
-------

Released under the MIT License. See `LICENSE`.

Release notes
-------------

v1.25.5 login viewport containment:
- Gives the desktop login flex layout a definite dynamic viewport height
- Clears the legacy YOURLS body top padding that extended the document beyond that viewport height
- Keeps the header, flexible login area, and footer inside one screen when their content fits
- Restores auto-height document flow below 721px viewport height for extra fields, short screens, and mobile keyboards

v1.25.4 login viewport-height fix:
- Removes the unnecessary vertical scrollbar when the login content fits in the viewport
- Allows the login wrapper and main area to shrink within the available flex height
- Removes the generic desktop footer top margin on login pages only
- Preserves natural vertical scrolling on short-height and mobile screens when content genuinely overflows

v1.25.3 shared-layout overflow fix:
- Removes the unnecessary page-level horizontal scrollbar on login and admin pages
- Relocates the shared header to the body layout and sizes it against the available width instead of `100vw`
- Preserves scoped horizontal scrolling inside native statistics components

v1.25.2 plugin identity consistency:
- Renames the public plugin to goo.bd Admin Experience for YOURLS
- Aligns the gettext text domain and locale filenames with the `goobd-admin-experience` plugin slug
- Keeps CORNQ as the author and default brand attribution

v1.25.1 quality and public-release fixes:
- Verifies saved branding through a database read-back before showing success
- Shows an error notice when branding settings cannot be persisted
- Allows intentionally empty tagline and footer content
- Balances malformed allowed HTML tags before rendering
- Prevents long custom header and tagline content from widening the viewport
- Adds translation loading and localizes the branding settings interface
- Adds sanitizer regression coverage, public installation documentation, and an MIT license

v1.25.0 configurable branding settings:

Configurable branding settings:
- Registers Admin Experience Settings as a native sub-page under Manage Plugins
- Adds responsive controls for Site name, Header identity HTML, Header tagline HTML, and Footer HTML
- Uses YOURLS options storage with the existing goo.bd by CORNQ identity as a backward-compatible default
- Applies the configured site name to browser titles, application metadata, and accessibility labels
- Supports {year}, {site_url}, and {admin_url} placeholders in branding HTML
- Uses nonce-protected POST handling followed by a clean redirect after save or reset
- Includes a sanitized saved preview and a Reset defaults action

Safe HTML support:
- Allows span, strong, em, small, and br in header/tagline fields
- Allows the same inline tags plus safe links in the footer field
- Removes scripts, embedded content, forms, inline event handlers, styles, and unsafe link protocols
- Automatically adds noopener noreferrer protection to footer links opening in a new tab
- Limits field sizes and sanitizes again when settings are read
- Uses a plugin-owned compatibility sanitizer so the feature works on the project's YOURLS 1.10.4 baseline

Public-release foundation:
- Uses a public-facing YOURLS Admin Experience plugin name
- Links plugin metadata to the GitHub repository and declares a text domain
- Keeps the established goobd_ae internal prefixes and option key for upgrade compatibility
- Leaves installations without saved settings visually unchanged

URL creator panel corners:
- Adds a restrained radius to the light-blue URL creation panel
- Keeps the inner radius visually smaller than the surrounding white card
- Applies consistently on desktop and mobile without changing the form layout

Mobile filter actions:
- Keeps Search and Clear on one equal-width row in the mobile Filter Links card
- Neutralizes legacy separator content that previously pushed Clear onto a second row
- Retains 44px touch targets at narrow mobile widths

Dropdown arrow refinement:
- Replaces inconsistent native select arrows with one restrained brand-aligned chevron
- Adds clear space between the arrow and the right field edge on desktop and mobile
- Shows a down arrow while closed and an up arrow while the dropdown is open in supporting browsers

Mobile dropdown containment:
- Keeps the Filter search-field dropdown and Stats section dropdown inside their mobile cards
- Reserves space for the extra gutter used by Chromium's native option popup
- Leaves desktop select widths and native select behavior unchanged

Responsive Stats back navigation:
- Adds a clear Back to links control at the start of every statistics page
- Returns to the last dashboard URL with its search, sort, filter, and pagination state when available
- Accepts only same-origin dashboard locations and falls back safely to the main Dashboard
- Uses a compact desktop treatment and a full-width 44px mobile-browser touch target

Created-date affordance:
- Adds a restrained calendar icon inside the native date field beside the Created filter
- Keeps the original YOURLS date input, value, and filtering behavior unchanged
- Scopes the icon to the dashboard Filter Links section on desktop and mobile

Delete notification refinement:
- Replaces the decorative green pill and checkmark with a compact neutral system notification
- Uses the standard white surface, quiet border, restrained radius, and minimal shadow
- Shortens the delete confirmation copy and reduces its display duration

Dashboard navigation and action feedback:
- Renames the native Admin interface menu label to Dashboard without changing its URL or active state
- Gives the Search button a light hover surface with dark, readable label text
- Shows a responsive success toast only after YOURLS has successfully removed a deleted URL row
- Keeps failed and canceled deletions free of misleading success feedback

Tools API overview layout:
- Uses the available desktop width with a balanced two-column signature overview
- Places the native API explanation and current token on the left and reset controls on the right
- Keeps usage examples full width below the overview
- Returns to a clean single-column stack on narrower screens and mobile

Signature reset login fix:
- Invalidates PHP OPcache immediately after replacing the active YOURLS config file
- Prevents the reset login page and its submitted nonce from using different cookie keys
- Keeps the existing clean re-login and success-message flow

Tools API signature reset:
- Adds a nonce-protected reset control beside the native secret signature token
- Requires explicit acknowledgement before rotating YOURLS_COOKIEKEY
- Uses a cryptographically secure random key and an atomic config-file replacement
- Invalidates existing API signatures, admin sessions, and nonces, then requires a fresh login
- Shows clear success, permission, and unsupported-config feedback without exposing the new secret
- Keeps the control usable on desktop and mobile and leaves YOURLS core untouched

Tools page experience:
- Groups Bookmarklets, Prefix-n-Shorten, and passwordless API guidance into responsive sections
- Preserves every native draggable bookmarklet and its original javascript URL
- Converts the bookmarklet comparison table into labeled mobile cards
- Improves social bookmarklet actions, notes, inline terms, secret-token presentation, and API examples
- Uses controlled code-block scrolling and full-width mobile layouts for long API values
- Scopes all presentation changes to the Tools page

Mobile statistics navigation:
- Replaces the four visual statistics tabs with one full-width section dropdown at 700px and below
- Keeps the original native tab anchors in the DOM and triggers them when a section is selected
- Synchronizes the dropdown with native tab clicks and hash-selected initial sections
- Retains the desktop/tablet tab bar and the no-JavaScript native fallback

Statistics page experience:
- Groups the long-link title, short URL, and destination URL in a responsive overview card
- Restyles native Traffic Statistics, Location, Sources, and Share navigation as responsive tabs
- Presents chart and detail areas as balanced desktop panels and stacked mobile cards
- Improves time-range controls, historical totals, best-day details, country and referrer lists
- Adds safe overflow containers around fixed-size native charts and maps without scaling or cloning them
- Aligns the Stats Share tab with the CORNQ cards, fields, and social buttons
- Keeps native IDs, tab hashes, chart scripts, detail toggles, share actions, and analytics data intact

Sticky footer layout:
- Keeps the footer at the viewport bottom when an admin or statistics page has little content
- Lets the footer follow long content normally without covering tables, charts, forms, or mobile controls
- Uses the page shell flex layout instead of a fixed-position overlay
- Supports desktop and dynamic mobile viewport heights

Delete detail panel refinement:
- Removes the decorative red side border from the confirmation details
- Uses a neutral information panel with subtle separators between URL details
- Reserves danger emphasis for the actual Delete action

Delete dialog height fix:
- Uses content-sized dialog height instead of stretching to the available viewport height
- Adds explicit vertical centering and zero minimum height on desktop and mobile
- Uses dynamic viewport limits while keeping long details internally scrollable

Delete confirmation dialog:
- Replaces the native fixed dialog dimensions with a responsive auto-height layout
- Removes unnecessary horizontal and vertical scrollbars for normal link details
- Wraps long titles and destination URLs safely inside the dialog
- Adds a clear danger action, secondary Cancel action, and mobile touch targets
- Keeps the native YOURLS confirmation, cancellation, nonce, and deletion behavior unchanged

Plugin action refinement:
- Presents Activate and Deactivate as clear, high-contrast action buttons
- Places the mobile Action label on the left and its button on the right
- Keeps Author and Action in distinct full-width mobile rows

Plugins page improvements:
- Places the installed/activated summary on the left and a status dropdown on the right
- Adds All, Activated, and Deactivated client-side filters without changing activation links
- Gives plugin descriptions a dedicated wrapping column on desktop
- Renders mobile plugins as compact full-width cards with clear labels and actions
- Presents recovery guidance and the plugin directory link in responsive footer panels
- Scopes every table override to the Plugins page so the URL-management table is unaffected

Dashboard summary bar:
- Combines the displayed URL range and overall totals into one responsive row
- Shows the current range on the left and total links/clicks on the right
- Preserves native counter elements and AJAX increment behavior
- Uses compact mobile typography while retaining all numeric information

Mobile navigation listing:
- Displays Dashboard, Tools, Manage Plugins, and Help as sequential full-width rows
- Displays All Plugins and registered plugin links as sequential dropdown rows
- Keeps the active item, account row, hamburger behavior, and touch-friendly sizing

Mobile hamburger navigation:
- Collapses the admin navigation behind a Menu button at 700px and below
- Expands inside the existing navigation card with an animated menu/close icon
- Supports outside click, Escape, focus visibility, and desktop resize reset
- Keeps the Manage Plugins dropdown and all native/plugin menu links available
- Falls back to the visible native menu if JavaScript does not run

Plugin navigation dropdown:
- Turns Manage Plugins into an accessible dropdown trigger
- Adds All Plugins as the first dropdown item using the native plugins.php URL
- Keeps registered plugin menu items such as 2FA Setup in the same dropdown
- Supports outside click, Escape, keyboard activation, desktop popover, and mobile expansion

Active navigation fix:
- Keeps the active menu label visible in white on the Deep Navy background
- Locks visited, hover, focus, opacity, and text-rendering states for active links

Admin navigation:
- Restyles the native YOURLS menu as a compact CORNQ navigation card
- Keeps user/logout, Admin, Tools, Plugins, Help, 2FA, and plugin-hook links intact
- Highlights the current core admin section
- Uses a wrapping mobile layout with a separate user/logout row

Copy action:
- Adds Copy beside the native row actions on desktop and inside the mobile three-dot menu
- Copies the row's current short URL directly to the clipboard
- Shows brief Copied or retry feedback without changing native action behavior
- Supports AJAX-added rows, edited keywords, and a legacy clipboard fallback

Desktop filter placement and layout:
- Moves the existing native Filter Links form above the URL table
- Hides the empty native table footer after relocation
- Uses a balanced 12-column desktop field layout aligned with the mobile hierarchy
- Keeps all native filter controls, names, values, Search, and Clear behavior

Mobile filter improvements:
- Preserves and displays the native Search and Clear buttons
- Gives Search controls full width and uses clearer responsive field grouping
- Uses 44px touch-friendly inputs, selects, and buttons
- Removes the legacy table-footer framing around the mobile filter card
- Stacks filter groups cleanly at 420px and below

Pagination placement:
- Moves the existing native pagination controls below the URL table
- Keeps all original pagination links, query strings, and filtering behavior
- Uses a full-width bottom pagination block on desktop and mobile

Share icon fix:
- Keeps the native Twitter and Facebook icons at a consistent 16px size
- Aligns both social icons cleanly inside the CORNQ-styled share buttons

Share experience:
- Smoothly scrolls the dashboard to Quick Share after a Share action is selected
- Respects reduced-motion preferences
- Restyles the dashboard share cards, fields, links, and social actions to match the CORNQ theme
- Keeps statistics/info page share and chart layouts isolated from these dashboard styles

Fix:
- Prevents the mobile three-dot action menu from being clipped after the first item
- Keeps Stats, Share, Edit, and Delete visible above the following URL card

Mobile refinements:
- Three-dot Actions menu on phone-sized screens (Stats, Share, Edit, Delete dropdown)
- Desktop keeps the normal four visible action buttons
- Fixed left-edge clipping in mobile URL cards
- URL creator is structured into clean fields and stays inside the card
- Filter controls are reorganized into responsive labeled fields
- Search/Clear and pagination are mobile-friendly
- Keeps responsive card table, branding, Remember Me, analytics/share compatibility fixes
