<?php

/**
 * Platform Domain Variations
 * 
 * Comprehensive list of valid domain variations for popular social media
 * and platform links. Used for link validation and platform detection.
 */

return [
    // Video Platforms
    'youtube' => [
        'youtube.com', 'www.youtube.com', 'youtu.be', 'm.youtube.com',
        'music.youtube.com', 'studio.youtube.com', 'tv.youtube.com',
        'gaming.youtube.com', 'kids.youtube.com', 'youtube-nocookie.com',
    ],
    'youtube-music' => ['music.youtube.com', 'youtube.com/music'],
    'vimeo' => ['vimeo.com', 'www.vimeo.com', 'player.vimeo.com', 'vimeopro.com'],
    'twitch' => ['twitch.tv', 'www.twitch.tv', 'm.twitch.tv', 'player.twitch.tv', 'clips.twitch.tv', 'go.twitch.tv'],
    'kick' => ['kick.com', 'www.kick.com'],

    // Major Social Media
    'tiktok' => ['tiktok.com', 'www.tiktok.com', 'vm.tiktok.com', 'm.tiktok.com', 'vt.tiktok.com'],
    'instagram' => ['instagram.com', 'www.instagram.com', 'instagr.am', 'ig.me', 'l.instagram.com'],
    'facebook' => [
        'facebook.com', 'www.facebook.com', 'fb.com', 'fb.me', 'm.facebook.com',
        'mobile.facebook.com', 'l.facebook.com', 'web.facebook.com', 'touch.facebook.com',
        'mbasic.facebook.com', 'business.facebook.com', 'fb.watch',
    ],
    'x' => ['x.com', 'www.x.com', 'twitter.com', 'www.twitter.com', 't.co', 'mobile.twitter.com', 'mobile.x.com'],
    'threads' => ['threads.net', 'www.threads.net'],
    'linkedin' => ['linkedin.com', 'www.linkedin.com', 'lnkd.in', 'm.linkedin.com'],
    'snapchat' => ['snapchat.com', 'www.snapchat.com', 'snap.com', 'story.snapchat.com', 't.snapchat.com'],
    'pinterest' => [
        'pinterest.com', 'www.pinterest.com', 'pin.it', 'pinterest.co.uk', 'pinterest.de',
        'pinterest.fr', 'pinterest.es', 'pinterest.ca', 'pinterest.jp', 'pinterest.com.au',
        'pinterest.com.mx', 'pinterest.pt', 'pinterest.at', 'pinterest.ch', 'pinterest.cl',
        'pinterest.nz', 'pinterest.ph', 'pinterest.se', 'pinterest.ie', 'pinterest.co.kr',
        'br.pinterest.com', 'in.pinterest.com', 'nl.pinterest.com', 'ru.pinterest.com',
    ],
    'reddit' => [
        'reddit.com', 'www.reddit.com', 'redd.it', 'old.reddit.com', 'new.reddit.com',
        'np.reddit.com', 'i.redd.it', 'v.redd.it', 'preview.redd.it', 'm.reddit.com', 'amp.reddit.com',
    ],
    'tumblr' => ['tumblr.com', 'www.tumblr.com', 'tmblr.co', 't.umblr.com'],

    // Messaging
    'discord' => ['discord.gg', 'discord.com', 'discordapp.com', 'ptb.discord.com', 'canary.discord.com', 'cdn.discordapp.com', 'media.discordapp.net'],
    'telegram' => ['t.me', 'telegram.me', 'telegram.org', 'web.telegram.org'],
    'whatsapp' => ['whatsapp.com', 'www.whatsapp.com', 'wa.me', 'api.whatsapp.com', 'chat.whatsapp.com', 'web.whatsapp.com'],
    'messenger' => ['m.me', 'messenger.com', 'www.messenger.com'],
    'signal' => ['signal.org', 'signal.me', 'signal.group'],
    'line' => ['line.me', 'lin.ee', 'page.line.me'],
    'viber' => ['viber.com', 'www.viber.com', 'invite.viber.com'],
    'slack' => ['slack.com', 'www.slack.com', 'app.slack.com'],
    'clubhouse' => ['clubhouse.com', 'joinclubhouse.com'],
    'guilded' => ['guilded.gg', 'www.guilded.gg'],
    'matrix' => ['matrix.to', 'matrix.org'],
    'simplex' => ['simplex.chat', 'www.simplex.chat'],

    // Music Platforms
    'spotify' => ['spotify.com', 'www.spotify.com', 'open.spotify.com', 'play.spotify.com', 'link.spotify.com', 'spotify.link', 'spoti.fi'],
    'apple-music' => ['music.apple.com', 'itunes.apple.com', 'geo.music.apple.com', 'embed.music.apple.com'],
    'apple-podcasts' => ['podcasts.apple.com', 'itunes.apple.com', 'embed.podcasts.apple.com'],
    'soundcloud' => ['soundcloud.com', 'www.soundcloud.com', 'on.soundcloud.com', 'm.soundcloud.com', 'api.soundcloud.com', 'w.soundcloud.com'],
    'bandcamp' => ['bandcamp.com'],
    'deezer' => ['deezer.com', 'www.deezer.com', 'dzr.page.link'],
    'tidal' => ['tidal.com', 'www.tidal.com', 'listen.tidal.com'],
    'amazon-music' => [
        'music.amazon.com', 'music.amazon.co.uk', 'music.amazon.de', 'music.amazon.com.au',
        'music.amazon.co.jp', 'music.amazon.com.br', 'music.amazon.in',
        'amazon.com/music', 'amazon.co.uk/music', 'amazon.de/music',
    ],
    'audiomack' => ['audiomack.com', 'm.audiomack.com', 'audiomack.app.link'],
    'mixcloud' => ['mixcloud.com', 'www.mixcloud.com', 'm.mixcloud.com'],
    'qobuz' => ['qobuz.com', 'www.qobuz.com', 'open.qobuz.com'],
    'last-fm' => ['last.fm', 'www.last.fm'],

    // Developer Platforms
    'github' => ['github.com', 'www.github.com', 'gist.github.com', 'raw.githubusercontent.com', 'github.io', 'pages.github.com', 'githubusercontent.com', 'objects.githubusercontent.com'],
    'gitlab' => ['gitlab.com', 'www.gitlab.com'],
    'stack-overflow' => ['stackoverflow.com', 'www.stackoverflow.com', 'stackexchange.com', 'superuser.com', 'serverfault.com', 'askubuntu.com'],
    'codepen' => ['codepen.io', 'www.codepen.io'],
    'codeberg' => ['codeberg.org', 'www.codeberg.org'],
    'dev-to' => ['dev.to'],
    'hashnode' => ['hashnode.com', 'www.hashnode.com'],
    'leetcode' => ['leetcode.com', 'www.leetcode.com', 'leetcode.cn'],
    'hackerrank' => ['hackerrank.com', 'www.hackerrank.com'],

    // Design Platforms
    'dribbble' => ['dribbble.com', 'www.dribbble.com', 'drbl.in'],
    'behance' => ['behance.net', 'www.behance.net', 'be.net'],
    'figma' => ['figma.com', 'www.figma.com'],
    'artstation' => ['artstation.com', 'www.artstation.com'],
    'unsplash' => ['unsplash.com', 'www.unsplash.com'],
    'flickr' => ['flickr.com', 'www.flickr.com', 'flic.kr'],
    'vsco' => ['vsco.co', 'www.vsco.co', 'vsco.com'],

    // Blogging/Writing
    'medium' => ['medium.com', 'www.medium.com', 'link.medium.com'],
    'substack' => ['substack.com', 'www.substack.com'],
    'notion' => ['notion.so', 'www.notion.so', 'notion.com'],

    // Creator/Support
    'patreon' => ['patreon.com', 'www.patreon.com'],
    'ko-fi' => ['ko-fi.com', 'www.ko-fi.com'],
    'buy-me-a-coffee' => ['buymeacoffee.com', 'www.buymeacoffee.com', 'bmc.link'],
    'gofundme' => ['gofundme.com', 'www.gofundme.com'],
    'kickstarter' => ['kickstarter.com', 'www.kickstarter.com'],
    'gumroad' => ['gumroad.com', 'www.gumroad.com'],
    'cameo' => ['cameo.com', 'www.cameo.com'],

    // Payment
    'paypal' => ['paypal.com', 'www.paypal.com', 'paypal.me'],
    'cash-app' => ['cash.app', 'www.cash.app', 'cash.me'],
    'venmo' => ['venmo.com', 'www.venmo.com', 'account.venmo.com'],
    'revolut' => ['revolut.me', 'revolut.com', 'www.revolut.com'],

    // E-commerce
    'amazon' => [
        'amazon.com', 'amazon.co.uk', 'amazon.de', 'amazon.fr', 'amazon.es', 'amazon.it',
        'amazon.nl', 'amazon.ca', 'amazon.com.au', 'amazon.com.br', 'amazon.in', 'amazon.co.jp',
        'amazon.com.mx', 'amazon.sg', 'amazon.ae', 'amazon.sa', 'amazon.se', 'amazon.pl',
        'amazon.com.tr', 'amazon.cn', 'amazon.eg', 'amazon.com.be', 'amzn.to', 'a.co',
    ],
    'etsy' => ['etsy.com', 'www.etsy.com', 'etsy.me'],
    'shop' => ['shop.app', 'www.shop.app'],
    'redbubble' => ['redbubble.com', 'www.redbubble.com'],

    // Gaming
    'steam' => ['steampowered.com', 'store.steampowered.com', 'steamcommunity.com', 's.team', 'help.steampowered.com', 'steamcdn-a.akamaihd.net'],
    'xbox' => ['xbox.com', 'www.xbox.com', 'account.xbox.com', 'social.xbox.com'],
    'playstation' => ['playstation.com', 'www.playstation.com', 'store.playstation.com', 'my.playstation.com'],
    'itch-io' => ['itch.io'],
    'gog' => ['gog.com', 'www.gog.com'],
    'roll20' => ['roll20.net', 'app.roll20.net'],
    'vrchat' => ['vrchat.com', 'www.vrchat.com'],
    'osu' => ['osu.ppy.sh'],

    // Entertainment
    'letterboxd' => ['letterboxd.com', 'www.letterboxd.com'],
    'goodreads' => ['goodreads.com', 'www.goodreads.com'],
    'myanimelist' => ['myanimelist.net', 'www.myanimelist.net'],
    'anilist' => ['anilist.co'],
    'trakt' => ['trakt.tv', 'www.trakt.tv'],

    // Alt Social
    'mastodon' => ['mastodon.social', 'mastodon.online', 'mas.to', 'mstdn.social', 'fosstodon.org', 'hachyderm.io', 'infosec.exchange'],
    'bluesky' => ['bsky.app', 'bsky.social', 'staging.bsky.app'],
    'lemmy' => ['lemmy.world', 'lemmy.ml', 'beehaw.org'],
    'pixelfed' => ['pixelfed.social', 'pixelfed.de', 'pixelfed.fr'],
    'nostr' => ['njump.me', 'snort.social', 'iris.to', 'primal.net', 'nos.social'],
    'bereal' => ['bere.al', 'bereal.com'],
    'spacehey' => ['spacehey.com', 'www.spacehey.com'],
    'vero' => ['vero.co', 'www.vero.co'],

    // Professional
    'fiverr' => ['fiverr.com', 'www.fiverr.com'],
    'upwork' => ['upwork.com', 'www.upwork.com'],
    'xing' => ['xing.com', 'www.xing.com'],
    'researchgate' => ['researchgate.net', 'www.researchgate.net'],
    'orcid' => ['orcid.org', 'www.orcid.org'],

    // Scheduling
    'calendly' => ['calendly.com', 'www.calendly.com'],
    'cal' => ['cal.com', 'app.cal.com'],
    'zoom' => ['zoom.us', 'zoom.com', 'zoomus.cn', 'us02web.zoom.us', 'us04web.zoom.us', 'us05web.zoom.us'],

    // Crypto/NFT
    'opensea' => ['opensea.io'],

    // Apps
    'appstore' => ['apps.apple.com', 'itunes.apple.com'],
    'google-play' => ['play.google.com', 'play.app.goo.gl'],
    'google-drive' => ['drive.google.com', 'docs.google.com', 'drive.googleusercontent.com'],
    'trello' => ['trello.com', 'www.trello.com'],
    'keybase' => ['keybase.io', 'www.keybase.io', 'keybase.pub'],
    'ngl' => ['ngl.link', 'www.ngl.link'],

    // Generic
    'email' => ['mailto:'],
    'phone' => ['tel:'],
    'sms' => ['sms:'],
    'website' => [],
    'blog' => [],
    'link' => [],
];
