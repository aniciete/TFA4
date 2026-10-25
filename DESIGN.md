---
name: POS Cart & Orders
description: Artisan Market & Retail Docket Design System for CodeIgniter 4 Orders
colors:
  primary: "#173c2a"
  primary-hover: "#0f281c"
  accent: "#c94c38"
  accent-hover: "#b33e2c"
  neutral-bg: "#fff8e8"
  surface: "#fffdf7"
  surface-subtle: "#f5eedc"
  border: "#e6dcbe"
  text: "#173c2a"
  text-muted: "#425849"
  text-inverse: "#fff8e8"
  sage: "#3f5947"
  sage-surface: "#e2ede4"
typography:
  display:
    fontFamily: "Bricolage Grotesque, -apple-system, sans-serif"
    fontSize: "clamp(1.75rem, 4vw, 2.5rem)"
    fontWeight: 700
  body:
    fontFamily: "Bricolage Grotesque, -apple-system, sans-serif"
    fontSize: "1rem"
    fontWeight: 400
  mono:
    fontFamily: "IBM Plex Mono, ui-monospace, monospace"
    fontSize: "0.9375rem"
    fontWeight: 500
rounded:
  sm: "2px"
  md: "4px"
  lg: "8px"
spacing:
  xs: "4px"
  sm: "8px"
  md: "16px"
  lg: "24px"
  xl: "32px"
components:
  button-primary:
    backgroundColor: "{colors.primary}"
    textColor: "{colors.text-inverse}"
    rounded: "{rounded.md}"
    padding: "10px 20px"
---

## Overview

POS Cart & Orders employs the **Bright-Market Design System** in **Surface Mode: Operate**. It enforces tabular figures for transaction calculations and clean 2px ledger border lines.

## Colors

- **Primary (`#173c2a`)**: Primary buttons, table headers, and brand tags.
- **Accent (`#c94c38`)**: High-priority checkout buttons and focus rings.
- **Neutral Background (`#fff8e8`)**: Glare-free warm cream paper stock.

## Typography

- **Bricolage Grotesque**: Display headers and navigation labels.
- **IBM Plex Mono**: Tabular numerals, order codes, quantities, and prices.
