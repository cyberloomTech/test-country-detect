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
$response = null;
$data = null;
$error = null;

try {
    // Use ip-api.com - free with better rate limits (45 requests/minute)
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://ip-api.com/json/$ip?fields=status,message,country,countryCode,query");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);
    
    if ($response && $httpCode == 200) {
        $data = json_decode($response, true);
        
        if (!empty($data['countryCode']) && $data['status'] === 'success') {
            $countryCode = $data['countryCode'];
            $countryName = $data['country'] ?? $countryCode;
        }

    } else {
        $error = "HTTP $httpCode - $curlError";
    }
} catch (Exception $e) {
    $error = $e->getMessage();
}

// Get language based on country
$language = $country_language_map[$countryCode] ?? 'en';

// Set cookies (removed before JSON output)
// Cookies will be set by JavaScript on the frontend instead

echo json_encode([
    'ip' => $ip,
    'countryCode' => $countryCode,
    'countryName' => $countryName,
    'language' => $language,
    'setCookies' => true, // Signal to frontend to set cookies
    'debug' => [
        'rawResponse' => $response,
        'apiData' => $data,
        'error' => $error,
        'apiUrl' => "http://ip-api.com/json/$ip",
        'apiProvider' => 'ip-api.com (45 req/min free)',
        'headers' => [
            'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'] ?? null,
            'HTTP_X_FORWARDED_FOR' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null,
            'HTTP_CF_CONNECTING_IP' => $_SERVER['HTTP_CF_CONNECTING_IP'] ?? null
        ]
    ]
]);
