goo.bd Admin Experience v1.24.1

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

Install by replacing the existing goobd-admin-experience plugin folder, then hard refresh the browser.
