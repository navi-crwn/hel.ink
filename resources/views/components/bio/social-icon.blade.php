@props(['platform', 'class' => 'h-5 w-5'])
@php
// Map platforms to icon filenames
$iconMap = [
    'instagram' => 'instagram.svg',
    'twitter' => 'twitter.svg',
    'facebook' => 'facebook.svg',
    'youtube' => 'youtube.svg',
    'tiktok' => 'tiktok.svg',
    'linkedin' => 'linkedin.svg',
    'github' => 'github.svg',
    'whatsapp' => 'whatsapp.svg',
    'telegram' => 'telegram.svg',
    'twitch' => 'twitch.svg',
    'spotify' => 'spotify.svg',
    'snapchat' => 'snapchat.svg',
    'threads' => 'threads.svg',
    'airchat' => 'airchat.svg',
    'email' => 'email.svg',
];
$iconFile = $iconMap[$platform] ?? 'email.svg'; // default fallback
$iconPath = asset('images/social-icons/' . $iconFile);
@endphp
<img src="{{ $iconPath }}" {{ $attributes->merge(['class' => $class]) }} alt="{{ $platform }} icon" />
