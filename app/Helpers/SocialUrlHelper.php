<?php

namespace App\Helpers;

class SocialUrlHelper
{
    /** Build a proper URL for a social platform from username or URL */
    public static function buildUrl(string $platform, string $value): string
    {
        $value = trim($value);
        // If already a full URL, validate and return as-is (or extract relevant part)
        if (preg_match('/^(https?:\/\/|mailto:|tel:|sms:|viber:|skype:|weixin:)/i', $value)) {
            return self::validateAndCleanUrl($platform, $value);
        }
        // Strip @ prefix for consistent handling (will be re-added if needed)
        $cleanValue = ltrim($value, '@');
        // Strip $ prefix for Cash App type handles
        $cleanValue = ltrim($cleanValue, '$');

        // Platform-specific URL building
        return self::getPlatformUrl($platform, $cleanValue, $value);
    }

    /** Validate and clean URL for specific protocols */
    private static function validateAndCleanUrl(string $platform, string $url): string
    {
        if ($platform === 'email') {
            return str_starts_with($url, 'mailto:') ? $url : 'mailto:'.$url;
        }
        if ($platform === 'phone') {
            return str_starts_with($url, 'tel:') ? $url : 'tel:'.preg_replace('/[^+0-9]/', '', $url);
        }
        if ($platform === 'sms') {
            return str_starts_with($url, 'sms:') ? $url : 'sms:'.preg_replace('/[^+0-9]/', '', $url);
        }

        return $url;
    }

    /** Get platform URL from username/handle */
    private static function getPlatformUrl(string $platform, string $cleanValue, string $originalValue): string
    {
        $platform = strtolower(str_replace('_', '-', $platform));

        return match ($platform) {
            'instagram' => "https://instagram.com/{$cleanValue}",
            'twitter', 'x' => "https://x.com/{$cleanValue}",
            'facebook', 'fb' => "https://facebook.com/{$cleanValue}",
            'youtube', 'yt' => "https://youtube.com/@{$cleanValue}",
            'youtube-music' => "https://music.youtube.com/channel/{$cleanValue}",
            'tiktok' => "https://tiktok.com/@{$cleanValue}",
            'threads' => "https://threads.net/@{$cleanValue}",
            'linkedin' => str_contains($cleanValue, '/') ? "https://linkedin.com/{$cleanValue}" : "https://linkedin.com/in/{$cleanValue}",
            'pinterest' => "https://pinterest.com/{$cleanValue}",
            'snapchat' => "https://snapchat.com/add/{$cleanValue}",
            'reddit' => self::buildRedditUrl($cleanValue),
            'tumblr' => str_contains($cleanValue, '.') ? "https://{$cleanValue}" : "https://{$cleanValue}.tumblr.com",
            'bluesky', 'bluesky-alt' => str_contains($cleanValue, '.') ? "https://bsky.app/profile/{$cleanValue}" : "https://bsky.app/profile/{$cleanValue}.bsky.social",
            'mastodon' => self::buildMastodonUrl($originalValue),
            'bereal' => "https://bfrnd.link/{$cleanValue}",
            'lemmy' => self::buildLemmyUrl($originalValue),
            'pixelfed' => self::buildPixelfedUrl($originalValue),
            'nostr' => str_starts_with($cleanValue, 'npub') ? "https://njump.me/{$cleanValue}" : "https://njump.me/{$cleanValue}",
            'spacehey' => "https://spacehey.com/profile?id={$cleanValue}",
            'vero' => "https://vero.co/{$cleanValue}",
            'whatsapp', 'wa' => 'https://wa.me/'.preg_replace('/[^0-9]/', '', $cleanValue),
            'telegram', 'tg' => "https://t.me/{$cleanValue}",
            'messenger' => "https://m.me/{$cleanValue}",
            'discord' => str_contains($cleanValue, '/') ? "https://discord.com/{$cleanValue}" : "https://discord.gg/{$cleanValue}",
            'signal', 'signal-alt' => "https://signal.me/#p/+{$cleanValue}",
            'line' => "https://line.me/ti/p/{$cleanValue}",
            'slack' => str_contains($cleanValue, '.') ? "https://{$cleanValue}" : "https://{$cleanValue}.slack.com",
            'viber' => 'viber://chat?number='.preg_replace('/[^0-9]/', '', $cleanValue),
            'keybase' => "https://keybase.io/{$cleanValue}",
            'simplex' => "https://simplex.chat/contact#{$cleanValue}",
            'matrix' => self::buildMatrixUrl($originalValue),
            'guilded' => "https://guilded.gg/{$cleanValue}",
            'threema' => "https://threema.id/{$cleanValue}",
            'spotify', 'spotify-alt' => self::buildSpotifyUrl($cleanValue),
            'apple-music', 'applemusic' => "https://music.apple.com/profile/{$cleanValue}",
            'apple-podcasts', 'applepodcasts' => "https://podcasts.apple.com/podcast/{$cleanValue}",
            'amazon-music', 'amazonmusic' => "https://music.amazon.com/artists/{$cleanValue}",
            'soundcloud' => "https://soundcloud.com/{$cleanValue}",
            'bandcamp' => str_contains($cleanValue, '.') ? "https://{$cleanValue}" : "https://{$cleanValue}.bandcamp.com",
            'deezer' => "https://deezer.com/profile/{$cleanValue}",
            'tidal' => "https://tidal.com/browse/artist/{$cleanValue}",
            'qobuz' => "https://open.qobuz.com/artist/{$cleanValue}",
            'mixcloud' => "https://mixcloud.com/{$cleanValue}",
            'audiomack' => "https://audiomack.com/{$cleanValue}",
            'last-fm', 'lastfm' => "https://last.fm/user/{$cleanValue}",
            'google-podcasts' => "https://podcasts.google.com/feed/{$cleanValue}",
            'twitch' => "https://twitch.tv/{$cleanValue}",
            'kick', 'kick-alt' => "https://kick.com/{$cleanValue}",
            'vimeo' => "https://vimeo.com/{$cleanValue}",
            'dailymotion' => "https://dailymotion.com/{$cleanValue}",
            'rumble' => "https://rumble.com/user/{$cleanValue}",
            'bilibili' => "https://space.bilibili.com/{$cleanValue}",
            'github' => "https://github.com/{$cleanValue}",
            'gitlab' => "https://gitlab.com/{$cleanValue}",
            'dev', 'dev-to' => "https://dev.to/{$cleanValue}",
            'stack-overflow', 'stackoverflow' => is_numeric($cleanValue) ? "https://stackoverflow.com/users/{$cleanValue}" : "https://stackoverflow.com/users/{$cleanValue}",
            'hashnode' => "https://hashnode.com/@{$cleanValue}",
            'leetcode' => "https://leetcode.com/u/{$cleanValue}",
            'hackerrank' => "https://hackerrank.com/profile/{$cleanValue}",
            'codepen' => "https://codepen.io/{$cleanValue}",
            'codeberg' => "https://codeberg.org/{$cleanValue}",
            'dribbble' => "https://dribbble.com/{$cleanValue}",
            'behance' => "https://behance.net/{$cleanValue}",
            'figma' => "https://figma.com/@{$cleanValue}",
            'artstation' => "https://artstation.com/{$cleanValue}",
            'unsplash' => "https://unsplash.com/@{$cleanValue}",
            'vsco' => "https://vsco.co/{$cleanValue}",
            'flickr' => "https://flickr.com/photos/{$cleanValue}",
            'deviantart' => "https://deviantart.com/{$cleanValue}",
            'steam' => "https://steamcommunity.com/id/{$cleanValue}",
            'xbox' => "https://xbox.com/play/user/{$cleanValue}",
            'playstation' => "https://psnprofiles.com/{$cleanValue}",
            'vrchat' => "https://vrchat.com/home/user/{$cleanValue}",
            'osu' => "https://osu.ppy.sh/users/{$cleanValue}",
            'itch-io', 'itch' => str_contains($cleanValue, '.') ? "https://{$cleanValue}" : "https://{$cleanValue}.itch.io",
            'gog' => "https://gog.com/u/{$cleanValue}",
            'epic-games', 'epicgames' => "https://store.epicgames.com/u/{$cleanValue}",
            'roblox' => "https://roblox.com/users/{$cleanValue}/profile",
            'roll20' => "https://app.roll20.net/users/{$cleanValue}",
            'myanimelist', 'mal' => "https://myanimelist.net/profile/{$cleanValue}",
            'anilist' => "https://anilist.co/user/{$cleanValue}",
            'trakt' => "https://trakt.tv/users/{$cleanValue}",
            'letterboxd' => "https://letterboxd.com/{$cleanValue}",
            'goodreads' => "https://goodreads.com/user/show/{$cleanValue}",
            'discogs', 'discogs-alt' => "https://discogs.com/user/{$cleanValue}",
            'paypal' => "https://paypal.me/{$cleanValue}",
            'venmo' => "https://venmo.com/{$cleanValue}",
            'cash-app', 'cashapp' => "https://cash.app/\${$cleanValue}",
            'patreon' => "https://patreon.com/{$cleanValue}",
            'ko-fi', 'kofi' => "https://ko-fi.com/{$cleanValue}",
            'buy-me-a-coffee', 'buymeacoffee' => "https://buymeacoffee.com/{$cleanValue}",
            'gofundme' => "https://gofundme.com/f/{$cleanValue}",
            'kickstarter' => "https://kickstarter.com/profile/{$cleanValue}",
            'gumroad' => str_contains($cleanValue, '.') ? "https://{$cleanValue}" : "https://{$cleanValue}.gumroad.com",
            'redbubble' => "https://redbubble.com/people/{$cleanValue}",
            'revolut' => "https://revolut.me/{$cleanValue}",
            'amazon' => "https://amazon.com/shop/{$cleanValue}",
            'etsy' => "https://etsy.com/shop/{$cleanValue}",
            'shop' => "https://shop.app/{$cleanValue}",
            'fiverr' => "https://fiverr.com/{$cleanValue}",
            'upwork' => "https://upwork.com/freelancers/{$cleanValue}",
            'appstore' => "https://apps.apple.com/app/{$cleanValue}",
            'google-play', 'googleplay' => "https://play.google.com/store/apps/details?id={$cleanValue}",
            'medium' => "https://medium.com/@{$cleanValue}",
            'substack' => str_contains($cleanValue, '.') ? "https://{$cleanValue}" : "https://{$cleanValue}.substack.com",
            'notion' => "https://notion.so/{$cleanValue}",
            'trello' => "https://trello.com/{$cleanValue}",
            'obsidian' => "https://obsidian.md/{$cleanValue}",
            'google-drive' => "https://drive.google.com/drive/folders/{$cleanValue}",
            'calendly' => "https://calendly.com/{$cleanValue}",
            'cal' => "https://cal.com/{$cleanValue}",
            'zoom' => "https://zoom.us/j/{$cleanValue}",
            'meetup', 'meetup-alt' => "https://meetup.com/members/{$cleanValue}",
            'mailchimp' => "https://mailchimp.com/{$cleanValue}",
            'wordpress' => str_contains($cleanValue, '.') ? "https://{$cleanValue}" : "https://{$cleanValue}.wordpress.com",
            'opensea' => "https://opensea.io/{$cleanValue}",
            'xing' => "https://xing.com/profile/{$cleanValue}",
            'researchgate' => "https://researchgate.net/profile/{$cleanValue}",
            'orcid' => self::buildOrcidUrl($cleanValue),
            'google-scholar' => "https://scholar.google.com/citations?user={$cleanValue}",
            'clubhouse' => "https://joinclubhouse.com/@{$cleanValue}",
            'cameo' => "https://cameo.com/{$cleanValue}",
            'ngl' => "https://ngl.link/{$cleanValue}",
            'product-hunt', 'producthunt' => "https://producthunt.com/@{$cleanValue}",
            'kit' => "https://kit.co/{$cleanValue}",
            'onlyfans' => "https://onlyfans.com/{$cleanValue}",
            'strava' => "https://strava.com/athletes/{$cleanValue}",
            'email', 'email-alt' => "mailto:{$cleanValue}",
            'phone' => 'tel:+'.preg_replace('/[^0-9]/', '', $cleanValue),
            'sms' => 'sms:+'.preg_replace('/[^0-9]/', '', $cleanValue),
            'website', 'link', 'blog' => str_starts_with($cleanValue, 'http') ? $cleanValue : "https://{$cleanValue}",
            // Default: assume it's a domain or username
            default => str_contains($cleanValue, '.') ? "https://{$cleanValue}" : "https://{$platform}.com/{$cleanValue}"
        };
    }

    /**
     * Build Reddit URL - handles r/subreddit, u/user, and username
     */
    private static function buildRedditUrl(string $value): string
    {
        $value = ltrim($value, '/'); // Remove leading slash
        // Check for subreddit (r/subreddit)
        if (preg_match('/^r\/(.+)$/i', $value, $matches)) {
            return "https://reddit.com/r/{$matches[1]}";
        }
        // Check for user profile (u/username)
        if (preg_match('/^u\/(.+)$/i', $value, $matches)) {
            return "https://reddit.com/u/{$matches[1]}";
        }

        // Default to user profile
        return "https://reddit.com/user/{$value}";
    }

    /**
     * Build Mastodon URL from @user@instance format
     */
    private static function buildMastodonUrl(string $value): string
    {
        $value = ltrim($value, '@');
        if (str_contains($value, '@')) {
            $parts = explode('@', $value);
            if (count($parts) >= 2) {
                $user = $parts[0];
                $instance = $parts[1];

                return "https://{$instance}/@{$user}";
            }
        }

        // Default to mastodon.social if no instance specified
        return "https://mastodon.social/@{$value}";
    }

    /**
     * Build Lemmy URL from @user@instance format
     */
    private static function buildLemmyUrl(string $value): string
    {
        $value = ltrim($value, '@');
        if (str_contains($value, '@')) {
            $parts = explode('@', $value);
            if (count($parts) >= 2) {
                $user = $parts[0];
                $instance = $parts[1];

                return "https://{$instance}/u/{$user}";
            }
        }

        // Default to lemmy.ml
        return "https://lemmy.ml/u/{$value}";
    }

    /**
     * Build Pixelfed URL from @user@instance format
     */
    private static function buildPixelfedUrl(string $value): string
    {
        $value = ltrim($value, '@');
        if (str_contains($value, '@')) {
            $parts = explode('@', $value);
            if (count($parts) >= 2) {
                $user = $parts[0];
                $instance = $parts[1];

                return "https://{$instance}/{$user}";
            }
        }

        // Default to pixelfed.social
        return "https://pixelfed.social/{$value}";
    }

    /**
     * Build Matrix URL from @user:server format
     */
    private static function buildMatrixUrl(string $value): string
    {
        $value = ltrim($value, '@');
        if (str_contains($value, ':')) {
            return "https://matrix.to/#/@{$value}";
        }

        // Default to matrix.org
        return "https://matrix.to/#/@{$value}:matrix.org";
    }

    /**
     * Build ORCID URL - expects format 0000-0000-0000-0000
     */
    private static function buildOrcidUrl(string $value): string
    {
        // Remove any non-digits and dashes
        $clean = preg_replace('/[^0-9\-]/', '', $value);
        // Ensure proper format
        if (! preg_match('/^\d{4}-\d{4}-\d{4}-\d{4}$/', $clean)) {
            // Try to format it
            $digits = preg_replace('/[^0-9]/', '', $clean);
            if (strlen($digits) === 16) {
                $clean = substr($digits, 0, 4).'-'.substr($digits, 4, 4).'-'.substr($digits, 8, 4).'-'.substr($digits, 12, 4);
            }
        }

        return "https://orcid.org/{$clean}";
    }

    /**
     * Build Spotify URL - handle different content types
     */
    private static function buildSpotifyUrl(string $value): string
    {
        // Check if it looks like a Spotify ID
        if (preg_match('/^[a-zA-Z0-9]{22}$/', $value)) {
            return "https://open.spotify.com/user/{$value}";
        }

        // Otherwise assume it's a username
        return "https://open.spotify.com/user/{$value}";
    }

    /**
     * Get placeholder text for a platform
     */
    public static function getPlaceholder(string $platform): string
    {
        return match (strtolower($platform)) {
            'whatsapp', 'wa' => '+62812345678',
            'telegram', 'tg' => '@username',
            'twitter', 'x' => '@username',
            'instagram' => '@username',
            'facebook', 'fb' => 'yourpage atau facebook.com/yourpage',
            'youtube', 'yt' => '@channel',
            'youtube-music' => 'channel_id',
            'tiktok' => '@username',
            'linkedin' => 'yourname (linkedin.com/in/yourname)',
            'github' => 'username',
            'email' => 'you@email.com',
            'website', 'blog', 'link' => 'https://yoursite.com',
            'discord' => 'invite_code atau discord.gg/code',
            'spotify' => 'username atau spotify_id',
            'threads' => '@username',
            'mastodon' => '@user@instance.social',
            'bluesky' => 'handle.bsky.social',
            'reddit' => 'username atau r/subreddit',
            'tumblr' => 'blogname (blogname.tumblr.com)',
            'twitch' => 'username',
            'snapchat' => 'username',
            'pinterest' => 'username',
            'bereal' => 'username',
            'lemmy' => '@user@instance',
            'pixelfed' => '@user@instance',
            'matrix' => '@user:matrix.org',
            'nostr' => 'npub1...',
            'orcid' => '0000-0000-0000-0000',
            'phone' => '+62812345678',
            'sms' => '+62812345678',
            'signal' => '+62812345678',
            'cash-app' => '$username',
            'venmo' => '@username',
            'paypal' => 'username (paypal.me/username)',
            default => 'username atau URL lengkap'
        };
    }

    /**
     * Validate user input for a platform
     * Returns error message if invalid, null if valid
     */
    public static function validateInput(string $platform, string $value): ?string
    {
        $value = trim($value);
        if (empty($value)) {
            return 'Value is required';
        }
        // If it's a full URL, accept it
        if (preg_match('/^https?:\/\//i', $value)) {
            return null; // Valid URL
        }

        // Platform-specific validation
        return match (strtolower($platform)) {
            'email' => filter_var($value, FILTER_VALIDATE_EMAIL) ? null : 'Invalid email format',
            'phone', 'sms', 'whatsapp', 'signal' => preg_match('/^\+?[0-9\s\-]+$/', $value) ? null : 'Invalid phone number',
            'orcid' => preg_match('/^[\d\-]+$/', $value) && strlen(preg_replace('/[^0-9]/', '', $value)) === 16 ? null : 'ORCID must be 16 digits',
            default => null // Accept any value
        };
    }
}
