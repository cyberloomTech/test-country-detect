<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Get user's IP address
$ip = $_SERVER['REMOTE_ADDR'];
if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
}
if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
    $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
}

// Map countries to language codes
$country_language_map = [
    'US' => 'en',
    'GB' => 'en',
    'PK' => 'en',
    'FR' => 'fr',
    'DE' => 'de',
    'ES' => 'es',
    'IT' => 'it',
    'PT' => 'pt-PT',
    'AE' => 'ar',
    'TR' => 'tr',
    'RU' => 'ru',
    'JP' => 'ja',
    'CN' => 'zh-CN',
    'MY' => 'ms'
];

// Detect country
$countryCode = 'US';
$countryName = 'United States';
$language = 'en';

try {
    $response = @file_get_contents("https://ipapi.co/$ip/json/");
    if ($response) {
        $data = json_decode($response, true);
        
        if (!empty($data['country_code'])) {
            $countryCode = $data['country_code'];
            $countryName = $data['country_name'] ?? $countryCode;
        }
    }
} catch (Exception $e) {
    // Use fallback
}

// Get language based on country
$language = $country_language_map[$countryCode] ?? 'en';

echo json_encode([
    'ip' => $ip,
    'countryCode' => $countryCode,
    'countryName' => $countryName,
    'language' => $language,
    'debug' => [
        'rawResponse' => $response ?? null,
        'apiData' => $data ?? null,
        'headers' => [
            'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'] ?? null,
            'HTTP_X_FORWARDED_FOR' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null,
            'HTTP_CF_CONNECTING_IP' => $_SERVER['HTTP_CF_CONNECTING_IP'] ?? null
        ]
    ]
]);
