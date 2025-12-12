# Credits & Acknowledgments

This document lists all open-source libraries, services, and tools that power **hel.ink**.

---

## Core Framework & Language

- **Laravel 12** - PHP Framework (MIT)
- **PHP 8.2+** - Programming Language

---

## Backend Dependencies (Composer)

### Framework & Core
- **laravel/framework** v12.0 - Full-stack PHP framework (MIT)
- **laravel/breeze** v2.3 - Authentication scaffolding (MIT)
- **laravel/socialite** v5.23 - OAuth authentication provider (MIT)
- **laravel/tinker** v2.10 - Interactive REPL console (MIT)

### Security & Authentication
- **pragmarx/google2fa-laravel** v2.3 - Two-factor authentication (MIT)
- **laravel/sanctum** - API token authentication (MIT)

### Utilities
- **simplesoftwareio/simple-qrcode** v4.2 - QR code generator (MIT)
- **geoip2/geoip2** v3.2 - IP geolocation library (Apache-2.0)
- **sentry/sentry-laravel** v4.19 - Error tracking & monitoring (BSD-3-Clause)

### Development Tools
- **laravel/pail** v1.2 - Real-time log viewer (MIT)
- **laravel/pint** v1.24 - Code style fixer (MIT)
- **laravel/sail** v1.41 - Docker development environment (MIT)

### Testing
- **pestphp/pest** v4.1 - Elegant testing framework (MIT)
- **pestphp/pest-plugin-laravel** v4.0 - Laravel integration for Pest (MIT)
- **mockery/mockery** v1.6 - Mock object framework (BSD-3-Clause)
- **fakerphp/faker** v1.23 - Fake data generator (MIT)
- **nunomaduro/collision** v8.6 - Beautiful error reporting (MIT)

---

## Frontend Dependencies (NPM)

### Build Tools
- **vite** v7.0 - Next-generation build tool (MIT)
- **laravel-vite-plugin** v2.0 - Laravel integration for Vite (MIT)
- **concurrently** v9.0 - Run multiple commands concurrently (MIT)

### CSS Framework & Plugins
- **tailwindcss** v3.1 - Utility-first CSS framework (MIT)
- **@tailwindcss/forms** v0.5 - Form styling plugin (MIT)
- **@tailwindcss/vite** v4.0 - Vite integration (MIT)
- **postcss** v8.4 - CSS transformation tool (MIT)
- **autoprefixer** v10.4 - CSS vendor prefixer (MIT)

### JavaScript
- **alpinejs** v3.4 - Lightweight JavaScript framework (MIT)
- **@alpinejs/collapse** v3.15 - Collapse/expand plugin (MIT)
- **axios** v1.11 - Promise-based HTTP client (MIT)

---

## Frontend Libraries (CDN)

### Visualization & UI
- **Chart.js** v4.4.0 - JavaScript charting library (MIT)
  - Source: `https://cdn.jsdelivr.net/npm/chart.js`
- **chartjs-plugin-zoom** v2.0.1 - Zoom and pan plugin for Chart.js (MIT)
  - Source: `https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom`
- **Leaflet.js** v1.9.4 - Interactive maps library (BSD-2-Clause)
  - Source: `https://unpkg.com/leaflet`
- **Heroicons** - Beautiful hand-crafted SVG icons (MIT)
  - Source: Inline SVG in templates
- **Simple Icons** - 150+ brand icons for Link in Bio (CC0 1.0)
  - Source: `https://simpleicons.org`
  - Usage: Social media and brand icons in bio pages
  - Location: `public/images/brands/`

---

## Inspiration & Design References

The Link in Bio feature was inspired by these amazing open-source projects:

- **LinkStack** - Self-hosted link management platform (AGPL-3.0)
  - Repository: `https://github.com/LinkStackOrg/LinkStack`
  - Inspiration: Theme system, block-based layout, social icons placement
  - Note: No code copied; design patterns used as reference

- **LittleLink** - DIY self-hosted link page (MIT)
  - Repository: `https://github.com/sethcottle/littlelink`
  - Inspiration: Minimalist button styling, brand icon approach
  - Note: No code copied; aesthetic principles referenced

- **Linktree** - Commercial link-in-bio service
  - Website: `https://linktr.ee`
  - Inspiration: General UX patterns for bio pages
  - Note: No assets or code used; concept reference only

### Fonts
- **Inter** - Variable font family (SIL Open Font License 1.1)
  - Source: `https://fonts.bunny.net`
- **Figtree** - Modern sans-serif font (SIL Open Font License 1.1)
  - Source: `https://fonts.bunny.net`

---

## External Services & APIs

### Security & Bot Protection
- **Cloudflare Turnstile** - CAPTCHA alternative for bot protection
  - License: [Cloudflare Terms of Service](https://www.cloudflare.com/terms/)
  - Usage: Registration, login, abuse reporting

### Authentication
- **Google OAuth 2.0** - Social login provider
  - License: [Google API Terms of Service](https://developers.google.com/terms)
  - Usage: Alternative login method

### Geolocation & IP Intelligence
- **IP-API.com** - IP geolocation API
  - License: Free tier for non-commercial use
  - Usage: Fallback IP location service

- **IPInfo.io** - IP address data provider
  - License: Free tier (50,000 requests/month)
  - Usage: Enhanced IP information

- **AbstractAPI** - IP geolocation & VPN detection
  - License: Free tier (20,000 requests/month)
  - Usage: Location and proxy detection

- **ProxyCheck.io** - VPN/Proxy detection service
  - License: Free tier (1,000 requests/day)
  - Usage: Abuse prevention

### Media & Assets
- **FlagCDN.com** - Country flag images
  - License: Public Domain (flag images only)
  - Source: `https://flagcdn.com`
  - Usage: Country flags in analytics
  - Note: Website design/code owned by Flagpedia.net, flag images are public domain

---

## Database & Infrastructure

### Database Systems
- **MySQL** 8.0+ - Relational database (GPLv2)
- **PostgreSQL** 13+ - Advanced relational database (PostgreSQL License)
- **SQLite** 3+ - Embedded database (Public Domain)

### Caching & Queues
- **Redis** 6.0+ - In-memory data structure store (BSD-3-Clause, optional)
  - Usage: Cache, sessions, queue driver

### Process Management
- **Supervisor** - Process control system (BSD-like license, optional)
  - Usage: Queue worker management in production

---

## Development Tools

### Code Quality
- **PHP_CodeSniffer** - Detect coding standard violations (BSD-3-Clause)
- **PHPStan** - Static analysis tool (MIT)

### Version Control
- **Git** - Distributed version control system (GPLv2)
- **GitHub** - Code hosting platform

---

## Custom Assets & Branding

- **hel.ink Logo** - Custom design (© 2025 hel.ink)
  - Location: `resources/img/Logo.png`
  - Served via: `/brand/logo.png`, `/brand/logo-dark.png`

- **hel.ink Favicon** - Custom design (© 2025 hel.ink)
  - Location: `resources/img/Favicon.png`
  - Served via: `/brand/favicon.png`

---

## License Summary

| License | Count | Libraries |
|---------|-------|-----------|
| **MIT** | 30+ | Laravel, Alpine.js, Chart.js, Tailwind CSS, Vite, Axios, Pest, etc. |
| **CC0 1.0** | 1 | Simple Icons (brand icons) |
| **BSD-2-Clause** | 1 | Leaflet.js |
| **BSD-3-Clause** | 3 | Redis, Sentry, Mockery |
| **Apache-2.0** | 1 | GeoIP2 (legacy, not actively used) |
| **SIL OFL 1.1** | 2 | Inter font, Figtree font |
| **GPLv2** | 1 | MySQL |
| **PostgreSQL** | 1 | PostgreSQL |
| **Public Domain** | 1 | Flag images (FlagCDN) |

---

## Special Thanks

- **Laravel Community** - For extensive documentation and support
- **Taylor Otwell** - For creating Laravel
- **Caleb Porzio** - For Alpine.js
- **Adam Wathan** - For Tailwind CSS
- **Simple Icons Contributors** - For 150+ brand icons
- **LinkStack Team** - For open-source link management inspiration
- **Seth Cottle** - For LittleLink and the self-hosted bio page movement
- **Cloudflare** - For Turnstile CAPTCHA service
- **All Open Source Contributors** - For making this project possible

---

## Reporting Issues

If you notice any missing attributions or license issues, please contact us:
- **Email**: support@hel.ink
- **GitHub Issues**: [hel.ink/issues](https://github.com/navi-crwn/hel.ink/issues)

---

**Last Updated**: December 12, 2025

**Total Dependencies**: 45+ core libraries and services powering HEL.ink
