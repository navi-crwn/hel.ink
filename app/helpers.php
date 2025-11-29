<?php

if (!function_exists('country_flag')) {
    function country_flag(?string $countryCode): string
    {
        if (!$countryCode || strlen($countryCode) !== 2) {
            return '<span class="inline-block w-5 h-4" title="Unknown">🌍</span>';
        }

        $code = strtolower($countryCode);
        $countryName = country_name(strtoupper($countryCode));
        $localPath = public_path("images/flags/{$code}.svg");
        if (file_exists($localPath)) {
            $url = asset("images/flags/{$code}.svg");
            return '<img src="' . $url . '" 
                         width="20" 
                         height="15"
                         alt="' . $countryName . '" 
                         title="' . $countryName . '"
                         class="inline-block rounded"
                         style="border-radius: 2px;">';
        }
        return '<img src="https://flagcdn.com/w20/' . $code . '.png" 
                     srcset="https://flagcdn.com/w40/' . $code . '.png 2x" 
                     width="20" 
                     alt="' . $countryName . '" 
                     title="' . $countryName . '"
                     class="inline-block rounded">';
    }
}

if (!function_exists('country_name')) {
    function country_name(?string $countryCode): string
    {
        if (!$countryCode) {
            return 'Unknown';
        }

        $countries = [
            'US' => 'United States',
            'ID' => 'Indonesia',
            'GB' => 'United Kingdom',
            'CA' => 'Canada',
            'AU' => 'Australia',
            'DE' => 'Germany',
            'FR' => 'France',
            'JP' => 'Japan',
            'CN' => 'China',
            'IN' => 'India',
            'BR' => 'Brazil',
            'RU' => 'Russia',
            'SG' => 'Singapore',
            'MY' => 'Malaysia',
            'TH' => 'Thailand',
            'PH' => 'Philippines',
            'VN' => 'Vietnam',
            'NL' => 'Netherlands',
            'IT' => 'Italy',
            'ES' => 'Spain',
        ];

        return $countries[strtoupper($countryCode)] ?? $countryCode;
    }
}
