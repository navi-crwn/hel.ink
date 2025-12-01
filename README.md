# 🔗 Hel.ink: Modern URL Shortener

<p align="center">
  <img src="https://hel.ink/brand/logo-dark.png" alt="Hel.ink Logo" width="200"/>
</p>

<p align="center">
  <strong>A powerful, feature-rich URL shortening service built with Laravel 11</strong>
</p>

<p align="center">
  <a href="#-features">Features</a> •
  <a href="#-demo">Demo</a> •
  <a href="#-installation">Installation</a> •
  <a href="#-configuration">Configuration</a> •
  <a href="#-usage">Usage</a> •
  <a href="#-tech-stack">Tech Stack</a> •
  <a href="#-contributing">Contributing</a> •
  <a href="#-license">License</a>
</p>

---

## ✨ Features

### 🔐 Authentication & Security
- **Multiple Login Options**: Email/Password, Google OAuth
- **Two-Factor Authentication (2FA)**: TOTP-based security with QR codes
- **Email Verification**: Secure account activation
- **Password Reset**: Via email with catchphrase option
- **Rate Limiting**: Protection against brute force attacks
- **IP Blocking & Watchlist**: Admin controls for suspicious activity
- **Proxy Detection**: Prevent abuse from VPN/proxy users
- **Abuse Reporting**: Community-driven moderation system

### 🎯 Link Management
- **Custom Short URLs**: Create branded, memorable links
- **Folders & Tags**: Organize links efficiently
- **Bulk Operations**: Mass edit, delete, or organize links
- **QR Code Generation**: PNG, SVG, JPG with customizable colors
- **Password Protected Links**: Add extra security layer
- **Expiration Dates**: Auto-disable links after set time
- **Link Comments**: Collaborate with team notes
- **Status Management**: Active, inactive, archived links

### 📊 Analytics & Tracking
- **Real-time Click Tracking**: Detailed visitor analytics
- **Geographic Data**: IP-based location (city, country)
- **Device Detection**: Browser, OS, device type
- **Referrer Tracking**: Know where visitors come from
- **Click History**: Complete audit trail
- **Export Data**: CSV export for analysis
- **Dashboard Charts**: Visual insights at a glance

### 🎨 User Experience
- **Dark Mode**: Eye-friendly interface
- **Responsive Design**: Works on all devices
- **Modern UI**: Built with Tailwind CSS
- **Alpine.js**: Smooth, reactive interactions
- **Real-time Validation**: Instant feedback on forms
- **Toast Notifications**: Non-intrusive alerts

### ⚙️ Admin Panel
- **User Management**: View, edit, suspend users
- **Link Moderation**: Review and manage all links
- **Domain Blacklist**: Block malicious domains
- **Analytics Dashboard**: System-wide statistics
- **Queue Monitoring**: Background job oversight
- **Activity Logs**: Track all user actions
- **SEO Management**: Meta tags, sitemap control

### 🚀 Performance & Scalability
- **Queue Jobs**: Async processing for clicks and notifications
- **Database Indexing**: Optimized queries
- **Caching**: Redis/Memcached support
- **CDN Ready**: Static asset optimization
- **API Rate Limiting**: Protect resources

---



## 📋 Requirements

- **PHP**: 8.2 or higher
- **Composer**: Latest version
- **Node.js**: 18+ and NPM
- **Database**: MySQL 8.0+ or PostgreSQL 13+
- **Web Server**: Nginx or Apache
- **Optional**: Redis (for caching/queues)

---

## 🛠️ Installation

### 1. Clone Repository

```bash
git clone https://github.com/navi-crwn/hel.ink.git
cd helink
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

### 3. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Configure Database

Edit `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=helink
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Run Migrations

```bash
# Create database tables
php artisan migrate

# (Optional) Seed with sample data
php artisan db:seed
```

### 6. Build Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 7. Storage Setup

```bash
# Create symbolic link for storage
php artisan storage:link

# Set permissions
chmod -R 775 storage bootstrap/cache
```

### 8. Start Services

```bash
# Development server
php artisan serve

# Queue worker (in separate terminal)
php artisan queue:work

# (Optional) Schedule runner
php artisan schedule:work
```

Visit `http://localhost:8000` 🎉

---

## ⚙️ Configuration

### Email Setup

Configure your mail driver in `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@hel.ink
MAIL_FROM_NAME="Hel.ink"
```

### Google OAuth

1. Create project at [Google Cloud Console](https://console.cloud.google.com)
2. Enable Google+ API
3. Create OAuth 2.0 credentials
4. Add to `.env`:

```env
GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-client-secret
GOOGLE_REDIRECT_URI=https://yourdomain.com/auth/google/callback
```

### GeoIP Setup

For IP geolocation features:

```env
GEOIP_SERVICE=ipapi
IPAPI_KEY=your-api-key
```

### Cloudflare Turnstile (Optional)

For CAPTCHA protection:

```env
TURNSTILE_SITE_KEY=your-site-key
TURNSTILE_SECRET_KEY=your-secret-key
```

### Queue Configuration

For background jobs:

```env
QUEUE_CONNECTION=database
# or use Redis for better performance
QUEUE_CONNECTION=redis
```

---

## 📖 Usage

### Creating Short Links

1. **Dashboard**: Click "Create Link" button
2. **Enter URL**: Paste your long URL
3. **Customize** (optional):
   - Custom slug
   - Password protection
   - Expiration date
   - Folder/tags
4. **Generate**: Get your short link instantly

### Managing Links

- **Edit**: Click pencil icon on any link
- **Delete**: Click trash icon (with confirmation)
- **QR Code**: Download in PNG/SVG/JPG formats
- **Analytics**: View detailed click statistics
- **Bulk Actions**: Select multiple links for batch operations

### Using Folders & Tags

- **Create Folder**: Settings → Folders → New Folder
- **Assign Tags**: During link creation or editing
- **Filter**: Use sidebar to filter by folder/tag

### Exporting Data

1. Go to **Settings**
2. Select **Export Data**
3. Choose date range and filters
4. Download CSV file

---

## 🏗️ Tech Stack

### Backend
- **Framework**: Laravel 12 (PHP 8.2+)
- **Database**: MySQL / PostgreSQL / SQLite
- **Cache/Queue**: Redis (optional)
- **Authentication**: Laravel Breeze + Google OAuth (Socialite)
- **2FA**: Pragmarx Google2FA (TOTP-based)
- **QR Codes**: SimpleSoftwareIO/simple-qrcode
- **Error Tracking**: Sentry
- **Geolocation**: IP-API, IPInfo, AbstractAPI (multi-provider fallback)

### Frontend
- **CSS Framework**: Tailwind CSS 3 + Forms Plugin
- **JavaScript**: Alpine.js 3 + Collapse Plugin
- **Build Tool**: Vite 7
- **Charts**: Chart.js 4.4 + Zoom Plugin
- **Maps**: Leaflet.js 1.9
- **Icons**: Heroicons (inline SVG)
- **CAPTCHA**: Cloudflare Turnstile
- **Fonts**: Inter, Figtree (via Bunny Fonts CDN)

### DevOps & Testing
- **Testing**: Pest PHP 4 + PHPUnit
- **Code Quality**: Laravel Pint (PSR-12)
- **Development**: Laravel Sail (Docker)
- **Log Viewer**: Laravel Pail
- **Process Manager**: Supervisor (production)

### External Services
- **Email**: SMTP (configurable: Gmail, SendGrid, etc.)
- **OAuth**: Google OAuth 2.0
- **IP Detection**: Multi-provider system (IP-API, IPInfo, AbstractAPI)
- **Proxy Detection**: ProxyCheck.io
- **Flags**: FlagCDN.com (CC BY-SA 4.0)

> **📋 Complete List**: See [CREDITS.md](CREDITS.md) for all 40+ dependencies and their licenses.

---

## 🗂️ Project Structure

```
helink/
├── app/
│   ├── Http/
│   │   ├── Controllers/      # All controllers
│   │   ├── Middleware/       # Custom middleware
│   │   └── Requests/         # Form requests
│   ├── Models/               # Eloquent models
│   ├── Services/             # Business logic
│   └── Jobs/                 # Queue jobs
├── config/                   # Configuration files
├── database/
│   ├── migrations/           # Database migrations
│   └── seeders/              # Database seeders
├── public/                   # Public assets
├── resources/
│   ├── views/                # Blade templates
│   ├── js/                   # JavaScript files
│   └── css/                  # Stylesheets
├── routes/
│   ├── web.php               # Web routes
│   └── auth.php              # Auth routes
└── tests/                    # PHPUnit tests
```

---

## 🧪 Testing

Run tests with PHPUnit:

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=LinkTest

# With coverage
php artisan test --coverage
```

---

## 🔒 Security

### Password Requirements
- Minimum 8 characters
- Cannot reuse old password
- Rate limited attempts

### Rate Limiting
- Login: 5 attempts per minute
- Link creation: 10 per minute
- Password reset: 5 per hour

### Reporting Vulnerabilities

Please email security issues to: **security@hel.ink**

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. **Fork** the repository
2. **Create** a feature branch (`git checkout -b feature/amazing-feature`)
3. **Commit** your changes (`git commit -m 'Add amazing feature'`)
4. **Push** to branch (`git push origin feature/amazing-feature`)
5. **Open** a Pull Request

### Coding Standards
- Follow PSR-12 coding standard
- Write tests for new features
- Update documentation as needed
- Use meaningful commit messages

---

## 📄 License

This project is open-source software licensed under the **MIT License**.

### Third-Party Licenses

This project uses various open-source libraries and services with different licenses:

- **MIT**: Laravel, Alpine.js, Chart.js, Tailwind CSS, Vite, and most dependencies
- **BSD-2-Clause**: Leaflet.js
- **BSD-3-Clause**: Redis, Sentry, Mockery
- **Apache-2.0**: GeoIP2 library (legacy dependency)
- **SIL OFL 1.1**: Inter and Figtree fonts
- **GPLv2**: MySQL
- **PostgreSQL License**: PostgreSQL
- **Public Domain**: Flag images (FlagCDN)

See [CREDITS.md](CREDITS.md) for complete attribution and [LICENSE](LICENSE) for full license text.

---

## 👨‍💻 Author

**Ivan Novskies**
- GitHub: [@navi-crwn](https://github.com/navi-crwn)
- Reddit: [u/Disastrous_Ant_375](https://reddit.com/u/Disastrous_Ant_375)
- Email: support@hel.ink

---

## 🙏 Acknowledgments

- **Taylor Otwell** - For creating Laravel
- **Caleb Porzio** - For Alpine.js
- **Adam Wathan** - For Tailwind CSS
- **Cloudflare** - For Turnstile CAPTCHA
- **All Open Source Contributors** - Making this project possible

Full credits: [CREDITS.md](CREDITS.md)

---

## 📞 Support

- **Email**: support@hel.ink
- **Issues**: [GitHub Issues](https://github.com/navi-crwn/helink/issues)
- **Discussions**: [GitHub Discussions](https://github.com/navi-crwn/helink/discussions)

---

<p align="center">Made with ❤️ by Ivan Novskies</p>
<p align="center">⭐ Star this repo if you find it helpful!</p>
