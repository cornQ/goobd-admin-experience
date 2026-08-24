# CORNQ Product Brand Guidelines

Version 1.0

This document defines a shared visual and verbal foundation for CORNQ digital products. It is intentionally product-neutral so the same system can be used across websites, utilities, dashboards and future tools.

Individual products may have their own name, purpose and tagline, but they should retain the CORNQ attribution, core palette, typography, interface character and communication principles defined here.

## 1. Brand principles

CORNQ products should feel:

- clear and dependable
- modern and minimal
- technically capable without being intimidating
- calm rather than flashy
- honest about capabilities and limitations
- accessible across devices and levels of technical experience

The interface should help users understand the primary task quickly. Visual polish should support usability rather than compete with it.

## 2. Brand architecture

### Naming pattern

Use this structure for CORNQ tools:

```text
ProductName by CORNQ
```

Examples:

```text
SiteSnap by CORNQ
ExampleTool by CORNQ
```

Rules:

- The product name is primary.
- `by CORNQ` is supporting attribution.
- Always write `CORNQ` in uppercase.
- Preserve the product's official capitalization and spacing.
- Do not combine the product name and CORNQ into a new word.
- Keep CORNQ attribution visible in the primary header, footer or another prominent identity location.

### Product taglines

Each product may have its own short tagline. A tagline should describe the product's value or rhythm without exaggerated claims.

Example:

```text
SiteSnap: Capture. Preserve. Share.
```

A product-specific tagline is not automatically a tagline for CORNQ or for other CORNQ products.

## 3. Text-only wordmark

CORNQ products should use a text-only identity by default. A standalone symbol, rounded-square initial, monogram or decorative logo is not required.

### Standard lockup

```text
ProductName by CORNQ
```

Recommended treatment:

| Element | Colour | Size | Weight |
|---|---|---:|---:|
| Product name | Deep Navy `#182c45` | `20px` | `800` |
| `by CORNQ` | Muted Text `#657385` | `12px` | `600` |

Reference HTML:

```html
<a class="brand" href="/">
  <span class="product-name">ProductName</span>
  <span class="brand-attribution">by CORNQ</span>
</a>
```

Reference CSS:

```css
.brand {
  display: inline-flex;
  align-items: baseline;
  gap: 5px;
  color: #182c45;
  text-decoration: none;
}

.product-name {
  font-size: 20px;
  font-weight: 800;
}

.brand-attribution {
  color: #657385;
  font-size: 12px;
  font-weight: 600;
}
```

### Wordmark rules

- Keep the product name readable at every viewport size.
- Keep `by CORNQ` visibly associated with the product name.
- Use typography and spacing instead of a decorative icon.
- Do not stretch, skew, outline or add heavy effects to the wordmark.
- Do not use multiple colours within the product name.
- Do not use Bright Yellow for small wordmark text on white.
- Keep clear space around the lockup approximately equal to the height of the lowercase letters.

### SiteSnap implementation

SiteSnap must use this text-only header identity:

```text
SiteSnap by CORNQ
```

Apply the shared wordmark hierarchy as follows:

- `SiteSnap`: Deep Navy `#182c45`, `20px`, weight `800`.
- `by CORNQ`: Muted Text `#657385`, `12px`, weight `600`.
- Align both parts on the text baseline with a `5px` gap.
- Keep `SiteSnap` visually primary and `by CORNQ` visibly smaller and quieter.
- Do not place an `S` icon, square mark, monogram or decorative symbol beside the name.
- The homepage may show `Capture. Preserve. Share.` on the opposite side of the header.
- Internal status and expired pages may omit the tagline while retaining `SiteSnap by CORNQ`.

Reference markup:

```html
<a class="brand" href="/">
  <span class="product-name">SiteSnap</span>
  <span class="brand-attribution">by CORNQ</span>
</a>
```

In the SiteSnap codebase, maintain this identity through the shared `app/partials/header.php` file instead of duplicating header markup across public pages.

### Favicons and app icons

A navigation logo is not mandatory. Favicons, social avatars and installed-app icons may still need a compact asset for technical reasons. These assets should be designed separately and must not automatically become part of the primary text wordmark.

Do not invent a permanent icon or monogram without documenting and approving it as a separate brand asset.

## 4. Footer attribution

Every CORNQ product should use this standard footer line, replacing `ToolName` with the actual product name and rendering the current year dynamically:

```text
© CurrentYear ToolName powered by CORNQ
```

`CORNQ` must use weight `700`, link to `https://cornq.com/`, and open in a new browser tab.

Reference Markdown:

```markdown
© CurrentYear ToolName powered by [**CORNQ**](https://cornq.com/)
```

Reference PHP/HTML:

```php
<footer class="product-footer">
  <span>&copy; <?= date('Y') ?> ToolName powered by </span>
  <a href="https://cornq.com/" target="_blank" rel="noopener noreferrer">
    <strong>CORNQ</strong>
  </a>
</footer>
```

Reference CSS:

```css
.product-footer {
  width: 100%;
  padding: 24px 18px;
  text-align: center;
  background: #ffffff;
  color: #657385;
  font-size: 13px;
  font-weight: 400;
}

.product-footer a {
  color: #182c45;
  font-weight: 700;
  text-decoration: none;
}

.product-footer a:hover,
.product-footer a:focus-visible {
  text-decoration: underline;
}
```

Footer rules:

- Use a White `#FFFFFF` background.
- Centre-align the complete attribution line.
- Prefix the attribution with the copyright symbol and current year.
- Generate the year dynamically from the server or application runtime; never hard-code it.
- Keep the product name in its official capitalization.
- Use weight `400` for the general footer attribution text so the line remains visually light.
- Keep `CORNQ` uppercase, at weight `700`, and linked to `https://cornq.com/`.
- Open the CORNQ link in a new tab and use `rel="noopener noreferrer"`.
- Do not add an icon or monogram to the standard footer attribution.
- Keep a visible keyboard-focus treatment on the CORNQ link.

## 5. Colour system

### Core CORNQ palette

| Token | Hex | Primary role |
|---|---:|---|
| Deep Navy | `#182c45` | Brand, headings, primary actions and dark surfaces |
| Bright Yellow | `#f8ee24` | Controlled accent and links on navy |
| Medium Blue | `#416ab8` | Focus, progress and informational emphasis |
| Muted Sage | `#788275` | Supporting accent; use sparingly |
| Soft Off-white | `#f4f7f7` | Soft page and section backgrounds |

### Interface colours

| Token | Hex | Role |
|---|---:|---|
| Ink | `#102033` | Primary body text |
| Muted Text | `#657385` | Secondary copy and attribution |
| White | `#ffffff` | Cards and clean surfaces |
| Border | `#dce3ea` | Dividers, fields and card outlines |
| Soft Blue Surface | `#eef3fb` | Informational pill background |
| Secondary Surface | `#edf2f7` | Secondary buttons and neutral controls |
| Error | `#b42318` | Error text and state |
| Error Surface | `#fff0ee` | Error background |
| Error Border | `#ffd3cd` | Error outline |
| Success | `#137a4d` | Success and completed states |

### Default page background

```css
background: linear-gradient(
  180deg,
  #ffffff 0%,
  #f7f9fb 52%,
  #f1f5f7 100%
);
```

### Colour rules

- Deep Navy is the dominant brand colour.
- Bright Yellow is an accent, not a general paragraph-text colour.
- Prefer Bright Yellow on Deep Navy.
- Use Medium Blue for focus, progress and informational emphasis.
- Reserve Error and Success colours for their semantic meanings.
- Do not use every core colour in one component.
- Check WCAG AA contrast for all new text and interactive states.

## 6. Typography

### Font family

Use an Inter-first system stack:

```css
font-family: Inter, ui-sans-serif, system-ui, -apple-system,
  BlinkMacSystemFont, "Segoe UI", sans-serif;
```

This provides a consistent modern appearance while retaining reliable system-font fallbacks.

### Recommended type scale

| Use | Size | Weight | Notes |
|---|---:|---:|---|
| Hero heading | `clamp(38px, 6vw, 72px)` | `800` | `1.02` line height; tight tracking |
| Page heading | `26–32px` | `700–800` | Deep Navy |
| Section heading | `20–24px` | `700` | Deep Navy or Ink |
| Lead paragraph | `clamp(17px, 2vw, 20px)` | `400` | Approximately `1.7` line height |
| Product wordmark | `20px` | `800` | Deep Navy |
| Body/UI copy | `14–16px` | `400–600` | Ink or Muted Text |
| Button | `15px` | `700` | Short action label |
| Label | `12px` | `800` | Uppercase; `0.05em` tracking |
| Attribution/helper | `12–13px` | `600` | Muted Text |
| Port speed metadata | `13px` | `400` | Muted Text; normal case |

Use strong hierarchy and comfortable line height. Reserve uppercase for short labels rather than paragraphs or navigation items.

## 7. Verbal identity

### Voice

Use plain, direct language. State the outcome first, followed by constraints or technical detail when necessary.

Preferred qualities:

- concise
- helpful
- factual
- action-oriented
- transparent about limitations
- respectful of privacy, authorization and ownership

### Action labels

Buttons should use a clear verb and object where practical:

```text
Create Report
Capture Website
View Result
Copy Link
Save Changes
Try Again
```

Avoid vague labels such as `Proceed`, `Submit` or `Click Here` when a specific action is available.

### Status and error language

- Use calm, specific status labels.
- Explain what happened and what the user can do next.
- Keep internal exceptions, credentials, paths and infrastructure details out of public errors.
- Never hide an operational failure by presenting it as success.

### Avoid

- exaggerated claims such as “perfect,” “unbreakable” or “works with everything”
- unexplained jargon in primary UI copy
- jokes in error, security, payment or expiry messages
- keyword stuffing
- claims that are not supported by the product

## 8. Layout and spacing

### Page container

```css
width: min(1080px, calc(100% - 36px));
margin-inline: auto;
```

### Reference dimensions

- Desktop navigation height: `78px`
- Mobile navigation height: `66px`
- Focused tool/card maximum width: approximately `760–830px`
- Desktop hero top padding: approximately `72px`
- Mobile hero top padding: approximately `45px`
- Standard gaps: `10px`, `12px`, `14px`, `18px`, `22px`, `24px`

Use whitespace to make the primary task obvious. Avoid dense dashboards unless the product genuinely requires one.

## 9. Shape, borders and depth

### Corner radii

| Component | Radius |
|---|---:|
| Major task/status card | `24px` |
| Feature card | `18px` |
| Button | `10px` |
| Primary input | `14px` |
| Select, alert and compact field | `12px` |
| Pill, tag and progress track | `999px` |

### Borders

Use thin, quiet borders:

```css
border: 1px solid #dce3ea;
```

Primary inputs may use a slightly stronger `1.5px` border with `#cfd8e2`.

### Shadow

Use the lightweight navy-tinted shadow for a focused on-page summary card:

```css
box-shadow: 0 8px 20px rgba(24, 44, 69, 0.045);
```

Reserve the stronger elevated shadow for important dialogs and modal surfaces:

```css
box-shadow: 0 20px 60px rgba(24, 44, 69, 0.10);
```

Avoid multiple heavy shadows, sharp black shadows or decorative effects that reduce clarity.

## 10. Components and interaction

### Primary button

- Deep Navy background
- White text
- Minimum height `52px`
- Radius `10px`
- Horizontal padding `20px`
- Weight `700`
- Subtle brightness increase on hover

### Secondary button

- Secondary Surface background
- Deep Navy text
- Same shape and typography as the primary button

### Input focus

```css
border-color: #416ab8;
box-shadow: 0 0 0 4px rgba(65, 106, 184, 0.10);
```

Never remove visible keyboard focus without supplying an accessible replacement.

### Cards

- Use a white or nearly white surface.
- Use a thin Border token outline.
- Reserve shadows for the primary task, important dialogs or status cards.
- Use `32px` padding for a focused desktop summary card, such as the primary public-IP card.
- At or below `700px`, reduce focused summary-card padding to `28px 18px`.
- Left-align controls and detailed content even when the surrounding hero is centred.

### States

- In progress: Medium Blue
- Completed: Success green
- Failed: Error red with a pale red surface
- Neutral/disabled: muted text and secondary surface

Do not communicate state through colour alone. Pair it with text, an accessible icon or another clear indicator.

## 11. Responsive behaviour

Use `700px` as the default primary breakpoint unless a product's content requires another documented breakpoint.

At smaller widths:

- reduce navigation height
- reduce major-card padding slightly
- stack primary inputs and actions
- make important actions full width when helpful
- collapse multi-column option and metadata grids
- preserve readable side margins
- avoid horizontal scrolling

Keep touch targets approximately `44px` or taller.

## 12. Imagery and iconography

CORNQ product interfaces should rely primarily on typography, spacing and clear controls.

- Icons are optional and should be functional rather than decorative.
- Prefer simple, familiar line symbols.
- Keep icon style consistent within a product.
- Important icon actions require a visible label or accessible name.
- Do not add an icon beside the product name merely to fill space.
- Use photography or illustration only when it explains the product better than the interface itself.

## 13. Accessibility

- Maintain WCAG AA contrast for normal text and controls.
- Do not use colour as the only status indicator.
- Keep visible keyboard focus.
- Use semantic headings in order.
- Give form controls explicit labels or accessible names.
- Keep errors close to the relevant action.
- Support keyboard navigation and mobile touch targets.
- Respect reduced-motion preferences when adding animation.

Bright Yellow should not be used for small text on white. Its preferred use is as a controlled accent on Deep Navy.

## 14. Metadata and public presentation

Each product should define:

- a concise page title
- a factual meta description
- a canonical URL
- Open Graph title, description and URL
- Twitter Card metadata where relevant
- appropriate structured data
- a theme colour, normally Deep Navy `#182c45`

Metadata must match the visible product identity and must not overstate functionality. User-generated, private or duplicate-content pages should normally be excluded from search indexing.

## 15. Starter design tokens

```css
:root {
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

  --radius-card: 24px;
  --radius-feature: 18px;
  --radius-button: 10px;
  --radius-control: 14px;
  --radius-small: 12px;
  --shadow-summary: 0 8px 20px rgba(24, 44, 69, 0.045);
  --shadow-card: 0 20px 60px rgba(24, 44, 69, 0.10);

  --font-sans: Inter, ui-sans-serif, system-ui, -apple-system,
    BlinkMacSystemFont, "Segoe UI", sans-serif;
}
```

## 16. Product-specific customization

Individual CORNQ tools may customize:

- product name
- tagline
- hero copy
- feature descriptions
- information architecture
- product-specific status labels
- illustrations or screenshots when genuinely useful

They should retain:

- the text-only `ProductName by CORNQ` identity
- CORNQ attribution
- the standard centred `© CurrentYear ToolName powered by CORNQ` footer
- the core palette and colour roles
- the Inter-first typography
- the shared control and card language
- accessible interaction patterns
- the direct, factual tone of voice

## 17. Release checklist

- The name follows `ProductName by CORNQ`.
- The primary identity is text-only.
- CORNQ attribution is visible.
- The footer uses `© CurrentYear ToolName powered by CORNQ` on White `#FFFFFF`.
- The year is generated dynamically rather than hard-coded.
- The bold `CORNQ` footer text links to `https://cornq.com/` in a new tab.
- No unapproved navigation icon or monogram has been added.
- Deep Navy is the primary brand colour.
- Bright Yellow is used as a controlled accent.
- Typography uses the approved Inter-first stack.
- Major cards, controls and buttons follow the shared radius system.
- Primary actions use concise, specific labels.
- Focus, error, success and disabled states are accessible.
- The mobile layout is tested at and below `700px`.
- Page title, description, canonical URL and social metadata match the product.
- Claims are factual and limitations are stated clearly.

## 18. Maintaining this guide

Treat this file as the shared reference for CORNQ product branding. When the family-wide system changes, update this guide and affected product assets together.

Product-specific implementation files may be used as examples, but they should not silently redefine the shared brand system.
