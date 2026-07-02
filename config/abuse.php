<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Link screening
    |--------------------------------------------------------------------------
    |
    | Controls automated screening of destination URLs when a short link is
    | created. Screening is layered: an admin-managed domain blacklist, an
    | optional Google Safe Browsing lookup, and a tunable keyword heuristic.
    |
    | This exists so an unattended, free-to-use shortener does not silently
    | become a distribution vector for illegal or malicious content.
    |
    */

    'enabled' => env('ABUSE_SCREENING_ENABLED', true),

    /*
    | What to do when the keyword heuristic matches. Domain-blacklist and
    | Safe Browsing hits always hard-block. The heuristic is fuzzier, so by
    | default it "flags" (creates the link but holds it in a non-redirecting
    | flagged state for admin review) rather than outright blocking, to keep
    | false positives from harming legitimate users. Set to "block" for a
    | stricter posture.
    */
    'heuristic_action' => env('ABUSE_HEURISTIC_ACTION', 'flag'), // 'flag' | 'block'

    /*
    | Notify the security/admin address whenever a link is auto-blocked or
    | auto-flagged, so takedown does not depend on someone watching the
    | dashboard.
    */
    'notify_on_flag' => env('ABUSE_NOTIFY_ON_FLAG', true),

    /*
    |--------------------------------------------------------------------------
    | Google Safe Browsing (optional)
    |--------------------------------------------------------------------------
    |
    | If an API key is set, destination URLs are checked against Google's
    | Safe Browsing lists (malware, social engineering / phishing, unwanted
    | software). Leave empty to disable this layer. Free tier is generous.
    | https://developers.google.com/safe-browsing/v4/get-started
    |
    */
    'safe_browsing' => [
        'api_key' => env('SAFE_BROWSING_API_KEY'),
        'endpoint' => 'https://safebrowsing.googleapis.com/v4/threatMatches:find',
        'threat_types' => ['MALWARE', 'SOCIAL_ENGINEERING', 'UNWANTED_SOFTWARE', 'POTENTIALLY_HARMFUL_APPLICATION'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Keyword heuristic
    |--------------------------------------------------------------------------
    |
    | Case-insensitive tokens matched against the destination URL (host, path,
    | query) and the requested slug. Intended as a coarse first-pass net for
    | the most clearly-illegal categories (child sexual abuse material above
    | all). This is deliberately conservative and operator-tunable — expand it
    | for your own risk profile. It is a triage aid, NOT a substitute for the
    | abuse-report channel or law-enforcement reporting obligations.
    |
    | NOTE: matches only whole words (word-boundary), so "class" will not trip
    | on a fragment. Keep entries lowercase.
    |
    */
    'keywords' => array_filter(array_map('trim', explode(',', (string) env('ABUSE_KEYWORDS', implode(',', [
        // CSAM-indicative shorthand commonly abused to label such material.
        'childporn', 'child-porn', 'cp-video', 'pedo', 'preteen', 'jailbait', 'lolicon', 'lolita',
        'underage', 'minor-nude', 'kids-nude', 'child-abuse',
    ]))))),

    /*
    | Hosts that are never screened by the keyword heuristic (their own paths
    | can legitimately contain flagged tokens, e.g. news or reference sites).
    */
    'keyword_allowlist_hosts' => array_filter(array_map('trim', explode(',', (string) env('ABUSE_KEYWORD_ALLOWLIST_HOSTS', '')))),
];
