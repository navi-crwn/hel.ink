# 🔗 HEL.ink v2.0

<p align="center">
  <img src="https://hel.ink/brand/logo-dark.png" alt="HEL.ink Logo" width="180"/>
</p>

<p align="center">
  <strong>Open-source URL shortener & Link in Bio platform</strong><br>
  Built with Laravel 12 • Self-hostable • Free forever
</p>

<p align="center">
  <a href="https://hel.ink">Live Demo</a> •
  <a href="https://hel.ink/b/hel">Bio Example</a> •
  <a href="#-quick-start">Quick Start</a> •
  <a href="CREDITS.md">Credits</a>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Laravel 12"/>
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP 8.2+"/>
  <img src="https://img.shields.io/badge/Tailwind-3.x-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white" alt="Tailwind CSS"/>
  <img src="https://img.shields.io/badge/License-MIT-green?style=flat-square" alt="MIT License"/>
</p>

<p align="center">
  <img src="https://img.shields.io/github/stars/navi-crwn/hel.ink?style=flat-square&color=yellow" alt="Stars"/>
  <img src="https://img.shields.io/github/forks/navi-crwn/hel.ink?style=flat-square" alt="Forks"/>
  <img src="https://img.shields.io/github/issues/navi-crwn/hel.ink?style=flat-square" alt="Issues"/>
  <img src="https://img.shields.io/github/last-commit/navi-crwn/hel.ink?style=flat-square" alt="Last Commit"/>
  <img src="https://komarev.com/ghpvc/?username=navi-crwn&label=views&color=brightgreen&style=flat-square" alt="Profile Views"/>
</p>

---

## What's HEL.ink?

HEL.ink is a modern link shortener with Link in Bio pages, built for creators and teams who want control over their links. Shorten URLs, track clicks, organize with folders, generate QR codes, and create beautiful bio pages. All features included, no premium tiers.

### Highlights

- **URL Shortener** — Custom slugs, passwords, expiration, QR codes
- **Link in Bio** — 20+ themes, animations, embeds (YouTube, Spotify, Maps)
- **Analytics** — Clicks, locations, devices, referrers, real-time stats
- **Organization** — Folders, tags, bulk actions, search
- **API Ready** — REST API for ShareX, automation, custom tools
- **Self-hostable** — Deploy on your own server with full control

---

## 🆕 v2.0 Updates

<details>
<summary><strong>Link in Bio Upgrades</strong></summary>

- 20+ themes: Galaxy, Neon, Cyberpunk, Aurora, Cherry, Midnight, Matrix, Ice, Lavender
- Background animations: Rain, Snow, Stars, Hearts, Leaves, Confetti, Particles
- Entrance animations: Fade, Slide, Pop, Bounce, Flip with stagger
- Attention animations: Pulse, Shake, Glow, Wiggle, Heartbeat, Rainbow
- Hover effects: Scale, Glow, Lift, Glossy, Color-shift
- New blocks: FAQ, vCard, Countdown, Maps, Code snippets
- 150+ brand icons from Simple Icons
- Share modal with QR generator

</details>

<details>
<summary><strong>Technical Updates</strong></summary>

- Upgraded to Laravel 12
- Canvas-based particle animations
- Better mobile responsiveness
- Improved SEO controls and meta tags
- Theme-aware styling system

</details>

---

## ✨ Features

<details>
<summary><strong>🔗 Link in Bio</strong></summary>

- 20+ professionally designed themes
- Background & entrance animations
- Rich blocks: links, text, images, videos, music, FAQ, vCard, countdown, maps
- 150+ social icons (powered by Simple Icons)
- Custom CSS support
- SEO controls, Google Analytics, Facebook Pixel, TikTok Pixel
- Password protection & age verification

</details>

<details>
<summary><strong>🎯 Link Management</strong></summary>

- Custom or random short URLs
- Password protection & expiration dates
- QR code generation (PNG, SVG, JPG)
- Folders & tags for organization
- Bulk operations & CSV export
- Link comments for collaboration

</details>

<details>
<summary><strong>📊 Analytics</strong></summary>

- Real-time click tracking
- Geographic data (city, country)
- Device & browser detection
- Referrer tracking
- Unique visitor counts
- Export to CSV

</details>

<details>
<summary><strong>🔐 Security</strong></summary>

- Email/password + Google OAuth
- Two-factor authentication (TOTP)
- Rate limiting & IP blocking
- Proxy/VPN detection
- Abuse reporting system

</details>

<details>
<summary><strong>🔌 API & Integrations</strong></summary>

- REST API with Bearer auth
- ShareX integration
- 100 requests/hour rate limit
- CLI compatible
- Webhook ready

</details>

---

## 🚀 Quick Start

```bash
# Clone
git clone https://github.com/navi-crwn/hel.ink.git
cd hel.ink

# Install
composer install && npm install

# Setup
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link

# Build & Run
npm run build
php artisan serve
```

Visit `http://localhost:8000`

<details>
<summary><strong>Full Installation Guide</strong></summary>

### Requirements
- PHP 8.2+
- Composer
- Node.js 18+
- MySQL 8.0+ / PostgreSQL 13+ / SQLite
- Redis (optional, for caching)

### Database Setup

Edit `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=helink
DB_USERNAME=your_user
DB_PASSWORD=your_pass
```

### Queue Worker (Production)

```bash
php artisan queue:work
```

### Email Setup

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@email.com
MAIL_PASSWORD=your-app-password
```

### Google OAuth

```env
GOOGLE_CLIENT_ID=xxx
GOOGLE_CLIENT_SECRET=xxx
GOOGLE_REDIRECT_URI=https://yourdomain.com/auth/google/callback
```

</details>

---

## 📖 Usage

### Shortening URLs

1. Paste your long URL
2. (Optional) Set custom slug, password, expiration
3. Click "Shorten" and get your link

### Link in Bio

1. Go to Dashboard → Link in Bio
2. Create a new bio page with your username
3. Add blocks: links, text, images, videos, music
4. Choose a theme and customize colors
5. Share your page: `hel.ink/b/yourname`

### API Usage

```bash
# Generate token in Settings → API

curl -X POST https://hel.ink/api/shorten \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"url": "https://example.com", "alias": "my-link"}'
```

ShareX config available in Settings → API.

---

## 🏗️ Tech Stack

| Layer | Technologies |
|-------|-------------|
| **Backend** | Laravel 12, PHP 8.2+, MySQL/PostgreSQL |
| **Frontend** | Tailwind CSS 3, Alpine.js 3, Vite 7 |
| **Charts** | Chart.js 4.4, Leaflet.js 1.9 |
| **Icons** | Heroicons, Simple Icons (150+ brands) |
| **Auth** | Laravel Breeze, Socialite, Google2FA |
| **Services** | Cloudflare Turnstile, IP-API, FlagCDN |

Full dependency list: [CREDITS.md](CREDITS.md)

---

## 🧪 Testing

```bash
php artisan test
php artisan test --coverage
```

---

## 🤝 Contributing

1. Fork the repo
2. Create feature branch (`git checkout -b feature/cool-thing`)
3. Commit changes (`git commit -m 'Add cool thing'`)
4. Push & open PR

Please follow PSR-12 and include tests for new features.

---

## 📄 License & Credits

**MIT License** — Free to use, modify, and distribute. See [LICENSE](LICENSE).

Built with Laravel, Tailwind CSS, Alpine.js, and 45+ open-source libraries. Link in Bio inspired by [LinkStack](https://github.com/LinkStackOrg/LinkStack) and [LittleLink](https://github.com/sethcottle/littlelink). Brand icons from [Simple Icons](https://simpleicons.org).

Full credits, dependencies, and acknowledgments: **[CREDITS.md](CREDITS.md)**

---

## 👤 Author

**Ivan Novskies** — [@navi-crwn](https://github.com/navi-crwn)

---

<p align="center">
  <a href="https://hel.ink">hel.ink</a> • 
  <a href="https://github.com/navi-crwn/hel.ink">GitHub</a> •
  <a href="https://hel.ink/b/hel">Bio Page</a>
</p>
