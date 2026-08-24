# Codex Instructions — goo.bd YOURLS Admin Experience

## 1. Project purpose

This project customizes the private YOURLS admin/login experience used at `https://goo.bd/`.

The goal is to make YOURLS feel like a clean CORNQ product while preserving all native YOURLS functionality and compatibility.

Current base:

- Product: `goo.bd`
- Platform: YOURLS `1.10.4`
- Plugin: `goo.bd Admin Experience`
- Current plugin version: `1.9.0`
- Plugin directory: `user/plugins/goobd-admin-experience/`
- Do **not** edit YOURLS core files unless there is no viable plugin-hook solution and the user explicitly approves it.

The plugin currently handles:

- goo.bd / CORNQ branding
- custom header and footer
- compact login interface
- 30-day optional “Remember Me” login
- responsive admin dashboard styling
- improved URL table styling
- responsive mobile URL cards
- mobile three-dot actions menu
- redesigned filter/pagination area
- responsive “Enter the URL” form
- compatibility fixes for YOURLS statistics/share pages

---

## 2. Files to treat as source material

Before changing anything, inspect these files in the working directory:

```text
BRAND.md
app.css

goobd-admin-experience/
├── plugin.php
├── assets/
│   └── admin.css
└── README.txt
```

### Source-of-truth priority

When sources conflict, use this order:

1. **YOURLS native functionality and markup contract** — never lose features or content.
2. **`BRAND.md`** — authoritative CORNQ visual/product rules.
3. **`app.css`** — useful implementation reference for CORNQ components and tokens.
4. **Current plugin behavior** — preserve anything already working unless a requested change requires modification.

Do not blindly copy styles from `app.css`; use it as a design-system reference because YOURLS has legacy markup and fixed-layout components.

---

## 3. CORNQ brand rules that must remain

The primary product identity should be text-only:

```text
goo.bd by CORNQ
```

Rules:

- `goo.bd` is visually primary.
- `by CORNQ` is smaller and quieter.
- `CORNQ` must always be uppercase.
- Do not add a decorative icon/monogram beside the product name.
- Keep the tagline subtle: `Short links. Made simple.`
- Deep Navy is the dominant brand color.
- Use Bright Yellow only as a controlled accent, never as small text on white.
- Keep the interface minimal, calm, practical, and non-flashy.
- Prefer typography, spacing, quiet borders, and good hierarchy over gradients/effects.

Core tokens:

```css
--brand-navy: #182c45;
--brand-yellow: #f8ee24;
--brand-blue: #416ab8;
--brand-sage: #788275;
--brand-off-white: #f4f7f7;
--text-ink: #102033;
--text-muted: #657385;
--surface: #ffffff;
--surface-secondary: #edf2f7;
--border: #dce3ea;
--danger: #b42318;
--success: #137a4d;
```

Preferred font stack:

```css
font-family: Inter, ui-sans-serif, system-ui, -apple-system,
  BlinkMacSystemFont, "Segoe UI", sans-serif;
```

Footer format:

```text
© CurrentYear goo.bd powered by CORNQ
```

`CORNQ` must link to `https://cornq.com/`, open in a new tab, and use `rel="noopener noreferrer"`.

---

## 4. Architecture requirements

### Keep it as a YOURLS plugin

Do not turn this into a core fork or separate admin application.

Prefer YOURLS hooks/filters, including the existing approach:

- `html_title`
- `html_footer_text`
- `html_logo`
- `login_form_top`
- `login_form_bottom`
- `get_cookie_life`
- `html_head`

If another YOURLS hook can solve a problem cleanly, use it instead of modifying core files.

### Current plugin structure

`plugin.php` currently contains:

- plugin metadata/version
- page-title filters
- footer filter
- custom product header
- compact login heading
- Remember Me checkbox and cookie-life override
- CSS inclusion
- a small inline JavaScript enhancement layer for legacy YOURLS DOM

`assets/admin.css` contains the visual layer.

For future cleanup, moving the JavaScript into `assets/admin.js` is acceptable and probably preferable once the current UI is stable. If doing this, keep loading/versioning safe and do not introduce a build-tool requirement unnecessarily.

---

## 5. Production safety rules

This is an existing production URL shortener. Treat changes as regression-sensitive.

Never:

- delete or rename existing database tables
- change short-link data
- rewrite stored URLs
- remove native YOURLS actions
- disable statistics functionality
- break pagination/filtering
- break AJAX edit/share/delete behavior
- remove plugin hooks used by 2FA or other installed plugins
- expose authentication/session details
- remove CSRF/nonces from native forms
- make the admin pages indexable

The plugin intentionally adds:

```html
<meta name="robots" content="noindex, nofollow, noarchive" />
```

Keep that behavior.

---

## 6. Login requirements

The login screen must remain compact and professional.

Required behavior:

- shared `goo.bd by CORNQ` page header
- compact `Sign in` card
- username field
- password field
- existing 2FA field injected by the installed 2FA setup must remain usable
- optional Remember Me checkbox
- Login button

Remember Me behavior:

- unchecked: keep normal YOURLS cookie lifetime
- checked: cookie lifetime becomes 30 days
- do not force a 30-day session for every login

Important: do not style only a hard-coded number of login inputs. Other plugins may inject login fields. Styling should degrade gracefully if extra fields appear.

---

## 7. Header and footer requirements

### Header

Use the CORNQ text identity:

```text
goo.bd by CORNQ                         Short links. Made simple.
```

Desktop:

- clean white header
- subtle bottom border
- responsive max-width content container
- no floating logo card
- no YOURLS logo visible

Mobile:

- keep `goo.bd by CORNQ` readable
- tagline may be hidden if space is tight
- no horizontal overflow

### Footer

Must be:

- full width
- centered
- white background
- no rounded blue outline
- no floating card appearance
- no legacy YOURLS favicon background

---

## 8. Dashboard URL-creation form

YOURLS native markup for the URL creator is legacy inline markup. The plugin currently restructures it at runtime for responsive presentation.

Required visible fields:

- Enter the URL
- Optional custom short URL
- Shorten The URL button

Desktop:

- URL field should receive most horizontal space
- custom keyword field should remain compact
- button should be easy to find

Mobile:

- stack fields vertically
- URL input must stay entirely inside the card
- no clipped left or right edges
- custom short URL input should be full usable width
- button should be touch-friendly, ideally full width or clearly centered

Do not remove hidden nonce inputs when restructuring the DOM.

---

## 9. Main URL table — desktop behavior

The desktop table should remain a real table.

Columns:

```text
Short URL | Original URL | Date | IP | Clicks | Actions
```

Requirements:

- no action button may overflow the table
- Original URL gets the most width
- long titles/URLs must not push the entire layout wider
- actual long URL should be secondary/muted text
- Date and IP should not wrap awkwardly
- Clicks should be compact/aligned
- keep sortable table-header behavior
- do not remove table sorter classes or functionality

Desktop actions should remain directly visible:

```text
Stats | Share | Edit | Delete
```

Delete should use semantic danger styling without being visually aggressive.

---

## 10. Main URL table — mobile behavior

At `700px` and below, do **not** squeeze the desktop table into a tiny viewport.

Render each normal row visually as a mobile card while retaining the original table DOM so native JavaScript continues to work.

Expected information hierarchy:

```text
SHORT URL
keyword

ORIGINAL URL
Title
https://example.com/...

DATE              IP               CLICKS
Aug 24, 2026      103.x.x.x        10

                                  [ ⋮ ]
```

### Mobile actions menu

On mobile, do not show four large stacked action buttons.

Use one compact three-dot/kebab trigger:

```text
⋮
```

When opened, show a dropdown/popover with:

```text
Stats
Share
Edit
Delete
```

Interaction requirements:

- preserve the original YOURLS anchor elements and their native IDs/onclick handlers
- do not clone actions in a way that breaks AJAX behavior
- outside click closes the menu
- Escape closes the menu
- trigger has an accessible label and `aria-expanded`
- menu should not be clipped by the card/table container
- menu should remain within the viewport
- Delete remains visually distinct
- only one row action menu should be open at a time

After AJAX DOM updates, newly inserted rows must also receive the menu enhancement. The current version uses a `MutationObserver`; preserve or improve that behavior.

---

## 11. Edit-row behavior

Clicking Edit uses YOURLS’ native dynamic edit row.

Do not break:

- Long URL editing
- Short URL keyword editing
- Title editing
- Save
- Cancel
- hidden nonce/old-keyword fields

Desktop edit row may remain horizontal where reasonable.

Mobile edit row should become a clean full-width form with vertically stacked fields and touch-friendly Save/Cancel controls.

---

## 12. Filter and pagination interface

YOURLS generates the filter UI as translated sentence fragments around native inputs/selects. The current plugin reorganizes those native controls with JavaScript rather than recreating the form from scratch.

Keep every native filter control and its name/value:

- `search`
- `search_in`
- `sort_by`
- `sort_order`
- `perpage`
- `click_filter`
- `click_limit`
- `date_filter`
- `date_first`
- `date_second`

Do not break query-string behavior.

Desired grouping:

```text
Filter links

Search
[ search text........ ] [ All fields ]

Sort
[ Date ] [ Descending ]

Rows
[ 15 ]

Clicks
[ more ] [ value ]

Created
[ before ] [ date/range controls ]

[ Search ] [ Clear ]
```

Mobile:

- no clipped labels
- no sentence fragments hanging between fields
- sensible one-column/two-column responsive layout
- inputs at least ~44px touch height where practical
- pagination in a separate clean block or aligned section
- no horizontal page scrollbar

Pagination should remain functional and use the existing YOURLS navigation links.

---

## 13. Statistics / information pages — critical compatibility rules

This area caused regressions during earlier styling work. Be conservative.

YOURLS statistics pages include:

- Traffic statistics
- Traffic location
- Traffic sources
- Share
- line charts
- pie charts
- world map
- historical click counts
- referrer details
- Quick Share

The native YOURLS CSS uses fixed/content-box dimensions in places, especially `share.css`.

Do **not** apply broad global rules such as:

```css
* { box-sizing: border-box; }
input, textarea { width: 100%; }
table { display: block; }
```

across the whole admin UI.

Those types of rules previously caused content to disappear or be clipped, especially Twitter/Facebook sharing content.

Scope dashboard/table/form styles carefully.

When touching analytics pages, compare against the default YOURLS theme with this plugin disabled and confirm that every piece of information still appears.

---

## 14. CSS maintenance warning

`assets/admin.css` evolved through multiple iterative fixes and currently contains repeated/overlapping responsive sections, especially around `@media (max-width: 700px)`.

Do not keep solving problems by endlessly appending stronger overrides.

Recommended next engineering task:

1. inventory all existing selectors
2. identify duplicate or superseded mobile blocks
3. consolidate them into one coherent responsive section
4. retain the working behavior
5. retest every page before deleting old rules

If doing a major cleanup, bump to a clearly new version and compare before/after screenshots.

Prefer scoped selectors such as:

```css
#main_table ...
#new_url ...
#filter_form ...
body.login #login ...
#tabs ...
```

over global element styling.

---

## 15. JavaScript rules

The enhancement JavaScript must remain progressive enhancement, not a replacement for YOURLS functionality.

Requirements:

- no external framework dependency beyond what YOURLS already loads
- avoid changing native IDs needed by YOURLS JS
- preserve native event handlers and anchors
- preserve hidden nonce fields
- avoid duplicate initialization
- use data flags when enhancing existing nodes
- account for AJAX-inserted rows
- keyboard-accessible menus
- fail safely if expected markup is absent

Do not make the dashboard unusable if the enhancement code does not run.

---

## 16. Responsive breakpoints

Default main breakpoint from the CORNQ design system:

```text
700px
```

Minimum mobile test widths:

```text
412px  — Pixel 7 type viewport
390px  — common iPhone width
360px  — small Android width
```

Also test:

```text
768px
1024px
1280px
1440px
```

Do not judge responsive behavior while the browser itself is zoomed to 50%. Use 100% browser zoom and DevTools device emulation.

---

## 17. Required regression test checklist

Before declaring a version finished, test all of these.

### Login

- login page loads
- header visible
- username/password work
- 2FA field visible if 2FA plugin is active
- Remember Me can be checked
- successful login works
- failed login error is readable
- mobile login does not overflow

### Dashboard

- create a normal short link
- create a custom-keyword short link
- newly added row appears correctly
- sorting still works
- pagination works
- filtering works
- Clear works

### Row actions

- Stats opens correct URL
- Share opens native share UI
- Edit opens edit row
- Save works
- Cancel works
- Delete confirmation works
- Delete works only after confirmation
- actions remain usable after AJAX updates

### Mobile table

- no horizontal page scroll
- no clipped card text
- long Bengali and English titles render
- long URLs do not widen the viewport
- Date/IP/Clicks are readable
- three-dot menu opens
- all four actions are available
- menu closes outside/Escape
- dropdown is not clipped

### Statistics

Compare plugin enabled vs disabled and verify:

- Traffic statistics graph
- historical click count
- Best day
- Traffic location pie chart
- world map
- Traffic sources pie charts
- referrer details
- Share tab
- Short link box
- Quick Share textarea
- Twitter/Facebook links

No content may disappear merely because the custom theme is enabled.

### Footer/header

- header alignment correct
- tagline hidden/visible appropriately
- footer centered and full width
- no legacy blue rounded border
- CORNQ link works in new tab
- current year is dynamic

---

## 18. Versioning and release process

For every user-visible release:

1. bump the plugin header version in `plugin.php`
2. bump `GOOBD_AE_VERSION`
3. update `README.txt`
4. use the version constant for CSS/JS cache busting
5. syntax-check PHP
6. syntax-check JavaScript if moved to its own file
7. inspect CSS for obvious malformed blocks
8. Maintain CHANGELOG.md with this structure:

```text
goobd-admin-experience/
├── plugin.php
├── assets/
│   ├── admin.css
│   └── admin.js        # only if introduced
└── README.txt
└──CHANGELOG.md
```


Suggested chnagelog naming:

```text
<heading> vX.Y.Z.
details section wise
```

---

## 19. Installation/update procedure

Production plugin path:

```text
/user/plugins/goobd-admin-experience/
```

For updates:

1. backup the current plugin folder
2. overwrite the plugin folder with the new release
3. do not delete YOURLS database data
4. hard-refresh the browser after deployment
5. test desktop and mobile before considering the update complete

If a change causes a regression, restoring the previous plugin directory should restore the previous UI because YOURLS core is intentionally untouched.

---

## 20. How to approach future user feedback

When the user supplies a screenshot:

1. identify the exact visual/functional issue from the screenshot
2. inspect the current plugin code before changing it
3. determine whether the problem is CSS, legacy YOURLS markup, or JavaScript behavior
4. fix the narrowest responsible layer
5. avoid unrelated redesigns
6. preserve every working feature from the previous version
7. verify nearby states that the change could affect

Do not claim a visual issue is fixed without checking the actual selectors/DOM responsible for it.

When possible, compare the custom theme with default YOURLS to detect content-visibility regressions.

---

## 21. Current v1.9.0 state

Version `1.9.0` is the handoff baseline.

Its intended mobile changes are:

- three-dot actions menu
- desktop four-button actions retained
- mobile URL card layout
- mobile left-edge clipping fixes
- structured responsive URL-creation form
- structured responsive filter fields
- mobile-friendly Search/Clear and pagination
- preservation of earlier statistics/share compatibility fixes

**Important:** treat these as intended current behavior, not proof that every device/browser has been visually validated. Re-test the latest build locally before continuing development.

The biggest technical debt to address next is the accumulated CSS override structure. Clean it carefully rather than adding another large layer of overrides.

---

## 22. Definition of done

A change is done only when:

- requested UI issue is actually fixed
- desktop still works
- mobile works at 412px, 390px, and 360px
- native YOURLS functionality remains intact
- statistics/share content is not lost
- no page-level horizontal overflow is introduced
- branding follows `BRAND.md`
- core YOURLS files remain untouched
- plugin version/readme are updated for a release
- code is maintainable enough that the next fix does not require another cascade of `!important` overrides

Focus on reliability and polish over large redesigns.
