# PHP Country Detector

A simple PHP project that detects the user's country based on their IP address and maps it to a language code.

## Features

- Detects user IP address (supports Cloudflare & proxies)
- Uses ipapi.co API for geolocation
- Maps countries to language codes
- Clean, responsive UI
- Serverless API endpoint

## Deploy to Vercel

[![Deploy with Vercel](https://vercel.com/button)](https://vercel.com/new/clone?repository-url=https://github.com/yourusername/yourrepo)

1. Push this code to a GitHub repository
2. Go to [vercel.com](https://vercel.com)
3. Click "New Project"
4. Import your repository
5. Click "Deploy"

That's it! Vercel will automatically detect the PHP runtime.

## Local Testing

Run the built-in PHP server:
```bash
php -S localhost:8000
```

Then visit: `http://localhost:8000`

## API Endpoint

Once deployed, you can access the API at:
```
https://your-domain.vercel.app/api/detect.php
```

Returns JSON:
```json
{
  "ip": "123.456.789.0",
  "countryCode": "US",
  "countryName": "United States",
  "language": "en"
}
```

## Supported Countries

US, GB, PK, FR, DE, ES, IT, PT, AE, TR, RU, JP, CN, MY
