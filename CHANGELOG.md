# Changelog

All notable changes to HEL.ink will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.0] - 2025-12-12

### Added
- **Link in Bio** - Create beautiful bio pages at hel.ink/b/username
  - 20+ themes with dark/light variants
  - Background animations (rain, snow, stars, confetti, fireflies)
  - Hover effects (glow, scale, tilt, shake)
  - Entrance animations (fade, slide, pop, bounce)
  - Attention animations (pulse, bounce, glow, shake, heartbeat)
  - Custom fonts (Inter, Poppins, Roboto, Playfair, Space Grotesk, JetBrains Mono)
  - Social media icons with multiple positions
  - Block types: links, text, video embeds, Spotify, maps, countdowns, dividers
  - Password protection for bio pages
  - QR code generation for bio pages
  - Analytics tracking (views, clicks, referrers, devices, countries)
- **Brand Icons** - 150+ SVG brand icons from Simple Icons
- **Animation System** - Full animation support for bio page elements
- **Admin Bio Dashboard** - Monitor and manage all bio pages

### Changed
- Rebranded from "Hop Easy Link" to "HEL.ink"
- Updated all marketing pages with casual professional tone
- Improved README with collapsible sections
- Cleaned up codebase (removed debug logs, redundant comments)
- Updated footer and navigation branding

### Fixed
- Fixed broken brand icons in bio pages
- Fixed "Explore Features" link pointing to non-existent route
- Fixed emdash usage across marketing pages
- Fixed inconsistent branding across error pages

### Security
- Added Content Security Policy headers
- Improved rate limiting on API endpoints
- Enhanced abuse reporting system

## [1.0.0] - 2025-11-18

### Added
- Initial release
- URL shortening with custom slugs
- Password protection for links
- Link expiration with custom dates
- Folder organization system
- Tag-based categorization
- QR code generation (PNG/SVG)
- Click analytics with geographic data
- Device and browser tracking
- Referrer tracking
- Admin dashboard
- User management
- IP ban system
- Domain blacklist
- Abuse reporting
- Google OAuth integration
- Two-factor authentication
- API with token authentication
- Cloudflare Turnstile integration
- Email verification system

---

## Migration Notes

### Upgrading to 2.0.0
1. Run database migrations: `php artisan migrate`
2. Clear caches: `php artisan optimize:clear`
3. Rebuild assets: `npm run build`
4. Update environment variables if needed

### New Environment Variables (2.0.0)
No new required environment variables. Bio pages use existing database.
