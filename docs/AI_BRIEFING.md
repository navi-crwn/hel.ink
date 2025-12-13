# 🔗 HEL.ink Project Briefing
## For AI Assistants & New Contributors

> **Last Updated:** December 13, 2025  
> **Current Version:** v2.0.0  
> **Next Target:** v2.1.0 (Custom Domains)

---

## 📋 Executive Summary

**HEL.ink** adalah platform URL shortener + Link in Bio open-source yang dibangun dengan Laravel 12. Aplikasi ini sudah **production-ready** dan berjalan di https://hel.ink.

### Core Value Proposition
- **URL Shortener**: Custom slugs, password protection, expiration, QR codes
- **Link in Bio**: 20+ themes, animations, 150+ brand icons, embeds
- **Analytics**: Real-time clicks, geo-location, devices, referrers
- **Self-hostable**: Deploy on your own server with full control

---

## 🏗️ Tech Stack

| Layer | Technology | Version |
|-------|------------|---------|
| **Framework** | Laravel | 12.x |
| **Language** | PHP | 8.2+ |
| **Database** | MySQL | 8.0+ |
| **Frontend** | Tailwind CSS | 3.x |
| **JS Framework** | Alpine.js | 3.x |
| **Build Tool** | Vite | 7.x |
| **Charts** | Chart.js | 4.4 |
| **Maps** | Leaflet.js | 1.9 |
| **Auth** | Laravel Breeze + Socialite | - |
| **2FA** | pragmarx/google2fa-laravel | 2.3 |
| **QR Code** | simplesoftwareio/simple-qrcode | 4.2 |
| **GeoIP** | geoip2/geoip2 | 3.2 |

---

## 📁 Project Structure

```
/var/www/helink/shortlink-app/
├── app/
│   ├── Console/Commands/       # Artisan commands
│   ├── Http/
│   │   ├── Controllers/        # All controllers
│   │   │   ├── Admin/          # Admin controllers
│   │   │   ├── Api/            # API controllers
│   │   │   └── Auth/           # Auth controllers
│   │   ├── Middleware/         # Custom middleware
│   │   └── Requests/           # Form requests
│   ├── Models/                 # Eloquent models (17 total)
│   ├── Services/               # Business logic services
│   │   ├── GeoIP/              # GeoIP providers
│   │   └── ProxyDetection/     # VPN/Proxy detection
│   └── View/Components/        # Blade components
├── config/                     # Configuration files
├── database/
│   ├── migrations/             # 50+ migrations
│   └── seeders/                # Test data seeders
├── public/
│   ├── images/brands/          # 150+ SVG brand icons
│   └── build/                  # Compiled assets
├── resources/
│   ├── views/                  # Blade templates
│   │   ├── admin/              # Admin views
│   │   ├── auth/               # Auth views
│   │   ├── bio/                # Bio page views
│   │   ├── components/         # Reusable components
│   │   ├── marketing/          # Public marketing pages
│   │   └── layouts/            # Layout templates
│   ├── css/                    # Tailwind source
│   └── js/                     # Alpine.js source
├── routes/
│   ├── web.php                 # Web routes
│   ├── api.php                 # API routes
│   └── auth.php                # Auth routes
└── tests/                      # Pest test files
```

---

## 🗄️ Database Models

### Core Models
| Model | Table | Purpose |
|-------|-------|---------|
| `User` | users | User accounts, roles, 2FA |
| `Link` | links | Shortened URLs |
| `LinkClick` | link_clicks | Click analytics |
| `Folder` | folders | Link organization |
| `Tag` | tags | Link categorization |
| `Comment` | comments | Link comments |

### Bio System Models
| Model | Table | Purpose |
|-------|-------|---------|
| `BioPage` | bio_pages | Bio page configuration |
| `BioLink` | bio_links | Bio page blocks/links |
| `BioClick` | bio_clicks | Bio page analytics |

### Security Models
| Model | Table | Purpose |
|-------|-------|---------|
| `IpBan` | ip_bans | Blocked IPs |
| `IpWatchlist` | ip_watchlist | Suspicious IPs |
| `DomainBlacklist` | domain_blacklist | Blocked domains |
| `AbuseReport` | abuse_reports | User reports |
| `LoginHistory` | login_histories | Login tracking |

### System Models
| Model | Table | Purpose |
|-------|-------|---------|
| `ApiToken` | api_tokens | API authentication |
| `ActivityLog` | activity_logs | User activity |
| `SeoSetting` | seo_settings | Global SEO config |

---

## 👤 User Roles

| Role | Capabilities |
|------|-------------|
| **user** | Create links, bio pages, view own analytics |
| **superadmin** | Full admin access, manage all users/links |

Role is stored in `users.role` column. Check with `$user->isSuperAdmin()`.

---

## 🔐 Authentication Flow

1. **Email/Password** - Standard Laravel Breeze
2. **Google OAuth** - Via Laravel Socialite
3. **Two-Factor Auth** - TOTP with recovery codes
4. **Cloudflare Turnstile** - CAPTCHA on forms

---

## 🌐 Key Routes

### Public
- `GET /` - Landing page with shortener form
- `GET /{slug}` - Redirect to target URL
- `GET /b/{slug}` - Bio page display
- `GET /sitemap.xml` - Dynamic sitemap

### User Dashboard
- `GET /dashboard` - Main dashboard
- `GET /links` - Link management
- `GET /analytics` - Global analytics
- `GET /bio` - Bio page management

### Admin
- `GET /admin` - Admin dashboard
- `GET /admin/users` - User management
- `GET /admin/links` - All links
- `GET /admin/bio` - All bio pages

### API
- `POST /api/shorten` - Create short link
- `GET /api/links` - List user links

---

## ✅ Completed Features (v2.0)

### URL Shortener
- [x] Custom & random slugs
- [x] Password protection
- [x] Expiration dates
- [x] Redirect types (301, 302, 307)
- [x] QR code generation (PNG, SVG, JPG)
- [x] Custom OG meta tags
- [x] Folders & tags organization
- [x] Bulk operations & export

### Link in Bio
- [x] 20+ themes (Galaxy, Neon, Cyberpunk, Aurora, etc.)
- [x] Background animations (Rain, Snow, Stars, Hearts, etc.)
- [x] Entrance animations (Fade, Slide, Pop, Bounce)
- [x] Attention animations (Pulse, Shake, Glow, Heartbeat)
- [x] Hover effects (Scale, Glow, Lift, Glossy)
- [x] 150+ brand icons (Simple Icons)
- [x] Rich blocks (links, text, video, Spotify, maps, countdown, FAQ)
- [x] Custom CSS support
- [x] Password protection
- [x] SEO controls

### Analytics
- [x] Real-time click tracking
- [x] Geographic data (city, country)
- [x] Device & browser detection
- [x] Referrer tracking
- [x] ISP detection
- [x] Proxy/VPN detection
- [x] CSV export

### Security
- [x] Rate limiting
- [x] IP banning
- [x] Domain blacklist
- [x] Abuse reporting
- [x] Content Security Policy
- [x] Cloudflare Turnstile CAPTCHA

---

## 🎯 Next Milestone: v2.1 Custom Domains

### Goal
Allow users to add their own domains for short links (e.g., `short.mycompany.com/abc`).

### Required Tasks

#### 1. Database Migration
```sql
CREATE TABLE custom_domains (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    team_id BIGINT NULL,                    -- For future team feature
    domain VARCHAR(255) NOT NULL UNIQUE,
    verification_token VARCHAR(64) NOT NULL,
    verified_at TIMESTAMP NULL,
    ssl_status ENUM('pending', 'provisioning', 'active', 'failed') DEFAULT 'pending',
    ssl_expires_at TIMESTAMP NULL,
    is_default BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Add domain_id to links table
ALTER TABLE links ADD COLUMN domain_id BIGINT NULL;
ALTER TABLE links ADD FOREIGN KEY (domain_id) REFERENCES custom_domains(id) ON DELETE SET NULL;

-- Add domain_id to bio_pages table  
ALTER TABLE bio_pages ADD COLUMN domain_id BIGINT NULL;
ALTER TABLE bio_pages ADD FOREIGN KEY (domain_id) REFERENCES custom_domains(id) ON DELETE SET NULL;
```

#### 2. New Files Needed
```
app/Models/CustomDomain.php
app/Http/Controllers/CustomDomainController.php
app/Http/Controllers/Admin/CustomDomainController.php
app/Services/DomainVerificationService.php
app/Services/SslProvisioningService.php
app/Console/Commands/VerifyDomains.php
app/Console/Commands/CheckSslExpiry.php
resources/views/domains/index.blade.php
resources/views/domains/create.blade.php
resources/views/domains/verify.blade.php
database/migrations/xxxx_create_custom_domains_table.php
```

#### 3. Domain Verification Flow
```
User adds domain → Generate verification token
                 ↓
User adds DNS record: TXT _helink-verify.domain.com = {token}
                 ↓
System checks DNS (via dns_get_record or Cloudflare API)
                 ↓
If verified → Mark verified_at, start SSL provisioning
                 ↓
SSL active → Domain ready for use
```

#### 4. SSL Options

**Option A: Cloudflare (Recommended)**
- User adds domain to Cloudflare
- CNAME to hel.ink
- Cloudflare handles SSL automatically

**Option B: Let's Encrypt (Self-hosted)**
- Server runs Caddy or Certbot
- Wildcard cert or per-domain certs
- Auto-renewal via cron

#### 5. Redirect Logic Update

Current (`app/Http/Controllers/RedirectController.php`):
```php
// Find link by slug only
$link = Link::where('slug', $slug)->first();
```

New logic:
```php
// Find link by slug AND domain
$host = request()->getHost();
$domain = CustomDomain::where('domain', $host)->first();

if ($domain) {
    $link = Link::where('slug', $slug)
                ->where('domain_id', $domain->id)
                ->first();
} else {
    // Default domain (hel.ink)
    $link = Link::where('slug', $slug)
                ->whereNull('domain_id')
                ->first();
}
```

---

## 🔧 Environment Variables

Key variables in `.env`:
```env
APP_NAME=HEL.ink
APP_URL=https://hel.ink
SUPERADMIN_EMAIL=admin@hel.ink

DB_CONNECTION=mysql
DB_DATABASE=helink

MAIL_MAILER=smtp
GOOGLE_CLIENT_ID=xxx
GOOGLE_CLIENT_SECRET=xxx
TURNSTILE_SITE_KEY=xxx
TURNSTILE_SECRET_KEY=xxx
SENTRY_LARAVEL_DSN=xxx

# Future: Custom domains
CLOUDFLARE_API_TOKEN=xxx
CLOUDFLARE_ZONE_ID=xxx
```

---

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=LinkTest

# With coverage
php artisan test --coverage
```

---

## 📚 Key Files to Understand

| File | Purpose |
|------|---------|
| `routes/web.php` | All web routes |
| `app/Http/Controllers/RedirectController.php` | Handles /{slug} redirects |
| `app/Http/Controllers/LinkController.php` | Link CRUD |
| `app/Http/Controllers/BioPageController.php` | Bio page management |
| `app/Services/LinkService.php` | Link business logic |
| `app/Models/Link.php` | Link model |
| `app/Models/BioPage.php` | Bio page model |
| `config/shortener.php` | Shortener configuration |
| `resources/views/bio/show.blade.php` | Bio page display |

---

## ⚠️ Important Notes

1. **Production Database**: Live data exists. Always use migrations, never raw SQL.
2. **Slug Uniqueness**: Slugs must be unique. With custom domains, uniqueness is per-domain.
3. **Caching**: Links are cached. Clear cache when updating: `Cache::forget("slug:{$slug}")`
4. **Queue Worker**: Background jobs require `php artisan queue:work`
5. **Assets**: Run `npm run build` after CSS/JS changes

---

## 🚀 Development Commands

```bash
# Start development
php artisan serve
npm run dev

# Database
php artisan migrate
php artisan migrate:fresh --seed

# Cache
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Code quality
./vendor/bin/pint          # Format PHP
php artisan test           # Run tests

# Production build
npm run build
php artisan optimize
```

---

## 📞 Contact

- **Repository**: https://github.com/navi-crwn/hel.ink
- **Live Site**: https://hel.ink
- **Author**: Ivan Novskies (@navi-crwn)

---

*This document is maintained as a reference for AI assistants and contributors working on HEL.ink.*
