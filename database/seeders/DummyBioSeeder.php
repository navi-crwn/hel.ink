<?php

namespace Database\Seeders;

use App\Models\BioLink;
use App\Models\BioPage;
use App\Models\User;
use Illuminate\Database\Seeder;

class DummyBioSeeder extends Seeder
{
    /**
     * Create a comprehensive dummy bio page for testing all platform links and social links.
     */
    public function run(): void
    {
        // Get or create test user
        $user = User::firstOrCreate(
            ['email' => 'test@helink.id'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password123'),
            ]
        );
        // Delete existing test bio page if exists
        BioPage::where('slug', 'testallplatforms')->delete();
        // Create comprehensive test bio page
        $bioPage = BioPage::create([
            'user_id' => $user->id,
            'slug' => 'testallplatforms',
            'title' => '🧪 Platform Test Page',
            'bio' => 'Testing all platform links and social icons. This page demonstrates every supported platform.',
            'avatar_url' => null,
            'avatar_shape' => 'circle',
            'theme' => 'modern',
            'layout' => 'centered',
            'background_type' => 'gradient',
            'background_gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
            'title_color' => '#ffffff',
            'bio_color' => '#e2e8f0',
            'link_bg_color' => '#ffffff',
            'link_text_color' => '#1e293b',
            'button_bg_color' => '#ffffff',
            'button_text_color' => '#1e293b',
            'block_shape' => 'rounded',
            'block_shadow' => 'md',
            'social_icons_position' => 'below_bio',
            'social_links' => $this->getAllSocialLinks(),
            'font_family' => 'Inter',
            'is_published' => true,
        ]);
        // Create all platform blocks
        $this->createAllPlatformBlocks($bioPage);
        $this->command->info('✅ Created dummy bio page at: /bio/testallplatforms');
        $this->command->info('   User: test@helink.id / password123');
    }

    /**
     * Get ALL social links for comprehensive testing
     * Format: {platform, value, enabled}
     *
     * NOTE: Social icons UI only displays up to 5 icons, but we include ALL
     * platforms here for complete testing of icons and links.
     */
    private function getAllSocialLinks(): array
    {
        return [
            ['platform' => 'facebook', 'value' => 'testpage', 'enabled' => true],
            ['platform' => 'instagram', 'value' => '@testaccount', 'enabled' => true],
            ['platform' => 'x', 'value' => '@testuser', 'enabled' => true],
            ['platform' => 'tiktok', 'value' => '@testcreator', 'enabled' => true],
            ['platform' => 'youtube', 'value' => 'testchannel', 'enabled' => true],
            ['platform' => 'threads', 'value' => '@testthreads', 'enabled' => true],
            ['platform' => 'linkedin', 'value' => 'testprofile', 'enabled' => true],
            ['platform' => 'pinterest', 'value' => 'testpin', 'enabled' => true],
            ['platform' => 'snapchat', 'value' => 'testsnap', 'enabled' => true],
            ['platform' => 'reddit', 'value' => 'testreddit', 'enabled' => true],
            ['platform' => 'tumblr', 'value' => 'testtumblr', 'enabled' => true],
            ['platform' => 'bluesky', 'value' => 'test.bsky.social', 'enabled' => true],
            ['platform' => 'mastodon', 'value' => '@test@mastodon.social', 'enabled' => true],
            ['platform' => 'bereal', 'value' => 'testbereal', 'enabled' => true],
            ['platform' => 'lemmy', 'value' => '@test@lemmy.ml', 'enabled' => true],
            ['platform' => 'pixelfed', 'value' => '@testpixelfed', 'enabled' => true],
            ['platform' => 'nostr', 'value' => 'npub1testnostr', 'enabled' => true],
            ['platform' => 'spacehey', 'value' => 'testspacehey', 'enabled' => true],
            ['platform' => 'vero', 'value' => 'testvero', 'enabled' => true],
            ['platform' => 'whatsapp', 'value' => '+1234567890', 'enabled' => true],
            ['platform' => 'telegram', 'value' => '@testtelegram', 'enabled' => true],
            ['platform' => 'messenger', 'value' => 'testmessenger', 'enabled' => true],
            ['platform' => 'discord', 'value' => 'testdiscord', 'enabled' => true],
            ['platform' => 'signal', 'value' => '+1234567890', 'enabled' => true],
            ['platform' => 'line', 'value' => 'testline', 'enabled' => true],
            ['platform' => 'slack', 'value' => 'testworkspace', 'enabled' => true],
            ['platform' => 'viber', 'value' => '+1234567890', 'enabled' => true],
            ['platform' => 'keybase', 'value' => 'testkeybase', 'enabled' => true],
            ['platform' => 'simplex', 'value' => 'testsimplex', 'enabled' => true],
            ['platform' => 'matrix', 'value' => '@test:matrix.org', 'enabled' => true],
            ['platform' => 'guilded', 'value' => 'testguilded', 'enabled' => true],
            ['platform' => 'threema', 'value' => 'TESTTHMA', 'enabled' => true],
            ['platform' => 'spotify', 'value' => 'testspotify', 'enabled' => true],
            ['platform' => 'apple-music', 'value' => 'testapplemusic', 'enabled' => true],
            ['platform' => 'apple-podcasts', 'value' => 'testpodcast', 'enabled' => true],
            ['platform' => 'amazon-music', 'value' => 'testamazonmusic', 'enabled' => true],
            ['platform' => 'youtube-music', 'value' => 'testyoutubemusic', 'enabled' => true],
            ['platform' => 'soundcloud', 'value' => 'testsoundcloud', 'enabled' => true],
            ['platform' => 'bandcamp', 'value' => 'testbandcamp', 'enabled' => true],
            ['platform' => 'deezer', 'value' => 'testdeezer', 'enabled' => true],
            ['platform' => 'tidal', 'value' => 'testtidal', 'enabled' => true],
            ['platform' => 'qobuz', 'value' => 'testqobuz', 'enabled' => true],
            ['platform' => 'mixcloud', 'value' => 'testmixcloud', 'enabled' => true],
            ['platform' => 'audiomack', 'value' => 'testaudiomack', 'enabled' => true],
            ['platform' => 'last-fm', 'value' => 'testlastfm', 'enabled' => true],
            ['platform' => 'google-podcasts', 'value' => 'testgooglepodcast', 'enabled' => true],
            ['platform' => 'twitch', 'value' => 'testtwitch', 'enabled' => true],
            ['platform' => 'kick', 'value' => 'testkick', 'enabled' => true],
            ['platform' => 'vimeo', 'value' => 'testvimeo', 'enabled' => true],
            ['platform' => 'dailymotion', 'value' => 'testdailymotion', 'enabled' => true],
            ['platform' => 'rumble', 'value' => 'testrumble', 'enabled' => true],
            ['platform' => 'bilibili', 'value' => 'testbilibili', 'enabled' => true],
            ['platform' => 'github', 'value' => 'testgithub', 'enabled' => true],
            ['platform' => 'gitlab', 'value' => 'testgitlab', 'enabled' => true],
            ['platform' => 'dev-to', 'value' => 'testdevto', 'enabled' => true],
            ['platform' => 'stack-overflow', 'value' => '123456', 'enabled' => true],
            ['platform' => 'hashnode', 'value' => '@testhashnode', 'enabled' => true],
            ['platform' => 'leetcode', 'value' => 'testleetcode', 'enabled' => true],
            ['platform' => 'hackerrank', 'value' => 'testhackerrank', 'enabled' => true],
            ['platform' => 'codepen', 'value' => 'testcodepen', 'enabled' => true],
            ['platform' => 'codeberg', 'value' => 'testcodeberg', 'enabled' => true],
            ['platform' => 'dribbble', 'value' => 'testdribbble', 'enabled' => true],
            ['platform' => 'behance', 'value' => 'testbehance', 'enabled' => true],
            ['platform' => 'figma', 'value' => '@testfigma', 'enabled' => true],
            ['platform' => 'artstation', 'value' => 'testartstation', 'enabled' => true],
            ['platform' => 'unsplash', 'value' => '@testunsplash', 'enabled' => true],
            ['platform' => 'vsco', 'value' => 'testvsco', 'enabled' => true],
            ['platform' => 'flickr', 'value' => 'testflickr', 'enabled' => true],
            ['platform' => 'deviantart', 'value' => 'testdeviantart', 'enabled' => true],
            ['platform' => 'steam', 'value' => 'teststeam', 'enabled' => true],
            ['platform' => 'xbox', 'value' => 'testxbox', 'enabled' => true],
            ['platform' => 'playstation', 'value' => 'testpsn', 'enabled' => true],
            ['platform' => 'vrchat', 'value' => 'testvrchat', 'enabled' => true],
            ['platform' => 'osu', 'value' => 'testosu', 'enabled' => true],
            ['platform' => 'itch-io', 'value' => 'testitchio', 'enabled' => true],
            ['platform' => 'gog', 'value' => 'testgog', 'enabled' => true],
            ['platform' => 'epic-games', 'value' => 'testepicgames', 'enabled' => true],
            ['platform' => 'roblox', 'value' => 'testroblox', 'enabled' => true],
            ['platform' => 'roll20', 'value' => 'testroll20', 'enabled' => true],
            ['platform' => 'myanimelist', 'value' => 'testmal', 'enabled' => true],
            ['platform' => 'anilist', 'value' => 'testanilist', 'enabled' => true],
            ['platform' => 'trakt', 'value' => 'testtrakt', 'enabled' => true],
            ['platform' => 'letterboxd', 'value' => 'testletterboxd', 'enabled' => true],
            ['platform' => 'goodreads', 'value' => 'testgoodreads', 'enabled' => true],
            ['platform' => 'discogs', 'value' => 'testdiscogs', 'enabled' => true],
            ['platform' => 'paypal', 'value' => 'testpaypal', 'enabled' => true],
            ['platform' => 'venmo', 'value' => '@testvenmo', 'enabled' => true],
            ['platform' => 'cash-app', 'value' => '$testcashapp', 'enabled' => true],
            ['platform' => 'patreon', 'value' => 'testpatreon', 'enabled' => true],
            ['platform' => 'ko-fi', 'value' => 'testkofi', 'enabled' => true],
            ['platform' => 'buy-me-a-coffee', 'value' => 'testbmac', 'enabled' => true],
            ['platform' => 'gofundme', 'value' => 'testgofundme', 'enabled' => true],
            ['platform' => 'kickstarter', 'value' => 'testkickstarter', 'enabled' => true],
            ['platform' => 'gumroad', 'value' => 'testgumroad', 'enabled' => true],
            ['platform' => 'redbubble', 'value' => 'testredbubble', 'enabled' => true],
            ['platform' => 'revolut', 'value' => 'testrevolut', 'enabled' => true],
            ['platform' => 'amazon', 'value' => 'testamazon', 'enabled' => true],
            ['platform' => 'etsy', 'value' => 'testetsy', 'enabled' => true],
            ['platform' => 'shop', 'value' => 'testshop', 'enabled' => true],
            ['platform' => 'fiverr', 'value' => 'testfiverr', 'enabled' => true],
            ['platform' => 'upwork', 'value' => 'testupwork', 'enabled' => true],
            ['platform' => 'appstore', 'value' => 'testappstore', 'enabled' => true],
            ['platform' => 'google-play', 'value' => 'com.test.app', 'enabled' => true],
            ['platform' => 'medium', 'value' => '@testmedium', 'enabled' => true],
            ['platform' => 'substack', 'value' => 'testsubstack', 'enabled' => true],
            ['platform' => 'notion', 'value' => 'testnotion', 'enabled' => true],
            ['platform' => 'trello', 'value' => 'testtrello', 'enabled' => true],
            ['platform' => 'obsidian', 'value' => 'testobsidian', 'enabled' => true],
            ['platform' => 'google-drive', 'value' => 'testdrive', 'enabled' => true],
            ['platform' => 'calendly', 'value' => 'testcalendly', 'enabled' => true],
            ['platform' => 'cal', 'value' => 'testcal', 'enabled' => true],
            ['platform' => 'zoom', 'value' => 'testzoom', 'enabled' => true],
            ['platform' => 'meetup', 'value' => 'testmeetup', 'enabled' => true],
            ['platform' => 'mailchimp', 'value' => 'testmailchimp', 'enabled' => true],
            ['platform' => 'wordpress', 'value' => 'testwordpress', 'enabled' => true],
            ['platform' => 'opensea', 'value' => 'testopensea', 'enabled' => true],
            ['platform' => 'xing', 'value' => 'testxing', 'enabled' => true],
            ['platform' => 'researchgate', 'value' => 'testresearchgate', 'enabled' => true],
            ['platform' => 'orcid', 'value' => '0000-0000-0000-0001', 'enabled' => true],
            ['platform' => 'google-scholar', 'value' => 'testscholar', 'enabled' => true],
            ['platform' => 'clubhouse', 'value' => '@testclubhouse', 'enabled' => true],
            ['platform' => 'cameo', 'value' => 'testcameo', 'enabled' => true],
            ['platform' => 'ngl', 'value' => 'testngl', 'enabled' => true],
            ['platform' => 'product-hunt', 'value' => '@testph', 'enabled' => true],
            ['platform' => 'kit', 'value' => 'testkit', 'enabled' => true],
            ['platform' => 'onlyfans', 'value' => 'testonlyfans', 'enabled' => true],
            ['platform' => 'strava', 'value' => 'teststrava', 'enabled' => true],
            ['platform' => 'email', 'value' => 'test@example.com', 'enabled' => true],
            ['platform' => 'phone', 'value' => '+1234567890', 'enabled' => true],
            ['platform' => 'sms', 'value' => '+1234567890', 'enabled' => true],
            ['platform' => 'website', 'value' => 'https://example.com', 'enabled' => true],
            ['platform' => 'blog', 'value' => 'https://blog.example.com', 'enabled' => true],
            ['platform' => 'link', 'value' => 'https://example.com/link', 'enabled' => true],
        ];
    }

    /**
     * Create all platform link blocks
     */
    private function createAllPlatformBlocks(BioPage $bioPage): void
    {
        $order = 0;
        // Header Text Block
        BioLink::create([
            'bio_page_id' => $bioPage->id,
            'type' => 'text',
            'title' => '📱 All Platforms Test',
            'content' => 'This page tests every single platform link and social icon available in the system.',
            'is_active' => true,
            'order' => $order++,
        ]);
        // ALL platforms as link blocks
        $allPlatforms = [
            // Social Media
            ['brand' => 'facebook', 'title' => 'Facebook', 'url' => 'https://facebook.com/test'],
            ['brand' => 'instagram', 'title' => 'Instagram', 'url' => 'https://instagram.com/test'],
            ['brand' => 'x', 'title' => 'X (Twitter)', 'url' => 'https://x.com/test'],
            ['brand' => 'threads', 'title' => 'Threads', 'url' => 'https://threads.net/@test'],
            ['brand' => 'tiktok', 'title' => 'TikTok', 'url' => 'https://tiktok.com/@test'],
            ['brand' => 'youtube', 'title' => 'YouTube', 'url' => 'https://youtube.com/@test'],
            ['brand' => 'youtube-music', 'title' => 'YouTube Music', 'url' => 'https://music.youtube.com/channel/test'],
            ['brand' => 'linkedin', 'title' => 'LinkedIn', 'url' => 'https://linkedin.com/in/test'],
            ['brand' => 'pinterest', 'title' => 'Pinterest', 'url' => 'https://pinterest.com/test'],
            ['brand' => 'snapchat', 'title' => 'Snapchat', 'url' => 'https://snapchat.com/add/test'],
            ['brand' => 'reddit', 'title' => 'Reddit', 'url' => 'https://reddit.com/u/test'],
            ['brand' => 'tumblr', 'title' => 'Tumblr', 'url' => 'https://test.tumblr.com'],
            ['brand' => 'bluesky', 'title' => 'Bluesky', 'url' => 'https://bsky.app/profile/test.bsky.social'],
            ['brand' => 'mastodon', 'title' => 'Mastodon', 'url' => 'https://mastodon.social/@test'],
            ['brand' => 'bereal', 'title' => 'BeReal', 'url' => 'https://bereal.com/test'],
            ['brand' => 'lemmy', 'title' => 'Lemmy', 'url' => 'https://lemmy.ml/u/test'],
            ['brand' => 'pixelfed', 'title' => 'Pixelfed', 'url' => 'https://pixelfed.social/test'],
            ['brand' => 'nostr', 'title' => 'Nostr', 'url' => 'https://nostr.com/test'],
            ['brand' => 'spacehey', 'title' => 'SpaceHey', 'url' => 'https://spacehey.com/profile?id=test'],
            ['brand' => 'vero', 'title' => 'Vero', 'url' => 'https://vero.co/test'],
            // Messaging
            ['brand' => 'whatsapp', 'title' => 'WhatsApp', 'url' => 'https://wa.me/1234567890'],
            ['brand' => 'telegram', 'title' => 'Telegram', 'url' => 'https://t.me/test'],
            ['brand' => 'messenger', 'title' => 'Messenger', 'url' => 'https://m.me/test'],
            ['brand' => 'discord', 'title' => 'Discord', 'url' => 'https://discord.gg/test'],
            ['brand' => 'signal', 'title' => 'Signal', 'url' => 'https://signal.me/#p/+1234567890'],
            ['brand' => 'line', 'title' => 'LINE', 'url' => 'https://line.me/ti/p/test'],
            ['brand' => 'slack', 'title' => 'Slack', 'url' => 'https://test.slack.com'],
            ['brand' => 'viber', 'title' => 'Viber', 'url' => 'viber://chat?number=1234567890'],
            ['brand' => 'keybase', 'title' => 'Keybase', 'url' => 'https://keybase.io/test'],
            ['brand' => 'simplex', 'title' => 'SimpleX', 'url' => 'https://simplex.chat/contact#test'],
            ['brand' => 'matrix', 'title' => 'Matrix', 'url' => 'https://matrix.to/#/@test:matrix.org'],
            // Music & Audio
            ['brand' => 'spotify', 'title' => 'Spotify', 'url' => 'https://open.spotify.com/artist/test'],
            ['brand' => 'apple-music', 'title' => 'Apple Music', 'url' => 'https://music.apple.com/artist/test'],
            ['brand' => 'apple-podcasts', 'title' => 'Apple Podcasts', 'url' => 'https://podcasts.apple.com/podcast/test'],
            ['brand' => 'amazon-music', 'title' => 'Amazon Music', 'url' => 'https://music.amazon.com/artists/test'],
            ['brand' => 'soundcloud', 'title' => 'SoundCloud', 'url' => 'https://soundcloud.com/test'],
            ['brand' => 'bandcamp', 'title' => 'Bandcamp', 'url' => 'https://test.bandcamp.com'],
            ['brand' => 'deezer', 'title' => 'Deezer', 'url' => 'https://deezer.com/artist/test'],
            ['brand' => 'tidal', 'title' => 'Tidal', 'url' => 'https://tidal.com/artist/test'],
            ['brand' => 'qobuz', 'title' => 'Qobuz', 'url' => 'https://open.qobuz.com/artist/test'],
            ['brand' => 'mixcloud', 'title' => 'Mixcloud', 'url' => 'https://mixcloud.com/test'],
            ['brand' => 'audiomack', 'title' => 'Audiomack', 'url' => 'https://audiomack.com/test'],
            ['brand' => 'last-fm', 'title' => 'Last.fm', 'url' => 'https://last.fm/user/test'],
            // Video & Streaming
            ['brand' => 'twitch', 'title' => 'Twitch', 'url' => 'https://twitch.tv/test'],
            ['brand' => 'kick', 'title' => 'Kick', 'url' => 'https://kick.com/test'],
            ['brand' => 'vimeo', 'title' => 'Vimeo', 'url' => 'https://vimeo.com/test'],
            // Developer
            ['brand' => 'github', 'title' => 'GitHub', 'url' => 'https://github.com/test'],
            ['brand' => 'gitlab', 'title' => 'GitLab', 'url' => 'https://gitlab.com/test'],
            ['brand' => 'dev-to', 'title' => 'DEV.to', 'url' => 'https://dev.to/test'],
            ['brand' => 'stack-overflow', 'title' => 'Stack Overflow', 'url' => 'https://stackoverflow.com/users/123/test'],
            ['brand' => 'hashnode', 'title' => 'Hashnode', 'url' => 'https://hashnode.com/@test'],
            ['brand' => 'leetcode', 'title' => 'LeetCode', 'url' => 'https://leetcode.com/test'],
            ['brand' => 'hackerrank', 'title' => 'HackerRank', 'url' => 'https://hackerrank.com/test'],
            ['brand' => 'codepen', 'title' => 'CodePen', 'url' => 'https://codepen.io/test'],
            ['brand' => 'codeberg', 'title' => 'Codeberg', 'url' => 'https://codeberg.org/test'],
            // Design & Creative
            ['brand' => 'dribbble', 'title' => 'Dribbble', 'url' => 'https://dribbble.com/test'],
            ['brand' => 'behance', 'title' => 'Behance', 'url' => 'https://behance.net/test'],
            ['brand' => 'figma', 'title' => 'Figma', 'url' => 'https://figma.com/@test'],
            ['brand' => 'artstation', 'title' => 'ArtStation', 'url' => 'https://artstation.com/test'],
            ['brand' => 'unsplash', 'title' => 'Unsplash', 'url' => 'https://unsplash.com/@test'],
            ['brand' => 'vsco', 'title' => 'VSCO', 'url' => 'https://vsco.co/test'],
            ['brand' => 'flickr', 'title' => 'Flickr', 'url' => 'https://flickr.com/photos/test'],
            // Gaming
            ['brand' => 'steam', 'title' => 'Steam', 'url' => 'https://steamcommunity.com/id/test'],
            ['brand' => 'xbox', 'title' => 'Xbox', 'url' => 'https://xbox.com/profile/test'],
            ['brand' => 'playstation', 'title' => 'PlayStation', 'url' => 'https://psnprofiles.com/test'],
            ['brand' => 'vrchat', 'title' => 'VRChat', 'url' => 'https://vrchat.com/home/user/test'],
            ['brand' => 'osu', 'title' => 'osu!', 'url' => 'https://osu.ppy.sh/users/test'],
            ['brand' => 'itch-io', 'title' => 'itch.io', 'url' => 'https://test.itch.io'],
            ['brand' => 'gog', 'title' => 'GOG', 'url' => 'https://gog.com/u/test'],
            // Entertainment
            ['brand' => 'myanimelist', 'title' => 'MyAnimeList', 'url' => 'https://myanimelist.net/profile/test'],
            ['brand' => 'anilist', 'title' => 'AniList', 'url' => 'https://anilist.co/user/test'],
            ['brand' => 'trakt', 'title' => 'Trakt', 'url' => 'https://trakt.tv/users/test'],
            ['brand' => 'letterboxd', 'title' => 'Letterboxd', 'url' => 'https://letterboxd.com/test'],
            ['brand' => 'goodreads', 'title' => 'Goodreads', 'url' => 'https://goodreads.com/user/show/test'],
            // Payment & Support
            ['brand' => 'paypal', 'title' => 'PayPal', 'url' => 'https://paypal.me/test'],
            ['brand' => 'venmo', 'title' => 'Venmo', 'url' => 'https://venmo.com/test'],
            ['brand' => 'cash-app', 'title' => 'Cash App', 'url' => 'https://cash.app/$test'],
            ['brand' => 'patreon', 'title' => 'Patreon', 'url' => 'https://patreon.com/test'],
            ['brand' => 'ko-fi', 'title' => 'Ko-fi', 'url' => 'https://ko-fi.com/test'],
            ['brand' => 'buy-me-a-coffee', 'title' => 'Buy Me a Coffee', 'url' => 'https://buymeacoffee.com/test'],
            ['brand' => 'gofundme', 'title' => 'GoFundMe', 'url' => 'https://gofundme.com/f/test'],
            ['brand' => 'kickstarter', 'title' => 'Kickstarter', 'url' => 'https://kickstarter.com/profile/test'],
            ['brand' => 'gumroad', 'title' => 'Gumroad', 'url' => 'https://test.gumroad.com'],
            ['brand' => 'redbubble', 'title' => 'Redbubble', 'url' => 'https://redbubble.com/people/test'],
            ['brand' => 'revolut', 'title' => 'Revolut', 'url' => 'https://revolut.me/test'],
            // Shopping & Business
            ['brand' => 'amazon', 'title' => 'Amazon', 'url' => 'https://amazon.com/shop/test'],
            ['brand' => 'etsy', 'title' => 'Etsy', 'url' => 'https://etsy.com/shop/test'],
            ['brand' => 'shop', 'title' => 'Shop', 'url' => 'https://shop.app/test'],
            ['brand' => 'fiverr', 'title' => 'Fiverr', 'url' => 'https://fiverr.com/test'],
            ['brand' => 'upwork', 'title' => 'Upwork', 'url' => 'https://upwork.com/freelancers/test'],
            // Productivity
            ['brand' => 'medium', 'title' => 'Medium', 'url' => 'https://medium.com/@test'],
            ['brand' => 'substack', 'title' => 'Substack', 'url' => 'https://test.substack.com'],
            ['brand' => 'notion', 'title' => 'Notion', 'url' => 'https://notion.so/test'],
            ['brand' => 'trello', 'title' => 'Trello', 'url' => 'https://trello.com/b/test'],
            ['brand' => 'google-drive', 'title' => 'Google Drive', 'url' => 'https://drive.google.com/drive/folders/test'],
            ['brand' => 'calendly', 'title' => 'Calendly', 'url' => 'https://calendly.com/test'],
            ['brand' => 'cal', 'title' => 'Cal.com', 'url' => 'https://cal.com/test'],
            ['brand' => 'zoom', 'title' => 'Zoom', 'url' => 'https://zoom.us/j/test'],
            // App Stores
            ['brand' => 'appstore', 'title' => 'App Store', 'url' => 'https://apps.apple.com/app/test'],
            ['brand' => 'google-play', 'title' => 'Google Play', 'url' => 'https://play.google.com/store/apps/details?id=com.test'],
            // Web3 & NFT
            ['brand' => 'opensea', 'title' => 'OpenSea', 'url' => 'https://opensea.io/test'],
            // Professional & Academic
            ['brand' => 'xing', 'title' => 'XING', 'url' => 'https://xing.com/profile/test'],
            ['brand' => 'researchgate', 'title' => 'ResearchGate', 'url' => 'https://researchgate.net/profile/test'],
            ['brand' => 'orcid', 'title' => 'ORCID', 'url' => 'https://orcid.org/0000-0000-0000-0000'],
            // Other
            ['brand' => 'clubhouse', 'title' => 'Clubhouse', 'url' => 'https://clubhouse.com/@test'],
            ['brand' => 'guilded', 'title' => 'Guilded', 'url' => 'https://guilded.gg/test'],
            ['brand' => 'cameo', 'title' => 'Cameo', 'url' => 'https://cameo.com/test'],
            ['brand' => 'ngl', 'title' => 'NGL', 'url' => 'https://ngl.link/test'],
            // Contact
            ['brand' => 'email', 'title' => 'Email Me', 'url' => 'mailto:test@example.com'],
            ['brand' => 'phone', 'title' => 'Call Me', 'url' => 'tel:+1234567890'],
            ['brand' => 'sms', 'title' => 'Text Me', 'url' => 'sms:+1234567890'],
            ['brand' => 'website', 'title' => 'Website', 'url' => 'https://example.com'],
            ['brand' => 'blog', 'title' => 'Blog', 'url' => 'https://blog.example.com'],
            ['brand' => 'link', 'title' => 'Custom Link', 'url' => 'https://example.com/link'],
        ];
        foreach ($allPlatforms as $platform) {
            BioLink::create([
                'bio_page_id' => $bioPage->id,
                'type' => 'link',
                'title' => $platform['title'],
                'url' => $platform['url'],
                'brand' => $platform['brand'],
                'is_active' => true,
                'order' => $order++,
            ]);
        }
        // Divider
        BioLink::create([
            'bio_page_id' => $bioPage->id,
            'type' => 'divider',
            'is_active' => true,
            'order' => $order++,
        ]);
        // Embed: Spotify Track
        BioLink::create([
            'bio_page_id' => $bioPage->id,
            'type' => 'music',
            'title' => 'Now Playing (Spotify Embed)',
            'url' => 'https://open.spotify.com/track/4cOdK2wGLETKBW3PvgPWqT',
            'embed_url' => 'https://open.spotify.com/track/4cOdK2wGLETKBW3PvgPWqT',
            'is_active' => true,
            'order' => $order++,
        ]);
        // Embed: YouTube Video
        BioLink::create([
            'bio_page_id' => $bioPage->id,
            'type' => 'video',
            'title' => 'Latest Video (YouTube Embed)',
            'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'embed_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'is_active' => true,
            'order' => $order++,
        ]);
        // Countdown Block
        BioLink::create([
            'bio_page_id' => $bioPage->id,
            'type' => 'countdown',
            'title' => 'Event Countdown',
            'countdown_date' => now()->addDays(30),
            'countdown_label' => 'Days until launch 🚀',
            'is_active' => true,
            'order' => $order++,
        ]);
    }
}
