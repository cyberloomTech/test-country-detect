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

// Country name mapping
$country_name_map = [
    'US' => 'United States',
    'GB' => 'United Kingdom',
    'PK' => 'Pakistan',
    'FR' => 'France',
    'DE' => 'Germany',
    'ES' => 'Spain',
    'IT' => 'Italy',
    'PT' => 'Portugal',
    'AE' => 'United Arab Emirates',
    'TR' => 'Turkey',
    'RU' => 'Russia',
    'JP' => 'Japan',
    'CN' => 'China',
    'MY' => 'Malaysia'
];

// Detect country using Vercel's geolocation headers
$countryCode = 'US'; // fallback
$countryName = 'United States';
$language = 'en';
$detectionMethod = 'fallback';
$geoipData = [];

// Vercel provides geolocation in request headers
// https://vercel.com/docs/edge-network/headers#x-vercel-ip-country
if (isset($_SERVER['HTTP_X_VERCEL_IP_COUNTRY'])) {
    $countryCode = $_SERVER['HTTP_X_VERCEL_IP_COUNTRY'];
    $detectionMethod = 'Vercel Geolocation (HTTP_X_VERCEL_IP_COUNTRY)';
    $geoipData['HTTP_X_VERCEL_IP_COUNTRY'] = $_SERVER['HTTP_X_VERCEL_IP_COUNTRY'];
    
    // Vercel also provides city, region, latitude, longitude
    if (isset($_SERVER['HTTP_X_VERCEL_IP_CITY'])) {
        $geoipData['HTTP_X_VERCEL_IP_CITY'] = $_SERVER['HTTP_X_VERCEL_IP_CITY'];
    }
    if (isset($_SERVER['HTTP_X_VERCEL_IP_COUNTRY_REGION'])) {
        $geoipData['HTTP_X_VERCEL_IP_COUNTRY_REGION'] = $_SERVER['HTTP_X_VERCEL_IP_COUNTRY_REGION'];
    }
} elseif (isset($_SERVER['GEOIP_COUNTRY_CODE'])) {
    $countryCode = $_SERVER['GEOIP_COUNTRY_CODE'];
    $detectionMethod = 'GEOIP_COUNTRY_CODE';
    $geoipData['GEOIP_COUNTRY_CODE'] = $_SERVER['GEOIP_COUNTRY_CODE'];
} elseif (isset($_SERVER['HTTP_CF_IPCOUNTRY'])) {
    // Cloudflare provides country code
    $countryCode = $_SERVER['HTTP_CF_IPCOUNTRY'];
    $detectionMethod = 'HTTP_CF_IPCOUNTRY (Cloudflare)';
    $geoipData['HTTP_CF_IPCOUNTRY'] = $_SERVER['HTTP_CF_IPCOUNTRY'];
} elseif (isset($_SERVER['HTTP_X_COUNTRY_CODE'])) {
    $countryCode = $_SERVER['HTTP_X_COUNTRY_CODE'];
    $detectionMethod = 'HTTP_X_COUNTRY_CODE';
    $geoipData['HTTP_X_COUNTRY_CODE'] = $_SERVER['HTTP_X_COUNTRY_CODE'];
}

// Get country name from mapping
$countryName = $country_name_map[$countryCode] ?? $countryCode;

// Get language based on country
$language = $country_language_map[$countryCode] ?? 'en';

echo json_encode([
    'ip' => $ip,
    'countryCode' => $countryCode,
    'countryName' => $countryName,
    'language' => $language,
    'setCookies' => true,
    'debug' => [
        'detectionMethod' => $detectionMethod,
        'geoipData' => $geoipData,
        'allServerVars' => [
            'HTTP_X_VERCEL_IP_COUNTRY' => $_SERVER['HTTP_X_VERCEL_IP_COUNTRY'] ?? null,
            'HTTP_X_VERCEL_IP_CITY' => $_SERVER['HTTP_X_VERCEL_IP_CITY'] ?? null,
            'HTTP_X_VERCEL_IP_COUNTRY_REGION' => $_SERVER['HTTP_X_VERCEL_IP_COUNTRY_REGION'] ?? null,
            'HTTP_X_VERCEL_IP_LATITUDE' => $_SERVER['HTTP_X_VERCEL_IP_LATITUDE'] ?? null,
            'HTTP_X_VERCEL_IP_LONGITUDE' => $_SERVER['HTTP_X_VERCEL_IP_LONGITUDE'] ?? null,
            'GEOIP_COUNTRY_CODE' => $_SERVER['GEOIP_COUNTRY_CODE'] ?? null,
            'GEOIP_COUNTRY_NAME' => $_SERVER['GEOIP_COUNTRY_NAME'] ?? null,
            'HTTP_CF_IPCOUNTRY' => $_SERVER['HTTP_CF_IPCOUNTRY'] ?? null,
            'HTTP_X_COUNTRY_CODE' => $_SERVER['HTTP_X_COUNTRY_CODE'] ?? null,
            'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'] ?? null,
            'HTTP_X_FORWARDED_FOR' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null,
            'HTTP_CF_CONNECTING_IP' => $_SERVER['HTTP_CF_CONNECTING_IP'] ?? null
        ]
    ]
]);
