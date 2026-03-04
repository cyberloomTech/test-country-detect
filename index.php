<?php
// Get user's IP address
$ip = $_SERVER['REMOTE_ADDR'];
if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
    // Cloudflare users
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
$countryCode = 'US'; // fallback
$countryName = 'United States';
$language = 'en';

try {
    $response = file_get_contents("https://ipapi.co/$ip/json/");
    $data = json_decode($response, true);
    
    if (!empty($data['country_code'])) {
        $countryCode = $data['country_code'];
        $countryName = $data['country_name'] ?? $countryCode;
    }
} catch (Exception $e) {
    $countryCode = 'US';
}

// Get language based on country
$language = $country_language_map[$countryCode] ?? 'en';
?>
<!DOCTYPE html>
<html lang="<?php echo $language; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Country Detector</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-top: 0;
        }
        .info {
            margin: 15px 0;
            padding: 10px;
            background: #f0f0f0;
            border-radius: 4px;
        }
        .label {
            font-weight: bold;
            color: #666;
        }
        .value {
            color: #333;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>🌍 Country Detection</h1>
        
        <div class="info">
            <div class="label">Your IP Address:</div>
            <div class="value"><?php echo htmlspecialchars($ip); ?></div>
        </div>
        
        <div class="info">
            <div class="label">Detected Country:</div>
            <div class="value"><?php echo htmlspecialchars($countryName); ?> (<?php echo htmlspecialchars($countryCode); ?>)</div>
        </div>
        
        <div class="info">
            <div class="label">Language Code:</div>
            <div class="value"><?php echo htmlspecialchars($language); ?></div>
        </div>
    </div>
</body>
</html>
